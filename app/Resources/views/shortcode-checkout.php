<?php
$product_id = filter_input( INPUT_GET, 'product_id', 519 );
$user = wp_get_current_user();
?>
<div id="subway-checkout-wrap">

    <div id="subway-checkout-inner-wrap">

        <form class="sw-form" action="" method="POST">

            <input type="hidden" name="sw-action" value="checkout"/>

            <input type="hidden" name="sw-product-id" value="<?php echo absint( $product_id ); ?>"/>

            <div class="subway-checkout-user-info">
                <div class="subway-checkout-user-info-title">
                    <h3>
						<?php esc_html_e( 'Review Order', 'subway' ); ?>
                    </h3>
                </div>

                <div class="subway-checkout-user-info-table-wrap">
                    <table class="subway-checkout-user-info-table">
                        <thead>
                        <tr>
                            <th colspan="2">You are logged-in as</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Name</td>
                            <td>
								<?php echo get_avatar( get_current_user_id(), 40 ); ?><br/>
                                <?php echo esc_html( $user->display_name ); ?>
                                <br/>
                                <a href="<?php echo esc_url( wp_logout_url() ); ?>">
                                    (Not You?) Logout
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Email Address</td>
                            <td>
	                            <?php echo esc_html( $user->user_email ); ?>
                                <br/>
                                <a href="http://multisite.local/my-account/">(Update Email Address) </a>
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
            </div><!--.subway-checkout-user-info-->

            <div class="subway-checkout-review-order">

				<?php $view->render( 'checkout-table', [ 'product' => $product, 'currency' => $currency ] ); ?>

            </div><!--.subway-checkout-review-order-->


            <div class="subway-checkout-place-order">
                <button type="submit" class="sw-button subway-checkout-place-order-button">
					<?php esc_html_e( 'Place Order', 'subway' ); ?>
                </button>
            </div><!--.subway-checkout-place-order-->

            <div class="subway-checkout-gateway-banner">

                <p>
                    You will be redirected to PayPal website to complete the payment.<br/>
                    <img width="200" src="https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg"/>
                </p>

            </div><!--.subway-checkout-gateway-banner-->

        </form>

    </div><!--.subway-checkout-inner-wrap-->

</div><!--.subway-checkout-wrap-->

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
