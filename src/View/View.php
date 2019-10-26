<?php

namespace Subway\View;

class View {

	public function render( $file_location, $args, $return = false, $sub_dir = '' ) {

		extract( $args );

		$file = sanitize_file_name( $file_location );

		$sub_dir = sanitize_file_name( $sub_dir );

		if ( ! empty( $sub_dir ) ) {
			$sub_dir = sprintf( '%s/', $sub_dir );
		}

		$file_path = SUBWAY_DIR_PATH . 'app/Resources/views/' . $sub_dir . $file . '.php';

		if ( ! file_exists( $file_path ) ) {

			echo "<code>";
			echo sprintf( __( 'ERROR: Cannot find %s %s in view directory', 'subway' ), $sub_dir, $file );
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