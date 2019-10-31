<?php

namespace Subway\Checkout;

use Subway\Memberships\Plan\Plan;
use Subway\Payment\Payment;


class Checkout {

	protected $wpdb;

	public function __construct( \wpdb $wpdb ) {

		$this->wpdb = $wpdb;

	}

	public function pay() {

		$action = filter_input( INPUT_POST, 'sw-action', 516 );

		$plan_id = filter_input( INPUT_POST, 'sw-plan-id', 519 );

		$plan = new Plan();

		$plan = $plan->get_plan( $plan_id );

		if ( ! $plan ) {
			return $this;
		}

		if ( 'published' !== $plan->get_status() ) {
			return $this;
		}

		if ( 'checkout' === $action ) {

			$payment = new Payment( $this->wpdb );

			$payment->pay( $plan_id );

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