<?php
/**
 * Copyright (c) 2019. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 * Morbi non lorem porttitor neque feugiat blandit. Ut vitae ipsum eget quam lacinia accumsan.
 * Etiam sed turpis ac ipsum condimentum fringilla. Maecenas magna.
 * Proin dapibus sapien vel ante. Aliquam erat volutpat. Pellentesque sagittis ligula eget metus.
 * Vestibulum commodo. Ut rhoncus gravida arcu.
 */

/**
 * This file contains the html for the product edit when
 * you click 'edit' in the membership list table.
 *
 * @since  3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}
?>

<?php $id = filter_input( INPUT_GET, 'product', FILTER_VALIDATE_INT); ?>

<?php $product = $membership->get_product( $id ); ?>

<?php if ( empty( $product ) ): ?>

	<?php $error = new WP_Error( 'broke', __( "Error: Product not found", "subway" ) ); ?>

	<h3>

		<?php echo $error->get_error_message(); ?>

	</h3>

	<?php return; ?>

<?php endif; ?>

<div id="subway-edit-product-form">

	<?php wp_enqueue_script( 'subway-membership-update-js' ); ?>

	<form autocomplete="off" method="POST" action="">

		<div>
			<input type="hidden" name="page" value="subway-membership">
			<input type="hidden" name="new" value="yes">
			<input type="hidden" id="input-id" name="product_id" value="<?php echo esc_attr( $product->id ); ?>">
		</div>

		<div>

			<h3>
				<label for="input-title">
                    <?php esc_html_e( 'Product Name', 'subway' ); ?>
                </label>
			</h3>

			<p><?php esc_html_e('Enter the new product name', 'subway'); ?></p>

			<input value="<?php echo esc_attr( $product->name ); ?>"
			       id="input-title" name="title" type="text" class="widefat"
			       placeholder="<?php esc_attr_e('Add Name', 'subway'); ?>" />
		</div>

        <!-- Product SKU-->
        <div>
            <h3>
                <label for="input-sku">
					<?php esc_html_e( 'Product SKU', 'subway' ); ?>
                </label>
            </h3>
            <p>
				<?php esc_html_e('Give your membership product a new and unique SKU', 'subway'); ?>
            </p>

            <input value="<?php echo esc_attr( $product->sku ); ?>" id="input-sku" name="sku" type="text" class="widefat"
                   placeholder="<?php esc_attr_e('(Stock Keeping Unit e.g. PROD001)', 'subway'); ?>" />
        </div>
        <!--/.Product SKU-->

		<div>
			<h3>
                <label for="input-description">
                    <?php esc_html_e('Product Description', 'subway'); ?>
                </label>
            </h3>
			<p>
                <?php esc_html_e('Enter the product description', 'subway'); ?>
            </p>
			<textarea id="input-description" name="description" class="widefat" rows="5" placeholder="<?php echo esc_attr('Product description', 'subway'); ?>"><?php echo esc_html( $product->description ); ?></textarea>
		</div>

        <div>
            <h3>
                <label>Payment Type</label>
            </h3>
            <p>Select a payment type</p>

            <?php
            $options = [
                    'free' => esc_html__('Free', 'subway'),
                    'fixed' => esc_html__('Fixed', 'subway'),
                    'recurring' => esc_html__('Recurring', 'subway'),
                ];
            ?>
            <select name="type" id="input-type">
                <?php foreach( $options as $value => $label ): ?>
                    <?php if ( $product->type === $value ): ?>
                        <?php $selected = 'selected'; ?>
                    <?php else: ?>
                        <?php $selected = ''; ?>
                    <?php endif; ?>
                    <option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $value ); ?>">
                        <?php echo esc_html( $label ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <h3><label>Price</label></h3>
            <p>Enter the price of this product</p>
            <input id="input-amount" name="amount" type="number" style="width: 6em;" size="3" placeholder="0.00" value="<?php echo esc_attr( $product->amount); ?>"/>
        </div>

		<hr/>

		<div>
			<input id="update-product" type="submit" class="button button-primary button-large" value="Update" />
		</div>

	</form>
</div><!--#subway-edit-product-form-->
