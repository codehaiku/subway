<?php

namespace Subway\Hooks;

use Subway\FlashMessage\FlashMessage;
use Subway\Options\Options;
use Subway\Payment\Payment;
use Subway\User\User;

class Hooks {

	public function confirm_order() {

		global $wpdb;

		$payment = new Payment( $wpdb );

		$payment->confirm();

	}

	public function confirm_email() {

		$user = new User();

		// Check if confirm email.

		$user->confirm_email_change();


	}

	public function edit_profile_url( $link, $user ) {

		$options = new Options();

		$accounts_url = $options->get_accounts_page_url();

		$link = add_query_arg( 'account-page', 'update-personal-information', $accounts_url );

		return $link;

	}

	public function new_user_email_content( $email_text, $new_user_email ) {

		$options = new Options();

		$accounts_url = $options->get_accounts_page_url();

		$url = add_query_arg( [
			'account-page' => 'update-email-address',
			'newuseremail' => $new_user_email['hash']
		], $accounts_url );

		$content = str_replace( '###ADMIN_URL###', '###NEW_ADMIN_URL###', $email_text );

		$new_content = str_replace( '###NEW_ADMIN_URL###', esc_url_raw( $url ), $content );

		return $new_content;

	}

	public function dismiss_email_change() {

		check_admin_referer( 'dismiss_user_email_change_' . get_current_user_id() );

		delete_user_meta( get_current_user_id(), '_new_email' );

		$flash = new FlashMessage( get_current_user_id(), 'subway-user-edit-email' );

		$flash->add( [ 'type' => 'success', 'message' => esc_html__( 'Email change request successfully deleted', 'subway' ) ] );

		$options = new Options();

		$redirect_url = esc_url_raw( add_query_arg( 'account-page', 'update-email-address', $options->get_accounts_page_url() ) );

		wp_safe_redirect( $redirect_url, 302 );

		exit;

	}

	public function listen() {

		if ( isset( $_GET['newuseremail'] ) && ! empty( $_GET['newuseremail'] ) ) {
			add_action( 'wp', array( $this, 'confirm_email' ) );
		}

		if ( isset( $_GET['action'] ) && ! empty( $_GET['action'] ) ) {
			if ( 'cancel-email' === $_GET['action'] ) {
				add_action( 'wp', array( $this, 'dismiss_email_change' ) );
			}
		}

		add_action( 'wp', array( $this, 'confirm_order' ) );

		if ( ! is_admin() ) {

			add_filter( 'get_edit_user_link', array( $this, 'edit_profile_url' ), 10, 2 );

		}

		add_filter( 'new_user_email_content', array( $this, 'new_user_email_content' ), 10, 2 );

	}

}