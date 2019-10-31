<table class="subway-checkout-user-info-table">
    <thead>
    <tr>
        <th>
			<?php esc_html_e( 'Personal Information', 'subway' ); ?>
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <div class="subway-flex-wrap">
                <div class="subway-flex-column-20">
					<?php echo get_avatar( get_current_user_id(), 40 ); ?><br/>
                </div>
                <div class="subway-flex-column-80">

					<?php echo esc_html( $user->display_name ); ?>
                    <br/>
                    <a title="<?php esc_attr_e( '(Not You?) Logout', 'subway' ); ?>"
                       href="<?php echo esc_url( wp_logout_url() ); ?>">
						<?php esc_html_e( '(Not You?) Logout', 'subway' ); ?>
                    </a>

                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <strong>
				<?php esc_html_e( 'Email Address', 'subway' ); ?>
            </strong>
            <br/>
			<?php echo esc_html( $user->user_email ); ?>
            <br/>
            <a href="<?php echo esc_url( add_query_arg( 'account-page', 'update-email-address', $options->get_accounts_page_url() ) ); ?>"
               title="<?php esc_attr_e( 'Update Email Address', 'subway' ); ?>">
				<?php esc_html_e( 'Update Email Address', 'subway' ); ?>
            </a>
        </td>
    </tr>
    <tr>
        <td>
            <strong>
				<?php esc_html_e( 'Current Plan', 'subway' ); ?>
            </strong>

            <h3 class="subway-mg-bot-zero subway-mg-top-zero">
				<?php echo $plan->get_product_link(); ?>
            </h3>

            <a title="<?php echo esc_attr( $plan->get_name() ); ?>"
               href="<?php echo esc_url( $plan->get_plan_url() ); ?>">
				<?php echo esc_html( $plan->get_name() ); ?>
            </a>

        </td>
    </tr>
    </tbody>
</table>