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

	function __construct() {

	}

	public function pay() {

		$apiContext = new \PayPal\Rest\ApiContext(
			new \PayPal\Auth\OAuthTokenCredential(
				'AciSvB2plla180-h3nG6h4NGe-IZYmVREghoOKBYOo79cHW3unLT2xLAvD9Dur0InymirnhcKzEDuyoI',     // ClientID
				'EG5QfZRgSKyzylpdDR0YdZg91XTdqZ8Qg1EOoaxuH4s1pAuxSI69WHT0cRYCqFvjQFpxzsfESR3AOoDi'      // ClientSecret
			)
		);

		$tax_rate = 12;
		$price    = 89.99;
		$quantity = 1;

		$tax      = $price * ( $tax_rate / 100 );
		$subtotal = $price * $quantity;

		$name     = "Subway Pro Plan";
		$currency = "USD";
		$sku      = "sku_stupid23412";

		$redirect_url = "http://multisite.local/nibble";
		$cancel_url   = "http://multisite.local/nibble";

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
		$payment->setIntent("sale")
		        ->setPayer($payer)
		        ->setRedirectUrls($redirectUrls)
		        ->setTransactions(array($transaction));

		$request = clone $payment;

		try {
			$payment->create( $apiContext );
		} catch ( Exception $ex ) {
			echo '<pre>';
			print_r( $ex );
			echo '</pre>';
			exit( 1 );
		}


		header("location: " . $payment->getApprovalLink() );

		return $payment;

	}

	public function confirm() {

	}

}