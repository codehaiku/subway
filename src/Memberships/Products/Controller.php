<?php

namespace Subway\Memberships\Products;

use Subway\Helpers\Helpers;

class Controller extends Products {

	public function edit_action() {

		check_admin_referer( 'subway_product_edit_action', 'subway_product_edit_action' );

		$id     = filter_input( 0, 'product_id', 519 );
		$title  = filter_input( 0, 'title', 513 );
		$desc   = filter_input( 0, 'description', 513 );
		$amount = filter_input( 0, 'amount', 513 );
		$type   = filter_input( 0, 'type', 513 );
		$sku    = filter_input( 0, 'sku', 513 );
		$status = filter_input( 0, 'status', 513 );

		$referrer = filter_input( INPUT_SERVER, 'HTTP_REFERER', 518 );

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


			wp_safe_redirect( $referrer );

			die();

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

	}

	public function attach_hooks() {
		$this->define_hooks();
	}

	private function define_hooks() {

		add_action( 'admin_post_subway_product_edit_action', [ $this, 'edit_action' ] );

	}

}