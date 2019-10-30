<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$user = new \Subway\User\User();
$user->set_id( get_current_user_id() );
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
                <tr>
                    <td>
						<?php $id = sprintf( 'membership-plan-%d', $plan->get_id() ); ?>

						<?php $checked = ''; ?>

						<?php $disabled = ''; ?>

						<?php if ( $plan->get_id() === $product->get_default_plan_id() ): ?>

							<?php $checked = 'checked'; ?>

						<?php endif; ?>

						<?php if ( $user->has_plan( $plan->get_id() ) ): ?>

							<?php $disabled = 'disabled'; ?>

						<?php endif; ?>

                        <label for="<?php echo esc_attr( $id ); ?>">

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


                        </label>

                        <span class="product-plan-pricing">
							<?php echo esc_html( $plan->get_displayed_price() ); ?>  / <?php echo esc_html( $plan->get_type() ); ?>
                        </span>


                    </td>
                </tr>
			<?php endforeach; ?>
        </table>

        <div id="product-plans-list">
            <button type="submit" class="sw-button subway-mg-top-zero aligncenter">
				<?php esc_html_e( 'Checkout', 'subway' ); ?>
            </button>
        </div>
    </form>
<?php endif; ?>