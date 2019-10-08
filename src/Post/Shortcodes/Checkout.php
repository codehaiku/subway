<?php

namespace Subway\Post\Shortcodes;

use Subway\Currency\Currency;
use Subway\Memberships\Products\Products;
use Subway\View\View;

class Checkout {

	protected $view;

	public function __construct( View $view ) {

		$this->view = $view;

	}

	public function view() {

		$product_id = filter_input( INPUT_GET, 'product_id', FILTER_SANITIZE_NUMBER_INT );
		$products   = new Products();
		$product    = $products->get_product( $product_id );

		$currency = new Currency();

		return $this->view->render( 'shortcode-checkout',
			[
				'view'     => $this->view,
				'product'  => $product,
				'currency' => $currency
			],
			true
		);

	}


	public function view_partial() {

		return $this;

	}

	public function attach_hooks() {

		$this->define_hooks();
	}

	private function define_hooks() {

		add_shortcode( 'subway_checkout', [ $this, 'view' ] );

	}
}