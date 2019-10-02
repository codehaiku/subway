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
				user_id mediumint(9) NOT NULL,
				status varchar(100) NOT NULL,
				amount double NOT NULL,
				gateway varchar(100) NOT NULL,
				gateway_details text NOT NULL,
				created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				last_updated datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				PRIMARY KEY (id)
			) $this->collate;";

		dbDelta( $sql );

		add_option( "subway_memberships_orders_version", $this->db_version );

		return $this;

	}

	protected function membership_products_install() {

		$table = $this->wpdb->prefix . "subway_memberships_products";

		$sql = "CREATE TABLE $table (
						id mediumint(9) NOT NULL AUTO_INCREMENT,
						name tinytext NOT NULL,
						time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
						PRIMARY KEY  (id)
					) $this->collate;";

		dbDelta( $sql );

		add_option( "subway_memberships_products_version", $this->db_version );

		return $this;

	}

	protected function membership_products_update() {

		$table_version = get_option( "subway_memberships_products_version" );

		$table = $this->wpdb->prefix . "subway_memberships_products";

		if ( $table_version !== $this->db_version ) {

			$sql = "CREATE TABLE $table (
						id mediumint(9) NOT NULL AUTO_INCREMENT,
						name tinytext NOT NULL,
						description text NULL,
						time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
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
