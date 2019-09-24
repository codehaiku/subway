<?php
namespace Subway\User;

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

}