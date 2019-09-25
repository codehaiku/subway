<?php

namespace Subway\Taxonomy;

use Subway\Options\Options;
use Subway\User\User;
use Subway\View\View;

class Taxonomy {

	protected $view;

	public function __construct( View $view ) {
		$this->view = $view;
	}

	/**
	 * @param $term
	 *
	 * @return $this
	 */
	public function taxonomy_option( $term ) {

		$this->view->render( "form-taxonomy-options", [ 'term' => $term ] );

		return $this;

	}

	public function save( $term_id ) {
		// Get requested term meta.
		$subway_term_meta = filter_input( INPUT_POST, 'subway_term_meta', FILTER_DEFAULT, FILTER_FORCE_ARRAY );
		// Make the default value public.
		$subway_membership_access_type = 'public';
		// Filter allowed access type.
		$allowed_subway_membership_access_type = array( 'private', 'public' );

		// Bail out if not array.
		if ( ! is_array( $subway_term_meta ) ) {
			return;
		}
		// Bail out if empty.
		if ( empty ( $subway_term_meta ) ) {
			return;
		}
		// Assign requested access type.
		$subway_membership_access_type       = $subway_term_meta['subway_membership_access_type'];
		$subway_membership_access_type_roles = (array) $subway_term_meta['subway_membership_access_type_role'];

		// Check if membership access type is in allowed access type.
		if ( ! in_array( $subway_membership_access_type, $allowed_subway_membership_access_type ) ) {
			return;
		}
		// Once everything is fine, save it to term meta.
		update_term_meta( $term_id, 'subway_membership_access_type', $subway_membership_access_type );
		update_term_meta( $term_id, 'subway_membership_access_type_roles', $subway_membership_access_type_roles );
	}

	public function authorize() {
		// Bail out if admin is viewing the page.
		if ( current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( is_tax() || is_category() || is_tag() ) {
			$term_id = get_queried_object()->term_id;

			if ( empty ( $term_id ) ) {
				return;
			}

			$user               = new User();
			$access_type        = get_term_meta( $term_id, 'subway_membership_access_type', true );
			$allowed_user_roles = get_term_meta( $term_id, 'subway_membership_access_type_roles', true );
			$current_user_role  = $user->get_role( get_current_user_id() );

			if ( 'private' === $access_type ) {
				// If no user role is found.
				if ( ! array_intersect( $allowed_user_roles, $current_user_role ) ) {
					$options        = new Options();
					$login_page_url = $options->get_redirect_url();
					wp_safe_redirect( $login_page_url, 302 );
					exit;
				}
			}
		}
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

		$query_args = array( 'public' => true, 'show_ui' => true );
		$taxonomies = get_taxonomies( $query_args, 'names' );

		foreach ( $taxonomies as $taxonomy => $value ):
			// 999 since we want our meta to display last.
			add_action( $taxonomy . '_edit_form_fields', array( $this, 'taxonomy_option' ), 999, 2 );
			// Save the changes made on the "presenters" taxonomy, using our callback function
			add_action( 'edited_' . $taxonomy, array( $this, 'save' ), 10, 2 );
		endforeach;

		add_action( 'wp', array( $this, 'authorize' ) );

		return $this;

		return;
	}

}