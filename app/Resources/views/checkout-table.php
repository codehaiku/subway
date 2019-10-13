<?php if ( 'free' !== $product->get_type() ): ?>
    <table>
        <thead>
        <tr>
            <th colspan="2">
				<?php esc_html_e( 'Selected Membership Plan', 'subway' ); ?>
            </th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td>
                <strong>
					<?php echo esc_html( $product->get_name() ); ?>
                </strong>
                <a href="#">(Change)</a>
            </td>
            <td>
                <strong>
					<?php echo esc_html( $product->get_displayed_price_without_tax() ); ?>
                </strong>
            </td>
        </tr>
        </tbody>

        <tfoot>
        <tr>
            <td>Subtotal</td>
            <td>
				<?php echo esc_html( $product->get_displayed_price_without_tax() ); ?>
            </td>
        </tr>

        <tr>
            <td>Tax</td>
            <td>
				<?php echo esc_html( sprintf( '%s%%', $product->get_tax_rate() ) ); ?>
            </td>
        </tr>

        <tr>
            <td>Total</td>
            <td>
                <strong>
					<?php echo esc_html( $product->get_displayed_price() ); ?>
                </strong>
            </td>
        </tr>
        </tfoot>
    </table>

    <!-- Gateway Badge-->
    <div class="checkout-container">
        <div class="subway-checkout-place-order">
            <button type="submit" class="sw-button subway-checkout-place-order-button">
				<?php esc_html_e( 'Place Order', 'subway' ); ?>
            </button>
        </div><!--.subway-checkout-place-order-->

        <div class="subway-checkout-gateway-banner">
            <p>
				<?php esc_html_e( 'You will be redirected to PayPal website to complete the payment.', 'subway' ); ?>
            </p>
            <p>
                <img class="gateway-img" width="200"
                     src="https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg"/>
            </p>
        </div><!--.subway-checkout-gateway-banner-->
    </div>
    <!-- Gateway Badge End-->

<?php else: ?>

    <!-- Gateway Badge-->
    <div class="checkout-container">
        <div class="subway-checkout-place-order">
            <button type="submit" class="sw-button subway-checkout-place-order-button">
				<?php esc_html_e( 'Free Membership', 'subway' ); ?>
            </button>
        </div><!--.subway-checkout-place-order-->
    </div>
    <!-- Gateway Badge End-->

<?php endif; ?>