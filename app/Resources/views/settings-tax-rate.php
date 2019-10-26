<?php
/**
 * Settings Tax Rate Template
 */
?>
<input style="width:80px" step="0.01"
       type="number" name="subway_tax_rate"
       value="<?php echo esc_attr( floatval( $tax_rate ) ); ?>"
       max="100" placeholder="0.00" size="4"/> %
<p class="howto">
	<?php esc_html_e( 'Enter the percentage of tax you would like to charge on top of the listing price.', 'subway' ); ?>
    <strong>
		<?php esc_html_e( 'No need to add %', 'subway' ); ?>
    </strong>
</p>
