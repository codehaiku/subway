<?php

namespace Subway\Post\Shortcodes;

use Subway\Currency\Currency;
use Subway\Memberships\Plan\Plan;
use Subway\Options\Options;
use Subway\View\View;

class Checkout {

	protected $view;

	public function __construct( View $view ) {

		$this->view = $view;

	}

	public function view() {

		$plan_id = filter_input( INPUT_GET, 'plan_id', FILTER_SANITIZE_NUMBER_INT );

		$plan = new Plan();

		if ( $plan ) {

			$plan->set_display_tax( true );

			$plan = $plan->get_plan( $plan_id );

		} else {
			$plan = [];
		}

		$currency = new Currency();

		$options = new Options();

		return $this->view->render( 'shortcode-checkout',
			[
				'view'     => $this->view,
				'plan'     => $plan,
				'currency' => $currency,
				'options'  => $options
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