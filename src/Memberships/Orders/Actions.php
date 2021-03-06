<?php

namespace Subway\Memberships\Orders;

class Actions extends Orders {

	const cancel_order_action = 'subway_cancel_order';

	const edit_action = 'subway_order_edit';

	public function __construct() {
		parent::__construct();
	}

	public function trash_order_action() {

		$order_id = filter_input( 1, 'order-id', FILTER_SANITIZE_NUMBER_INT );

		check_admin_referer( self::cancel_order_action );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Error: You are not allowed to do that.', 'subway' ) );
		}

		$this->edit( [ 'status' => 'trash' ], [ 'id' => $order_id ], [ '%s' ], [ '%d' ], $this->table );

		wp_safe_redirect( wp_get_referer(), 302 );

		exit;

	}

	public function edit_action() {

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

		add_action( "admin_post_" . self::cancel_order_action, [ $this, 'trash_order_action' ] );
		add_action( "admin_post_" . self::edit_action, [ $this, 'edit_action' ] );

	}
}