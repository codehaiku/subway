<?php

namespace Subway\Post\Shortcodes;

use Subway\Options\Options;
use Subway\View\View;

class UserAccount {

	protected $view;

	public function __construct( View $view ) {
		$this->view = $view;
	}

	public function display() {
		wp_enqueue_style('subway-general');
		return $this->view->render( 'shortcode-user-account', [
			'options' => new Options()
		], true );
	}

	public function attach_hooks() {
		$this->define_hooks();
	}

	protected function define_hooks() {
		add_shortcode( 'subway_user_account', array( $this, 'display' ) );
	}
}