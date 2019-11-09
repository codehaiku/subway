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
use Subway\Checkout\Checkout;
use Subway\Helpers\Helpers;
use Subway\Memberships\Orders\Details as OrderDetails;
use Subway\Memberships\Plan\Plan;
use Subway\Memberships\Product\Controller;
use Subway\Memberships\Product\Product;
use Subway\User\Plans;

class Payment {

	const gateway = "paypal";
	const invoice_prefix = "SUBWAY";

	protected $return_url = "";
	protected $cancel_url = "";
	protected $db = null;

	private $api_context = null;
	private $quantity = 1;

	protected $plan = null;

	function __construct( Plan $plan ) {

		$this->db   = Helpers::get_db();
		$this->plan = $plan;

		$confirmation_id  = absint( get_option( 'subway_options_account_page' ) );
		$cancel_id        = absint( get_option( 'subway_paypal_page_cancel' ) );
		$confirmation_url = get_permalink( $confirmation_id );
		$cancel_url       = get_permalink( $cancel_id );

		$this->return_url = $confirmation_url;
		$this->cancel_url = $cancel_url;

		$this->api_context = new \PayPal\Rest\ApiContext(
			new \PayPal\Auth\OAuthTokenCredential(
				get_option( 'subway_paypal_client_id' ),     // Client ID.
				get_option( 'subway_paypal_client_secret' )  // Client Secret.
			)
		);

		return $this;

	}


