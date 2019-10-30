<?php

namespace Subway\User;

use Subway\FlashMessage\FlashMessage;
use Subway\Helpers\Helpers;
use Subway\Post\Post;
use Subway\Options\Options;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * This class handles basic methods for retrieving user info.
 */
class User extends Plans {

	public function __construct() {
		parent::__construct( Helpers::get_db() );
	}


	/**
	 * Returns the given user role.
	 *
	 * @param integer $user_id The user's id.
	 *
	 * @return array The user roles.
	 */
	public function get_role( $user_id = 0 ) {
		$roles = array();

		$user = get_userdata( absint( $user_id ) );

		if ( ! empty( $user->roles ) ) {
			$roles = $user->roles;
		}

		return $roles;
	}

	public function is_subscribed( $post_id ) {

		if ( current_user_can( 'manage_plugins' ) ) {
			return true;
		}

		if ( empty( $post_id ) ) {
			return true;
		}

		$allowed_user_roles = get_post_meta( $post_id, 'subway-visibility-settings-allowed-user-roles', true );

		$access_type = get_post_meta( $post_id, 'subway_visibility_meta_key', true );

		// Check the subscribe type of the current post type.
		if ( 'private' === $access_type ) {

			$user_role = $this->get_role( get_current_user_id() );

			// If the user role matches checked subscription role.
			if ( ! array_intersect( $user_role, $allowed_user_roles ) ) {
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

		$user_plans = $this->get_user_plans( $this->get_id() );

		return $user_plans;

	}

	public function has_plan( $plan_id ) {

		$subscriptions = $this->get_subscriptions();

		$subscription_plans = array();

		if ( ! empty( $subscriptions ) ) {

			foreach ( $subscriptions as $subscription ) {

				if ( $subscription ) {

					if ( ! in_array( $subscription->result->status, [ 'cancelled', 'paused' ] ) ) {

						array_push( $subscription_plans, $subscription->plan->get_id() );

					}

				}

			}

		}

		if ( in_array( $plan_id, $subscription_plans ) ) {

			return true;

		}

		return false;
	}

	/**
	 * Confirms the email change of the user.
	 * Taken from WordPress' user-edit.php
	 * <https://github.com/WordPress/WordPress/blob/master/wp-admin/user-edit.php>
	 *
	 */
	public function confirm_email_change() {

		global $wpdb;

		$current_user = wp_get_current_user();

		$new_email = get_user_meta( $current_user->ID, '_new_email', true );

		if ( $new_email && hash_equals( $new_email['hash'], $_GET['newuseremail'] ) ) {

			$user             = new \stdClass;
			$user->ID         = $current_user->ID;
			$user->user_email = esc_html( trim( $new_email['newemail'] ) );

			$stmt = $wpdb->prepare( "SELECT user_login FROM {$wpdb->signups} WHERE user_login = %s", $current_user->user_login );

			$user_login = $wpdb->get_var( $stmt );

			if ( is_multisite() && $user_login ) {

				$stmt_update = $wpdb->prepare(
					"UPDATE {$wpdb->signups} SET user_email = %s WHERE user_login = %s",
					$user->user_email,
					$current_user->user_login
				);

				$wpdb->query( $stmt_update );

			}

			wp_update_user( $user );

			delete_user_meta( $current_user->ID, '_new_email' );

			$options = new Options();

			$url = esc_url_raw( add_query_arg( [
				'account-page' => 'update-email-address',
				'success'      => true
			], $options->get_accounts_page_url()
			) );

			$flash = new FlashMessage( get_current_user_id(), 'subway-user-edit-email' );

			$flash->add( [
				'type'    => 'success',
				'message' => __( 'E-mail address has been successfully updated.', 'subway' )
			] );

			wp_redirect( add_query_arg( array( 'updated' => 'true' ), $url ) );

			die();

		}

	}
}