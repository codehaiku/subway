<?php
if ( ! defined('ABSPATH') ) {
    return;
}
?>



<div id="subway-memberships">
	<?php if ( ! empty ( $products ) ): ?>
        <table class="subway-membership-lists">
            <thead>
                <tr>
                    <th colspan="2">Membership Plans</th>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Price</th>

                </tr>
            </thead>
            <tbody>
            <?php foreach( $products as $list_product ): ?>
                <tr>
                    <td><?php echo esc_html( $list_product['name'] ); ?></td>
                    <td>
                        USD $<?php echo esc_html( $list_product['amount'] ); ?>

                    </td>
                    <td style="text-align: right;">
                        <a class="sw-button" href="<?php echo esc_url( $product->get_product_checkout_url( $list_product['id']) ); ?>">
		                    <?php esc_html_e('Select Membership', 'subway'); ?>
                        </a>
                    </td>

                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="sw-form-errors">
            <p class="sw-error">
	            <?php esc_html_e('There are no memberships product available at the moment.', 'subway'); ?>
            </p>
        </div>
    <?php endif; ?>
</div>