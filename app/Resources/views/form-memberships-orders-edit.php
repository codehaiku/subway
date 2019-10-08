<div class="wrap">

	<h1 class="wp-heading-inline">
        <?php esc_html_e( 'Orders Details: ', 'subway' ); ?>
        <?php echo esc_html(  $order_id ); ?>
    </h1>

        <?php $details = $order_details->get( $order_id ); ?>
        
    <h3><?php echo sprintf( esc_html__('Payment Gateway: %s','subway'), $details->gateway_name ); ?>  </h3>
    <?php if ( ! empty( $details ) ): ?>
    <form>
        <table class="widefat striped">
        <thead>
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
        </thead>
            <tbody>
        <?php foreach( $details as $key => $detail ): ?>
            <?php if ( ! in_array( $key, $excluded_fields ) ): ?>

                <tr>
                    <?php $label = ucwords( str_replace('_', ' ', $key) ); ?>
                    <?php $label = str_replace( 'Gateway', '', $label ); ?>
                    <td><strong><?php echo esc_html( $label ); ?></strong></td>
                    <td><input class="widefat" type="text" value="<?php echo $detail; ?>" /></td>
                </tr
            <?php endif; ?>
        <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>

                <th colspan="2">
                    <input class="button button-large button-primary" type="submit" value="Update Order Details" />
                    <a href="" class="button button-large">Print Order Details</a>
                </th>
            </tr>
            </tfoot>
        </table>
    </form>
    <?php else: ?>
        <p>
            <?php esc_html_e('There are no order details found.', 'subway'); ?>
        </p>
    <?php endif; ?>
</div>