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

			// Update the product.
			$this->set_id( $validated['id'] )
			     ->set_name( $validated['name'] )
			     ->set_description( $validated['description'] )
			     ->set_tax_rate( $validated['tax_rate'] )
			     ->set_tax_displayed( $validated['tax_rate_displayed'] )
			     ->set_date_updated( current_time( 'mysql' ) );

			$this->update();

			wp_safe_redirect( wp_get_referer(), 302 );

		}

	}

	public function attach_hooks() {
		$this->define_hooks();
	}

	private function define_hooks() {
		add_action( 'admin_post_subway_product_edit', [ $this, 'edit' ] );
	}

}