<?php
/**
 * This file is part of the Subway WordPress Plugin Package.
 *
 * (c) Joseph Gabito <joseph@useissuestabinstead.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP Version 5.4
 *
 * @category Subway\Templates\MetaBoxSinglePostTypeAccess
 * @package  Subway
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/subway
 * @link     github.com/codehaiku/subway The Plugin Repository
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

$private_setting_label = __( 'Members Only', 'subway' );

$is_post_private = $class_post->is_private( $post->ID );

// Make sure the form request comes from WordPress.
wp_nonce_field( basename( __FILE__ ),  'subway_post_visibility_nonce' );

// Disable the options (radio) when site is selected as public
?>
<input type="hidden" name="subway-visibility-form-submitted" value="1" />
<p>
	<label class="subway-visibility-settings-checkbox-label" for="subway-visibility-public">
		
		<input type="radio" class="subway-visibility-settings-radio" 
			id="subway-visibility-public" name="subway-visibility-settings" 
				value="public" <?php echo checked( false, $is_post_private, false ); ?> 
					/>
		
		<?php esc_html_e( 'Anyone', 'subway' ) ?>

	</label>
</p>

<?php $current_page_id = get_the_id(); ?>

<?php $internal_pages = $options->get_internal_pages(); ?>

<?php if ( ! in_array( $current_page_id, $internal_pages ) ) : ?>
	<p>
		<label class="subway-visibility-settings-checkbox-label" for="subway-visibility-private">
			
			<input type="radio" class="subway-visibility-settings-radio" 
				id="subway-visibility-private" 
					name="subway-visibility-settings"
						value="private" <?php echo checked( true, $is_post_private, false ); ?> 
							/>
			
			<?php esc_html_e( 'Members Only', 'subway' ) ?>

		 </label>
	</p>
<?php endif ;?>

<div id="subway-roles-access-visibility-fields" class="hidden">
	
	<?php
		$cb_args = array();
		$cb_args['name'] = 'subway-visibility-settings-user-role';
		$cb_args['saved_roles'] = $class_post->get_allowed_roles( $post->ID );
		$cb_args['howto'] = esc_html__( 'Uncheck the user roles that you do not want to have access to this content', 'subway' );
	?>

	<?php $helpers->display_roles_checkbox( $cb_args ); ?>

	<dl>
		<dt>
			<strong>
				<?php esc_html_e( 'No Access Control', 'subway' ); ?>
			</strong>
		</dt>
	</dl>
	<!-- No Access Type -->
	<?php $no_access_type = $class_post->get_no_access_type( $post->ID ); ?>
	
	<dl>
		<p>
			<label>
				<input value="block_content" <?php checked( 'block_content', $no_access_type, true ); ?> type="radio" name="subway-visibility-settings-no-access-type" />
				<?php esc_html_e( 'Block Content', 'subway' ); ?>
				<a href="#" title="<?php esc_attr_e( 'Customize', 'subway' ); ?>">
					&#8599; <?php esc_html_e( 'Edit Message ', 'subway' ); ?>
				</a>
			</label>
		</p>

		<?php if ( 'redirect' === $no_access_type ) : ?>
		<div id="access-type-block-wrap" style="display: none;">
		<?php else : ?>
		<div id="access-type-block-wrap">
		<?php endif; ?>
			<p>
				<dl>
					<label>
						<h4>
							<?php esc_html_e( 'Message to show', 'subway' ); ?>
						</h4>
						<?php $access_type_block_message = get_post_meta( $post->ID, 'subway-visibility-settings-no-access-type-message', true ); ?>
						<textarea class="widefat" id="subway-visibility-settings-no-access-type-message" name="subway-visibility-settings-no-access-type-message"><?php echo wp_kses_post( $access_type_block_message ); ?></textarea>
					</label>
				</dl>
			</p>
			<p class="howto">
				<?php esc_html_e( 'This message will show if you choose "Block Content" option "No Access Control"', 'subway' ); ?>
			</p>
			<p class="howto">
				<strong><?php esc_html_e( 'Note: ', 'subway' ); ?></strong>
				<?php esc_html_e( "'Block Content' option might not work for some content that are plugin-generated. In that case, try using the 'Redirect(302) option.", 'subway' ); ?>
			</p>
		</div><!--#access-type-block-wrap-->
	</dl>
	
	
	<dl>
		<p>
			<label>
				<input value="redirect" <?php checked( 'redirect', $no_access_type, true ); ?> type="radio" name="subway-visibility-settings-no-access-type" />
				<?php esc_html_e( 'Redirect (302) to', 'subway' ); ?> 
				<a target="_blank" href="<?php echo esc_url( $options->get_redirect_url() ); ?>" title="<?php esc_attr_e( 'Login Page', 'subway' ); ?>">
					&#8599; <?php esc_html_e( 'Login Page ', 'subway' ); ?>
				</a>
			</label>
		</p>
	</dl>

</p>

<p class="howto">
	<?php esc_html_e( 'Choose what type of behaviour would you like to have if the user has no access to the content.', 'subway' ); ?>
</p>

<hr/>
		
</div>
<script>
	jQuery(document).ready(function($){
		'use strict';
		if ( $('#subway-visibility-private').is(':checked') ) {
			$('#subway-roles-access-visibility-fields').css('display', 'block');
		}
		$('.subway-visibility-settings-radio').click(function(){
			$('#subway-roles-access-visibility-fields').css('display', 'none');
			if ( $('#subway-visibility-private').is(':checked') ) {
				$('#subway-roles-access-visibility-fields').css('display', 'block');
			}
		});
		$('body').on('change', 'input[name=subway-visibility-settings-no-access-type]', function(){
			if ( 'block_content' === $(this).val() )
			{
				console.log('test');
				$('#access-type-block-wrap').css('display', 'block');
			}else {
				$('#access-type-block-wrap').css('display', 'none');
			}

		});
	});
</script>
<?php if ( ! in_array( $current_page_id, $internal_pages ) ) : ?>
	<p class="howto">
		<?php esc_html_e( 'Choose the accessibility of this page from the options above.', 'subway' ); ?>
	</p>
<?php else : ?>
	<p class="howto">
		<?php esc_html_e( 'This page is selected as your login page. You cannot make this page private.' ); ?>
	</p>
<?php endif ;?>
