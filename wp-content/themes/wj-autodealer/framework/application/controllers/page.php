<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Page extends AT_Controller{
	public function __construct() {
		parent::__construct();
	}

	public function index(){
		$post_id = get_the_ID();
		$layout = get_post_meta( $post_id, '_layout', true );

		if ( empty( $layout ) || !$this->view->check_layout( $layout ) ) $layout = $this->core->get_option( 'default_page_layout', 'content_right' );
		$page_title = get_the_title();
		$page_tagline = get_post_meta( $post_id, '_page_tagline', true );

		if ( !get_post_meta( $post_id, '_disable_breadcrumbs', true ) ) {
			$this->breadcrumbs->add_item( $page_title . ' ' . $page_tagline, get_permalink( $post_id ) );
		}

		if ( !get_post_meta( $post_id, '_disable_page_title', true ) )
			$this->view->add_block('page_title', 'general/page_title', array( 'page_title' => $page_title, 'page_tagline' => $page_tagline ));

		$this->view->use_layout('header_' . $layout . '_footer')
			->add_block('content', 'page/view', array( 'layout' => $layout ));
	}

}