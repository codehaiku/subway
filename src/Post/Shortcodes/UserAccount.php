<?php

namespace Subway\Post\Shortcodes;

use Subway\FlashMessage\FlashMessage;
use Subway\Options\Options;
use Subway\User\User;
use Subway\View\View;

class UserAccount {

	protected $view;

	public function __construct( View $view ) {
		$this->view = $view;
	}

	public function display() {

		wp_enqueue_style( 'subway-general' );

		$current_page = filter_input( 1, 'account-page', 516 );

		$args = [
			'options' => new Options(),
			'user'    => new User(),
			'wp_user' => wp_get_current_user()
		];

		switch ( $current_page ):
			case 'update-personal-information':
				$flash           = new FlashMessage( get_current_user_id(), 'subway-user-edit-profile' );
				$template        = 'shortcode-user-account-edit';
				$args['message'] = $flash->get();
				break;
			default:
				$template = 'shortcode-user-account';

		endswitch;


		return $this->view->render( $template, $args, true );

	}

	public function attach_hooks() {
		$this->define_hooks();
	}

	protected function define_hooks() {
		add_shortcode( 'subway_user_account', array( $this, 'display' ) );
	}
}