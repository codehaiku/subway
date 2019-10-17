
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
<div class="subway-user-invoice">
    <div class="subway-flex-wrap">
        <div class="subway-flex-column-50">
            <!--Invoice Merchant-->
            <h3 class="subway-invoice-merchant-name">Envato Elements Pty Ltd</h3>
            <ul class="subway-invoice-list-details">
                <li>Collins Street West</li>
                <li>Melbourne, Victoria 8007</li>
                <li>Australia</li>
                <li>Email: help.elements@envato.com</li>
                <li>VAT #: EU826463953</li>
                <li>Registration #: 87 613 824 258</li>
            </ul>
            <!--Invoice Merchant End-->
        </div>
        <div id="subway-invoice-info" class="subway-flex-column-50">
            <!--Invoice Merchant-->
            <h3 id="subway-invoice-info-header">Invoice</h3>

            <ul class="subway-invoice-list-details">
                <li><?php echo sprintf( __('Invoice ID: %s', 'subway'), $order->invoice_number ); ?></li>
                <li><?php echo sprintf( __('Billed On: %s', 'subway'), $order->created ); ?></li>
                <li><?php echo sprintf( __('Due On: %s', 'subway'), $order->created ); ?></li>
            </ul>
            <!--Invoice Merchant End-->
        </div>
    </div>
    <div class="subway-flex-wrap">
        <div id="subway-invoice-customer" class="subway-flex-column-50">
            <!--Invoice Merchant-->
            <h4 id="subway-invoice-customer-heading">Bill To</h4>
            <ul class="subway-invoice-list-details">
                <li><h4 id="subway-invoice-customer-name">Joseph Gabito</h4></li>
                <li>3rd Road #4 Jupiter Street, Puentebella Subdivision</li>
                <li>Bacolod City, Negros Occidental 6100</li>
                <li>Philippines</li>
            </ul>
            <!--Invoice Merchant End-->
        </div>
        <div class="subway-flex-column-50">
            <!--Invoice Merchant-->
            <div id="subway-invoice-payment-status">
                <h3>Paid</h3>
                <h4 id="subway-invoice-payment-status-date">Aug 23, 2019</h4>
                <h4 id="subway-invoice-payment-status-amount">$19.00</h4>
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
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Sub Total</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="2">Membership - Elements $19</td>
                    <td>1</td>
                    <td>$19.00</td>
                    <td>$19.00</td>
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
                    <td>$19.00</td>
                </tr>
                <tr>
                    <td>Tax (10%)</td>
                    <td>$2.00</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>$21.00</td>
                </tr>
                <tr>
                    <td>Paid</td>
                    <td>($21.00)</td>
                </tr>
                <tr>
                    <td>Amount Due</td>
                    <td>$0.00</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="subway-flex-wrap">
        <div class="subway-flex-column-100">
            <h5>Payments</h5>
            Aug 23, 2019 $19.00 Payment from PayPal
            <h5>Notes</h5>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi id vehicula ex, at fringilla felis. Donec
                pellentesque, nulla at ultricies dictum, tortor velit dapibus nisl, id dapibus nibh turpis vitae augue.
                Interdum et malesuada fames ac ante ipsum primis in faucibus.
            </p>
        </div>
    </div>
</div>