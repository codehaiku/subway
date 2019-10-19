<?php
$product_id = filter_input( INPUT_GET, 'product_id', 519 );
$user       = wp_get_current_user();
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
                            <th colspan="2">
								<?php esc_html_e( 'Personal Information', 'subway' ); ?>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
								<?php esc_html_e( 'Name', 'subway' ); ?>
                            </td>
                            <td>
                                <div class="subway-flex-wrap">
                                    <div class="subway-flex-column-20">
										<?php echo get_avatar( get_current_user_id(), 40 ); ?><br/>
                                    </div>
                                    <div class="subway-flex-column-80">

										<?php echo esc_html( $user->display_name ); ?>
                                        <br/>
                                        <a title="<?php esc_attr_e( '(Not You?) Logout', 'subway' ); ?>"
                                           href="<?php echo esc_url( wp_logout_url() ); ?>">
											<?php esc_html_e( '(Not You?) Logout', 'subway' ); ?>
                                        </a>

                                    </div>
                                </div>


                            </td>
                        </tr>
                        <tr>
                            <td>
								<?php esc_html_e( 'Email Address', 'subway' ); ?>
                            </td>
                            <td>
								<?php echo esc_html( $user->user_email ); ?>
                                <br/>
                                <a href="<?php echo esc_url( add_query_arg( 'edit', 'profile', $options->get_accounts_page_url() ) ); ?>"
                                   title="<?php esc_attr_e( 'Update Email Address', 'subway' ); ?>">
									<?php esc_html_e( 'Update Email Address', 'subway' ); ?>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php esc_html_e('Current Memberships Plan', 'subway'); ?>
                            </td>
                            <td>
                                <strong>
                                    <a title="<?php echo esc_attr( $product->get_name() ); ?>" href="<?php echo esc_url( $product->get_plan_url() ); ?>">
                                        <?php echo esc_html( $product->get_name() ); ?>
                                    </a>
                                </strong>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div><!--.subway-checkout-user-info-->

            <div class="subway-checkout-review-order-wrap">

				<?php $view->render( 'checkout-table', [ 'product' => $product, 'currency' => $currency, 'options' => $options ] ); ?>

            </div><!--.subway-checkout-review-order-->

        </form>

    </div><!--.subway-checkout-inner-wrap-->

</div><!--.subway-checkout-wrap-->

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
