<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Widgets {
	
	static protected $_widgets = array(
		'Advert',
		'Contact',
		'Sociable',
		'LoanCalculator',
		'AsideMenu',
		'FeaturedCar',
		'TextBlock',
		'Twitter',
		'Recent',
		'Workhours',
		'Map',
		'Manufacturers',
	);

	static public function register() {
		foreach( self::$_widgets as $widget ) {
			require_once AT_DIR . '/libraries/widgets/' . strtolower( $widget ) . '.php';
			$widget_class = 'AT_' . $widget . '_Widget';
			register_widget( $widget_class );
		}
	}

}