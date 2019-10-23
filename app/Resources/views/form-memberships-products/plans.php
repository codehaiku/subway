<?php
$plans = new \Subway\Memberships\Plan\Plan();
$items = $plans->get_plans( [ 'product_id' => $product->get_id() ] );
?>
<h3>Membership Plans</h3>
<?php if ( ! empty( $items ) ): ?>
    <table class="wp-list-table widefat fixed striped posts">
        <thead>
        <tr>
            <th><?php esc_html_e( 'Plan Name', 'subway' ); ?></th>
            <th><?php esc_html_e( 'Displayed Price', 'subway' ); ?></th>
            <th><?php esc_html_e( 'Type', 'subway' ); ?></th>
            <th><?php esc_html_e( 'Status', 'subway' ); ?></th>
            <th><?php esc_html_e( 'Updated', 'subway' ); ?></th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ( $items as $item ): ?>
            <tr>
                <td>
                    <a href="<?php echo esc_url_raw( $item->get_edit_url( $item->get_id() ) ); ?>">
                        <strong>
							<?php echo esc_html( $item->get_name() ); ?>
                        </strong>
                    </a>
                    <div class="row-actions">
                        <a href="<?php echo esc_url_raw( $item->get_edit_url( $item->get_id() ) ); ?>" class="">
                            Edit
                        </a>
                        |
                        <a href="<?php echo esc_url_raw( $item->get_edit_url( $item->get_id() ) ); ?>" class="">
                            View
                        </a>
                    </div>
                </td>

                <td>
					<?php echo esc_html( $item->get_displayed_price() ); ?>
                </td>

                <td>
					<?php echo esc_html( $item->get_type() ); ?>
                </td>

                <td>
					<?php echo esc_html( $item->get_status() ); ?>
                </td>

                <td>
					<?php echo esc_html( $item->get_date_updated() ); ?>
                </td>
            </tr>
		<?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="5">
                <a href="<?php echo esc_url_raw( $plans->get_add_url( $product->get_id() ) ); ?>" class="button button-large button-secondary">
                    <?php esc_html_e('Create New Membership Plan', 'subway'); ?>
                </a>
            </td>
        </tr>
        </tfoot>
    </table>
<?php endif; ?>
