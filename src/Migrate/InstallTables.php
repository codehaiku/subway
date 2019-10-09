<?php

namespace Subway\Migrate;

class InstallTables {

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

	protected function membership_orders_install() {

		$table = $this->wpdb->prefix . 'subway_memberships_orders';

		$sql = "CREATE TABLE $table(
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				product_id mediumint(9) NOT NULL,
				invoice_number varchar(100) NOT NULL,
				user_id mediumint(9) NOT NULL,
				status varchar(100) NOT NULL,
				amount double NOT NULL,
				tax_rate double NOT NULL,
				customer_vat_number varchar(100) NOT NULL,
				currency varchar(50) NOT NULL,
				gateway varchar(100) NOT NULL,
				ip_address varchar(100) NOT NULL,
				created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				last_updated datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (id)
			) AUTO_INCREMENT = 1000 $this->collate;";

		dbDelta( $sql );

		update_option( "subway_memberships_orders_version", $this->db_version );

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

	protected function membership_products_install() {

		$table = $this->wpdb->prefix . "subway_memberships_products";

		$sql = "CREATE TABLE $table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				name tinytext NOT NULL,
				sku tinytext NOT NULL,
				description text NULL,
				amount double NOT NULL,
				status varchar(100) DEFAULT 'draft',
				type tinytext NOT NULL,
				date_created datetime DEFAULT CURRENT_TIMESTAMP,
				date_updated datetime DEFAULT CURRENT_TIMESTAMP
				PRIMARY KEY  (id)
			) $this->collate;";

		dbDelta( $sql );

		update_option( "subway_memberships_products_version", $this->db_version );

		return $this;

	}

	protected function membership_products_update() {

		$table_version = get_option( "subway_memberships_products_version" );

		$table = $this->wpdb->prefix . "subway_memberships_products";

		if ( $table_version !== $this->db_version ) {

			$sql = "CREATE TABLE $table (
						id mediumint(9) NOT NULL AUTO_INCREMENT,
						name tinytext NOT NULL,
						sku tinytext NOT NULL,
						description text NULL,
						amount double NOT NULL,
						status varchar(100) DEFAULT 'draft',
						type tinytext NOT NULL,
						date_created datetime DEFAULT CURRENT_TIMESTAMP,
						date_updated datetime DEFAULT CURRENT_TIMESTAMP
						PRIMARY KEY  (id)
				) $this->collate;";

			dbDelta( $sql );

			update_option( "subway_memberships_products_version", $this->db_version );

			return $this;

		}

		return $this;

	}

	public function install_tables() {

		$this->membership_products_install();
		$this->membership_orders_install();
		$this->membership_orders_details_install();

		return $this;
	}

	public function update_tables() {

		$this->membership_products_update();

		return $this;
	}

	public function attach_hooks() {
		$this->define_hooks();

		return $this;
	}

	protected function define_hooks() {

		register_activation_hook( SUBWAY_DIR_PATH . 'subway.php', array( $this, 'install_tables' ) );

		add_action( 'plugins_loaded', array( $this, 'update_tables' ) );


	}
}
