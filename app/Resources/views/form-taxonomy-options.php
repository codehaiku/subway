<tr class="form-field subway-membership-access-type">
    <th scope="row" valign="top">
        <label for="subway-membership-access-type">
			<?php esc_html_e( 'Membership Access', 'subway' ); ?>
        </label>
    </th>

    <td>
		<?php
		$access_type = get_term_meta( $term->term_id, 'subway_membership_access_type', true );
		if ( empty ( $access_type ) ) {
			$access_type = 'public';
		}
		?>
        <!--Membership Access Type-->
        <p style="margin-top: 0px;">
            <label>
                <input <?php checked( $access_type, 'public', true ); ?>
                        type="radio"
                        name="subway_term_meta[subway_membership_access_type]"
                        class="subway_membership_access_type_radio"
                        value="public"
                        id="subway_membership_access_type_public"
                />
				<?php esc_html_e( 'Public', 'subway' ); ?>
            </label>
        </p>
        <p class="howto">
			<?php esc_html_e( "Select the 'Public' option to make this term 
			    accessible to all users and readers.", 'subway' ); ?>
        </p>
        <p>
			<?php $display = 'none'; ?>
			<?php if ( 'private' === $access_type ): ?>
				<?php $display = 'block'; ?>
			<?php endif; ?>
            <label>
                <input <?php checked( $access_type, 'private', true ); ?>
                        type="radio"
                        name="subway_term_meta[subway_membership_access_type]"
                        class="subway_membership_access_type_radio"
                        value="private"
                        id="subway_membership_access_type_private"
                />
				<?php esc_html_e( 'Members Only', 'subway' ); ?>
            </label>
        </p>

        <p class="howto">
			<?php esc_html_e( "Select the ‘Private’ option to redirect the users to the login page 
			when a user visit the archive of this taxonomy term. ", 'subway' ); ?>
        </p>
        <!--Membership Access Type End-->

        <dl id="subway-term-membership-role-access-wrap" style="display: <?php echo esc_attr( $display ); ?>">

			<?php $editable_roles = get_editable_roles(); ?>
			<?php $selected_roles = get_term_meta( $term->term_id,
                'subway_membership_access_type_roles', true ); ?>

			<?php // Set the default to 'check all'?>
			<?php unset( $editable_roles['administrator'] ); ?>

			<?php foreach ( $editable_roles as $role_name => $role_info ): ?>

				<?php $checked = ''; ?>
				<?php if ( in_array( $role_name, (array) $selected_roles ) ): ?>
					<?php $checked = 'checked'; ?>
				<?php endif; ?>

				<?php //Check the checkbox if there are no meta available. ?>
				<?php if ( "string" === gettype( $selected_roles ) ): ?>
					<?php $checked = 'checked'; ?>
				<?php endif; ?>

                <dt>
                    <label>
                        <input <?php echo esc_attr( $checked ); ?>
                                value="<?php echo esc_attr( $role_name ); ?>"
                                name="subway_term_meta[subway_membership_access_type_role][]"
                                type="checkbox"/>
						<?php echo esc_html( $role_info['name'] ); ?>
                    </label>
                </dt>
			<?php endforeach; ?>
            <dt>
                <p class="howto">
					<?php esc_html_e( 'Uncheck the user roles that you do not want to have access
					 to this content', 'subway' ); ?>
                </p>
            </dt>
        </dl>
    </td>
</tr>
<script>
    jQuery(document).ready(function ($) {
        'use strict';
        var display = 'none';
        $('.subway_membership_access_type_radio').change(function () {
            if ('private' === $(this).val()) {
                display = "block";
            } else {
                display = "none";
            }
            $('#subway-term-membership-role-access-wrap').css('display', display);
        });
    });
</script>