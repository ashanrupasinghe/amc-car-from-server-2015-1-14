<?php
if (!defined("AT_DIR")) die('!!!');
class AT_Registry {

	private static $_instance = null;
	private $_data = array();

	private function __construct() {

	}

	public function set( $key, $value ) {
		$this->_data[$key] = $value;
	}
	
	public function get( $key ) {
		return isset($this->_data[$key]) ? $this->_data[$key] : false;
	}
	
	public function get_all() {
		return $this->_data;
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