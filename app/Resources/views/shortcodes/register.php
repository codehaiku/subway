<?php
/**
 * Register shortcode template
 */
$plan_id = filter_input( INPUT_GET, 'plan_id', 519 );
?>

<?php if ( is_user_logged_in() ): ?>

    <div class="subway-alert subway-alert-success">
        <p>
			<?php esc_html_e( 'You are already registered', 'subway' ); ?>
        </p>
    </div>

<?php else: ?>

    <form class="sw-form" action="" method="POST">

        <input type="hidden" name="sw-action" value="1"/>

        <input type="hidden" name="sw-plan-id" value="<?php echo absint( $plan_id ); ?>"/>

        <div class="subway-flex-wrap">
            <div class="subway-flex-column-100">
                <div id="subway-checkout-order-review">
		            <?php
		            $checkout = new \Subway\Checkout\Checkout();
		            $this->render(
			            'checkout-order',
			            [
				            'plan'     => $plan,
				            'currency' => $currency,
				            'options'  => $options,
                            'checkout' => $checkout
			            ] );
		            ?>
                </div>
            </div>
        </div>

        <div class="subway-flex-wrap">
            <div class="subway-flex-column-60">
                <div id="subway-checkout-user-info">
                    <p>
		                <?php esc_html_e( 'Already have an account? Click', 'subway' ); ?>
                        <a href="#">
			                <?php esc_html_e( 'here to login', 'subway' ); ?>
                        </a>
		                <?php esc_html_e( 'instead', 'subway' ); ?>
                    </p>
                    <div class="subway-flex-column">

		                <?php $errors = apply_filters( 'subway_shortcode_register_errors', array() ); ?>

                        <!--Form row start-->
                        <div class="subway-form-row">

                            <div class="sw-field-inner-row">

                                <label>

	                    <span class="sw-block sw-field-title">

                            <?php esc_html_e( 'Username ', 'subway' ); ?>

                        </span>

                                    <span class="sw-block sw-field">

                            <input autocomplete="off"
                                   placeholder="<?php esc_attr_e( 'Example: john_doe99', 'subway' ); ?>"
                                   type="text"
                                   name="sw-username"
                                   required
                                   value="<?php echo isset( $_POST['sw-username'] ) ? esc_attr( $_POST['sw-username'] ) : 'user' . uniqid(); ?>"
                            />

                        </span>

                                </label>
                            </div>
			                <?php if ( isset( $errors['sw-username'] ) ): ?>

                                <div class="sw-form-errors">

                                    <p class="sw-error">

						                <?php echo esc_html( $errors['sw-username'] ); ?>

                                    </p>

                                </div><!--.sw-form-errors-->

			                <?php endif; ?>

                            <div class="sw-field-inner-row sw-field-howto">

				                <?php esc_html_e( 'Alphanumeric characters are allowed no special characters allowed.', 'subway' ); ?>

                            </div>

                        </div>
                        <!--Form row end-->

                        <!--Form row start-->
                        <!--Email Address -->
                        <div class="subway-form-row">
                            <div class="sw-field-inner-row">
                                <label>
	                    <span class="sw-block sw-field-title">
                            <?php esc_html_e( 'Email Address ', 'subway' ); ?>
                        </span>

                                    <span class="sw-block sw-field">
                            <input autocomplete="off"
                                   placeholder="<?php esc_attr_e( 'Example: john_doe99@website.org', 'subway' ); ?>"
                                   type="email"
                                   required
                                   name="sw-email"
                                   value="<?php echo isset( $_POST['sw-email'] ) ? esc_attr( $_POST['sw-email'] ) : 'user-emai' . uniqid() . '@yahoo.com'; ?>"
                            />
                        </span>
                                </label>
                            </div>
			                <?php if ( isset( $errors['sw-email'] ) ): ?>
                                <div class="sw-form-errors">
                                    <p class="sw-error">
						                <?php echo esc_html( $errors['sw-email'] ); ?>
                                    </p>
                                </div><!--.sw-form-errors-->
			                <?php endif; ?>
                            <div class="sw-field-inner-row sw-field-howto">
				                <?php esc_html_e( 'This is where we will send you important updates.', 'subway' ); ?>
                            </div>
                        </div>
                        <!--Form row end-->
                        <!-- Email Address -->

                        <!-- Form row start -->
                        <!-- Password -->
                        <div class="subway-form-row">

                            <div class="sw-field-inner-row">
                                <label>

                        <span class="sw-block sw-field-title">
                            <?php esc_html_e( 'Set Account Password ', 'subway' ); ?>
                        </span>

                                    <span class="sw-block sw-field">
                            <input autocomplete="off"
                                   placeholder="<?php esc_attr_e( '****', 'subway' ); ?>"
                                   type="password"
                                   name="sw-password"
                                   required
                                   value="a123"
                            />
                         </span>
                                </label>

                            </div>

			                <?php if ( isset( $errors['sw-password'] ) ): ?>
                                <div class="sw-form-errors">
                                    <p class="sw-error">
						                <?php echo esc_html( $errors['sw-password'] ); ?>
                                    </p>
                                </div><!--.sw-form-errors-->
			                <?php endif; ?>

                            <div class="sw-field-inner-row sw-field-howto">
				                <?php esc_html_e( 'Please do not forget your password!', 'subway' ); ?>
                            </div>
                        </div>
                        <!-- Password end-->
                        <!--Form row end-->
                        <!--Form row start-->
                        <div class="subway-form-row">
                            <div class="sw-field-inner-row">
                                <label>
                        <span class="sw-block sw-field-title">
                            <?php esc_html_e( 'Confirm Password ', 'subway' ); ?>
                        </span>
                                    <span class="sw-block sw-field-sub-title">
                            <?php esc_html_e( 'Please re-type the password below', 'subway' ); ?>
                        </span>
                                    <span class="sw-block sw-field">
                            <input autocomplete="off"
                                   placeholder="<?php esc_attr_e( '****', 'subway' ); ?>"
                                   type="password"
                                   name="sw-password-confirm"
                                   required
                                   value="a123"
                            />
                        </span>
                                </label>
                            </div>
			                <?php if ( isset( $errors['sw-password-confirm'] ) ): ?>
                                <div class="sw-form-errors">
                                    <p class="sw-error">
						                <?php echo esc_html( $errors['sw-password-confirm'] ); ?>
                                    </p>
                                </div><!--.sw-form-errors-->
			                <?php endif; ?>
                        </div>
                        <!--Form row end-->
		                <?php do_action( 'subway_shortcode_register_before_submit' ); ?>


                    </div>
                </div>
            </div>
            <div class="subway-flex-column-40">
                <div class="subway-flex-column">
                    <!-- Coupon -->
                    <div class="sw-block sw-field">
                        <label>
                            Have a coupon? <a href="#">Click here to enter discount coupon.</a>
                            <input style="display:none" placeholder="Discount Code" class="hidden" type="text" name="sw-coupon"/>
                        </label>
                    </div>
                    <!-- Coupon End -->
                    <!-- Review Order -->
                    <div class="subway-checkout-review-order">

			            <?php if ( ! empty ( $plan ) ): ?>

                            <div class="subway-checkout-review-order-table">
					            <?php $this->render( 'checkout-gateway', [
						            'plan'     => $plan,
						            'currency' => $currency,
						            'options'  => $options
					            ] ); ?>
                            </div><!--.subway-checkout-review-order-->

                            <!--Form row start-->

			            <?php else: ?>


                            <div class="subway-alert subway-alert-info">
                                <p>
						            <?php esc_html_e( 'Please select a membership plan first.', 'subway' ); ?>
                                    <br/>
                                    <a title="<?php esc_attr_e( 'Select Membership Plan', 'subway' ); ?>" class="sw-button"
                                       href="<?php echo esc_url( $options->get_membership_page_url() ); ?>">
							            <?php esc_html_e( 'Select Membership Plan', 'subway' ); ?>
                                    </a>
                                </p>

                            </div>


			            <?php endif; ?>


                    </div><!--.subway-checkout-review-order-->
                </div>
            </div>
        </div>
    </form>

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

<?php endif; ?>