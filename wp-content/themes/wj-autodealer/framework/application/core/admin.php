<?php
if (!defined("AT_DIR")) die('!!!');
class AT_Admin {
	
	static public function init() {
		add_action( 'init', array( 'AT_Posttypes', 'register') );
		if ( AT_Core::get_instance()->get_option( 'theme_is_activated', false ) ){
			if( isset($_GET['page']) && $_GET['page'] != 'at_theme_install' ) {
				$update_model = AT_Loader::get_instance()->model( 'admin/update_model' );
				if ( !$update_model->is_updated() ){
					$update_model->update();
				}
			}
			
			// AT_Core::get_instance()->set_option( 'current_theme_version', '1.3' );
			// AT_Core::get_instance()->save_option();
			// echo  AT_Core::get_instance()->get_option( 'current_theme_version', '1.0' );
			// die();

			add_action( 'init', array( 'AT_Meta_Options', 'register') );
			add_action( 'widgets_init', array( 'AT_Sidebars', 'register') );
			add_action( 'widgets_init', array( 'AT_Widgets', 'register') );
			add_action( 'init', array( 'AT_Shortcodes', 'init' ) );
		}
		add_action( 'init', array( 'AT_Admin', 'menus_init') );
		add_action( 'init', array( 'AT_Admin', 'route') );

		add_action( 'init', array( 'AT_Posttypes', 'custom_post_status' ) );
		add_action('admin_footer-post.php', array( 'AT_Posttypes', 'append_post_status_list') );

		// self::_locale();
		self::_actions();
		self::_filters();
		self::_notices();
	}	


	public static function route() {
		global $pagenow;
		if ( isset($_GET['page']) || ( $pagenow === 'themes.php' && isset( $_GET['activated'] ) ) ){
			AT_Router::route();
			AT_Core::get_instance()->view->render();

			if ( AT_Core::get_instance()->get_option( 'theme_is_activated', false ) ){
				// Init media gallery
				wp_enqueue_media();
			}
		}
		// echo AT_Router::get_instance()->segments();
		if ( $pagenow === 'widgets.php' ){
			// Init media gallery
			wp_enqueue_media();
		}
	}

	static public function _notices() {
		if (version_compare(PHP_VERSION, '5.3.0') < 0) {
			add_action('admin_notices', array('AT_Notices','php_version_check'));
		}
		if( !class_exists( 'WPBakeryShortCode' ) ) {
			add_action('admin_notices', array('AT_Notices','wpb_installation_check'));
		}
		if ( !extension_loaded('gd') && !function_exists('gd_info') ) {
			add_action('admin_notices', array('AT_Notices','missing_gd'));	
		}
	}

	static protected function _actions(){
		add_action( 'admin_menu', array( 'AT_Admin', 'options_init' ) );
		add_action( 'admin_enqueue_scripts', array( AT_Core::get_instance()->view, 'render_admin_statics') );

		add_action( 'manage_car_posts_custom_column', array( 'AT_Admin', 'action_managing_car_custom_columns' ), 10, 2 );
		add_action( 'restrict_manage_posts', array( 'AT_Admin', 'action_restrict_listings_car' ) );
	}

	static protected function _filters(){
		add_filter( 'admin_body_class', array( 'AT_Admin', 'filter_add_theme_admin_body_class') );
		// Custom Post Type
		add_filter( 'manage_edit-car_columns', array( 'AT_Admin', 'filter_managing_car_columns'),10, 1 );
		add_filter( 'manage_edit-car_sortable_columns', array( 'AT_Admin', 'filter_managing_car_sortable_columns') );
		add_filter( 'parse_query', array( 'AT_Admin', 'filter_car' ) );
		//add_filter( 'posts_where', array( 'AT_Admin', 'filter_car' ) );
	}
	static protected function _locale() {
		$locale = get_locale();		
		load_theme_textdomain( AT_ADMIN_TEXTDOMAIN, get_template_directory() . '/languages' );
		return;
	}

