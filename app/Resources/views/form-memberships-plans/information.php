<?php $product = new \Subway\Memberships\Product\Controller(); ?>

<?php $product->set_id( $plan->get_product_id() ); ?>

<?php $attached_product = $product->get(); ?>

<?php $items = $product->fetch_all()->products; ?>

    <!--Plan-->
    <div class="subway-form-row" id="product-row">

        <h3 class="field-title">

            <label for="plan-product">

                <span class="dashicons dashicons-admin-links"></span>

				<?php if ( ! empty( $attached_product ) ): ?>

					<?php esc_html_e( 'Attached to Product:', 'subway' ); ?>

				<?php else: ?>

					<?php esc_html_e( 'Add to Product', 'subway' ); ?>

				<?php endif; ?>

            </label>

        </h3>

        <p class="field-help">

			<?php if ( ! empty( $attached_product ) && ! empty( $_REQUEST['product'] ) ): ?>

				<?php echo sprintf( esc_html__( 'This membership plan is under %s product:', 'subway' ), $product->get_name() ); ?>

			<?php else: ?>

				<?php esc_html_e( 'Select the product where this plan belongs:', 'subway' ); ?>

			<?php endif; ?>

        </p>

		<?php $disabled = ! empty( $_REQUEST['product'] ) ? 'disabled' : ''; ?>

        <select <?php echo esc_attr( $disabled ); ?> id="plan-product" name="product">

			<?php foreach ( $items as $item ): ?>

				<?php if ( $item->get_id() === $plan->get_product_id() ): ?>

					<?php $selected = 'selected'; ?>

				<?php else: ?>

					<?php $selected = ''; ?>

				<?php endif; ?>

                <option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $item->get_id() ); ?>">

					<?php echo esc_html( $item->get_name() ); ?>

                </option>

			<?php endforeach; ?>

        </select>



		<?php if ( ! empty( $attached_product ) && ! empty( $_REQUEST['product'] ) ): ?>

            <input type="hidden" name="product" value="<?php echo esc_attr( $_REQUEST['product'] ); ?>"/>

            <p id="add-to-different-plan">

                <a href="<?php echo esc_url( $plan->get_edit_url( $plan_id ) ); ?>"
                   title="<?php esc_attr_e( 'Add to Different Product', 'subway' ); ?>">
					<?php esc_html_e( 'Add to Different Product', 'subway' ); ?>
                </a>

            </p>

		<?php endif; ?>

        <p id="view-product">
            <a class="button button-small" href="<?php echo esc_url( $attached_product->get_product_url_edit() ); ?>">
			    <?php echo sprintf( esc_html__( 'Edit %s', 'subway' ), $attached_product->get_name() ); ?>
            </a>
        </p>

        <div class="clear"></div>

    </div>
    <!--Plan End-->

    <!--Plan Name-->
    <div class="subway-form-row">

        <h3 class="field-title">
            <label for="input-title">
				<?php esc_html_e( 'Memberships Plan Name', 'subway' ); ?>
            </label>
        </h3>

        <p class="field-help"><?php esc_html_e( 'Enter the new name of this plan.', 'subway' ); ?></p>

        <input autofocus value="<?php echo esc_attr( $plan->get_name() ); ?>"
               id="input-title" name="title" type="text" class="widefat"
               placeholder="<?php esc_attr_e( 'Add Name', 'subway' ); ?>"/>

		<?php if ( isset( $errors['title'] ) ): ?>
            <p class="validation-errors">
				<?php echo $errors['title']; ?>
            </p>
		<?php endif; ?>
    </div>
    <!--/Plan Name-->

    <!-- Plan SKU-->
    <div class="subway-form-row">
        <h3 class="field-title">
            <label for="input-sku">
				<?php esc_html_e( 'SKU', 'subway' ); ?>
            </label>
        </h3>
        <p class="field-help">
			<?php esc_html_e( 'Give this membership plan a new and a unique SKU.', 'subway' ); ?>
        </p>

        <input autofocus value="<?php echo esc_attr( $plan->get_sku() ); ?>" id="input-sku"
               name="sku"
               type="text" class="widefat"
               placeholder="<?php esc_attr_e( '(Stock Keeping Unit e.g. PROD001)', 'subway' ); ?>"/>
		<?php if ( isset( $errors['sku'] ) ): ?>
            <p class="validation-errors">
				<?php echo $errors['sku']; ?>
            </p>
		<?php endif; ?>
    </div>
    <!--/.Plan SKU-->

    <!-- Plan Description -->
    <div class="subway-form-row">

        <h3 class="field-title">

            <label for="input-description">

				<?php esc_html_e( 'Product Description', 'subway' ); ?>

            </label>

        </h3>

        <p class="field-help">

			<?php esc_html_e( 'Update this membership plan description.', 'subway' ); ?>

        </p>

	    <?php wp_editor( wp_kses_post( $plan->get_description() ), 'description', $settings = array('editor_height'=>150) ); ?>

		<?php if ( isset( $errors['description'] ) ): ?>

            <p class="validation-errors">

				<?php echo esc_html( $errors['description'] ); ?>

            </p>

		<?php endif; ?>
    </div>
    <!--/.Plan Description-->


<?php do_action( 'subway_plan_edit_section' ); ?>