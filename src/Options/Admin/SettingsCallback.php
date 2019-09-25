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

	public function login_user_no_access_page() {
		$this->view->render('settings-login-user-no-access-page', []);
	}

	public function author_archives() {
		$this->view->render('settings-author-archives', []);
	}

	public function date_archives() {
		$this->view->render('settings-date-archives', []);
	}
}
?>