<?php

class AT_Module_Init {

	static public function init() {

		self::_defined();
		$files = glob(AT_DIR . "/core/*.php");
		foreach($files as $file) if (!is_dir($file)) {
			require_once($file);
		}

		AT_Loader::get_instance()->helper('common');
		AT_Loader::get_instance()->helper('route');
		AT_Loader::get_instance()->library( 'Shortcodes', false );
		AT_Loader::get_instance()->library( 'Widgets', false );

		AT_Core::locale();

		// setup_theme
		if ( is_admin() ) {
			AT_Admin::init();
			self::_support();
			return;
		}

		self::_support();
		// self::_locale();
		self::_filters();
		self::_actions();
	}

	static protected function _support(){
		add_theme_support( 'menus' );
		add_theme_support( 'widgets' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'html5' );

		if ( function_exists( 'add_image_size' ) ) {
			add_theme_support( 'post-thumbnails' );
			set_post_thumbnail_size( 258, '' );
		}

		if ( function_exists( 'at_custom_style_mgr' ) ) {
			add_theme_support( 'custom-header' );
			add_theme_support( 'custom-background' );
		}
	}

	// static protected function _locale(){
	// 	$locale = get_locale();		
	// 	load_theme_textdomain( AT_TEXTDOMAIN, get_template_directory() . '/languages' );
	// }

	static protected function _actions(){
		if ( ! wp_next_scheduled( 'at_cron_hourly_task_hook' ) ) {
			//wp_schedule_event( time(), 'hourly', 'at_cron_task_hook' );
			wp_schedule_event( time(), 'hourly', 'at_cron_hourly_task_hook' );
		}

		add_action( 'at_cron_hourly_task_hook', array( 'AT_Module_Init', 'cron_hourly_task') );

		add_action( 'widgets_init', array( 'AT_Sidebars', 'register') );
		add_action( 'widgets_init', array( 'AT_Widgets', 'register') );
		add_action( 'init', array( 'AT_Posttypes', 'register') );
		add_action( 'init', array( 'AT_Posttypes', 'custom_post_status' ) );
		add_action( 'init', array( 'AT_Shortcodes', 'init' ) );
		add_action( 'init', array( 'AT_Router', 'add_rewrite_rules' ) );
		add_action( 'init', array( 'AT_Module_Init', 'init_actions' ) );
		add_action( 'wp', array( 'AT_Router', 'route' ) );
	}

	static public function init_actions() {
		add_action( 'wp_enqueue_scripts', array( AT_Core::get_instance()->view, 'render_styles') );
		add_action( 'wp_enqueue_scripts', array( AT_Core::get_instance()->view, 'render_scripts') );
		add_action( 'template_redirect', array( AT_Core::get_instance()->view, 'render' ) );
		add_action( 'template_redirect', array( AT_Core::get_instance()->view, 'display' ) );
		add_action( 'after_setup_theme', array( 'AT_Core', 'locale' ) ); // MOVE TO after_setup_theme IN RELEASE 2.0

	}

	static protected function _defined(){
		$theme_data = wp_get_theme();

		define( 'AT_DIR_THEME', dirname( __FILE__ ) );
		define( 'AT_DIR', dirname( __FILE__ ) . '/application' );
		define( 'AT_URI_THEME', get_template_directory_uri() );

		$upload_dir = wp_upload_dir();
		define( 'AT_UPLOAD_URI_THEME', set_url_scheme( $upload_dir['baseurl'] . '/at_usr_data' ) );
		define( 'AT_UPLOAD_DIR_THEME', $upload_dir['basedir'] . '/at_usr_data' );
		define( 'AT_URI', AT_URI_THEME . '/framework' );

		define( 'THEME_NAME', $theme_data->name );
		define( 'THEME_VERSION', $theme_data->version );
		define( 'THEME_PREFIX', 'at_' );
		define( 'THEME_SLUG', get_template() );
		define( 'THEME_ADMIN_ASSETS_URI', '' );

		define( 'AT_TEXTDOMAIN', THEME_SLUG );
		define( 'AT_ADMIN_TEXTDOMAIN', THEME_SLUG );

	}

	static protected function _filters(){
		add_filter('body_class', array( 'AT_Filters', 'add_body_class' ));
		remove_filter('template_redirect', 'redirect_canonical');
	}


	// Expire cron
	static public function cron_hourly_task() {
		$payments_model = AT_Loader::get_instance()->model('payments_model');
		// var_dump($payments_model);
		
	}

}