<?php
namespace Subway\Helpers;
use Subway\View\View;

class Helpers {

	var $view = '';

	public function __construct()
	{
		$this->view = new View();
	}

	public function display_roles_checkbox( $args )
	{
		$post_id = get_the_id();
       
        $defaults = array( 'name' => '', 'option_name' => '');

        $this->view->render('helper-roles-checkbox', [ 'args' => $args ] );

	}

	public function attach_hooks() 
	{

		$this->define_hooks();

	}

	private function define_hooks()
	{
		// Blank.
	}

}