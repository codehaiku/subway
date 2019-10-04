<select name="subway_currency">
    <?php $selected = ""; ?>
    <?php foreach( $currencies as $key => $val ): ?>
        <?php if ( $selected_currency === $key ): ?>
            <?php $selected = 'selected'; ?>
        <?php else: ?>
		    <?php $selected = ''; ?>
        <?php endif; ?>
        <option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $key ); ?>">
            <?php echo esc_html( $val ); ?>
        </option>
    <?php endforeach; ?>
</select>