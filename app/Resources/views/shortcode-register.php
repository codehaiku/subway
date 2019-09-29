<?php
/**
 * Register shortcode template
 */
?>

<?php if ( is_user_logged_in() ): ?>
    <p>
        <?php esc_html_e('You are already registered','subway'); ?>
    </p>
	<?php return; ?>
<?php endif; ?>


<form class="sw-form" action="" method="POST">
    <h4>
		<?php esc_html_e( 'Already have an account? Click', 'subway' ); ?>
        <a href="#">
			<?php esc_html_e( 'here to login', 'subway' ); ?>
        </a>
		<?php esc_html_e( 'instead', 'subway' ); ?>
    </h4>



    <input type="hidden" name="sw-action" value="1"/>

	<?php $errors = apply_filters( 'subway_shortcode_register_errors', array() ); ?>

    <!--Form row start-->
    <div class="subway-form-row">
        <div class="sw-field-inner-row">
            <label>
	            <span class="sw-block sw-field-title">
                    <?php esc_html_e( 'Username ', 'subway' ); ?>
                </span>

                <span class="sw-block sw-field-sub-title">
	                <?php esc_html_e( 'Type desired username below', 'subway' ); ?>
                </span>
                <span class="sw-block sw-field">
                    <input autocomplete="off"
                           placeholder="<?php esc_attr_e( 'Example: john_doe99', 'subway' ); ?>"
                           type="text"
                           name="sw-username"
                           required
                           value="<?php echo isset( $_POST['sw-username'] ) ? esc_attr( $_POST['sw-username'] ) : 'user' . uniqid(); ?>"
                    />
                </span>
            </label>
        </div>
		<?php if ( isset( $errors['sw-username'] ) ): ?>
            <div class="sw-form-errors">
                <p class="sw-error">
					<?php echo esc_html( $errors['sw-username'] ); ?>
                </p>
            </div><!--.sw-form-errors-->
		<?php endif; ?>
        <div class="sw-field-inner-row sw-field-howto">
			<?php esc_html_e( 'Alphanumeric characters are allowed no special characters allowed.', 'subway' ); ?>
        </div>


    </div>
    <!--Form row end-->

    <!--Form row start-->
    <div class="subway-form-row">
        <div class="sw-field-inner-row">
            <label>
	            <span class="sw-block sw-field-title">
                    <?php esc_html_e( 'Email Address ', 'subway' ); ?>
                </span>
                <span class="sw-block sw-field-sub-title">
	                <?php esc_html_e( 'Type your email address below. We will use this to send you important updates', 'subway' ); ?>
                </span>
                <span class="sw-block sw-field">
                    <input autocomplete="off"
                           placeholder="<?php esc_attr_e( 'Example: john_doe99@website.org', 'subway' ); ?>"
                           type="email"
                           required
                           name="sw-email"
                           value="<?php echo isset( $_POST['sw-email'] ) ? esc_attr( $_POST['sw-email'] ) : 'user-emai'.uniqid().'@yahoo.com'; ?>"
                    />
                </span>
            </label>
        </div>
		<?php if ( isset( $errors['sw-email'] ) ): ?>
            <div class="sw-form-errors">
                <p class="sw-error">
					<?php echo esc_html( $errors['sw-email'] ); ?>
                </p>
            </div><!--.sw-form-errors-->
		<?php endif; ?>
        <div class="sw-field-inner-row sw-field-howto">
			<?php esc_html_e( 'This is where we will send you important updates.', 'subway' ); ?>
        </div>
    </div>
    <!--Form row end-->

    <!--Form row start-->
    <div class="subway-form-row">
        <div class="sw-field-inner-row">
            <label>
	            <span class="sw-block sw-field-title">
                    <?php esc_html_e( 'Set Account Password ', 'subway' ); ?>
                </span>
                <span class="sw-block sw-field-sub-title">
	                <?php esc_html_e( 'Type desired password below', 'subway' ); ?>
                </span>
                <span class="sw-block sw-field">
                    <input autocomplete="off"
                           placeholder="<?php esc_attr_e( '****', 'subway' ); ?>"
                           type="password"
                           name="sw-password"
                           required
                           value="a123"
                    />
                </span>
            </label>
        </div>
		<?php if ( isset( $errors['sw-password'] ) ): ?>
            <div class="sw-form-errors">
                <p class="sw-error">
					<?php echo esc_html( $errors['sw-password'] ); ?>
                </p>
            </div><!--.sw-form-errors-->
		<?php endif; ?>
    </div>
    <!--Form row end-->

    <!--Form row start-->
    <div class="subway-form-row">
        <div class="sw-field-inner-row">
            <label>
	            <span class="sw-block sw-field-title">
                    <?php esc_html_e( 'Confirm Password ', 'subway' ); ?>
                </span>
                <span class="sw-block sw-field-sub-title">
	                <?php esc_html_e( 'Please re-type the password below', 'subway' ); ?>
                </span>
                <span class="sw-block sw-field">
                    <input autocomplete="off"
                           placeholder="<?php esc_attr_e( '****', 'subway' ); ?>"
                           type="password"
                           name="sw-password-confirm"
                           required
                           value="a123"
                    />
                </span>
            </label>
        </div>
		<?php if ( isset( $errors['sw-password-confirm'] ) ): ?>
            <div class="sw-form-errors">
                <p class="sw-error">
					<?php echo esc_html( $errors['sw-password-confirm'] ); ?>
                </p>
            </div><!--.sw-form-errors-->
		<?php endif; ?>
    </div>
    <!--Form row end-->



    <section>
        <h3>Your Order</h3>
    </section>
    <table>
        <tr>
            <td>Subway Membership</td>
            <td><strong>$100.00</strong></td>
        </tr>
        <tr>
            <td>VAT (12%)</td>
            <td><strong> $12.00</strong></td>
        </tr>
        <tr>
            <td>Sub Total</td>
            <td><strong>$100.00</strong></td>
        </tr>
        <tr>
            <td>Total</td>
            <td><strong>$112.00</strong></td>
        </tr>
    </table>

	<?php do_action( 'subway_shortcode_register_before_submit' ); ?>

    <!--Form row start-->

    <div class="subway-form-row">

        <!--form row start-->
        <div class="sw-field-inner-row">
            <span class="sw-block sw-field">
                <label>
                    Have a coupon? Click here to enter discount code/coupon
                    <input placeholder="Discount Code" class="hidden" type="text" name="sw-coupon"/>
                </label>
            </span>

            <p>
                <label>
                    <input checked type="checkbox" required/>
                    Agree to our license terms and refund policy
                </label>
            </p>
        </div>
        <!--form row end-->

        <div class="sw-field-inner-row">
            <!-- PayPal Logo -->
            <span class="sw-block sw-field">
                <button type="submit" class="button">
                    <?php echo esc_html_e( 'Subscribe', 'subway' ); ?>
                </button>
                <p>
                <small>
					<?php esc_html_e( 'You will be redirect to PayPal website to complete the payment.',
						'subway' ); ?>
                </small>
                </p>
            </span>

        </div>

        <div class="sw-field-inner-row">
            <p>
                <a target="__blank" href="https://www.paypal.com/c2/webapps/mpp/paypal-popup?locale.x=en_C2" title="Secured by PayPal">
                    <img width="200" src="https://www.paypalobjects.com/digitalassets/c/website/marketing/apac/C2/logos-buttons/optimize/Full_Online_Tray_RGB.png" alt="Secured by PayPal"/>
                </a>
            </p>
        </div>
    </div>
    <!--Form row end-->





</form>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>