<?php
if ( ! defined( 'ABSPATH' ) ) {
	return;
}
?>

<div id="subway-memberships">

	<?php $current_plan = get_user_meta( get_current_user_id(), 'subway_user_membership_product_id', true ); ?>

	<?php if ( ! empty ( $plans ) ): ?>

        <table class="subway-membership-lists">

            <thead>
            <tr>
                <th colspan="3">
					<?php esc_html_e( 'Memberships Plans', 'subway' ); ?>
                </th>
            </tr>
            <tr>
                <th>
					<?php esc_html_e( 'Name', 'subway' ); ?>
                </th>
                <th colspan="2">
					<?php esc_html_e( 'Price', 'subway' ); ?>
                </th>
            </tr>
            </thead>

            <tbody>

			<?php foreach ( $plans as $item ): ?>

				<?php if ( $item->get_id() === $current_plan ): ?>

                    <tr class="current-user-plan">

				<?php else: ?>

                    <tr>

				<?php endif; ?>

                <!-- Name -->
                <td>
					<?php echo esc_html( $item->get_name() ); ?>
                </td>
                <!-- Name End -->

                <!-- Price -->
                <td>
					<?php echo esc_html( $item->get_displayed_price() ); ?>
                </td>
                <!-- Price End -->

                <td style="text-align: right;">

					<?php if ( $item->get_id() === $current_plan ): ?>

                        <span class="current-plan-btn">
                                   <?php esc_html_e( 'Current Plan', 'subway' ); ?>
                                </span>

					<?php else: ?>

                        <a class="sw-button"
                           href="<?php echo esc_url( $plan->get_plan_checkout_url( $item->get_id() ) ); ?>">
							<?php esc_html_e( 'Select Plan', 'subway' ); ?>
                        </a>

					<?php endif; ?>

                </td>
                </tr>

			<?php endforeach; ?>

            </tbody>

        </table>

	<?php else: ?>

        <div class="subway-alert subway-alert-danger">

            <p>
				<?php esc_html_e( 'There are no membership plans found.', 'subway' ); ?>
            </p>

        </div>

	<?php endif; ?>

</div>