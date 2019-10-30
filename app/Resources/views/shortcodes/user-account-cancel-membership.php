<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="subway-cancel-membership">

    <h3>
		<?php esc_html_e( 'Cancel Membership', 'subway' ); ?>
    </h3>

	<?php if ( $plan ): ?>

        <div class="subway-alert subway-alert-danger">
            <p>
				<?php printf( esc_html__( 'You are cancelling your membership plan: %s.', 'subway' ), $plan->get_name() ); ?>
				<?php esc_html_e( 'Please click the button below to confirm.', 'subway' ); ?>
            </p>
        </div>

		<?php do_action('subway-user-account-cancel-membership-form-before'); ?>

        <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">

            <p>
				<?php esc_html_e( 'This action is irreversible!', 'subway' ); ?>
            </p>

            <?php do_action('subway-user-account-cancel-membership-form'); ?>

	        <?php wp_nonce_field( 'subway_user_account_cancel_membership', 'subway_user_account_cancel_membership' ); ?>

            <input type="hidden" value="<?php echo esc_attr( $plan->get_id() ); ?>" name="plan_id" />

            <input type="hidden" value="subway_user_account_cancel_membership" name="action" />

            <button class="sw-button sw-button-danger" type="submit">
				<?php esc_html_e( 'Confirm Membership Cancellation', 'subway' ); ?>
            </button>

        </form>

	<?php else: ?>

        <div class="subway-alert subway-alert-danger">
            <p>
				<?php esc_html_e( 'Error: Cannot find Requested Membership Plan. Please contact site administrator.', 'subway' ); ?>
            </p>
        </div>

	<?php endif; ?>

</div>
