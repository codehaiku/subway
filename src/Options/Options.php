<?php
namespace Subway\Options;

class Options {

	public function get_internal_pages()
	{
		// Login Page.
        $login_page = (int)get_option('subway_login_page');
        // No Access Page.
        $no_access_page = (int)get_option('subway_logged_in_user_no_access_page');
        // Return the pages.
        return array_diff( [$login_page, $no_access_page], [0]);
	}

	public function get_redirect_url()
	{
		$selected_login_post_id = intval( get_option('subway_login_page') );

        // Redirect logged in user to different page.
        if ( is_user_logged_in() )
        {
            if ( ! empty( intval( get_option('subway_logged_in_user_no_access_page') ) ) )
            {
                $selected_login_post_id = intval( get_option('subway_logged_in_user_no_access_page') );
            }
        }

        $login_url = site_url( 'wp-login.php', 'login' );

        $destination_post = get_post( $selected_login_post_id );

        if (! empty( $destination_post ) ) {

            $login_url = trailingslashit(get_permalink($destination_post->ID));

        }
        
        return add_query_arg( apply_filters( 'subway_redirected_url', 
            array('_redirected'=> 'redirected') ), $login_url );
	}
	
	public function attach_hooks() 
	{

		$this->define_hooks();

	}

	private function define_hooks()
	{
		// Blank.
	}

}