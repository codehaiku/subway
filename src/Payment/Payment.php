<?php

namespace Subway\Payment;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use Subway\Memberships\Products\Products;

class Payment {

	protected $gateway = "paypal";

	protected $return_url = "";

	protected $cancel_url = "";

	protected $wpdb = '';

	private $api_context = "";

	private $quantity = 1;

	function __construct( \wpdb $wpdb ) {

		$confirmation_id = absint( get_option( 'subway_options_account_page' ) );

		$cancel_id = absint( get_option( 'subway_paypal_page_cancel' ) );

		$confirmation_url = get_permalink( $confirmation_id );

		$cancel_url = get_permalink( $cancel_id );

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

	/**
	 * @param int $product_id
	 *
	 * @return bool|\PayPal\Api\Payment
	 */
	public function pay( $product_id = 0 ) {

		if ( empty( $product_id ) ) {

			return false;

		}

		$products = new Products();

		$product = $products->get_product( $product_id );

		if ( empty( $product ) ) {

			return false;

		}

		// @TODO: Assign valid tax rate.
		$tax_rate = 12;

		$price        = $product->amount;
		$quantity     = $this->quantity;

		$tax          = $price * ( $tax_rate / 100 );
		$subtotal     = $price * $quantity;

		$name         = $product->name;
		$currency     = get_option( 'subway_currency', 'USD' );

		$sku          = $product->sku;

		$redirect_url = add_query_arg( 'success', 'true', $this->return_url );
		$cancel_url   = add_query_arg( 'success', 'fail', $this->cancel_url );

		// @TODO: Assign valid invoice number.
		$invoice_number = uniqid();

		$description = sprintf( __( 'Payment for: %s', 'subway' ), $product->name );

		try {

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

			$total = $subtotal + $tax;

			$details->setTax( $tax )
			        ->setSubtotal( $subtotal );

			$amount = new Amount();

			$amount->setCurrency( $currency )
			       ->setTotal( $total )
			       ->setDetails( $details );

			$transaction = new Transaction();

			$transaction->setAmount( $amount )
			            ->setCustom( $product->id )
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


		} catch ( \Exception $e ) {
			//@Todo: Assign valid return url.
			echo '<pre>';
				echo $e->getMessage();
			echo '</pre>';
			die;
		}

		try {

			$payment->create( $this->api_context );

		} catch ( \Exception $ex ) {
			//@Todo: Assign valid return url.
			echo '<pre>';
				echo $ex->getMessage();
			echo '</pre>';
			die;
		}

		header( "location: " . $payment->getApprovalLink() );

		return $payment;

	}

	/**
	 * Confirms the payment made by the user.
	 */
	public function confirm() {

		if ( isset( $_GET['paymentId'] ) && isset( $_GET['success'] ) && $_GET['success'] == 'true' ) {

			$paymentId = $_GET['paymentId'];
			$payment = \PayPal\Api\Payment::get($paymentId, $this->api_context);

			$execution = new PaymentExecution();
			$execution->setPayerId($_GET['PayerID']);

			$payment_id = $_GET['paymentId'];

			try {

				$result = $payment->execute($execution, $this->api_context);

				$payment = \PayPal\Api\Payment::get( $payment_id, $this->api_context );

				$product_id = $payment->getTransactions()[0]->getCustom();

				$added_order = $this->wpdb->insert(

					$this->wpdb->prefix . 'subway_memberships_orders',
					array(
						'product_id'      => $product_id,
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

				if ( $added_order ) {

					// Update the user meta.
					update_user_meta( get_current_user_id(), 'subway_user_membership_product_id', $product_id );

					// Update orders count.
					$count_orders = absint( get_option( 'subway_count_orders', 0) );
					update_option( 'subway_count_orders', $count_orders += 1 );

					// Redirect user to the right page.
					wp_safe_redirect(
						add_query_arg( 'welcome', get_current_user_id(), $this->return_url ),
						302
					);


				} else {

					// Redirect user to the right page.
					wp_safe_redirect(
						add_query_arg( 'new_order', 'fail_to_add', $this->cancel_url ),
						302
					);
				}
			} catch ( \Exception $e ) {
				// Log error here.
			}
		}
	}


}