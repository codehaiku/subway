<?php
if ( ! defined( 'ABSPATH' ) ) {
	return;
}
$pricing = new \Subway\Memberships\Plan\Pricing\Controller();
$pricing->set_plan_id( $plan->get_id() );
$pricing = $pricing->get( true );

?>
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
                    <option <?php selected( $pricing->get_billing_cycle_frequency(), $i ); ?>
                            value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></option>
				<?php } ?>
            </select>
        </div>
        <div class="subway-flex-column">
            <select id="billing-cycle-period" name="billing-cycle-period">
                <option <?php selected( $pricing->get_billing_cycle_period(), 'days' ); ?>
                        value="days"><?php esc_html_e( 'Day(s)' ); ?></option>
                <option <?php selected( $pricing->get_billing_cycle_period(), 'weeks' ); ?>
                        value="weeks"><?php esc_html_e( 'Week(s)' ); ?></option>
                <option <?php selected( $pricing->get_billing_cycle_period(), 'months' ); ?>
                        value="months"><?php esc_html_e( 'Month(s)' ); ?></option>
                <option <?php selected( $pricing->get_billing_cycle_period(), 'years' ); ?>
                        value="years"><?php esc_html_e( 'Year(s)' ); ?></option>
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

    <select name="billing-limit">
        <option <?php selected( $pricing->get_billing_limit(), 0 ); ?>
                value="0"><?php esc_html_e( 'Never', 'subway' ); ?></option>
		<?php for ( $i = 2; $i <= 31; $i ++ ) { ?>
            <option <?php selected( $pricing->get_billing_limit(), $i ); ?>
                    value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></option>
		<?php } ?>
    </select>


</div>
<!--/.Billing Limit-->

<!--Trial Period-->
<div class="subway-form-row" id="free-trial">
    <h3 class="field-title">
        <label for="input-free-trial-checkbox">
            <input <?php checked( $pricing->is_has_trial(), true ) ?> type="checkbox" value="1" name="free-trial"
                                                                      id="input-free-trial-checkbox"/>
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
                        <option <?php selected( $pricing->get_trial_frequency(), $i ); ?>
                                value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></option>
					<?php } ?>
                </select>
            </div>
            <div class="subway-flex-column">
                <select id="trial-billing-cycle-period" name="trial-billing-cycle-period">
                    <option <?php selected( $pricing->get_trial_period(), "days" ); ?>
                            value="days"><?php esc_html_e( 'Day(s)' ); ?></option>
                    <option <?php selected( $pricing->get_trial_period(), "weeks" ); ?>
                            value="weeks"><?php esc_html_e( 'Week(s)' ); ?></option>
                    <option <?php selected( $pricing->get_trial_period(), "month" ); ?>
                            value="month"><?php esc_html_e( 'Month(s)' ); ?></option>
                    <option <?php selected( $pricing->get_trial_period(), "years" ); ?>
                            value="years"><?php esc_html_e( 'Year(s)' ); ?></option>
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

            <input autofocus="" id="input-trial-amount" name="trial-amount" type="number"
                   style="width: 6em;"
                   size="3" placeholder="0.00" step="0.01"
                   value="<?php echo esc_attr( $pricing->get_trial_amount() ); ?>">
        </div>
    </div><!--#trial-period-details-->
</div>

<!--/.Trial Period-->