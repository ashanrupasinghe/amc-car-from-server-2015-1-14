<?php
if (!defined("AT_DIR")) die('!!!');

abstract class AT_VC_ShortCode {

	protected static $shortcode_id = 1;
	
	protected static function _shortcode_id() {
		$called_class = get_called_class();
	    return $called_class::$shortcode_id++;
	}

	public static function _options( $method ) {
		$called_class = get_called_class();
        return $called_class::$method('generator');
	}

}