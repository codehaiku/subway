<?php

if ( ! defined( 'ABSPATH' ) ) {
	return;
}


$subway_options_checkout_page = intval( get_option( 'subway_options_checkout_page' ) );

if ( ! empty( $subway_options_checkout_page ) ) {

	$login_page_object = get_post( $subway_options_checkout_page );

}

wp_dropdown_pages(
	array(
		'name'             => 'subway_options_checkout_page',
		'selected'         => intval( $subway_options_checkout_page ),
		'show_option_none' => esc_html__( '---', 'subway' ),
	)
);
?>
<button type="button" class="button button-secondary button-medium">
	<?php esc_html_e( 'Create', 'subway' ); ?>
</button>
<p class="description">
	<?php
	echo sprintf( esc_html__( "This page will display all published memberships plans or products in a single page. A shortcode [subway_checkout] will be automatically added.", 'subway' ) );
	?>
</p>

