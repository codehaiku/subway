<?php

namespace Subway\User;

use Subway\FlashMessage\FlashMessage;
use Subway\Validators\GUMP;

class Controller {

	public function edit_profile() {

		$validator = new GUMP();

		$flash = new FlashMessage( get_current_user_id(), 'subway-user-edit-profile' );

		$rules = [
			'name'      => 'required|alpha|max_len,99',
			'last_name' => 'required|alpha|max_len,99',
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

	public function attach_hooks() {
		$this->define_hooks();
	}

	protected function define_hooks() {
		add_action( 'admin_post_subway_user_edit_profile', [ $this, 'edit_profile' ] );
	}
}