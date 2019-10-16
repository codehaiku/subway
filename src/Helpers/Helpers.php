<?php

namespace Subway\Helpers;

use Subway\View\View;

/**
 * Class Helpers
 * @package Subway\Helpers
 */
class Helpers {

	/**
	 * @var string|View
	 */
	protected $view = '';

	/**
	 * Helpers constructor.
	 *
	 * @param View $view
	 */
	public function __construct( View $view ) {
		$this->view = $view;
	}

	public static function debug( $mixed, $dump = false ) {

		echo '<pre>';
		if ( $dump ) {
			var_dump( $mixed );
		} else {
			print_r( $mixed );
		}
		echo '</pre>';

	}

	public static function get_db() {

		global $wpdb;

		return $wpdb;

	}

	/**
	 * @param $args
	 */
	public function display_roles_checkbox( $args ) {
		$post_id  = get_the_id();
		$defaults = array( 'name' => '', 'option_name' => '' );
		$this->view->render(
			'helper-roles-checkbox', [ 'args' => $args ]
		);
	}

	/**
	 * Get the IP Address of user
	 *
	 * @reference <https://stackoverflow.com/questions/1634782>
	 * @return string
	 */
	public static function get_ip_address() {
		foreach (
			array(
				'HTTP_CLIENT_IP',
				'HTTP_X_FORWARDED_FOR',
				'HTTP_X_FORWARDED',
				'HTTP_X_CLUSTER_CLIENT_IP',
				'HTTP_FORWARDED_FOR',
				'HTTP_FORWARDED',
				'REMOTE_ADDR'
			) as $key
		) {

			if ( array_key_exists( $key, $_SERVER ) === true ) {
				foreach ( explode( ',', $_SERVER[ $key ] ) as $ip ) {
					$ip = trim( $ip ); // just to be safe
					if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) !== false ) {
						return $ip;
					}
				}
			}
		}

		// Return localhost.
		return '::1';
	}

}

