<?php
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

<?php $id = filter_input( INPUT_GET, 'product', FILTER_VALIDATE_INT ); ?>
<?php $section = filter_input(1, 'section', 516); ?>


<?php foreach( $flash_messages as $flash_message ): ?>
    <div class="notice notice-<?php echo esc_attr( $flash_message['type'] ); ?> is-dismissible">
        <p>
		    <?php echo esc_html( $flash_message['message'] ); ?>
        </p>
    </div>
<?php endforeach; ?>

<?php $product = $membership->get_product( $id ); ?>

<?php if ( empty( $product ) ): ?>

	<?php $error = new WP_Error( 'broke', __( "Error: Product not found", "subway" ) ); ?>

    <h3>
		<?php echo $error->get_error_message(); ?>
    </h3>

	<?php return; ?>

<?php endif; ?>

<div id="subway-edit-product-form">

    <form autocomplete="off" method="POST" action="<?php echo admin_url( 'admin-post.php' ); ?>">

        <ul id="product-tabs">
            <li><a class="<?php echo $section == 'product-information'? 'active': ''; ?>"data-section-target="product-information" href="#">
                    <span class="dashicons dashicons-info"></span>Product Information</a></li>
            <li><a class="<?php echo $section == 'product-pricing'? 'active': ''; ?>"data-section-target="product-pricing" href="#">
                    <span class="dashicons dashicons-tag"></span>Pricing</a></li>
            <li><a class="<?php echo $section == 'product-email'? 'active': ''; ?>" data-section-target="product-email" href="#">
                    <span class="dashicons dashicons-email"></span>Emails</a></li>
            <?php do_action('subway_product_edit_list_tabs'); ?>
        </ul>

        <input type="hidden" name="active-section" value="<?php echo ! empty( $section ) ? $section: 'product-information'?>" />

        <div class="subway-card subway-product-section <?php echo $section == 'product-email' ? 'active': ''; ?>" id="product-email">
            Emails
        </div>

        <div class="subway-card subway-product-section <?php echo $section == 'product-pricing' ? 'active': ''; ?>" id="product-pricing">
            <!-- Payment Type -->
            <div class="subway-form-row">
                <h3 class="field-title">
                    <label for="input-type">
				        <?php esc_html_e('Payment Type', 'subway'); ?>
                    </label>
                </h3>
                <p class="field-help">
			        <?php esc_html_e('Update the payment type of this membership plan.', 'subway'); ?>
                </p>
		        <?php
		        $options = [
			        'free'      => esc_html__( 'Free', 'subway' ),
			        'fixed'     => esc_html__( 'Fixed (One-Time Payment)', 'subway' ),
			        'recurring' => esc_html__( 'Recurring (Subscription Payment)', 'subway' ),
		        ];
		        ?>
                <select autofocus  name="type" id="input-type">
			        <?php foreach ( $options as $value => $label ): ?>
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
            <!--/.Payment Type-->

            <!--Product Price-->
            <div class="subway-form-row">
                <h3 class="field-title">
                    <label>
				        <?php esc_html_e('Price', 'subway'); ?>
                    </label>
                </h3>
                <p class="field-help">
			        <?php esc_html_e('Enter the new price for this Membership Plan', 'subway'); ?>
                </p>
                <div class="field-group">
                    <span class="currency-amount">
                    <?php echo get_option('subway_currency', 'USD'); ?>
                </span>
                    <input  id="input-amount" name="amount" type="number" style="width: 6em;" size="3"
                           placeholder="0.00"
                           step="0.01"
                           value="<?php echo esc_attr( $product->amount ); ?>"/>
                </div>

            </div>
            <!--/.Product Price-->
        </div>

        <div class="subway-card subway-product-section <?php echo $section == 'product-information'  ? 'active': ''; ?>" id="product-information">

            <!--hidden fields-->
			<?php wp_nonce_field( 'subway_product_edit_action', 'subway_product_edit_action' ); ?>
            <input type="hidden" name="action" value="subway_product_edit_action"/>
            <input type="hidden" name="page" value="subway-membership"/>
            <input type="hidden" name="new" value="yes"/>
            <input type="hidden" id="input-id" name="product_id" value="<?php echo esc_attr( $product->id ); ?>"/>

            <!--Product Name-->
            <div class="subway-form-row">

                <h3 class="field-title">
                    <label for="input-title">
						<?php esc_html_e( 'Membership Plan Name', 'subway' ); ?>
                    </label>
                </h3>

                <p class="field-help"><?php esc_html_e( 'Enter the new name of this plan.', 'subway' ); ?></p>

                <input autofocus  value="<?php echo esc_attr( $product->name ); ?>"
                       id="input-title" name="title" type="text" class="widefat"
                       placeholder="<?php esc_attr_e( 'Add Name', 'subway' ); ?>"/>
            </div>
            <!--/Product Name-->

            <!-- Product SKU-->
            <div class="subway-form-row">
                <h3 class="field-title">
                    <label for="input-sku">
						<?php esc_html_e( 'Stock Keeping Unit (SKU)', 'subway' ); ?>
                    </label>
                </h3>
                <p class="field-help">
					<?php esc_html_e( 'Give this membership plan a new and a unique SKU.', 'subway' ); ?>
                </p>

                <input autofocus value="<?php echo esc_attr( $product->sku ); ?>" id="input-sku" name="sku"
                       
                       type="text"
                       class="widefat"
                       placeholder="<?php esc_attr_e( '(Stock Keeping Unit e.g. PROD001)', 'subway' ); ?>"/>
            </div>
            <!--/.Product SKU-->

            <!-- Product Description -->
            <div class="subway-form-row">
                <h3 class="field-title">
                    <label for="input-description">
				        <?php esc_html_e( 'Product Description', 'subway' ); ?>
                    </label>
                </h3>
                <p class="field-help">
			        <?php esc_html_e( 'Update this membership plan description.', 'subway' ); ?>
                </p>
                <textarea  id="input-description" name="description" class="widefat" rows="5"
                          placeholder="<?php echo esc_attr( 'Product description', 'subway' ); ?>"><?php echo esc_html( $product->description ); ?></textarea>
            </div>
            <!--/.Product Description-->

            <!--Product Status-->
            <div class="subway-form-row">
                <h3 class="field-title">
                    <label for="input-description">
				        <?php esc_html_e( 'Product Status', 'subway' ); ?>
                    </label>
                </h3>
                <p class="field-help">
			        <?php esc_html_e( 'Update the status of this membership plan. Change the value of this field to \'Published\' to make this
                membership available to everyone.', 'subway' ); ?>
                </p>
		        <?php
		        $statuses = [
			        'draft'     => esc_html__( 'Draft', 'subway' ),
			        'published' => esc_html__( 'Published', 'subway' ),
		        ];
		        ?>

                <select  name="status">
			        <?php foreach ( $statuses as $key => $val ): ?>
				        <?php $selected = ''; ?>
				        <?php if ( $key === $product->status ): ?>
					        <?php $selected = 'selected'; ?>
				        <?php endif; ?>
                        <option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $key ); ?>">
					        <?php echo esc_html( $val ); ?>
                        </option>
			        <?php endforeach; ?>
                </select>
            </div>
            <!--/.Product Status-->

        </div>

	    <?php do_action('subway_product_edit_section'); ?>

        <hr/>

        <div class="subway-card">
            <input  id="update-product" type="submit" class="button button-primary button-large"
                   value="<?php esc_attr_e('Update Membership Plan', 'subway'); ?>"/>
        </div>

    </form>
</div><!--#subway-edit-product-form-->
