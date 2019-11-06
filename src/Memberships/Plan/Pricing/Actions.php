<?php

namespace Subway\Memberships\Plan\Pricing;

use Subway\Helpers\Helpers;

class Actions extends Controller {

	public function edit_pricing() {
		echo '@TODO// Define pricing here.';
		Helpers::debug( $_POST );
		die;
	}

	public function attach_hooks() {
		add_action( 'subway_membership_plans_edit_after', [ $this, 'edit_pricing' ] );
	}
}