<?php

namespace Subway\Memberships\Orders;

use Subway\Currency\Currency;
use Subway\Memberships\Products\Products;

class ListTable extends \WP_List_Table {

	var $found_data = array();

	function get_columns() {

		$columns = array(
			'cb'           => '<input type="checkbox" />',
			'created'      => 'Created',
			'status'       => 'Status',
			'amount'       => 'Amount',
			'user_id'      => 'User ID',
			'product_id'   => 'Product ID',
			'gateway'      => 'Gateway',
			'last_updated' => 'Last Updated',

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

		$per_page     = $this->get_items_per_page( 'products_per_page', 5 );
		$current_page = $this->get_pagenum();

		$order = filter_input( INPUT_GET, 'order', FILTER_SANITIZE_SPECIAL_CHARS );

		if ( empty ( $order ) ) {
			$order = "DESC";
		}

		$offset = 0;

		// Manually determine page query offset (offset + current page (minus one) x posts per page).
		$page_offset = $offset + ( $current_page - 1 ) * $per_page;

		$data = $orders->get_orders( array(
			'orderby'   => 'date_updated',
			'direction' => $order,
			'offset'    => $page_offset,
			'limit'     => $per_page
		) );

		//@Todo: Update total order count.
		$total_items = 100;

		$this->set_pagination_args( array(
			'total_items' => $total_items, // We have to calculate the total number of items.
			'per_page'    => $per_page // We have to determine how many items to show on a page.
		) );

		$this->items = $data;

		return $this;

	}

	function get_sortable_columns() {

		$sortable_columns = array(
			'name'         => array( 'name', false ),
			'date_created' => array( 'created', false ),
			'date_updated' => array( 'last_updated', false ),
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
				return $currency->format( $item[ $column_name ], get_option( 'subway_currency', 'USD' ) );
				break;

			case 'date_created':
			case 'date_updated':
				return date( $datetime_format, strtotime( $item[ $column_name ] ) );
				break;
			default:
				return $item[ $column_name ];
		}

	}

	function column_name( $item ) {

		$trash_uri = add_query_arg( array(
			'action' => 'listing_delete_action',
			'id'     => $item['id'],
		), get_admin_url() . 'admin-post.php' );

		$delete_url = wp_nonce_url(
			$trash_uri,
			sprintf( 'trash_order_%s', $item['id'] ),
			'_wpnonce'
		);

		$edit_url = wp_nonce_url(
			sprintf( '?page=%s&edit=%s&order=%s', $_REQUEST['page'], 'yes', $item['id'] ),
			sprintf( 'edit_order_%s', $item['id'] ),
			'_wpnonce'
		);

		$actions = array(
			'edit'   => sprintf( '<a href="%s">' . esc_html__( 'Edit', 'subway' ) . '</a>', esc_url( $edit_url ) ),
			'delete' => sprintf( '<a href="%s">' . esc_html__( 'Trash', 'subway' ) . '</a>', esc_url( $delete_url ) ),
		);

		return sprintf( '%1$s %2$s', '<a href="#"><strong>' . $item['name'] . '</strong></a>',
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
			'<input type="checkbox" name="order_ids[]" value="%s" />', $item['id']
		);

	}

}

$list_table = new ListTable();