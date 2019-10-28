<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="box-membership-product">

	<?php if ( $product ): ?>

        <div class="box-membership-product-wrap">
            <div class="subway-flex-wrap">
                <div class="subway-flex-column-50">
                    <div class="box-membership-product-preview">
                        <img src="<?php echo esc_url( $product->get_preview_image_url() ); ?>"
                             alt="<?php echo esc_attr( $product->get_name() ); ?>"/>

                    </div>
                </div>
                <div class="subway-flex-column-50">
                    <div class="subway-pd-left-20">
                        <div class="box-membership-product-title">
                            <h2 class="page-title subway-mg-top-zero">
								<?php echo esc_html( $product->get_name() ); ?>
                            </h2>
                        </div>
                        <div class="box-membership-product-plans">
							<?php $this->render( 'product-plans', [ 'plans' => $plans, 'plan' => $plan ], false, 'shortcodes' ); ?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="box-membership-product-details">
                <h2>
					<?php esc_html_e( 'Description', 'subway' ); ?>
                </h2>
				<?php echo wp_kses_post( $product->get_description() ); ?>
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

