<?php
if ( ! defined( 'ABSPATH') ) {
	return;
}
?>
<input type="text" class="widefat" name="subway_paypal_client_id"
       value="<?php echo esc_attr( get_option('subway_paypal_client_id') ) ?>"/>