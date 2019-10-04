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