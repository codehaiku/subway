<?php

namespace Subway\Memberships\Orders;

use Subway\Helpers\Helpers;

class Orders {

	protected $order;

	protected $table = '';

	protected $db = null;

	public function __construct() {

		$this->db = Helpers::get_db();

		$this->table = $this->db->prefix . 'subway_memberships_orders';

		return $this;

	}

	/**
	 * Returns the url for trashing orders.
	 *
	 * @param $order_id
	 *
	 * @return string
	 */
	public function get_cancel_url( $order_id ) {

		$action = 'subway_cancel_order';

		return add_query_arg( [
			'order-id' => $order_id,
			'_wpnonce' => wp_create_nonce( $action ),
			'action'   => $action
		], admin_url( 'admin-post.php' ) );

	}

	/**
	 * Fetch orders.
	 *
	 * @param array $args
	 *
	 * @return array|object|null
	 */
	public function get_orders( $args = [], $status = 'approved' ) {

		$defaults = [
			'orderby' => 'created',
			'order'   => 'desc',
			'limit'   => 10,
			'offset'  => 0,
		];

		$args = wp_parse_args( $args, $defaults );

		$fields = implode( ',', [ '*' ] );

		$stmt = $this->db->prepare( "
				SELECT $fields FROM $this->table 
				WHERE id > %d and status = %s
				ORDER BY {$args['orderby']} {$args['order']}
				LIMIT %d OFFSET %d
				"
			,
			0,
			$status,
			$args['limit'],
			$args['offset']
		);

		$result = $this->db->get_results( $stmt, ARRAY_A );

		return $result;

	}

	/**
	 * Fetches single order via order id.
	 *
	 * @param int $order_id
	 *
	 * @return array|object|void|null
	 */
	public function get_order( $order_id = 0 ) {

		$stmt = $this->db->prepare( "SELECT * FROM $this->table WHERE id = %d", $order_id );

		return $this->db->get_row( $stmt, OBJECT );

	}

	/**
	 * @param array $args The field values.
	 * @param array $compare The compare field e.g ['id' => 1]
	 * @param array $format_values The format of the values passed in $args.
	 * @param array $format_compare The format of the values passed in $compare.
	 * @param string $table Optional Table.
	 *
	 * @return false|int|string
	 */
	public function edit( $args = [], $compare = [], $format_values = [], $format_compare = [], $table = '' ) {

		if ( ! isset ( $table ) ) {
			$table = $this->table;
		}

		$update = $this->db->update(
			$table,
			$args,
			$compare,
			$format_values,  // Value format.
			$format_compare  // Where format.
		);

		if ( false === $update ) {
			return $this->db->last_error;
		}

		return $update;

	}

	public function get_num_approved_orders() {

		$stmt = $this->db->prepare( "SELECT COUNT(id) FROM $this->table WHERE status = %s'; ", 'approved' );

		return $this->db->get_var( $stmt );

	}

}