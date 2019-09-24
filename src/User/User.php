<?php
namespace Subway\User;
use Subway\Post\Post;

if ( ! defined('ABSPATH') ) {
    return;
}

/**
 * This class handles basic methods for retrieving user info.
 */
class User {

	/**
	 * Returns the given user role.
	 * @param  integer $user_id The user's id.
	 * @return array The user roles.
	 */
	public function get_role( $user_id = 0 )
	{
		$roles = array();
		
		$user = get_userdata( absint( $user_id ) );
		
		if ( ! empty( $user->roles ) ) {
			$roles = $user->roles;
		}

		return $roles;
	}

	public function is_subscribed( $post_id )
	{
		if ( current_user_can('manage_plugins') )
		{
			return true;
		}
		
		if ( empty( $post_id ) ) {
			return true;
		}
		
		// Check the subscribe type of the current post type.
		$obj_post = new Post();
		$post_subscribe_type = $obj_post->get_access_type( $post_id );
		
		if ( 'private' === $post_subscribe_type['type'] )
		{
			$user_role = $this->get_role( get_current_user_id() );

			// If the user role matches checked subscription role.
			if ( ! array_intersect( $user_role, $post_subscribe_type['roles'] ) ) 
			{
				return false;
			}
		}

		return true;
	}
}