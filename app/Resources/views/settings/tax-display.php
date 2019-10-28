<?php
/**
 * Template for showing tax on listing price.
 */
?>

<p class="howto">
    <label>

        <input
			<?php checked( '1', $subway_display_tax, true ); ?>
                name="subway_display_tax"
                value="1" type="checkbox"
        />
		<?php esc_html_e( 'Check to include the tax rate on listing price.', 'subway' ); ?>
    </label>

</p>