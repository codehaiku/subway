<?php

namespace Subway\Memberships\Plan;

use Subway\FlashMessage\FlashMessage;
use Subway\Helpers\Helpers;
use Subway\User\User;
use Subway\Validators\GUMP;

class Controller extends Plan {

	const trashed = 'trashed';

	const published = 'published';

	public function add_action() {

		$this->check_admin();

		check_admin_referer( 'subway_plan_add_action', 'subway_plan_add_action' );

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
				'product_id'  => $validated['product'],
				'description' => $desc,
				'sku'         => $sku
			] );

		} catch ( \Exception $e ) {

			echo $e->getMessage();

		}

		$redirect_url = esc_url_raw( add_query_arg(
			[
				'page' => 'subway-membership-plans',
				'edit' => 'yes',
				'plan' => $product_id
			],
			admin_url( 'admin.php?section=plan-pricing' )
		) );

		$redirect_url = $redirect_url . '#section-plan-pricing';

		$flash = new FlashMessage( get_current_user_id(), 'plan-edit-submit-messages' );

		$flash->add( [
			'type'    => 'success',
			'message' => esc_html__( 'Successfully added new membership plan. Go ahead and configure the pricing and email settings.', 'subway' )
		] );

		wp_safe_redirect( $redirect_url, 302 );

		exit;

	}

	public function trash_action() {

		$this->check_admin();

		$plan_id = filter_input( 1, 'plan-id', 519 );

		check_admin_referer( sprintf( 'subway_plan_trash_action_%d', $plan_id ) );

		$plan = $this->get_plan( $plan_id );

		$plan->update( [
			'status' => self::trashed
		], $plan_id );

		wp_safe_redirect( wp_get_referer(), 302 );

		exit;

	}

	/**
	 * Updates the status of plan to 'published'.
	 */
	public function restore_action() {

		$this->check_admin();

		$plan_id = filter_input( 1, 'plan-id', 519 );

		check_admin_referer( sprintf( 'subway_plan_restore_action_%d', $plan_id ) );

		$this->update( [
			'status' => self::published
		], $plan_id );

		wp_safe_redirect( wp_get_referer(), 302 );

		exit;

	}

	public function edit_action() {

		$this->check_admin();

		check_admin_referer( 'subway_plan_edit_action', 'subway_plan_edit_action' );

		do_action('subway_membership_plans_edit_before');

		$id      = filter_input( 0, 'plan_id', 519 );
		$title   = filter_input( 0, 'title', 513 );
		$desc    = filter_input( 0, 'description', FILTER_DEFAULT );
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
					'description' => wp_kses_post( wp_unslash( $desc ) ),
					'sku'         => $sku,
					'amount'      => $amount,
					'type'        => $type
				]
			] );

			wp_safe_redirect( $referrer, 302 );

			exit;

		}

		$args = [
			'name'        => $title,
			'product_id'  => $validated['product'],
			'description' => $desc,
			'amount'      => round( $amount, 2 ), // Round the amount to 2nd decimal place.
			'type'        => $type,
			'sku'         => $sku,
			'status'      => $status
		];

		$updated = $this->update( $args, $id );

		if ( false === $updated ) {

			$flash->add( [
				'type'    => 'error',
				'message' => __( 'An error occurred while updating this membership plan.', 'subway' )
			] );

		} else {

			do_action( 'subway_product_updated' );

			$flash->add( [
				'type'    => 'success',
				'message' => __( 'Memberships Plan has been successfully updated.', 'subway' )
			] );
		}

		do_action('subway_membership_plans_edit_after');

		wp_safe_redirect( $referrer, 302 );

		exit;

	}

	public function get_plan_details() {

		header( 'Content-Type: application/json' );

		$plan_id = filter_input( 1, 'plan_id', FILTER_SANITIZE_NUMBER_INT );

		$plan = $this->get_plan( $plan_id );

		$response = [
			'type' => 'fail',
			'plan' => [
				'id'              => '',
				'name'            => '',
				'description'     => '',
				'sku'             => '',
				'displayed_price' => '',
				'tax_rate'        => '',
				'type'            => ''
			]
		];

		if ( $plan ) {
			$response['type']                    = 'success';
			$response['plan']['id']              = $plan->get_id();
			$response['plan']['name']            = $plan->get_name();
			$response['plan']['description']     = wp_kses_post( wpautop( $plan->get_description() ) );
			$response['plan']['sku']             = $plan->get_sku();
			$response['plan']['displayed_price'] = $plan->get_displayed_price();
			$response['plan']['tax_rate']        = $plan->get_tax_rate();
			$response['plan']['type']            = $plan->get_type();
			$response['user']['has_plan']        = false;

			$user = new User();
			$user->set_id( get_current_user_id() );

			if ( $user->has_plan( $plan->get_id() ) ) {
				$response['user']['has_plan'] = true;
			}
		}

		echo json_encode( $response );

		die;

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

		add_action( 'admin_post_subway_plan_edit_action', [ $this, 'edit_action' ] );

		add_action( 'admin_post_subway_plan_add_action', [ $this, 'add_action' ] );

		add_action( 'admin_post_subway_plan_trash_action', [ $this, 'trash_action' ] );

		add_action( 'admin_post_subway_plan_restore_action', [ $this, 'restore_action' ] );

		add_action( 'wp_ajax_get_plan_details', [ $this, 'get_plan_details' ] );

		add_action( 'wp_ajax_nopriv_get_plan_details', [ $this, 'get_plan_details' ] );

	}

}