<!-- Payment Type -->
<div class="subway-form-row">
    <h3 class="field-title">
        <label for="billing-type">
			<?php esc_html_e( 'Payment Type', 'subway' ); ?>
        </label>
    </h3>
    <p class="field-help">
		<?php esc_html_e( 'Update the payment type of this membership plan.', 'subway' ); ?>
    </p>
	<?php
	$options = [
		'free'      => esc_html__( 'Free', 'subway' ),
		'fixed'     => esc_html__( 'Fixed (One-Time Payment)', 'subway' ),
		'recurring' => esc_html__( 'Recurring (Subscription Payment)', 'subway' ),
	];
	?>

	<?php if ( isset( $form_data['type'] ) && ! empty( $form_data['type'] ) ): ?>

		<?php $plan->set_type( $form_data['type'] ); ?>

	<?php endif; ?>

    <select name="type" id="billing-type">

		<?php foreach ( $options as $value => $label ): ?>
			<?php if ( $plan->get_type() === $value ): ?>
				<?php $selected = 'selected'; ?>
			<?php else: ?>
				<?php $selected = ''; ?>
			<?php endif; ?>
            <option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $value ); ?>">
				<?php echo esc_html( $label ); ?>
            </option>
		<?php endforeach; ?>
    </select>
</div>
<!--/.Payment Type-->

<!--Plan Price-->
<div class="subway-form-row" id="billing-amount">
    <h3 class="field-title">
        <label>
			<?php esc_html_e( 'Billing Amount', 'subway' ); ?>
        </label>
    </h3>
    <p class="field-help">
		<?php esc_html_e( 'Enter the new price for this Memberships Plan', 'subway' ); ?>
    </p>
    <div class="field-group">
                            <span class="currency-amount">
                                <?php echo get_option( 'subway_currency', 'USD' ); ?>
                            </span>
        <input autofocus id="input-amount" name="amount" type="number" style="width: 6em;" size="3"
               placeholder="0.00"
               step="0.01"
               value="<?php echo esc_attr( $plan->get_real_amount() ); ?>"/>

    </div>
	<?php if ( isset( $errors['amount'] ) ): ?>
        <p class="validation-errors">
			<?php echo $errors['amount']; ?>
        </p>
	<?php endif; ?>
</div>

<!--/.Plan Price-->

<!--Billing Cycle-->
<div class="subway-form-row" id="billing-cycle">
    <h3 class="field-title">
        <label>
			<?php esc_html_e( 'Billing Cycle', 'subway' ); ?>
        </label>
    </h3>
    <p class="field-help">
		<?php esc_html_e( 'Select the billing cycle for this plan. The customer will be billed on every selected cycle.', 'subway' ); ?>
    </p>
    <div class="field-bundle subway-flex-wrap">
        <div class="subway-flex-column">
            <select id="billing-cycle-number" name="billing-cycle-number">
				<?php for ( $i = 1; $i <= 30; $i ++ ) { ?>
                    <option><?php echo esc_html( $i ); ?></option>
				<?php } ?>
            </select>
        </div>
        <div class="subway-flex-column">
            <select id="billing-cycle-period" name="billing-cycle-period">
                <option><?php esc_html_e( 'Day(s)' ); ?></option>
                <option><?php esc_html_e( 'Week(s)' ); ?></option>
                <option><?php esc_html_e( 'Month(s)' ); ?></option>
                <option><?php esc_html_e( 'Year(s)' ); ?></option>
            </select>
        </div>


    </div>
</div>
<!--/.Billing Cycle-->

<!--Billing Limit-->
<div class="subway-form-row" id="billing-limit">
    <h3 class="field-title">
        <label>
			<?php esc_html_e( 'Billing Limit', 'subway' ); ?>
        </label>
    </h3>
    <p class="field-help">
		<?php esc_html_e( 'Select how many cycles until the billing should stop.', 'subway' ); ?>
    </p>

    <select name="billing-cycle-number">
        <option><?php esc_html_e( 'Never', 'subway' ); ?></option>
		<?php for ( $i = 2; $i <= 31; $i ++ ) { ?>
            <option><?php echo esc_html( $i ); ?></option>
		<?php } ?>
    </select>


</div>
<!--/.Billing Limit-->

<!--Trial Period-->
<div class="subway-form-row" id="free-trial">
    <h3 class="field-title">
        <label for="input-free-trial-checkbox">
            <input type="checkbox" name="free-trial" id="input-free-trial-checkbox"/>
			<?php esc_html_e( 'Offer Trial Period', 'subway' ); ?>

        </label>
    </h3>
    <div id="trial-period-details">
        <p class="field-help">
			<?php esc_html_e( 'Define the trial period', 'subway' ); ?>
        </p>
        <div class="field-bundle subway-flex-wrap">
            <div class="subway-flex-column">
                <select id="trial-billing-cycle-number" name="trial-billing-cycle-number">
					<?php for ( $i = 1; $i <= 52; $i ++ ) { ?>
                        <option><?php echo esc_html( $i ); ?></option>
					<?php } ?>
                </select>
            </div>
            <div class="subway-flex-column">
                <select id="trial-billing-cycle-period" name="trial-billing-cycle-period">
                    <option><?php esc_html_e( 'Day(s)' ); ?></option>
                    <option><?php esc_html_e( 'Week(s)' ); ?></option>
                    <option><?php esc_html_e( 'Month(s)' ); ?></option>
                    <option><?php esc_html_e( 'Year(s)' ); ?></option>
                </select>
            </div>
        </div>
        <p class="field-help">
			<?php esc_html_e( 'Amount to bill for the trial period. Enter 0.00 for free trials.', 'subway' ); ?>
        </p>
        <div class="field-group">
                                <span class="currency-amount">
                                    <?php echo get_option( 'subway_currency', 'USD' ); ?>
                                </span>

            <input autofocus="" id="input-trial-amount" name="trial_amount" type="number"
                   style="width: 6em;"
                   size="3" placeholder="0.00" step="0.01" value="10">
        </div>
    </div><!--#trial-period-details-->
</div>

<!--/.Trial Period-->