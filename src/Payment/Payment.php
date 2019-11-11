<?php

namespace Subway\Payment;

use PayPal\Api\Agreement;
use PayPal\Api\Amount;
use PayPal\Api\BillingInfo;
use PayPal\Api\Currency;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Api\Payer;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Common\PayPalModel;
use Subway\Checkout\Checkout;
use Subway\Helpers\Helpers;
use Subway\Memberships\Orders\Details as OrderDetails;
use Subway\Memberships\Plan\Plan;
use Subway\Memberships\Product\Controller;
use Subway\Memberships\Product\Product;
use Subway\Options\Options;
use Subway\User\Plans;

class Payment {

	protected $plan = null;

	protected $return_url = '';

	protected $cancel_url = '';

	private $api_context = null;

	function __construct( Plan $plan ) {

		$this->plan = $plan;

		$confirmation_id = absint( get_option( 'subway_options_account_page' ) );
		$cancel_id       = absint( get_option( 'subway_paypal_page_cancel' ) );

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

		$options = new Options();

		$plan = new \PayPal\Api\Plan();

		$redirect_url = esc_url_raw( add_query_arg( 'success', 'true', $this->return_url ) );
		$cancel_url   = esc_url_raw( add_query_arg( 'success', 'false', $this->cancel_url ) );

		$plan->setName( 'T-Shirt of the Month Club Plan' )
		     ->setDescription( 'Template creation.' )
		     ->setType( 'fixed' );

		$paymentDefinitions = [];

		$paymentDefinition = new PaymentDefinition();
		$paymentDefinition->setName( 'Regular Payments' )
		                  ->setType( 'REGULAR' )
		                  ->setFrequency( 'MONTH' )
		                  ->setFrequencyInterval( "1" )
		                  ->setCycles( "24" )
		                  ->setAmount( new Currency( array( 'value' => 89, 'currency' => 'USD' ) ) );

		array_push( $paymentDefinitions, $paymentDefinition );

		$trialDefinition = new PaymentDefinition();
		$trialDefinition->setName( 'Trial Payments' )
		                  ->setType( 'TRIAL' )
		                  ->setFrequency( 'DAY' )
		                  ->setFrequencyInterval( "7" )
		                  ->setCycles( "1" )
		                  ->setAmount( new Currency( array( 'value' => 0, 'currency' => 'USD' ) ) );

		if ( $trialDefinition ) {
			array_push( $paymentDefinitions, $trialDefinition );
		}

		$merchantPreferences = new MerchantPreferences();

		$merchantPreferences->setReturnUrl( $redirect_url )
		                    ->setCancelUrl( $cancel_url )
		                    ->setAutoBillAmount( "yes" )
		                    ->setInitialFailAmountAction( "CONTINUE" )
		                    ->setMaxFailAttempts( "0" )
		                    ->setSetupFee( new Currency( array( 'value' => 89, 'currency' => 'USD' ) ) );

		$plan->setPaymentDefinitions( $paymentDefinitions );
		$plan->setMerchantPreferences( $merchantPreferences );

		$request = clone $plan;

		try {
			$output = $plan->create( $this->api_context );

		} catch ( \PayPal\Exception\PayPalConnectionException $e ) {
			echo $e->getData();
			exit( 1 );
		}

		// echo 'success'; echo '<br>';
		// Helpers::debug( $request );
		// Helpers::debug( $output );
		$created_plan = \PayPal\Api\Plan::get( $output->getId(), $this->api_context );

		try {

			$patch = new Patch();
			$value = new PayPalModel( '{"state": "ACTIVE"}' );
			$patch->setOp( 'replace' )->setPath( '/' )->setValue( $value );

			$patchRequest = new PatchRequest();
			$patchRequest->addPatch( $patch );

			$created_plan->update( $patchRequest, $this->api_context );

			$created_plan = \PayPal\Api\Plan::get( $created_plan->getId(), $this->api_context );

		} catch ( \Exception $ex ) {
			die( 'error' );
		}

		$start_date = date( 'c', strtotime( 'now + 1 minutes' ) );

		$agreement = new Agreement();

		$agreement->setName( 'Basic Agreement' )
		          ->setDescription( 'Basic Agreement' )
		          ->setStartDate( $start_date );

		$plan = new \PayPal\Api\Plan();
		$plan->setId( $created_plan->getId() );
		$agreement->setPlan( $plan );

		$payer = new Payer();
		$payer->setPaymentMethod( 'paypal' );

		$agreement->setPayer( $payer );

		try {
			$agreement = $agreement->create( $this->api_context );
		} catch ( \PayPal\Exception\PayPalConnectionException $e ) {
			echo $e->getData();
			echo 'error';
			die;
		}

		echo 'Created Billing Agreement. Please visit the URL to Approve';

		wp_redirect( $agreement->getApprovalLink(), 302 );

		die;

		return $output;

		$paypal = new PayPal( $this->plan );

		$paypal->create_payment();

	}

	/**
	 * Confirms the payment made by the user.
	 */
	public function confirm() {
		if ( isset( $_GET['success'] ) && $_GET['success'] == 'true' ) {
			$token     = $_GET['token'];
			$agreement = new \PayPal\Api\Agreement();
			try {
				$agreement->execute( $token, $this->api_context );
			} catch ( \Exception $ex ) {

			}
		}

		return;
		$paypal = new PayPal( $this->plan );

		$paypal->confirm_payment();

	}

	private function error_redirect_url( $url ) {

		wp_safe_redirect(
			esc_url( add_query_arg( 'new_order', 'fail_to_add_details', $url ),
				302
			) );

		exit;
	}


}