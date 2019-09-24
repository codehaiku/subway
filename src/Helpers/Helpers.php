<?php
namespace Subway\Helpers;

use Subway\View\View;

class Helpers 
{

	protected $view = '';

    public function __construct( View $view )
    {
        $this->view = $view;
    }

	public function display_roles_checkbox( $args )
	{
        $post_id = get_the_id();
        $defaults = array( 'name' => '', 'option_name' => '');
        $this->view->render('helper-roles-checkbox', [ 'args' => $args ] );
	}

}