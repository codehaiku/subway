<?php
$addr_line2 = get_option( 'subway_seller_address_line2', '' ); ?>
<input
	autocomplete="off"
	name="subway_seller_address_line2"
	value="<?php echo esc_attr( $addr_line2 ); ?>"
	type="text"
	class="regular-text"
	placeholder="<?php esc_html_e( 'Street Address', 'subway' ); ?>"
/>