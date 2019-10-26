<?php

namespace Subway\Options\Admin;

class SectionCallback {

	public function general() {

		return $this;
	}

	public function seller_profile() {
		echo '<p class="sw-lead">';
		esc_html_e( 'Tell your customers about your business info. This info will appear on emails, invoices, and/or receipts.', 'subway' );
		echo '</p>';

		return $this;
	}

	public function pages() {
		echo '<p class="sw-lead">';
		esc_html_e( 'Select a page for each corresponding membership pages', 'subway' );
		echo '</p>';

		return $this;
	}

	public function archives() {
		echo '<p class="sw-lead">';
		esc_html_e( 'Choose the accessibility type of your archive pages.', 'subway' );
		echo '</p>';

		return $this;
	}

	public function login_redirect() {
		echo '<p class="sw-lead">';
		esc_html_e( 'Choose the destination page after the user logs in.', 'subway' );
		echo '</p>';

		return $this;
	}

	public function system_messages() {
		echo '<p class="sw-lead">';
		esc_html_e( 'You can use the options below to
		 change the default system messages.', 'subway' );
		echo '</p>';

		return $this;
	}

	public function paypal() {
		echo '<p class="sw-lead">';
		esc_html_e( 'PayPal Payments Settings.', 'subway' );
		echo '</p>';
	}
}