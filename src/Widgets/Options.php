<?php
namespace Subway\Widgets;
use Subway\View\View;
use Subway\User\User;

if ( ! defined('ABSPATH') ) {
    return;
}

class Options {

	var $allowed_access_type = array('public', 'private');

	var $view = '';

	var $user = '';

	public function __construct()
	{
		$this->view = new View();
		$this->user = new User();
	}

	public function form( $widget, $return, $instance )
	{

		$args = array('widget' => $widget,'return' => $return,'instance' => $instance );

		$this->view->render('form-widget', $args);

		return;

	}

	public function update( $instance, $new_instance )
	{
		if ( ! isset( $new_instance['subway-widget-access-roles'] ) ) {
			$new_instance['subway-widget-access-roles'] = array();
		}
		return $new_instance;
	}

	public function display( $settings, $widget, $args )
	{
		
		// Show all widgets to administrator.
		if ( current_user_can('manage_options') )
		{
			return true;
		}

		// Show all widgets that don't have access types.
		if ( ! isset( $settings['subway-widget-access-type'] ) ) 
		{
			return true;
		}

		// Check if access type options are saved.
		if ( isset( $settings['subway-widget-access-type'] ) )
		{
			$access_type = $settings['subway-widget-access-type'];
				
			// Check to see if access type from options are valid.
			if ( in_array( $access_type, $this->allowed_access_type ) )
			{
				// Show the widget if the settings are set to public.
				if ( 'private' === $access_type )
				{
					
					// Do check for roles and subscription type here.
					$current_user_roles = $this->user->get_role( get_current_user_id() );
					$widget_roles_allowed = $settings['subway-widget-access-roles'];

					// Allow if the user has roles.
					if ( array_intersect( $current_user_roles, $widget_roles_allowed ) ) {
						return true;
					}
					
					echo wp_kses_post( $this->display_message( $settings, $args ) );

					return false;

				} 

				return true;
			}

			return true;
		}
		
		echo wp_kses_post( $this->display_message( $settings, $args ) );

		return false;
	}

	public function display_message( $settings, $r )
	{
		$args = array( 'settings' => $settings, $args => $r );
		$this->view->render('form-widget-message', $args );
	}

	public function attach_hooks() 
	{
		$this->define_hooks();
	}

	private function define_hooks()
	{
		// load the widget option for each widgets.
		add_action('in_widget_form', array( $this, 'form' ), 10, 3);

		// Handle the updating of the widget options.
		add_action('widget_update_callback', array( $this, 'update'), 10, 2);

		// Control the display of the widget.
		add_filter('widget_display_callback', array( $this, 'display'), 10, 3 );

	}
}