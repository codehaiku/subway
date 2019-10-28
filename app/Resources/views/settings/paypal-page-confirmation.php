<?php
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

$subway_paypal_page_confirmation = absint( get_option( 'subway_paypal_page_confirmation' ) );

wp_dropdown_pages(
	array(
		'name'             => 'subway_paypal_page_confirmation',
		'selected'         => absint( $subway_paypal_page_confirmation ),
		'show_option_none' => esc_html__( 'Select Confirmation Page', 'subway' ),
	)
);
?>
<button type="button" class="button button-secondary button-medium">Create</button>
<p class="description">
	<?php
	echo sprintf( esc_html__( "Select the return url for the order. 
	    This page will process the order of the customer", 'subway' ) )
	?>
</p>
