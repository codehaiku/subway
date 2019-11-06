<?php

namespace Subway\Memberships\Plan;

use Subway\Currency\Currency;
use Subway\Helpers\Helpers;
use Subway\Options\Options;

/**
 * Class Plan
 * @package Subway\Memberships\Plan
 */
class Plan {

	var $table = '';

	protected $id = '';
	protected $name = '';
	protected $product_id = '';
	protected $product = '';
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

	protected $db;


	public function __construct() {

		$this->db = Helpers::get_db();

		$this->table = $this->db->prefix . 'subway_memberships_products_plans';

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
	 * @param int|string $tax_rate
	 */
	public function set_tax_rate( $tax_rate ) {

		$this->tax_rate = $tax_rate;

	}

	/**
	 * @return string
	 */
	public function get_tax_rate() {

		$product = new \Subway\Memberships\Product\Controller();

		$product->set_id( $this->get_product_id() );

		$product = $product->get();

		$this->set_tax_rate( 0 );

		if ( $product ) {

			$this->set_tax_rate( $product->get_tax_rate() );

		} else {

			// Use Options instead.
			$this->set_tax_rate( get_option( 'subway_tax_rate', 0 ) );

		}

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

	public function get_product() {

		$product = new \Subway\Memberships\Product\Controller();
		$product->set_id( $this->get_product_id() );

		// Cache the product.
		$key   = 'subway_membership_plan_get_product_key';
		$group = 'subway_membership_plan_get_product_group';

		$cache = wp_cache_get( $key, $group );

		if ( $cache ) {
			// Return the cache when found.
			return $cache;

		} else {
			// Otherwise, get the product.
			$this->product = $product->get();
			wp_cache_set( $key, $this->product, $group );
		}

		return $this->product;
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

		$tax_rate = 1 + ( $this->get_tax_rate() / 100 );

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


		foreach ( $results as $result ) {

			$p = new Plan();

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

			$tax_displayed = false;

			$product = $p->get_product();

			if ( $product ) {
				$tax_displayed = $product->is_tax_displayed();
			}

			// Disable tax when administrator disable from product option.
			if ( false === $tax_displayed ) {
				$p->set_display_tax( false );
			}

			$plans[] = $p;

		}

		if ( empty( $plans ) ) {

			return false;

		}

		return $plans;

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

		$plans = $this->get_plans( $args );

		$plan_collection = [];

		if ( ! empty( $plans ) ) {

			foreach ( $plans as $plan ) {
				$p                 = [];
				$p['id']           = $plan->get_id();
				$p['name']         = $plan->get_name();
				$p['sku']          = $plan->get_sku();
				$p['amount']       = $plan->get_displayed_price_without_tax();
				$p['description']  = $plan->get_description();
				$p['type']         = $plan->get_type();
				$p['status']       = $plan->get_status();
				$p['date_created'] = $plan->get_date_created();
				$p['date_updated'] = $plan->get_date_updated();
				$plan_collection[] = $p;
			}

		}

		return $plan_collection;
	}


	public function get_plan( $id, $is_published = false ) {

		global $wpdb;

		$stmt = $wpdb->prepare( "SELECT * FROM $this->table WHERE id = %d", absint( $id ) );

		if ( $is_published ) {
			$stmt .= $wpdb->prepare( " AND status = %s", 'published' );
		}

		$result = $wpdb->get_row( $stmt, OBJECT );

		if ( ! empty ( $result ) ) {

			$plan = new Plan();

			$plan->set_id( $result->id );
			$plan->set_name( $result->name );
			$plan->set_product_id( $result->product_id );
			$plan->set_amount( $result->amount );
			$plan->set_display_tax( false );
			$plan->set_sku( $result->sku );
			$plan->set_description( $result->description );
			$plan->set_status( $result->status );
			$plan->set_type( $result->type );
			$plan->set_date_created( $result->date_created );
			$plan->set_date_updated( $result->date_updated );

			$product = $plan->get_product();

			if ( $product ) {
				if ( $product->is_tax_displayed() ) {
					$plan->set_display_tax( true );
				}
			}

		} else {

			$plan = false;

		}

		return $plan;

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

	public function delete( $plan_id ) {

		$is_deleted = $this->db->delete( $this->table, [ 'id' => $plan_id ], [ '%d' ] );

		// Update the total membership count.
		if ( $is_deleted ) {

			$current_total = get_option( 'subway_plans_count', 0 );

			if ( $current_total !== 0 ) {

				update_option( 'subway_plans_count', absint( $current_total ) - 1 );

			}

		}

		return $is_deleted;

	}

	/**
	 * @param array $args
	 * @param int $id
	 *
	 * @return bool
	 */
	public function update( $args = array(), $id = 0 ) {

		$plan = $this->get_plan( $id );

		if ( ! $plan ) {

			return false;

		}

		$updated = $this->db->update( $this->table, $args, [ 'id' => $plan->get_id() ] );

		if ( false === $updated ) {

			return false;

		}

		return true;

	}

	public function get_plan_checkout_url( $id = 0 ) {

		$options = new Options();

		if ( is_user_logged_in() ) {

			$options_checkout_url = $options->get_checkout_page_url();

		} else {

			$options_checkout_url = $options->get_registration_page_url();

		}

		$checkout_url = esc_url( $options_checkout_url );

		if ( ! empty( $id ) ) {

			$checkout_url = esc_url( add_query_arg( 'plan_id', $id, $checkout_url ) );

			if ( ! is_user_logged_in() ) {

				$checkout_url = esc_url( add_query_arg( 'plan_id', $id, $checkout_url ) );

			}

		}

		return apply_filters( 'get_plan_checkout_url', $checkout_url );

	}

	/**
	 * @param $plan_id
	 *
	 * @return string
	 */
	public function get_trash_url( $plan_id ) {

		$args = [
			'action'   => 'subway_plan_trash_action',
			'plan-id'  => $plan_id,
			'_wpnonce' => wp_create_nonce( sprintf( 'subway_plan_trash_action_%d', $plan_id ) )
		];

		return add_query_arg( $args, admin_url( 'admin-post.php' ) );

	}

	public function get_restore_url( $plan_id ) {

		$args = [
			'action'   => 'subway_plan_restore_action',
			'plan-id'  => $plan_id,
			'_wpnonce' => wp_create_nonce( sprintf( 'subway_plan_restore_action_%d', $plan_id ) )
		];

		return add_query_arg( $args, admin_url( 'admin-post.php' ) );

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

	public function get_plan_url( $is_current_plan_selected = true ) {

		$options = new Options();

		$args = [
			'box-membership-product-id' => $this->get_product_id()
		];

		if ( $is_current_plan_selected ) {
			$args['plan-id'] = $this->get_id();
		}

		$edit_url = add_query_arg( $args, $options->get_membership_page_url() );

		return $edit_url . '#box-membership-plan-details';

	}

	public function get_cancel_url() {

		$options = new Options();

		$args = [
			'account-page' => 'cancel-membership',
			'plan-id'      => $this->get_id()
		];

		$cancel_url = add_query_arg( $args, $options->get_accounts_page_url() );

		return apply_filters( 'subway_memberships_plan_get_cancel_url', $cancel_url );

	}

	public function get_product_link() {

		$cache_key = 'box_membership_product_plan_get_product_link';

		$link = wp_cache_get( $cache_key );

		$product = new \Subway\Memberships\Product\Controller();

		if ( false === $link ) {

			$product->set_id( $this->get_product_id() );

			$product = $product->get();

			if ( $product ) {

				$link = '<a href="%1$s" title="%2$s">%2$s</a>';

				$link = sprintf( $link, esc_url( $product->get_url() ), esc_html( $product->get_name() ) );

				wp_cache_set( $cache_key, $link, 'box-membership' );

			}

		}

		return apply_filters( 'box_membership_product_plan_get_product_link', $link, $product );

	}

	public function get_num_total() {

		$stmt = $this->db->prepare( "SELECT COUNT(id) FROM $this->table WHERE id > %d; ", 0 );

		return $this->db->get_var( $stmt );
		
	}

}