<?php

namespace Subway\Options\Admin;

use Subway\Currency\Currency;
use Subway\View\View;

class SettingsCallback {

	protected $view;

	public function __construct( View $view ) {

		$this->view = $view;

	}

	public function seller_name() {

		$this->view->render( 'settings-seller-name', []);

	}

	public function seller_address_line1() {

		$this->view->render( 'settings-seller-address-line1', []);

	}

	public function seller_address_line2() {

		$this->view->render( 'settings-seller-address-line2', []);

	}

	public function seller_city() {

		$this->view->render( 'settings-seller-city', []);

	}

	public function seller_postal_code() {

		$this->view->render( 'settings-postal-code', []);

	}

	public function seller_country() {

		$this->view->render( 'settings-seller-country', []);

	}

	public function seller_email() {

		$this->view->render( 'settings-seller-email', []);

	}

	public function seller_vat() {

		$this->view->render( 'settings-seller-vat', []);

	}

	public function seller_registration_number() {

		$this->view->render( 'settings-registration-number', []);

	}

	public function currency() {

		$currencies = Currency::get_supported_currencies();

		$selected_currency = get_option( 'subway_currency', 'USD' );

		$this->view->render( 'settings-currency', [
			'currencies'        => $currencies,
			'selected_currency' => $selected_currency
		] );

	}

	public function tax_rate() {
		$tax_rate = get_option( 'subway_tax_rate', 0.00 );
		$this->view->render( 'settings-tax-rate', [ 'tax_rate' => $tax_rate ] );
	}

	public function display_tax() {
		$subway_display_tax = get_option( 'subway_display_tax', '1' );
		$this->view->render( 'settings-tax-display', [ 'subway_display_tax' => $subway_display_tax ] );
	}

	public function login_page() {
		$this->view->render( 'settings-login-page', [] );
	}

	public function membership_page() {
		$this->view->render( 'settings-membership-page', [] );
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