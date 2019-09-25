<?php

namespace Subway\Taxonomy;

use Subway\View\View;

class Taxonomy {

	protected  $view;

	public function __construct( View $view ) {
		$this->view = $view;
	}

	/**
	 * @param $term
	 *
	 * @return $this
	 */
	public function taxonomy_option( $term )
	{

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

		$query_args = array( 'public' => true, 'show_ui' => true );
		$taxonomies = get_taxonomies( $query_args, 'names' );

		foreach ( $taxonomies as $taxonomy => $value ):
			// 999 since we want our meta to display last.
			add_action( $taxonomy . '_edit_form_fields', array( $this, 'taxonomy_option' ), 999, 2 );
			// Save the changes made on the "presenters" taxonomy, using our callback function
			add_action( 'edited_' . $taxonomy, array( $this, 'save' ), 10, 2 );
		endforeach;

		add_action( 'wp', array( $this, 'authorizeTaxonomyTerm' ) );

		return $this;

		return;
	}

}