	static public function action_restrict_listings_car( $options ) {
	    global $typenow;
	    global $pagenow;
	    if ( $typenow == 'car' && $pagenow == 'edit.php' ) {
	    	$owner_id = (isset($_GET['_owner_id']) && is_numeric($_GET['_owner_id'])) ? $_GET['_owner_id'] : 0;
	    	$user_model = AT_Loader::get_instance()->model('user_model');
	    	$out = '<select name="_owner_id">';
	    	$out .='<option value="0">' . __( 'Show all user`s cars', AT_ADMIN_TEXTDOMAIN ) . '</option>';
	    	foreach ($user_model->get_all_users() as $key => $user) {
	    		$out .='<option value="' . $user['id'] . '" ' . ( $owner_id == $user['id'] ? 'selected' : '' ) . '>' . $user['name'] . ' : ' . $user['email'] . '</option>';
	    	}
	    	$out .= '</select>';
	    	echo $out;
		}
	}
	
	static public function filter_car( $query ) {
	    global $pagenow;
	    $qv = &$query->query_vars;
	    if ( $pagenow == 'edit.php' && isset($_GET['_owner_id']) && is_numeric($_GET['_owner_id']) && ( $_GET['_owner_id'] > 0 ) ) {
	    	$qv['meta_key'] = '_owner_id';
	    	$qv['meta_value'] = $_GET['_owner_id'];
	    }
	}


	static public function filter_managing_car_columns( $columns ) {
    	$new_columns = array();
    	unset($columns['author']);
	  	unset($columns['comments']);
     	foreach( $columns as $key => $value ) {
        	$new_columns[ $key ] = $value;
        	switch ( $key ) {
        		case 'cb':
        			$new_columns[ 'image' ] = __( 'Image', AT_ADMIN_TEXTDOMAIN );
        			break;
        		case 'title':
        			$new_columns[ 'owner' ] = __( 'Owner', AT_ADMIN_TEXTDOMAIN );
        			$new_columns[ 'views' ] = __( 'Views', AT_ADMIN_TEXTDOMAIN );
        			break;
        	}
     	}
    	return $new_columns;
	}

	static public function filter_managing_car_sortable_columns( $columns ) {
        $columns[ 'owner' ] = __( 'Owner', AT_ADMIN_TEXTDOMAIN );
    	return $columns;
	}

	static public function action_managing_car_custom_columns( $column_name, $car_id ) {
		switch ( $column_name ) {
			case 'image':
				if ( has_post_thumbnail( $car_id ) ) {
					 the_post_thumbnail( array( 50, 50 ) );
				}
				break;
			case 'views':
				$car_model = AT_Loader::get_instance()->model( 'car_model' );
				echo $car_model->get_car_views( $car_id );
				break;
			case 'owner':
				$owner_id = get_post_meta( $car_id, '_owner_id', true );
				$user_model = AT_Loader::get_instance()->model( 'user_model' );
				$user_info = $user_model->get_user_by_id( $owner_id );
				if( $user_info ) {
					echo $user_info['name'] . '<br/> ' . $user_info['email'];
				} else {
					echo __( 'Not set', AT_ADMIN_TEXTDOMAIN );
				}
				break;
			default:
				break;
		}
	}


	public static function filter_add_theme_admin_body_class( $classes ) {
		if( implode('_', AT_Router::get_instance()->segments()) != 'welcome_index' )
		$classes .= 'at_theme_options_styled';
		return $classes;
	}

