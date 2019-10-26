<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php global $wpdb; ?>

<?php $user_plans = new \Subway\User\Plans( $wpdb ); ?>

<?php $subscribed_users = $user_plans->get_users_by_product( $product->get_id() ); ?>

<table class="wp-list-table widefat fixed striped">
    <thead>
    <tr>
        <th colspan="5">
			<?php esc_html_e( 'Subscribers', 'subway' ); ?>
        </th>
    </tr>
    <tr>
        <th colspan="1"><?php esc_html_e( 'Username', 'subway' ); ?></th>
        <th><?php esc_html_e( 'Name', 'subway' ); ?></th>
        <th><?php esc_html_e( 'Email', 'subway' ); ?></th>
        <th><?php esc_html_e( 'Plan', 'subway' ); ?></th>
        <th><?php esc_html_e( 'Started', 'subway' ); ?></th>
    </tr>
    </thead>
	<?php if ( ! empty( $subscribed_users ) ): ?>
        <tbody>
		<?php foreach ( $subscribed_users as $subscribed_user ): ?>
            <tr>
                <td class="column-username">
                    <a href="#" title="">
						<?php $user = get_user_by( 'id', $subscribed_user->result->user_id ); ?>
						<?php echo get_avatar( $user->ID, 42 ); ?>
                    </a>
                    <a title="<?php echo esc_attr( $user->display_name ); ?>"
                       href="<?php echo get_edit_user_link(); ?>">
						<?php echo esc_html( $user->user_login ); ?>
                    </a>
                    <div class="row-actions">
                        <span class="edit">
                            <a title="<?php echo esc_attr( $user->display_name ); ?>"
                               href="<?php echo get_edit_user_link(); ?>">
                                <?php esc_html_e( 'Edit', 'subway' ); ?>
                            </a>
                        </span>
                    </div>
                </td>
                <td>
                    <a title="<?php echo esc_attr( $user->display_name ); ?>"
                       href="<?php echo get_edit_user_link(); ?>">
						<?php echo esc_html( $user->display_name ); ?>
                    </a>
                </td>
                <td><?php echo esc_html( $user->user_email ); ?></td>
                <td>
                    <a title="<?php esc_attr_e( $subscribed_user->plan->get_name() ); ?>"
                       href="<?php echo esc_url( $subscribed_user->plan->get_edit_url( $subscribed_user->plan->get_id() ) ); ?>">
						<?php echo esc_html( $subscribed_user->plan->get_name() ); ?>
                    </a>
                </td>
                <td>
					<?php echo esc_html( date( 'M d, o h:s A', strtotime( $subscribed_user->result->created ) ) ); ?>
                </td>
            </tr>
		<?php endforeach; ?>
        </tbody>
	<?php else: ?>
        <tbody>
        <tr>
            <td colspan="5">
				<?php esc_html_e( 'There are currently no people subscribe into this product.', 'subway' ); ?>
            </td>
        </tr>
        </tbody>
	<?php endif; ?>
    <tfoot></tfoot>
</table>
