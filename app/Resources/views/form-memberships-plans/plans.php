<div class="subway-flex-inner-wrap" style="margin-top:4.7em">
    <div class="subway-card">
        <!--Plan Status-->
        <div class="subway-form-row">
            <h3 class="field-title">
                <label for="input-description">
					<?php esc_html_e( 'Memberships Plan Status', 'subway' ); ?>
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
					<?php if ( $key === $plan->get_status() ): ?>
						<?php $selected = 'selected'; ?>
					<?php endif; ?>
                    <option <?php echo esc_attr( $selected ); ?>
                            value="<?php echo esc_attr( $key ); ?>">
						<?php echo esc_html( $val ); ?>
                    </option>
				<?php endforeach; ?>
            </select>
        </div>
        <!--/.Plan Status-->
        <!--Plan Link -->
        <div class="subway-form-row" id="checkout-link-section">
            <h3 class="field-title">
                <label for="input-checkout-link">
					<?php esc_html_e( 'Copy Checkout URL', 'subway' ); ?>
                </label>
            </h3>
            <p class="field-help">
				<?php esc_html_e( 'Use the url below to share this membership plan anywhere.', 'subway' ); ?>
            </p>
			<?php $checkout_url = $membership->get_plan_checkout_url( $id ); ?>
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
        <!--.Plan Link -->


    </div>
</div>