	public function pay() {

		$is_trial = filter_input( 1, 'is_trial', 516 );

		$checkout = new Checkout();
		$checkout->set_plan( $this->plan );

		$redirect_url = esc_url_raw( add_query_arg( 'success', 'true', $this->return_url ) );
		$cancel_url   = esc_url_raw( add_query_arg( 'success', 'false', $this->cancel_url ) );

		// Check to see if its a trial or not.
		if ( ! empty( $is_trial ) ) {

			$redirect_url = esc_url_raw( add_query_arg( 'is_trial', 'true', $redirect_url ) );
			$cancel_url   = esc_url_raw( add_query_arg( 'is_trial', 'true', $cancel_url ) );

			$checkout->set_is_trial( true );

		}

		if ( ! $this->plan ) {
			return false;
		}

		$plan           = $checkout->get_plan();
		$tax_rate       = $plan->get_tax_rate();
		$price          = $checkout->get_price( false, false );
		$quantity       = $this->quantity;
		$tax            = $price * ( $tax_rate / 100 );
		$subtotal       = $price * $quantity;
		$name           = $plan->get_name();
		$sku            = $plan->get_sku();
		$currency       = get_option( 'subway_currency', 'USD' );
		$prefix         = apply_filters( __METHOD__ . '-invoice', get_option( 'subway_invoice_prefix', self::invoice_prefix ) );
		$user_id        = get_current_user_id();
		$combination    = date( 'y' ) . date( 'd' ) . date( 'H' ) . date( 'i' ) . date( 's' );
		$invoice        = sprintf( '%s-%s-%s', $prefix, $user_id, $combination );
		$invoice_number = apply_filters( __METHOD__ . '-invoice-number', $invoice );

		// Add description.
		$description = sprintf( __( 'Payment for: %s', 'subway' ), $plan->get_name() );

		try {

			$payer = new Payer();
			$payer->setPaymentMethod( self::gateway );

			$item = new Item();

			$item->setName( $name )
			     ->setCurrency( $currency )
			     ->setQuantity( $quantity )
			     ->setSku( $sku ) // Similar to `item_number` in Classic API
			     ->setPrice( $price );

			$itemList = new ItemList();

			$itemList->setItems( array( $item ) );

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
			            ->setCustom( $plan->get_id() )
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

			$is_trial = filter_input( 1, 'is_trial', 516 );

			$paymentId = $_GET['paymentId'];
			$payment   = \PayPal\Api\Payment::get( $paymentId, $this->api_context );

			$execution = new PaymentExecution();
			$execution->setPayerId( $_GET['PayerID'] );

			$payment_id = $_GET['paymentId'];

			try {

				$payment->execute( $execution, $this->api_context );

				$payment = \PayPal\Api\Payment::get( $payment_id, $this->api_context );

				$plan_id = $payment->getTransactions()[0]->getCustom();

				$plan = new Plan();
				$plan = $plan->get_plan( $plan_id );

				$plan_name = '';
				$tax_rate  = 0;

				if ( $plan ) {
					// Set plan product id.
					$plan->set_product_id( $plan->get_product_id() );
					// Format product and plan.
					$plan_name = sprintf( "%s - %s", $plan->get_product_link(), $plan->get_name() );
					// Get the corresponding product.
					$product = $plan->get_product();
					// Get tax rate.
					if ( $product ) {
						$tax_rate = $product->get_tax_rate();
					}
				}

				$added_order = $this->db->insert(
					$this->db->prefix . 'subway_memberships_orders',
					array(
						'plan_id'            => $plan_id,
						'recorded_plan_name' => strip_tags( $plan_name ),
						'user_id'            => get_current_user_id(),
						'invoice_number'     => $payment->getTransactions()[0]->getInvoiceNumber(),
						'status'             => $payment->getState(),
						'amount'             => $payment->getTransactions()[0]->getAmount()->getTotal(),
						'tax_rate'           => $tax_rate,
						'currency'           => $payment->getTransactions()[0]->getAmount()->getCurrency(),
						'gateway'            => self::gateway,
						'ip_address'         => Helpers::get_ip_address(),
						'created'            => $payment->getCreateTime(),
						'last_updated'       => current_time( 'mysql' )
					),
					array(
						'%d', // Plan ID.
						'%s', // Recorded Plan Name.
						'%d', // User ID.
						'%s', // Invoice No.
						'%s', // Status.
						'%f', // Amount.
						'%f', // Tax Rate.
						'%s', // Currency.
						'%s', // Gateway.
						'%s', // Ip Address.
						'%s', // Created.
						'%s', // Last Updated.
					)
				);

				$last_order_id = $this->db->insert_id;

				if ( $added_order ) {

					// Update the user meta.
					update_user_meta( get_current_user_id(), 'subway_user_membership_plan_id', $plan_id );

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

					$order_details = new OrderDetails( $this->db );

					// Create new order detail.
					$ordered = $order_details->add( $order_details_args );

					// Create new user plan.
					$user_plans = new Plans( $this->db );

					// Get the Plan's product id.
					$plans   = new \Subway\Memberships\Plan\Controller();
					$plan    = $plans->get_plan( $plan_id );
					$pricing = $plan->get_pricing();

					$product_id = $plan->get_product_id();

					// Actually insert the plan into users plan table.
					$user_plan_args = [
						'user_id'      => get_current_user_id(),
						'product_id'   => $product_id,
						'plan_id'      => $plan_id,
						'status'       => 'active',
						'trial_status' => 'none'
					];

					if ( ! empty( $is_trial ) ) {

						if ( $pricing ) {

							$pricing->set_plan( $plan );

							$trial_duration = sprintf( "+ %d %s", $pricing->get_trial_frequency(), $pricing->get_trial_period() );

							$user_plan_args['trial_status'] = 'active';

							$user_plan_args['trial_ending'] = strtotime( current_time( 'mysql' ) . $trial_duration );

						}
					}

					$user_plan_added = $user_plans->add( $user_plan_args );

					if ( ! is_numeric( $user_plan_added ) ) {
						$this->error_redirect_url( $this->cancel_url );
					}

					if ( true === $ordered ) {
						// Redirect user to the right page.
						wp_safe_redirect(
							esc_url( add_query_arg( 'welcome', get_current_user_id(), $this->return_url ),
								302
							) );
					} else {

						$this->error_redirect_url( $this->cancel_url );
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

	private function error_redirect_url( $url ) {

		wp_safe_redirect(
			esc_url( add_query_arg( 'new_order', 'fail_to_add_details', $url ),
				302
			) );

		exit;
	}


}