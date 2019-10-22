<?php
/**
 * Plugin Name: Subway Memberships & Subscriptions
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

global $wpdb;

// Define Subway Plugin Version.
define( 'SUBWAY_VERSION', '3.0' );

// Define Database Version.
define( 'SUBWAY_DB_VERSION', '1.0.5' );

// Define Subway Directory Path.
define( 'SUBWAY_DIR_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

// Define Subway URL Path.
define( 'SUBWAY_DIR_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

// Assets CSS URL.
define( 'SUBWAY_CSS_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'web/css/' );

// Assets JS URL.
define( 'SUBWAY_JS_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'web/js/' );

// Require our autoloader.
require_once SUBWAY_DIR_PATH . 'vendor' . '/autoload.php';
