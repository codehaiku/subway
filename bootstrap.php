<?php
namespace Subway\Bootstrap;

use Subway\Archives\Author;
use Subway\Options\Admin\Settings;
use Subway\Post\Shortcodes\Login;
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

// Load Login Shortcode
$login_shortcode = new Login( $view );
$login_shortcode->attach_hooks();

// Load Admin Settings
$admin_settings = new Settings();
$admin_settings->attach_hooks();