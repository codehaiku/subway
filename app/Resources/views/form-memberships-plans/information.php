<!--Plan-->
<div class="subway-form-row" id="product-row">

	<h3 class="field-title">
		<label for="input-title">
			<span class="dashicons dashicons-admin-links"></span>
			<?php esc_html_e( 'Add to Product', 'subway' ); ?>
		</label>
	</h3>

	<p class="field-help">
		<?php esc_html_e( 'Select from the list of available plans.', 'subway' ); ?>
	</p>

	<?php $plans = new \Subway\Memberships\Product\Controller(); ?>
	<?php $result = $plans->fetch_all(); ?>
	<?php $items = $result->products; ?>

	<select disabled name="plan">
		<?php foreach ( $items as $item ): ?>
			<?php if ( $item->get_id() === $plan->get_product_id() ): ?>
				<?php $selected = 'selected'; ?>
			<?php else: ?>
				<?php $selected = ''; ?>
			<?php endif; ?>
			<option <?php echo esc_attr( $selected ); ?>
				value="<?php echo esc_attr( $item->get_id() ); ?>">
				<?php echo esc_html( $item->get_name() ); ?>
			</option>
		<?php endforeach; ?>
	</select>
	<?php if ( isset( $_REQUEST['plan'] ) && ! empty( $_REQUEST['plan'] ) ): ?>
		<p class="field-help" id="add-to-different-plan">
			<a href="#">
				<?php esc_html_e( 'Add to Different Product', 'subway' ); ?>
			</a>
		</p>
	<?php endif; ?>
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
			<?php esc_html_e( 'Stock Keeping Unit (SKU)', 'subway' ); ?>
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
	<textarea id="input-description" name="description" class="widefat" rows="5"
	          placeholder="<?php echo esc_attr( 'Product description', 'subway' ); ?>"><?php echo esc_html( $plan->get_description() ); ?></textarea>
	<?php if ( isset( $errors['description'] ) ): ?>
		<p class="validation-errors">
			<?php echo $errors['description']; ?>
		</p>
	<?php endif; ?>
</div>
<!--/.Plan Description-->

</div>

<?php do_action( 'subway_plan_edit_section' ); ?>