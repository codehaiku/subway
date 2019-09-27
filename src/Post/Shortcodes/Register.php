<?php

namespace Subway\Post\Shortcodes;

use Subway\Form\Form;
use Subway\View\View;

class Register {

	protected $view;

	public function __construct( View $view ) {
		$this->view = $view;
	}

	public function attach_hooks() {
		$this->define_hooks();
	}

	public function register() {

		$reg_action = filter_input( INPUT_POST, 'sw-action', FILTER_DEFAULT );

		if ( isset( $reg_action ) ) {

			$name     = filter_input( INPUT_POST, 'sw-name', FILTER_SANITIZE_STRING );
			$username = filter_input( INPUT_POST, 'sw-username', FILTER_SANITIZE_STRING );
			$email    = filter_input( INPUT_POST, 'sw-email', FILTER_SANITIZE_EMAIL );
			$password = filter_input( INPUT_POST, 'sw-password', FILTER_SANITIZE_STRING );
			$password_confirm = filter_input( INPUT_POST, 'sw-password-corfirm', FILTER_SANITIZE_STRING );

			$errors = [];

			if ( empty( $username ) ) {
				$errors[] = esc_html__( 'Username is required', 'subway' );
			}

			if ( empty( $email ) ) {
				$errors[] = esc_html__( 'Email is required', 'subway' );
			}

			if ( empty( $password ) ) {
				$errors[] = esc_html__( 'Password is required', 'subway' );
			}

			if ( $password !== $password_confirm ) {
				$errors[] = esc_html__( 'Password does not match', 'subway' );
			}

			if ( ! empty ( $errors ) ) {

				add_filter( 'subway_shortcode_register_errors', function () use ( $errors ) {
					return $errors;
				}, 10, 1 );

				return;
			}

			$user_id = username_exists( $username );

			if ( ! $user_id && email_exists( $email ) == false ) {

				$user_id = wp_create_user( $username, $password, $email );

				wp_update_user( [
					'display_name' => $name,
					'first_name'   => $name,
					'nickname'     => $name
				] );

				$creds = [
					'user_login'    => $username,
					'user_password' => $password,
					'remember'      => true
				];

				$autologin_user = wp_signon( $creds, is_ssl() );

				if ( $autologin_user ) {
					wp_safe_redirect( get_home_url(), 302 );
				}
			}

		}

		return;
	}

	public function display_form() {
		wp_enqueue_style( 'subway-general' );

		return $this->view->render( 'shortcode-register', [], true );
	}

	protected function define_hooks() {
		add_shortcode( 'subway_register', array( $this, 'display_form' ) );
		add_action( 'wp', array( $this, 'register' ) );
	}
}