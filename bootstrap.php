<?php
namespace Subway\Bootstrap;

use Subway\Widgets\Options as Widget_Options;
use Subway\View\View;
use Subway\User\User;
use Subway\Enqueue\Enqueue;

final class Bootstrap{
	
	var $services = array();

	public function __construct()
	{
		// @todo get out of this shit.
		$this->services = array(
				'Subway\Post\Metabox',
				'Subway\Post\Post',
				'Subway\Post\Comments',
			);
	}

	public function boot() 
	{
		foreach( $this->services as $service )
		{
			$_service = new $service;
			$_service->attach_hooks();
		}
	}
}

$bootstrap = new Bootstrap();
$bootstrap->boot();

// Load our widgets.
$widget = new Widget_Options( new User(), new View() );
$widget->attach_hooks();

// Enqueue Everything.
$assets = new Enqueue();
$assets->attach_hooks();