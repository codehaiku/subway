<?php

namespace Subway\Memberships\Product;

use EventEspresso\core\domain\services\validation\email\strategies\Basic;
use Subway\Helpers\Helpers;

class Product {

	protected $id;
	protected $name;
	protected $description;
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
			'%s',
			'%s',
			'%f',
			'%d'
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

		if ( $updated ) {
			return true;
		}

		return $db->last_error;

	}

	public function trash() {
	}

	public function delete() {
	}

}

