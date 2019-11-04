<?php if ( $plan ): ?>

	<?php $user = new \Subway\User\User() ?>

	<?php $plan_id = $plan->get_id(); ?>

	<?php $wp_user = wp_get_current_user(); ?>

	<?php $user->set_id( get_current_user_id() ); ?>

	<?php if ( $user->has_plan( $plan_id ) ): ?>

        <div class="subway-alert-success subway-alert">
            <p>
                <?php esc_html_e( 'You are already subscribed into this membership plan.', 'subway' ); ?>
            </p>
        </div>

        <a class="sw-button" href="<?php echo esc_url( $plan->get_plan_url( false ) ); ?>"
           title="<?php esc_attr_e( 'Select different membership plan' ); ?>">
			<?php esc_attr_e( 'Select Different Membership Plan' ); ?>
        </a>

	<?php else: ?>

        <form class="sw-form" action="" method="POST">

            <div id="subway-checkout-wrap">

                <div id="subway-checkout-inner-wrap">

                    <div class="subway-checkout-user-info-title">

                        <h3>
							<?php esc_html_e( 'Review Order', 'subway' ); ?>
                        </h3>

                    </div>

                    <div class="subway-flex-wrap">
                        <div class="subway-flex-column-100">
                            <div id="subway-checkout-order-review">
								<?php
								$view->render(
									'checkout-order',
									[
										'plan'     => $plan,
										'currency' => $currency,
										'options'  => $options
									] );
								?>
                            </div>
                        </div>
                    </div>
                    <div class="subway-flex-wrap">
                        <!-- Personal Info -->
                        <div class="subway-flex-column-60">

                            <div id="subway-checkout-user-info">

								<?php if ( is_user_logged_in() ): ?>

                                    <div class="subway-checkout-user-info">

                                        <div class="subway-checkout-user-info-table-wrap">

											<?php $this->render( 'checkout-personal-information',
												[
													'user'    => $wp_user,
													'options' => $options,
													'plan'    => $plan
												], false, 'shortcodes' ); ?>

                                        </div>

                                    </div><!--.subway-checkout-user-info-->

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

                            </div><!--#subway-checkout-user-info-->

                        </div>
                        <!-- Personal Info End -->

                        <!-- Payment Gateway -->
                        <div class="subway-flex-column-40">
                            <div id="subway-checkout-gateway" class="subway-checkout-review-order-wrap">
								<?php
								$view->render(
									'checkout-gateway',
									[
										'plan'     => $plan,
										'currency' => $currency,
										'options'  => $options
									] );
								?>
                            </div><!--.subway-checkout-review-order-->
                        </div>
                        <!-- Payment Gateway End -->

                    </div>


                    <input type="hidden" name="sw-action" value="checkout"/>

                    <input type="hidden" name="sw-plan-id" value="<?php echo absint( $plan_id ); ?>"/>


                </div><!--.subway-checkout-inner-wrap-->

            </div><!--.subway-checkout-wrap-->

        </form><!--#sw-form-->

        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
	<?php endif; ?>


<?php else: ?>
    <div class="subway-alert subway-alert-warning">
        <p>
			<?php esc_html_e( 'There is an error with the checkout process. Requested Membership Plan is either not found or not yet published.', 'subway' ); ?>
            <br/>
            <a class="sw-button" href="<?php echo esc_url( $options->get_membership_page_url() ); ?>"
               title="<?php esc_attr_e( 'Browse Membership Products', 'subway' ); ?>">
				<?php esc_html_e( '&larr; Browse Membership Products', 'subway' ); ?>
            </a>
        </p>
    </div>
<?php endif; ?>
