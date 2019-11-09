<?php

namespace Subway\User;

use Subway\Helpers\Helpers;
use Subway\Memberships\Plan\Plan;

class Plans {

	protected $id;
	protected $user_id;
	protected $plan_id;
	protected $status;
	protected $trial_status;
	protected $notes;
	protected $updated;
	protected $created;
	protected $wpdb;
	protected $table;

	public function __construct( \wpdb $wpdb ) {

		$this->wpdb  = $wpdb;
		$this->table = $this->wpdb->prefix . 'subway_memberships_users_plans';

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 *
	 * @return Plans
	 */
	public function set_id( $id ) {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_user_id() {
		return $this->user_id;
	}

	/**
	 * @param mixed $user_id
	 *
	 * @return Plans
	 */
	public function set_user_id( $user_id ) {
		$this->user_id = $user_id;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_plan_id() {
		return $this->plan_id;
	}

	/**
	 * @param mixed $plan_id
	 *
	 * @return Plans
	 */
	public function set_product_id( $plan_id ) {
		$this->plan_id = plan_id;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_status() {
		return $this->status;
	}

	/**
	 * @param mixed $status
	 *
	 * @return Plans
	 */
	public function set_status( $status ) {
		$this->status = $status;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_trial_status() {
		return $this->trial_status;
	}

	/**
	 * @param mixed $trial_status
	 *
	 * @return Plans
	 */
	public function set_trial_status( $trial_status ) {
		$this->trial_status = $trial_status;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_notes() {
		return $this->notes;
	}

	/**
	 * @param mixed $notes
	 *
	 * @return Plans
	 */
	public function set_notes( $notes ) {
		$this->notes = $notes;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_updated() {
		return $this->updated;
	}

	/**
	 * @param mixed $updated
	 *
	 * @return Plans
	 */
	public function set_updated( $updated ) {
		$this->updated = $updated;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_created() {
		return $this->created;
	}

	/**
	 * @param mixed $created
	 *
	 * @return Plans
	 */
	public function set_created( $created ) {

		$this->created = $created;

		return $this;

	}


	public function add( $args ) {

		$defaults = [
			'user_id'      => '',
			'plan_id'      => '',
			'product_id'   => '',
			'status'       => 'pending',
			'trial_status' => 'none',
			'trial_ending' => '',
			'notes'        => '',
			'updated'      => current_time( 'mysql' ),
			'created'      => current_time( 'mysql' ),
		];

		$r = wp_parse_args( $args, $defaults );

		$inserted = $this->wpdb->insert( $this->table, $r );

		if ( $inserted ) {

			return $this->wpdb->insert_id;

		}

		return $this->wpdb->last_error;

	}

	public function get_users_by_product( $product_id ) {

		$users = [];
		$index = 0;

		$plan     = new \Subway\Memberships\Plan\Plan();
		$plans    = $plan->get_plans( [ 'product_id' => $product_id ] );
		$plan_ids = [];

		if ( $plans ) {
			foreach ( $plans as $plan ) {
				array_push( $plan_ids, $plan->get_id() );
			}
		}

		$plan_ids = esc_sql( implode( ',', $plan_ids ) );

		$stmt = $this->wpdb->prepare( "SELECT * FROM $this->table WHERE plan_id IN({$plan_ids}) AND id > %d", $index );

		$results = $this->wpdb->get_results( $stmt, OBJECT );

		if ( ! empty( $results ) ) {

			foreach ( $results as $result ) {

				$plan = new Plan();
				$plan = $plan->get_plan( $result->plan_id );

				$r         = new \stdClass();
				$r->result = $result;
				$r->user   = [];
				$r->plan   = $plan;

				array_push( $users, $r );
			}

		}

		return $users;

	}

	public function get_user_plans( $user_id ) {

		$stmt = $this->wpdb->prepare( "SELECT * FROM $this->table WHERE user_id = %d ORDER BY id DESC", $user_id );

		$results = $this->wpdb->get_results( $stmt, OBJECT );


		$plans = [];

		if ( ! empty( $results ) ) {

			foreach ( $results as $result ) {

				$r = new \stdClass();

				$plan = new Plan();
				$plan = $plan->get_plan( $result->plan_id );

				$user = [];

				$r->result = $result;
				$r->user   = $user;
				$r->plan   = $plan;

				array_push( $plans, $r );

			}

		}

		return $plans;

	}

	/**
	 * Cancels the user plan by changing the user plan status to 'cancelled'.
	 *
	 * @param $user_id
	 * @param $plan_id
	 *
	 * @return bool
	 */
	public function cancel_user_plan( $user_id, $plan_id ) {

		$db = $this->wpdb;

		$data = [ 'status' => 'cancelled' ];

		$where = [ 'user_id' => $user_id, 'plan_id' => $plan_id ];

		$format = [ '%s' ];

		$where_format = [ '%d', '%d' ];

		$cancelled = $db->update( $this->table, $data, $where, $format, $where_format );

		if ( false === $cancelled ) {
			return false;
		}

		return true;

	}

}