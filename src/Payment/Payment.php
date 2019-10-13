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
use Subway\Helpers\Helpers;
use Subway\Memberships\Orders\Details as OrderDetails;
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

		$price    = $product->amount;
		$quantity = $this->quantity;

		$tax      = $price * ( $tax_rate / 100 );
		$subtotal = $price * $quantity;

		$name     = $product->name;
		$currency = get_option( 'subway_currency', 'USD' );

		$sku = $product->sku;

		$redirect_url = esc_url( add_query_arg( 'success', 'true', $this->return_url ) );
		$cancel_url   = esc_url( add_query_arg( 'success', 'fail', $this->cancel_url ) );

		// Generate Invoice Number.
		$prefix = apply_filters(
			'subway\payment.pay.invoice_number_prefix',
			get_option( 'subway_invoice_prefix', 'WPBXM' )
		);

		$user_id        = get_current_user_id();
		$combination    = date( 'y' ) . date( 'd' ) . date( 'H' ) . date( 'i' ) . date( 's' );
		$invoice        = sprintf( '%s-%s-%s', $prefix, $user_id, $combination );
		$invoice_number = apply_filters( 'subway\payment.pay.invoice_number', $invoice );

		// Add description.
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
			$payment   = \PayPal\Api\Payment::get( $paymentId, $this->api_context );

			$execution = new PaymentExecution();
			$execution->setPayerId( $_GET['PayerID'] );

			$payment_id = $_GET['paymentId'];

			try {

				$result = $payment->execute( $execution, $this->api_context );

				$payment = \PayPal\Api\Payment::get( $payment_id, $this->api_context );

				$product_id = $payment->getTransactions()[0]->getCustom();

				$added_order = $this->wpdb->insert(

					$this->wpdb->prefix . 'subway_memberships_orders',
					array(
						'product_id'     => $product_id,
						'user_id'        => get_current_user_id(),
						'invoice_number' => $payment->getTransactions()[0]->getInvoiceNumber(),
						'status'         => $payment->getState(),
						'amount'         => $payment->getTransactions()[0]->getAmount()->getTotal(),
						'currency'       => $payment->getTransactions()[0]->getAmount()->getCurrency(),
						'gateway'        => $this->gateway,
						'ip_address'     => Helpers::get_ip_address(),
						'created'        => $payment->getCreateTime(),
						'last_updated'   => current_time( 'mysql' )
					),
					array(
						'%d', // Product ID.
						'%d', // User ID.
						'%s', // Invoice No.
						'%s', // Status.
						'%f', // Amount.
						'%s', // Currency.
						'%s', // Gateway.
						'%s', // Ip Address.
						'%s', // Created.
						'%s', // Last Updated.
					)
				);

				$last_order_id = $this->wpdb->insert_id;

				if ( $added_order ) {

					// Update the user meta.
					update_user_meta( get_current_user_id(), 'subway_user_membership_product_id', $product_id );

					// Update orders count.
					$count_orders = absint( get_option( 'subway_count_orders', 0 ) );

					update_option( 'subway_count_orders', $count_orders += 1 );

					// Update order details.
					$payer = $payment->getPayer()->getPayerInfo();

					$billing_address = $payment->getPayer()->getPayerInfo()->getBillingAddress();

					// Use the shipping address if the billing is empty.
					if ( empty( $billing_address ) ) {

						$billing_address = $payment->getTransactions()[0]->getItemList()->getShippingAddress();

					}

					$order_details_args = [
						'order_id'                        => $last_order_id,
						'gateway_name'                    => 'PAYPAL',
						'gateway_customer_name'           => $payer->getFirstName(),
						'gateway_customer_lastname'       => $payer->getLastName(),
						'gateway_customer_email'          => $payer->getEmail(),
						'gateway_customer_address_line_1' => $billing_address->getLine1(),
						'gateway_customer_address_line_2' => $billing_address->getLine2(),
						'gateway_customer_postal_code'    => $billing_address->getPostalCode(),
						'gateway_customer_city'           => $billing_address->getCity(),
						'gateway_customer_country'        => $billing_address->getCountryCode(),
						'gateway_customer_state'          => $billing_address->getState(),
						'gateway_customer_phone_number'   => $payer->getPhone(),
						'gateway_transaction_created'     => $payment->getCreateTime()
					];

					$order_details = new OrderDetails( $this->wpdb );

					$ordered = $order_details->add( $order_details_args );

					if ( true === $ordered ) {
						// Redirect user to the right page.
						wp_safe_redirect(
							esc_url( add_query_arg( 'welcome', get_current_user_id(), $this->return_url ),
								302
							) );
					} else {
						wp_safe_redirect(
							esc_url( add_query_arg( 'new_order', 'fail_to_add_details', $this->cancel_url ),
								302
							) );
					}

				} else {

					// Redirect user to the right page.
					wp_safe_redirect(
						esc_url( add_query_arg( 'new_order', 'fail_to_add', $this->cancel_url ),
							302
						) );
				}
			} catch ( \Exception $e ) {
				// Log error here.
			}
		}
	}


}