<div id="subway-new-product-form">

	<?php $messages = (array) $flash_message_add->get(); ?>

	<?php $form_data = array( 'title' => '', 'description' => '', 'sku' => '' ); ?>

	<?php if ( isset( $messages['form_data'] ) ): ?>

		<?php $form_data = $messages['form_data']; ?>

		<?php $errors = $messages['validation']; ?>

	<?php endif; ?>

    <form autocomplete="off" method="POST" action="<?php echo admin_url( 'admin-post.php' ); ?>">

        <div>
            <!--hidden fields-->
			<?php wp_nonce_field( 'subway_plan_add_action', 'subway_plan_add_action' ); ?>

            <input type="hidden" name="action" value="subway_plan_add_action"/>

            <input type="hidden" name="page" value="subway-membership"/>

            <input type="hidden" name="new" value="yes"/>

        </div>

        <div class="subway-card subway-product-section active" id="product-information-add">

            <!--Product  -->
            <div class="subway-form-row" id="product-row">

                <?php $this->render('create-field-product', [], false, 'form-memberships-plans'); ?>

            </div>
            <!--/.Product -->

            <!--Plan Name -->
            <div class="subway-form-row">

                <h3 class="field-title">
                    <label for="input-title">
						<?php esc_html_e( 'Membership Plan Name', 'subway' ); ?>
                    </label>
                </h3>

                <p class="field-help">
					<?php esc_html_e( 'Enter the name of your membership product', 'subway' ); ?>
                </p>

                <input autofocus value="<?php echo esc_attr( $form_data['title'] ); ?>" id="input-title" name="title"
                       type="text"
                       class="widefat" placeholder="<?php esc_attr_e( 'Add Name', 'subway' ); ?>">

				<?php if ( isset( $errors['title'] ) ): ?>
                    <p class="validation-errors">
						<?php echo $errors['title']; ?>
                    </p>
				<?php endif; ?>

            </div>

            <!--/.Plan Name -->
            <!-- Plan SKU-->
            <div class="subway-form-row">
                <h3 class="field-title">
                    <label for="input-sku">
						<?php esc_html_e( 'SKU', 'subway' ); ?>
                    </label>
                </h3>
                <p class="field-help">
					<?php esc_html_e( 'Give your membership plan a unique SKU', 'subway' ); ?>
                </p>

                <input value="<?php echo esc_attr( $form_data['sku'] ); ?>" id="input-sku" name="sku" type="text"
                       class="widefat"
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
						<?php esc_html_e( 'Description', 'subway' ); ?>
                    </label>
                </h3>
                <p class="field-help"><?php esc_html_e( 'Explain what this membership plan is all about.' ); ?></p>
                <textarea id="input-description" name="description" class="widefat" rows="5"
                          placeholder="Product description"><?php echo esc_html( $form_data['description'] ); ?></textarea>
				<?php if ( isset( $errors['description'] ) ): ?>
                    <p class="validation-errors">
						<?php echo $errors['description']; ?>
                    </p>
				<?php endif; ?>
            </div>
            <!--/. Plan Description -->

            <div>
                <input id="publish-plan" type="submit" class="button button-primary button-large"
                       value="<?php esc_attr_e( 'Save & Configure', 'subway' ); ?>"/>
            </div>
        </div>


    </form>
</div>
