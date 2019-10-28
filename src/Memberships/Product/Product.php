<?php

namespace Subway\Memberships\Product;

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
	protected $plans = [];
	protected $default_plan_id = '';

	protected $table = '';

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

	/**
	 * @return string
	 */
	public function get_default_plan_id() {
		return $this->default_plan_id;
	}

	/**
	 * @param $default_plan_id
	 *
	 * @return $this
	 */
	public function set_default_plan_id( $default_plan_id ) {
		$this->default_plan_id = $default_plan_id;

		return $this;
	}

}

