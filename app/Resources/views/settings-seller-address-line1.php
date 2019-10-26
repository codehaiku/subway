<?php
$addr_line1 = get_option( 'subway_seller_address_line1', '' ); ?>
<input
        autocomplete="off"
        name="subway_seller_address_line1"
        value="<?php echo esc_attr( $addr_line1 ); ?>"
        type="text"
        class="regular-text"
        placeholder="<?php esc_html_e( 'Suite or Apartment Number', 'subway' ); ?>"
/>