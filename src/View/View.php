<?php
namespace Subway\View;

class View {

	public function render( $file_location, $args )
	{
		
		extract( $args );
		
		$file_path = SUBWAY_DIR_PATH . 'app/Resources/views/' . sanitize_title( $file_location ) . '.php';
		
		if ( file_exists( $file_path ) ) 
		{
			include $file_path;
		}

		return;

	}
}