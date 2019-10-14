<?php
namespace Subway\User;
use Subway\Post\Post;
use Subway\Options\Options;

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
		
		$allowed_user_roles = get_post_meta( $post_id, 'subway-visibility-settings-allowed-user-roles', true );

		$access_type = get_post_meta( $post_id, 'subway_visibility_meta_key', true);

		// Check the subscribe type of the current post type.
		if ( 'private' === $access_type )
		{
			$user_role = $this->get_role( get_current_user_id() );

			// If the user role matches checked subscription role.
			if ( ! array_intersect( $user_role, $allowed_user_roles ) ) 
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Lists all the user's subscription.
	 * @return array
	 */
	public function get_subscriptions() {
		return array();
	}
}