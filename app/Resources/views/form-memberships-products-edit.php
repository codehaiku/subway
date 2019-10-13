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

<?php $section = filter_input( 1, 'section', 516 ); ?>

<?php $product = $membership->get_product( $id ); ?>

<?php if ( empty( $product ) ): ?>

	<?php $error = new WP_Error( 'broke', __( "Error: Product not found", "subway" ) ); ?>

    <h3>
		<?php echo $error->get_error_message(); ?>
    </h3>

	<?php return; ?>

<?php endif; ?>

<?php $messages = empty( $flash_messages ) ? [] : end( $flash_messages ); ?>

<?php $form_data = array( 'title' => '', 'description' => '', 'sku' => '', 'type' => '', 'amount' => 0.00 ); ?>

<?php if ( isset( $messages['form_data'] ) ): ?>

	<?php $form_data = $messages['form_data']; ?>

	<?php $errors = $messages['validation']; ?>

<?php endif; ?>

<?php if ( isset( $flash_messages[0]['type'] ) ): ?>

    <div class="notice notice-<?php echo esc_attr( $flash_messages[0]['type'] ); ?> is-dismissible">

        <p>
			<?php echo esc_html( $flash_messages[0]['message'] ); ?>
        </p>

    </div>

<?php endif; ?>

<div id="subway-edit-product-form">

    <form autocomplete="off" method="POST" action="<?php echo admin_url( 'admin-post.php' ); ?>">

        <div class="subway-flex-wrap">

            <div class="subway-flex-column subway-flex-column-70">

                <!--hidden fields-->
				<?php wp_nonce_field( 'subway_product_edit_action', 'subway_product_edit_action' ); ?>

                <input type="hidden" name="action" value="subway_product_edit_action"/>

                <input type="hidden" name="page" value="subway-membership"/>

                <input type="hidden" name="new" value="yes"/>

                <input type="hidden" id="input-id" name="product_id" value="<?php echo esc_attr( $product->get_id() ); ?>"/>

                <!--Product Tabs-->
                <ul id="product-tabs">
                    <li><a class="<?php echo $section == 'product-information' ? 'active' : ''; ?>"
                           data-section-target="product-information" href="#">
                            <span class="dashicons dashicons-info"></span>Product Information</a></li>
                    <li><a class="<?php echo $section == 'product-pricing' ? 'active' : ''; ?>"
                           data-section-target="product-pricing" href="#">
                            <span class="dashicons dashicons-tag"></span>Pricing</a></li>
                    <li><a class="<?php echo $section == 'product-email' ? 'active' : ''; ?>"
                           data-section-target="product-email" href="#">
                            <span class="dashicons dashicons-email"></span>Emails</a></li>
					<?php do_action( 'subway_product_edit_list_tabs' ); ?>
                </ul>
                <!--/.Product Tabs-->

                <input type="hidden" name="active-section"
                       value="<?php echo ! empty( $section ) ? $section : 'product-information' ?>"/>

                <!--Section Email-->
                <div class="subway-card subway-product-section <?php echo $section == 'product-email' ? 'active' : ''; ?>"
                     id="product-email">
                    do_action('subway_products_edit_email_section');
                </div>
                <!--/.Section Email-->

                <!--Section Pricing-->
                <div class="subway-card subway-product-section <?php echo $section == 'product-pricing' ? 'active' : ''; ?>"
                     id="product-pricing">

                    <!-- Payment Type -->
                    <div class="subway-form-row">
                        <h3 class="field-title">
                            <label for="billing-type">
								<?php esc_html_e( 'Payment Type', 'subway' ); ?>
                            </label>
                        </h3>
                        <p class="field-help">
							<?php esc_html_e( 'Update the payment type of this membership plan.', 'subway' ); ?>
                        </p>
						<?php
						$options = [
							'free'      => esc_html__( 'Free', 'subway' ),
							'fixed'     => esc_html__( 'Fixed (One-Time Payment)', 'subway' ),
							'recurring' => esc_html__( 'Recurring (Subscription Payment)', 'subway' ),
						];
						?>

                        <?php if ( isset( $form_data['type'] ) && ! empty( $form_data['type'] ) ): ?>

	                        <?php $product->set_type( $form_data['type'] ); ?>

                        <?php endif; ?>

                        <select name="type" id="billing-type">

							<?php foreach ( $options as $value => $label ): ?>
								<?php if ( $product->get_type() === $value ): ?>
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
                    <div class="subway-form-row" id="billing-amount">
                        <h3 class="field-title">
                            <label>
								<?php esc_html_e( 'Billing Amount', 'subway' ); ?>
                            </label>
                        </h3>
                        <p class="field-help">
							<?php esc_html_e( 'Enter the new price for this Membership Plan', 'subway' ); ?>
                        </p>
                        <div class="field-group">
                            <span class="currency-amount">
                                <?php echo get_option( 'subway_currency', 'USD' ); ?>
                            </span>
                            <input autofocus id="input-amount" name="amount" type="number" style="width: 6em;" size="3"
                                   placeholder="0.00"
                                   step="0.01"
                                   value="<?php echo esc_attr( $product->get_real_amount() ); ?>"/>

                        </div>
	                    <?php if ( isset( $errors['amount'] ) ): ?>
                            <p class="validation-errors">
			                    <?php echo $errors['amount']; ?>
                            </p>
	                    <?php endif; ?>
                    </div>

                    <!--/.Product Price-->

                    <!--Billing Cycle-->
                    <div class="subway-form-row" id="billing-cycle">
                        <h3 class="field-title">
                            <label>
								<?php esc_html_e( 'Billing Cycle', 'subway' ); ?>
                            </label>
                        </h3>
                        <p class="field-help">
							<?php esc_html_e( 'Select the billing cycle for this plan. The customer will be billed on every selected cycle.', 'subway' ); ?>
                        </p>
                        <div class="field-bundle subway-flex-wrap">
                            <div class="subway-flex-column">
                                <select id="billing-cycle-number" name="billing-cycle-number">
									<?php for ( $i = 1; $i <= 30; $i ++ ) { ?>
                                        <option><?php echo esc_html( $i ); ?></option>
									<?php } ?>
                                </select>
                            </div>
                            <div class="subway-flex-column">
                                <select id="billing-cycle-period" name="billing-cycle-period">
                                    <option><?php esc_html_e( 'Day(s)' ); ?></option>
                                    <option><?php esc_html_e( 'Week(s)' ); ?></option>
                                    <option><?php esc_html_e( 'Month(s)' ); ?></option>
                                    <option><?php esc_html_e( 'Year(s)' ); ?></option>
                                </select>
                            </div>


                        </div>
                    </div>
                    <!--/.Billing Cycle-->

                    <!--Billing Limit-->
                    <div class="subway-form-row" id="billing-limit">
                        <h3 class="field-title">
                            <label>
								<?php esc_html_e( 'Billing Limit', 'subway' ); ?>
                            </label>
                        </h3>
                        <p class="field-help">
							<?php esc_html_e( 'Select how many cycles until the billing should stop.', 'subway' ); ?>
                        </p>

                        <select name="billing-cycle-number">
                            <option><?php esc_html_e( 'Never', 'subway' ); ?></option>
							<?php for ( $i = 2; $i <= 31; $i ++ ) { ?>
                                <option><?php echo esc_html( $i ); ?></option>
							<?php } ?>
                        </select>


                    </div>
                    <!--/.Billing Limit-->

                    <!--Trial Period-->
                    <div class="subway-form-row" id="free-trial">
                        <h3 class="field-title">
                            <label for="input-free-trial-checkbox">
                                <input type="checkbox" name="free-trial" id="input-free-trial-checkbox"/>
								<?php esc_html_e( 'Offer Trial Period', 'subway' ); ?>

                            </label>
                        </h3>
                        <div id="trial-period-details">
                            <p class="field-help">
								<?php esc_html_e( 'Define the trial period', 'subway' ); ?>
                            </p>
                            <div class="field-bundle subway-flex-wrap">
                                <div class="subway-flex-column">
                                    <select id="trial-billing-cycle-number" name="trial-billing-cycle-number">
										<?php for ( $i = 1; $i <= 52; $i ++ ) { ?>
                                            <option><?php echo esc_html( $i ); ?></option>
										<?php } ?>
                                    </select>
                                </div>
                                <div class="subway-flex-column">
                                    <select id="trial-billing-cycle-period" name="trial-billing-cycle-period">
                                        <option><?php esc_html_e( 'Day(s)' ); ?></option>
                                        <option><?php esc_html_e( 'Week(s)' ); ?></option>
                                        <option><?php esc_html_e( 'Month(s)' ); ?></option>
                                        <option><?php esc_html_e( 'Year(s)' ); ?></option>
                                    </select>
                                </div>
                            </div>
                            <p class="field-help">
								<?php esc_html_e( 'Amount to bill for the trial period. Enter 0.00 for free trials.', 'subway' ); ?>
                            </p>
                            <div class="field-group">
                                <span class="currency-amount">
                                    <?php echo get_option( 'subway_currency', 'USD' ); ?>
                                </span>

                                <input autofocus="" id="input-trial-amount" name="trial_amount" type="number" style="width: 6em;"
                                       size="3" placeholder="0.00" step="0.01" value="10">
                            </div>
                        </div><!--#trial-period-details-->
                    </div>

                    <!--/.Trial Period-->

                </div>
                <!--/.Section Email-->

                <!--Section Product Information-->
                <div class="subway-card subway-product-section <?php echo $section == 'product-information' ? 'active' : ''; ?>"
                     id="product-information">

                    <!--Product Name-->
                    <div class="subway-form-row">

                        <h3 class="field-title">
                            <label for="input-title">
								<?php esc_html_e( 'Membership Plan Name', 'subway' ); ?>
                            </label>
                        </h3>

                        <p class="field-help"><?php esc_html_e( 'Enter the new name of this plan.', 'subway' ); ?></p>

                        <input autofocus value="<?php echo esc_attr( $product->get_name() ); ?>"
                               id="input-title" name="title" type="text" class="widefat"
                               placeholder="<?php esc_attr_e( 'Add Name', 'subway' ); ?>"/>

						<?php if ( isset( $errors['title'] ) ): ?>
                            <p class="validation-errors">
								<?php echo $errors['title']; ?>
                            </p>
						<?php endif; ?>
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

                        <input autofocus value="<?php echo esc_attr( $product->get_sku() ); ?>" id="input-sku" name="sku"
                               type="text" class="widefat"
                               placeholder="<?php esc_attr_e( '(Stock Keeping Unit e.g. PROD001)', 'subway' ); ?>"/>
						<?php if ( isset( $errors['sku'] ) ): ?>
                            <p class="validation-errors">
								<?php echo $errors['sku']; ?>
                            </p>
						<?php endif; ?>
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
                        <textarea id="input-description" name="description" class="widefat" rows="5"
                                  placeholder="<?php echo esc_attr( 'Product description', 'subway' ); ?>"><?php echo esc_html( $product->get_description() ); ?></textarea>
						<?php if ( isset( $errors['description'] ) ): ?>
                            <p class="validation-errors">
								<?php echo $errors['description']; ?>
                            </p>
						<?php endif; ?>
                    </div>
                    <!--/.Product Description-->

                </div>

				<?php do_action( 'subway_product_edit_section' ); ?>

            </div>
            <!--/.Section Product Information-->

            <!--Product Status-->
            <div class="subway-flex-column subway-flex-column-30">
                <div class="subway-flex-inner-wrap" style="margin-top:3.3em">
                    <div class="subway-card">
                        <!--Product Status-->
                        <div class="subway-form-row">
                            <h3 class="field-title">
                                <label for="input-description">
									<?php esc_html_e( 'Membership Plan Status', 'subway' ); ?>
                                </label>
                            </h3>
                            <p class="field-help">
								<?php esc_html_e( 'Update the status of this membership plan. 
								Change the value of this field to \'Published\' to make this membership available to everyone.', 'subway' ); ?>
                            </p>
							<?php
							$statuses = [
								'draft'     => esc_html__( 'Draft', 'subway' ),
								'published' => esc_html__( 'Published', 'subway' ),
							];
							?>

                            <select name="status">
								<?php foreach ( $statuses as $key => $val ): ?>
									<?php $selected = ''; ?>
									<?php if ( $key === $product->get_status() ): ?>
										<?php $selected = 'selected'; ?>
									<?php endif; ?>
                                    <option <?php echo esc_attr( $selected ); ?>
                                            value="<?php echo esc_attr( $key ); ?>">
										<?php echo esc_html( $val ); ?>
                                    </option>
								<?php endforeach; ?>
                            </select>
                        </div>
                        <!--/.Product Status-->
                        <!--Product Link -->
                        <div class="subway-form-row" id="checkout-link-section">
                            <h3 class="field-title">
                                <label for="input-checkout-link">
									<?php esc_html_e( 'Copy Checkout URL', 'subway' ); ?>
                                </label>
                            </h3>
                            <p class="field-help">
								<?php esc_html_e( 'Use the url below to share this membership plan anywhere.', 'subway' ); ?>
                            </p>
							<?php $checkout_url = $membership->get_product_checkout_url( $id ); ?>
                            <input readonly value="<?php echo esc_url( $checkout_url ); ?>" id="input-checkout-link"
                                   type="url" value="input-checkout-link"/>
                            <p>
                                <button id="btn-copy-checkout-link" type="button"
                                        class="button button-primary button-small">
									<?php esc_html_e( 'Copy Link', 'subway' ); ?>
                                </button>
                                <a href="<?php echo esc_url( $checkout_url ); ?>" target="_blank"
                                   class="button button-secondary button-small" href="">
									<?php esc_html_e( 'Visit Link', 'subway' ); ?>
                                </a>
                            </p>
                        </div>
                        <!--.Product Link -->


                    </div>
                </div>
            </div>
            <!--/.Product Status-->
        </div>


        <hr/>
        <!--Submit Button-->
        <div class="subway-card">
            <input id="update-product" type="submit" class="button button-primary button-large"
                   value="<?php esc_attr_e( 'Update Membership Plan', 'subway' ); ?>"/>
        </div>
        <!--/.Submit Button-->
    </form>
</div><!--#subway-edit-product-form-->


