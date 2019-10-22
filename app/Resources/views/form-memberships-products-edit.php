<div class="wrap">
	<?php
	// The product.
	$product_id = filter_input( 1, 'id', 516 );

	$product = new \Subway\Memberships\Product\Controller();
	$product->set_id( $product_id );
	$product->get();

	// Error Messages
	$flash   = new \Subway\FlashMessage\FlashMessage( get_current_user_id(), 'subway_product_edit' );
	$notices = $flash->get();
	?>

    <h1 class="wp-heading-inline">
		<?php echo sprintf( esc_html__( 'Configure %s', 'subway' ), $product->get_name() ); ?>
    </h1>

    <hr class="wp-header-end">

	<?php if ( isset( $notices['type'] ) && $notices['type'] === 'success' ): ?>

        <div class="notice notice-success is-dismissible">
            <p>
				<?php echo esc_html( $notices['message'] ); ?>
            </p>
        </div>
	<?php endif; ?>

    <form autocomplete="off" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">

        <div class="subway-flex-wrap">

            <div class="subway-flex-column-70">

                <ul id="product-tabs">
                    <li>
                        <a class="active" data-section-target="product-information" href="#">
                            <span class="dashicons dashicons-admin-settings"></span>
							<?php esc_html_e( 'Settings', 'subway' ); ?>
                        </a>
                    </li>
                    <li>
                        <a data-section-target="product-information" href="#">
                            <span class="dashicons dashicons-buddicons-groups"></span>
							<?php esc_html_e( 'Membership Plans', 'subway' ); ?>
                        </a>
                    </li>
                    <li>
                        <a data-section-target="product-information" href="#">
                            <span class="dashicons dashicons-groups"></span>
							<?php esc_html_e( 'Subscribers', 'subway' ); ?>
                        </a>
                    </li>

                </ul>


                <div>
                    <!--hidden fields-->
                    <input type="hidden" name="action" value="subway_product_edit"/>
                    <input type="hidden" name="id" value="<?php echo esc_attr( $product_id ); ?>"/>
                </div>

                <div class="subway-card subway-product-section active">

                    <!--Product Name -->
                    <div class="subway-form-row">
                        <h3 class="field-title">
                            <label for="input-title">
								<?php esc_html_e( 'Name', 'subway' ); ?>
                            </label>
                        </h3>
                        <p>
							<?php esc_html_e( 'Enter the name of your membership product', 'subway' ); ?>
                        </p>
                        <input autofocus value="<?php echo esc_attr( $product->get_name() ); ?>" id="input-title"
                               name="name" type="text" class="widefat"
                               placeholder="<?php esc_attr_e( 'New product name', 'subway' ); ?>"/>

						<?php if ( isset( $notices['message']['name'] ) ): ?>
                            <p class="validation-errors">
								<?php echo $notices['message']['name']; ?>
                            </p>
						<?php endif; ?>
                    </div>
                    <!--/.Product Name -->


                    <!-- Product Description -->
                    <div class="subway-form-row">
                        <h3 class="field-title">
                            <label for="input-description">
								<?php esc_html_e( 'Description', 'subway' ); ?>
                            </label>
                        </h3>
                        <p>
							<?php esc_html_e( 'Explain what this product is all about.', 'subway' ); ?>
                        </p>
                        <textarea id="input-description" name="description" class="widefat" rows="5"
                                  placeholder="<?php esc_attr_e( 'New product description', 'subway' ); ?>"><?php echo wp_kses_post( $product->get_description() ); ?></textarea>
						<?php if ( isset( $notices['message']['description'] ) ): ?>
                            <p class="validation-errors">
								<?php echo $notices['message']['description']; ?>
                            </p>
						<?php endif; ?>
                    </div>
                    <!--/. Product Description -->

                    <!--Tax Rate -->
                    <div class="subway-form-row">
                        <h3 class="field-title">
                            <label for="input-tax-rate">
								<?php esc_html_e( 'Tax Rate', 'subway' ); ?>
                            </label>
                        </h3>
                        <p>
							<?php esc_html_e( 'Enter the tax rate as percentage for this product.', 'subway' ); ?>
                        </p>
                        <input step="0.01" value="<?php echo esc_attr( $product->get_tax_rate() ); ?>"
                               id="input-tax-rate"
                               name="tax_rate" type="number" class="widefat"
                               placeholder="<?php esc_attr_e( 'Enter 12 for 12%. Enter 0 or 0.00 to disable.', 'subway' ); ?>"/>
						<?php if ( isset( $notices['message']['tax_rate'] ) ): ?>
                            <p class="validation-errors">
								<?php echo $notices['message']['tax_rate']; ?>
                            </p>
						<?php endif; ?>
                    </div>
                    <!--/.Tax Rate -->

                    <!--Tax Rate Display-->
                    <div class="subway-form-row">
                        <h3 class="field-title">
                            <label for="input-tax-rate-display">
								<?php esc_html_e( 'Display Tax', 'subway' ); ?>
                            </label>
                        </h3>
                        <p>
                            <label for="input-tax-rate-display">
                                <input <?php checked( true, $product->is_tax_displayed(), true ); ?>
                                        type="checkbox" name="tax_rate_displayed" id="input-tax-rate-display"/>
								<?php esc_html_e( 'Check to include tax in membership plans pricing.', 'subway' ); ?>
                            </label>
                        </p>
                    </div>
                    <!--/.Tax Rate -->

                    <!--Prorating-->
                    <div class="subway-form-row">
                        <h3 class="field-title">
                            <label for="is_prorated">
								<?php esc_html_e( 'Prorate Charges', 'subway' ); ?>
                            </label>
                        </h3>
                        <p>
                            <label for="is_prorated">
                                <input checked type="checkbox" name="is_prorated" id="is_prorated"/>
								<?php esc_html_e( 'Check to automatically prorate charges when member switch to any plan of this product.', 'subway' ); ?>
                            </label>
                        </p>
                    </div>
                    <!--/.Tax Rate -->

                </div>


            </div>


            <div class="subway-flex-column-30">
                <div class="subway-flex-inner-wrap" style="margin-top:3.3em">
                    <div class="subway-card">
                        <!--Product Status-->
                        <div class="subway-form-row">
                            <h3 class="field-title">
                                <label for="product-status">
									<?php esc_html_e( 'Product Status', 'subway' ); ?>
                                </label>
                            </h3>
                            <p class="field-help">
								<?php esc_html_e( 'Change the value of this field to \'Published\' to make this product available to
                                    everyone.', 'subway' ); ?>
                            </p>
							<?php
							$statuses = [
								'draft'     => __( 'Draft', 'subway' ),
								'published' => __( 'Published', 'subway' )
							];
							?>
                          
                            <select id="product-status" name="status">
								<?php foreach ( $statuses as $key => $value ): ?>
									<?php if ( $key === $product->get_status() ): ?>
										<?php $selected = 'selected'; ?>
									<?php else: ?>
										<?php $selected = ''; ?>
									<?php endif; ?>
                                    <option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ); ?>>
										<?php echo esc_html( $value ); ?>
                                    </option>
								<?php endforeach; ?>
                            </select>
                        </div>
                        <!--/.Product Status-->
                        <!--Product Link -->
                        <div class="subway-form-row" id="checkout-link-section">
                            <h3 class="field-title">
                                <label for="input-checkout-link">
									<?php esc_html_e( 'Copy Product URL', 'subway' ); ?>
                                </label>
                            </h3>
                            <p class="field-help">
								<?php esc_html_e( 'Use the url below to share this membership product', 'subway' ); ?>
                            </p>
                            <input readonly="" value="http://multisite.local/checkout?product_id=2"
                                   id="input-checkout-link" type="url">
                            <p>
                                <button id="btn-copy-checkout-link" type="button"
                                        class="button button-primary button-small">
                                    Copy Link
                                </button>
                                <a href="http://multisite.local/checkout?product_id=2" target="_blank"
                                   class="button button-secondary button-small">
                                    Visit Link </a>
                            </p>
                        </div>
                        <!--.Product Link -->


                    </div>
                    <div class="subway-card">
                        <div>
                            <input id="publish-product" type="submit" class="button button-primary button-large"
                                   value="<?php esc_attr_e( 'Update Product', 'subway' ); ?>">
                        </div>
                    </div>

                </div>


            </div>

        </div><!--FlexWrap-->
    </form>


</div>