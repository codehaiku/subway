<?php
/**
 * Doc
 */
?>
<?php $response = end( $message ); ?>

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

            <h3 class="mg-top-zero">
				<?php esc_html_e( 'Edit E-mail Address', 'subway' ); ?>
            </h3>

			<?php if ( isset( $response['type'] ) && $response['type'] === 'success' ): ?>
                <div class="sw-form-success">
                    <p class="sw-success">
						<?php echo esc_html( $response['message'] ); ?>
                    </p>
                </div>
			<?php endif; ?>

            <div class="subway-flex-wrap">
                <div class="subway-flex-column-100">
                    <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">

                        <!-- Hidden Fields -->
                        <input type="hidden" name="action" value="subway_user_edit_email"/>
                        <input type="hidden" name="user_id" value="<?php echo esc_attr( get_current_user_id() ); ?>"/>

                        <!-- Email Address -->
                        <div class="subway-form-row">
                            <p>
                                <strong>
									<?php esc_html_e( 'Current Email Address:', 'subway' ); ?>
                                </strong>
                                <br/>
                                <small><?php echo esc_attr( $wp_user->user_email ); ?></small>
                            </p>
                        </div>

						<?php $user_new_email = get_user_meta( get_current_user_id(), '_new_email', true ); ?>

						<?php if ( empty( $user_new_email ) ): ?>
                            <!-- Email Address -->
                            <div class="subway-form-row">

                                <div class="sw-field-inner-row">

                                    <div class="sw-block sw-field-title">
                                        <label>
											<?php echo esc_html_e( 'Enter New Email Address:', 'subway' ); ?>
                                        </label>
                                    </div>
									<?php if ( isset( $response['message']['email'] ) && ! empty( $response['message']['email'] ) ): ?>
                                        <div class="sw-form-errors">
                                            <p class="sw-error">
												<?php echo $response['message']['email']; ?>
                                            </p>
                                        </div>
									<?php endif; ?>

                                    <div class="sw-block sw-field">
                                        <input autocomplete="off"
                                               placeholder="<?php esc_attr_e( 'mail.address@example.com', 'subway' ); ?>"
                                               type="text" name="email"
                                               value=""/>
                                    </div>

                                    <div class="sw-field-inner-row sw-field-howto">
                                        <p class="howto">
											<?php esc_html_e( 'We will send you a confirmation link. The new address will not become active until confirmed.', 'subway' ); ?>
                                        </p>
                                    </div>


                                </div><!--.sw-field-inner-row-->

                            </div><!--.subway-form-row--->
                                  <!-- Email Address End -->


                                  <!-- Save Button -->

                            <p>
                                <button class="sw-button" type="submit">
									<?php esc_html_e( 'Change E-mail Address', 'subway' ); ?>
                                </button>
                            </p>
						<?php else: ?>

                            <div class="subway-form-row">

                                <div class="subway-alert subway-alert-info">

                                    <p>
										<?php echo sprintf( esc_html__( 'There is a pending change of your email to %s. Check your email address for confirmation link.', 'subway' ), $user_new_email['newemail'] ); ?>
                                    </p>

									<?php
									$email_cancel_url = esc_url_raw( add_query_arg( [
										'account-page' => 'update-email-address',
										'action'       => 'cancel-email'
									], $options->get_accounts_page_url() ) );

									$email_cancel_url = wp_nonce_url( $email_cancel_url, 'dismiss_user_email_change_' . get_current_user_id() );
									?>

                                    <a title="<?php esc_html_e( 'Cancel Email Change', 'subway' ); ?>"
                                       href="<?php echo esc_url( $email_cancel_url ); ?>" class="sw-button">
										<?php esc_html_e( 'Cancel Email Change', 'subway' ); ?>
                                    </a>

                                </div>

                            </div>

						<?php endif; ?>
                        <p>
                            <a href="<?php echo esc_url( $options->get_accounts_page_url() ); ?>"
                               class="sw-button-link">
								<?php esc_html_e( '&larr; Go back to Account Dashboard', 'subway' ); ?>
                            </a>
                        </p>
                        <!-- Save Button End -->
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>