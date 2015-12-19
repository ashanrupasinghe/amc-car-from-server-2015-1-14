<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Route {

	static function fronted(  $key = false  ){
		$rewrite = array(
			'profile' 	=> array( 'segment_start' => 1, 'regular_expressions' => array( '/([^/]*)', '/([^/]*)/([^/]*)' ), 'without_index' => false ),
			'welcome' 	=> array( 'segment_start' => 0, 'regular_expressions' => array( '/([^/]*)' ), 'without_index' => false ),
			'auth' 		=> array( 'segment_start' => 0, 'regular_expressions' => array( '/([^/]*)' ), 'without_index' => false ),
			'catalog' 	=> array( 'segment_start' => 0, 'regular_expressions' => array( '/([^/]*)', '/([^/]*)/([^/]*)' ), 'without_index' => true ),
			'dealer' 	=> array( 'segment_start' => 0, 'regular_expressions' => array( '/([^/]*)' ), 'without_index' => false ),
			'payments' 	=> array( 'segment_start' => 0, 'regular_expressions' => array( '/([^/]*)', '/([^/]*)/([^/]*)'  ), 'without_index' => false ),
			'merchant_paypal' => array( 'segment_start' => 0, 'regular_expressions' => array( '/([^/]*)', '/([^/]*)/([^/]*)'  ), 'without_index' => false ),
		);
		return ( $key ? $rewrite[$key] : $rewrite );
	}

	static function admin($page){
		$config = array();


		$config['at_merchant'] = array('merchant', 'index');
		$config['at_merchant_general'] = array('merchant', 'general');
		$config['at_merchant_paypal'] = array('merchant', 'paypal');
		$config['at_merchant_gateway'] = array('merchant', 'gateway');

		$config['at_reference'] = array('reference', 'index');
		$config['at_users'] = array('users', 'index');

		$config['at_theme_options_general'] = array('theme_options', 'general');
		$config['at_theme_options_header'] = array('theme_options', 'header');
		$config['at_theme_options_footer'] = array('theme_options', 'footer');
		$config['at_theme_options_sociable'] = array('theme_options', 'sociable');
		$config['at_theme_options_styled'] = array('theme_options', 'styled');
		$config['at_theme_options_blog'] = array('theme_options', 'blog');
		$config['at_theme_options_news'] = array('theme_options', 'news');
		$config['at_theme_options_reviews'] = array('theme_options', 'reviews');
		$config['at_theme_options_sidebars'] = array('theme_options', 'sidebars');
		$config['at_theme_options_backup'] = array('theme_options', 'backup');
		$config['at_theme_options_support'] = array('theme_options', 'support');
		$config['at_theme_options_release'] = array('theme_options', 'release');
		$config['at_theme_options_troubleshooting'] = array('theme_options', 'troubleshooting');

		$config['at_site_options_general'] = array('site_options', 'general');
		$config['at_site_options_filter'] = array('site_options', 'filter');
		$config['at_site_options_maintenance'] = array('site_options', 'maintenance');
		$config['at_site_options_registration'] = array('site_options', 'registration');
		$config['at_site_options_catalog'] = array('site_options', 'catalog');
		$config['at_site_options_car'] = array('site_options', 'car');
		$config['at_site_options_advertisement'] = array('site_options', 'advertisement');
		$config['at_site_options_texts'] = array('site_options', 'texts');
		$config['at_site_options_mailtemplate'] = array('site_options', 'mailtemplate');
		$config['at_site_options_loans'] = array('site_options', 'loans');
		
		$config['at_theme_install'] = array('install', 'index');

		return (isset($config[$page]) ? array_merge(array('admin'), $config[$page] ) : false);
	}

}