<?php

namespace Subway\Post\Shortcodes;

use Subway\Memberships\Products\Products;
use Subway\View\View;

class Memberships {

	protected $view;

	public function __construct( View $view ) {

		$this->view = $view;
	}

	public function display() {

		add_action( 'wp_enqueue_scripts', array( $this, 'stylesheet' ) );

		$product     = new Products();
		$products = $product->get_products([]);

		return $this->view->render( 'shortcode-memberships', [
			'products' => $products,
			'product' => $product
		], true );

	}

	public function attach_hooks() {
		$this->define_hooks();
	}

	protected function define_hooks() {
		add_shortcode( 'subway_memberships', array( $this, 'display' ) );
	}
}