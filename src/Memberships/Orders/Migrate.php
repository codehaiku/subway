<?php
namespace Subway\Memberships\Orders;

final class Migrate extends Orders {

	protected $collate = '';

	public function __construct( \wpdb $wpdb ) {
		parent::__construct( $wpdb );
		$this->collate = $this->db->get_charset_collate();
	}

	public function sql(){
		return "CREATE TABLE {$this->table} (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				plan_id mediumint(9) NOT NULL,
				recorded_plan_name varchar(100) NOT NULL,
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
			) $this->collate;";
	}
}