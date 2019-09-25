<?php

namespace Subway\Post;

use Subway\Post\Post;
use Subway\View\View;
use Subway\Options\Options;
use Subway\Helpers\Helpers;

class Metabox {

	protected $post;
	protected $view;
	protected $options;
	protected $helpers;

	/**
	 * Metabox constructor.
	 *
	 * @param \Subway\Post\Post $post
	 * @param View $view
	 * @param Options $options
	 * @param Helpers $helpers
	 */
	public function __construct( Post $post, View $view, Options $options, Helpers $helpers ) {
		
		$this->post    = $post;
		$this->view    = $view;
		$this->options = $options;
		$this->helpers = $helpers;

		return;
	}

	/**
	 * Adds metabox right into each post type.
	 */
	private function add_meta_boxes() {
		$post_types = $this->post->get_types();

		foreach ( $post_types as $post_type => $value ) {
			add_meta_box( 'subway_comment_metabox',
				esc_html__( 'Membership Discussion', 'subway' ),
				function ( $post ) {
					$this->discussion( $post );
				},
				$post_type, 'side', 'high'
			);
			add_meta_box( 'subway_visibility_metabox',
				esc_html__( 'Membership Access', 'subway' ),
				function ( $post ) {
					$this->visibility( $post );
				},
				$post_type, 'side', 'high'
			);
		}

	}

	/**
	 * @param $post
	 */
	private function visibility( $post ) {
		$this->view->render( 'form-post-visiblity-metabox', [
			'class_post' => $this->post,
			'options'    => $this->options,
			'helpers'    => $this->helpers,
			'post'       => $post,
		] );
	}

	/**
	 * @param $post
	 */
	private function discussion( $post ) {
		$this->view->render( 'form-post-discussion-metabox', [
			'post_id' => $post->ID,
			'helpers' => $this->helpers,
		] );
	}

	/**
	 * @param $post_id
	 */
	private function save( $post_id ) {

		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		$is_form_submitted = filter_input( INPUT_POST, 'subway-visibility-form-submitted', FILTER_DEFAULT );

		if ( ! $is_form_submitted ) {
			return;
		}

		$visibility_nonce = filter_input(
			INPUT_POST,
			'subway_post_visibility_nonce',
			FILTER_SANITIZE_STRING
		);

		$post_visibility = filter_input(
			INPUT_POST,
			'subway-visibility-settings',
			FILTER_SANITIZE_STRING
		);

		$is_nonce_valid = $this->is_nonce_valid( $visibility_nonce );

		$allowed_roles = filter_input(
			INPUT_POST,
			'subway-visibility-settings-user-role',
			FILTER_DEFAULT,
			FILTER_REQUIRE_ARRAY
		);

		$comments_allowed_roles = filter_input(
			INPUT_POST,
			'subway_post_discussion_roles',
			FILTER_DEFAULT,
			FILTER_REQUIRE_ARRAY
		);

		$subway_post_discussion_access_type = filter_input(
			INPUT_POST,
			'subway_post_discussion_access_type',
			FILTER_SANITIZE_STRING
		);

		$no_access_type = filter_input(
			INPUT_POST,
			'subway-visibility-settings-no-access-type',
			FILTER_SANITIZE_STRING
		);

		$access_type_block_message = filter_input(
			INPUT_POST,
			'subway-visibility-settings-no-access-type-message',
			FILTER_DEFAULT
		);

		// Check the nonce.
		if ( false === $is_nonce_valid ) {
			return;
		}

		if ( empty( $allowed_roles ) ) {
			$allowed_roles = array();
		}

		if ( empty( $comments_allowed_roles ) ) {
			$comments_allowed_roles = array();
		}

		// Update user roles.
		update_post_meta( $post_id, 'subway-visibility-settings-allowed-user-roles', $allowed_roles );

		// Update comment access type.
		update_post_meta( $post_id, 'subway_post_discussion_access_type', $subway_post_discussion_access_type );

		// Update comment user roles.
		update_post_meta( $post_id, 'subway_post_discussion_roles', $comments_allowed_roles );

		// Update no access control.
		update_post_meta( $post_id, 'subway-visibility-settings-no-access-type', $no_access_type );

		if ( 'inherit' !== get_post_status( $post_id ) ) {
			if ( true === $is_nonce_valid ) {
				update_post_meta( $post_id, 'subway_visibility_meta_key', $post_visibility );
			}
		}

		update_post_meta( $post_id, 'subway-visibility-settings-no-access-type-message', $access_type_block_message );

		return;

	}

	/**
	 * @param $nonce
	 * Checks if nonce is valid.
	 *
	 * @return bool
	 */
	private function is_nonce_valid( $nonce ) {

		if ( ! isset( $nonce ) || ! wp_verify_nonce( $nonce, 'form-post-visiblity-metabox.php' ) ) {
			return false;
		}

		return true;

	}

	/**
	 * @return $this
	 */
	public function hook_add_meta_boxes() {
		$this->add_meta_boxes();

		return $this;

	}

	/**
	 * @param $post_id
	 * Callback method for saving post via 'save_post' hook.
	 *
	 * @return $this
	 */
	public function hook_save_post( $post_id ) {
		$this->save( $post_id );

		return $this;
	}

	/**
	 * Public method for attaching hooks to wp.
	 */
	public function attach_hooks() {
		$this->define_hooks();
	}

	/**
	 * This is where we hook our method to wp.
	 */
	private function define_hooks() {

		add_action( 'add_meta_boxes', array( $this, 'hook_add_meta_boxes' ), 10, 1 );

		add_action( 'save_post', array( $this, 'hook_save_post' ), 10, 1 );

		return;
	}
}
