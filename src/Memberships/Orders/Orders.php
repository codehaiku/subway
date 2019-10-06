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

		$defaults = [
			'orderby' => 'order_created',
			'order' => 'desc',
			'limit' => 10
		];

		$args = wp_parse_args( $args, $defaults );

		$product_table = $this->product_table;

		$fields        = implode( ',',
			[
				$this->table . '.id as order_id',
				$this->table . '.product_id as order_product_id',
				$this->table . '.created as order_created',
				$this->table . '.last_updated as order_updated',
				$this->table . '.amount as order_amount',
				$this->table . '.user_id as order_user_id',
				$this->table . '.status as order_status',
				$this->table . '.gateway as order_gateway',
				$product_table . '.id as product_id',
				$product_table . '.name'

			]
		);

		$stmt = $this->wpdb->prepare( "
				SELECT $fields FROM $this->table 
				INNER JOIN $product_table 
				WHERE {$this->table}.product_id = {$product_table}.id
				AND {$this->table}.id > %d
				ORDER BY {$args['orderby']} {$args['order']}
				LIMIT {$args['limit']}
				"
				,
			0
			);

		$result = $this->wpdb->get_results( $stmt, ARRAY_A );

		return $result;

	}

	public function get_order() {

		return [];

	}
}