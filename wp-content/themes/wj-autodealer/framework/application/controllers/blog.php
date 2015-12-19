<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Blog extends AT_Controller{
	public function __construct() {
		parent::__construct();
	}

	public function index( $page = 1 ){
		global $paged;
		$page_id = get_option( 'page_for_posts' );

		if ( $page < 0 ) $page = 1;
		set_query_var ( 'paged', $page );

		if ( $page_id > 0 ) {

			$layout = get_post_meta( $page_id, '_layout', true );
			if ( empty( $layout ) || !$this->view->check_layout( $layout ) ) $layout = $this->core->get_option( 'blog_layout', 'content_right' );
			$page_title = get_the_title( $page_id );
			$page_tagline = get_post_meta( $page_id, '_page_tagline', true );

			if ( !get_post_meta( $page_id, '_disable_page_title', true ) )
				$this->view->add_block('page_title', 'general/page_title', array( 'page_title' => $page_title, 'page_tagline' => $page_tagline ));

			if ( !get_post_meta( $page_id, '_disable_breadcrumbs', true ) ) {
				$this->breadcrumbs->add_item( $page_title . ' ' . $page_tagline, get_permalink( $page_id ) );
			}

		} else {
			// THEME SETTINGS
			$this->view->add_block('page_title', 'general/page_title', array( 'page_title' => $this->core->get_option( 'blog_title' ) ));
			$layout = $this->core->get_option( 'blog_layout', 'content_right' );
		}
		//$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		$paginator = $this->load->library('paginator');
		$paginator = $paginator->get(3, wp_count_posts()->publish, get_option('posts_per_page'), 1, 2, 'blog/page/' . $page, 'blog/' );
		$this->view->use_layout('header_' . $layout . '_footer')
			->add_block( 'content', 'blog/view', array( 'layout' => $layout ) )
			->add_block( 'content/pagination', 'general/pagination', $paginator );
	}

}