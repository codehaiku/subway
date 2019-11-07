<?php $is_trial = filter_input( 1, 'is_trial', 516 ); ?>
<?php $pricing = $plan->get_pricing(); ?>
<?php $checkout->set_plan( $plan ); ?>

<?php if ( ! empty( $is_trial ) ): ?>
	<?php $checkout->set_is_trial( true ); ?>
<?php endif; ?>

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

            <h4 class="subway-mg-bot-zero subway-mg-top-zero">
				<?php echo $checkout->get_plan()->get_product_link(); ?>
            </h4>

            <h3 class="subway-mg-bot-zero subway-mg-top-zero">
				<?php echo esc_html( $checkout->get_plan()->get_name() ); ?>
            </h3>

            <span class="product-plan-pricing-text">
                <?php echo esc_html( $checkout->get_plan()->get_pricing()->get_text( $plan ) ); ?>
            </span>

        </td>

        <td>
            <strong>
				<?php echo esc_html( $checkout->get_price() ); ?>
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
			<?php echo esc_html( $checkout->get_subtotal() ); ?>
        </td>
    </tr>

	<?php if ( $checkout->get_tax_rate() > 0 ): ?>
        <tr>
            <td>
				<?php esc_html_e( 'Tax', 'subway' ); ?>
            </td>
            <td>
				<?php echo esc_html( sprintf( '%s%%', $checkout->get_tax_rate() ) ); ?>
            </td>
        </tr>
	<?php endif; ?>

    <tr>
        <td>
			<?php esc_html_e( 'Total', 'subway' ); ?>
        </td>
        <td>
            <strong>
				<?php echo esc_html( $checkout->get_total() ); ?>
            </strong>
        </td>
    </tr>
    </tfoot>
</table>