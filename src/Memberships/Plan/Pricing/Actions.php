<?php

namespace Subway\Memberships\Plan\Pricing;

use Subway\Helpers\Helpers;

class Actions extends Controller {

	public function edit_pricing() {

		$plan_id                    = filter_input( 0, 'plan_id', 519 );
		$billing_cycle_number       = filter_input( 0, 'billing-cycle-number', 519 );
		$billing_cycle_period       = filter_input( 0, 'billing-cycle-period', 513 );
		$billing_limit              = filter_input( 0, 'billing-limit', 519 );
		$trial_billing_cycle_number = filter_input( 0, 'trial-billing-cycle-number', 519 );
		$trial_billing_cycle_period = filter_input( 0, 'trial-billing-cycle-period', 513 );
		$trial_amount               = filter_input( 0, 'trial-amount', 520, FILTER_FLAG_ALLOW_FRACTION );
		$free_trial                 = filter_input( 0, 'free-trial', 513 );
		$has_trial                  = false;

		if ( ! empty( $free_trial ) ) {
			$has_trial = true;
		}

		$this->set_plan_id( $plan_id );
		$this->set_billing_cycle_frequency( $billing_cycle_number );
		$this->set_billing_cycle_period( $billing_cycle_period );
		$this->set_billing_limit( $billing_limit );
		$this->set_trial_frequency( $trial_billing_cycle_number );
		$this->set_trial_period( $trial_billing_cycle_period );
		$this->set_has_trial( $has_trial );
		$this->set_trial_amount( $trial_amount );

		$updated = $this->set();

		return $updated;

	}

	public function attach_hooks() {
		add_action( 'subway_membership_plans_edit_after', [ $this, 'edit_pricing' ] );
	}
}