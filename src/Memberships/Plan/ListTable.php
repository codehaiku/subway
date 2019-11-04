<?php

namespace Subway\Memberships\Plan;

use Subway\Currency\Currency;

class ListTable extends \WP_List_Table {

	var $found_data = array();

	function get_columns() {

		$columns = array(
			'cb'           => '<input type="checkbox" />',
			'name'         => esc_html__( 'Plan Name', 'subway' ),
			'sku'          => esc_html__( 'SKU', 'subway' ),
			'type'         => esc_html__( 'Type', 'subway' ),
			'amount'       => esc_html__( 'Amount', 'subway' ),
			'date_updated' => esc_html__( 'Last Update', 'subway' )
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
				'<a title="%s" class="%s" href="admin.php?page=subway-membership-plans&status=%s">%s</a>',
				$label, $class, $key, $label
			);

			$returned_value[ $key ] = $view_items;

		}

		return apply_filters( 'subway_list_table_views', $returned_value );

	}

	function prepare_items() {

		$plan = new Plan();

		// Process bulk actions.
		$this->process_bulk_action( $plan );

		$this->_column_headers = $this->get_column_info();

		$per_page = $this->get_items_per_page( 'plans_per_page', 5 );

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

		$data = $plan->get_wp_list_table( array(
			'orderby'   => 'date_updated',
			'direction' => $order,
			'offset'    => $page_offset,
			'limit'     => $per_page,
			'name_like' => $search_value,
			'status'    => $status
		) );

		$total_items = absint( get_option( 'subway_plans_count' ) );

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

		$plan      = new Plan();
		$trash_url = $plan->get_trash_url( $item['id'] );
		$restore_url = $plan->get_restore_url( $item['id'] );
        $delete_url = '#';

		$edit_url = wp_nonce_url(
			sprintf( '?page=%s&edit=%s&plan=%s&section=plan-information', $_REQUEST['page'], 'yes', $item['id'] ),
			sprintf( 'edit_plan_%s', $item['id'] ),
			'_wpnonce'
		);

		$actions = array(
			'edit'  => sprintf( '<a href="%s">' . esc_html__( 'Configure Plan', 'subway' ) . '</a>', esc_url( $edit_url ) ),
			'trash' => sprintf( '<a href="%s">' . esc_html__( 'Trash', 'subway' ) . '</a>', esc_url( $trash_url ) ),
		);

		if ( 'trashed' === $item['status'] ) {
			$actions = array(
				'restore'  => sprintf( '<a href="%s">' . esc_html__( 'Restore', 'subway' ) . '</a>', esc_url( $restore_url ) ),
				'delete' => sprintf( '<a href="%s">' . esc_html__( 'Delete Permanently', 'subway' ) . '</a>', esc_url( $delete_url ) ),
			);
		}


		$plan_name = sprintf( '<strong><a href="%1$s" title="%2$s">%2$s</a></strong>', $edit_url, $item['name'] );

		if ( 'draft' === $item['status'] ):
			$plan_name .= '&nbsp; &mdash; ' . esc_html__( 'Draft', 'subway' ) . '';
		endif;

		$plan_name .= $this->row_actions( $actions );

		return $plan_name;

	}

	function get_bulk_actions() {

		$actions = array(
			'trash' => __( 'Trash', 'subway' )
		);

		return $actions;

	}

	function process_bulk_action( $plan ) {

		if ( 'delete' === $this->current_action() ) {

			check_admin_referer( 'bulk-' . $this->_args['plural'] );

			$plan_ids = filter_input( INPUT_POST, 'plan_ids',
				FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

			if ( ! empty( $plan_ids ) ) {
				foreach ( $plan_ids as $id ) {
					$plan->delete( $id );
				}
			}

		}

		return $this;

	}

	function column_cb( $item ) {

		return sprintf(
			'<input type="checkbox" name="plan_ids[]" value="%s" />', $item['id']
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

