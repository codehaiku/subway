<?php
/**
 * This file is part of the Subway WordPress Plugin Package.
 * This file contains the class which handles the metabox of the plugin.
 *
 * (c) Joseph G <emailnotdisplayed@domain.ltd>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP Version 5.4
 *
 * @category Classes\Services\Templates\Widgets\WidgetForm
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/subway-2.0
 * @link     github.com/codehaiku/subway-2.0 The Plugin Repository
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

?>
<?php wp_enqueue_script( 'subway-general' ) ?>
<h4>
    <a class="subway-widget-options-toggle button" data-target="subway-<?php echo esc_attr( $widget->id ); ?>" href="#">
        <span class="dashicons dashicons-lock"></span>
		<?php esc_html_e( 'Edit Memberships Access', 'subway' ); ?>
    </a>
</h4>
<?php if ( ! isset( $instance['subway-widget-access-type'] ) ) : ?>
	<?php array_push( $instance, 'subway-widget-access-type' ); ?>
<?php endif; ?>
<?php if ( empty( $instance['subway-widget-access-type'] ) ) : ?>
	<?php $instance['subway-widget-access-type'] = 'public'; ?>
<?php endif; ?>

<?php
$class = '';
if ( 'private' === $instance['subway-widget-access-type'] ) :
	$class = 'active';
endif;
?>
<div id="subway-<?php echo esc_attr( $widget->id ); ?>" class="subway-widget-membership-options" style="display: none;">
    <hr/>
    <dl>
        <p>

            <label class="subway-access-role-public-toggle">
                <input data-target="subway-access-role-<?php echo esc_attr( $widget->id ); ?>"
					<?php checked( $instance['subway-widget-access-type'], 'public', true ); ?> type="radio"
                       id="<?php echo esc_attr( $widget->get_field_id( 'subway-widget-access-type' ) ); ?>"
                       name="<?php echo esc_attr( $widget->get_field_name( 'subway-widget-access-type' ) ); ?>"
                       value="public"/>
				<?php esc_html_e( 'Public', 'subway' ); ?>
            </label>
        </p>
        <p>
            <label class="subway-access-role-private-toggle">
                <input data-target="subway-access-role-<?php echo esc_attr( $widget->id ); ?>"
					<?php checked( $instance['subway-widget-access-type'], 'private', true ); ?>
                       type="radio" id="<?php echo esc_attr( $widget->get_field_id( 'subway-widget-access-type' ) ); ?>"
                       name="<?php echo esc_attr( $widget->get_field_name( 'subway-widget-access-type' ) ); ?>"
                       value="private"/>
				<?php esc_html_e( 'Private', 'subway' ); ?>
            </label>
        </p>
        <div id="subway-access-role-<?php echo esc_attr( $widget->id ); ?>" class="subway-widget-access-type-roles
		<?php echo esc_attr( $class ); ?>">
			<?php $editable_roles = get_editable_roles(); ?>
			<?php unset( $editable_roles['administrator'] ); ?>
            <dl>
				<?php foreach ( $editable_roles as $role_name => $role_info ) : ?>
                    <dt style="margin-bottom: 5px;">
                        <label>
							<?php

							$allowed_roles = array();

							$checked = '';

							if ( ! empty( $instance['subway-widget-access-roles'] ) ) {
								$allowed_roles = (array) $instance['subway-widget-access-roles'];
							}

							if ( ! array_key_exists( 'subway-widget-access-roles', $instance ) ) {
								$checked = 'checked';
							}

							if ( in_array( $role_name, $allowed_roles, true ) ) {
								$checked = 'checked';
							}

							?>

                            <input <?php echo esc_attr( $checked ); ?> type="checkbox"
                                                                       name="<?php echo esc_attr( $widget->get_field_name( 'subway-widget-access-roles' ) ); ?>[]"
                                                                       id="<?php echo esc_attr( $widget->get_field_name( 'subway-widget-access-roles' ) ); ?>"
                                                                       value="<?php echo esc_attr( $role_name ); ?>"/>

							<?php echo esc_html( ucfirst( $role_name ) ); ?>

                        </label>
                    </dt>
				<?php endforeach; ?>
            </dl>
        </div>
        <div class="subway-widget-access-type-message">
            <label>
                <p>
                    <strong>
						<?php esc_html_e( 'No Access Message', 'subway' ); ?>
                    </strong>
                </p>
				<?php $widget_message = ''; ?>
				<?php if ( ! empty( $instance['subway-widget-access-roles-message'] ) ) { ?>
					<?php $widget_message = $instance['subway-widget-access-roles-message']; ?>
				<?php } ?>
                <textarea
                        name="<?php echo esc_attr( $widget->get_field_name( 'subway-widget-access-roles-message' ) ); ?> "
                        class="widefat"
                        rows="3"
                        placeholder="<?php esc_attr_e( 'The message that will be displayed if user has no access', 'subway' ); ?>"><?php echo wp_kses_post( $widget_message ); ?></textarea>
            </label>
            <p>
				<?php esc_html_e( 'Limited HTML are allowed for security reasons', 'subway' ); ?>
            </p>
        </div>
    </dl>
</div>
