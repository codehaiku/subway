<div class="wrap">

    <h1 class="wp-heading-inline">Membership Products</h1>

    <a href="?page=subway-membership-plans&amp;new=yes" class="page-title-action">Add New</a>

    <hr class="wp-header-end">

	<?php $products = new \Subway\Memberships\Product\Product(); ?>

	<?php
	$current_page = filter_input(
		INPUT_GET, 'paged', FILTER_VALIDATE_INT,
		[ 'options' => [ 'default' => 1 ] ]
	);
	?>
	<?php $list = $products->fetch_all( [ 'current_page' => $current_page ] ); ?>

    <div class="subway-filter">
        <div class="subway-flex-wrap h-align-center">
            <div class="subway-flex-column-40">
                <p class="label subway-padding-right-5">
                    <form method="GET">
                    <input type="hidden" name="page" value="subway-membership" />
                    <input type="hidden" name="paged" value="<?php echo esc_attr( $current_page ); ?>" />
                        <select name="sort">
                            <option value="latest">Latest</option>
                            <option value="last-updated">Last Updated</option>
                            <option value="alpha">Alphabetically</option>
                        </select>
                        <input type="submit" class="button button-large" value="<?php esc_attr_e('Filter', 'subway'); ?>"/>
                    </form>
                </p>

            </div>
            <div class="subway-flex-column-60 subway-text-align-right">
                <div class="subway-pagination">
                    <span class="label subway-padding-right-5">
                        <?php echo esc_html( sprintf( __('%s Item(s)', 'subway'), number_format( $list->info_result['total'] ) ) ); ?>
                    </span>
					<?php echo $products->get_pagination( $list ); ?>
                </div>
            </div>
        </div>
    </div>

	<?php if ( ! empty( $list ) ): ?>

        <div class="subway-flex-wrap subway-section-offset">

			<?php foreach ( $list->products as $item ): ?>
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
                                <a href="#">
									<?php echo $item->get_name(); ?>
                                </a>
                            </h3>
                            <p>
								<?php echo $item->get_description(); ?>
                            </p>
                            <div class="actions">
                                <a href="<?php echo esc_url( $item->get_id() ); ?>"
                                   class="button button-secondary button-large">
									<?php esc_html_e( 'View', 'box-membership' ); ?>
                                </a>
                                <a href="<?php echo esc_url( $item->get_id() ); ?>"
                                   class="button button-primary button-large">
									<?php esc_html_e( 'Configure', 'box-membership' ); ?>
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

        <div class="subway-pagination subway-mg-top-20">
            <span class="label subway-padding-right-5">
                <?php echo esc_html( sprintf( __('%s Item(s)', 'subway'), number_format( $list->info_result['total'] ) ) ); ?>
            </span>
			<?php echo $products->get_pagination( $list ); ?>
        </div>
	<?php endif; ?>

</div>


