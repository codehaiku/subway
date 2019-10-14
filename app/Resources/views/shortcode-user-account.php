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
                        <li><a href="<?php echo esc_url( get_edit_user_link( get_current_user_id() ) ); ?>">Update Personal Info</a></li>
                        <li><a href="<?php echo esc_url( wp_logout_url() ); ?>">Sign out of my account</a></li>
                    </ul>
                </div>
            </div>

        </div>

        <div>
            <h3>Membership & Billing</h3>
            <div class="subway-flex-wrap">
                <div class="subway-flex-column-50">
                    <img width="50" src="https://www.paypalobjects.com/webstatic/mktg/logo-center/PP_Acceptance_Marks_for_LogoCenter_76x48.png" />
                    emailuseinpayment@gmail.com
                </div>
                <div class="subway-flex-column-50 ">
                    <ul class="subway-user-account-actions">
                        <li><a href="#">Update Payment</a></li>
                        <li><a href="#">Billing Details</a></li>
                    </ul>
                </div>
            </div>

        </div>
        <div>
            <h3>Plan Details</h3>
            <div class="subway-flex-wrap">
                <div class="subway-flex-column-50">
                    <strong>
                        Subway Memberships Pro
                    </strong>
                </div>
                <div class="subway-flex-column-50 ">
                    <ul class="subway-user-account-actions">
                        <li><a href="<?php echo esc_url( $options->get_membership_page_url() ); ?>">Change Membership Plan</a></li>
                        <li><a href="#">Cancel Membership</a></li>
                    </ul>
                </div>
            </div>

        </div>


    </div>
</div>