<?php
namespace Subway\Options\Admin;

class SectionCallback{

	public function pages() {
		echo '<p class="lead">';
			esc_html_e('Select a page from each options to assign an individual 
				pages for your membership site.', 'subway');
		echo '</p>';
	}

	public function archives() {
		echo '<p class="lead">';
		esc_html_e('Choose the accessibility type of your archive pages.', 'subway');
		echo '</p>';
	}

	public function login_redirect() {
		echo '<p class="lead">';
		esc_html_e('Choose the destination page after the user logs in.', 'subway');
		echo '</p>';
	}

	public function system_messages() {
		echo '<p class="lead">';
		esc_html_e('You can use the options below to
		 change the default system messages.', 'subway');
		echo '</p>';
	}
}