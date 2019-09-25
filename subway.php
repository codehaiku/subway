<?php
/**
 * Plugin Name: Subway | Memberships & Subscriptions
 * Description: Lock your site content base on user roles and membership type. Easily charge for membership subscription via PayPal Subscriptions and More!
 * Version: 3.0
 * Author: Joseph G
 * Author URI: http://subway-wp.com
 * Text Domain: subway
 * License: GPL2
 *
 * Includes all the file necessary for Subway.
 *
 * PHP version 5.4+
 *
 * @category Subway\Bootstrap
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/subway
 * @link     github.com/codehaiku/subway The Plugin Repository
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

// Define Subway Plugin Version.
define( 'SUBWAY_VERSION', '3.0' );

// Define Subway Directory Path.
define( 'SUBWAY_DIR_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

// Define Subway URL Path.
define( 'SUBWAY_DIR_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

// Require our autoloder.
require_once SUBWAY_DIR_PATH . 'vendor' . '/autoload.php';