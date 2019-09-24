<?php
namespace Subway\Post;
use Subway\Post\Post;
use Subway\View\View;
use Subway\Options\Options;
use Subway\Helpers\Helpers;

class Metabox {

	public function __construct()
	{
		$this->post = new Post();
		$this->view = new View();
		$this->option = new Options();
		$this->helper = new Helpers();
	}

	public function add_meta_boxes()
	{
		$post_types = $this->post->get_types();

		foreach ( $post_types as $post_type => $value ) {
			add_meta_box( 'subway_comment_metabox',
				esc_html__( 'Membership Discussion', 'subway' ),
				array( $this, 'discussion' ), $post_type, 'side', 'high'
			);

			add_meta_box('subway_visibility_metabox',
				esc_html__( 'Membership Access', 'subway' ),
				array( $this, 'visibility' ), $post_type, 'side', 'high'
			);
		}

	}

	public function visibility( $post ) 
	{
		
		$this->view->render('form-post-visiblity-metabox', [
				'class_post'=> $this->post,
				'options' => $this->option,
				'helper' => $this->helper,
				'post' => $post 
			]);
	}

	public function discussion()
	{

	}

	public function attach_hooks() 
	{

		$this->define_hooks();

	}

	private function define_hooks()
	{
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		//add_action( 'save_post', array( $this, 'saveMetaboxValues' ) );

	}
}