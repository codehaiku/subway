<?php $c_user = wp_get_current_user(); ?>
<div>
	<h3>
		<?php esc_html_e( 'My Profile', 'subway' ); ?>
	</h3>
	<div class="subway-flex-wrap">

		<div class="subway-flex-column-50">
			<div class="subway-flex-wrap">
				<div class="subway-flex-column-20">
					<div style="margin: 10px 0 0 0;">
						<?php echo get_avatar( $c_user->ID, 32 ); ?>
					</div>
				</div>
				<div class="subway-flex-column-80">
					<p>
						<?php echo esc_html( $c_user->display_name ); ?>
						<br/>
						<?php echo esc_html( $c_user->user_email ); ?>
					</p>
				</div>
			</div>


		</div>
		<div class="subway-flex-column-50">
			<ul class="subway-user-account-actions">
				<li>
					<a title="<?php esc_attr_e( 'Update Personal Info', 'subway' ); ?>"
					   href="<?php echo esc_url( get_edit_user_link( get_current_user_id() ) ); ?>">
						<?php esc_html_e( 'Update Personal Info', 'subway' ); ?>
					</a>
				</li>
				<li>
					<a title="<?php esc_attr_e( 'Change E-mail Address', 'subway' ); ?>"
					   href="<?php echo esc_url( add_query_arg( 'account-page', 'update-email-address', $options->get_accounts_page_url() ) ); ?>">
						<?php esc_html_e( 'Change E-mail Address', 'subway' ); ?>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>