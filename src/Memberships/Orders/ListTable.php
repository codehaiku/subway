<?php

namespace Subway\Memberships\Orders;

use Subway\Currency\Currency;
use Subway\Helpers\Helpers;
use Subway\Memberships\Plan\Plan;

class ListTable extends \WP_List_Table {

	var $found_data = array();

	var $plan = null;

	var $order = null;

	function __construct( $args = array() ) {

		parent::__construct( $args );
		$this->plan = new Plan();
		$this->order  = new Orders();

	}

	function get_columns() {

		$columns = array(
			'cb'                 => '<input type="checkbox" />',
			'created'            => esc_html__( 'Created', 'subway' ),
			'recorded_plan_name' => esc_html__( 'Plan Name', 'subway' ),
			'amount'             => esc_html__( 'Amount', 'subway' ),
			'user_id'            => esc_html__( 'Customer', 'subway' ),
		);

		return $columns;

	}

	function prepare_items() {

		$orders = new Orders( Helpers::get_db() );

		$order        = filter_input( INPUT_GET, 'order', 513 );
		$order_by     = filter_input( INPUT_GET, 'orderby', 513 );
		$per_page     = $this->get_items_per_page( 'orders_per_page', 5 );
		$current_page = $this->get_pagenum();

		// Process bulk actions.
		$this->process_bulk_action( $orders );

		if ( empty( $order_by ) ) {
			$order_by = 'created';
		}

		if ( empty ( $order ) ) {
			$order = "desc";
		}

		$offset = 0;

		// Manually determine page query offset (offset + current page (minus one) x posts per page).
		$page_offset = $offset + ( $current_page - 1 ) * $per_page;

		$data = $orders->get_orders( array(
			'orderby' => $order_by,
			'order'   => $order,
			'offset'  => $page_offset,
			'limit'   => $per_page
		) );

		$total_items = $this->order->get_num_approved_orders();

		$this->set_pagination_args( array(
			'total_items' => $total_items, // We have to calculate the total number of items.
			'per_page'    => $per_page // We have to determine how many items to show on a page.
		) );

		$this->items = $data;

		return $this;

	}

	function get_sortable_columns() {

		$sortable_columns = array(
			'created' => array( 'created', false ),
			'id'      => array( 'id', false ),
			'amount'  => array( 'amount', false ),
		);

		return $sortable_columns;

	}

	function column_default( $item, $column_name ) {

		$datetime_format = sprintf( '%s %s',
			get_option( 'date_format', 'F j, Y' ),
			get_option( 'time_format', 'g:i a' )
		);

		$currency = new Currency();

		switch ( $column_name ) {

			case 'amount':

				$src           = apply_filters('subway_memberships_orders_paypal_logo', 'https://www.paypalobjects.com/webstatic/mktg/logo-center/PP_Acceptance_Marks_for_LogoCenter_76x48.png');
				$amount_column = '<img style="vertical-align: middle;" width="32" alt="' . esc_attr__('PayPal Logo', 'subway') . '" src="' . esc_url( $src ) . '" />';
				$amount        = $currency->format( $item[ $column_name ], get_option( 'subway_currency', 'USD' ) );
				$amount_column = $amount_column . '&nbsp;' . $amount;

				return apply_filters( 'subway_orders_list_table_amount', $amount_column );

				break;

			case 'user_id':

				$user_id = $item[ $column_name ];

				$user = get_userdata( $user_id );

				$user_column = esc_html__( '[Unknown User]', 'box-membership' );

				if ( $user ) {

					$user_name = $user->display_name;

					$user_edit_link = esc_url( add_query_arg( 'user_id', $user_id, admin_url( 'wp-admin/user-edit.php' ) ) );

					$user_link = sprintf( '<a href="%s" title="%s">%s</a>', $user_edit_link, $user_name, $user_name );

					$user_avatar = get_avatar( $user_id, 32 );

					$user_column = sprintf( '<div style="float: left; margin-right: 10px;">%s</div> %s',
						$user_avatar,
						$user_link
					);
				}

				return apply_filters( 'subway_orders_list_table_user', $user_column );

				break;

			case 'created':
			case 'last_updated':

				return date( $datetime_format, strtotime( $item[ $column_name ] ) );

				break;

			default:

				return $item[ $column_name ];
		}

	}

	function column_created( $item ) {

		$datetime_format = sprintf( '%s',
			get_option( 'date_format', 'F j, Y' )
		);

		$trash_url = $this->order->get_trash_url( $item['id'] );

		$edit_url = wp_nonce_url(
			sprintf( '?page=%s&edit=%s&order=%s', $_REQUEST['page'], 'yes', $item['id'] ),
			sprintf( 'edit_order_%s', $item['id'] ),
			'_wpnonce'
		);

		$actions = array(
			'edit'   => sprintf( '<a href="%s">' . esc_html__( 'See Payment Details', 'subway' ) . '</a>', esc_url( $edit_url ) ),
			'trash' => sprintf( '<a href="%s">' . esc_html__( 'Trash', 'subway' ) . '</a>', esc_url( $trash_url ) ),
		);

		return sprintf( '%1$s %2$s', '<a href="#"><strong>' . date( $datetime_format, strtotime( $item['created'] ) ) . '</strong></a>',
			$this->row_actions( $actions ) );

	}

	function get_bulk_actions() {

		$actions = array(
			'delete' => __( 'Delete', 'subway' )
		);

		return $actions;

	}

	function process_bulk_action( $order ) {

		if ( 'delete' === $this->current_action() ) {

			check_admin_referer( 'bulk-' . $this->_args['plural'] );

			$plan_id_collection = filter_input( INPUT_POST, 'plan_id_collection',
				FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

			if ( ! empty( $plan_id_collection ) ) {
				foreach ( $plan_id_collection as $id ) {
					$order->delete( $id );
				}
			}

		}

		return $this;

	}

	function column_cb( $item ) {

		return sprintf(
			'<input type="checkbox" name="order_ids[]" value="%s" />', $item['id']
		);

	}

}