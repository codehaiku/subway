<?php

namespace Subway\Payment;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

class Payment {

	protected $gateway = "paypal";
	protected $return_url = "";
	protected $cancel_url = "";
	protected $wpdb = '';
	private $api_context = "";

	function __construct( \wpdb $wpdb ) {

		$confirmation_id = absint( get_option( 'subway_options_account_page' ) );
		$cancel_id       = absint( get_option( 'subway_paypal_page_cancel' ) );

		$confirmation_url =  get_permalink( $confirmation_id );
		$cancel_url       =  get_permalink( $cancel_id );

		$this->return_url = $confirmation_url;
		$this->cancel_url = $cancel_url;

		$this->api_context = new \PayPal\Rest\ApiContext(
			new \PayPal\Auth\OAuthTokenCredential(
				get_option( 'subway_paypal_client_id' ),     // Client ID.
				get_option( 'subway_paypal_client_secret' )  // Client Secret.
			)
		);

		$this->wpdb = $wpdb;

	}

	public function pay() {


		$tax_rate = 12;
		$price    = 89.99;
		$quantity = 1;

		$tax      = $price * ( $tax_rate / 100 );
		$subtotal = $price * $quantity;

		$name     = "Subway Pro Plan";
		$currency = "USD";
		$sku      = "sku_stupid23412";

		$redirect_url = add_query_arg( 'success', 'true', $this->return_url );
		$cancel_url   = add_query_arg( 'success', 'fail', $this->cancel_url );

		$invoice_number = uniqid();
		$description    = "Payment for Subway Pro Plan";

		// -------

		$payer = new Payer();
		$payer->setPaymentMethod( "paypal" );

		$item1 = new Item();

		$item1->setName( $name )
		      ->setCurrency( $currency )
		      ->setQuantity( $quantity )
		      ->setSku( $sku ) // Similar to `item_number` in Classic API
		      ->setPrice( $price );

		$itemList = new ItemList();

		$itemList->setItems( array( $item1 ) );

		$details = new Details();

		$total = number_format( $subtotal + $tax, 2 );

		$details->setTax( $tax )
		        ->setSubtotal( $subtotal );

		$amount = new Amount();

		$amount->setCurrency( $currency )
		       ->setTotal( $total )
		       ->setDetails( $details );

		$transaction = new Transaction();

		$transaction->setAmount( $amount )
		            ->setItemList( $itemList )
		            ->setDescription( $description )
		            ->setInvoiceNumber( $invoice_number );

		$redirectUrls = new RedirectUrls();
		$redirectUrls->setReturnUrl( $redirect_url )
		             ->setCancelUrl( $cancel_url );

		$payment = new \PayPal\Api\Payment();
		$payment->setIntent( "sale" )
		        ->setPayer( $payer )
		        ->setRedirectUrls( $redirectUrls )
		        ->setTransactions( array( $transaction ) );

		$request = clone $payment;

		try {
			$payment->create( $this->api_context );
		} catch ( Exception $ex ) {
			echo '<pre>';
			print_r( $ex );
			echo '</pre>';
			exit( 1 );
		}


		header( "location: " . $payment->getApprovalLink() );

		return $payment;

	}

	public function confirm() {

		if ( isset( $_GET['paymentId']) && isset( $_GET['success'] ) && $_GET['success'] == 'true' ) {

			$payment_id = $_GET['paymentId'];

			try {

				$payment = \PayPal\Api\Payment::get( $payment_id, $this->api_context );

				$inserted = $this->wpdb->insert(

					$this->wpdb->prefix . 'subway_memberships_orders',
					array(
						'product_id'      => 999,
						'user_id'         => get_current_user_id(),
						'status'          => $payment->getState(),
						'amount'          => $payment->getTransactions()[0]->getAmount()->getTotal(),
						'gateway'         => $this->gateway,
						'gateway_details' => serialize( array() ),
						'created'         => $payment->getCreateTime(),
						'last_updated'    => current_time( 'mysql' )
					),
					array(
						'%d', // Product ID.
						'%d', // User ID.
						'%s', // Status.
						'%f', // Amount.
						'%s', // Gateway.
						'%s', // Serialized array(),
						'%s', // Created.
						'%s', // Last Updated.
					)
				);

				if ( $inserted ) {
					wp_safe_redirect(
						add_query_arg('welcome', get_current_user_id(), $this->return_url ),
						302
					);
				} else {
					wp_safe_redirect(
						add_query_arg('new_order', 'fail_to_add', $this->cancel_url ),
						302
					);
				}
			} catch ( \Exception $e ) {
				// Log error here.
			}
		}
	}


}