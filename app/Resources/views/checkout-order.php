<table class="subway-checkout-review-order">
    <thead>
    <tr>
        <th colspan="2">
			<?php esc_html_e( 'Selected Memberships Plan', 'subway' ); ?>
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <strong>
                <h3 class="subway-mg-bot-zero subway-mg-top-zero"><?php echo $plan->get_product_link(); ?>
                </h3>
                <strong>
					<?php echo esc_html( $plan->get_name() ); ?>
                </strong>
            </strong>
            <a href="<?php echo esc_url( $options->get_membership_page_url() ); ?>"
               title="<?php esc_attr_e( '(Change)', 'subway' ); ?>">
				<?php esc_html_e( '(Change)', 'subway' ); ?>
            </a>
            <?php $pricing = new \Subway\Memberships\Plan\Pricing\Controller(); ?>
	        <?php $pricing->set_plan_id( $plan->get_id() ); ?>
	        <?php $pricing = $pricing->get(); ?>
	        <?php if ( $pricing ): ?>
                <span class="product-plan-pricing-text">
                    <?php echo esc_html( $pricing->get_text( $plan ) ); ?>
                </span>
	        <?php endif; ?>
        </td>
        <td>
            <strong>
				<?php echo esc_html( $plan->get_displayed_price_without_tax() ); ?>
            </strong>
        </td>
    </tr>
    </tbody>

    <tfoot>
    <tr>
        <td>
			<?php esc_html_e( 'Subtotal', 'subway' ); ?>
        </td>
        <td>
			<?php echo esc_html( $plan->get_displayed_price_without_tax() ); ?>
        </td>
    </tr>

    <tr>
        <td>
			<?php esc_html_e( 'Tax', 'subway' ); ?>
        </td>
        <td>
			<?php echo esc_html( sprintf( '%s%%', $plan->get_tax_rate() ) ); ?>
        </td>
    </tr>

    <tr>
        <td>
			<?php esc_html_e( 'Total', 'subway' ); ?>
        </td>
        <td>
            <strong>
				<?php echo esc_html( $plan->get_taxed_price() ); ?>
            </strong>
        </td>
    </tr>
    </tfoot>
</table>