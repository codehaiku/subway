<div id="subway-checkout-wrap">

    <div id="subway-checkout-inner-wrap">


        <div class="subway-checkout-user-info">
            <div class="subway-checkout-user-info-title">
                <h3>
			        <?php esc_html_e( 'Review Order', 'subway' ); ?>
                </h3>
            </div>

            <div class="subway-checkout-user-info-table-wrap">
                <table class="subway-checkout-user-info-table">
                    <thead>
                        <tr><th colspan="2">You are logged-in as</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Name</td>
                            <td>
                                <?php echo get_avatar( get_current_user_id(), 40 ); ?><br/>
                                Joseph Gabito<br/>
                                <a href="<?php echo esc_url( wp_logout_url()); ?>">
                                    (Not You?) Logout
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Email Address</td>
                            <td>
                                Joseph@dunhakdis.com<br/>
                                <a href="http://multisite.local/my-account/">(Update Email Address)  </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Current Membership Plan</td>
                            <td>
                                <strong>
                                <a href="#">
                                    Subway Pro
                                </a>
                                </strong>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

        <div class="subway-checkout-review-order">

            <div class="subway-checkout-review-order-table">
                <table>

                    <thead>
                    <tr>
                        <th colspan="2">
	                        <?php esc_html_e( 'Change Membership Plan', 'subway' ); ?>
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td>
							<strong>
								<?php echo esc_html( $product->name ); ?>
                            </strong>
                            <a href="#">
                                (Change)
                            </a>
                        </td>
                        <td>
                            <strong> USD$<?php echo esc_html( $product->amount ); ?> </strong>
                        </td>
                    </tr>
                    </tbody>

                    <tfoot>
                    <tr>
                        <td>Subtotal</td>
                        <td>USD $<?php echo esc_html( $product->amount ); ?></td>
                    </tr>

                    <tr>
                        <td>Tax</td>
                        <td>USD $0.00</td>
                    </tr>

                    <tr>
                        <td>Total</td>
                        <td><strong>USD $<?php echo esc_html( $product->amount ); ?></strong></td>
                    </tr>

                    </tfoot>

                </table>
            </div>

            <div class="subway-checkout-place-order">
                <button type="button" class="sw-button subway-checkout-place-order-button">
					<?php esc_html_e( 'Place Order', 'subway' ); ?>
                </button>
            </div>

            <div class="subway-checkout-gateway-banner">

               <p>
                   You will be redirected to PayPal website to complete the payment.<br/>
                   <img width="200" src="https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg"/>
               </p>

            </div>
        </div><!--.subway-checkout-review-order-->


    </div><!--.subway-checkout-inner-wrap-->
</div><!--.subway-checkout-wrap-->