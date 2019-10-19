<?php $order = $invoice['order']; ?>
<?php $details = $invoice['details']; ?>

<?php if ( ! is_user_logged_in() ): ?>
    <div class="subway-login-form-message">
        <p class="error">
			<?php esc_html_e( 'Please login to view your account. ', 'subway' ); ?>
            <a href="<?php echo esc_url( wp_login_url() ); ?>" title="<?php echo esc_attr( 'Login', 'subway' ); ?>">
				<?php echo esc_html( __( 'Login', 'subway' ) ); ?>
            </a>
            &middot;
            <a href="<?php echo esc_url( wp_registration_url() ); ?>"
               title="<?php esc_attr( 'Register', 'subway' ); ?>">
				<?php echo esc_html( __( 'Register', 'subway' ) ); ?>
            </a>
        </p>
    </div>
	<?php return; ?>
<?php endif; ?>

<!--Start Invoice-->
<div id="subway-user-invoice" class="subway-user-invoice">

    <div class="subway-flex-wrap">
        <div class="subway-flex-column-50">
            <!--Invoice Merchant-->
            <h3 class="subway-invoice-merchant-name">
				<?php echo esc_html( get_option( 'subway_seller_name', '' ) ); ?>
            </h3>
            <ul class="subway-invoice-list-details">
                <li><?php echo esc_html( get_option( 'subway_seller_address_line1', '' ) ); ?></li>
                <li><?php echo esc_html( get_option( 'subway_seller_address_line2', '' ) ); ?></li>
                <li>
					<?php echo esc_html( get_option( 'subway_seller_city', '' ) ); ?>&nbsp;
					<?php echo esc_html( get_option( 'subway_seller_postal_code', '' ) ); ?>
                </li>
                <li><?php echo esc_html( get_option( 'subway_seller_country', '' ) ); ?></li>
                <li>Email: <?php echo esc_html( get_option( 'subway_seller_email', '' ) ); ?></li>
                <li>VAT #: <?php echo esc_html( get_option( 'subway_seller_vat', '' ) ); ?></li>
                <li>Registration #: <?php echo esc_html( get_option( 'subway_seller_registration_number', '' ) ); ?>
            </ul>
            <!--Invoice Merchant End-->
        </div>
        <div id="subway-invoice-info" class="subway-flex-column-50">
            <!--Invoice Merchant-->
            <h3 id="subway-invoice-info-header">Invoice</h3>

            <ul class="subway-invoice-list-details">
                <li><?php echo esc_html( sprintf( __( 'Invoice ID: %s', 'subway' ), $order->invoice_number ) ); ?></li>
                <li><?php echo esc_html( sprintf( __( 'Billed On: %s', 'subway' ), $order->created ) ); ?></li>
                <li><?php echo esc_html( sprintf( __( 'Due On: %s', 'subway' ), $order->created ) ); ?></li>
            </ul>
            <!--Invoice Merchant End-->
        </div>
    </div>
    <div class="subway-flex-wrap">
        <div id="subway-invoice-customer" class="subway-flex-column-50">
            <!--Invoice Merchant-->
            <h4 id="subway-invoice-customer-heading">Bill To</h4>

            <ul class="subway-invoice-list-details">
                <li>
                    <h4 id="subway-invoice-customer-name">
						<?php echo esc_html( $details->gateway_customer_name ); ?>
						<?php echo esc_html( $details->gateway_customer_lastname ); ?>
                    </h4>
					<?php echo esc_html( $details->gateway_customer_email ); ?>
                </li>
                <li>
					<?php echo esc_html( $details->gateway_customer_address_line_1 ); ?>
                </li>
                <li>
					<?php echo esc_html( $details->gateway_customer_address_line_2 ); ?>
                </li>
                <li>
					<?php echo esc_html( $details->gateway_customer_city ); ?>
					<?php if ( ! empty( $details->gateway_customer_postal_code ) ): ?>
                        , <?php echo esc_html( $details->gateway_customer_postal_code ); ?>
					<?php endif; ?>
                </li>
                <li>
					<?php echo esc_html( $details->gateway_customer_state ); ?>
					<?php if ( ! empty( $details->gateway_customer_country ) ): ?>
                        , <?php echo esc_html( $details->gateway_customer_country ); ?>
					<?php endif; ?>
                </li>
            </ul>
            <!--Invoice Merchant End-->
        </div>

		<?php
		$products = new \Subway\Memberships\Plan\Plan();
		$plan     = $products->get_plan( $invoice['order']->product_id );
		?>
        <div class="subway-flex-column-50">
            <!--Invoice Merchant-->
            <div id="subway-invoice-payment-status">
                <h3>Paid</h3>
                <h4 id="subway-invoice-payment-status-date">
					<?php echo esc_html( $plan->get_date_created() ); ?>
                </h4>
                <h4 id="subway-invoice-payment-status-amount">
					<?php echo esc_html( $plan->get_taxed_price() ); ?>
                </h4>
            </div>
            <!--Invoice Merchant End-->
        </div>
    </div>

    <div class="subway-flex-wrap">
        <div class="subway-flex-column-100">
            <table class="subway-membership-lists">
                <thead>
                <tr>
                    <th>Description</th>
                    <th></th>
                    <th>Price</th>
                    <th>Sub Total</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="2">
						<?php echo esc_html( $plan->get_name() ); ?>
                    </td>
                    <td>
						<?php echo esc_html( $plan->get_displayed_price_without_tax() ); ?>
                    </td>
                    <td id="subway-invoice-row-subtotal">
						<?php echo esc_html( $plan->get_displayed_price_without_tax() ); ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="subway-flex-wrap">
        <div class="subway-flex-column-60">
        </div>
        <div class="subway-flex-column-40">
            <table id="subway-invoice-numbers">
                <tr>
                    <td>Subtotal</td>
                    <td><?php echo esc_html( $plan->get_displayed_price_without_tax() ); ?></td>
                </tr>
                <tr>
                    <td>Tax (<?php echo esc_html( $plan->get_tax_rate() ); ?>%)</td>
                    <td><?php echo esc_html( $plan->get_tax_amount() ); ?></td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td><?php echo esc_html( $plan->get_taxed_price() ); ?></td>
                </tr>
                <tr>
                    <td>Paid</td>
                    <td>(<?php echo esc_html( $plan->get_taxed_price() ); ?>)</td>
                </tr>
                <tr>
                    <td>Amount Due</td>
                    <td>
						<?php echo esc_html( $order->currency ); ?>
                        0.00
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="subway-flex-wrap">
        <div class="subway-flex-column-100">
            <h5>Payments</h5>
			<?php echo $order->created ?>
			<?php echo $plan->get_displayed_price_without_tax() ?>
            Payment from
			<?php $order->gateway; ?>

            <h5>Notes</h5>
            <p>
                @todo.
            </p>
        </div>
    </div>
