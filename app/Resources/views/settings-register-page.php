<?php
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

$subway_options_register_page = absint( get_option( 'subway_options_register_page' ) );

if ( ! empty( $subway_options_register_page ) ) {

	$register_page_object = get_post( $subway_options_register_page );

	if ( ! empty( $register_page_object ) && isset( $register_page_object->post_content ) ) {

// Automatically prepend the login shortcode if no shortcode exists in the selected login page.
		if ( ! has_shortcode( $register_page_object->post_content, 'subway_register' ) ) {

			$new_post_object = array(
				'ID'           => $register_page_object->ID,
				'post_content' => '[subway_register] ' . $register_page_object->post_content,// Prepend Only.
			);

			wp_update_post( $new_post_object );
		}
	}
}

wp_dropdown_pages(
	array(
		'name'             => 'subway_options_register_page',
		'selected'         => absint( $subway_options_register_page ),
		'show_option_none' => esc_html__( 'Select Registration Page', 'subway' ),
	)
);
?>
<button type="button" class="button button-secondary button-medium">Create</button>
<p class="description">
	<?php
		echo sprintf( esc_html__( "Select a page to be used as your registration page. 
		Click 'Create' button to automatically select and create new page (Register). 
		The shortcode [subway_register] will be automatically added to the selected page.", 'subway' ) )
	?>
</p>
