<div class="wrap">
	<?php
	$product_id = filter_input( 1, 'id', 516 );
	$product    = new \Subway\Memberships\Product\Product();
	$product->set_id( $product_id );
	$product->get();
	?>
    <h1 class="wp-heading-inline">
		<?php echo sprintf( esc_html__( 'Configure %s', 'subway' ), $product->get_name() ); ?>
    </h1>

    <hr class="wp-header-end">

    <div id="subway-new-product-form">

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

        <form autocomplete="off" method="POST" action="http://multisite.local/wp-admin/admin-post.php">

            <div>
                <!--hidden fields-->
            </div>

            <div class="subway-card subway-product-section active" id="product-information-add">

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
                           name="title"
                           type="text" class="widefat"
                           placeholder="<?php esc_attr_e( 'New product name', 'subway' ); ?>"/>
                </div>
                <!--/.Product Name -->

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
                    <input step="0.01" value="<?php echo esc_attr( $product->get_tax_rate() ); ?>" id="input-tax-rate"
                           name="tax_rate" type="number" class="widefat"
                           placeholder="<?php esc_attr_e( 'Enter 12 for 12%. Enter 0 or 0.00 to disable.', 'subway' ); ?>"/>
                </div>
                <!--/.Tax Rate -->

                <!--Tax Rate Display-->
                <div class="subway-form-row">
                    <h3 class="field-title">
                        <label for="input-tax-rate-display-head">
							<?php esc_html_e( 'Display Tax', 'subway' ); ?>
                        </label>
                    </h3>
                    <p>
                        <label for="input-tax-rate-display">
                            <input <?php checked( true, $product->is_tax_displayed(), true ); ?>
                                    type="checkbox" name="input-tax-rate-display" id="input-tax-rate-display"/>
							<?php esc_html_e( 'Check to include tax in membership plans pricing.', 'subway' ); ?>
                        </label>
                    </p>
                </div>
                <!--/.Tax Rate -->

                <!--Prorating-->
                <div class="subway-form-row">
                    <h3 class="field-title">
                        <label for="input-prorated">
				            <?php esc_html_e( 'Prorated Charges', 'subway' ); ?>
                        </label>
                    </h3>
                    <p>
                        <label for="input-tax-rate-display">
                            <input checked type="checkbox" name="input-tax-rate-display" id="input-tax-rate-display"/>
				            <?php esc_html_e( 'Check to automatically prorate charges when member switch to any plan of this product.', 'subway' ); ?>
                        </label>
                    </p>
                </div>
                <!--/.Tax Rate -->

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
                              placeholder="<?php esc_attr_e( 'New product description', 'subway' ); ?>"></textarea>
                </div>
                <!--/. Product Description -->

                <div>
                    <input id="publish-product" type="submit" class="button button-primary button-large"
                           value="<?php esc_attr_e( 'Update Product', 'subway' ); ?>">
                </div>
            </div>


        </form>
    </div>

</div>