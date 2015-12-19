<?php
if (!defined("AT_DIR")) die('!!!');

class AT_News extends AT_Controller{
	public function __construct() {
		parent::__construct();
	}

	public function single(){
		$post_id = get_the_ID();
		$layout = get_post_meta( $post_id, '_layout', true );
		if ( empty( $layout ) ) $layout = 'content_right';
		$page_title = get_the_title();
		$page_tagline = get_post_meta( $post_id, '_page_tagline', true );

		if ( !get_post_meta( $post_id, '_disable_breadcrumbs', true ) ) {
			$this->breadcrumbs->add_item( $this->core->get_option( 'news_title', __( 'News', AT_TEXTDOMAIN ) ), 'news' );
			$this->breadcrumbs->add_item( $page_title . ' ' . $page_tagline, get_permalink( $post_id ) );
		}

		if ( !get_post_meta( $post_id, '_disable_page_title', true ) )
			$this->view->add_block('page_title', 'general/page_title', array( 'page_title' => $page_title, 'page_tagline' => $page_tagline ));

		$this->view->use_layout('header_' . $layout . '_footer')
			->add_block('content', 'post/single', array( 'layout' => $layout ));
	}

	public function archive( $page = 1 ){
		$this->view->add_block('page_title', 'general/page_title', array( 'page_title' => $this->core->get_option( 'news_title', __( 'News', AT_TEXTDOMAIN ) ) ));
		$layout = $this->core->get_option( 'news_layout' );
		if ( empty($layout) ) $layout = $this->core->get_option( 'default_page_layout', 'content_right' );

		$this->view->global_sidebar = $this->core->get_option( 'news_custom_sidebar', '' );

		$paginator = $this->load->library('paginator');
		$paginator = $paginator->get(3, wp_count_posts( 'news' )->publish, get_option('posts_per_page'), 1, 2, 'news/page/' . $page, 'news/' );

		$this->breadcrumbs->add_item( $this->core->get_option( 'news_title', __( 'News', AT_TEXTDOMAIN ) ), 'news' );
		$this->view->use_layout('header_' . $layout . '_footer')
			->add_block( 'content', 'blog/view', array( 'layout' => $layout ) )
			->add_block( 'content/pagination', 'general/pagination', $paginator );
	}

}