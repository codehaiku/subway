<?php
/**
 * Doc
 */
?>

<?php if ( ! is_user_logged_in() ): ?>
    <div class="subway-login-form-message">
        <p class="error">
			<?php esc_html_e( 'Please login to view your account. ', 'subway' ); ?>
            <a href="<?php echo esc_url( wp_login_url() ); ?>" title="<?php echo esc_attr( 'Login', 'subway' ); ?>">
				<?php echo esc_html( __( 'Login', 'subway' ) ); ?>
            </a>
            &middot;
            <a href="<?php echo esc_url( wp_registration_url() ); ?>"
               title="<?php esc_attr( 'Register', 'subway' ); ?>">
				<?php echo esc_html( __( 'Register', 'subway' ) ); ?>
            </a>
        </p>
    </div>
	<?php return; ?>
<?php endif; ?>

<div id="sw-shortcode-user-account-wrap">
    <div id="sw-shortcode-user-account-wrap-inner">
        <div>
			<?php $c_user = wp_get_current_user(); ?>
        </div>
        <div>
            <h3>My Profile</h3>
            <div class="subway-flex-wrap">

                <div class="subway-flex-column-50">
                    <div class="subway-flex-wrap">
                        <div class="subway-flex-column-20">
                            <div style="margin: 10px 0 0 0;">
								<?php echo get_avatar( $c_user->ID, 32 ); ?>
                            </div>
                        </div>
                        <div class="subway-flex-column-80">
                            <p>
								<?php echo esc_html( $c_user->display_name ); ?>
                                <br/>
								<?php echo esc_html( $c_user->user_email ); ?>
                            </p>
                        </div>
                    </div>


                </div>
                <div class="subway-flex-column-50">
                    <ul class="subway-user-account-actions">
                        <li><a href="<?php echo esc_url( get_edit_user_link( get_current_user_id() ) ); ?>">Update
                                Personal Info</a></li>
                        <li><a href="<?php echo esc_url( wp_logout_url() ); ?>">Sign out of my account</a></li>
                    </ul>
                </div>
            </div>

        </div>

        <div>
            <h3>Membership & Billing</h3>
            <div class="subway-flex-wrap">
                <div class="subway-flex-column-50">
                    <div class="subway-flex-wrap">
                        <div class="subway-flex-column-10">
                            <img width="50"
                                 src="https://www.paypalobjects.com/webstatic/mktg/logo-center/PP_Acceptance_Marks_for_LogoCenter_76x48.png"/>
                        </div>
                        <div class="subway-flex-column-80">
                            emailuseinpayment@gmail.com
                        </div>
                    </div>


                </div>
                <div class="subway-flex-column-50">
                    <ul class="subway-user-account-actions">
                        <li><a href="#">Update Payment</a></li>
                        <li><a href="#">Billing Details</a></li>
                    </ul>
                </div>
            </div>

        </div>
        <div>
            <h3>Membership Plans</h3>
            <div class="subway-flex-wrap">
                <div class="subway-flex-column-100">
					<?php $subscriptions = $user->get_subscriptions( get_current_user_id() ); ?>
					<?php if ( ! empty( $subscriptions ) ): ?>
                        <table class="subway-membership-lists subway-mg-top-zero">
                            <thead>
                            <tr>
                                <th><?php esc_html_e( 'Plan Name', 'subway' ); ?></th>
                                <th><?php esc_html_e( 'Billing', 'subway' ); ?></th>
                            </tr>
                            </thead>
                            <tbody>
							<?php foreach ( $subscriptions as $subscription ): ?>
                                <tr>
                                    <td>
                                        <p>
                                            <a href="<?php echo esc_url( $subscription->get_product_url() ); ?>"
                                               title=" <?php echo esc_attr( $subscription->get_name() ); ?>">
                                                <strong>
													<?php echo esc_html( $subscription->get_name() ); ?>
                                                </strong>
                                            </a>
                                            <br/>
											<?php echo esc_html( $subscription->get_displayed_price_without_tax() ); ?>
                                            &mdash;
                                            <?php echo esc_html( $subscription->get_type() ); ?>
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            October 1, 2019
                                        </p>
                                    </td>
                                </tr>
							<?php endforeach; ?>
                            </tbody>
                        </table>
					<?php else: ?>
                        <p>
							<?php esc_html_e( 'You do not have an active subscriptions.', 'subway' ); ?>
                        </p>
					<?php endif; ?>

                </div>

            </div>

        </div>


    </div>
</div>