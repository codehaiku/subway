<?php

namespace Subway\Memberships\Plan\Pricing;

use Subway\Memberships\Plan\Plan;

/**
 * Class Pricing
 * @package Subway\Memberships\Plan\Pricing
 */
class Pricing extends Plan {

	protected $id = '';
	protected $plan_id = '';
	protected $plan = null;
	protected $billing_cycle_frequency = '';
	protected $billing_cycle_period = '';
	protected $billing_limit = '';
	protected $has_trial = false;
	protected $trial_frequency = '';
	protected $trial_period = '';
	protected $date_created = '';
	protected $date_updated = '';

	var $table = null;

	public function __construct() {
		parent::__construct();
		$this->table = $this->db->prefix . 'subway_memberships_products_plans_pricing';
	}

	/**
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function set_id( $id ) {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function get_plan_id() {
		return $this->plan_id;
	}

	/**
	 * @param string $plan_id
	 */
	public function set_plan_id( $plan_id ) {
		$this->plan_id = $plan_id;
	}

	/**
	 * @param $id
	 * @param string $status
	 *
	 * @return bool|mixed|Plan
	 */
	public function get_plan( $id, $status = 'public' ) {

		if ( ! empty( $this->get_plan_id() ) ) {
			$id = $this->get_plan_id();
		}

		return $this->get_plan( $id, $status = 'public' );

	}

	/**
	 * @param null $plan
	 */
	public function set_plan( $plan ) {
		$this->plan = $plan;
	}

	/**
	 * @return string
	 */
	public function get_billing_cycle_frequency() {
		return $this->billing_cycle_frequency;
	}

	/**
	 * @param string $billing_cycle_frequency
	 */
	public function set_billing_cycle_frequency( $billing_cycle_frequency ) {
		$this->billing_cycle_frequency = $billing_cycle_frequency;
	}

	/**
	 * @return string
	 */
	public function get_billing_cycle_period() {
		return $this->billing_cycle_period;
	}

	/**
	 * @param string $billing_cycle_period
	 */
	public function set_billing_cycle_period( $billing_cycle_period ) {
		$this->billing_cycle_period = $billing_cycle_period;
	}

	/**
	 * @return string
	 */
	public function get_billing_limit() {
		return $this->billing_limit;
	}

	/**
	 * @param string $billing_limit
	 */
	public function set_billing_limit( $billing_limit ) {
		$this->billing_limit = $billing_limit;
	}

	/**
	 * @return bool
	 */
	public function is_has_trial() {
		return $this->has_trial;
	}

	/**
	 * @param bool $has_trial
	 */
	public function set_has_trial( $has_trial ) {
		$this->has_trial = $has_trial;
	}

	/**
	 * @return string
	 */
	public function get_trial_frequency() {
		return $this->trial_frequency;
	}

	/**
	 * @param string $trial_frequency
	 */
	public function set_trial_frequency( $trial_frequency ) {
		$this->trial_frequency = $trial_frequency;
	}

	/**
	 * @return string
	 */
	public function get_trial_period() {
		return $this->trial_period;
	}

	/**
	 * @param string $trial_period
	 */
	public function set_trial_period( $trial_period ) {
		$this->trial_period = $trial_period;
	}

	/**
	 * @return string
	 */
	public function get_date_created() {
		return $this->date_created;
	}

	/**
	 * @param string $date_created
	 */
	public function set_date_created( $date_created ) {
		$this->date_created = $date_created;
	}

	/**
	 * @return string
	 */
	public function get_date_updated() {
		return $this->date_updated;
	}

	/**
	 * @param string $date_updated
	 */
	public function set_date_updated( $date_updated ) {
		$this->date_updated = $date_updated;
	}
}