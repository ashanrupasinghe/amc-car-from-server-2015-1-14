<?php
if (!defined("AT_DIR")) die('!!!');
class AT_Posttypes {
	
	//////////////////////////////////////////////////////////////////////////////////////////////
	// support wp posttypes
	//////////////////////////////////////////////////////////////////////////////////////////////
	static protected $_wp_post_types = array( 'post' );
	
	//////////////////////////////////////////////////////////////////////////////////////////////
	// registered posttypes
	//////////////////////////////////////////////////////////////////////////////////////////////
	static protected $_register_post_type = array( 'car', 'reviews', 'news' );

	static public function register(){
		foreach( self::$_register_post_type as $key => $value) {
			$method = '_' . $value;
			self::$method();
		}
	}

	static public function get_post_types(){
		return array_merge( self::$_wp_post_types, self::$_register_post_type );
	}

	static private function _car(){
		register_post_type('car', array(
			'labels' => array(
				'name' => _x('Cars', 'post type general name', AT_ADMIN_TEXTDOMAIN ),
				'singular_name' => _x('Car', 'post type singular name', AT_ADMIN_TEXTDOMAIN ),
				'add_new' => _x('Add New', 'service', AT_ADMIN_TEXTDOMAIN ),
				'add_new_item' => __('Add Car', AT_ADMIN_TEXTDOMAIN ),
				'edit_item' => __('Edit Car', AT_ADMIN_TEXTDOMAIN ),
				'new_item' => __('Add Car', AT_ADMIN_TEXTDOMAIN ),
				'view_item' => __('View Car', AT_ADMIN_TEXTDOMAIN ),
				'search_items' => __('Search Car', AT_ADMIN_TEXTDOMAIN ),
				'not_found' =>  __('No Car found', AT_ADMIN_TEXTDOMAIN ),
				'not_found_in_trash' => __('No Car found in Trash', AT_ADMIN_TEXTDOMAIN ), 
				'parent_item_colon' => ''
			),
			'singular_label' => __('Car', AT_ADMIN_TEXTDOMAIN ),
			'public' => true,
			'exclude_from_search' => true,
			'show_ui' => true,
			'menu_icon' => AT_Common::static_url( 'assets/images/admin/custom_pages_icons/20x20/cars.png'),
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => array( 'slug' => 'cars', 'with_front' => false ),
			//'rewrite' => true,
			'has_archive' => false,
			'query_var' => false,
			'supports' => array( 'title', 'editor', 'thumbnail' )
		));
	}

	static private function _reviews(){
		register_post_type('reviews', array(
			'labels' => array(
				'name' => _x('Reviews', 'post type general name', AT_ADMIN_TEXTDOMAIN ),
				'singular_name' => _x('Reviews', 'post type singular name', AT_ADMIN_TEXTDOMAIN ),
				'add_new' => _x('Add Review', 'service', AT_ADMIN_TEXTDOMAIN ),
				'add_new_item' => __('Add Review', AT_ADMIN_TEXTDOMAIN ),
				'edit_item' => __('Edit Review', AT_ADMIN_TEXTDOMAIN ),
				'new_item' => __('Add Review', AT_ADMIN_TEXTDOMAIN ),
				'view_item' => __('View Review', AT_ADMIN_TEXTDOMAIN ),
				'search_items' => __('Search Review', AT_ADMIN_TEXTDOMAIN ),
				'not_found' =>  __('No reviews found', AT_ADMIN_TEXTDOMAIN ),
				'not_found_in_trash' => __('No Review found in Trash', AT_ADMIN_TEXTDOMAIN ), 
				'parent_item_colon' => ''
			),
			'singular_label' => __('Reviews', AT_ADMIN_TEXTDOMAIN ),
			'public' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'menu_icon' => AT_Common::static_url( 'assets/images/admin/custom_pages_icons/20x20/reviews.png'),
			'capability_type' => 'post',
			'hierarchical' => false,
		//	'rewrite' => array( 'with_front' => false ),
			'rewrite' => true,
			'has_archive' => true,
			'query_var' => false,
			'supports' => array( 'title', 'editor', 'thumbnail' )
		));
	}

	static private function _news(){
		register_post_type('news', array(
			'labels' => array(
				'name' => _x('News', 'post type general name', AT_ADMIN_TEXTDOMAIN ),
				'singular_name' => _x('News', 'post type singular name', AT_ADMIN_TEXTDOMAIN ),
				'add_new' => _x('Add New', 'service', AT_ADMIN_TEXTDOMAIN ),
				'add_new_item' => __('Add News', AT_ADMIN_TEXTDOMAIN ),
				'edit_item' => __('Edit News', AT_ADMIN_TEXTDOMAIN ),
				'new_item' => __('Add News', AT_ADMIN_TEXTDOMAIN ),
				'view_item' => __('View News', AT_ADMIN_TEXTDOMAIN ),
				'search_items' => __('Search News', AT_ADMIN_TEXTDOMAIN ),
				'not_found' =>  __('No news found', AT_ADMIN_TEXTDOMAIN ),
				'not_found_in_trash' => __('No news found in Trash', AT_ADMIN_TEXTDOMAIN ), 
				'parent_item_colon' => ''
			),
			'singular_label' => __('News', AT_ADMIN_TEXTDOMAIN ),
			'public' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'menu_icon' => AT_Common::static_url( 'assets/images/admin/custom_pages_icons/20x20/news.png'),
			'capability_type' => 'post',
			'hierarchical' => false,
		//	'rewrite' => array( 'with_front' => false ),
			'rewrite' => true,
			'has_archive' => true,
			'query_var' => false,
			'supports' => array( 'title', 'editor', 'thumbnail' )
		));
	}

	//////////////////////////////////////////////////////////////////////////////
	// POST STATUS
	//////////////////////////////////////////////////////////////////////////////
	public static function custom_post_status(){
		register_post_status( 'archive', array(
			'label'                     => _x( 'Archive', 'post', AT_ADMIN_TEXTDOMAIN ),
			'public'                    => true,
			'exclude_from_search'       => true,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Archive <span class="count">(%s)</span>', 'Archive <span class="count">(%s)</span>' ),
		) );
	}

	public static function append_post_status_list(){
	     global $post;
	     $complete = '';
	     $label = '';
	     if($post->post_type == 'car'){
	          if($post->post_status == 'archive'){
	               $complete = ' selected=\"selected\"';
	               $label = '<span id=\"post-status-display\"> Archived</span>';
	          }
	          echo '
	          <script>
	          jQuery(document).ready(function($){
	               $("select#post_status").append("<option value=\"archive\" '.$complete.'>Archive</option>");
	               $(".misc-pub-section label").append("'.$label.'");
	          });
	          </script>
	          ';
	     }
	}
}