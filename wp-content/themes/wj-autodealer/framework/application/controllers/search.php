<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Search extends AT_Controller{
	public function __construct() {
		parent::__construct();
	}

	public function index(){
		global $wp_query;
		$title = sprintf( __('Search: %1$s', AT_TEXTDOMAIN ), '&lsquo;' . get_search_query() . '&rsquo;');
		
		$count = $wp_query->found_posts;
		$paginator = $this->load->library('paginator');
		$paginator = $paginator->get_query_string( 'paged', $count, get_option('posts_per_page'), 1, 2 );

		$layout = $this->core->get_option( 'default_page_layout' );
		$this->view->use_layout('header_' . $layout . '_footer')
			->add_block('page_title', 'general/page_title', array( 'page_title' => $title))
			->add_block('content', 'blog/view', array( 'layout' => $layout ) )
			->add_block( 'content/pagination', 'general/pagination', $paginator );
	}

}