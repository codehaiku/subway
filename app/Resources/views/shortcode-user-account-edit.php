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
            <a href="<?php echo esc_url( $options->get_accounts_page_url() ); ?>" class="sw-button">
				<?php esc_html_e( '&larr; Go Back to Account Dashboard', 'subway' ); ?>
            </a>
            <h3>
				<?php esc_html_e( 'Edit Personal Profile', 'subway' ); ?>
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
                        <input type="hidden" name="action" value="subway_user_edit_profile"/>

                        <!-- First Name -->
                        <div class="subway-form-row">

                            <div class="sw-field-inner-row">

                                <div class="sw-block sw-field-title">
                                    <label>
										<?php echo esc_html_e( 'First Name:', 'subway' ); ?>
                                    </label>
                                </div>

                                <div class="sw-block sw-field">
                                    <input autocomplete="off" placeholder="" type="text" name="name"
                                           value="<?php echo esc_attr( $wp_user->first_name ); ?>"/>
                                </div>
								<?php if ( isset( $response['message']['name'] ) && ! empty( $response['message']['name'] ) ): ?>
                                    <div class="sw-form-errors">
                                        <p class="sw-error">
											<?php echo $response['message']['name']; ?>
                                        </p>
                                    </div>
								<?php endif; ?>
                            </div><!--.sw-field-inner-row-->

                        </div><!--.subway-form-row--->
                        <!-- First Name End -->

                        <!-- Last Name -->
                        <div class="subway-form-row">

                            <div class="sw-field-inner-row">

                                <div class="sw-block sw-field-title">
                                    <label>
										<?php echo esc_html_e( 'Last Name:', 'subway' ); ?>
                                    </label>
                                </div>

                                <div class="sw-block sw-field">
                                    <input autocomplete="off" placeholder="" type="text" name="last_name"
                                           value="<?php echo esc_attr( $wp_user->last_name ); ?>"/>
                                </div>

								<?php if ( isset( $response['message']['last_name'] ) && ! empty( $response['message']['last_name'] ) ): ?>
                                    <div class="sw-form-errors">
                                        <p class="sw-error">
											<?php echo $response['message']['last_name']; ?>
                                        </p>
                                    </div>
								<?php endif; ?>

                            </div><!--.sw-field-inner-row-->

                        </div><!--.subway-form-row--->
                        <!-- Last Name End -->

                        <!-- Display Name -->
                        <div class="subway-form-row">

                            <div class="sw-field-inner-row">

                                <div class="sw-block sw-field-title">
                                    <label>
										<?php echo esc_html_e( 'Display name publicly as:', 'subway' ); ?>
                                    </label>
                                </div>

                                <div class="sw-block sw-field">

                                    <select name="display_name">

                                        <option value="<?php echo esc_attr( $wp_user->user_login ); ?>">
											<?php esc_html_e( '- Use My Username -', 'subway' ); ?>
                                        </option>

										<?php if ( ! empty ( $wp_user->first_name ) ): ?>
                                            <option <?php echo $wp_user->first_name === $wp_user->display_name ? 'selected' : ''; ?>>
												<?php echo esc_html( $wp_user->first_name ); ?>
                                            </option>
										<?php endif; ?>

										<?php if ( ! empty ( $wp_user->last_name ) ): ?>
                                            <option <?php echo $wp_user->last_name === $wp_user->display_name ? 'selected' : ''; ?>>
												<?php echo esc_html( $wp_user->last_name ); ?>
                                            </option>
										<?php endif; ?>

										<?php if ( ! empty ( $wp_user->first_name ) && ! empty ( $wp_user->last_name ) ): ?>
											<?php $name = sprintf( '%s %s', $wp_user->first_name, $wp_user->last_name ); ?>
                                            <option <?php echo $name === $wp_user->display_name ? 'selected' : ''; ?>>
												<?php echo esc_html( $name ); ?>
                                            </option>
										<?php endif; ?>

										<?php if ( ! empty ( $wp_user->first_name ) && ! empty ( $wp_user->last_name ) ): ?>
											<?php $name_reversed = sprintf( '%s %s', $wp_user->last_name, $wp_user->first_name ); ?>
                                            <option <?php echo $name_reversed === $wp_user->display_name ? 'selected' : ''; ?>>
												<?php echo esc_html( $name_reversed ); ?>
                                            </option>
										<?php endif; ?>

                                    </select>
                                </div>

                            </div><!--.sw-field-inner-row-->

                        </div><!--.subway-form-row--->
                        <!-- Display Name End -->


                        <!-- Save Button -->
                        <button class="sw-button" type="submit">
							<?php esc_html_e( 'Save Profile', 'subway' ); ?>
                        </button>
                        <!-- Save Button End -->
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>