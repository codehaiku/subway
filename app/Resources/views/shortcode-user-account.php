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
            <h3>
				<?php echo sprintf( __( 'Welcome! %s', 'subway' ), $c_user->user_nicename ); ?>
            </h3>
            <hr/>
        </div>

        <div>
            <h4>My Profile</h4>
            <div>
                <dl>
                    <dt>
                        <p>
							<?php echo get_avatar( $c_user->ID, 48 ); ?>
                        </p>

                        <p>
							<?php echo esc_html( $c_user->display_name ); ?><br/>
							<?php echo esc_html( $c_user->user_email ); ?>
                        </p>

                    </dt>

                    <dd>
                        <ul>
                            <li><a href="#">Settings</a></li>
                            <li><a href="#">Log out</a></li>
                        </ul>
                    </dd>
                </dl>
            </div>
        </div>

        <div>
            <h4>Membership & Billing</h4>

            <div>
                <dl>
                    <dt><?php echo esc_html( $c_user->user_email ); ?></dt>
                    <dd></dd>
                </dl>
            </div>
            <div>
                <dl>
                    <dt>
                        <img width="80" src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-100px.png"/>
                        emailuseinpayment@gmail.com
                    </dt>
                    <dd>
                        <ul>
                            <li><a href="#">Update Payment Info</a></li>
                            <li><a href="#">Billing Details</a></li>
                        </ul>
                    </dd>
                </dl>

            </div>
        </div>
        <div>
            <h4>Plan Details</h4>
            <div>
                <dl>
                    <dt>Subway Memberships Pro</dt>
                    <dd>
                        <ul>
                            <li><a href="#">Change Plan</a></li>
                            <li><a href="#">Cancel Membership</a></li>
                        </ul>
                    </dd>
                </dl>
            </div>
        </div>


    </div>
</div>