<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$user = new \Subway\User\User();
$user->set_id( get_current_user_id() );
$pricing = new \Subway\Memberships\Plan\Pricing\Controller();
?>
<?php if ( $plans ): ?>
    <form action="<?php echo esc_url( $plan->get_plan_checkout_url() ); ?>" method="GET">
        <table class="subway-membership-product-plans subway-mg-top-zero subway-mg-bot-zero">
            <thead>
            <tr>
                <th>
					<?php esc_html_e( 'Select Membership Plan', 'subway' ); ?>
                </th>
            </tr>
            </thead>
			<?php foreach ( $plans as $plan ): ?>

				<?php $checked = ''; ?>

				<?php $disabled = ''; ?>

				<?php if ( $plan->get_id() === $product->get_default_plan_id() ): ?>

					<?php $checked = 'checked'; ?>

				<?php endif; ?>

                <tr class="<?php echo esc_attr( $checked ); ?>">

                    <td>
						<?php $id = sprintf( 'membership-plan-%d', $plan->get_id() ); ?>

						<?php if ( $user->has_plan( $plan->get_id() ) ): ?>

							<?php $disabled = 'disabled'; ?>

						<?php endif; ?>

                        <label for="<?php echo esc_attr( $id ); ?>" class="plan-pricing-details">

                            <span class="product-plan-title">
                                <?php if ( ! $disabled ): ?>
                                    <input <?php echo esc_attr( $checked ); ?>
                                            class="product-single-product-plan"
                                            required id="<?php echo esc_attr( $id ); ?>"
                                            type="radio" name="plan_id"
                                            value="<?php echo esc_attr( $plan->get_id() ); ?>"
                                    />
                                <?php endif; ?>
	                            <?php echo esc_html( $plan->get_name() ); ?>
                            </span>

							<?php if ( $disabled ): ?>
                                <span class="product-plan-user-subscribed">
		                            <?php esc_html_e( 'Subscribed', 'subway' ); ?>
                                </span>
							<?php endif; ?>

							<?php if ( ! $disabled ): ?>

                                <div class="product-plan-pricing">

                                    <?php $pricing->set_plan_id( $plan->get_id() ); ?>

                                    <?php $pricing = $pricing->get(); ?>

                                    <?php if ( $pricing ): ?>
                                        <span class="product-plan-pricing-text">
                                            <?php echo esc_html( $pricing->get_text( $plan ) ); ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php echo esc_html( $plan->get_price( $plan->is_display_tax() ) ); ?>

                                    <?php if ( $pricing ): ?>
                                        <?php if ( $pricing->is_has_trial() ): ?>
                                            <a class="subway-trial-button"
                                               href="<?php echo esc_url( $pricing->get_trial_checkout_url( $plan->get_id() ) ); ?>"
                                               title="<?php esc_attr( $pricing->get_trial_info() ); ?>">
                                            <?php echo esc_html( $pricing->get_trial_info() ); ?>
                                        </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
							<?php endif; ?>


                        </label>


                    </td>
                </tr>
			<?php endforeach; ?>
        </table>

        <div id="product-plans-submit">
			<?php if ( $user->has_plan( filter_input( 1, 'plan-id', 519 ) ) ): ?>
                <button disabled type="submit" class="sw-button subway-mg-top-zero width-100">
					<?php esc_html_e( 'Proceed to checkout &rarr;', 'subway' ); ?>
                </button>
			<?php else: ?>
                <button type="submit" class="sw-button subway-mg-top-zero width-100">
					<?php esc_html_e( 'Proceed to checkout &rarr;', 'subway' ); ?>
                </button>
			<?php endif; ?>
        </div>

    </form>
<?php endif; ?>