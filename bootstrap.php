<?php

namespace Subway\Bootstrap;

use Subway\Api\Products as ApiProducts;
use Subway\Archives\Author;
use Subway\Hooks\Hooks;
use Subway\Memberships\Orders\Details;
use Subway\Memberships\Orders\Orders;
use Subway\Memberships\Products\Controller;
use Subway\Migrate\InstallTables;
use Subway\Options\Admin\Settings;
use Subway\Post\Shortcodes\Checkout;
use Subway\Post\Shortcodes\Login;
use Subway\Post\Shortcodes\Memberships;
use Subway\Post\Shortcodes\Register;
use Subway\Post\Shortcodes\UserAccount;
use Subway\Tools\Repair;
use Subway\Widgets\Options as WidgetOptions;
use Subway\Options\Options as PluginOptions;
use Subway\Post\Post;
use Subway\Post\Metabox;
use Subway\Post\Comments;
use Subway\View\View;
use Subway\User\User;
use Subway\Enqueue\Enqueue;
use Subway\Helpers\Helpers;
use Subway\Taxonomy\Taxonomy;

global $wpdb;

// Install Tables.
$migrate = new InstallTables( $wpdb, SUBWAY_DB_VERSION );
$migrate->attach_hooks();

// Hooks.
$hooks = new Hooks();
$hooks->listen();

// Get our repositories.
$user    = new User();
$view    = new View();
$options = new PluginOptions();

// Load our helpers.
$helpers = new Helpers( $view );

// Load our widgets.
$widget = new WidgetOptions( $user, $view );
$widget->attach_hooks();

// Enqueue Everything.
$assets = new Enqueue();
$assets->attach_hooks();

// Load our post hooks.
$post = new Post( $user, $options );
$post->attach_hooks();

// Load our metabox.
$metabox = new Metabox( $post, $view, $options, $helpers );
$metabox->attach_hooks();

// Load Comments.
$comments = new Comments( $user, $view );
$comments->attach_hooks();

// Load Taxonomy.
$comments = new Taxonomy( $view );
$comments->attach_hooks();

// Load Author Archives
$archives_author = new Author( $options, $user );
$archives_author->attach_hooks();

// Load Admin Settings
$admin_settings = new Settings();
$admin_settings->attach_hooks();

// Load our API for products.
$api = new ApiProducts();
$api->attach_hooks();

// Load Checkout.
$checkout = new \Subway\Checkout\Checkout( $wpdb );
$checkout->attach_hooks();

// Load Login Shortcode
$login_shortcode = new Login( $view );
$login_shortcode->attach_hooks();

// Load Register Shortcode
$register_shortcode = new Register( $view );
$register_shortcode->attach_hooks();

// Load User Account Shortcode.
$user_account = new UserAccount( $view );
$user_account->attach_hooks();

// Load Memberships Shortcode.
$membership = new Memberships( $view );
$membership->attach_hooks();

// Load Checkout Shortcode.
$checkout = new Checkout( $view );
$checkout->attach_hooks();

// Load Membership Tools.
$checkout = new Repair( $wpdb );
$checkout->attach_hooks();

// Load Orders Actions
$order = new Orders( $wpdb );
$order->attach_hooks();

// Load Products Actions.
$products_controller = new Controller();
$products_controller->attach_hooks();
