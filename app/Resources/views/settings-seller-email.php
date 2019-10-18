<?php
$subway_seller_email = get_option( 'subway_seller_email', '' ); ?>
<input
	autocomplete="off"
	name="subway_seller_email"
	value="<?php echo esc_attr( $subway_seller_email ); ?>"
	type="email"
	class="regular-text"
	placeholder="<?php esc_html_e( 'yourwebsite@example.com', 'subway' ); ?>"
/>