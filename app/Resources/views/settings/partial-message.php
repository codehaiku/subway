<?php
if ( ! defined( 'ABSPATH' ) ) {
	return;
}


?>
<?php $content = $message = get_option( 'subway_partial_message', esc_html__( 'Please login to see this content', 'subway' ) );; ?>

<?php $settings = apply_filters( 'subway_partial_message_editor',
	array( 'teeny' => false, 'media_buttons' => true, 'editor_height' => 100 ) ); ?>

<?php echo wp_editor( $content, 'subway_partial_message', $settings ); ?>
<?php


