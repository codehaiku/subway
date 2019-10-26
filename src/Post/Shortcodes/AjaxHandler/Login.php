<?php

namespace Subway\Post\Shortcodes\AjaxHandler;

use Subway\Options\Options;

class Login {

	public function handle_login_request() {

		// Set the header type to json.
		header( 'Content-Type: application/json' );

		$is_signing = new \WP_Error( 'broke', __( 'Unable to authenticate user', 'subway' ) );

		$log = filter_input( INPUT_POST, 'log', FILTER_SANITIZE_STRING );

		$pwd = filter_input( INPUT_POST, 'pwd', FILTER_SANITIZE_STRING );

		if ( empty( $log ) && empty( $pwd ) ) {

			$response['type'] = 'error';

			$response['message'] = esc_html__(
				'Username and Password cannot be empty.',
				'subway'
			);

		} else {

			$is_signing = wp_signon();

			$response = array();

			if ( is_wp_error( $is_signing ) ) {

				$response['type'] = 'error';

				$response['message'] = $is_signing->get_error_message();

			} else {

				$response['type'] = 'success';

				$response['message'] = esc_html__(
					'You have successfully logged-in. Redirecting you in few seconds...',
					'subway'
				);

			}
		}

		$subway_redirect_url = $this->get_login_redirect_url( '', $is_signing );

		$response['redirect_url'] = apply_filters(
			'subway_login_redirect', $subway_redirect_url, $is_signing
		);

		echo wp_json_encode( $response );

		wp_die();

	}

	public function get_login_redirect_url( $redirect_to, $user ) {

		$subway_redirect_type = get_option( 'subway_redirect_type' );

		// Redirect the user to default behaviour.
		// If there are no redirect type option saved.
		if ( empty( $subway_redirect_type ) ) {

			return $redirect_to;

		}

		if ( 'default' === $subway_redirect_type ) {
			return $redirect_to;
		}

		if ( 'page' === $subway_redirect_type ) {

			// Get the page url of the selected page.
			// If the admin selected 'Custom Page' in the redirect type settings.
			$selected_redirect_page = intval( get_option( 'subway_redirect_page_id' ) );

			// Redirect to default WordPress behaviour.
			// If the user did not select page.
			if ( empty( $selected_redirect_page ) ) {

				return $redirect_to;
			}

			// Otherwise, get the permalink of the saved page
			// and let the user go into that page.
			return get_permalink( $selected_redirect_page );

		} elseif ( 'custom_url' === $subway_redirect_type ) {

			// Get the custom url saved in the redirect type settings.
			$entered_custom_url = get_option( 'subway_redirect_custom_url' );

			// Redirect to default WordPress behaviour
			// if the user did enter a custom url.
			if ( empty( $entered_custom_url ) ) {

				return $redirect_to;

			}

			// Otherwise, get the custom url saved
			// and let the user go into that page.
			if ( ! empty( $user->ID ) ) {
				$entered_custom_url = str_replace(
					'%user_id%', $user->ID,
					$entered_custom_url
				);
			}

			if ( ! empty( $user->user_login ) ) {
				$entered_custom_url = str_replace(
					'%user_name%', $user->user_login,
					$entered_custom_url
				);
			}

			return $entered_custom_url;

		}

		// Otherwise, quit and redirect the user back to default WordPress behaviour.
		return $redirect_to;

	}
}