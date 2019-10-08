<div class="wrap">

    <h1 class="wp-heading-inline">
		<?php esc_html_e( 'Orders Details: ', 'subway' ); ?>
		<?php echo esc_html( $order_id ); ?>
    </h1>

	<?php do_action( 'before-form-memberships-orders-edit' ); ?>

	<?php $details = $order_details->get( $order_id ); ?>

	<?php if ( ! empty( $details ) ): ?>
        <h3>
			<?php echo sprintf( esc_html__( 'Payment Gateway: %s', 'subway' ), $details->gateway_name ); ?>
        </h3>
        <form id="order-details">
            <table class="widefat striped">
                <thead>
                <tr>
                    <th><?php esc_html_e( 'Field', 'subway' ); ?></th>
                    <th><?php esc_html_e( 'Value', 'subway' ); ?></th>
                </tr>
                </thead>
                <tbody>
				<?php foreach ( $details as $key => $detail ): ?>
					<?php if ( ! in_array( $key, $excluded_fields ) ): ?>
                        <tr>
							<?php $label = ucwords( str_replace( '_', ' ', $key ) ); ?>
							<?php $label = str_replace( 'Gateway', '', $label ); ?>
                            <td><strong><?php echo esc_html( $label ); ?></strong></td>
                            <td><input class="widefat" type="text" value="<?php echo $detail; ?>"/></td>
                        </tr
					<?php endif; ?>
				<?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>

                    <th colspan="2">
                        <input class="button button-large button-primary" type="submit" value="Update Order Details"/>
                        <a href="#" id="print-details" class="button button-large">Print Order Details</a>
                    </th>
                </tr>
                </tfoot>
            </table>
        </form>
	<?php else: ?>
        <p>
			<?php esc_html_e( 'There are no order details found.', 'subway' ); ?>
        </p>
	<?php endif; ?>

	<?php do_action( 'after-form-memberships-orders-edit' ); ?>

</div>
<script>
    jQuery(document).ready(function ($) {
        'use strict';
        $('#print-details').on('click', function (e) {

            e.preventDefault();
            var content = $('#order-details').html();
            var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');

            WinPrint.document.write(content);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        });
    });
</script>