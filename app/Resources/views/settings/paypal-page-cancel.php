<?php
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

$subway_paypal_page_cancel = absint( get_option( 'subway_paypal_page_cancel' ) );

wp_dropdown_pages(
	array(
		'name'             => 'subway_paypal_page_cancel',
		'selected'         => absint( $subway_paypal_page_cancel ),
		'show_option_none' => esc_html__( 'Select Cancel Page', 'subway' ),
	)
);
?>
<button type="button" class="button button-secondary button-medium">Create</button>
<p class="description">
	<?php
	echo sprintf( esc_html__( "Select the cancel order page. 
	    User will be redirected into this page when he cancel the payment.", 'subway' ) )
	?>
</p>
