<?php

namespace Subway\Checkout;

use Subway\Payment\Payment;


class Checkout {

	protected $wpdb;

	public function __construct( \wpdb $wpdb ) {

		$this->wpdb = $wpdb;

	}

	public function pay() {

		$action = filter_input( INPUT_POST, 'sw-action', 516 );

		$product_id = filter_input( INPUT_POST, 'sw-product-id', 519 );

		if ( 'checkout' === $action ) {

			$payment = new Payment( $this->wpdb );

			$payment->pay( $product_id );

		}

		return $this;

	}

	public function attach_hooks() {

		$this->define_hooks();

	}

	protected function define_hooks() {

		add_action( 'wp', [ $this, 'pay' ] );

	}
}