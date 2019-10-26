<div class="subway-comment-closed">
	<?php
	echo wp_kses_post( get_option( 'subway_comment_limited_message', esc_html__( 'Commenting is limited.', 'subway' ) ) );
	?>
</div>