<?php
namespace Subway\Options\Admin;

use Subway\View\View;

class SettingsCallback {

	protected $view;

	public function __construct( View $view ) {
		$this->view = $view;
	}

	public function login_page() {

		$this->view->render('settings-login-page', []);

	}
}
?>