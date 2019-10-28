<?php
$seller_name = get_option( 'subway_seller_name', '' ); ?>
<input
        autocomplete="off"
        name="subway_seller_name"
        value="<?php echo esc_attr( $seller_name ); ?>"
        type="text"
        class="regular-text"
        placeholder="<?php esc_html_e( 'Your Company Name, LLC', 'subway' ); ?>"
/>