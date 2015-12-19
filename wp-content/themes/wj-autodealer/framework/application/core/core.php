<?php
if (!defined("AT_DIR")) die('!!!');
class AT_Core {

	private static $_instance = null;
	public static $is_404 = false;
	
	private $_options = array();

	public $view = null;
	
	private function __construct() {
		$this->view = new AT_View();
		$tmp = get_option( THEME_PREFIX . 'theme_options' );
		$this->_options = !$tmp ? array() : $tmp; 
	}

	static public function show_404() {
		include_once AT_DIR . '/controllers/errors.php';
		AT_Router::get_instance()->set_controller( 'AT_Errors' );
		AT_Router::get_instance()->set_method( 'show_404' );

        $controllerObj = new AT_Errors;
        call_user_func(array($controllerObj, 'show_404'));
        unset($controllerObj);
	}

	static public function show_underconstruction() {
		if (!is_super_admin()) {
			include_once AT_DIR . '/controllers/errors.php';
			AT_Router::get_instance()->set_controller( 'AT_Errors' );
			AT_Router::get_instance()->set_method( 'show_underconstruction' );

	        $controllerObj = new AT_Errors;
	        call_user_func(array($controllerObj, 'show_underconstruction'));
	        unset($controllerObj);
	    }
	}

	public function get_option( $item, $default = null ) {
		return isset($this->_options[$item]) ? $this->_options[$item] : $default;
	}

	public function get_options() {
		return $this->_options;
	}
	
	public function set_option( $item, $value ) {
		$this->_options[$item] = $value;
	}

	public function set_options( $options ) {
		$this->_options = $options;
	}

	public function save_option() {
		update_option(  THEME_PREFIX . 'theme_options', $this->_options );
	}

	static public function locale() {
		$locale = get_locale();		
		load_theme_textdomain( AT_TEXTDOMAIN, get_template_directory() . '/languages' );
		return;
	}

	static public function get_instance() {
		if(is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	protected function __clone() {
	}
}
?>