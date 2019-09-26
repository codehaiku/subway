<?php

namespace Subway\Post;

use Subway\User\User;
use Subway\Options\Options;

class Post {

	private $user;

	private $options;

	public function __construct( User $user, Options $options ) {

		$this->user    = $user;

		$this->options = $options;

	}

	public function get_types( $args = '', $output = '' ) {

		if ( empty( $args ) ) {
			$args   = array( 'public' => true );
			$output = 'names';
		}

		$post_types = get_post_types( $args, $output );

		return $post_types;

	}

	public function get_allowed_roles( $post_id ) {

		$allowed_roles = array();

		if ( ! empty( $post_id ) ) {

			// Check if metadata exists for the following post.
			if ( metadata_exists( 'post', $post_id, 'subway-visibility-settings-allowed-user-roles' ) ) {

				$allowed_roles = get_post_meta( $post_id, 'subway-visibility-settings-allowed-user-roles', true );

				if ( ! is_null( $allowed_roles ) ) {
					return $allowed_roles;
				}

				return false;

			} else {

				return false;

			}

		} else {

			return false;

		}

		return $allowed_roles;

	}

	public function get_access_type( $post_id ) {

		$user_roles = get_post_meta( $post_id, 'subway-visibility-settings-allowed-user-roles', true );

		$visibility = get_post_meta( $post_id, 'subway_visibility_meta_key', true );

		if ( empty( $visibility ) ) {
			$visibility = 'public';
		}

		if ( empty( $user_roles ) ) {
			$user_roles = array();
		}

		return array(
			'type'              => $visibility,
			'roles'             => $user_roles,
			'subscription_type' => array()
		);

	}

	public function get_no_access_type( $post_id ) {

		$allowed_no_access_type = array( 'block_content', 'redirect' );

		$post_no_access_type = get_post_meta( $post_id, 'subway-visibility-settings-no-access-type', true );

		if ( empty( $post_no_access_type ) || ! in_array( $post_no_access_type, $allowed_no_access_type ) ) {

			$post_no_access_type = 'block_content';

		}

		return $post_no_access_type;

	}

	public function is_private( $post_id ) {

		$meta_value = '';

		if ( ! empty( $post_id ) ) {
			$meta_value = get_post_meta( $post_id, 'subway_visibility_meta_key', true );

			// Pages that dont have meta values yet. 
			if ( empty( $meta_value ) ) {
				// Give it a public visibility.
				return false;
			}
			if ( 'private' === $meta_value ) {
				return true;
			}
		}

		return false;

	}

	public function is_redirect( $post_id ) {

		$post_no_access_type = get_post_meta( $post_id, 'subway-visibility-settings-no-access-type', true );

		if ( ! empty ( $post_no_access_type ) ) {
			if ( 'redirect' === $post_no_access_type ) {
				return true;
			}
		}

		return false;
	}

	private function redirect() {

		$internal_pages = $this->options->get_internal_pages();

		$current_page_id = get_queried_object_id();

		$is_post_type_redirect = $this->is_redirect( $current_page_id );

		$login_page_id = intval( get_option( 'subway_login_page' ) );

		$login_page_url = $this->options->get_redirect_url();

		// Only run on main query.
		if ( ! is_singular() && ! is_home() ) {
			return;
		}

		if ( ! $is_post_type_redirect ) {
			return;
		}

		// Prevent infinite loop.
		if ( in_array( $current_page_id, $internal_pages ) ) {
			return;
		}

		if ( ! $this->user->is_subscribed( $current_page_id, $user ) ) {

			wp_safe_redirect( $login_page_url, 302 );

			exit;
		}

		return;
	}

	public function hook_wp() {

		$this->redirect();

	}

	public function hook_the_content( $content ) {

		$internal_pages  = $this->options->get_internal_pages();

		$current_page_id = get_queried_object_id();

		// Only run on main query.
		if ( ! is_singular() && is_main_query() && ! is_feed() ) {
			return $content;
		}

		// Just show the content if current page is internal page.
		if ( in_array( $current_page_id, $internal_pages ) ) {
			return $content;
		}

		$post_id = get_queried_object_id();

		if ( ! $this->user->is_subscribed( $post_id ) ) {
			$access_block_message = get_post_meta( $post_id, 'subway-visibility-settings-no-access-type-message', true );

			$wrap_start = '<div class="widget-subway-no-access-message">';
			$wrap_end   = '</div>';

			return $wrap_start . wp_kses_post( $access_block_message ) . $wrap_end;
		}

		return $content;
	}

	public function attach_hooks() {

		$this->define_hooks();

		return;

	}

	private function define_hooks() {

		add_action( 'wp', array( $this, 'hook_wp' ), 10, 1 );

		add_action( 'the_content', array( $this, 'hook_the_content' ), 10, 1 );

		return;
	}
}