<?php

namespace Subway\Memberships\Plan\Pricing;

final class Migrate extends Pricing {

	protected $collate = '';

	public function __construct() {
		parent::__construct();
		$this->collate = $this->db->get_charset_collate();
	}

	public function sql() {
		return "CREATE TABLE $this->table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				plan_id mediumint(9) NOT NULL AUTO_INCREMENT,
				billing_cycle_frequency smallint(9) NOT NULL,
				billing_cycle_period varchar(100) NOT NULL,
				billing_limit mediumint(9) NOT NULL,
				has_trial tinyint(1) NOT NULL,
				trial_frequency smallint(9) NOT NULL,
				trial_period varchar(100) NOT NULL,
				date_created datetime DEFAULT CURRENT_TIMESTAMP,
				date_updated datetime DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY  (id)
			) $this->collate;";
	}

}
