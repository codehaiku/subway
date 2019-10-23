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