<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
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

                        <label for="<?php echo esc_attr( $id ); ?>">
                            <input required id="<?php echo esc_attr( $id ); ?>" type="radio" name="plan_id"
                                   value="<?php echo esc_attr( $plan->get_id() ); ?>">
							<?php echo esc_html( $plan->get_name() ); ?>

                        </label>
                        <em>
						<?php echo esc_html( $plan->get_displayed_price() ); ?>
                        /
						<?php echo esc_html( $plan->get_type() ); ?>
                        </em>
                    </td>
                </tr>
			<?php endforeach; ?>
        </table>
        <button type="submit" class="sw-button subway-mg-top-zero">
			<?php esc_html_e( 'Select Membership Plan', 'subway' ); ?>
        </button>
    </form>
<?php endif; ?>