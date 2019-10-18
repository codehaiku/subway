<?php
$subway_seller_vat = get_option( 'subway_seller_vat', '' ); ?>
<input
	autocomplete="off"
	name="subway_seller_vat"
	value="<?php echo esc_attr( $subway_seller_vat ); ?>"
	type="text"
	class="regular-text"
	placeholder="<?php esc_html_e( 'Your business VAT number', 'subway' ); ?>"
/>