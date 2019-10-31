<?php if ( 'free' !== $plan->get_type() ): ?>
    <!-- Gateway Badge-->
    <div class="checkout-container">
        <div class="subway-checkout-gateway-banner">
            <p><br/>
				<?php esc_html_e( 'Payments will be fulfilled through PayPal website for a secure checkout experience.', 'subway' ); ?>
            </p>
            <p>
                <img class="gateway-img" width="200"
                     src="https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg"/>
            </p>
        </div><!--.subway-checkout-gateway-banner-->

        <div class="subway-checkout-place-order">
            <button type="submit" class="sw-button subway-checkout-place-order-button">
				<?php esc_html_e( 'Place Order', 'subway' ); ?>
            </button>
        </div><!--.subway-checkout-place-order-->
    </div>
    <!-- Gateway Badge End-->
<?php else: ?>
	<?php if ( is_user_logged_in() ): ?>
        <div class="subway-alert subway-alert-info">
            <p>
				<?php esc_html_e( 'Cannot switch to a free membership. You have already an active membership plan.', 'subway' ); ?>
            </p>
        </div>
	<?php else: ?>
        <!-- Gateway Badge-->
        <div class="checkout-container">
            <div class="subway-checkout-place-order">
                <p>
					<?php esc_html_e( 'This membership plan is free.', 'subway' ); ?><br/>
					<?php esc_html_e( ' Click the button below to proceed.', 'subway' ); ?>
                </p>
                <button type="submit" class="sw-button subway-checkout-place-order-button">
					<?php esc_html_e( 'Free Memberships', 'subway' ); ?>
                </button>
            </div><!--.subway-checkout-place-order-->
        </div>
        <!-- Gateway Badge End-->
	<?php endif; ?>
<?php endif; ?>