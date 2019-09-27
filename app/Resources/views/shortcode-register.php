<?php
/**
 * Register shortcode template
 */
?>

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

	<?php if ( ! empty( $errors ) ): ?>

        <div class="sw-form-errors">
			<?php foreach ( $errors as $error ): ?>
                <p class="sw-error">
					<?php echo esc_html( $error ); ?>
                </p>
			<?php endforeach; ?>
        </div><!--.sw-form-errors-->

	<?php endif; ?>

    <!--Form row start-->
    <div class="subway-form-row">
        <div class="sw-field-inner-row">
            <label>
	            <span class="sw-block sw-field-title">
                    <?php esc_html_e( 'Hello! What\'s your name? ', 'subway' ); ?>
                </span>
                <span class="sw-block sw-field-sub-title">
	                <?php esc_html_e( 'Type your name below', 'subway' ); ?>
                </span>
                <span class="sw-block sw-field">
                    <input autocomplete="off"
                           placeholder="<?php esc_attr_e( 'Example: William Smith', 'subway' ); ?>"
                           type="text"
                           name="sw-name"
                    />
                </span>
            </label>
        </div>

    </div>
    <!--Form row end-->

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
                    />
                </span>
            </label>
        </div>
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
	                <?php esc_html_e( 'Type your email address below', 'subway' ); ?>
                </span>
                <span class="sw-block sw-field">
                    <input autocomplete="off"
                           placeholder="<?php esc_attr_e( 'Example: john_doe99@website.org', 'subway' ); ?>"
                           type="email"
                           name="sw-email"
                    />
                </span>
            </label>
        </div>
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
                    <?php esc_html_e( 'Password ', 'subway' ); ?>
                </span>
                <span class="sw-block sw-field-sub-title">
	                <?php esc_html_e( 'Type desired password below', 'subway' ); ?>
                </span>
                <span class="sw-block sw-field">
                    <input autocomplete="off"
                           placeholder="<?php esc_attr_e( '****', 'subway' ); ?>"
                           type="password"
                           name="sw-password"
                    />
                </span>
            </label>
        </div>
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
                    />
                </span>
            </label>
        </div>
    </div>
    <!--Form row end-->

	<?php do_action( 'subway_shortcode_register_before_submit' ); ?>

    <!--Form row start-->

    <div class="subway-form-row">
        <div class="sw-field-inner-row">
            <span class="sw-block sw-field">
                <button type="submit" class="button">
                    <?php echo esc_html_e( 'Register', 'subway' ); ?>
                </button>
            </span>
        </div>
    </div>
    <!--Form row end-->


</form>