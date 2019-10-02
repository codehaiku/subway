<?php
if ( ! defined('ABSPATH') ) {
    return;
}
?>
<div id="subway-memberships">
	<?php if ( ! empty ( $products ) ): ?>
        <ul class="subway-membership-lists">
            <?php foreach( $products as $list_product ): ?>
                <li class="subway-membership-list">
                    <div class="subway-membership-list-wrap">
                        <div class="subway-membership-list-wrap-inner-wrap">
                        <img src="https://images.ctfassets.net/1nw4q0oohfju/7LHJsclEKmfusO4qqEisMg/588af5c8765d713ce4e9cdc3bf9008a1/Frame_3__1_.png" />
                            <div class="details">
                                <h3 class="product-title">
                                    <a href="#">
                                        <?php echo esc_html( $list_product['name'] ); ?>
                                    </a>
                                </h3>
                                <h4 class="product-amount">
                                    USD $<?php echo esc_html( $list_product['amount'] ); ?>
                                </h4>
                                <p class="product-description">
                                    <?php echo wp_kses_post( $list_product['description']); ?>
                                </p>

                                <p>
                                    <a class="sw-button" href="<?php echo esc_url( $product->get_product_checkout_url( $list_product['id']) ); ?>">
                                        <?php esc_html_e('Buy Now', 'subway'); ?>
                                    </a>
                                </p>
                             </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <div class="sw-form-errors">
            <p class="sw-error">
	            <?php esc_html_e('There are no memberships product available at the moment.', 'subway'); ?>
            </p>
        </div>
    <?php endif; ?>
</div>