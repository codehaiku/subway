<?php

namespace Subway\User;

use Subway\Memberships\Products\Products;

class Plans {

	protected $id;
	protected $user_id;
	protected $product_id;
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
	public function get_product_id() {
		return $this->product_id;
	}

	/**
	 * @param mixed $product_id
	 *
	 * @return Plans
	 */
	public function set_product_id( $product_id ) {
		$this->product_id = $product_id;

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
			'prod_id'      => '',
			'status'       => 'pending',
			'trial_status' => 'none',
			'notes'        => '',
			'updated'      => current_time( 'mysql' ),
			'created'      => current_time( 'mysql' ),
		];

		$r = wp_parse_args( $args, $defaults );

		$inserted = $this->wpdb->insert( $this->table, $r, $format );

		if ( $inserted ) {

			return $this->wpdb->insert_id;

		}

		return $this->wpdb->last_error;

	}

	public function get_user_plans( $user_id ) {

		$stmt = $this->wpdb->prepare( "SELECT * FROM $this->table WHERE user_id = %d", $user_id );

		$results = $this->wpdb->get_results( $stmt, OBJECT );

		$plans = [];

		if ( ! empty( $results ) ) {

			foreach ( $results as $result ) {

				$product = new Products();

				$p = $product->get_product( $result->prod_id );

				$plans[] = $p;

			}

		}

		return $plans;
	}

}