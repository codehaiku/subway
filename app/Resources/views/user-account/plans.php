<?php $currency = new Subway\Currency\Currency(); ?>
<h3>
	<?php esc_html_e( 'Membership Plans', 'subway' ); ?>
</h3>
<div class="subway-flex-wrap">

    <div class="subway-flex-column-100">

		<?php $subscriptions = $user->get_subscriptions(); ?>

		<?php if ( ! empty( $subscriptions ) ): ?>

            <table class="subway-membership-lists subway-mg-top-zero">

                <thead>
                <tr>
                    <th><?php esc_html_e( 'Plan', 'subway' ); ?></th>
                    <th><?php esc_html_e( 'Status', 'subway' ); ?></th>
                    <th><?php esc_html_e( 'Recent Payment', 'subway' ); ?></th>
                    <th><?php esc_html_e( 'Next Due Date', 'subway' ); ?></th>
                </tr>
                </thead>

                <tbody>

				<?php foreach ( $subscriptions as $subscription ): ?>


					<?php if ( $subscription->plan ) : ?>

						<?php $plan = $subscription->plan; ?>
                        <tr>
                            <td>
                                <h4 class="subway-mg-top-zero subway-mg-bot-zero">
									<?php echo $subscription->plan->get_product_link(); ?>
                                </h4>
                                <p>
                                    <a href="<?php echo esc_url( $subscription->plan->get_plan_url() ); ?>"
                                       title=" <?php echo esc_attr( $subscription->plan->get_name() ); ?>">
                                        <strong>
											<?php echo esc_html( $subscription->plan->get_name() ); ?>
                                        </strong>
                                    </a>
                                    <br/>
									<?php echo esc_html( $subscription->plan->get_pricing()->get_text( $subscription->plan ) ); ?>
                                </p>

                            </td>

                            <td>

								<?php $status = $subscription->result->status; ?>
								<?php if ( 'active' === $subscription->result->trial_status ) { ?>
									<?php $status = 'trial'; ?>
								<?php } ?>

                                <div class="product-plan-user-<?php echo esc_attr( $status ); ?>">
									<?php echo esc_html( $status ); ?>
                                </div>

								<?php if ( 'trial' === $status ): ?>
                                    <div class="trial-info">
										<?php echo esc_html( sprintf( __( '%s expires on %s', 'subway' ),
											$subscription->plan->get_pricing()->get_trial_info( true ),
											date( 'F j, Y  g:iA', $subscription->result->trial_ending ) ) );
										?>
                                    </div>
								<?php endif; ?>
                            </td>

                            <td>
                                <div class="product-user-plan-details">

									<?php if ( 'cancelled' === $subscription->result->status ): ?>
                                        <p>
                                            <em>
                                                <small>
													<?php esc_html_e( 'Not Applicable', 'subway' ); ?>
                                                </small>
                                            </em>
                                        </p>
									<?php else: ?>

                                        <div class="product-plan-user-trial">
											<?php esc_html_e( 'Amount:', 'subway' ); ?>
											<?php echo esc_html( $currency->format( $subscription->result->orders_amount, $subscription->result->orders_currency ) ); ?>
                                        </div>

                                        <p class="subway-mg-bot-zero">

											<?php echo date( 'F j, <\b\\r> Y  g:i a', strtotime( $subscription->result->created ) ); ?>
                                            <br/>
                                            <a href="<?php echo esc_url( add_query_arg( [
												'account-page' => 'invoice',
												'invoice_id'   => 20
											], $options->get_accounts_page_url() ) ); ?>">
												<?php esc_html_e( 'View Invoice', 'subway' ); ?>
                                            </a>
                                        </p>

									<?php endif; ?>

                                </div>
                            </td>

                            <td>
								<?php if ( 'cancelled' === $subscription->result->status ): ?>

                                    <p>
                                        <em>
                                            <small>
												<?php esc_html_e( 'Not Applicable', 'subway' ); ?>
                                            </small>
                                        </em>
                                    </p>

								<?php else: ?>

                                    <p>
                                        <a title="<?php esc_attr_e( 'Cancel Membership', 'subway' ); ?>"
                                           href="<?php echo esc_url( $subscription->plan->get_cancel_url() ); ?>" class="sw-button">
											<?php esc_html_e( 'Cancel Membership', 'subway' ); ?>
                                        </a>
                                    </p>

								<?php endif; ?>

                            </td>

                        </tr>
					<?php endif; ?>
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