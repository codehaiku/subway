<?php

namespace Subway\Memberships\Orders;

use Subway\Helpers\Helpers;

class Orders {

	protected $order;

	protected $table = '';

	protected $db = null;

	public function __construct( \wpdb $wpdb ) {

		$this->db = Helpers::get_db();

		$this->table = $this->db->prefix . 'subway_memberships_orders';

		return $this;

	}

	public function get_orders( $args = [] ) {

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
				WHERE id > %d
				ORDER BY {$args['orderby']} {$args['order']}
				LIMIT %d OFFSET %d
				"
			,
			0,
			$args['limit'],
			$args['offset']
		);

		$result = $this->db->get_results( $stmt, ARRAY_A );

		return $result;

	}

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

	private function edit_submit() {

		Helpers::debug( $_POST );

		do_action( 'orders_edit_submit' );

		$gateway_customer_name           = filter_input( 0, 'gateway_customer_name', 516 );
		$gateway_customer_lastname       = filter_input( 0, 'gateway_customer_lastname', 516 );
		$gateway_customer_email          = filter_input( 0, 'gateway_customer_email', 516 );
		$gateway_customer_address_line_1 = filter_input( 0, 'gateway_customer_address_line_1', 516 );
		$gateway_customer_address_line_2 = filter_input( 0, 'gateway_customer_address_line_2', 516 );
		$gateway_customer_postal_code    = filter_input( 0, 'gateway_customer_postal_code', 516 );
		$gateway_customer_city           = filter_input( 0, 'gateway_customer_city', 516 );
		$gateway_customer_country        = filter_input( 0, 'gateway_customer_country', 516 );
		$gateway_customer_state          = filter_input( 0, 'gateway_customer_state', 516 );
		$gateway_customer_phone_number   = filter_input( 0, 'gateway_customer_phone_number', 516 );
		$order_id                        = filter_input( 0, 'order_details_id', 516 );

		$args = [
			'gateway_customer_name'           => $gateway_customer_name,
			'gateway_customer_lastname'       => $gateway_customer_lastname,
			'gateway_customer_email'          => $gateway_customer_email,
			'gateway_customer_address_line_1' => $gateway_customer_address_line_1,
			'gateway_customer_address_line_2' => $gateway_customer_address_line_2,
			'gateway_customer_postal_code'    => $gateway_customer_postal_code,
			'gateway_customer_city'           => $gateway_customer_city,
			'gateway_customer_country'        => $gateway_customer_country,
			'gateway_customer_state'          => $gateway_customer_state,
			'gateway_customer_phone_number'   => $gateway_customer_phone_number,
		];

		$compare = [
			'id' => $order_id
		];

		$format_v = [ '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ];
		$format_c = [ '%d' ];

		$edited = $this->edit( $args, $compare, $format_v, $format_c,
			$this->db->prefix . 'subway_memberships_orders_details' );


		$uri_params = [
			'page'    => 'subway-membership-orders',
			'edit'    => 'yes',
			'success' => 'true',
			'order'   => $order_id
		];

		if ( false === $edited ) {
			$uri_params['type'] = 'fail';
		}

		$return_url = esc_url_raw( add_query_arg( $uri_params, admin_url( 'admin.php' ) ) );

		wp_safe_redirect( $return_url, 302 );

		exit;
	}


	public function attach_hooks() {

		add_action( 'admin_post_subway_order_edit', function () {
			$this->edit_submit();
		}, 10 );

	}

}