<?php

namespace Subway\Memberships\Product;

use EventEspresso\core\domain\services\validation\email\strategies\Basic;
use Subway\Helpers\Helpers;

class Product {

	protected $id;
	protected $name;
	protected $description;
	protected $status = 'draft';
	protected $tax_rate;
	protected $tax_displayed = false;
	protected $date_created = '';
	protected $date_updated = '';

	private $table = '';

	public function __construct() {

		$db = Helpers::get_db();

		$this->table = $db->prefix . 'subway_memberships_products';

		return $this;

	}

	/**
	 * @return mixed
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 *
	 * @return Product
	 */
	public function set_id( $id ) {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @param mixed $name
	 *
	 * @return Product
	 */
	public function set_name( $name ) {
		$this->name = $name;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * @param mixed $description
	 *
	 * @return Product
	 */
	public function set_description( $description ) {
		$this->description = $description;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_status() {
		return $this->status;
	}

	/**
	 * @param string $status
	 *
	 * @return Product
	 */
	public function set_status( $status ) {
		$this->status = $status;

		return $this;
	}


	/**
	 * @return mixed
	 */
	public function get_tax_rate() {
		return $this->tax_rate;
	}

	/**
	 * @param mixed $tax_rate
	 *
	 * @return Product
	 */
	public function set_tax_rate( $tax_rate ) {
		$this->tax_rate = $tax_rate;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function is_tax_displayed() {
		return $this->tax_displayed;
	}

	/**
	 * @param bool $is_tax_displayed
	 *
	 * @return Product
	 */
	public function set_tax_displayed( $is_tax_displayed ) {
		$this->tax_displayed = $is_tax_displayed;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_date_created() {
		return $this->date_created;
	}

	/**
	 * @param string $date_created
	 *
	 * @return Product
	 */
	public function set_date_created( $date_created ) {
		$this->date_created = $date_created;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_date_updated() {
		return $this->date_updated;
	}

	/**
	 * @param string $date_updated
	 *
	 * @return Product
	 */
	public function set_date_updated( $date_updated ) {
		$this->date_updated = $date_updated;

		return $this;
	}

	public function get_preview_image_url() {

		return 'https://picsum.photos/id/' . rand( 200, 400 ) . '/700/400';

	}

	public function fetch_all( $args = [] ) {

		$db = Helpers::get_db();

		$defaults = [
			'per_page'     => 12,
			'current_page' => 1,
		];

		$args = wp_parse_args( $args, $defaults );

		// Manually determine page query offset (offset + current page (minus one) x posts per page).
		$args['offset'] = ( absint( $args['current_page'] ) - 1 ) * absint( $args['per_page'] );

		$stmt = $db->prepare(
			"SELECT SQL_CALC_FOUND_ROWS * FROM $this->table WHERE id > %d
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
			'tax_rate'      => 0,
			'tax_displayed' => false,
			'date_updated'  => current_time( 'mysql' ),
		];

		$data = wp_parse_args( [
			'name'          => $this->get_name(),
			'description'   => $this->get_description(),
			'tax_rate'      => $this->get_tax_rate(),
			'tax_displayed' => $this->is_tax_displayed()
		], $defaults );

		$where = [
			'id' => $this->get_id()
		];

		$format = [
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

}

