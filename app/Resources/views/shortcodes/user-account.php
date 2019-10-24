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
                        <li>
                            <a title="<?php esc_attr_e( 'Update Personal Info', 'subway' ); ?>"
                               href="<?php echo esc_url( get_edit_user_link( get_current_user_id() ) ); ?>">
								<?php esc_html_e( 'Update Personal Info', 'subway' ); ?>
                            </a>
                        </li>
                        <li>
                            <a title="<?php esc_attr_e( 'Change E-mail Address', 'subway' ); ?>"
                               href="<?php echo esc_url( add_query_arg( 'account-page', 'update-email-address', $options->get_accounts_page_url() ) ); ?>">
								<?php esc_html_e( 'Change E-mail Address', 'subway' ); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        <div>
            <h3>
				<?php esc_html_e( 'Membership Plans', 'subway' ); ?>
            </h3>
            <div class="subway-flex-wrap">
                <div class="subway-flex-column-100">
					<?php $subscriptions = $user->get_subscriptions( get_current_user_id() ); ?>

					<?php if ( ! empty( $subscriptions ) ): ?>

                        <table class="subway-membership-lists subway-mg-top-zero">
                            <thead>
                            <tr>
                                <th><?php esc_html_e( 'Plan Name', 'subway' ); ?></th>
                                <th><?php esc_html_e( 'Status', 'subway' ); ?></th>
                                <th><?php esc_html_e( 'Next Billing', 'subway' ); ?></th>
                            </tr>
                            </thead>

                            <tbody>
							<?php foreach ( $subscriptions as $subscription ): ?>
                                <tr>
                                    <td>
                                        <p>
                                            <a href="<?php echo esc_url( $subscription->plan->get_plan_url() ); ?>"
                                               title=" <?php echo esc_attr( $subscription->plan->get_name() ); ?>">
                                                <strong>
													<?php echo esc_html( $subscription->plan->get_name() ); ?>
                                                </strong>
                                            </a>
                                            <br/>
											<?php echo esc_html( $subscription->plan->get_displayed_price_without_tax() ); ?>
                                            &mdash;
											<?php echo esc_html( $subscription->plan->get_type() ); ?>
                                        </p>
                                    </td>
                                    <td>
										<?php esc_html_e( 'Active', 'subway' ); ?>
                                    </td>
                                    <td>
                                        <p>
                                            October 1, 2019 @todo
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

                        <p>
                            <a href="<?php echo esc_url( $options->get_membership_page_url() ); ?>"
                               title="<?php esc_attr_e( 'Select Memberships', 'subway' ); ?>" class="sw-button">
								<?php esc_html_e( 'Select Memberships', 'subway' ); ?>
                            </a>
                        </p>

					<?php endif; ?>

                </div>


            </div>

        </div>

        <div>
            <h3>
				<?php esc_html_e( 'My Invoices', 'subway' ); ?>
            </h3>
            <div class="subway-flex-wrap">
                <div class="subway-flex-column-100">
                    <table class="subway-membership-lists subway-mg-top-zero">
                        <thead>
                        <tr>
                            <th><?php esc_html_e( 'Date', 'subway' ); ?></th>
                            <th><?php esc_html_e( 'Plan', 'subway' ); ?></th>
                            <th><?php esc_html_e( 'Invoice Number', 'subway' ); ?></th>
                            <th><?php esc_html_e( 'Total Amount', 'subway' ); ?></th>
                        </tr>
                        </thead>
                        <tbody>
						<?php if ( ! empty( $invoices ) ): ?>
							<?php foreach ( $invoices as $invoice ): ?>
								<?php $item = $plan->get_plan( $invoice->plan_id ); ?>
								<?php if ( $item ): ?>
                                    <tr>
                                        <td><?php echo esc_html( $invoice->created ); ?></td>
                                        <td>

                                            <a href="<?php echo esc_url( $item->get_plan_url() ); ?>"
                                               title="<?php echo esc_attr( $item->get_name() ); ?>">
												<?php echo esc_html( $item->get_name() ); ?>
                                            </a>

                                        </td>
                                        <td>

											<?php
											$invoice_url = add_query_arg( [
												'account-page' => 'invoice',
												'invoice_id'   => $invoice->id
											], $options->get_accounts_page_url() );
											?>

                                            <a href="<?php echo esc_url( $invoice_url ); ?>"
                                               title="<?php echo esc_html( $invoice->invoice_number ); ?>">

												<?php echo esc_html( $invoice->invoice_number ); ?>

                                            </a>
                                        </td>

                                        <td>
											<?php echo esc_html( $invoice->amount ); ?>
                                        </td>
                                    </tr>
								<?php endif; ?>

							<?php endforeach; ?>
						<?php else: ?>
                            <tr>
                                <td colspan="4">
									<?php esc_html_e( 'There are no invoices found.', 'subway' ); ?>
                                </td>
                            </tr>
						<?php endif; ?>

                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="4">
								<?php $invoice_count = count( $invoices ); ?>
								<?php printf( _n( 'Found %d Invoice', 'Found %d Invoices', $invoice_count, 'subway' ), number_format_i18n( $invoice_count ) ); ?>
                            </td>
                        </tr>
                        </tfoot>
                    </table>

                </div>

            </div>
        </div>
        <h6>
            <a class="sw-button sw-button-danger" title="<?php esc_attr_e( 'Sign out of my account', 'subway' ); ?>"
               href="<?php echo esc_url( wp_logout_url() ); ?>">
				<?php esc_html_e( 'Sign out of my account', 'subway' ); ?>
            </a>
        </h6>

    </div>
</div>