<?php

namespace Subway\Migrate;

use Subway\Helpers\Helpers;
use Subway\Memberships\Orders\Migrate;

class InstallTable {

	protected $wpdb;
	protected $db_version;
	protected $collate;

	public function __construct( \wpdb $wpdb, $db_version ) {

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$this->wpdb       = $wpdb;
		$this->db_version = $db_version;
		$this->collate    = $this->wpdb->get_charset_collate();

		return $this;

	}

	public function install_tables() {

		// Install products table.
		$this->membership_products_install();

		// Install Product Plans.
		$this->membership_products_plans_install();

		// Install Orders.
		$this->membership_orders_install();

		// Install Order Details.
		$this->membership_orders_details_install();

		// Install User Plans.
		$this->membership_users_plans_install();

		// Install Billing Agreements.
		$this->membership_users_billing_agreements_install();

		// Install Pricing.
		$this->plans_pricing_install();

		return $this;
	}

	public function update_tables() {

		$this->membership_products_plans_update();

		$this->membership_orders_install_update();

		$this->plans_pricing_install_update();

		$this->membership_users_plans_update();

		return $this;
	}

	public function plans_pricing_install() {

		$migrate = new \Subway\Memberships\Plan\Pricing\Migrate();

		dbDelta( $migrate->sql() );

		update_option( "subway_memberships_products_plans_pricing_version", $this->db_version );

		return $this;

	}

	protected function plans_pricing_install_update() {

		$table_version = get_option( "subway_memberships_products_plans_pricing_version" );

		if ( $table_version !== $this->db_version ) {

			$this->plans_pricing_install();

			return $this;

		}

		return $this;

	}

	protected function membership_products_install() {

		$table = $this->wpdb->prefix . "subway_memberships_products";

		$sql = "CREATE TABLE $table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				name varchar(255) NOT NULL,
				description text NULL,
				status varchar(100) NOT NULL,
				tax_rate double NOT NULL,
				tax_displayed tinyint(1) NOT NULL,
				default_plan_id mediumint(9) NOT NULL,
				date_created datetime DEFAULT CURRENT_TIMESTAMP,
				date_updated datetime DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY  (id)
			) $this->collate;";


		dbDelta( $sql );

		update_option( "subway_memberships_products_version", $this->db_version );
	}

	protected function membership_products_plans_install() {

		$migrate = new \Subway\Memberships\Plan\Migrate();

		dbDelta( $migrate->sql() );

		update_option( "subway_memberships_products_plans_version", $this->db_version );

		return $this;

	}

	protected function membership_products_plans_update() {

		$table_version = get_option( "subway_memberships_products_plans_version" );

		if ( $table_version !== $this->db_version ) {

			$this->membership_products_plans_install();

			return $this;

		}

		return $this;

	}

	protected function membership_users_billing_agreements_install() {

		$table = $this->wpdb->prefix . 'subway_memberships_users_billing_agreements';

		$sql = "CREATE TABLE $table(
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				user_id mediumint(9) NOT NULL,
				prod_id mediumint(9) NOT NULL,
				status varchar(100) NOT NULL,
				billing_amount mediumint(9) NOT NULL,
				type varchar(100) NOT NULL,
				cycle_frequency mediumint(9) NOT NULL,
				cycle_time_period varchar(100) NOT NULL,
				cycle_limit mediumint(9) NOT NULL,
				gateway_agreement_id varchar(100) NOT NULL,
				updated datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (id)
			) $this->collate;";

		dbDelta( $sql );

		update_option( "subway_memberships_users_billing_agreements_version", $this->db_version );

		return $this;

	}

	protected function membership_users_plans_install() {

		$users_plans = new \Subway\User\Migrate();

		dbDelta( $users_plans->sql() );

		update_option( "subway_memberships_users_plans_version", $this->db_version );

		return $this;

	}

	protected function membership_users_plans_update() {

		$table_version = get_option( "subway_memberships_users_plans_version" );

		if ( $table_version !== $this->db_version ) {

			$this->membership_users_plans_install();
		}

		return $this;

	}

	protected function membership_orders_install() {

		$orders_migrate = new Migrate();

		dbDelta( $orders_migrate->sql() );

		update_option( "subway_memberships_orders_version", $this->db_version );

		return $this;

	}

	protected function membership_orders_install_update() {

		$table_version = get_option( "subway_memberships_orders_version" );

		if ( $table_version !== $this->db_version ) {

			$orders_migrate = new Migrate();

			dbDelta( $orders_migrate->sql() );

			update_option( "subway_memberships_orders_version", $this->db_version );

		}

		return $this;

	}

	protected function membership_orders_details_install() {

		$table = $this->wpdb->prefix . 'subway_memberships_orders_details';

		$sql = "CREATE TABLE $table(
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				order_id mediumint(9) NOT NULL,
				gateway_name varchar(100) NOT NULL,
				gateway_customer_name varchar(100) NOT NULL,
				gateway_customer_lastname varchar(100) NOT NULL,
				gateway_customer_email varchar(100) NOT NULL,
				gateway_customer_address_line_1 varchar(255) NOT NULL,
				gateway_customer_address_line_2 varchar(255) DEFAULT NULL,
				gateway_customer_postal_code varchar(100) DEFAULT NULL,
				gateway_customer_city varchar(100) DEFAULT NULL,
				gateway_customer_country varchar(100) NOT NULL,
				gateway_customer_state varchar(100) DEFAULT NULL,
				gateway_customer_phone_number varchar(100) DEFAULT NULL,
				gateway_transaction_created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (id)
			) $this->collate;";

		dbDelta( $sql );

		update_option( "subway_memberships_orders_details_version", $this->db_version );

		return $this;

	}

	protected function define_hooks() {

		register_activation_hook( SUBWAY_DIR_PATH . 'subway.php', array( $this, 'install_tables' ) );

		add_action( 'plugins_loaded', array( $this, 'update_tables' ) );

	}

	public function attach_hooks() {
		$this->define_hooks();

		return $this;
	}

}
