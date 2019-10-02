<?php

namespace Subway\Api;

/**
 * Class Products
 * @package Subway\Api
 */
class Products extends \WP_REST_Controller {

	public function register_routes() {

		$version   = 1;
		$namespace = 'subway/v' . $version;
		$base      = 'membership';

		// New Product.
		register_rest_route( $namespace, '/' . $base . '/new-product', array(
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'add_product' ),
				'permission_callback' => array( $this, 'permission_check' ),
				'args'                => $this->get_endpoint_args_for_item_schema( false ),
			),
		) );

		// Delete Product.
		register_rest_route( $namespace, '/' . $base . '/delete-product', array(
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'delete_product' ),
				'permission_callback' => array( $this, 'permission_check' ),
				'args'                => $this->get_endpoint_args_for_item_schema( false ),
			),
		) );

		// Edit Product.
		register_rest_route( $namespace, '/' . $base . '/update-product', array(
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'update_product' ),
				'permission_callback' => array( $this, 'permission_check' ),
				'args'                => $this->get_endpoint_args_for_item_schema( false ),
			),
		) );

	}

	public function delete_product( \WP_REST_Request $request ) {

		$id       = $request->get_param( 'id' );
		$redirect = $request->get_param( 'redirect' );
		$nonce    = $request->get_param( '_wpnonce' );

		check_admin_referer();

		if ( 'yes' === $redirect ) {
			$url = add_query_arg( 'page', 'subway-membership', get_admin_url() );
			wp_safe_redirect( esc_url( $url ), 302 );
		}

		exit;

	}

	/**
	 * Adds a new product to the database.
	 *
	 * @param $request
	 *
	 * @return \WP_REST_Response
	 */
	public function add_product( \WP_REST_Request $request ) {

		global $wpdb;

		$title = $request->get_param( 'title' );

		$desc = $request->get_param( 'description' );

		$amount = $request->get_param( 'amount' );

		$type = $request->get_param( 'type' );

		$table = $this->get_table_name();

		$data = array(
			'name'        => $title,
			'description' => $desc,
			'type'        => $type,
			'amount'      => $amount
		);

		if ( empty( $title ) ) {
			return new \WP_REST_Response(
				array(
					'is_error' => true,
					'message'  => 'Title is required.'
				), 200
			);
		}

		$format = array( '%s', '%s', '%s', '%f' );

		$inserted = $wpdb->insert( $table, $data, $format );

		if ( $inserted ) {
			// Update the total membership count.
			$current_total = get_option( 'subway_products_count', 0 );
			update_option( 'subway_products_count', absint( $current_total ) + 1 );
		}

		return new \WP_REST_Response(
			array(
				'is_error' => false,
				'message'  => 'Successfully added new product',
				'data'     => array(
					'title'       => $title,
					'description' => $desc,
					'type' => $type,
					'amount' => $amount
				)
			), 200 );
	}

	/**
	 * Updates the product record.
	 *
	 * @param $request
	 *
	 * @return \WP_REST_Response
	 */
	public function update_product( $request ) {

		$id = $request->get_param( 'id' );

		$title = $request->get_param( 'title' );

		$desc = $request->get_param( 'description' );

		$membership = new \Subway\Memberships\Products\Products();

		$membership->update( [ 'id' => $id, 'title' => $title, 'description' => $desc ] );

		return new \WP_REST_Response(
			array(
				'is_error' => false,
				'message'  => 'Successfully updated product',
				'data'     => array(
					'title'       => $title,
					'description' => $desc
				)
			), 200 );

	}

	/**
	 * Callback function for checking permissions.
	 *
	 * @param $request
	 *
	 * @return bool
	 */
	public function permission_check( $request ) {
		// Testing purposes no need permission check for now.
		return true;
	}

	/**
	 * Returns the prefixed table name for memberships_products.
	 *
	 * @return string
	 */
	protected function get_table_name() {

		global $wpdb;

		return $wpdb->prefix . 'subway_memberships_products';

	}

	/**
	 * Register js files.
	 *
	 * @return $this
	 */
	public function register_scripts() {

		wp_register_script( 'subway-admin-js',
			SUBWAY_JS_URL . 'admin.js',
			[ 'jquery' ] );

		wp_register_script( 'subway-membership-add-js',
			SUBWAY_JS_URL . 'product-new.js',
			[ 'jquery', 'subway-admin-js' ] );

		wp_register_script( 'subway-membership-update-js',
			SUBWAY_JS_URL . '/product-update.js',
			[ 'jquery', 'subway-admin-js' ] );

		wp_localize_script( 'subway-admin-js', 'subway_api_settings', array(
			'root'  => esc_url_raw( rest_url() ),
			'nonce' => wp_create_nonce( 'wp_rest' )
		) );

		return $this;
	}

	/**
	 * Deletes product.
	 *
	 * @return $this
	 */
	public function listing_delete_action() {

		status_header( 200 );

		$products = new \Subway\Memberships\Products\Products();

		$product_id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );

		check_admin_referer( 'trash_product_' . absint( $product_id ) );

		$is_deleted = $products->delete( $product_id );

		if ( $is_deleted ) {

			$http_referrer = add_query_arg(
				[ 'page' => 'subway-membership' ],
				get_admin_url() . 'admin.php'
			);

			wp_safe_redirect(
				esc_url( $http_referrer ),
				302
			);

		}

		return $this;

	}

	/**
	 * Attach all wp hooks.
	 *
	 * @return $this
	 */
	public function attach_hooks() {

		$this->define_hooks();

		return $this;

	}

	/**
	 * Define all wp hooks.
	 *
	 * @return $this
	 */
	protected function define_hooks() {

		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ), 10, 1 );

		add_action( 'rest_api_init', function () {
			$this->register_routes();
		} );

		// Add delete action as part of the api (for now).

		add_action( 'admin_post_listing_delete_action', array( $this, 'listing_delete_action' ) );


		return $this;

	}

}


