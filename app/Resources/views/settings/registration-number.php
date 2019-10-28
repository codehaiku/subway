<?php
$subway_seller_registration_number = get_option( 'subway_seller_registration_number', '' ); ?>
<input
        autocomplete="off"
        name="subway_seller_registration_number"
        value="<?php echo esc_attr( $subway_seller_registration_number ); ?>"
        type="text"
        class="regular-text"
        placeholder="<?php esc_html_e( 'Your business registration number', 'subway' ); ?>"
/>