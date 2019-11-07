<?php

namespace Subway\Memberships\Plan\Pricing;

use Subway\Currency\Currency;
use Subway\Memberships\Plan\Plan;
use Subway\Options\Options;

class Controller extends Pricing {

	const format = [];

	public function __construct() {

		parent::__construct();

	}

	public function get_trial_checkout_url( $plan_id ) {

		$options = new Options();

		return add_query_arg( [ 'plan_id' => $plan_id, 'is_trial' => 'true' ], $options->get_checkout_page_url() );

	}

	public function get_trial_info() {

		$currency = new Currency();

		$i18             = __( 'Start %d %s trial for %s &rarr;', 'subway' );
		$trial_amount    = doubleval( $this->get_trial_amount() );
		$displayed_price = $currency->format( $trial_amount, get_option( 'subway_currency', 'USD' ) );

		$period    = $this->get_trial_period();
		$frequency = absint( $this->get_trial_frequency() );

		if ( 1 === $frequency ) {
			$period = str_replace( 's', '', $period );
		}

		if ( 0 === $trial_amount ) {
			$displayed_price = __( 'free.', 'subway' );
		}

		$text = sprintf( $i18, $frequency, $period, $displayed_price );

		return apply_filters( __METHOD__, $text, $period, $frequency, $displayed_price );

	}

	public function get_text( Plan $plan ) {

		$period = $this->get_billing_cycle_period();

		$frequency = absint( $this->get_billing_cycle_frequency() );

		$i18 = 'One-time Payment';

		if ( 'recurring' === $plan->get_type() ) {
			$i18 = _n( 'Billed every %2$s', 'Billed every %1$d %2$s', $frequency );
		}

		if ( 1 === $frequency ) {
			$period = str_replace( 's', '', $period );
		}

		$text = sprintf( $i18, $frequency, $period );

		return apply_filters( __METHOD__, $text, $period, $frequency );

	}

	public function set( $args = [] ) {

		if ( empty ( $this->get_plan_id() ) ) {
			return new \Exception( __( 'Controller::set plan_id should not be empty.', 'subway' ) );
		}

		$defaults = [
			'plan_id'                 => $this->get_plan_id(),
			'billing_cycle_frequency' => $this->get_billing_cycle_frequency(),
			'billing_cycle_period'    => $this->get_billing_cycle_period(),
			'billing_limit'           => $this->get_billing_limit(),
			'has_trial'               => $this->is_has_trial(),
			'trial_frequency'         => $this->get_trial_frequency(),
			'trial_amount'            => $this->get_trial_amount(),
			'trial_period'            => $this->get_trial_period(),
		];

		$args = wp_parse_args( $args, $defaults );

		// Get the pricing.
		$this->set_plan_id( $args['plan_id'] );

		// Check if the record exists.
		if ( $this->get() ) {
			// Update if it does.
			$args['date_updated'] = current_time( 'mysql' );
			if ( false !== $this->db->update( $this->table, $args, [ 'plan_id' => $this->get_plan_id() ] ) ) {
				return true;
			}
		} else {
			// Otherwise, insert new data.
			$args['date_created'] = current_time( 'mysql' );
			$args['date_updated'] = current_time( 'mysql' );

			if ( $this->db->insert( $this->table, $args ) ) {
				return true;
			}
		}

		return false;

	}

	public function get( $populate_default = false ) {

		$stmt = $this->db->prepare( "SELECT * FROM $this->table WHERE plan_id = %d", $this->get_plan_id() );

		$result = $this->db->get_row( $stmt, OBJECT );

		if ( $populate_default ) {
			// Defaults.
			$this->set_plan_id( 0 );
			$this->set_billing_cycle_frequency( 1 );
			$this->set_billing_cycle_period( 'months' );
			$this->set_billing_limit( 0 );
			$this->set_has_trial( true );
			$this->set_trial_frequency( 7 );
			$this->set_trial_period( 'days' );
			$this->set_trial_amount( 0.00 );
			$this->set_date_created( current_time( 'mysql' ) );
			$this->set_date_updated( current_time( 'mysql' ) );
		}

		// Overwrite defaults if result is not empty.
		if ( ! empty( $result ) ) {

			$this->set_plan_id( $result->plan_id );
			$this->set_billing_cycle_frequency( $result->billing_cycle_frequency );
			$this->set_billing_cycle_period( $result->billing_cycle_period );
			$this->set_billing_limit( $result->billing_limit );
			$this->set_has_trial( $result->has_trial );
			$this->set_trial_frequency( $result->trial_frequency );
			$this->set_trial_period( $result->trial_period );
			$this->set_trial_amount( $result->trial_amount );
			$this->set_date_created( $result->date_created );
			$this->set_date_updated( $result->date_updated );

		} else {
			if ( $populate_default ) {
				return $this;
			} else {
				return false;
			}
		}

		return $this;
	}

}