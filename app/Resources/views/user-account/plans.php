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
                    <th><?php esc_html_e( 'Membership Plan', 'subway' ); ?></th>
                    <th><?php esc_html_e( 'Status', 'subway' ); ?></th>
                    <th><?php esc_html_e( 'Last Payment', 'subway' ); ?></th>
                    <th><?php esc_html_e( 'Next Payment', 'subway' ); ?></th>
                </tr>
                </thead>

                <tbody>

				<?php foreach ( $subscriptions as $subscription ): ?>

					<?php if ( $subscription->plan ) : ?>
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

										<?php echo esc_html( $subscription->plan->get_price() ); ?>
                                        /
										<?php echo esc_html( $subscription->plan->get_type() ); ?>

                                        <p class="subway-mg-bot-zero">
                                            <small>Paid:
                                                Oct 18, 2019 @TODO
                                            </small>
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

                                    <p class="subway-mg-bot-zero">July 02, 2020 @TODO</p>
                                    <p>
                                        <a title="<?php esc_attr_e( 'Cancel Membership', 'subway' ); ?>"
                                           href="<?php echo esc_url( $subscription->plan->get_cancel_url() ); ?>">
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