<?php
if (!defined("AT_DIR")) die('!!!');
class AT_Common {
	
	public function check_session(){
		$this->_is_logged = ( AT_Session::get_instance()->userdata( 'logged' ) === true ) ? true : false;
		if ( $this->is_user_logged() ) $this->_user_id = AT_Session::get_instance()->userdata( 'user_id' );
	}


	static public function price_format( $value = 0 ) {
		if ( !is_numeric($value) ) {
			$value = 0;
		}
		return number_format($value, 0, '.', ',');
	}

	static public function show_full_price( $value = 0, $currency ) {
		$value = self::price_format($value);

		if ( empty( $value ) ) {
			return false;
		}
		$location = AT_Core::get_instance()->get_option( 'currency_location', '1');

		$sign = AT_Core::get_instance()->get_option( 'currency_style', 'alias');

		$currency = $currency[$sign];

		if( $sign == 'name' ){
			$currency = ' ' . $currency . ' ';
		}

		$price = self::price_format($value);

		if ( $location == 1 ) {
			$price = $currency . $value;
		} else {
			$price = $value . $currency;
		}

		return $price;
	}

	static public function car_mileage( $view = 1 ){
		$car_mileage = AT_Core::get_instance()->get_option( 'car_mileage', 'miles');
		$types = array(
			'miles' => array(
				__( 'Miles', AT_ADMIN_TEXTDOMAIN ),
				__( 'miles', AT_ADMIN_TEXTDOMAIN ),
				__( 'miles', AT_ADMIN_TEXTDOMAIN ),
			),
			'kilometers' => array(
				__( 'Kilometers', AT_ADMIN_TEXTDOMAIN ),
				__( 'kilometers', AT_ADMIN_TEXTDOMAIN ),
				__( 'km', AT_ADMIN_TEXTDOMAIN ),
			)
		);
		return $types[$car_mileage][$view];
	}

	static public function  convert_time( $original = 0, $do_more = 0 ) {
		# array of time period chunks
		$chunks = array(
			array(60 * 60 * 24 * 365 , 'year'),
			array(60 * 60 * 24 * 30 , 'month'),
			array(60 * 60 * 24 * 7, 'week'),
			array(60 * 60 * 24 , 'day'),
			array(60 * 60 , 'hour'),
			array(60 , 'minute'),
		);

		$today = time();
		$since = $today - $original;

		for ($i = 0, $j = count($chunks); $i < $j; $i++) {
			$seconds = $chunks[$i][0];
			$name = $chunks[$i][1];

			if (($count = floor($since / $seconds)) != 0) {
				break;
			}
		}

		$print = ($count == 1) ? '1 '.$name : "$count {$name}s";

		if ($i + 1 < $j) {
			$seconds2 = $chunks[$i + 1][0];
			$name2 = $chunks[$i + 1][1];

			# add second item if it's greater than 0
			if ( (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) && $do_more ) {
				$print .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
			}
		}
		return $print;
	}

	static function get_current_page_url(){
		$page_url = AT_Router::get_instance()->server('HTTPS') == 'on' ? 'https://' : 'http://';
	    if ( AT_Router::get_instance()->server('SERVER_PORT') != '80' )
	    	$page_url .= AT_Router::get_instance()->server('SERVER_NAME') . ':' . AT_Router::get_instance()->server('SERVER_PORT') . AT_Router::get_instance()->server('REQUEST_URI');
	    else
	    	$page_url .= AT_Router::get_instance()->server('SERVER_NAME') . AT_Router::get_instance()->server('REQUEST_URI');
	    return $page_url;
	}

	static public function truncate( $content = '', $limit = 200 ) {
		$content = preg_replace('/<[^>]*>/', '', preg_replace('/\[[^>]*\]/', '', $content));
		$truncated = (strlen($content) > $limit) ? substr($content, 0, $limit) . '...' : $content;
		return $truncated;
	}

	static public function validate_id( $id = false ) {
		if ( $id && is_numeric($id) && $id > 0 ) {
			return true;
		} else if ( $id && is_array($id) && count($id) > 0 && isset($id[0]) && is_numeric($id[0]) ) {
			return true;
		}
		return false;
	}

