<?php

namespace Subway\Post\Shortcodes;

use Subway\Currency\Currency;
use Subway\Memberships\Plan\Plan;
use Subway\View\View;

class Memberships {

	protected $view;

	public function __construct( View $view ) {

		$this->view = $view;
	}

	public function memberships() {

		add_action( 'wp_enqueue_scripts', array( $this, 'stylesheet' ) );

		$plan    = new Plan();
		$currency = new Currency();

		$plans = $plan->get_plans( [ 'status' => 'published' ] );

		$args = [
			'plan'     => $plan,
			'plans'    => $plans,
			'currency' => $currency
		];

		return $this->view->render( 'memberships', $args, true, 'shortcodes' );

	}

	public function attach_hooks() {

		$this->define_hooks();

	}

	protected function define_hooks() {

		add_shortcode( 'subway_memberships', array( $this, 'memberships' ) );

	}
}