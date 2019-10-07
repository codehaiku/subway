<?php

namespace Subway\Options\Admin;

use Subway\Currency\Currency;
use Subway\Earnings\Earnings;
use Subway\Memberships\Products\ListTable;
use Subway\Memberships\Products\Products;
use Subway\View\View;

class Settings {


	public function menu() {



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
			esc_html__( 'Memberships: Plans', 'subway' ),
			esc_html__( 'All Plans', 'subway' ),
			'manage_options',
			'subway-membership',
			array( $this, 'membership_products' )
		);

		// Add Orders Sub Menu Page.
		add_submenu_page(
			'subway-membership',
			esc_html__( 'Memberships: Orders', 'subway' ),
			esc_html__( 'Statements', 'subway' ),
			'manage_options',
			'subway-membership-orders',
			array( $this, 'orders' )
		);

		// Add License Key menu page.
		add_submenu_page(
			'subway-membership',
			esc_html__( 'Memberships: Earnings', 'subway' ),
			esc_html__( 'Earnings', 'subway' ),
			'manage_options',
			'subway-membership-earnings',
			array( $this, 'earnings' )
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

		// Add Tools sub menu page.
		add_submenu_page(
			'subway-membership',
			esc_html__( 'Memberships: Tools', 'subway' ),
			esc_html__( 'Tools', 'subway' ),
			'manage_options',
			'subway-membership-tools',
			array( $this, 'tools' )
		);



		// Add License Key menu page.
		add_submenu_page(
			'subway-membership',
			esc_html__( 'Memberships: License Key', 'subway' ),
			esc_html__( 'Upgrade', 'subway' ),
			'manage_options',
			'subway-membership-license',
			array( $this, 'license_key' )
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

	public function orders() {

		$view = new View();

		$view->render(
			'form-memberships-orders',
			[ 'view' => $view, 'orders' => [] ]
		);

		return $this;
	}


	public function earnings() {

		global $wpdb;

		$view = new View();

		$earnings = new Earnings( $wpdb );

		$view->render(
			'form-memberships-earnings',
			[ 'view' => $view, 'orders' => [], 'earnings' => $earnings, 'currency' => new Currency( $wpdb ) ]
		);

		return $this;

	}

	public function tools() {

		$view = new View();

		$view->render(
			'form-memberships-tools',
			[ 'view' => $view, 'orders' => [] ]
		);

		return $this;

	}

	public function license_key() {
		echo "<h2>Enter your license key</h2>";
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
			'subway-general-section', $this->get_icon( 'dashicons-admin-generic' ) . __( 'General', 'subway' ),
			array( $section_callback, 'general' ), 'subway-settings-section'
		);

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

		// PayPal section.
		add_settings_section(
			'subway-paypal-section', $this->get_icon( 'dashicons-email-alt2' ) . __( 'PayPal', 'subway' ),
			array( $section_callback, 'paypal' ), 'subway-settings-section'
		);

		$settings_callback = new SettingsCallback( $view );

		// Register the fields.
		$fields = array(
			// Currency Page Settings.
			array(
				'id'       => 'subway_currency',
				'label'    => __( 'Currency', 'subway' ),
				'callback' => array( $settings_callback, 'currency' ),
				'section'  => 'subway-settings-section',
				'group'    => 'subway-general-section',
			),

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
			// PayPal Client ID.
			array(
				'id'       => 'subway_paypal_client_id',
				'label'    => __( 'Client ID', 'subway' ),
				'callback' => array( $settings_callback, 'paypal_client_id' ),
				'section'  => 'subway-settings-section',
				'group'    => 'subway-paypal-section'
			),
			// PayPal Client Secret.
			array(
				'id'       => 'subway_paypal_client_secret',
				'label'    => __( 'Client Secret', 'subway' ),
				'callback' => array( $settings_callback, 'paypal_client_secret' ),
				'section'  => 'subway-settings-section',
				'group'    => 'subway-paypal-section'
			),
			// PayPal Order Confirmation Page.
			array(
				'id'       => 'subway_paypal_page_confirmation',
				'label'    => __( 'Order Confirmation Page', 'subway' ),
				'callback' => array( $settings_callback, 'paypal_page_confirmation' ),
				'section'  => 'subway-settings-section',
				'group'    => 'subway-paypal-section'
			),
			// PayPal Cancel Order Page.
			array(
				'id'       => 'subway_paypal_page_cancel',
				'label'    => __( 'Cancel Order Page', 'subway' ),
				'callback' => array( $settings_callback, 'paypal_page_cancel' ),
				'section'  => 'subway-settings-section',
				'group'    => 'subway-paypal-section'
			),
		);

		foreach ( $fields as $field ) {

			add_settings_field(
				$field['id'],
				$field['label'],
				$field['callback'],
				$field['section'],
				$field['group']
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

	public function membership_orders_screen_options() {

		global $SubwayListTableOrders;

		$option = 'per_page';

		$args = array(
			'label'   => esc_html__( 'Orders', 'subway' ),
			'default' => 10,
			'option'  => 'orders_per_page'
		);

		add_screen_option( $option, $args );

		if ( ! class_exists( 'WP_List_Table' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		}

		$SubwayListTableOrders = new \Subway\Memberships\Orders\ListTable();

	}

	public function assets( $hook ) {

		wp_register_script( 'subway-settings-script', SUBWAY_JS_URL . 'settings.js' );

		wp_register_style( 'subway-settings-style', SUBWAY_CSS_URL . 'settings.css' );

		$styled_settings_pages = [
			'memberships_page_subway-membership-general',
			'memberships_page_subway-membership-earnings',
		];

		if ( in_array( $hook, $styled_settings_pages ) ) {

			// Enqueues the script only on the Subway Settings page.
			wp_enqueue_script( 'subway-settings-script' );

			wp_enqueue_style( 'subway-settings-style' );

		}

		return;

	}

	public function set_screen_option( $status, $option, $value ) {

		return $value;

	}

	public function attach_hooks() {

		$this->define_hooks();

	}

	protected function define_hooks() {

		add_action( 'admin_menu', array( $this, 'menu' ) );

		add_action( 'admin_init', array( $this, 'settings' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );

		add_action( 'load-toplevel_page_subway-membership', array( $this, 'membership_screen_options' ) );

		add_action( 'load-memberships_page_subway-membership-orders', array(
			$this,
			'membership_orders_screen_options'
		) );

		add_filter( 'set-screen-option', array( $this, 'set_screen_option' ), 10, 3 );
	}
}

