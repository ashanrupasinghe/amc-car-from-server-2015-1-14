<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Post extends AT_Controller{
	public function __construct() {
		parent::__construct();
	}

	public function single(){
		$post_id = get_the_ID();
		$layout = get_post_meta( $post_id, '_layout', true );
		if ( empty( $layout ) || !$this->view->check_layout( $layout ) ) $layout = $this->core->get_option( 'default_page_layout', 'content_right' );
		$page_title = get_the_title();
		$page_tagline = get_post_meta( $post_id, '_page_tagline', true );

		if ( !get_post_meta( $post_id, '_disable_breadcrumbs', true ) ) {
			if( get_option( 'show_on_front' ) == 'page' ) { 
				$blog_url = get_permalink( get_option('page_for_posts' ) );
				$blog_title = get_the_title( get_option('page_for_posts' ) );
				$this->breadcrumbs->add_item( $blog_title, $blog_url );
			}
			//else $blog_url = bloginfo('url');
			$this->breadcrumbs->add_item( $page_title . ' ' . $page_tagline, get_permalink( $post_id ) );
		}

		if ( !get_post_meta( $post_id, '_disable_page_title', true ) )
			$this->view->add_block('page_title', 'general/page_title', array( 'page_title' => $page_title, 'page_tagline' => $page_tagline ));

		$this->view->use_layout('header_' . $layout . '_footer')
			->add_block('content', 'post/single', array( 'layout' => $layout ));
	}

	public function archive(){
		global $wp_query;
		$title =  __( 'Archives', AT_TEXTDOMAIN );
		$page = get_query_var( 'paged' );
		
		$segment = 4;
		if( is_category() ) {
			$title = sprintf( __('Category Archive for: %1$s', AT_TEXTDOMAIN ), '&lsquo;' . single_cat_title('',false) . '&rsquo;');
			$catID = get_query_var('cat');
			//$count = $wpdb->get_var("SELECT count FROM " . $wpdb->term_taxonomy . " WHERE term_taxonomy_id = '" . $catID . "'");
		} elseif ( is_tag () ) {
			$title = sprintf( __('All Posts Tagged Tag: %1$s', AT_TEXTDOMAIN ), '&lsquo;' . single_tag_title('',false) . '&rsquo;');
		} elseif ( is_day() ) {
			$title = sprintf( __('Daily Archive for: %1$s', AT_TEXTDOMAIN ), '&lsquo;' . get_the_time('F jS, Y') . '&rsquo;');
			$likedate = 'Y-m-d ';
		} elseif ( is_month() ) {
			$title = sprintf( __('Monthly Archive for: %1$s', AT_TEXTDOMAIN ), '&lsquo;' . get_the_time('F, Y') . '&rsquo;');
			$likedate = 'Y-m-';
		} elseif ( is_year() ) {
			$title = sprintf( __('Yearly Archive for: %1$s', AT_TEXTDOMAIN ), '&lsquo;' . get_the_time('Y') . '&rsquo;');
			$likedate = 'Y-';
		} elseif ( is_author() ) {
			$curauth = get_userdata( intval($author) );
			$title = sprintf( __('Author Archive for: %1$s', AT_TEXTDOMAIN ), '&lsquo;' . $curauth->nickname . '&rsquo;');
		} elseif ( is_tax() ) {
			$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			$title = sprintf( __('Archives for: %1$s', AT_TEXTDOMAIN ), '&lsquo;' . $term->name . '&rsquo;');
		}
		$count = $wp_query->found_posts;

		$this->view->add_block('page_title', 'general/page_title', array( 'page_title' => $title ));
		$layout = $this->core->get_option( 'default_page_layout', 'content_right' );

		$segments = explode( '?', $_SERVER['REQUEST_URI'] );
		$segments = trim( $segments[0], '/' );
		$segments = explode( '/page/', $segments );
		$url = $segments[0];

		$paginator = $this->load->library('paginator');
		$paginator = $paginator->get( $segment, $count, get_option('posts_per_page'), 1, 2, $url . '/page/' . $page, $url . '/' );

		$this->breadcrumbs->add_item( $title, '' );
		$this->view->use_layout('header_' . $layout . '_footer')
			->add_block( 'content', 'blog/view', array( 'layout' => $layout ) )
			->add_block( 'content/pagination', 'general/pagination', $paginator );
	}

}