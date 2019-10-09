<?php
namespace Subway\Enqueue;

class Enqueue {

	public function assets_loaded()
	{
		// General stylesheets.
		wp_register_style( 'subway-general', 
			plugins_url('subway/web/css/subway.css', SUBWAY_DIR_PATH), 
				array(), false, 'all' );

		// Global scripts.
		wp_register_script( 'subway-global',
			plugins_url('subway/web/js/global.js', SUBWAY_DIR_PATH),
			array('jquery'), false, false );

		// Localize the script.
		wp_localize_script( 'subway-global', 'subway_config', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'login_http_error' => esc_html__( 'An error occured while transmitting the data. Refresh the page and try again', 'subway' ), )
		);

		wp_enqueue_style('subway-general');
	}

	public function assets_admin_loaded()
	{
		// General admin scripts.
		wp_register_script( 'subway-general', 
			plugins_url('subway/web/js/general.js', SUBWAY_DIR_PATH), 
				array('jquery'), false, false );

		wp_register_script( 'subway-product-add-js',
			SUBWAY_JS_URL . 'product-new.js',
			[ 'jquery', 'subway-admin-js' ] );

		wp_register_script( 'subway-product-update-js',
			SUBWAY_JS_URL . '/product-update.js',
			[ 'jquery', 'subway-admin-js' ] );
	}

	public function attach_hooks() 
	{
		$this->define_hooks();
	}

	private function define_hooks()
	{
		add_action( 'admin_enqueue_scripts', array( $this, 'assets_admin_loaded') );

		add_action( 'wp_enqueue_scripts', array( $this, 'assets_loaded') );

		return;
	}
}