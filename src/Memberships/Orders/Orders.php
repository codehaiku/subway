<?php

namespace Subway\Memberships\Orders;

class Orders {

	protected $order;

	protected $table = '';

	public function __construct( \wpdb $wpdb ) {

		$this->wpdb = $wpdb;

		$this->table = $this->wpdb->prefix . 'subway_memberships_orders';

	}

	public function get_orders() {

		$stmt = $this->wpdb->prepare("SELECT * FROM $this->table WHERE id > %d ORDER BY id", 0 );

		$result = $this->wpdb->get_results($stmt, ARRAY_A);

		return $result;

	}

	public function get_order() {
		return [];
	}
}