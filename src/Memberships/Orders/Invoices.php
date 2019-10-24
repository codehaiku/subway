<?php

namespace Subway\Memberships\Orders;

use Subway\Helpers\Helpers;
use Subway\Memberships\Plan\Plan;

class Invoices {

	protected $id = 0;
	protected $user = 0;
	protected $wpdb = '';
	protected $table_orders = '';
	protected $table_orders_details = '';

	public function __construct( \wpdb $wpdb ) {

		$this->wpdb                 = $wpdb;
		$this->table_orders         = $this->wpdb->prefix . 'subway_memberships_orders';
		$this->table_orders_details = $this->wpdb->prefix . 'subway_memberships_orders_details';

		return $this;

	}

	/**
	 * @return string
	 */
	public function get_user() {
		return $this->user;
	}

	/**
	 * @param integer $user
	 *
	 * @return Invoices
	 */
	public function set_user( $user ) {
		$this->user = $user;

		return $this;
	}

	/**
	 * @return int
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @param int $id
	 *
	 * @return Invoices
	 */
	public function set_id( $id ) {
		$this->id = $id;

		return $this;
	}

	/**
	 * Retrieve the order and the order details to provide
	 * data for Invoice layout.
	 *
	 * @return array
	 */
	public function get_user_invoice() {

		$invoice = [];

		$stmt = $this->wpdb->prepare(
			"SELECT * FROM $this->table_orders WHERE id = %d AND user_id = %d",
			$this->get_id(),
			$this->get_user()
		);

		// Get the order
		$order = $this->wpdb->get_row( $stmt, OBJECT );

		$details       = new Details( Helpers::get_db() );
		$order_details = $details->get( $order->id );

		$invoice['order']   = $order;
		$invoice['details'] = $order_details;

		return $invoice;

	}

	public function get_user_invoices() {

		$user = $this->user;

		$stmt = $this->wpdb->prepare(
			"SELECT * FROM $this->table_orders WHERE user_id = %d AND status = %s",
			$this->get_user(), 'approved'
		);

		$results = $this->wpdb->get_results( $stmt, OBJECT );

		$invoices = [];

		if ( ! empty ( $results ) ) {
			foreach ( $results as $result ) {
				array_push( $invoices, $result );
			}
		}

		return $invoices;
	}

}