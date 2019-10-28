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

		$this->view->render( 'seller-name', [], false, 'settings' );

	}

	public function seller_address_line1() {

		$this->view->render( 'seller-address-line1', [], false, 'settings' );

	}

	public function seller_address_line2() {

		$this->view->render( 'seller-address-line2', [], false, 'settings' );

	}

	public function seller_city() {

		$this->view->render( 'seller-city', [], false, 'settings' );

	}

	public function seller_postal_code() {

		$this->view->render( 'postal-code', [], false, 'settings' );

	}

	public function seller_country() {

		$this->view->render( 'seller-country', [], false, 'settings' );

	}

	public function seller_email() {

		$this->view->render( 'seller-email', [], false, 'settings' );

	}

	public function seller_vat() {

		$this->view->render( 'seller-vat', [], false, 'settings' );

	}

	public function seller_registration_number() {

		$this->view->render( 'registration-number', [], false, 'settings' );

	}

	public function currency() {

		$currencies = Currency::get_supported_currencies();

		$selected_currency = get_option( 'subway_currency', 'USD' );

		$this->view->render( 'currency', [
			'currencies'        => $currencies,
			'selected_currency' => $selected_currency
		], false, 'settings' );

	}

	public function tax_rate() {
		$tax_rate = get_option( 'subway_tax_rate', 0.00 );
		$this->view->render( 'tax-rate', [ 'tax_rate' => $tax_rate ], false, 'settings' );
	}

	public function display_tax() {
		$subway_display_tax = get_option( 'subway_display_tax', '1' );
		$this->view->render( 'tax-display', [ 'subway_display_tax' => $subway_display_tax ], false, 'settings' );
	}

	public function login_page() {
		$this->view->render( 'login-page', [], false, 'settings' );
	}

	public function checkout_page() {
		$this->view->render( 'checkout-page', [], false, 'settings' );
	}

	public function membership_page() {
		$this->view->render( 'membership-page', [], false, 'settings' );
	}

	public function register_page() {
		$this->view->render( 'register-page', [], false, 'settings' );
	}

	public function user_account() {
		$this->view->render( 'account-page', [], false, 'settings' );
	}

	public function author_archives() {
		$this->view->render( 'author-archives', [], false, 'settings' );
	}

	public function date_archives() {
		$this->view->render( 'date-archives', [], false, 'settings' );
	}

	public function redirect_type() {
		$this->view->render( 'redirect-type', [], false, 'settings' );
	}

	public function info_wp_login_link() {
		$this->view->render( 'info-wp-login-link', [], false, 'settings' );
	}

	public function partial_message() {
		$this->view->render( 'partial-message', [], false, 'settings' );
	}

	public function comment_limited() {
		$this->view->render( 'comment-limited', [], false, 'settings' );
	}

	public function shortcode_login_form() {
		$this->view->render( 'shortcode-login-form', [], false, 'settings' );
	}

	public function paypal_client_id() {
		$this->view->render( 'paypal-client-id', [], false, 'settings' );
	}

	public function paypal_client_secret() {
		$this->view->render( 'paypal-client-secret', [], false, 'settings' );
	}

	public function paypal_page_confirmation() {
		$this->view->render( 'paypal-page-confirmation', [], false, 'settings' );
	}

	public function paypal_page_cancel() {
		$this->view->render( 'paypal-page-cancel', [], false, 'settings' );
	}
}

?>