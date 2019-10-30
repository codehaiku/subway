<?php

namespace Subway\User;

use Subway\FlashMessage\FlashMessage;
use Subway\Helpers\Helpers;
use Subway\Options\Options;
use Subway\Validators\GUMP;

class Controller extends Plans {

	public function __construct() {
		parent::__construct( Helpers::get_db() );
	}

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

	public function cancel_membership() {

		$plan_id = filter_input( 0, 'plan_id', 519 );

		$user_id = get_current_user_id();

		$options = new Options();

		$flash = new FlashMessage( get_current_user_id(), 'user_account_cancelled' );

		if ( $this->cancel_user_plan( $user_id, $plan_id ) ) {

			$flash->add(
				[
					'type'    => 'success',
					'message' => __( 'You have successfully cancelled your membership.', 'subway' )
				]
			);

			wp_safe_redirect( $options->get_accounts_page_url(), 302 );

			exit;

		} else {

			$flash->add(
				[
					'type'    => 'danger',
					'message' => __( 'An error occurred while we are cancelling your membership. Please contact administrator.', 'subway' )
				]
			);

			wp_safe_redirect( $options->get_accounts_page_url(), 302 );

			exit;
		}

	}

	public function attach_hooks() {
		$this->define_hooks();
	}

	protected function define_hooks() {

		add_action( 'admin_post_subway_user_edit_profile', [ $this, 'edit_profile' ] );
		add_action( 'admin_post_subway_user_edit_email', [ $this, 'edit_email' ] );
		add_action( 'admin_post_subway_user_account_cancel_membership', [ $this, 'cancel_membership' ] );
	}
}