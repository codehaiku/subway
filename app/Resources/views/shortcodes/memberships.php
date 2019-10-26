<?php
if ( ! defined( 'ABSPATH' ) ) {
	return;
}
?>
<?php $products = new \Subway\Memberships\Product\Controller(); ?>
<div id="subway-memberships">
	<?php $items = $products->fetch_all( [ 'status' => 'published' ] ); ?>
	<?php if ( $items->products ): ?>
        <div class="subway-flex-wrap" id="subway-products">
			<?php foreach ( $items->products as $product ): ?>
                <div class="subway-flex-column-50">
                    <div class="subway-flex-inner-wrap">
                        <div class="subway-product-preview">
                            <img title="<?php echo esc_attr( $product->get_name() ); ?>" src="<?php echo esc_url( $product->get_preview_image_url() ); ?>" />
                        </div>
                        <!--Product Details-->
                        <div class="subway-product-details">
                            <h3 class="subway-product-title">
                                <a href="<?php echo esc_url( $product->get_url() ); ?>" title="<?php echo esc_attr( $product->get_name() ); ?>">
				                    <?php echo esc_html( $product->get_name() ); ?>
                                </a>
                            </h3>
                            <div class="subway-product-short-description">
                                <p>
				                    <?php echo strip_tags( trim( str_replace( '&nbsp;', '', $product->get_description() ) ) ); ?>
                                </p>
                            </div>
                            <div class="subway-product-actions">
                                <a class="sw-button" title="<?php esc_html_e('Select Membership Plan', 'subway'); ?>" href="<?php echo esc_url( $product->get_url() ); ?>">
				                    <?php esc_html_e('Select Membership Plan', 'subway'); ?>
                                </a>
                            </div>
                        </div>
                        <!--Product Details End-->
                    </div>
                </div>
			<?php endforeach; ?>
        </div>
	<?php else: ?>
        ss12asdasd
	<?php endif; ?>
</div>