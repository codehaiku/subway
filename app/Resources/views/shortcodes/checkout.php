<?php if ( $plan ): ?>

	<?php $plan_id = $plan->get_id(); ?>

	<?php $wp_user = wp_get_current_user(); ?>

	<?php $user = new \Subway\User\User() ?>

	<?php $user->set_id( get_current_user_id() ); ?>

	<?php if ( $user->has_plan( $plan_id ) ): ?>
        <div class="subway-alert-danger subway-alert">
            <p>
				<?php esc_html_e( 'You have chosen a membership plan which is the same as your current membership plan. Please select a different membership plan to switch.', 'subway' ); ?>
            </p>
        </div>
        <a class="sw-button" href="<?php echo esc_url( $plan->get_plan_url( false ) ); ?>"
           title="<?php esc_attr_e( 'Select different membership plan' ); ?>">
			<?php esc_attr_e( 'Select Different Membership Plan' ); ?>
        </a>
	<?php else: ?>

        <div id="subway-checkout-wrap">

            <div id="subway-checkout-inner-wrap">

                <form class="sw-form" action="" method="POST">

                    <input type="hidden" name="sw-action" value="checkout"/>

                    <input type="hidden" name="sw-product-id" value="<?php echo absint( $plan_id ); ?>"/>

                    <div class="subway-checkout-user-info">

                        <div class="subway-checkout-user-info-title">

                            <h3>
								<?php esc_html_e( 'Review Order', 'subway' ); ?>
                            </h3>

                        </div>

						<?php if ( is_user_logged_in() ): ?>

                        <div class="subway-checkout-user-info-table-wrap">

							<?php $this->render( 'checkout-personal-information',
								[
									'user'    => $wp_user,
									'options' => $options,
									'plan'    => $plan
								], false, 'shortcodes' ); ?>

                        </div>

                    </div><!--.subway-checkout-user-info-->

                    <div class="subway-checkout-review-order-wrap">

						<?php
						$view->render(
							'checkout-table',
							[
								'plan'     => $plan,
								'currency' => $currency,
								'options'  => $options
							] );
						?>

                    </div><!--.subway-checkout-review-order-->

					<?php else: ?>

                        <div class="subway-alert subway-alert-info">
                            <p>
								<?php esc_html_e( 'Please go to memberships products page, select a membership plan, and register.', 'subway' ); ?>
                                <a href="<?php echo esc_url( $options->get_membership_page_url() ); ?>"
                                   title="<?php esc_attr_e( 'Select Membership Plan', 'subway' ); ?>">
									<?php esc_html_e( 'Select Membership Plan', 'subway' ); ?>
                                </a>
                            </p>
                        </div>

					<?php endif; ?>

                </form>

            </div><!--.subway-checkout-inner-wrap-->

        </div><!--.subway-checkout-wrap-->

        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
	<?php endif; ?>


<?php else: ?>
    <div class="subway-alert subway-alert-info">
        <p>
			<?php esc_html_e( 'Error: Plan not found', 'subway' ); ?>
            <br/>
            <a class="sw-button" href="<?php echo esc_url( $options->get_membership_page_url() ); ?>"
               title="<?php esc_attr_e( 'Select Membership', 'subway' ); ?>">
				<?php esc_html_e( 'Select Membership Plan', 'subway' ); ?>
            </a>
        </p>
    </div>
<?php endif; ?>
