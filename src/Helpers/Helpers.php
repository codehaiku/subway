<?php
namespace Subway\Helpers;
use Subway\View\View;

class Helpers {

	var $view = '';

	public function __construct()
	{
		$this->view = new View();
	}

	public function display_roles_checkbox()
	{
		$post_id = get_the_id();
       
        $defaults = array(
                'name' => '',
                'option_name' => ''
            );

        $args = wp_parse_args( $args, $defaults );

        $r = array(
        	'args' => $args
        );

        $this->view->render('helper-roles-checkbox', $r );

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