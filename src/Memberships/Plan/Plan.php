<?php

namespace Subway\Memberships\Plan;

use Subway\Currency\Currency;

/**
 * Class Plan
 * @package Subway\Memberships\Plan
 */
class Plan {

	var $table = '';

	protected $id = '';
	protected $name = '';
	protected $product_id = '';
	protected $sku = '';
	protected $status = '';
	protected $description = '';
	protected $amount = 0.00;
	protected $real_amount = 0.00;
	protected $type = '';
	protected $date_created = '';
	protected $date_updated = '';
	protected $tax_rate = '';
	protected $display_tax = true;


	public function __construct() {

		global $wpdb;

		$this->table = $wpdb->prefix . 'subway_memberships_products_plans';

		$this->tax_rate = get_option( 'subway_tax_rate', 0.00 );

	}

	/**
	 * @return bool
	 */
	public function is_display_tax() {
		return $this->display_tax;
	}

	/**
	 * @param bool $display_tax
	 *
	 * @return Plan
	 */
	public function set_display_tax( $display_tax ) {
		$this->display_tax = $display_tax;

		return $this;
	}


	/**
	 * @return float
	 */
	public function get_real_amount() {
		return $this->real_amount;
	}

	/**
	 * @param float $real_amount
	 *
	 * @return Plan
	 */
	public function set_real_amount( $real_amount ) {
		$this->real_amount = $real_amount;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function get_tax_rate() {
		return $this->tax_rate;
	}


	/**
	 * @param string $id
	 *
	 * @return Plan
	 */
	public function set_id( $id ) {
		$this->id = $id;

		return $this;
	}


	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @param string $name
	 *
	 * @return Plan
	 */
	public function set_name( $name ) {
		$this->name = $name;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_product_id() {
		return $this->product_id;
	}

	/**
	 * @param string $product_id
	 *
	 * @return Plan
	 */
	public function set_product_id( $product_id ) {
		$this->product_id = $product_id;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_sku() {
		return $this->sku;
	}

	/**
	 * @param string $sku
	 *
	 * @return Plan
	 */
	public function set_sku( $sku ) {
		$this->sku = $sku;

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
	 * @return Plan
	 */
	public function set_status( $status ) {
		$this->status = $status;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * @param string $description
	 *
	 * @return Plan
	 */
	public function set_description( $description ) {
		$this->description = $description;

		return $this;
	}

	/**
	 * @return float
	 */
	public function get_amount() {

		$tax = $this->get_tax_rate();

		$tax_displayed = true;

		// Allow to specify whether to display the tax or not.
		if ( ! $this->is_display_tax() ) {
			$tax_displayed = false;
		}

		$item_cost = $this->amount;

		$amount = $item_cost;

		if ( $tax_displayed ) {

			$sales_tax = $tax / 100;

			$sales_tax = $item_cost * $sales_tax;

			$amount = $item_cost + $sales_tax;

		}

		return apply_filters( 'subway_product_get_amount', $amount );

	}

	/**
	 * @return mixed|void
	 */
	public function get_displayed_price() {

		$currency = new Currency();

		$displayed_price = $this->get_amount();

		$formatted_amount = $currency->format( $displayed_price, get_option( 'subway_currency' ) );

		if ( 'free' === $this->get_type() ) {
			$formatted_amount = esc_html__( 'Free', 'subway' );
		}

		return apply_filters( 'subway_product_get_displayed_price', $formatted_amount );

	}

	public function get_displayed_price_without_tax() {

		$currency = new Currency();

		$displayed_price = $this->get_real_amount();

		$formatted_amount = $currency->format( $displayed_price, get_option( 'subway_currency' ) );

		if ( 'free' === $this->get_type() ) {
			$formatted_amount = esc_html__( 'Free', 'subway' );
		}

		return apply_filters( 'subway_product_get_displayed_price_with_out_tax', $formatted_amount );
	}

	public function get_taxed_price() {

		$currency = new Currency();

		$tax_rate    = 1 + ( $this->get_tax_rate() / 100 );

		$taxed_price = $this->amount * $tax_rate;

		$amount = $currency->format( $taxed_price, get_option( 'subway_currency', 'USD ' ) );

		return apply_filters( 'subway_product_get_amount', $amount );

	}

	public function get_tax_amount() {

		$currency = new Currency();

		$tax_rate = $this->get_tax_rate() / 100;

		$taxed_price = $this->amount * $tax_rate;

		$tax_amount = $currency->format( $taxed_price, get_option( 'subway_currency', 'USD ' ) );

		return apply_filters( 'subway_product_get_tax_amount', $tax_amount );

	}

	/**
	 * @param float $amount
	 *
	 * @return Plan
	 */
	public function set_amount( $amount ) {

		$this->amount = $amount;

		$this->set_real_amount( $this->amount );

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_type() {

		return $this->type;

	}

	/**
	 * @param string $type
	 *
	 * @return Plan
	 */
	public function set_type( $type ) {

		$this->type = $type;

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
	 * @return Plan
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
	 * @return Plan
	 */
	public function set_date_updated( $date_updated ) {

		$this->date_updated = $date_updated;

		return $this;

	}


	public function get_plans( $args ) {

		global $wpdb;

		$defaults = array(
			'limit'      => 60,
			'offset'     => 0,
			'product_id' => 0,
			'orderby'    => 'id',
			'direction'  => 'DESC',
			'status'     => '',
			'name_like'  => ''
		);

		$args = wp_parse_args( $args, $defaults );

		$orderby = $args['orderby'];

		if ( ! in_array( $args['status'], [ 'draft', 'published', 'trashed' ] ) ) {
			$args['status'] = "'draft', 'published'";
		} else {
			$args['status'] = '\'' . $args['status'] . '\'';
		}

		$search_query = $wpdb->prepare( "AND name like '%%%s%%' ", $args['name_like'] );

		$product_query = $wpdb->prepare( 'AND product_id = %d', $args['product_id'] );

		if ( empty( $args['name_like'] ) ) {
			$search_query = '';
		}

		if ( empty( $args['product_id'] ) ) {
			$product_query = '';
		}

		$direction = strtoupper( $args['direction'] );

		$stmt = $wpdb->prepare( "SELECT status, id, name, product_id, sku, description, type, amount, date_created, date_updated 
			FROM $this->table 
			WHERE status IN (" . $args['status'] . ")
			$product_query
			$search_query 
			ORDER BY $orderby $direction LIMIT %d, %d",
			array( $args['offset'], $args['limit'] ) );

		$results = $wpdb->get_results( $stmt, OBJECT );

		$products = [];

		$tax_displayed = absint( get_option( 'subway_display_tax', 1 ) );

		foreach ( $results as $result ) {

			$p = new Plan();

			// Disable tax when administrator disable from option.
			if ( 0 === $tax_displayed ) {
				$p->set_display_tax( false );
			}

			$p->set_id( $result->id )
			  ->set_name( $result->name )
			  ->set_product_id( $result->product_id )
			  ->set_amount( $result->amount )
			  ->set_sku( $result->sku )
			  ->set_description( $result->description )
			  ->set_status( $result->status )
			  ->set_type( $result->type )
			  ->set_date_created( $result->date_created )
			  ->set_date_updated( $result->date_updated );

			$products[] = $p;

		}

		if ( empty( $products ) ) {

			return false;

		}

		return $products;

	}

	/**
	 * This method returns the product in array format.
	 * Useful for displaying products in WordPress' list table format.
	 *
	 * @param $args
	 *
	 * @return array
	 */
	public function get_wp_list_table( $args ) {

		$products = $this->get_plans( $args );

		$product_collections = [];

		if ( ! empty( $products ) ) {

			foreach ( $products as $product ) {
				$p                     = [];
				$p['id']               = $product->get_id();
				$p['name']             = $product->get_name();
				$p['sku']              = $product->get_sku();
				$p['amount']           = $product->get_amount();
				$p['description']      = $product->get_description();
				$p['type']             = $product->get_type();
				$p['status']           = $product->get_status();
				$p['date_created']     = $product->get_date_created();
				$p['date_updated']     = $product->get_date_updated();
				$product_collections[] = $p;
			}

		}

		return $product_collections;
	}


	public function get_plan( $id ) {

		global $wpdb;

		$stmt = $wpdb->prepare( "SELECT * FROM $this->table WHERE id = %d", absint( $id ) );

		$result = $wpdb->get_row( $stmt, OBJECT );

		if ( ! empty ( $result ) ) {

			$product = new Plan();

			$product->set_id( $result->id );
			$product->set_name( $result->name );
			$product->set_product_id( $result->product_id );
			$product->set_amount( $result->amount );
			$product->set_sku( $result->sku );
			$product->set_description( $result->description );
			$product->set_status( $result->status );
			$product->set_type( $result->type );
			$product->set_date_created( $result->date_created );
			$product->set_date_updated( $result->date_updated );

		} else {

			$product = false;

		}

		return $product;

	}

	public function add( $args = array() ) {

		global $wpdb;

		$defaults = [
			'name'         => '',
			'description'  => '',
			'product_id'   => '',
			'amount'       => 0.00,
			'type'         => 'free',
			'sku'          => '',
			'status'       => 'draft',
			'date_created' => current_time( 'mysql' ),
			'date_updated' => current_time( 'mysql' )
		];

		$r = wp_parse_args( $args, $defaults );

		// check if sku exists.
		$product = $wpdb->insert(
			$this->table,
			$r,
			'',
			);

		if ( ! $product ) {
			return $wpdb->last_error;
		}

		return $wpdb->insert_id;

	}

	public function delete( $product_id ) {

		global $wpdb;

		$is_deleted = $wpdb->delete( $this->table,
			array( 'id' => $product_id ), array( '%d' ) );

		// Update the total membership count.
		if ( $is_deleted ) {

			$current_total = get_option( 'subway_products_count', 0 );

			if ( $current_total !== 0 ) {
				update_option( 'subway_products_count', absint( $current_total ) - 1 );
			}

		}

		return $is_deleted;

	}

	/**
	 * @param array $args
	 *
	 * @return false|int
	 * @throws \Exception
	 */
	public function update( $args = array() ) {

		global $wpdb;

		$data = array(
			'name'         => $args['title'],
			'description'  => $args['description'],
			'product_id'   => $args['product_id'],
			'type'         => $args['type'],
			'amount'       => $args['amount'],
			'sku'          => $args['sku'],
			'status'       => $args['status'],
			'date_updated' => current_time( 'mysql' )
		);

		$table = $this->table;

		$where        = array( 'id' => $args['id'] );
		$format       = array( '%s', '%s', '%s', '%s', '%f', '%s', '%s' );
		$where_format = array( '%d' );

		$updated = $wpdb->update( $table, $data, $where, $format, $where_format );

		if ( false === $updated ) {
			return false;
		}

		return true;

	}

	public function get_plan_checkout_url( $id ) {

		$checkout_url = esc_url( add_query_arg( 'plan_id', $id, 'http://multisite.local/checkout' ) );

		if ( ! is_user_logged_in() ) {
			$checkout_url = esc_url( add_query_arg( 'plan_id', $id, 'http://multisite.local/create-account' ) );
		}

		return apply_filters( 'get_plan_checkout_url', $checkout_url );

	}

	/**
	 * @param $plan_id
	 * @param $product_id
	 *
	 * @return string
	 */
	public function get_edit_url( $plan_id, $product_id = 0 ) {

		$args = [
			'page'    => 'subway-membership-plans',
			'edit'    => 'yes',
			'plan'    => $plan_id,
			'section' => 'plan-information'
		];

		if ( ! empty( $product_id ) ) {
			$args['product'] = $product_id;
		}

		$url = add_query_arg( $args, admin_url( 'admin.php' ) );

		return $url;

	}

	public function get_add_url( $product_id ) {

		$url = add_query_arg( [
			'page'       => 'subway-membership-plans',
			'new'        => 'yes',
			'product_id' => $product_id
		], admin_url( 'admin.php' ) );

		return $url;

	}

	public function get_plan_url() {
		return '#';
	}


}