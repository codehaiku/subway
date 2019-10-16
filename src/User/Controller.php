<?php

namespace Subway\User;

use Subway\FlashMessage\FlashMessage;
use Subway\Validators\GUMP;

class Controller {

	public function edit_profile() {

		$validator = new GUMP();

		$flash = new FlashMessage( get_current_user_id(), 'subway-user-edit-profile' );

		$rules = [
			'name'      => 'required|alpha_space|max_len,99',
			'last_name' => 'required|alpha_space|max_len,99',
		];

		$validator->validation_rules( $rules );

		$validated = $validator->run( $_POST );

		if ( false === $validated ) {
			$flash->add( [ 'type' => 'error', 'message' => $validator->get_errors_array() ] );
			wp_safe_redirect( wp_get_referer(), 302 );
			exit;
		}

		$validated = $validator->sanitize( $validated );

		$updated = wp_update_user( [
			'ID'           => get_current_user_id(),
			'first_name'   => $validated['name'],
			'last_name'    => $validated['last_name'],
			'display_name' => $validated['display_name']
		] );

		$flash->add( [ 'type' => 'success', 'message' => __( 'Profile has been successfully updated.', 'subway' ) ] );

		wp_safe_redirect( wp_get_referer(), 302 );

		exit;

	}

	public function edit_email() {

		$validator = new GUMP();

		$flash = new FlashMessage( get_current_user_id(), 'subway-user-edit-email' );

		$rules = [
			'email' => 'required|valid_email',
		];

		$validator->validation_rules( $rules );

		$validated = $validator->run( $_POST );

		if ( false === $validated ) {
			$flash->add( [ 'type' => 'error', 'message' => $validator->get_errors_array() ] );
			wp_safe_redirect( wp_get_referer(), 302 );
			exit;
		}

		$validated = $validator->sanitize( $validated );

		// Check if there are any changes.
		$current_user = wp_get_current_user();
		if ( $current_user->user_email === $validated['email'] ) {
			$flash->add( [
				'type'    => 'error',
				'message' => [ 'email' => __( 'The email address you entered is the same as the one you are using.', 'subway' ) ]
			] );
			wp_safe_redirect( wp_get_referer(), 302 );
			exit;
		}

		// Do database check.
		if ( email_exists( $validated['email'] ) ) {
			$flash->add( [
				'type'    => 'error',
				'message' => [ 'email' => __( 'The email address is already used.', 'subway' ) ]
			] );
			wp_safe_redirect( wp_get_referer(), 302 );
			exit;
		}

		$confirm = send_confirmation_on_profile_email();

		$flash->add( [
			'type'    => 'success',
			'message' => sprintf(
				__( 'We have sent an email to %s. Please check your email for confirmation link.', 'subway' ),
				$validated['email']
			)
		] );

		wp_safe_redirect( wp_get_referer(), 302 );

		exit;

	}

	public function attach_hooks() {
		$this->define_hooks();
	}

	protected function define_hooks() {

		add_action( 'admin_post_subway_user_edit_profile', [ $this, 'edit_profile' ] );
		add_action( 'admin_post_subway_user_edit_email', [ $this, 'edit_email' ] );
	}
}