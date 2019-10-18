<?php
$subway_seller_postal_code = get_option( 'subway_seller_postal_code', '' ); ?>
<input
	autocomplete="off"
	name="subway_seller_postal_code"
	value="<?php echo esc_attr( $subway_seller_postal_code ); ?>"
	type="text"
	class="regular-text"
	placeholder="<?php esc_html_e( 'N-N-N-N-N', 'subway' ); ?>"
/>