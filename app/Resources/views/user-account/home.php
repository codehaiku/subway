<?php if ( ! is_user_logged_in() ): ?>
    <div class="subway-alert subway-alert-info">
        <p>
			<?php esc_html_e( 'Please login to view your account. ', 'subway' ); ?>
            <a href="<?php echo esc_url( wp_login_url() ); ?>" title="<?php echo esc_attr( 'Login', 'subway' ); ?>">
				<?php echo esc_html( __( 'Login', 'subway' ) ); ?>
            </a>
            &middot;
            <a href="<?php echo esc_url( wp_registration_url() ); ?>"
               title="<?php esc_attr( 'Register', 'subway' ); ?>">
				<?php echo esc_html( __( 'Register', 'subway' ) ); ?>
            </a>
        </p>
    </div>
	<?php return; ?>
<?php endif; ?>

<div id="sw-shortcode-user-account-wrap">

	<?php $message = new \Subway\FlashMessage\FlashMessage( get_current_user_id(), 'user_account_cancelled' ); ?>

	<?php $message = $message->get(); ?>

	<?php if ( ! empty( $message ) ): ?>

        <div class="subway-alert subway-alert-<?php echo esc_attr( $message['type'] ); ?>">

			<?php echo esc_html( $message['message'] ); ?>

        </div>

	<?php endif; ?>

    <div id="sw-shortcode-user-account-wrap-inner">

        <div id="user-profile">
			<?php $this->render( 'profile',
				[ 'options' => $options ], false, 'user-account' );
			?>
        </div>

        <div id="user-plans">
			<?php $this->render( 'plans',
				[ 'options' => $options, 'user' => $user ], false, 'user-account' );
			?>
        </div>

        <div id="subway-user-invoices">
			<?php $this->render( 'invoice', [
				'invoices' => $invoices,
				'options'  => $options
			], false, 'user-account' ); ?>
        </div>

        <h6>
            <a class="sw-button sw-button-danger" title="<?php esc_attr_e( 'Sign out of my account', 'subway' ); ?>"
               href="<?php echo esc_url( wp_logout_url() ); ?>">
				<?php esc_html_e( 'Sign out of my account', 'subway' ); ?>
            </a>
        </h6>

    </div>

</div>