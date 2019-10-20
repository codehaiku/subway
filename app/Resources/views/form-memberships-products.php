<div class="wrap">

    <h1 class="wp-heading-inline">Membership Products</h1>

    <a href="?page=subway-membership-plans&amp;new=yes" class="page-title-action">Add New</a>

    <hr class="wp-header-end">

	<?php $products = new \Subway\Memberships\Product\Product(); ?>

	<?php $list = $products->fetch_all(); ?>

	<?php if ( ! empty( $list ) ): ?>
        <div class="subway-flex-wrap">
			<?php foreach ( $list as $item ): ?>
                <div class="subway-flex-column-by-three">
                    <div class="spacer">
                        <div class="subway-card">
                            <div class="subway-product-preview">
                                <img src="<?php echo esc_url( $item->get_preview_image_url() ); ?>"
                                     alt="<?php esc_html_e( 'Preview', 'subway' ); ?>"
                                     class="subway-product-preview-image"
                                />
                            </div>
                            <h3>
								<?php echo $item->get_name(); ?>
                            </h3>
                            <p>
								<?php echo $item->get_description(); ?>
                            </p>
                            <div class="actions">
                                <a href="<?php echo esc_url( $item->get_id() ); ?>" class="button button-primary button-large">
									<?php esc_html_e( 'Edit', 'box-membership' ); ?>
                                </a>
                                <span class="product-status">
                                    <?php echo esc_html( $item->get_status() ); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
			<?php endforeach; ?>
        </div>
	<?php endif; ?>

</div>

<?php
$p = new \Subway\Memberships\Product\Product();
$p->set_id(10);
$p->set_name( 'Awesome IvI');
$p->set_description('Wow!');
$p->set_tax_rate(12);
$p->update();