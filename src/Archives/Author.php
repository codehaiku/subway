<?php

namespace Subway\Archives;

use Subway\Options\Options;
use Subway\User\User;


/**
 * Class Author
 *
 * @package Subway\Archives
 */
class Author {

	/**
	 * @var Options
	 */
	protected $options;

	/**
	 * @var User
	 */
	protected $user;

	/**
	 * Author constructor.
	 *
	 * @param Options $options DI parameter must pass an instance of Options.
	 * @param User    $user DI parameter must pass an instance of User.
	 */
	public function __construct( Options $options, User $user ) {

		$this->options = $options;

		$this->user = $user;

	}

	/**
	 * @return mixed|void
	 */
	public function get_access_type() {

		$access_type = get_option( 'subway_author_archives', 'public' );

		if ( ! $access_type ) {

			$access_type = 'public';

		}

		return apply_filters( 'set_author_archive_default_access_type', $access_type );

	}

	/**
	 * @return mixed|void
	 */
	public function is_archive_lock() {

		$option_is_author_archive_locked = false;

		$author_archive_access_type = $this->get_access_type();

		if ( 'private' === $author_archive_access_type ) {

			$option_is_author_archive_locked = true;

			$current_user_role = $this->user->get_role( get_current_user_id() );

			$allowed_user_roles = (array) get_option( 'subway_author_archives_roles', array() );

			if ( array_intersect( $current_user_role, $allowed_user_roles ) ) {

				// Unlock the archive page.
				$option_is_author_archive_locked = false;

			}
		}

		return apply_filters( 'is_author_archive_lock', $option_is_author_archive_locked );
	}

	/**
	 * Redirects the current user to proper page.
	 */
	public function redirect() {

		if ( current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( is_author() ) {

			if ( $this->is_archive_lock() ) {
				$login_url = $this->options->get_redirect_url();
				wp_safe_redirect( $login_url, 302 );
				exit;
			}
		}

		return;

	}

	/**
	 * Calls define_hook method accessible public.
	 */
	public function attach_hooks() {

		$this->define_hooks();

	}

	/**
	 * Attach class method to wp's hook system.
	 */
	protected function define_hooks() {

		add_action( 'wp', array( $this, 'redirect' ) );

	}
}
