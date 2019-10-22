<div class="wrap">

    <h1 class="wp-heading-inline">
		<?php esc_html_e( 'Membership Products', 'subway' ); ?>
    </h1>

    <a href="?page=subway-membership-plans&amp;new=yes" class="page-title-action">
		<?php esc_html_e( 'Add New', 'subway' ); ?>
    </a>

    <hr class="wp-header-end">

	<?php $products = new \Subway\Memberships\Product\Controller(); ?>

	<?php
	$current_page = filter_input( INPUT_GET, 'paged', FILTER_VALIDATE_INT, [ 'options' => [ 'default' => 1 ] ] );
	$order_by     = filter_input( INPUT_GET, 'order-by', FILTER_SANITIZE_STRING, [ 'options' => [ 'default' => 'date_created' ] ] );
	$order_dir    = filter_input( INPUT_GET, 'order-dir', FILTER_SANITIZE_STRING, [ 'options' => [ 'default' => 'DESC' ] ] );
	?>

	<?php
	$list = $products->fetch_all( [
		'current_page' => $current_page,
		'field'        => $order_by,
		'direction'    => $order_dir
	] );
	?>

	<?php if ( ! empty( $list->products ) ): ?>

        <div class="subway-filter">

            <form method="GET">
                <input type="hidden" name="page" value="subway-membership"/>
                <!--Reset Paging-->
                <input type="hidden" name="paged" value="1"/>
                <p>
                    <label for="order-by">
                        <strong>
                            <span class="dashicons dashicons-editor-alignleft" style="margin-top: 5px;"></span>
                        </strong>
                    </label>
					<?php
					$list_order_by = [
						'date_created' => __( 'Latest', 'subway' ),
						'date_updated' => __( 'Last Updated', 'subway' ),
						'name'         => __( 'Alphabetical', 'subway' ),
					];
					?>
                    <select id="order-by" name="order-by">
						<?php foreach ( $list_order_by as $key => $value ): ?>
							<?php $selected = $key === $order_by ? 'selected' : ''; ?>
                            <option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $key ); ?>">
								<?php echo esc_html( $value ); ?>
                            </option>
						<?php endforeach; ?>
                    </select>

                    <label for="order-dir">
                        <strong>
                            <span class="dashicons dashicons-text" style="margin-top: 5px;"></span>
                        </strong>
                    </label>
					<?php
					$list_order_by_direction = [
						'DESC' => __( 'Descending', 'subway' ),
						'ASC'  => __( 'Ascending', 'subway' )
					];
					?>

                    <select id="order-dir" name="order-dir">
						<?php foreach ( $list_order_by_direction as $key => $value ): ?>
							<?php $selected = $key === $order_dir ? 'selected' : ''; ?>
                            <option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $key ); ?>">
								<?php echo esc_html( $value ); ?>
                            </option>
						<?php endforeach; ?>
                    </select>

                    <input type="submit" class="button button-large"
                           value="<?php esc_attr_e( 'Filter', 'subway' ); ?>"/>
                </p>
            </form>

        </div>

        <div class="subway-pagination">
            <span class="label subway-padding-right-5">
                <?php echo esc_html( sprintf( __( 'Found %s Item(s)', 'subway' ), number_format( $list->info_result['total'] ) ) ); ?>
            </span>
			<?php echo $products->get_pagination( $list ); ?>
        </div>

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
                                <a class="subway-product-title" href="<?php echo esc_url( '?action=edit&page=subway-membership&id=' . absint( $item->get_id() ) ); ?>"
                                   title="<?php echo esc_attr( $item->get_name() ); ?>">
									<?php echo $item->get_name(); ?>
                                </a>
                            </h3>
                            <p>
								<?php echo $item->get_description(); ?>
                            </p>
                            <div class="actions">
                                <a href="<?php echo esc_url( '?action=edit&page=subway-membership&id=' . absint( $item->get_id() ) ); ?>"
                                   class="button button-primary button-large">
									<?php esc_html_e( 'Configure', 'box-membership' ); ?>
                                </a>

                                <a href="<?php echo esc_url( $item->get_id() ); ?>"
                                   class="button button-secondary button-large">
									<?php esc_html_e( 'See Product', 'box-membership' ); ?>
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

        <div class="subway-pagination">
            <br/>
            <span class="label subway-padding-right-5">
                <?php echo esc_html( sprintf( __( 'Found %s Item(s)', 'subway' ), number_format( $list->info_result['total'] ) ) ); ?>
            </span>
			<?php echo $products->get_pagination( $list ); ?>
        </div>

	<?php else: ?>
		<?php //@Todo: Create a no products section ?>
        <h3>
			<?php esc_html_e( 'Create new membership product and start adding plans to get started', 'subway' ); ?>
        </h3>
        <p>
            <img src="https://image.freepik.com/free-vector/recruiting-professionals-studying-candidate-profiles_1262-21404.jpg"/>
        </p>
        <p>
            <a href="?page=subway-membership-plans&amp;new=yes" class="button button-large button-primary">
				<?php esc_html_e( 'Create Membership Product', 'subway' ); ?>
            </a>
        </p>

	<?php endif; ?>
</div>


