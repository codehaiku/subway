<?php

namespace Subway\Checkout;

use Subway\Helpers\Helpers;
use Subway\Memberships\Plan\Plan;
use Subway\Payment\Payment;


class Checkout {

	protected $db = null;
	protected $plan = null;
	protected $pricing = null;

	protected $price = 0.00;
	protected $subtotal = 0.00;
	protected $tax_rate = 0.00;
	protected $total = 0.00;

	protected $is_trial = false;

	public function __construct() {
		$this->db = Helpers::get_db();
	}

	/**
	 * @return null
	 */
	public function get_plan() {
		return $this->plan;
	}

	/**
	 * @param null $plan
	 */
	public function set_plan( $plan ) {
		$this->plan = $plan;
	}

	/**
	 * @return null
	 */
	public function get_pricing() {
		return $this->pricing;
	}

	/**
	 * @param null $pricing
	 */
	public function set_pricing( $pricing ) {
		$this->pricing = $pricing;
	}

	/**
	 * @param bool $tax_included
	 *
	 * @return float
	 */
	public function get_price( $tax_included = false ) {

		$plan = $this->get_plan();

		$pricing = $plan->get_pricing();
		$this->set_price( $plan->get_price( $tax_included ) );

		if ( $this->is_trial() ) {
			$this->set_price( $pricing->get_trial_price( $tax_included ) );
		}

		return $this->price;

	}

	/**
	 * @param float $price
	 */
	public function set_price( $price ) {
		$this->price = $price;
	}

	/**
	 * @return float
	 */
	public function get_subtotal() {
		// Copy price in the meantime.
		$this->set_subtotal( $this->get_price() );

		return $this->subtotal;
	}

	/**
	 * @param float $subtotal
	 */
	public function set_subtotal( $subtotal ) {
		$this->subtotal = $subtotal;
	}

	/**
	 * @return float
	 */
	public function get_tax_rate() {
		$this->set_tax_rate( $this->plan->get_tax_rate() );

		return $this->tax_rate;
	}

	/**
	 * @param $tax_rate
	 */
	protected function set_tax_rate( $tax_rate ) {
		$this->tax_rate = $tax_rate;
	}

	/**
	 * @return float
	 */
	public function get_total() {
		$this->set_total( $this->get_price( true ) );

		return $this->total;
	}

	/**
	 * @param float $total
	 */
	public function set_total( $total ) {
		$this->total = $total;
	}

	/**
	 * @return bool
	 */
	public function is_trial() {
		return $this->is_trial;
	}

	/**
	 * @param bool $is_trial
	 */
	public function set_is_trial( $is_trial ) {
		$this->is_trial = $is_trial;
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

			$payment = new Payment( $this->db );

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