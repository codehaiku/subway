<?php
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

$account_page = absint( get_option( 'subway_options_account_page' ) );

if ( ! empty( $account_page ) ) {

	$account_page_object = get_post( $account_page );

	if ( ! empty( $account_page_object ) && isset( $account_page_object->post_content ) ) {
	    
		if ( ! has_shortcode( $account_page_object->post_content, 'subway_user_account' ) ) {

			$new_post_object = array(
				'ID'           => $account_page_object->ID,
				'post_content' => '[subway_user_account] ' . $account_page_object->post_content,// Prepend Only.
			);

			wp_update_post( $account_page_object );
		}
	}
}

wp_dropdown_pages(
	array(
		'name'             => 'subway_options_account_page',
		'selected'         => absint( $account_page ),
		'show_option_none' => esc_html__( 'Select User Account Page', 'subway' ),
	)
);
?>
<button type="button" class="button button-secondary button-medium">Create</button>
<p class="description">
	<?php
	echo sprintf( esc_html__( "Select a page to be used as user account page. 
		Click 'Create' button to automatically select and create new page (My Account). 
		The shortcode [subway_user_account] will be automatically added to the selected page.", 'subway' ) )
	?>
</p>
