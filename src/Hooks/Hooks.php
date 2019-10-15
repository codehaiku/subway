<?php

namespace Subway\Hooks;

use Subway\Options\Options;
use Subway\Payment\Payment;

class Hooks {

	public function confirm_order() {

		global $wpdb;

		$payment = new Payment( $wpdb );

		$payment->confirm();

	}

	public function edit_profile_url( $link, $user ) {

		$options = new Options();

		$accounts_url = $options->get_accounts_page_url();

		$link = add_query_arg( 'account-page', 'update-personal-information', $accounts_url );

		return $link;

	}

	public function listen() {

		add_action( 'wp', array( $this, 'confirm_order' ) );

		add_filter( 'get_edit_user_link', array( $this, 'edit_profile_url' ), 10, 2 );

	}

}