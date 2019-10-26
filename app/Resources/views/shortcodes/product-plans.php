<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php if ( $plans ): ?>
    <form action="http://multisite.local/checkout" method="GET">
        <table class="subway-membership-product-plans">
            <thead>
            <tr>
                <th colspan="2">
					<?php esc_html_e( 'Select Membership Plan', 'subway' ); ?>
                </th>
            </tr>
            </thead>
			<?php foreach ( $plans as $plan ): ?>
                <tr>
                    <td>
						<?php echo esc_html( $plan->get_displayed_price() ); ?>
                        /
						<?php echo esc_html( $plan->get_type() ); ?>
                    </td>
                    <td>
						<?php $id = sprintf( 'membership-plan-%d', $plan->get_id() ); ?>
                        <label for="<?php echo esc_attr( $id ); ?>">
                            <input id="<?php echo esc_attr( $id ); ?>" type="radio" name="plan_id"
                                   value="<?php echo esc_attr( $plan->get_id() ); ?>">
							<?php echo esc_html( $plan->get_name() ); ?>
                        </label>
                    </td>
                </tr>
			<?php endforeach; ?>
        </table>
        <button type="submit" class="sw-button">
            <?php esc_html_e('Select Membership Plan', 'subway'); ?>
        </button>
    </form>
<?php endif; ?>