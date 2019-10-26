<?php
/**
 * Plugin Name: Box Membership
 * Description: Box Membership is a complete membership platform for WordPress. Charge users for content subscriptions, and more!
 * Version: 3.0
 * Author: Joseph G
 * Author URI: http://boxmemberships.com
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

// Define Database Version.
define( 'SUBWAY_DB_VERSION', '1.0.6' );

// Define Subway Directory Path.
define( 'SUBWAY_DIR_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

// Define Subway URL Path.
define( 'SUBWAY_DIR_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

// Assets CSS URL.
define( 'SUBWAY_CSS_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'web/css/' );

// Assets JS URL.
define( 'SUBWAY_JS_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'web/js/' );

// Require our autoloader.
require_once SUBWAY_DIR_PATH . 'vendor/autoload.php';
