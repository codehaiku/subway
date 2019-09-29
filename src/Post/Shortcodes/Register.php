<?php

namespace Subway\Post\Shortcodes;

use Subway\Form\Form;
use Subway\Payment\Payment;
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

			$username         = filter_input( INPUT_POST, 'sw-username', FILTER_SANITIZE_STRING );
			$email            = filter_input( INPUT_POST, 'sw-email', FILTER_SANITIZE_EMAIL );
			$password         = filter_input( INPUT_POST, 'sw-password', FILTER_SANITIZE_STRING );
			$password_confirm = filter_input( INPUT_POST, 'sw-password-confirm', FILTER_SANITIZE_STRING );

			if ( $this->process_registration( $username, $email, $password, $password_confirm ) ) {

				$payment = new Payment();
				$payment->pay();

				die();
				// Create the user after successful validation.

				wp_create_user( $username, $password, $email );

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

	private function process_registration( $username, $email, $password, $password_confirm )
	{

		$errors = [];

		if ( empty( $username ) ) {
			$errors['sw-username'] = esc_html__( 'Username is required', 'subway' );
		}

		if ( ! ctype_alnum ( $username ) ) {
			$errors['sw-username'] = esc_html__( 'Username field contains invalid characters', 'subway' );
		}

		if ( empty( $email ) ) {
			$errors['sw-email'] = esc_html__( 'Email is required', 'subway' );
		}

		if ( empty( $password ) ) {
			$errors['sw-password'] = esc_html__( 'Password is required', 'subway' );
		}

		if ( $password !== $password_confirm ) {
			$errors['sw-password-confirm'] = esc_html__( 'Password does not match', 'subway' );
		}

		if ( username_exists( $username ) ) {
			$errors['sw-username'] = esc_html__( 'Username already exists', 'subway' );
		}

		if ( email_exists( $email ) ) {
			$errors['sw-email'] = sprintf( esc_html__( "Existing account is already in used for the email %s",
				'subway' ), $email );
		}

		if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			$errors['sw-email'] = esc_html__( "Invalid email address format", 'subway' );
		}

		if ( ! empty ( $errors ) ) {

			add_filter( 'subway_shortcode_register_errors', function () use ( $errors ) {
				return $errors;
			}, 10, 1 );

			return false;

		}

		return true;

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