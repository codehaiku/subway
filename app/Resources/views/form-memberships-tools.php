<div class="wrap">

    <h1 class="wp-heading-inline">

        <?php esc_html_e( 'Tools', 'subway' ); ?></h1>


    <hr class="wp-header-end">
    <?php $repaired = filter_input(1, 'repaired',516); ?>

    <?php if ( ! empty( $repaired ) ): ?>
    <div id="message" class="updated notice is-dismissible">
        <p>
            <?php esc_html_e('Successfully updated the count record of the selected component.', 'subway'); ?>
        </p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text">
                <?php esc_html_e('Dismiss this notice', 'subway'); ?>
            </span>
        </button>
    </div>
    <?php endif; ?>
    <h3>
		<?php esc_html_e( 'Repair Counts', 'subway' ); ?>
    </h3>

    <p class="lead">
		<?php esc_html_e( 'Use the tool below to repair the total count 
        of each corresponding components.', 'subway' ); ?>
    </p>

    <p class="lead">
        <strong>
		<?php esc_html_e( 'NOTE: This tool runs expensive database query. Please run each repair tool one at a time.', 'subway' ); ?>
        </strong>
    </p>

    <table class="wp-list-table widefat fixed striped">
        <tr>
            <th>
                <strong>
	                <?php esc_html_e('Component', 'subway'); ?>
                </strong>
            </th>
            <th>
                <strong><span class="dashicons dashicons-hammer" style="margin-top: -2.5px;"></span>
			        <?php esc_html_e('Actions', 'subway'); ?>
                </strong>
            </th>
        </tr>
        <!--.Products-->
        <tr>
            <td>
                <?php esc_html_e('Membership Plans', 'subway'); ?>
            </td>
            <td>
                <form method="POST" action="<?php echo admin_url('admin-post.php'); ?>">
                    <input type="hidden" name="action" value="repair_products_count" />
                    <button type="submit" class="button button-primary button-large">
			            <?php esc_html_e('Repair Plan Count', 'subway'); ?>
                    </button>
                </form>
            </td>
        </tr>
        <!--.Products End-->
        <!--.Products-->
        <tr>
            <td>
	            <?php esc_html_e('Orders', 'subway'); ?>
            </td>
            <td>
                <form method="POST" action="<?php echo admin_url('admin-post.php'); ?>">
                    <input type="hidden" name="action" value="repair_orders_count" />
                    <button type="submit" class="button button-primary button-large">
			            <?php esc_html_e('Repair Orders Count', 'subway'); ?>
                    </button>
                </form>
            </td>
        </tr>
        <!--.Products End-->
    </table>

</div>