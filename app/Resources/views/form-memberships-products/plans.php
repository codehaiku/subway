<?php
$plans = new \Subway\Memberships\Plan\Plan();
$items = $plans->get_plans( [ 'product_id' => $product->get_id() ] );
if ( ! $items ) {
	$items = [];
}
?>

<table class="wp-list-table widefat fixed striped" id="boxmembership-product-plans-table">
    <thead>
    <tr>
        <th colspan="2">
			<?php esc_html_e( 'Membership Plans', 'subway' ); ?>
        </th>
        <th colspan="4">
            <a href="<?php echo esc_url_raw( $plans->get_add_url( $product->get_id() ) ); ?>"
               class="button button-small button-secondary">
                <span>&plus;</span>
				<?php esc_html_e( 'Create New Plan', 'subway' ); ?>
            </a>
        </th>
    </tr>
    <tr>
        <th colspan="2"><?php esc_html_e( 'Plan Name', 'subway' ); ?></th>
        <th><?php esc_html_e( 'Displayed Price', 'subway' ); ?></th>
        <th><?php esc_html_e( 'Type', 'subway' ); ?></th>
        <th><?php esc_html_e( 'Status', 'subway' ); ?></th>
        <th><?php esc_html_e( 'Updated', 'subway' ); ?></th>
    </tr>
    </thead>
	<?php if ( ! empty( $items ) ): ?>
        <tbody>
		<?php foreach ( $items as $plan ): ?>
            <tr>
                <td colspan="2">

                    <a href="<?php echo esc_url_raw( $plan->get_edit_url( $plan->get_id(), $product->get_id() ) ); ?>">

                        <strong>

							<?php echo esc_html( $plan->get_name() ); ?>

                        </strong>

                    </a>

					<?php if ( $product->get_default_plan_id() === $plan->get_id() ): ?>

                        <span class="box-memberships-label-default">

                            <?php esc_html_e( 'Default', 'subway' ); ?>

                        </span>

					<?php endif; ?>

                    <div class="row-actions">

                        <a href="<?php echo esc_url_raw( $plan->get_edit_url( $plan->get_id(), $product->get_id() ) ); ?>"
                           class="">
		                    <?php esc_html_e( 'Edit Plan', 'subway' ); ?>
                        </a>

                        |

                        <a href="<?php echo esc_url_raw( add_query_arg( [
							'product-id' => $product->get_id(),
							'plan-id'    => $plan->get_id(),
							'action'     => 'subway_product_edit_set_default_plan'
						], admin_url( 'admin-post.php' ) ) ); ?>"
                           class="">
							<?php esc_html_e( 'Set as Default', 'subway' ); ?>
                        </a>

                    </div>
                </td>

                <td>
					<?php echo esc_html( $plan->get_displayed_price() ); ?>
                </td>

                <td>
					<?php echo esc_html( ucfirst( $plan->get_type() ) ); ?>
                </td>

                <td>
					<?php echo esc_html( ucfirst( $plan->get_status() ) ); ?>
                </td>

                <td>
					<?php echo esc_html( date( 'M d, o h:s A', strtotime( $plan->get_date_updated() ) ) ); ?>
                </td>
            </tr>
		<?php endforeach; ?>
        </tbody>
	<?php else: ?>
        <tbody>
        <tr>
            <td colspan="6">
				<?php esc_html_e( 'There are no membership plans found. Click the "Create New Plan" button to add new membership plan.', 'subway' ); ?>
            </td>
        </tr>
        </tbody>
	<?php endif; ?>
    <tfoot>
    <tr>
        <td colspan="6">
			<?php echo sprintf( esc_html__( 'Found %d Item(s)', 'subway' ), count( $items ) ); ?>
        </td>
    </tr>
    </tfoot>
</table>

