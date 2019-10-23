<ul class="subway-section-tabs" id="product-tabs">
    <li>
        <a class="<?php echo esc_attr( $product->get_is_active_tab( 'settings' ) ); ?>"
           href="<?php echo esc_url_raw( $product->get_product_url_edit() ); ?>">
            <span class="dashicons dashicons-admin-settings"></span>
			<?php esc_html_e( 'Settings', 'subway' ); ?>
        </a>
    </li>
    <li>
        <a class="<?php echo esc_attr( $product->get_is_active_tab( 'membership-plans' ) ); ?>"
           href="<?php echo esc_url_raw( $product->get_product_url_edit( 'membership-plans' ) ); ?>">
            <span class="dashicons dashicons-buddicons-groups"></span>
			<?php esc_html_e( 'Membership Plans', 'subway' ); ?>
        </a>
    </li>
    <li>
        <a class="<?php echo esc_attr( $product->get_is_active_tab( 'subscribers' ) ); ?>"
           href="<?php echo esc_url_raw( $product->get_product_url_edit( 'subscribers' ) ); ?>">
            <span class="dashicons dashicons-groups"></span>
			<?php esc_html_e( 'Subscribers', 'subway' ); ?>
        </a>
    </li>
</ul>