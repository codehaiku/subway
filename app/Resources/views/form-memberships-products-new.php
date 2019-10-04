<div id="subway-new-product-form">

	<?php wp_enqueue_script( 'subway-membership-add-js' ); ?>


    <form autocomplete="off" method="POST" action="<?php echo esc_url( rest_url( 'subway/v1/membership/new-product' ) ); ?>">

        <div>
            <input type="hidden" name="page" value="subway-membership">
            <input type="hidden" name="new" value="yes">
        </div>

        <!--Product Name -->
        <div>
            <h3>
                <label for="input-title">
                    <?php esc_html_e('Product Name', 'subway'); ?>
                </label>
            </h3>
            <p>
                <?php esc_html_e('Enter the name of your membership product', 'subway'); ?>
            </p>
            <input id="input-title" name="title" type="text" class="widefat" placeholder="Add Name">
        </div>
        <!--/.Product Name -->

        <!-- Product SKU-->
        <div>
            <h3>
                <label for="input-sku">
				    <?php esc_html_e( 'Product SKU', 'subway' ); ?>
                </label>
            </h3>
            <p>
                <?php esc_html_e('Give your membership product a unique SKU', 'subway'); ?>
            </p>

            <input value="" id="input-sku" name="sku" type="text" class="widefat"
                   placeholder="<?php esc_attr_e('(Stock Keeping Unit e.g. PROD001)', 'subway'); ?>" />
        </div>
        <!--/.Product SKU-->

        <!-- Product Description -->
        <div>
            <h3>
                <label for="input-description">
                    <?php esc_html_e('Product Description', 'subway'); ?>
                </label>
            </h3>
            <p><?php esc_html_e('Explain what this membership product is all about.'); ?></p>
            <textarea id="input-description" name="description" class="widefat" rows="5"
                      placeholder="Product description"></textarea>
        </div>
        <!--/. Product Description -->


        <!-- Payment Type -->
        <div>
            <h3><label for="input-type"><?php esc_html_e('Payment Type', 'subway'); ?></label></h3>
            <p>
                <?php esc_html_e("Select a payment type. Select 'Free' for free membership, 
                'Fixed' for one-time charge. Select 'Recurring' for subscription payment", 'subway'); ?>
            </p>
            <select name="type" id="input-type">
                <option value="free">Free</option>
                <option value="fixed">Fixed</option>
                <option value="recurring">Recurring</option>
            </select>
        </div>
        <!--/. Payment Type -->

        <!-- Price -->
        <div>
            <h3><label>Price</label></h3>
            <p>
                <?php esc_html_e('Give your new membership product a good price.', 'subway');?>
            </p>
            <input id="input-amount" name="amount" type="number" style="width: 6em;" size="3" placeholder="0.00"/>
        </div>
        <!--/. Price -->

        <hr/>

        <div>
            <input id="publish-product" type="submit" class="button button-primary button-large"
                   value="Publish Product"/>
        </div>

    </form>
</div>
