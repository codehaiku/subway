<?php

namespace Subway\View;

class View {

	public function render( $file_location, $args, $return = false ) {

		extract( $args );

		$file_path = SUBWAY_DIR_PATH . 'app/Resources/views/' . sanitize_title( $file_location ) . '.php';

		if ( ! file_exists( $file_path ) ) {

			echo "<code>";
			echo sprintf( __( 'ERROR: Cannot find %s in view directory', 'subway' ), sanitize_title( $file_location ) );
			echo "</code>";

			return $this;

		}

		if ( $return ) {

			ob_start();

			if ( file_exists( $file_path ) ) {
				include $file_path;
			}

			return ob_get_clean();

		} else {

			if ( file_exists( $file_path ) ) {
				include $file_path;
			}

		}

		return $this;

	}
}