<?php

namespace Subway\Memberships\Plan;

use Subway\Currency\Currency;

class ListTable extends \WP_List_Table {

	var $found_data = array();

	function get_columns() {

		$columns = array(
			'cb'           => '<input type="checkbox" />',
			'name'         => 'Product Name',
			'sku'          => 'SKU',
			'type'         => 'Type',
			'amount'       => 'Amount',
			'date_updated' => 'Last Update'
		);

		return $columns;

	}


	function get_views() {

		$returned_value = [];

		$status = filter_input( 1, 'status', 516 );

		// Default to 'All'.
		if ( empty( $status ) ) {
			$status = 'all';
		}

		$views = array(
			'all'       => __( 'All', 'subway' ),
			'published' => __( 'Published', 'subway' ),
			'draft'     => __( 'Drafts', 'subway' ),
			'trashed'   => __( 'Trash', 'subway' ),
		);

		foreach ( $views as $key => $label ) {

			$class = 'current';

			if ( $status !== $key ) {
				$class = 'not-current';
			}

			$view_items = sprintf(
				'<a title="%s" class="%s" href="admin.php?page=subway-membership&status=%s">%s</a>',
				$label, $class, $key, $label
			);

			$returned_value[ $key ] = $view_items;

		}

		return apply_filters( 'subway_list_table_views', $returned_value );

	}

	function prepare_items() {

		$memberships = new Plan();

		// Process bulk actions.
		$this->process_bulk_action( $memberships );

		$this->_column_headers = $this->get_column_info();

		$per_page = $this->get_items_per_page( 'products_per_page', 5 );

		$current_page = $this->get_pagenum();

		$order = filter_input( 1, 'order', 515 );

		$search_value = filter_input( 1, 's', 513 );

		$status = filter_input( 1, 'status', 513 );

		if ( empty ( $order ) ) {

			$order = "DESC";

		}

		$offset = 0;

		// Manually determine page query offset (offset + current page (minus one) x posts per page).
		$page_offset = $offset + ( $current_page - 1 ) * $per_page;

		$data = $memberships->get_wp_list_table_products( array(
			'orderby'   => 'date_updated',
			'direction' => $order,
			'offset'    => $page_offset,
			'limit'     => $per_page,
			'name_like' => $search_value,
			'status'    => $status
		) );

		$total_items = absint( get_option( 'subway_products_count' ) );

		if ( ! empty( $search_value ) ) {
			$total_items = count( $data );
		}

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
			'date_created' => array( 'date_created', false ),
			'date_updated' => array( 'date_updated', false ),
		);

		return $sortable_columns;

	}

	function column_default( $item, $column_name ) {

		$datetime_format = sprintf( "%s \n\t %s",
			'M d, Y',
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
			case 'type':
				return ucwords( $item[ $column_name ] );
				break;
			default:
				return $item[ $column_name ];
		}

	}

	function column_name( $item ) {

		$trash_uri = esc_url_raw( add_query_arg( array(
			'action' => 'listing_delete_action',
			'id'     => $item['id'],
		), get_admin_url() . 'admin-post.php' ) );

		$delete_url = wp_nonce_url(
			$trash_uri,
			sprintf( 'trash_product_%s', $item['id'] ),
			'_wpnonce'
		);

		$edit_url = wp_nonce_url(
			sprintf( '?page=%s&edit=%s&product=%s&section=product-information', $_REQUEST['page'], 'yes', $item['id'] ),
			sprintf( 'edit_product_%s', $item['id'] ),
			'_wpnonce'
		);

		$actions = array(
			'edit'   => sprintf( '<a href="%s">' . esc_html__( 'Configure Plan', 'subway' ) . '</a>', esc_url( $edit_url ) ),
			'delete' => sprintf( '<a href="%s">' . esc_html__( 'Trash', 'subway' ) . '</a>', esc_url( $delete_url ) ),
		);


		$product_name = sprintf( '<strong><a href="%1$s" title="%2$s">%2$s</a></strong>', $edit_url, $item['name'] );

		if ( 'draft' === $item['status'] ):
			$product_name .= '&nbsp; &mdash; ' . esc_html__( 'Draft', 'subway' ) . '';
		endif;

		$product_name .= $this->row_actions( $actions );

		return $product_name;

	}

	function get_bulk_actions() {

		$actions = array(
			'trash' => __( 'Trash', 'subway' )
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

	function search_box( $text, $input_id ) {

		if ( empty( $_REQUEST['s'] ) && ! $this->has_items() ) {
			return;
		}

		$input_id = $input_id . '-search-input';

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
		}
		if ( ! empty( $_REQUEST['order'] ) ) {
			echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
		}
		if ( ! empty( $_REQUEST['post_mime_type'] ) ) {
			echo '<input type="hidden" name="post_mime_type" value="' . esc_attr( $_REQUEST['post_mime_type'] ) . '" />';
		}
		if ( ! empty( $_REQUEST['detached'] ) ) {
			echo '<input type="hidden" name="detached" value="' . esc_attr( $_REQUEST['detached'] ) . '" />';
		}
		?>

        <p class="search-box">
            <label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo $text; ?>:</label>
            <input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s"
                   value="<?php _admin_search_query(); ?>"/>
			<?php submit_button( $text, '', '', false, array( 'id' => 'search-submit' ) ); ?>
        </p>
		<?php

	}

}

