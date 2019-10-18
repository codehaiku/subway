<?php
$subway_seller_city = get_option( 'subway_seller_city', '' ); ?>
<input
	autocomplete="off"
	name="subway_seller_city"
	value="<?php echo esc_attr( $subway_seller_city ); ?>"
	type="text"
	class="regular-text"
	placeholder="<?php esc_html_e( 'Enter the name of the city', 'subway' ); ?>"
/>