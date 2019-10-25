<?php

namespace Subway\Tools;

class Repair {

	protected $wpdb;

	protected $products_table = '';

	protected $orders_table = '';

	public function __construct( \wpdb $wpdb ) {

		$this->wpdb = $wpdb;

		$this->products_table = $this->wpdb->prefix . 'subway_memberships_products_plans';

		$this->orders_table = $this->wpdb->prefix . 'subway_memberships_orders';

		return $this;

	}

	public function repair_products_count() {

		// Prepare the query.
		$stmt = $this->wpdb->prepare( "SELECT COUNT(*) as total from {$this->products_table} 
				WHERE id > %d", 0 );

		// Get the total number of rows.
		$result = $this->wpdb->get_row( $stmt, OBJECT );

		// Update the count of products base on our result.
		update_option( 'subway_products_count', absint( $result->total ) );

		do_action( 'after_repair_products_count' );

		// Redirect back.
		wp_safe_redirect(
			esc_url_raw( add_query_arg( [
				'page'     => 'subway-membership-tools',
				'repaired' => 'products_count'
			],
				admin_url( 'admin.php' ) ),
				302
			) );

		return $this;

	}

	public function repair_orders_count() {

		// Prepare the query.
		$stmt = $this->wpdb->prepare( "SELECT COUNT(*) as total from {$this->orders_table} 
				WHERE id > %d", 0 );

		// Get the total number of rows.
		$result = $this->wpdb->get_row( $stmt, OBJECT );

		// Update the count of products base on our result.
		update_option( 'subway_count_orders', absint( $result->total ) );

		do_action( 'after_repair_orders_count' );

		// Redirect back.
		wp_safe_redirect(
			esc_url_raw( add_query_arg( [
				'page'     => 'subway-membership-tools',
				'repaired' => 'orders_count'
			],
				admin_url( 'admin.php' ) ),
				302
			) );

		return $this;

	}

	public function attach_hooks() {

		$this->define_hooks();

	}

	private function define_hooks() {

		add_action( 'admin_post_repair_products_count', [ $this, 'repair_products_count' ] );

		add_action( 'admin_post_repair_orders_count', [ $this, 'repair_orders_count' ] );

	}
}