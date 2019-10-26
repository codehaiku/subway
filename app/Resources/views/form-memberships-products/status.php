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
				<?php esc_html_e( 'Product Link', 'subway' ); ?>
            </label>
        </h3>
        <p class="field-help">
			<?php esc_html_e( 'Use the url below to share this membership product', 'subway' ); ?>
        </p>
        <input readonly="" value="http://multisite.local/checkout?product_id=2"
               id="input-checkout-link" type="url">
        <p>
            <button id="btn-copy-checkout-link" type="button"
                    class="button button-small">
                Copy Link
            </button>
            <a href="http://multisite.local/checkout?product_id=2" target="_blank"
               class="button button-secondary button-small">
                Visit Link </a>
        </p>
    </div>
    <!--.Product Link -->

</div>
