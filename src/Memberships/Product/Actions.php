<?php

namespace Subway\Memberships\Product;

use Subway\FlashMessage\FlashMessage;
use Subway\Helpers\Helpers;
use Subway\Validators\GUMP;

class Actions extends Controller {

	public function edit() {

		$validator = new GUMP();

		$validator->validation_rules( array(
			'name'        => 'required|alpha_space|max_len,200',
			'description' => 'required',
			'tax_rate'    => 'required|numeric|max_numeric,75',
		) );

		$validated = $validator->run( $_POST );

		$flash = new FlashMessage( get_current_user_id(), 'subway_product_edit' );

		if ( false === $validated ) {

			$flash->add( [ 'type' => 'error', 'message' => $validator->get_errors_array() ] );

			wp_safe_redirect( wp_get_referer(), 302 );

			exit;

		} else {

			$flash->add( [
				'type'    => 'success',
				'message' => __( 'Product has been successfully updated.', ' subway' )
			] );

			if ( empty( $validated['tax_rate_displayed'] ) ) {
				$validated['tax_rate_displayed'] = false;
			} else {
				$validated['tax_rate_displayed'] = true;
			}

			// Fetch the product to populate the object properties.
			$product = new Controller();
			$product->set_id( $validated['id'] );
			$product->get();

			// Update other attributes.
			$product->set_name( $validated['name'] )
			        ->set_description( wp_kses_post( wp_unslash( $validated['description'] ) ) )
			        ->set_status( $validated['status'] )
			        ->set_tax_rate( $validated['tax_rate'] )
			        ->set_tax_displayed( $validated['tax_rate_displayed'] )
			        ->set_date_updated( current_time( 'mysql' ) );

			$product->update();

			wp_safe_redirect( wp_get_referer(), 302 );

		}

	}

	public function edit_set_default_plan() {

		$product_id = filter_input( 1, 'product-id', FILTER_SANITIZE_NUMBER_INT );
		$plan_id    = filter_input( 1, 'plan-id', FILTER_SANITIZE_NUMBER_INT );

		if ( empty( $product_id ) ) {
			wp_die( __( 'Invalid Product ID', 'subway' ), __( 'Invalid Product ID', 'subway' ) );
		}

		if ( empty( $plan_id ) ) {
			wp_die( __( 'Invalid Plan ID', 'subway' ), __( 'Invalid Plan ID', 'subway' ) );
		}

		$this->set_id( $product_id );
		$product = $this->get();

		if ( $product ) {
			// Set the default plan id to requested plan id.
			$product->set_default_plan_id( $plan_id );

			$updated = $product->update();

			if ( $updated ) {
				wp_safe_redirect( wp_get_referer(), 302 );
				exit;
			}
		}

		return;
	}

	public function attach_hooks() {
		$this->define_hooks();
	}

	private function define_hooks() {
		add_action( 'admin_post_subway_product_edit', [ $this, 'edit' ] );
		add_action( 'admin_post_subway_product_edit_set_default_plan', [ $this, 'edit_set_default_plan' ] );

	}

}