</div>
<div id="subway-invoice-customer-actions">
    <p>
        <a class="sw-button" href="#" id="subway-invoice-button-print" title="<?php esc_attr_e( 'Print Invoice', 'subway' ); ?>">
			<?php esc_html_e( 'Print Invoice', 'subway' ); ?>
        </a>
        <?php $options = new \Subway\Options\Options(); ?>
        <?php $accounts_url = $options->get_accounts_page_url(); ?>
        <a class="sw-button" href="<?php echo esc_url( $accounts_url ); ?>" id="subway-invoice-button-dashboard" title="<?php esc_attr_e( 'Go to Dashboard', 'subway' ); ?>">
		    <?php esc_html_e( 'My Dashboard', 'subway' ); ?>
        </a>
    </p>
</div>
<script>
    jQuery(document).ready(function ($) {
        'use strict';

        $('#subway-invoice-button-print').on('click', function (e) {
            e.preventDefault();
            subway_invoice_print_element('subway-user-invoice');
        });

        var subway_invoice_print_element = function (elem) {

            var print_window = window.open('', 'PRINT', 'height=400,width=600');

            print_window.document.write('<html><head><title>' + document.title + '</title>');
            print_window.document.write('</head><body >');
            print_window.document.write('<h1>' + document.title + '</h1>');
            print_window.document.write(document.getElementById(elem).innerHTML);
            print_window.document.write('</body></html>');

            print_window.document.close(); // necessary for IE >= 10
            print_window.focus(); // necessary for IE >= 10*/

            print_window.print();
            print_window.close();

            return true;
        }
    });


</script>