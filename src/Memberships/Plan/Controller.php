<?php

namespace Subway\Memberships\Plan;

use Subway\FlashMessage\FlashMessage;
use Subway\Helpers\Helpers;
use Subway\Validators\GUMP;

class Controller extends Plan {

	public function add_action() {

		$this->check_admin();

		check_admin_referer( 'subway_product_add_action', 'subway_product_add_action' );

		$title = filter_input( 0, 'title', 513 );
		$desc  = filter_input( 0, 'description', 513 );
		$sku   = filter_input( 0, 'sku', 513 );

		$validator = new GUMP();

		$validator->validation_rules( array(
			'title'       => 'required|alpha_space|max_len,200',
			'description' => 'required',
			'sku'         => 'required|alpha_dash|max_len,100',
		) );

		$validated = $validator->run( $_POST );

		if ( $validated === false ) {

			$flash = new FlashMessage( get_current_user_id(), 'product-add-submit-messages' );

			$flash->add( [
				'validation' => $validator->get_errors_array(),
				'form_data'  => [
					'title'       => $title,
					'description' => $desc,
					'sku'         => $sku
				]
			] );

			wp_safe_redirect( wp_get_referer(), 302 );

			exit;
		}

		try {


			$product_id = $this->add( [
				'name'        => $title,
				'description' => $desc,
				'sku'         => $sku
			] );

		} catch ( \Exception $e ) {

			echo $e->getMessage();

		}

		$redirect_url = esc_url_raw( add_query_arg(
			[
				'page'    => 'subway-membership-plans',
				'edit'    => 'yes',
				'product' => $product_id
			],
			admin_url( 'admin.php?section=product-pricing' )
		) );

		$redirect_url = $redirect_url . '#section-product-pricing';

		$flash = new FlashMessage( get_current_user_id(), 'product-edit-submit-messages' );

		$flash->add( [
			'type'    => 'success',
			'message' => esc_html__( 'Successfully added new membership plan. Go ahead and configure the pricing and email settings.', 'subway' )
		] );

		wp_safe_redirect( $redirect_url, 302 );

		exit;

	}

	public function edit_action() {

		$this->check_admin();

		check_admin_referer( 'subway_product_edit_action', 'subway_product_edit_action' );

		$id      = filter_input( 0, 'product_id', 519 );
		$title   = filter_input( 0, 'title', 513 );
		$desc    = filter_input( 0, 'description', 513 );
		$amount  = filter_input( 0, 'amount', 513 );
		$type    = filter_input( 0, 'type', 513 );
		$sku     = filter_input( 0, 'sku', 513 );
		$status  = filter_input( 0, 'status', 513 );
		$section = filter_input( 0, 'active-section', 513 );

		$referrer = esc_url_raw( add_query_arg( 'section', $section, wp_get_referer() ) );

		$flash = new FlashMessage( get_current_user_id(), 'product-edit-submit-messages' );

		// Validate.
		$validator = new GUMP();

		$rules = [
			'title'       => 'required|alpha_space|max_len,200',
			'description' => 'required',
			'sku'         => 'required|alpha_dash|max_len,100',
			'amount'      => 'required|float|min_numeric,0.1'
		];

		// Disable validation on amount if the price is free. :)
		if ( 'free' === $type ) {
			unset( $rules['amount'] );
		}

		$validator->validation_rules( $rules );

		$validated = $validator->run( $_POST );

		if ( false === $validated ) {

			$flash->add( [
				'validation' => $validator->get_errors_array(),
				'form_data'  => [
					'title'       => $title,
					'description' => $desc,
					'sku'         => $sku,
					'amount'      => $amount,
					'type'        => $type
				]
			] );

			wp_safe_redirect( $referrer, 302 );

			exit;

		}

		try {

			$updated = $this->update( [
				'id'          => $id,
				'title'       => $title,
				'description' => $desc,
				'amount'      => $amount,
				'type'        => $type,
				'sku'         => $sku,
				'status'      => $status
			] );

			do_action( 'subway_product_edit_saved' );

			$flash->add( [
				'type'    => 'success',
				'message' => __( 'Memberships Plan has been successfully updated.', 'subway' )
			] );

			wp_safe_redirect( $referrer, 302 );

			die();

		} catch ( \Exception $e ) {

			try {

				$flash->add( [ 'type' => 'error', 'message' => $e->getMessage() ] );

			} catch ( \Exception $e ) {

				wp_die(
					$e->getMessage() .
					sprintf(
						'<a href="%1$s" title="%s">%2$s</a>',
						esc_url( $referrer ),
						esc_html__( 'Go Back', 'subway' )
					),
				);

			}

			wp_safe_redirect( $referrer );

		}

	}

	private function check_admin() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You are not allowed to do this.', 'subway' ) );
		}

		return;
	}

	public function attach_hooks() {

		$this->define_hooks();

	}

	private function define_hooks() {

		add_action( 'admin_post_subway_product_edit_action', [ $this, 'edit_action' ] );

		add_action( 'admin_post_subway_product_add_action', [ $this, 'add_action' ] );

	}

}