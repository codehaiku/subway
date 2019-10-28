<?php

namespace Subway\Memberships\Product;

use Subway\Helpers\Helpers;
use Subway\Options\Options;
use Subway\User\Plans;

class Controller extends Product {

	public function __construct() {
		parent::__construct();
	}

	public function fetch_all( $args = [] ) {

		$db = Helpers::get_db();

		$defaults = [
			'per_page'     => 12,
			'current_page' => 1,
			'field'        => 'date_created',
			'direction'    => 'DESC'
		];

		$args = wp_parse_args( $args, $defaults );

		// Manually determine page query offset (offset + current page (minus one) x posts per page).
		$args['offset'] = ( absint( $args['current_page'] ) - 1 ) * absint( $args['per_page'] );

		// Manually create order by string.
		$order_by = sanitize_sql_orderby( sprintf( '%s %s', $args['field'], $args['direction'] ) );

		$stmt = $db->prepare(
			"SELECT SQL_CALC_FOUND_ROWS * FROM $this->table 
			WHERE id > %d
			ORDER BY {$order_by}
			LIMIT %d OFFSET %d",
			0, $args['per_page'], $args['offset']
		);

		$items = $db->get_results( $stmt, OBJECT );

		$count = $count = $db->get_var( 'SELECT FOUND_ROWS()' );

		$info_result = [
			'num_pages'    => ceil( $count / $args['per_page'] ),
			'current_page' => absint( $args['current_page'] ),
			'total'        => $count
		];

		$products = [];

		$results = new \stdClass();

		$results->info_result = $info_result;

		if ( ! empty( $items ) ) {

			foreach ( $items as $item ):

				$p = new self();

				$p->set_id( $item->id )
				  ->set_name( $item->name )
				  ->set_description( $item->description )
				  ->set_status( $item->status )
				  ->set_tax_rate( $item->tax_rate )
				  ->set_tax_displayed( $item->tax_displayed )
				  ->set_date_updated( $item->date_updated )
				  ->set_date_created( $item->date_created );

				$products[] = $p;

			endforeach;

		}

		$results->products = $products;

		return $results;

	}

	/**
	 * Retrieve a product by id.
	 *
	 * @return array|bool|object|void|null
	 */
	public function get() {

		$db = Helpers::get_db();

		$stmt = $db->prepare(
			"SELECT * FROM $this->table WHERE id = %d",
			$this->get_id()
		);

		$result = $db->get_row( $stmt, OBJECT );

		if ( ! empty( $result ) ) {

			$this->set_id( $result->id )
			     ->set_name( $result->name )
			     ->set_description( $result->description )
			     ->set_status( $result->status )
			     ->set_tax_rate( $result->tax_rate )
			     ->set_tax_displayed( $result->tax_displayed )
			     ->set_date_updated( $result->date_updated )
			     ->set_date_created( $result->date_created );

			return $this;

		}

		return false;
	}

	/**
	 * Inserts a new product in our table.
	 *
	 * @return bool|string
	 */
	public function save() {

		$db = Helpers::get_db();

		$defaults = [
			'name'          => '',
			'description'   => '',
			'status'        => 'draft',
			'tax_rate'      => 0,
			'tax_displayed' => false,
			'date_updated'  => current_time( 'mysql' ),
			'date_created'  => current_time( 'mysql' )
		];

		$data = wp_parse_args( [
			'name'          => $this->get_name(),
			'description'   => $this->get_description(),
			'tax_rate'      => $this->get_tax_rate(),
			'tax_displayed' => $this->is_tax_displayed()
		], $defaults );

		$format = [
			'%s', // Name.
			'%s', // Description.
			'%s', // Status.
			'%f', // Tax Rate.
			'%d', // Tax Displayed.
			'%s', // Date Updated.
			'%s', // Date Created.
		];

		$inserted = $db->insert( $this->table, $data, $format );

		if ( $inserted ) {
			return true;
		}

		return $db->last_error;

	}

	public function update() {

		$db = Helpers::get_db();

		$defaults = [
			'name'          => '',
			'description'   => '',
			'status'        => 'draft',
			'tax_rate'      => 0,
			'tax_displayed' => false,
			'date_updated'  => current_time( 'mysql' ),
		];

		$data = wp_parse_args( [
			'name'          => $this->get_name(),
			'description'   => $this->get_description(),
			'status'        => $this->get_status(),
			'tax_rate'      => $this->get_tax_rate(),
			'tax_displayed' => $this->is_tax_displayed()
		], $defaults );

		$where = [
			'id' => $this->get_id()
		];

		$format = [
			'%s',
			'%s',
			'%s',
			'%f',
			'%d'
		];

		$where_format = [ '%d' ];

		$updated = $db->update(
			$this->table, $data, $where, $format, $where_format
		);

		if ( true === $updated ) {
			return true;
		}

		return $db->last_error;

	}

	/**
	 * Updates the product status to 'trashed'.
	 * @return bool|string
	 */
	public function trash() {

		$db = Helpers::get_db();

		$data         = [ 'status' => 'trashed' ];
		$where        = [ 'id' => $this->get_id() ];
		$format       = [ '%s' ];
		$where_format = [ '%d' ];

		$trashed = $db->update( $this->table, $data, $where, $format, $where_format );

		if ( true === $trashed ) {
			return true;
		} else {
			return $db->last_error;
		}

	}

	/**
	 * Permanently Deletes the Product.
	 * @return bool
	 */
	public function delete() {
		return true;
	}


	public function get_pagination( $list ) {

		$current_page = filter_input(
			INPUT_GET, 'paged', FILTER_VALIDATE_INT,
			[ 'options' => [ 'default' => 1 ] ]
		);

		$big = 999999999; // need an unlikely integer

		$paginate_args = array(
			'format'    => '?paged=%#%',
			'current'   => max( 1, $current_page ),
			'total'     => $list->info_result['num_pages'],
			'prev_text' => __( '&laquo;', 'subway' ),
			'next_text' => __( '&raquo;', 'subway' ),
		);

		return paginate_links( $paginate_args );

	}

	/**
	 * Returns the edit product url.
	 *
	 * @param string $tab The current displayed tab.
	 *
	 * @return string
	 */
	public function get_product_url_edit( $tab = 'settings' ) {

		$url = add_query_arg(
			[
				'action' => 'edit',
				'tab'    => $tab,
				'page'   => 'subway-membership',
				'id'     => $this->get_id()
			],
			admin_url( 'admin.php' )
		);

		return $url;
	}

	/**
	 * Compares the tab provided with requested tab. Returns 'active' if match, otherwise 'inactive'.
	 *
	 * @param string $tab
	 *
	 * @return string
	 */
	public function get_is_active_tab( $tab = 'settings' ) {

		$active_tab = filter_input( 1, 'tab', 516,
			[ 'options' => [ 'default' => 'settings' ] ] );

		if ( $tab === $active_tab ) {
			return 'active';
		}

		return 'inactive';
	}

	public function get_url() {

		$options = new Options();
		$url = esc_url_raw( add_query_arg([
			'box-membership-product-id' => $this->get_id()
		], $options->get_membership_page_url()) );

		return $url;


	}

	public function get_preview_image_url() {

		//return 'https://picsum.photos/id/' . rand( 395, 405 ) . '/700/350';
		return 'http://multisite.local/wp-content/uploads/2019/10/netflix.png';

	}
}