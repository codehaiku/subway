<?php
namespace Subway\Hooks;

use Subway\Payment\Payment;

class Hooks {

	public function confirm_order() {
		global $wpdb;
		$payment = new Payment( $wpdb );
		$payment->confirm();
	}

	public function listen() {
		add_action('wp', array( $this, 'confirm_order' ) );
	}
}