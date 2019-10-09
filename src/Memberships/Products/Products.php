<?php

namespace Subway\Memberships\Products;

use mysql_xdevapi\Exception;

/**
 * Class Products
 * @package Subway\Memberships\Products
 */
class Products {

	var $table = '';

	public function __construct() {

		global $wpdb;

		$this->table = $wpdb->prefix . 'subway_memberships_products';

	}

	public function get_products( $args ) {

		global $wpdb;

		$defaults = array(
			'limit'     => 60,
			'offset'    => 0,
			'orderby'   => 'id',
			'direction' => 'DESC',
			'status'    => '',
			'name_like' => ''
		);

		$args = wp_parse_args( $args, $defaults );

		$orderby = $args['orderby'];

		if ( ! in_array( $args['status'], [ 'draft', 'published', 'trashed' ] ) ) {
			$args['status'] = "'draft', 'published'";
		} else {
			$args['status'] = '\'' . $args['status'] . '\'';
		}

		$search_query = $wpdb->prepare( "AND name like '%%%s%%' ", $args['name_like'] );

		if ( empty( $args['name_like'] ) ) {
			$search_query = '';
		}

		$direction = strtoupper( $args['direction'] );

		$stmt = $wpdb->prepare( "SELECT status, id, name, sku, description, type, amount, date_created, date_updated 
			FROM $this->table 
			WHERE status IN (" . $args['status'] . ")
			$search_query 
			ORDER BY $orderby $direction LIMIT %d, %d",
			array( $args['offset'], $args['limit'] ) );

		$results = $wpdb->get_results( $stmt, ARRAY_A );


		return $results;

	}


	public function get_product( $id ) {

		global $wpdb;

		$stmt = $wpdb->prepare( "SELECT * FROM $this->table WHERE id = %d", absint( $id ) );

		$result = $wpdb->get_row( $stmt, OBJECT );

		return $result;

	}

	public function add() {

	}

	public function delete( $product_id ) {

		global $wpdb;

		$is_deleted = $wpdb->delete( $this->table,
			array( 'id' => $product_id ), array( '%d' ) );

		// Update the total membership count.
		if ( $is_deleted ) {

			$current_total = get_option( 'subway_products_count', 0 );

			if ( $current_total !== 0 ) {
				update_option( 'subway_products_count', absint( $current_total ) - 1 );
			}

		}

		return $is_deleted;

	}

	/**
	 * @param array $args
	 *
	 * @return false|int
	 * @throws \Exception
	 */
	public function update( $args = array() ) {

		global $wpdb;

		$data = array(
			'name'         => $args['title'],
			'description'  => $args['description'],
			'type'         => $args['type'],
			'amount'       => $args['amount'],
			'sku'          => $args['sku'],
			'status'       => $args['status'],
			'date_updated' => current_time( 'mysql' )
		);

		foreach ( $data as $key => $value ) {
			if ( empty ( $value ) ) {
				throw new \Exception( 'ERROR: All Fields Are Required. ' );
			}
		}

		$table = $this->table;

		$where        = array( 'id' => $args['id'] );
		$format       = array( '%s', '%s', '%s', '%f', '%s', '%s' );
		$where_format = array( '%d' );

		return $wpdb->update( $table, $data, $where, $format, $where_format );

	}

	public function get_product_checkout_url( $id ) {

		$checkout_url = add_query_arg( 'product_id', $id, 'http://multisite.local/checkout' );

		if ( ! is_user_logged_in() ) {
			$checkout_url = add_query_arg( 'product_id', $id, 'http://multisite.local/create-account' );
		}

		return apply_filters( 'get_product_checkout_url', $checkout_url );

	}


}