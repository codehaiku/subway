<?php

namespace Subway\Helpers;

use Subway\View\View;

/**
 * Class Helpers
 * @package Subway\Helpers
 */
class Helpers {

	/**
	 * @var string|View
	 */
	protected $view = '';

	/**
	 * Helpers constructor.
	 *
	 * @param View $view
	 */
	public function __construct( View $view ) {
		$this->view = $view;
	}

	/**
	 * @param $args
	 */
	public function display_roles_checkbox( $args ) {
		$post_id  = get_the_id();
		$defaults = array( 'name' => '', 'option_name' => '' );
		$this->view->render(
			'helper-roles-checkbox', [ 'args' => $args ]
		);
	}

}