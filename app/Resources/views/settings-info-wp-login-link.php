<?php
/**
 * This file is part of the Subway WordPress Plugin Package.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}
?>

<p class="description">
	<?php
	echo sprintf( __( "In case you were locked out. Use the link below to bypass the log-in page and go directly
		to your website's wp-login URL (http://yoursiteurl.com/wp-login.php): 
		<strong class='subway-settings-text-notice'>%s</strong>", 'subway' ),
		site_url( 'wp-login.php?no_redirect=true' ) );
	?>
</p>