	// INIT MENU
	public static function menus_init() {
		register_nav_menu( 'primary-menu', __( 'Primary Menu', AT_ADMIN_TEXTDOMAIN ) );
		register_nav_menu( 'footer-menu', __( 'Footer Menu', AT_ADMIN_TEXTDOMAIN ) );
		//register_nav_menu( 'footer-links', __( 'Footer Menu', AT_ADMIN_TEXTDOMAIN ) );
	}
	public static function options_init() {
		//AT_Core::get_instance()->view->add_style( 'adminmenu.css', 'assets/css/admin/adminmenu.css' );
		// INIT PAGES
		add_menu_page( __( 'Theme Options', AT_ADMIN_TEXTDOMAIN ), __( 'Theme Options', AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'theme_options', array( 'AT_Admin', 'options' ), AT_Common::static_url( 'assets/images/admin/custom_pages_icons/20x20/theme_options.png'), 103 );
		add_submenu_page( THEME_PREFIX . 'theme_options', __( 'General Theme Options' , AT_ADMIN_TEXTDOMAIN ), __( 'General Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'theme_options_general', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'theme_options', __( 'Header Theme Options' , AT_ADMIN_TEXTDOMAIN ), __( 'Header Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'theme_options_header', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'theme_options', __( 'Footer Theme Options' , AT_ADMIN_TEXTDOMAIN ), __( 'Footer Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'theme_options_footer', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'theme_options', __( 'Sociable Theme Options' , AT_ADMIN_TEXTDOMAIN ), __( 'Sociable Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'theme_options_sociable', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'theme_options', __( 'Styled Theme Options' , AT_ADMIN_TEXTDOMAIN ), __( 'Styled Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'theme_options_styled', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'theme_options', __( 'Blog Theme Options' , AT_ADMIN_TEXTDOMAIN ), __( 'Blog Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'theme_options_blog', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'theme_options', __( 'News Theme Options' , AT_ADMIN_TEXTDOMAIN ), __( 'News Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'theme_options_news', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'theme_options', __( 'Reviews Theme Options' , AT_ADMIN_TEXTDOMAIN ), __( 'Reviews Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'theme_options_reviews', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'theme_options', __( 'Sidebars Theme Options' , AT_ADMIN_TEXTDOMAIN ), __( 'Sidebars Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'theme_options_sidebars', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'theme_options', __( 'Backup Theme Options' , AT_ADMIN_TEXTDOMAIN ), __( 'Backup Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'theme_options_backup', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'theme_options', __( 'Support Theme Options' , AT_ADMIN_TEXTDOMAIN ), __( 'Support Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'theme_options_support', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'theme_options', __( 'Release Info' , AT_ADMIN_TEXTDOMAIN ), __( 'Release Info' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'theme_options_release', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'theme_options', __( 'Troubleshooting' , AT_ADMIN_TEXTDOMAIN ), __( 'Troubleshooting' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'theme_options_troubleshooting', array( 'AT_Admin', 'options' ));

		remove_submenu_page( THEME_PREFIX . 'theme_options', THEME_PREFIX . 'theme_options' );

		add_menu_page( __( 'Site Options', AT_ADMIN_TEXTDOMAIN ), __( 'Site Options', AT_ADMIN_TEXTDOMAIN ), 'manage_options', THEME_PREFIX . 'site_options', array( 'AT_Admin', 'options' ), AT_Common::static_url( 'assets/images/admin/custom_pages_icons/20x20/site_options.png'), 104 );
		add_submenu_page( THEME_PREFIX . 'site_options', __( 'General Site Options' , AT_ADMIN_TEXTDOMAIN ), __( 'General Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'site_options_general', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'site_options', __( 'Search Filter Options' , AT_ADMIN_TEXTDOMAIN ), __( 'Search Filter Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'site_options_filter', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'site_options', __( 'Maintenance Options' , AT_ADMIN_TEXTDOMAIN ), __( 'Maintenance Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'site_options_maintenance', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'site_options', __( 'Catalog Options' , AT_ADMIN_TEXTDOMAIN ), __( 'Catalog Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'site_options_catalog', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'site_options', __( 'Car Options' , AT_ADMIN_TEXTDOMAIN ), __( 'Car Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'site_options_car', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'site_options', __( 'Registration Options' , AT_ADMIN_TEXTDOMAIN ), __( 'Registration Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'site_options_registration', array( 'AT_Admin', 'options' ));
		//add_submenu_page( THEME_PREFIX . 'site_options', __( 'Advertisement Options' , AT_ADMIN_TEXTDOMAIN ), __( 'Advertisement' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'site_options_advertisement', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'site_options', __( 'Mail Template Options' , AT_ADMIN_TEXTDOMAIN ), __( 'Mail Template Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'site_options_mailtemplate', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'site_options', __( 'Text Options' , AT_ADMIN_TEXTDOMAIN ), __( 'Texts Options ' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'site_options_texts', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'site_options', __( 'Loans Options' , AT_ADMIN_TEXTDOMAIN ), __( 'Loans Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'site_options_loans', array( 'AT_Admin', 'options' ));

		remove_submenu_page( THEME_PREFIX . 'site_options', THEME_PREFIX . 'site_options' );

		if ( !AT_Core::get_instance()->get_option( 'theme_is_activated', false ) ) {
			add_submenu_page( THEME_PREFIX . 'site_options', __( 'Theme Install' , AT_ADMIN_TEXTDOMAIN ), __( 'Theme Install ' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'theme_install', array( 'AT_Admin', 'options' ));
		}
		add_menu_page( __( 'Edit Reference Tables', AT_ADMIN_TEXTDOMAIN ), __( 'Reference', AT_ADMIN_TEXTDOMAIN ), 'manage_options', THEME_PREFIX . 'reference', array( 'AT_Admin', 'options' ), AT_Common::static_url( 'assets/images/admin/custom_pages_icons/20x20/reference.png'), 105 );
		add_menu_page( __( 'Edit Frontend Users', AT_ADMIN_TEXTDOMAIN ), __( 'Frontend Users', AT_ADMIN_TEXTDOMAIN ), 'manage_options', THEME_PREFIX . 'users', array( 'AT_Admin', 'options' ), AT_Common::static_url( 'assets/images/admin/custom_pages_icons/20x20/users.png'), 106 );

		add_menu_page( __( 'Merchant [BETA]', AT_ADMIN_TEXTDOMAIN ), __( 'Merchant [BETA]', AT_ADMIN_TEXTDOMAIN ), 'manage_options', THEME_PREFIX . 'merchant', array( 'AT_Admin', 'options' ), AT_Common::static_url( 'assets/images/admin/custom_pages_icons/20x20/merchant.png'), 107 );
		add_submenu_page( THEME_PREFIX . 'merchant', __( 'General Options' , AT_ADMIN_TEXTDOMAIN ), __( 'General Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'merchant_general', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'merchant', __( 'PayPal Options' , AT_ADMIN_TEXTDOMAIN ), __( 'PayPal Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'merchant_paypal', array( 'AT_Admin', 'options' ));
		add_submenu_page( THEME_PREFIX . 'merchant', __( 'Custom Gateway' , AT_ADMIN_TEXTDOMAIN ), __( 'Gateway Options' , AT_ADMIN_TEXTDOMAIN ), 'edit_themes', THEME_PREFIX . 'merchant_gateway', array( 'AT_Admin', 'options' ));
		remove_submenu_page( THEME_PREFIX . 'merchant', THEME_PREFIX . 'merchant' );

	}

	public static function options() { 
		if (isset($_GET['page'])){
			AT_Core::get_instance()->view->display();
		}
	}

	private function _remove_submenu( $menu_name, $submenu_name ) {
	    global $submenu;
	    $menu = $submenu[$menu_name];
	    if (!is_array($menu)) return;
	    foreach ($menu as $submenu_key => $submenu_object) {
	        if (in_array($submenu_name, $submenu_object)) {// remove menu object
	            unset($submenu[$menu_name][$submenu_key]);
	            return;
	        }
	    }
	}

	/**
	* gets the current post type in the WordPress Admin
	*/
	public static function get_current_post_type() {
		global $post, $typenow, $current_screen, $pagenow;
		if( $post && $post->post_type )
			$post_type = $post->post_type;
		elseif( $typenow )
			$post_type = $typenow;
		elseif( $current_screen && $current_screen->post_type )
			$post_type = $current_screen->post_type;
		elseif( isset( $_REQUEST['post_type'] ) )
			$post_type = sanitize_key( $_REQUEST['post_type'] );
		elseif ( 'post.php' == $pagenow && isset($_GET['post']) )
			$post_type = get_post_type($_GET['post']);
		elseif ( 'post-new.php' == $pagenow ){
			$post_type = 'post';
		}
		else
			$post_type = null;
		
		return $post_type;
	}
}