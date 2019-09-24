<?php
namespace Subway\Post;
use Subway\Post\Post;
use Subway\View\View;
use Subway\Options\Options;
use Subway\Helpers\Helpers;

class Metabox {

	public function __construct()
	{
		$this->post = new Post();
		$this->view = new View();
		$this->option = new Options();
		$this->helper = new Helpers();
	}

	public function add_meta_boxes()
	{
		$post_types = $this->post->get_types();

		foreach ( $post_types as $post_type => $value ) {
			add_meta_box( 'subway_comment_metabox',
				esc_html__( 'Membership Discussion', 'subway' ),
				array( $this, 'discussion' ), $post_type, 'side', 'high'
			);

			add_meta_box('subway_visibility_metabox',
				esc_html__( 'Membership Access', 'subway' ),
				array( $this, 'visibility' ), $post_type, 'side', 'high'
			);
		}

	}

	public function visibility( $post ) 
	{
		
		$this->view->render('form-post-visiblity-metabox', [
				'class_post' => $this->post,
				'options' => $this->option,
				'helper' => $this->helper,
				'post' => $post 
			]);
	}

	public function discussion()
	{

	}

	public function attach_hooks() 
	{

		$this->define_hooks();

	}

	public function save( $post_id )
	{
		
		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		$is_form_submitted = filter_input( INPUT_POST, 'subway-visibility-form-submitted', FILTER_DEFAULT );

		if ( ! $is_form_submitted ) {
			return;
		}

		$posts_implode    = '';

		$visibility_field = 'subway-visibility-settings';

		$visibility_nonce = filter_input( INPUT_POST, 'subway_post_visibility_nonce', FILTER_SANITIZE_STRING );

		$post_visibility = filter_input( INPUT_POST,  $visibility_field, FILTER_SANITIZE_STRING );

		$is_nonce_valid = $this->is_nonce_valid( $visibility_nonce );
		
		$allowed_roles = filter_input( INPUT_POST, 'subway-visibility-settings-user-role', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		$comments_allowed_roles = filter_input( INPUT_POST, 'subway_post_discussion_roles', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		// Check the nonce.
		if ( false === $is_nonce_valid ) {
			return;
		}

		if ( empty( $allowed_roles ) ) {
			$allowed_roles = array();
		}

		if ( empty ( $comments_allowed_roles ) ) {
			$comments_allowed_roles = array();
		}

		// Update user roles.
		update_post_meta( $post_id, 'subway-visibility-settings-allowed-user-roles', $allowed_roles );

		// Update comment access type.
		$subway_post_discussion_access_type = filter_input( INPUT_POST,  'subway_post_discussion_access_type', FILTER_SANITIZE_STRING );

		update_post_meta( $post_id, 'subway_post_discussion_access_type', $subway_post_discussion_access_type);

		// Update comment user roles.
		update_post_meta( $post_id, 'subway_post_discussion_roles', $comments_allowed_roles );

		// Update no access control.
		$no_access_type = filter_input( INPUT_POST,  'subway-visibility-settings-no-access-type', FILTER_SANITIZE_STRING );

		update_post_meta( $post_id, 'subway-visibility-settings-no-access-type', $no_access_type );

		if ( ! empty( $post_id ) ) {

			if ( 'inherit' !== get_post_status( $post_id ) ) {

				if ( true === $is_nonce_valid ) {
					update_option( 'subway_public_post', $posts_implode );
					update_post_meta( $post_id, 'subway_visibility_meta_key', $post_visibility );
				}
			}
		}

		$access_type_block_message = filter_input( INPUT_POST, 'subway-visibility-settings-no-access-type-message', FILTER_DEFAULT);
		
		if ( ! empty( $access_type_block_message)) {
			update_post_meta( $post_id, 'subway-visibility-settings-no-access-type-message', $access_type_block_message );
		}

		return;
	}

	private function is_nonce_valid( $nonce )
	{

		if ( ! isset( $nonce ) || ! wp_verify_nonce( $nonce, 'form-post-visiblity-metabox.php' ) ) {
			return false;
		}

		return true;

	}

	private function define_hooks()
	{
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save' ) );

	}
}