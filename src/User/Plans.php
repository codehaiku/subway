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
			'txn_id'       => '',
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

	/**
	 * @param $user_id
	 *
	 * @return array
	 */
	public function get_user_plans( $user_id ) {

		$r    = new \stdClass();
		$plan = new Plan();

		$stmt    = $this->get_user_plans_query( $user_id );
		$results = $this->wpdb->get_results( $stmt, OBJECT );

		$plans = [];
		$user  = [];

		if ( ! empty( $results ) ) {
			foreach ( $results as $result ) {
				$r->result = $result;
				$r->user   = $user;
				$r->plan   = $plan->get_plan( $result->plan_id );
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

	private function get_user_plans_query( $user_id ) {

		$orders_table = $this->wpdb->prefix . 'subway_memberships_orders';

		// User Plans Fields.
		$fields = [
			"{$this->table}.id",
			"{$this->table}.user_id",
			"{$this->table}.plan_id",
			"{$this->table}.product_id",
			"{$this->table}.txn_id",
			"{$this->table}.status",
			"{$this->table}.trial_status",
			"{$this->table}.notes",
			"{$this->table}.updated",
			"{$this->table}.created",
			"{$this->table}.trial_ending"
		];

		// Order Fields.
		$orders = [
			"{$orders_table}.id as orders_id",
			"{$orders_table}.user_id as orders_user_id",
			"{$orders_table}.plan_id as orders_plan_id",
			"{$orders_table}.txn_id as orders_txn_id",
			"{$orders_table}.recorded_plan_name as orders_recorded_plan_name",
			"{$orders_table}.invoice_number as orders_invoice_number",
			"{$orders_table}.status as orders_status",
			"{$orders_table}.amount as orders_amount",
			"{$orders_table}.tax_rate as orders_tax_rate",
			"{$orders_table}.customer_vat_number as orders_customer_vat_number",
			"{$orders_table}.currency as orders_currency",
			"{$orders_table}.gateway as orders_gateway",
			"{$orders_table}.ip_address as orders_ip_address",
			"{$orders_table}.created as orders_created",
			"{$orders_table}.last_updated as orders_last_updated"
		];

		$fields = implode( ',', array_merge( $fields, $orders ) );

		return $this->wpdb->prepare(
			"SELECT $fields FROM $this->table 
			INNER JOIN $orders_table 
			ON {$this->table}.txn_id = {$orders_table}.txn_id
			WHERE {$this->table}.user_id = %d
			AND {$this->table}.txn_id <> ''
			ORDER BY id DESC
			",
			$user_id
		);

	}

}