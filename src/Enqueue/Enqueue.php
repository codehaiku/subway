<?php
namespace Subway\Enqueue;

class Enqueue {

	public function assets_loaded()
	{
		// General stylesheets.
		wp_register_style( 'subway-general', 
			plugins_url('subway/web/css/subway.css', SUBWAY_DIR_PATH), 
				array(), false, 'all' );
	}

	public function assets_admin_loaded()
	{
		// General admin scripts.
		wp_register_script( 'subway-general', 
			plugins_url('subway/web/js/general.js', SUBWAY_DIR_PATH), 
				array('jquery'), false, false );
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