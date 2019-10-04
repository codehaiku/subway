<?php

namespace Subway\Memberships\Orders;

class Orders {

	protected $order;

	protected $table = '';

	protected $product_table;

	public function __construct( \wpdb $wpdb ) {

		$this->wpdb = $wpdb;

		$this->product_table = $this->wpdb->prefix . 'subway_memberships_products';

		$this->table = $this->wpdb->prefix . 'subway_memberships_orders';

	}

	public function get_orders( $args = [] ) {

		$product_table = $this->product_table;

		$stmt = $this->wpdb->prepare("
				SELECT * FROM $this->table 
				INNER JOIN $product_table 
				WHERE $product_table.id = {$this->table}.product_id
				AND {$this->table}.id > %d
				ORDER BY {$this->table}.id DESC", 0 );

		$result = $this->wpdb->get_results($stmt, ARRAY_A);

		return $result;

	}

	public function get_order() {

		return [];

	}
}