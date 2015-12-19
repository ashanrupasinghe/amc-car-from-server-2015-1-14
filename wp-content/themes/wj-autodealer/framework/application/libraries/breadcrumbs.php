<?php
if (!defined("AT_DIR")) die('!!!');
class AT_Breadcrumbs {

	private $_data = array();
    private static $_instance;	
	
	public function __construct() {
		$this->add_item( __('Home', AT_TEXTDOMAIN), '/' );
	}

	public static function get_instance() {
        self::$_instance = self::$_instance instanceof AT_Breadcrumbs ? self::$_instance : new AT_Breadcrumbs();
        return self::$_instance;
    }

    public function add_item($name, $url) {
        $this->_data[] = array('name' => $name,'url' => AT_Common::site_url($url));
    }

    public function get_all() {
        return $this->_data;
    }
}