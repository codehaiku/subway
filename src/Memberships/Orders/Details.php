<?php

namespace Subway\Memberships\Orders;

use Subway\Memberships\Orders\Orders;

class Details extends Orders {

	protected $table;

	public function __construct( \wpdb $wpdb ) {

		parent::__construct( $wpdb );

		$this->table = $this->db->prefix . 'subway_memberships_orders_details';

	}

	public function get( $order_id = 0 ) {

		$stmt = $this->db->prepare( "SELECT * FROM $this->table WHERE order_id = %d", $order_id );

		$details = $this->db->get_row( $stmt, OBJECT );

		return $details;
	}

	public function add( $args = [] ) {

		$defaults = [
			'order_id'                        => 0,
			'gateway_name'                    => '',
			'gateway_customer_name'           => '',
			'gateway_customer_lastname'       => '',
			'gateway_customer_email'          => '',
			'gateway_customer_address_line_1' => '',
			'gateway_customer_address_line_2' => '',
			'gateway_customer_postal_code'    => '',
			'gateway_customer_city'           => '',
			'gateway_customer_country'        => '',
			'gateway_customer_state'          => '',
			'gateway_customer_phone_number'   => '',
			'gateway_transaction_created'     => '',
		];

		$args = wp_parse_args( $args, $defaults );

		$inserted = $this->db->insert(
			$this->table, $args,
			[ '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ]
		);

		if ( ! $inserted ) {

			return $this->db;

		} else {

			return true;

		}
	}

	public function update() {

	}

	public function delete() {

	}


}