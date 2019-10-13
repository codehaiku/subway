
<div class="wrap">

    <h1 class="wp-heading-inline">

        <?php esc_html_e( 'Orders Details ID: ', 'subway' ); ?>

        <?php echo esc_html( $order_id ); ?>

    </h1>

	<?php do_action( 'before-form-memberships-orders-edit' ); ?>

	<?php $details = $order_details->get( $order_id ); ?>

    <?php if ( isset( $_GET['success'] ) ): ?>

        <div class="notice notice-success is-dismissible">

            <p>
                <?php _e( 'Customer details successfully updated.', 'subway' ); ?>
            </p>

        </div>

    <?php endif; ?>

	<?php if ( ! empty( $details ) ): ?>

        <h3>
            <?php echo sprintf( esc_html__( 'Payment Gateway: %s', 'subway' ), $details->gateway_name ); ?>
        </h3>

        <div id="order-details">
            <div style="width: 48%; margin-right: 2%; float: left;">
                <table class="widefat striped">
                    <thead>
                    <tr>
                        <th colspan="2">
                            <h3 style="margin:10px 0"><?php esc_html_e( 'Order Information', 'subway' ); ?>
                            </h3>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Order ID</td>
                        <td><?php echo esc_html( $order->id ); ?></td>
                    </tr>
                    <tr>
                        <td>Invoice Number</td>
                        <td><?php echo esc_html( $order->invoice_number ); ?></td>
                    </tr>

                    <tr>
	                    <?php
	                    $products = new \Subway\Memberships\Products\Products();
	                    $product = $products->get_product( $order->product_id );
	                    ?>
                        <td>Product/Plan ID</td>
                        <td><?php echo esc_html( $product->get_name() ); ?></td>
                    </tr>
                    <tr>
                        <td>Customer ID</td>
                        <td><?php echo esc_html( $order->user_id ); ?></td>
                    </tr>
                    <tr>
                        <td>Amount</td>
                        <td><?php echo esc_html( $product->get_real_amount() ); ?></td>
                    </tr>
                    <tr>
                        <td>Displayed Amount</td>
                        <td><?php echo esc_html( $product->get_displayed_price_without_tax() ); ?></td>
                    </tr>
                    <tr>
                        <td>Tax Rate</td>
                        <td><?php echo esc_html( $product->get_tax_rate() ); ?>%</td>
                    </tr>
                    <tr>
                        <td>Displayed Total Amount</td>
                        <td><?php echo esc_html( $product->get_displayed_price() ); ?></td>
                    </tr>
                    <tr>
                        <td>Currency</td>
                        <td><?php echo esc_html( $order->currency ); ?></td>
                    </tr>
                    <tr>
                        <td>Vat Number</td>
                        <td><?php echo esc_html( $order->customer_vat_number ); ?></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td><?php echo esc_html( $order->status ); ?></td>
                    </tr>
                    <tr>
                        <td>Created</td>
                        <td><?php echo esc_html( $order->created ); ?></td>
                    </tr>
                    <tr>
                        <td>Last Updated</td>
                        <td><?php echo esc_html( $order->last_updated ); ?></td>
                    </tr>
                    </tbody>
                </table>
                <p>
                    <?php esc_html_e( 'Payment has been made using the IP Address:', 'subway' ); ?>
                    <?php echo esc_html( $order->ip_address ); ?>
                </p>

            </div>

            <!-- Billing Details -->
            <div style="width: 50%; float: left;">
                <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">

                    <input type="hidden" name="action" value="subway_order_edit"/>
                    <input type="hidden" name="order_details_id" value="<?php echo esc_attr( $details->id ); ?>"/>

                    <table class="widefat striped">
                        <thead>
                        <tr>
                            <th colspan="2">
                                <h3 style="margin:10px 0"><?php esc_html_e( 'Customer Details', 'subway' ); ?>
                                </h3>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ( $details as $key => $detail ): ?>
                            <?php if ( ! in_array( $key, $excluded_fields ) ): ?>
                                <tr>
                                    <?php $label = ucwords( str_replace( '_', ' ', $key ) ); ?>
                                    <?php $label = str_replace( 'Gateway', '', $label ); ?>
                                    <?php $label = str_replace( 'Customer', '', $label ); ?>
                                    <td><strong><?php echo esc_html( $label ); ?></strong></td>
                                    <td><input class="widefat" type="text" name="<?php echo esc_attr( $key ); ?>"
                                               value="<?php echo esc_attr( $detail ); ?>"/></td>
                                </tr
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="2" style="text-align: right;">
                                <input class="button button-large button-primary" type="submit"
                                       value="Update Order Details"/>
                                <a href="#" id="print-details" class="button button-large">Print Order Details</a>
                            </th>
                        </tr>
                        </tfoot>
                    </table>
                </form>
            </div>

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