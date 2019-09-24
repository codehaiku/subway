<?php
namespace Subway\Bootstrap;

final class Bootstrap{
	
	var $services = array();

	public function __construct()
	{
		$this->services = array(
				'Subway\Helpers\Helpers',
				'Subway\Enqueue\Enqueue',
				'Subway\Options\Options',
				'Subway\Post\Metabox',
				'Subway\Post\Post',
				'Subway\Post\Comments',
				'Subway\Widgets\Options'
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
