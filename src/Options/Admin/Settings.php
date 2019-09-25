<?php

namespace Subway\Options\Admin;

use Subway\View\View;

class Settings {

	public function __construct() {

	}

	public function menu() {

		// Add top-level menu "Membership".
		add_menu_page(
			esc_html__( 'Memberships Settings', 'subway' ),
			esc_html__( 'Memberships', 'subway' ),
			'manage_options',
			'subway-membership',
			array( $this, 'dummy' ),
			'dashicons-clipboard',
			2
		);

		// Add 'dashboard' sub menu page.
		add_submenu_page(
			'subway-membership',
			esc_html__( 'Memberships: Products', 'subway' ),
			esc_html__( 'Products', 'subway' ),
			'manage_options',
			'subway-membership',
			array( $this, 'dummy' )
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


		//add_action( 'admin_enqueue_scripts', array( $this, 'enqueueSettingsScripts' ) );

		return;

	}

	public function general() {
		$view = new View();
		$view->render( 'form-admin-general-settings', [] );
	}

	/**
	 * Register our settings in admin.
	 */
	public function settings() {

		// Register our settings section.
		add_settings_section(
			'subway-page-visibility-section', __( 'Pages', 'subway' ),
			array( $this, 'sectionCallback' ), 'subway-settings-section'
		);

		// Register Archives Options pages.
		add_settings_section(
			'subway-archives-section', __( 'Archives', 'subway' ),
			array( $this, 'archivesCallback' ), 'subway-settings-section'
		);

		// Register Redirect Options pages.
		add_settings_section(
			'subway-redirect-section', __( 'Login Redirect', 'subway' ),
			array( $this, 'redirectCallback' ), 'subway-settings-section'
		);

		// Register Redirect Options pages.
		add_settings_section(
			'subway-messages-section', __( 'Messages', 'subway' ),
			array( $this, 'messagesCallback' ), 'subway-settings-section'
		);

		$view = new View();
		$settings_callback = new SettingsCallback( $view );
		// Register the fields.
		$fields = array(
			// Login page settings.
			array(
				'id'       => 'subway_login_page',
				'label'    => __( 'Login Page', 'subway' ),
				'callback' => array( $settings_callback, 'login_page' ),
				'section'  => 'subway-settings-section',
				'group'    => 'subway-page-visibility-section',
			),
			// Redirect page for logged-in users.
			array(
				'id'       => 'subway_logged_in_user_no_access_page',
				'label'    => __( 'No Access Page', 'subway' ),
				'callback' => 'subway_logged_in_user_no_access_page',
				'section'  => 'subway-settings-section',
				'group'    => 'subway-page-visibility-section'
			),
			// Author archive access settings.
			array(
				'id'       => 'subway_author_archives',
				'label'    => __( 'Author', 'subway' ),
				'callback' => 'subway_author_archives',
				'section'  => 'subway-settings-section',
				'group'    => 'subway-archives-section'
			),
			// Date archive access settings.
			array(
				'id'       => 'subway_date_archives',
				'label'    => __( 'Date', 'subway' ),
				'callback' => 'subway_date_archives',
				'section'  => 'subway-settings-section',
				'group'    => 'subway-archives-section'
			),

			// Login redirect type.
			array(
				'id'       => 'subway_redirect_type',
				'label'    => __( 'Redirect Type', 'subway' ),
				'callback' => 'subway_redirect_option_form',
				'section'  => 'subway-settings-section',
				'group'    => 'subway-redirect-section'
			),
			// Login link notice.
			array(
				'id'       => 'subway_redirect_wp_admin',
				'label'    => __( 'WP Login Link', 'subway' ),
				'callback' => 'subway_lock_wp_admin',
				'section'  => 'subway-settings-section',
				'group'    => 'subway-redirect-section'
			),
			// Partial message settings.
			array(
				'id'       => 'subway_partial_message',
				'label'    => __( 'Partial Content Block', 'subway' ),
				'callback' => 'subway_messages',
				'section'  => 'subway-settings-section',
				'group'    => 'subway-messages-section'
			),
			// Commenting message.
			array(
				'id'       => 'subway_comment_limited_message',
				'label'    => __( 'Limited Comment', 'subway' ),
				'callback' => 'subway_comment_limited_message',
				'section'  => 'subway-settings-section',
				'group'    => 'subway-messages-section'
			),
			// Login form message settings.
			array(
				'id'       => 'subway_redirected_message_login_form',
				'label'    => __( 'Login Form', 'subway' ),
				'callback' => 'subway_redirected_message_login_form',
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


	public function assets( $hook ) {

		wp_register_script( 'subway-settings-script',
			plugins_url( '/assets/js/settings.js', __FILE__ ) );

		wp_register_style( 'subway-settings-style',
			plugins_url( '/assets/css/settings.css', __FILE__ ) );

		if ( in_array( $hook, [ 'memberships_page_subway-membership-general' ] ) ) {
			// Enqueues the script only on the Subway Settings page.
			wp_enqueue_script( 'subway-settings-script' );
			wp_enqueue_style( 'subway-settings-style' );
		}

		return;

	}

	public function dummy() {
		echo 'dummy';
	}

	public function attach_hooks() {
		$this->define_hooks();;
	}

	protected function define_hooks() {

		add_action( 'admin_menu', array( $this, 'menu' ) );

		add_action( 'admin_init', array( $this, 'settings' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );

	}
}