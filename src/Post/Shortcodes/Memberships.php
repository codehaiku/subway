<?php

namespace Subway\Post\Shortcodes;

use Subway\Currency\Currency;
use Subway\Memberships\Plan\Plan;
use Subway\Memberships\Product\Controller;
use Subway\Memberships\Product\Product;
use Subway\Options\Options;
use Subway\View\View;

class Memberships {

	protected $view;

	public function __construct( View $view ) {

		$this->view = $view;
	}

	public function memberships() {

		add_action( 'wp_enqueue_scripts', array( $this, 'stylesheet' ) );

		$plan     = new Plan();
		$currency = new Currency();

		$template = 'memberships';

		$plans = $plan->get_plans( [ 'status' => 'published' ] );

		$args = [
			'plan'     => $plan,
			'plans'    => $plans,
			'currency' => $currency
		];

		$product_id = filter_input( 1, 'box-membership-product-id', 519 );

		if ( ! empty( $product_id ) ) {

			$template = 'product';
			// Product Controller.
			$product = new Controller();
			$product->set_id( $product_id );

			// Get associated membership plans.
			$plans = $plan->get_plans(['product_id' => $product_id, 'status' => 'published']);

			// Options.
			$options = new Options();

			$args['plans'] = $plans;
			$args['options'] = $options;
			$args['product'] = $product->get();

		}

		return $this->view->render( $template, $args, true, 'shortcodes' );

	}

	public function attach_hooks() {

		$this->define_hooks();

	}

	protected function define_hooks() {

		add_shortcode( 'subway_memberships', array( $this, 'memberships' ) );

	}
}