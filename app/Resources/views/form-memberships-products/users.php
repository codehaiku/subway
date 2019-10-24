<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php global $wpdb; ?>
<?php $user_plans = new \Subway\User\Plans( $wpdb ); ?>
<?php $user_plans = $user_plans->get_user_plans( get_current_user_id() ); ?>
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
	<?php if ( ! empty( $user_plans ) ): ?>
        <tbody>
		<?php foreach ( $user_plans as $user_plan ): ?>
            <tr>
                <td class="column-username">
                    <a href="#" title="">
						<?php $user = get_user_by( 'id', $user_plan->result->user_id ); ?>
						<?php echo get_avatar( $user->ID, 32 ); ?>
                    </a>
                    <a href="#" title="">
						<?php echo esc_html( $user->user_login ); ?>
                    </a>
                    <div class="row-actions">
                        <span class="edit">
                            <a href="#">
                                <?php esc_html_e( 'Edit', 'subway' ); ?>
                            </a>
                        </span>
                    </div>
                </td>
                <td><?php echo esc_html( $user->display_name ); ?></td>
                <td><?php echo esc_html( $user->user_email ); ?></td>
                <td><?php echo esc_html( $user_plan->result->prod_id ); ?></td>
                <td>Oct 02, 2018 (@todo)</td>
            </tr>
		<?php endforeach; ?>
        </tbody>
	<?php endif; ?>
    <tfoot></tfoot>
</table>
