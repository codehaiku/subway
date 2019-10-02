<?php

namespace Subway\Options\Admin;

use Subway\View\View;

class SettingsCallback {

	protected $view;

	public function __construct( View $view ) {
		$this->view = $view;
	}

	public function login_page() {
		$this->view->render( 'settings-login-page', [] );
	}

	public function login_user_no_access_page() {
		$this->view->render( 'settings-login-user-no-access-page', [] );
	}

	public function register_page() {
		$this->view->render( 'settings-register-page', [] );
	}

	public function user_account() {
		$this->view->render( 'settings-account-page', [] );
	}
	public function author_archives() {
		$this->view->render( 'settings-author-archives', [] );
	}

	public function date_archives() {
		$this->view->render( 'settings-date-archives', [] );
	}

	public function redirect_type() {
		$this->view->render( 'settings-redirect-type', [] );
	}

	public function info_wp_login_link() {
		$this->view->render( 'settings-info-wp-login-link', [] );
	}

	public function partial_message() {
		$this->view->render( 'settings-partial-message', [] );
	}

	public function comment_limited() {
		$this->view->render( 'settings-comment-limited', [] );
	}

	public function shortcode_login_form() {
		$this->view->render( 'settings-shortcode-login-form', [] );
	}

	public function paypal_client_id() {
		$this->view->render( 'settings-paypal-client-id', [] );
	}

	public function paypal_client_secret() {
		$this->view->render( 'settings-paypal-client-secret', [] );
	}

	public function paypal_page_confirmation() {
		$this->view->render( 'settings-paypal-page-confirmation', [] );
	}
	public function paypal_page_cancel() {
		$this->view->render( 'settings-paypal-page-cancel', [] );
	}
}

?>