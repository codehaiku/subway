<?php

namespace Subway\Memberships\Plan\Pricing;

class Controller extends Pricing {

	const format = [];

	public function __construct() {
		parent::__construct();
	}

	public function set( $args = [] ) {

		$defaults = [
			'plan_id'                 => $this->get_plan_id(),
			'billing_cycle_frequency' => $this->get_billing_cycle_frequency(),
			'billing_cycle_period'    => $this->get_billing_cycle_period(),
			'billing_limit'           => $this->get_billing_limit(),
			'has_trial'               => $this->is_has_trial(),
			'trial_frequency'         => $this->get_trial_frequency(),
			'trial_period'            => $this->get_trial_period(),
		];

		$args = wp_parse_args( $args, $defaults );

		// Get the pricing.
		$this->set_plan_id( $args['plan_id'] );

		// Check if the record exists.
		if ( $this->get() ) {
			// Update if it does.
			if ( false !== $this->db->update( $this->table, $args, [ 'id' => $this->get_id() ] ) ) {
				return true;
			}
		} else {
			// Otherwise, insert new data.
			if ( $this->db->insert( $this->table, $args ) ) {
				return true;
			}
		}

		return false;

	}


	public function get() {

		$stmt = $this->db->prepare( "SELECT * FROM $this->table WHERE plan_id = %d", $this->get_plan_id() );

		$result = $this->db->get_row( $stmt, OBJECT );

		if ( empty( $result ) ) {
			return false;
		} else {
			$this->set_billing_cycle_frequency( $result->set_billing_cycle_frequency );
			$this->set_billing_cycle_period( $result->set_billing_cycle_period );
			$this->set_billing_limit( $result->set_billing_limit );
			$this->set_has_trial( $result->set_has_trial );
			$this->set_trial_frequency( $result->set_trial_frequency );
			$this->set_trial_period( $result->set_trial_period );
		}

		return true;
	}

}