<?php if ( ! defined( 'ABSPATH' ) ) {
	return;
} ?>

<?php $product_id = filter_input( 1, 'product_id', 516 ); ?>

<?php $product = new \Subway\Memberships\Product\Controller(); ?>

<?php if ( ! empty ( $product_id ) ): ?>

	<?php $product->set_id( $product_id ); ?>

	<?php $preselected_product = $product->get(); ?>

<?php endif; ?>

<?php // Show different view for each different instance. ?>

<?php if ( ! empty( $preselected_product ) ): ?>

    <h3 class="field-title">
        <label for="input-product">
			<?php esc_html_e( 'Membership Product:', 'subway' ); ?>
        </label>
    </h3>

    <p class="field-help">
		<?php esc_html_e( 'Product is automatically selected.', 'subway' ); ?>
    </p>

    <select readonly="readonly" name="input-product" id="input-product" disabled>
        <option value="<?php echo esc_attr( $preselected_product->get_id() ); ?>">
			<?php echo esc_html( $preselected_product->get_name() ); ?>
        </option>
    </select>

    <input type="hidden" name="product" value="<?php echo esc_attr( $preselected_product->get_id() ); ?>"/>


<?php else: ?>

    <h3 class="field-title">

        <label for="product">

			<?php esc_html_e( 'Add to Product', 'subway' ); ?>

        </label>

    </h3>

    <p class="field-help">

		<?php esc_html_e( 'Select a membership product.', 'subway' ); ?>

    </p>

	<?php $result = $product->fetch_all(); ?>

	<?php $items = $result->products; ?>

    <select id="product" name="product">

		<?php foreach ( $items as $item ): ?>

			<?php $selected = ''; ?>

			<?php if ( $attached_product ): ?>

				<?php if ( $item->get_id() === $attached_product->get_id() ): ?>

					<?php $selected = 'selected'; ?>

				<?php endif; ?>

			<?php endif; ?>

            <option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $item->get_id() ); ?>">

				<?php echo esc_html( $item->get_name() ); ?>

            </option>

		<?php endforeach; ?>

    </select>

<?php endif; ?>

