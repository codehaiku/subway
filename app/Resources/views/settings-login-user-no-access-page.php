<?php

if ( ! defined( 'ABSPATH' ) ) {
	return;
}


$subway_logged_in_user_no_access_page = intval( get_option( 'subway_logged_in_user_no_access_page' ) );

if ( ! empty( $subway_logged_in_user_no_access_page ) ) {

	$login_page_object = get_post( $subway_logged_in_user_no_access_page );

}

wp_dropdown_pages(
	array(
		'name'             => 'subway_logged_in_user_no_access_page',
		'selected'         => intval( $subway_logged_in_user_no_access_page ),
		'show_option_none' => esc_html__( '---', 'subway' ),
	)
);
?>
    <button type="button" class="button button-secondary button-medium">Create</button>
    <p class="description">

<?php
echo '<p class="description">' . sprintf( esc_html__( "This page will display all published memberships 
plans or products in a single page. A shortcode [subway_membership_products] 
will be automatically added.", 'subway' ) ) . '</span></p>';

