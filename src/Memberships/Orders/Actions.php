<?php
namespace Subway\Memberships\Orders;

class Actions {

	public function trash_order() {
		echo 'tae';
		die;
	}

	public function attach_hooks() {
		add_action('admin_post_subway_trash_order', [$this, 'trash_order']);
	}
}