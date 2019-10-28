<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="box-membership-product">

	<?php if ( $product ): ?>

        <div class="box-membership-product-wrap">
            <div class="subway-flex-wrap" id="box-membership-product-header">
                <div class="subway-flex-column-100">
                    <div class="box-membership-product-preview">
                        <img src="<?php echo esc_url( $product->get_preview_image_url() ); ?>"
                             alt="<?php echo esc_attr( $product->get_name() ); ?>"/>

                    </div>
                </div>

            </div>

            <div class="subway-flex-wrap" id="box-membership-product-details">
                <div class="box-membership-product-details">
                    <div class="box-membership-product-title">
                        <h2 class="box-membership-product-title-h subway-mg-top-zero">
							<?php echo esc_html( $product->get_name() ); ?>
                        </h2>
                    </div>
					<?php echo wp_kses_post( wpautop( $product->get_description() ) ); ?>
                </div>
            </div>

            <div class="subway-flex-wrap">
				<?php if ( $plans ): ?>
					<?php $default_plan = $plan->get_plan( $product->get_default_plan_id() ); ?>

                    <div class="subway-flex-column-60">
                        <h3 class="subway-mg-top-zero">
							<?php echo esc_html( $default_plan->get_name() ); ?>
                        </h3>
                        <h4>
							<?php echo esc_html( $default_plan->get_displayed_price() ); ?>
                            /
							<?php echo esc_html( $default_plan->get_type() ); ?>
                            <br/>
                            <small>
								<?php echo esc_html( sprintf( __( 'SKU: %s', 'subway' ), $default_plan->get_sku() ) ); ?>
                            </small>
                        </h4>

                        <div id="product-plan-description">
                            <?php echo wp_kses_post( wpautop( $default_plan->get_description() ) ); ?>
                        </div>
                    </div>
                    <div class="subway-flex-column-40">
                        <div class="subway-pd-left-20">
                            <div class="box-membership-product-plans">
								<?php $this->render( 'product-plans', [
									'plans'   => $plans,
									'plan'    => $plan,
									'product' => $product
								], false, 'shortcodes' ); ?>
                            </div>
                        </div>
                    </div>
				<?php else: ?>
                    <div class="subway-flex-column-100">
                        <div class="subway-alert-warning subway-alert">
                            <p>
								<?php esc_html_e( 'There are no membership plans yet.', 'subway' ); ?>
                            </p>
                        </div>
                        <p>
                            <a href="<?php echo esc_url( $options->get_membership_page_url() ); ?>"
                               title="<?php esc_attr_e( 'Browse Membership Products', 'subway' ); ?>" class="sw-button">
								<?php esc_html_e( 'Browse Membership Products', 'subway' ); ?>
                            </a>
                        </p>
                    </div>
				<?php endif; ?>
            </div>

        </div>

	<?php else: ?>

        <div class="subway-alert subway-alert-info">
            <p>
				<?php esc_html_e( 'Error: Product not found', 'box-membership' ); ?>
            </p>
        </div>

        <a class="sw-button" title="<?php esc_attr_e( 'Select Products', 'subway' ); ?>"
           href="<?php echo esc_url( $options->get_membership_page_url() ); ?>">
			<?php esc_html_e( 'Select Products', 'subway' ); ?>
        </a>

	<?php endif; ?>
</div>

