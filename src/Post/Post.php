<?php
namespace Subway\Post;
use Subway\User\User;

class Post {

	var $user = '';

	public function __construct()
	{
		$this->user = new User();
	}

	public function get_types( $args = '', $output = '' )
	{
		if ( empty( $args ) ) 
		{
			$args = array( 'public'=> true );
			$output = 'names';
		}

		$post_types = get_post_types( $args, $output );

		return $post_types;
	}

	public function get_allowed_roles( $id )
	{
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

	public function get_no_access_type( $id )
	{
		$allowed_no_access_type = array('block_content', 'redirect');

        $post_no_access_type = get_post_meta($post_id, 'subway-visibility-settings-no-access-type', true );
        
        if ( empty( $post_no_access_type ) || ! in_array( $post_no_access_type, $allowed_no_access_type ) ) 
        {
            $post_no_access_type = 'block_content';
        }

        return $post_no_access_type;
	}

	public function is_private( $id )
	{
		$meta_value = '';

		if ( ! empty( $post_id ) ) 
		{
			$meta_value = get_post_meta( $post_id, 'subway_visibility_meta_key', true );
			
			// Pages that dont have meta values yet. 
			if ( empty( $meta_value ) ) 
			{
				// Give it a public visibility.
				return false;
			}
			if ( 'private' === $meta_value ) 
			{
				return true;
			}
		}

		return false;
	}

	public function attach_hooks() 
	{

		$this->define_hooks();

	}

	private function define_hooks()
	{
		

	}
}