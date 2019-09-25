<?php
namespace Subway\Post;

use Subway\User\User;
use Subway\View\View;

class Comments {

    protected $user;

    protected $view;

    public function __construct( User $user, View $view)
    {
        $this->user = $user;
        $this->view = $view;
    }

    public function is_current_user_allowed()
    {
        
        $post_id = get_the_id();

        $access_type = get_post_meta( $post_id, 'subway_post_discussion_access_type', true );

        // Allow commenting when editor unchecked the 'Limit to roles or membership types'.
        if ( empty ( $access_type ) )
        {
            return apply_filters('subway_post_discussion_allow_comment', true);
        }

        // If access type is not empty. Editor limits the commenting.
        if ( ! empty ( $access_type ) )
        {
            // Allow administrator.
            if ( current_user_can( 'manage_option' ) ) 
            {
                return apply_filters('subway_post_discussion_allow_comment', true);
            }

            // Allow specific roles only.
            $current_user_roles = $this->user->get_role( get_current_user_id() );

            $allowed_user_roles = get_post_meta( $post_id, 'subway_post_discussion_roles', true );

            if ( array_intersect( $current_user_roles, (array)$allowed_user_roles ) )
            {
                return apply_filters('subway_post_discussion_allow_comment', true);
            }

        }
        
        return apply_filters('subway_post_discussion_allow_comment', false);

    }

    protected function display()
    {
        if ( ! $this->is_current_user_allowed() )
        {
            $this->view->render('comment-display', []);
        }

    }

    protected function restrict()
    {
        // Check if coming from submit form.
        $requested_post_id = filter_input( INPUT_POST, 'comment_post_ID', FILTER_VALIDATE_INT);

        // Handle comment submission.
        $post_id = get_the_id();
        // Post is coming from submit comment.
        if ( ! empty ( $requested_post_id ) ) {
            $post_id = $requested_post_id;
        }
 
        $current_post = get_post( $post_id );
        
        if ( "open" === $current_post->comment_status ) 
        {

            if ( current_user_can( 'manage_option' ) ) 
            {

                return true;
            }

            if ( $this->is_current_user_allowed()) 
            {
                return true;
            }

        }

        return false;
    }

    public function hook_comments_open()
    {
       return $this->restrict();
    }

    public function hook_comment_form_comments_closed()
    {
        $this->display();
    }

    public function attach_hooks() 
    {
        $this->define_hooks();
    }

    private function define_hooks()
    {

        add_filter('comments_open', array( $this, 'hook_comments_open'), 10, 2 );
        add_action('comment_form_comments_closed', array( $this, 'hook_comment_form_comments_closed' ) );
    }
    
}