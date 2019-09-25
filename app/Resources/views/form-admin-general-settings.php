<div class="wrap">
	<h2>
		<?php esc_html_e('General Settings', 'subway'); ?>
	</h2>
	<form id="subway-settings-form" action="options.php" method="POST">
		<?php settings_fields('subway-settings-group'); ?>
		<?php do_settings_sections('subway-settings-section'); ?>
		<?php submit_button(); ?>
	</form>
</div>