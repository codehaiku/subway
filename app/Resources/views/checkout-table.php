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
					<?php echo esc_html( $product->name ); ?>
	            </strong>
	            <a href="#">(Change)</a>
	        </td>
	        <td>
	            <strong>
		            <?php echo $currency->format( $product->amount, get_option( 'subway_currency', 'USD' ) ); ?>
                </strong>
	        </td>
	    </tr>
    </tbody>

    <tfoot>
        <tr>
            <td>Subtotal</td>
            <td>
	            <?php echo $currency->format( $product->amount, get_option( 'subway_currency', 'USD' ) ); ?>
            </td>
        </tr>

        <tr>
            <td>Tax</td>
            <td><?php echo $currency->format( 0.00, get_option( 'subway_currency', 'USD' ) ); ?></td>
        </tr>

        <tr>
            <td>Total</td>
            <td>
                <strong>
		            <?php echo $currency->format( $product->amount, get_option( 'subway_currency', 'USD' ) ); ?>
                </strong>
            </td>
        </tr>
    </tfoot>

</table>