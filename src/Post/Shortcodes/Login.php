<?php

namespace Subway\Post\Shortcodes;

use Subway\View\View;
use Subway\Post\Shortcodes\AjaxHandler\Login as LoginAjax;

class Login {

	public function __construct( View $view ) {
		$this->view = $view;
	}

	public function login_form( $attributes ) {

		$atts = shortcode_atts( array(
			'echo'           => true,
			'form_id'        => 'loginform',
			'label_username' => __( 'Username', 'subway' ),
			'label_password' => __( 'Password', 'subway' ),
			'label_remember' => __( 'Remember Me', 'subway' ),
			'label_log_in'   => __( 'Log In', 'subway' ),
			'id_username'    => 'user_login',
			'id_password'    => 'user_pass',
			'id_remember'    => 'rememberme',
			'id_submit'      => 'wp-submit',
			'remember'       => true,
			'value_username' => '',
			'value_remember' => false,
			'redirect'       => home_url(),
		), $atts );

		wp_enqueue_script( 'subway-global' );

		wp_enqueue_script( 'subway-shortcode-login' );

		return $this->view->render( 'shortcode-login', [ 'atts' => $atts ], true );

	}

	public function handle_login_request()
	{
		$ajax_login = new LoginAjax();
		$ajax_login->handle_login_request();
	}
	public function enqueue_script() {

		wp_register_script(
			'subway-shortcode-login',
			plugin_dir_url( SUBWAY_DIR_PATH ) . '/subway/web/js/shortcode-login.js',
			[ 'jquery' ],
			true,
			true
		);

	}

	public function attach_hooks() {

		$this->define_hooks();

	}

	public function define_hooks() {

		add_shortcode( 'subway_login', array( $this, 'login_form' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_script' ) );

		add_action( 'wp_ajax_nopriv_subway_logging_in', array( $this, 'handle_login_request' ) );

	}


}