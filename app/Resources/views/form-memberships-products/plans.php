<?php
$plans = new \Subway\Memberships\Plan\Plan();
$items = $plans->get_plans( [ 'product_id' => $product->get_id() ] );
?>

<p>
    <a href="<?php echo esc_url_raw( $plans->get_add_url( $product->get_id() ) ); ?>" class="button button-large button-primary">
        <span>&plus;</span>
		<?php esc_html_e('Create New Plan', 'subway'); ?>
    </a>
</p>

<?php if ( ! empty( $items ) ): ?>
    <table class="wp-list-table widefat fixed striped" id="boxmembership-product-plans-table">
        <thead>
        <tr>
            <th colspan="5">
                <strong>
                    <?php esc_html_e('Membership Plans','subway'); ?>
                </strong>
            </th>
        </tr>
        <tr>
            <th><?php esc_html_e( 'Plan Name', 'subway' ); ?></th>
            <th><?php esc_html_e( 'Displayed Price', 'subway' ); ?></th>
            <th><?php esc_html_e( 'Type', 'subway' ); ?></th>
            <th><?php esc_html_e( 'Status', 'subway' ); ?></th>
            <th><?php esc_html_e( 'Updated', 'subway' ); ?></th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ( $items as $plan ): ?>
            <tr>
                <td>
                    <a href="<?php echo esc_url_raw( $plan->get_edit_url( $plan->get_id(), $product->get_id() ) ); ?>">
                        <strong>
							<?php echo esc_html( $plan->get_name() ); ?>
                        </strong>
                    </a>
                    <div class="row-actions">
                        <a href="<?php echo esc_url_raw( $plan->get_edit_url( $plan->get_id(), $product->get_id() ) ); ?>" class="">
                            <?php esc_html_e('Edit', 'subway'); ?>
                        </a>
                        |
                        <a href="<?php echo esc_url_raw( $plan->get_edit_url( $plan->get_id(), $product->get_id() ) ); ?>" class="">
	                        <?php esc_html_e('View', 'subway'); ?>
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
					<?php echo esc_html( ucfirst($plan->get_status() )); ?>
                </td>

                <td>
					<?php echo esc_html( $plan->get_date_updated() ); ?>
                </td>
            </tr>
		<?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="5">
               <?php echo sprintf( esc_html__('Found %d Item(s)', 'subway'), count( $items ) ); ?>
            </td>
        </tr>
        </tfoot>
    </table>
<?php endif; ?>
