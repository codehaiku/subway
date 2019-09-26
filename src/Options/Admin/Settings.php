<?php

namespace Subway\Options\Admin;

use Subway\Memberships\Products\ListTable;
use Subway\Memberships\Products\Products;
use Subway\View\View;

class Settings {


	public function menu() {

		// Require Stylesheet.
		wp_enqueue_script( 'subway-settings-style' );

		// Add top-level menu "Membership".
		add_menu_page(
			esc_html__( 'Memberships Settings', 'subway' ),
			esc_html__( 'Memberships', 'subway' ),
			'manage_options',
			'subway-membership',
			array( $this, 'membership_products' ),
			'dashicons-clipboard',
			apply_filters( 'subway-memberships-admin-menu-position', 2 )
		);

		// Add 'dashboard' sub menu page.
		add_submenu_page(
			'subway-membership',
			esc_html__( 'Memberships: Products', 'subway' ),
			esc_html__( 'Products', 'subway' ),
			'manage_options',
			'subway-membership',
			array( $this, 'membership_products' )
		);

		// Add 'general' sub menu page.
		add_submenu_page(
			'subway-membership',
			esc_html__( 'Memberships: Settings', 'subway' ),
			esc_html__( 'Settings', 'subway' ),
			'manage_options',
			'subway-membership-general',
			array( $this, 'general' )
		);

		return;

	}

	public function general() {
		$view = new View();
		$view->render( 'form-admin-general-settings', [] );

		return $this;
	}

	public function membership_products() {

		$view = new View();
		$view->render(
			'form-membership-products',
			[ 'view' => $view, 'products' => new Products() ]
		);

		return $this;
	}

	private function get_icon( $icon = 'dashicons-admin-generic' ) {

		return '<hr /><span class="dashicons ' . esc_attr( $icon ) . '"></span>&nbsp';

	}

