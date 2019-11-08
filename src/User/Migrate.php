<?php
namespace Subway\User;

use Subway\Helpers\Helpers;

final class Migrate {

	protected $db = null;
	protected $collate = null;
	protected $table = null;

	public function __construct() {
		$this->db      = Helpers::get_db();
		$this->table   = $this->db->prefix . 'subway_memberships_users_plans';
		$this->collate = $this->db->get_charset_collate();
	}

	public function sql() {
		return "CREATE TABLE $this->table (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				user_id mediumint(9) NOT NULL,
				plan_id mediumint(9) NOT NULL,
				product_id mediumint(9) NOT NULL,
				status varchar(100) NOT NULL,
				trial_status varchar(100) NOT NULL,
				trial_ending varchar(100) NOT NULL,
				notes text NOT NULL,
				updated datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (id)
			) $this->collate;";
	}
}