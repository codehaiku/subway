<?php
/**
 * This file contains the html for the plan edit when
 * you click 'edit' in the membership list table.
 *
 * @since  3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}
?>

<?php $section = filter_input( 1, 'section', 516 ); ?>

<?php if ( empty( $plan ) ): ?>

	<?php $error = new WP_Error( 'broke', __( "Error: Membership Plan is not found.", "subway" ) ); ?>

    <h3>
		<?php echo $error->get_error_message(); ?>
    </h3>

	<?php return; ?>

<?php endif; ?>

<?php $form_data = array( 'title' => '', 'description' => '', 'sku' => '', 'type' => '', 'amount' => 0.00 ); ?>

<?php $errors = []; ?>

<?php if ( isset( $messages['form_data'] ) ): ?>

	<?php $form_data = $messages['form_data']; ?>

	<?php $errors = $messages['validation']; ?>

<?php endif; ?>

<?php if ( isset( $messages['type'] ) ): ?>

    <div class="notice notice-<?php echo esc_attr( $messages['type'] ); ?> is-dismissible">

        <p>
			<?php echo esc_html( $messages['message'] ); ?>
        </p>

    </div>

<?php endif; ?>

<div id="subway-edit-plan-form">

    <form autocomplete="off" method="POST" action="<?php echo admin_url( 'admin-post.php' ); ?>">

        <div id="subway-ul-section-tabs">
			<?php $this->render( 'tabs', [ 'section' => $section ], false, 'form-memberships-plans' ); ?>
        </div>

        <div class="subway-flex-wrap">

            <div class="subway-flex-column subway-flex-column-70">

                <!--hidden fields-->
				<?php wp_nonce_field( 'subway_plan_edit_action', 'subway_plan_edit_action' ); ?>

                <input type="hidden" name="action" value="subway_plan_edit_action"/>

                <input type="hidden" name="page" value="subway-membership"/>

                <input type="hidden" name="new" value="yes"/>

                <input type="hidden" id="input-id" name="plan_id"
                       value="<?php echo esc_attr( $plan->get_id() ); ?>"/>


                <input type="hidden" name="active-section"
                       value="<?php echo ! empty( $section ) ? $section : 'plan-information' ?>"/>

                <!--Section Email-->
                <div class="subway-card subway-plan-section <?php echo $section == 'plan-email' ? 'active' : ''; ?>"
                     id="plan-email">
					<?php $this->render( 'email', [], false, 'form-memberships-plans' ); ?>
                </div>
                <!--/.Section Email-->

                <!--Section Pricing-->
                <div class="subway-card subway-plan-section <?php echo $section == 'plan-pricing' ? 'active' : ''; ?>"
                     id="plan-pricing">
					<?php
					$this->render( 'pricing',
						[
							'plan'   => $plan,
							'errors' => $errors
						], false, 'form-memberships-plans' );
					?>
                </div>
                <!--/.Section Email-->

                <!--Section Plan Information-->
                <div class="subway-card subway-plan-section <?php echo $section == 'plan-information' ? 'active' : ''; ?>"
                     id="plan-information">

					<?php
					$this->render( 'information',
						[
							'plan'      => $plan,
							'plan_id'   => $id,
							'form_data' => $form_data,
							'errors'    => $errors
						],
						false, 'form-memberships-plans' );
					?>
                </div>
            </div>
            <!--/.Section Plan Information-->

            <!--Plan Status-->
            <div class="subway-flex-column subway-flex-column-30">

				<?php
				$this->render( 'status', [
					'plans' => $plans,
					'plan'  => $plan,
					'id'    => $id
				], false, 'form-memberships-plans' );
				?>

                <div class="subway-card" id="box-membership-submit-wrap">

                    <input id="update-plan" type="submit" class="button button-primary button-large"
                           value="<?php esc_attr_e( 'Update Plan', 'subway' ); ?>"/>

                    <input name="trash-plan" id="trash-plan" type="submit" class="button button-trash button-large"
                           value="<?php esc_attr_e( 'Send to Trash', 'subway' ); ?>"/>

                </div>

            </div>
            <!--/.Plan Status-->
        </div>


    </form>

</div><!--#subway-edit-plan-form-->