	/**
	 * Register our settings in admin.
	 */
	public function settings() {

		$view = new View();

		$section_callback = new SectionCallback();

		// Register our settings section.
		add_settings_section(
			'subway-page-visibility-section', $this->get_icon( 'dashicons-text-page' ) . __( 'Pages', 'subway' ),
			array( $section_callback, 'pages' ), 'subway-settings-section'
		);

		// Register Archives Options pages.
		add_settings_section(
			'subway-archives-section', $this->get_icon( 'dashicons-calendar-alt' ) . __( 'Archives', 'subway' ),
			array( $section_callback, 'archives' ), 'subway-settings-section'
		);

		// Register Redirect Options pages.
		add_settings_section(
			'subway-redirect-section', $this->get_icon( 'dashicons-undo' ) . __( 'Login Redirect', 'subway' ),
			array( $section_callback, 'login_redirect' ), 'subway-settings-section'
		);

		// Register Redirect Options pages.
		add_settings_section(
			'subway-messages-section', $this->get_icon( 'dashicons-email-alt2' ) . __( 'System Messages', 'subway' ),
			array( $section_callback, 'system_messages' ), 'subway-settings-section'
		);

		$settings_callback = new SettingsCallback( $view );

		// Register the fields.
		$fields = array(
			// Login page settings.
			array(
				'id'       => 'subway_login_page',
				'label'    => __( 'Log-in', 'subway' ),
				'callback' => array( $settings_callback, 'login_page' ),
				'section'  => 'subway-settings-section',
				'group'    => 'subway-page-visibility-section',
			),

			// Registration page.
			array(
				'id'       => 'subway_options_register_page',
				'label'    => __( 'Registration', 'subway' ),
				'callback' => array( $settings_callback, 'register_page' ),
				'section'  => 'subway-settings-section',
				'group'    => 'subway-page-visibility-section'
			),
			// Account page.
			array(
				'id'       => 'subway_options_account_page',
				'label'    => __( 'My Account', 'subway' ),
				'callback' => array( $settings_callback, 'user_account' ),
				'section'  => 'subway-settings-section',
				'group'    => 'subway-page-visibility-section'
			),
			// Redirect page for logged-in users.
			array(
				'id'       => 'subway_logged_in_user_no_access_page',
				'label'    => __( 'Memberships', 'subway' ),
				'callback' => array( $settings_callback, 'login_user_no_access_page' ),
				'section'  => 'subway-settings-section',
				'group'    => 'subway-page-visibility-section'
			),
			// Author archive access settings.
			array(
				'id'       => 'subway_author_archives',
				'label'    => __( 'Author', 'subway' ),
				'callback' => array( $settings_callback, 'author_archives' ),
				'section'  => 'subway-settings-section',
				'group'    => 'subway-archives-section'
			),
			// Date archive access settings.
			array(
				'id'       => 'subway_date_archives',
				'label'    => __( 'Date', 'subway' ),
				'callback' => array( $settings_callback, 'date_archives' ),
				'section'  => 'subway-settings-section',
				'group'    => 'subway-archives-section'
			),

			// Login redirect type.
			array(
				'id'       => 'subway_redirect_type',
				'label'    => __( 'Redirect Type', 'subway' ),
				'callback' => array( $settings_callback, 'redirect_type' ),
				'section'  => 'subway-settings-section',
				'group'    => 'subway-redirect-section'
			),
			// Login link notice.
			array(
				'id'       => 'subway_redirect_wp_admin',
				'label'    => __( 'WP Login Link', 'subway' ),
				'callback' => array( $settings_callback, 'info_wp_login_link' ),
				'section'  => 'subway-settings-section',
				'group'    => 'subway-redirect-section'
			),
			// Partial message settings.
			array(
				'id'       => 'subway_partial_message',
				'label'    => __( 'Partial Content Block', 'subway' ),
				'callback' => array( $settings_callback, 'partial_message' ),
				'section'  => 'subway-settings-section',
				'group'    => 'subway-messages-section'
			),
			// Commenting message.
			array(
				'id'       => 'subway_comment_limited_message',
				'label'    => __( 'Limited Comment', 'subway' ),
				'callback' => array( $settings_callback, 'comment_limited' ),
				'section'  => 'subway-settings-section',
				'group'    => 'subway-messages-section'
			),
			// Login form message settings.
			array(
				'id'       => 'subway_redirected_message_login_form',
				'label'    => __( 'Login Form', 'subway' ),
				'callback' => array( $settings_callback, 'shortcode_login_form' ),
				'section'  => 'subway-settings-section',
				'group'    => 'subway-messages-section'
			),
		);

		foreach ( $fields as $field ) {

			add_settings_field(
				$field['id'],
				$field['label'],
				$field['callback'],
				$field['section'],
				$field['group'],
				$field['args']
			);

			register_setting( 'subway-settings-group', $field['id'] );


		}

		// Register Redirect Page ID Settings.
		register_setting( 'subway-settings-group', 'subway_redirect_page_id' );

		// Register Redirect Custom URL Settings.
		register_setting( 'subway-settings-group', 'subway_redirect_custom_url' );

		// Register Author Archive Settings.
		register_setting( 'subway-settings-group', 'subway_author_archives_roles' );

		// Register Date Archive Settings.
		register_setting( 'subway-settings-group', 'subway_date_archives_roles' );

		return;
	}

	public function membership_screen_options() {

		global $SubwayListTableMembership;

		$option = 'per_page';

		$args = array(
			'label'   => esc_html__( 'Products', 'subway' ),
			'default' => 10,
			'option'  => 'products_per_page'
		);

		add_screen_option( $option, $args );

		if ( ! class_exists( 'WP_List_Table' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		}

		$SubwayListTableMembership = new ListTable();

	}

	public function assets( $hook ) {

		wp_register_script( 'subway-settings-script', SUBWAY_JS_URL . 'settings.js' );

		wp_register_style( 'subway-settings-style', SUBWAY_CSS_URL . 'settings.css' );

		if ( in_array( $hook, [ 'memberships_page_subway-membership-general' ] ) ) {
			// Enqueues the script only on the Subway Settings page.
			wp_enqueue_script( 'subway-settings-script' );

			wp_enqueue_style( 'subway-settings-style' );

		}

		return;

	}

	public function attach_hooks() {

		$this->define_hooks();

	}

	protected function define_hooks() {

		add_action( 'admin_menu', array( $this, 'menu' ) );

		add_action( 'admin_init', array( $this, 'settings' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );

		add_action( 'load-toplevel_page_subway-membership', array( $this, 'membership_screen_options' ) );

	}
}