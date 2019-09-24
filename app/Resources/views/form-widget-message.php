<?php wp_enqueue_style('subway-general'); ?>

<?php if ( isset( $settings['subway-widget-access-roles-message'] ) ): ?>
	<?php if ( ! empty ( $settings['subway-widget-access-roles-message'] ) ): ?>
		<?php echo $args['before_widget']; ?>
		<div class="widget-subway-no-access-message">
			<?php echo $settings['subway-widget-access-roles-message']; ?>
		</div>
		<?php echo $args['after_widget']; ?>
	<?php endif; ?>
<?php endif; ?>