	static public function get_icons(  $type = "all"  ) {
		$icons = array(
			'tools' => array(
				'icon-code' => 'icon-code',
				'icon-calendar' => 'icon-calendar',
				'icon-arrow' => 'icon-arrow',
				'icon-list' => 'icon-list',
				'icon-cancel' => 'icon-cancel',
				'icon-up' => 'icon-up',
				'icon-up-dir' => 'icon-up-dir',
				'icon-print' => 'icon-print',
				'icon-link' => 'icon-link',
				'icon-user' => 'icon-user',
				'icon-search' => 'icon-search',
				'icon-mail' => 'icon-mail',
				'icon-grid' => 'icon-grid',
				'icon-cancel-circled2' => 'icon-cancel-circled2',
				'icon-tag' => 'icon-tag',
				'icon-marker' => 'icon-marker',
				'icon-reply' => 'icon-reply',
			),
			'contacts' => array(
				'icon-skype' => 'Skype',
				'icon-email' => 'Email',
				'icon-chat' => 'Chat',
				'icon-comment' => 'Comment',
				'icon-phone' => 'Phone',
				'icon-mobile' => 'Mobile',
			),
			'social' => array(
				'icon-foursquare' => 'Four Square',
				'icon-vkontakte' => 'Vkontakte',
				'icon-youtube' => 'YouTube',
				'icon-github' => 'GitHub',
				'icon-vimeo' => 'Vimeo',
				'icon-twitter' => 'Twitter',
				'icon-facebook' => 'Facebook',
				'icon-gplus' => 'Google Plus',
				'icon-pinterest' => 'Pinterest',
				'icon-dribbble' => 'Dribbble',
				'icon-email' => 'Email',
				'icon-instagram' => 'Instagram',
				'icon-forrst' => 'Forrst',
				'icon-reddit' => 'Reddit',
				'icon-linkedin' => 'Linkedin',
				'icon-deviantart' => 'Deviantart',
			),
			'transport_types' => array(
				'filter-icon-all' => 'All',
				'filter-icon-car' => 'Car 1',
				'filter-icon-car-2' => 'Car 2',
				'filter-icon-moto' => 'Moto',
				'filter-icon-boat' => 'Boat',
				'filter-icon-tractor' => 'Tractor',
				'filter-icon-trailer' => 'Trailer 1',
				'filter-icon-trailer-2' => 'Trailer 2',
				'filter-icon-truck' => 'Truck',
				'filter-icon-bus' => 'Bus',
				'filter-icon-helicopter' => 'Helicopter',
				'filter-icon-parts' => 'Parts 1',
				'filter-icon-parts-2' => 'Parts 2',
				'filter-icon-parts-3' => 'Parts 3',
				'filter-icon-parts-4' => 'Parts 4',
				'filter-icon-service' => 'Service 1',
				'filter-icon-service-2' => 'Service 2',
			)

		);
		if ( $type == 'all') {
			$all = array();
			foreach( $icons as $group ) {
				$all = array_merge( $all, $group );
			}
			return $all;
		}

		return $icons[$type];
	}

	static public function trim_content($text = '', $length = 100, $ellipsis = '...') {
		$text = ( $text == '' ) ? get_the_content('') : $text;
		$text = preg_replace( '`\[(.*)]*\]`','',$text );
		if ( strlen ( $text ) > $length ) {
			$text = strip_tags( $text  );
			$text = substr( $text, 0, $length );
			$text = substr( $text, 0, strripos($text, " " ) );
			$text = $text.$ellipsis;
		}
	return $text;

	}

	static public function is_user_logged(){
		return ( AT_Session::get_instance()->userdata( 'logged' ) === true ) ? true : false;
	}

	static public function get_logged_user_id(){
		return !self::is_user_logged() ? 0 : AT_Session::get_instance()->userdata( 'user_id' );
	}

	static public function redirect(  $url = '', $status = '302'  ){
		wp_redirect( self::site_url( $url ), $status );
		exit;
	}

	static public function site_url(  $url = '', $root = true  ){
		//return !$root ? ( '/profile/' . ltrim( $url, '/' ) ) : ( '/' . ltrim( $url, '/' ) );
		if (strpos($url, 'http') === 0) return $url;
		return home_url() . '/' . ltrim( $url, '/' );
	}

	static public function static_url( $url = '' ){
		if (strpos($url, 'http') === 0) return $url;
		return AT_URI .'/' . ltrim( $url, '/' );
	}

	static public function wp_path() {
		if (strstr($_SERVER["SCRIPT_FILENAME"], "/wp-content/")) {
			return preg_replace("/\/DOCUMENT_ROOT\/.*/", "", $_SERVER["DOCUMENT_ROOT"]);
		}
		return preg_replace("/\/[^\/]+?\/themes\/.*/", "", $_SERVER["DOCUMENT_ROOT"]);
	}

	static public function nospam( $email, $filterLevel = 'normal' ) {
		$email = strrev( $email );
		$email = preg_replace( '[@]', '//', $email );
		$email = preg_replace( '[\.]', '/', $email );

		if( $filterLevel == 'low' ) 	{
			$email = strrev( $email );
		}	
		return $email;
	}

	static public function get_transport_icons () {
		return array(
			array( 'name' => 'Car 1', 'class' => 'filter-icon-car' ),
			array( 'name' => 'Car 2', 'class' => 'filter-icon-car-2' ),
			array( 'name' => 'Moto', 'class' => 'filter-icon-moto' ),
			array( 'name' => 'Boat', 'class' => 'filter-icon-boat' ),
			array( 'name' => 'Tractor', 'class' => 'filter-icon-tractor' ),
			array( 'name' => 'Trailer 1', 'class' => 'filter-icon-trailer' ),
			array( 'name' => 'Trailer 2', 'class' => 'filter-icon-trailer-2' ),
			array( 'name' => 'Truck', 'class' => 'filter-icon-truck' ),
			array( 'name' => 'Bus', 'class' => 'filter-icon-bus' ),
			array( 'name' => 'Helicopter', 'class' => 'filter-icon-helicopter' ),
			array( 'name' => 'Parts 1', 'class' => 'filter-icon-parts' ),
			array( 'name' => 'Parts 2', 'class' => 'filter-icon-parts-2' ),
			array( 'name' => 'Parts 3', 'class' => 'filter-icon-parts-3' ),
			array( 'name' => 'Parts 4', 'class' => 'filter-icon-parts-4' ),
			array( 'name' => 'Service 1', 'class' => 'filter-icon-service' ),
			array( 'name' => 'Service ', 'class' => 'filter-icon-service-2' ),
		);
	}
}
?>