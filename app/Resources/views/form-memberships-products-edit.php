<div class="wrap">

	<?php

	// The product.
	$product_id = filter_input( 1, 'id', 516 );

	$product = new \Subway\Memberships\Product\Controller();
	$product->set_id( $product_id );
	$product->get();

	// Error Messages.
	$flash   = new \Subway\FlashMessage\FlashMessage( get_current_user_id(), 'subway_product_edit' );
	$notices = $flash->get();

	// Extract the variables to our partial views.
	$extracted_vars = [
		'notices' => $notices,
		'product' => $product
	];

	?>

    <h1 class="wp-heading-inline">

		<?php echo esc_html( $product->get_name() ); ?>

    </h1>

	<?php if ( isset( $notices['type'] ) && $notices['type'] === 'success' ): ?>

        <div class="notice notice-success is-dismissible">

            <p>
				<?php echo esc_html( $notices['message'] ); ?>
            </p>

        </div>

	<?php endif; ?>

    <form autocomplete="off" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">

        <!-- Product Tabs -->
		<?php $this->render( 'tabs', $extracted_vars, false, 'form-memberships-products' ); ?>
        <!-- Product Tabs End -->

        <div class="subway-flex-wrap">

            <div class="subway-flex-column-70">

                <div>
                    <!--Hidden Fields-->
                    <input type="hidden" name="action" value="subway_product_edit"/>

                    <input type="hidden" name="id" value="<?php echo esc_attr( $product_id ); ?>"/>
                    <!--Hidden Fields End-->
                </div>

                <div class="subway-card subway-tab-section subway-product-section <?php echo esc_attr( $product->get_is_active_tab( 'settings' ) ); ?>">

					<?php if ( 'active' === $product->get_is_active_tab( 'settings' ) ): ?>

						<?php $this->render( 'settings', $extracted_vars, false, 'form-memberships-products' ); ?>

					<?php endif; ?>

                </div>

                <div class="subway-mg-top-20 subway-tab-section subway-membership-plans-section <?php echo esc_attr( $product->get_is_active_tab( 'membership-plans' ) ); ?>">

					<?php if ( 'active' === $product->get_is_active_tab( 'membership-plans' ) ): ?>

						<?php $this->render( 'plans', $extracted_vars, false, 'form-memberships-products' ); ?>

					<?php endif; ?>

                </div>

                <div class="subway-mg-top-20 subway-tab-section subway-subscribers-section <?php echo esc_attr( $product->get_is_active_tab( 'subscribers' ) ); ?>">

					<?php if ( 'active' === $product->get_is_active_tab( 'subscribers' ) ): ?>

						<?php $this->render( 'users', $extracted_vars, false, 'form-memberships-products' ); ?>

					<?php endif; ?>

                </div>

            </div>

            <div class="subway-flex-column-30">

                <div class="subway-flex-inner-wrap" style="margin-top:-10px">

					<?php $this->render( 'status', $extracted_vars, false, 'form-memberships-products' ); ?>

					<?php if ( 'active' === $product->get_is_active_tab( 'settings' ) ): ?>
                        <div class="subway-card">
                            <div style="display: block; text-align: center;">
                                <input id="publish-product" type="submit" class="button button-primary button-large"
                                       value="<?php esc_attr_e( 'Update Product', 'subway' ); ?>">
                                <a href="#" class="button button-danger button-large">
									<?php esc_html_e( 'Move to Trash', 'subway' ); ?>
                                </a>
                            </div>
                        </div>
					<?php endif; ?>

                </div>


            </div>

        </div><!--FlexWrap-->

    </form>

</div><!--.wrap-->