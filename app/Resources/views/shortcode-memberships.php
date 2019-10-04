<?php
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

$current_plan = get_user_meta( get_current_user_id(), 'subway_user_membership_product_id', true );

?>

<div id="subway-memberships">
	<?php if ( ! empty ( $products ) ): ?>
        <table class="subway-membership-lists">
            <thead>
            <tr>
                <th colspan="3">Membership Plans</th>
            </tr>
            <tr>
                <th>Name</th>
                <th colspan="2">Price</th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ( $products as $list_product ): ?>
				<?php if ( $list_product['id'] === $current_plan ): ?>
                    <tr class="current-user-plan">
				<?php else: ?>
                    <tr>
				<?php endif; ?>

                <td><?php echo esc_html( $list_product['name'] ); ?></td>
                <td>
					<?php echo $currency->format( $list_product['amount'], get_option( 'subway_currency', 'USD' ) ); ?>
                </td>
                <td style="text-align: right;">

					<?php if ( $list_product['id'] === $current_plan ): ?>
                        <span class="current-plan-btn">
	                            <?php esc_html_e( 'Current Plan', 'subway' ); ?>
                                </span>
					<?php else: ?>
                        <a class="sw-button"
                           href="<?php echo esc_url( $product->get_product_checkout_url( $list_product['id'] ) ); ?>">
							<?php esc_html_e( 'Select Membership', 'subway' ); ?>
                        </a>
					<?php endif; ?>

                </td>
                </tr>
			<?php endforeach; ?>
            </tbody>
        </table>
	<?php else: ?>
        <div class="sw-form-errors">
            <p class="sw-error">
				<?php esc_html_e( 'There are no memberships product available at the moment.', 'subway' ); ?>
            </p>
        </div>
	<?php endif; ?>
</div>