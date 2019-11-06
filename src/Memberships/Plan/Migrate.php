<?php

namespace Subway\Memberships\Plan;

final class Migrate extends Plan {

	protected $collate = '';

	public function __construct() {
		parent::__construct();
		$this->collate = $this->db->get_charset_collate();
	}

	public function sql() {
		return "CREATE TABLE $this->table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				product_id mediumint(9) NOT NULL,
				name tinytext NOT NULL,
				sku tinytext NOT NULL,
				description text NULL,
				amount double NOT NULL,
				status varchar(100) DEFAULT 'draft',
				type tinytext NOT NULL,
				date_created datetime DEFAULT CURRENT_TIMESTAMP,
				date_updated datetime DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY  (id)
			) $this->collate;";
	}

}