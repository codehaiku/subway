<?php

namespace Subway\Memberships\Products;

use Subway\Memberships\Products\Products;

class ListTable extends \WP_List_Table {

	var $found_data = array();

	function get_columns() {

		$columns = array(
			'cb'           => '<input type="checkbox" />',
			'name'         => 'Product Name',
			'description'  => 'Description',
			'type'         => 'Type',
			'amount'       => 'Amount',
			'date_updated' => 'Last Updated',
		);

		return $columns;

	}

	function prepare_items() {

		$memberships = new Products();

		// Process bulk actions.
		$this->process_bulk_action( $memberships );

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

		$data = $memberships->get_products( array(
			'orderby'   => 'date_updated',
			'direction' => $order,
			'offset'    => $page_offset,
			'limit'     => $per_page
		) );

		$total_items = absint( get_option( 'subway_products_count' ) );

		$this->set_pagination_args( array(
			'total_items' => $total_items, // We have to calculate the total number of items.
			'per_page'    => $per_page // We have to determine how many items to show on a page.
		) );

		$this->items = $data;

		return $this;

	}

	function get_sortable_columns() {

		$sortable_columns = array(
			'name' => array( 'name', false ),
			'date_created' => array( 'date_created', false ),
			'date_updated' => array( 'date_updated', false ),
		);

		return $sortable_columns;

	}

	function column_default( $item, $column_name ) {

		$datetime_format = sprintf('%s %s',
			get_option('date_format', 'F j, Y'),
			get_option('time_format', 'g:i a')
		);

		switch ( $column_name ) {
			case 'amount':
				return number_format( $item[ $column_name ], 2 );
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
			sprintf( 'trash_product_%s', $item['id'] ),
			'_wpnonce'
		);

		$edit_url = wp_nonce_url(
			sprintf( '?page=%s&edit=%s&product=%s', $_REQUEST['page'], 'yes', $item['id'] ),
			sprintf( 'edit_product_%s', $item['id'] ),
			'_wpnonce'
		);

		$actions = array(
			'edit'   => sprintf( '<a href="%s">Edit</a>', esc_url( $edit_url ) ),
			'delete' => sprintf( '<a href="%s">Trash</a>', esc_url( $delete_url ) ),
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
					$membership->delete( $id );
				}
			}
		}

		return $this;

	}

	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="product_ids[]" value="%s" />', $item['id']
		);
	}

}

$list_table = new ListTable();