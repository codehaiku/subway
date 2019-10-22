<?php

namespace Subway\Memberships\Orders;

use Subway\Currency\Currency;
use Subway\Memberships\Plan\Plan;

class ListTable extends \WP_List_Table {

	var $found_data = array();

	function get_columns() {

		$columns = array(
			'cb'            => '<input type="checkbox" />',
			'order_created' => 'Created',
			'name'          => 'Product',
			'order_id'      => 'Order ID',
			'order_amount'  => 'Amount',
			'order_user_id' => 'Customer',

		);

		return $columns;

	}

	function prepare_items() {

		global $wpdb;

		$orders = new Orders( $wpdb );

		// Process bulk actions.
		$this->process_bulk_action( $orders );

		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = $this->get_column_info();

		$per_page     = $this->get_items_per_page( 'orders_per_page', 5 );
		$current_page = $this->get_pagenum();

		$order = filter_input( INPUT_GET, 'order', FILTER_SANITIZE_SPECIAL_CHARS );

		$orderby = filter_input( INPUT_GET, 'orderby', FILTER_SANITIZE_SPECIAL_CHARS );

		if ( empty( $orderby ) ) {
			$orderby = 'order_created';
		}

		if ( empty ( $order ) ) {
			$order = "desc";
		}

		$offset = 0;

		// Manually determine page query offset (offset + current page (minus one) x posts per page).
		$page_offset = $offset + ( $current_page - 1 ) * $per_page;

		$data = $orders->get_orders( array(
			'orderby' => $orderby,
			'order'   => $order,
			'offset'  => $page_offset,
			'limit'   => $per_page
		) );

		//@Todo: Update total order count.
		$total_items = absint( get_option('subway_count_orders', 0) );

		$this->set_pagination_args( array(
			'total_items' => $total_items, // We have to calculate the total number of items.
			'per_page'    => $per_page // We have to determine how many items to show on a page.
		) );

		$this->items = $data;

		return $this;

	}

	function get_sortable_columns() {

		$sortable_columns = array(
			'order_created' => array( 'order_created', false ),
			'order_id'      => array( 'order_id', false ),
			'order_amount'  => array( 'order_amount', false ),
			'name'          => array( 'name', false ),
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

			case 'order_amount':

				$src           = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/PP_Acceptance_Marks_for_LogoCenter_76x48.png';
				$amount_column = '<img style="vertical-align: middle;" width="32" alt="PayPal Logo" src="' . esc_url( $src ) . '" />';
				$amount        = $currency->format( $item[ $column_name ], get_option( 'subway_currency', 'USD' ) );
				$amount_column = $amount_column . '&nbsp;' . $amount;

				return apply_filters( 'subway_orders_list_table_amount', $amount_column );

				break;

			case 'order_user_id':

				$user_id = $item[ $column_name ];

				$user = get_userdata( $user_id );

				$user_name = $user->display_name;

				$user_edit_link = esc_url( add_query_arg( 'user_id', $user_id, admin_url( 'wp-admin/user-edit.php' ) ) );

				$user_link = sprintf( '<a href="%s" title="%s">%s</a>', $user_edit_link, $user_name, $user_name );

				$user_avatar = get_avatar( $user_id, 32 );

				$user_column = sprintf( '<div style="float: left; margin-right: 10px;">%s</div> %s',
					$user_avatar,
					$user_link
				);

				return apply_filters( 'subway_orders_list_table_user', $user_column );

				break;

			case 'name':

				$product_id = $item['product_id'];

				$product_edit_link = esc_url( add_query_arg( [
					'page'    => 'subway-membership',
					'edit'    => 'yes',
					'product' => $product_id
				],
					admin_url( 'wp-admin/user-edit.php' )
				) );

				$product_edit_link = wp_nonce_url( $product_edit_link, sprintf( 'edit_product_%s', $product_id ), '_wpnonce' );

				$product_column = sprintf(
					"<a href='%s' title='%s'>%s</a>",
					$product_edit_link,
					$item[ $column_name ],
					$item[ $column_name ]
				);

				return apply_filters( 'subway_orders_list_table_product', $product_column );;

				break;
			case 'order_created':
			case 'order_last_updated':
				return date( $datetime_format, strtotime( $item[ $column_name ] ) );
				break;
			default:
				return $item[ $column_name ];
		}

	}

	function column_order_created( $item ) {

		$datetime_format = sprintf( '%s',
			get_option( 'date_format', 'F j, Y' )
		);

		$trash_uri = esc_url( add_query_arg( array(
			'action' => 'listing_delete_action',
			'id'     => $item['order_id'],
		), get_admin_url() . 'admin-post.php' ) );

		$delete_url = wp_nonce_url(
			$trash_uri,
			sprintf( 'trash_order_%s', $item['order_id'] ),
			'_wpnonce'
		);

		$edit_url = wp_nonce_url(
			sprintf( '?page=%s&edit=%s&order=%s', $_REQUEST['page'], 'yes', $item['order_id'] ),
			sprintf( 'edit_order_%s', $item['order_id'] ),
			'_wpnonce'
		);

		$actions = array(
			'edit'   => sprintf( '<a href="%s">' . esc_html__( 'See Payment Details', 'subway' ) . '</a>', esc_url( $edit_url ) ),
			'delete' => sprintf( '<a href="%s">' . esc_html__( 'Trash', 'subway' ) . '</a>', esc_url( $delete_url ) ),
		);

		return sprintf( '%1$s %2$s', '<a href="#"><strong>' . date( $datetime_format, strtotime( $item['order_created'] ) ) . '</strong></a>',
			$this->row_actions( $actions ) );

	}

	function get_bulk_actions() {

		$actions = array(
			'delete' => __( 'Delete', 'subway' )
		);

		return $actions;

	}

	function process_bulk_action( $membership ) {

		if ( 'delete' === $this->current_action() ) {

			check_admin_referer( 'bulk-' . $this->_args['plural'] );

			$product_ids = filter_input( INPUT_POST, 'product_ids',
				FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

			if ( ! empty( $product_ids ) ) {
				foreach ( $product_ids as $id ) {
					$order->delete( $id );
				}
			}

		}

		return $this;

	}

	function column_cb( $item ) {

		return sprintf(
			'<input type="checkbox" name="order_ids[]" value="%s" />', $item['order_id']
		);

	}

}

$list_table = new ListTable();