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
                    <div class="subway-flex-column-60">
                        <h3 class="subway-mg-top-zero">
                            Developer
                        </h3>
                        <h4>
                            $8.80 / recurring<br/>
                            <small>
                                SKU: MEMBERPROD1
                            </small>
                        </h4>

                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pharetra elit a consectetur
                        ultricies.
                        Donec a convallis nulla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
                        inceptos himenaeos. Fusce hendrerit ornare erat quis dapibus. Donec placerat eu erat vitae
                        congue. Etiam nunc risus, aliquet eu porttitor non, malesuada et sapien.
                    </div>
                    <div class="subway-flex-column-40">
                        <div class="subway-pd-left-20">
                            <div class="box-membership-product-plans">
								<?php $this->render( 'product-plans', [
									'plans' => $plans,
									'plan'  => $plan
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
                            <a href="<?php echo esc_url( $options->get_membership_page_url() ); ?>" title="<?php esc_attr_e( 'Browse Membership Products', 'subway' ); ?>" class="sw-button">
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

