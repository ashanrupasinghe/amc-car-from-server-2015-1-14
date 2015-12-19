<?php
if (!defined("AT_DIR")) die('!!!');
class AT_Router {
	private $_segments = array();
	private $_file = 'welcome';
	private $_controller = 'welcome';
	private $_method = 'index';
	private $_arguments = array();
	private $_init = false;
	private $_is_admin = false;
	static public $without_index = false;

	private static $_instance = null;
	
	private function __construct( $segments ) {

		$this->_init = true;
		$this->_segments = $segments;

		if (!empty($segments[0])) {
			if ($segments[0] == 'admin' && is_admin()) {
				$this->_is_admin = true;
				array_splice($segments,0, 1);
			}
			$this->_controller = $segments[0];
			if (!empty($segments[1])) {
				$this->_method = $segments[1];
				array_splice($segments,0, 2);
				$this->_arguments = $segments;
			} else {
				$this->set_segment(1, $this->_method);
			}
		} else {
			$this->set_segment(0, $this->_controller);
			$this->set_segment(1, $this->_method);
		}
		if (function_exists('mb_strtolower')) {
			$this->_method = mb_strtolower($this->_method);
			$this->_controller = $this->_file = mb_strtolower($this->_controller);
		} else {
			$this->_method = strtolower($this->_method);
			$this->_controller = $this->_file = strtolower($this->_controller);
		}


		$t = substr($this->_controller, 0, 1);
		$t = mb_strtoupper($t);
		$this->_controller = 'AT_' . substr_replace($this->_controller, $t, 0, 1);
	}

	public function get_controller() {
		return $this->_controller;
	}

	public function set_controller( $controller ) {
		$this->_controller = $controller;
	}

	public function get_method() {
		return $this->_method;
	}

	public function set_method( $method ) {
		$this->_method = $method;
	}

	public function set_segment($segment, $value){
		$this->_segments[$segment] = $value;
	}

	public function segments( $segment = false ){
		return ( $segment === false ) ? $this->_segments : ( !empty( $this->_segments[$segment] ) ? $this->_segments[$segment] : false );
	}

	public function run(){
		if($this->_method != '__construct' && $this->_method != '__clone' && file_exists(AT_DIR . '/controllers/' . ($this->_is_admin ? 'admin/' : ''). $this->_file . '.php') ) {
			include_once AT_DIR . '/controllers/' . ($this->_is_admin ? 'admin/' : '') . $this->_file . '.php';
			if(method_exists($this->_controller, $this->_method)) {
	            $controllerObj = new $this->_controller();
	            call_user_func_array(array($controllerObj, $this->_method), $this->_arguments);
	            unset($controllerObj);
	        } else if( self::$without_index ) {
	        	$tmp = $this->_segments;
	        	$this->set_method( 'index' );
	        	$tmp = array_splice( $tmp, 1 );
	        	$this->_arguments = $tmp;
	        	array_unshift( $tmp, $this->_segments[0], 'index');
	        	$this->_segments = $tmp;
	        	
	        	$controllerObj = new $this->_controller();
	            call_user_func_array(array($controllerObj, $this->_method), $this->_arguments);
	            unset($controllerObj);
	        } else {
	        	AT_Core::get_instance()->show_404();
	        }
        } else {
        	AT_Core::get_instance()->show_404();
        }
	}

	public function ruri_string(){
		return trim($this->_controller . '/' . $this->_method . implode('/', $this->_arguments), '/');
	}

	public function server( $env ) {
		if ( getenv( $env ) ) {
			return getenv( $env );
		} else if ( isset( $_SERVER[$env] ) ) {
			return $_SERVER[$env];
		} else {
			return false;
		}
	}

	public function is_ajax_request() {
		return ($this->server('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest');
	}

	static public function get_instance( $segments = array() ) {
		if(is_null(self::$_instance)) {
			self::$_instance = new self( $segments );
		}
		return self::$_instance;
	}
	
	protected function __clone() {
	}

	//////////////////////////////////////////////////////////////////////////////////////////////
	// Static Methods
	//////////////////////////////////////////////////////////////////////////////////////////////

	//////////////////////////////////////////////////////////////////////////////////////////////
	// Add Rewrite rule
	//////////////////////////////////////////////////////////////////////////////////////////////
	static public function add_rewrite_rules() {
	    global $wp,$wp_rewrite; 

	    $wp->add_query_var( 'profile' );
	    foreach( AT_Route::fronted() as $key=>$params ){
			$wp_rewrite->add_rule('^' .$key, 'index.php?profile=true', 'top');
			foreach ( $params['regular_expressions'] as $key => $expression ) {
				$wp_rewrite->add_rule('^' .$key . $expression, 'index.php?profile=true', 'top');
			}

	    }
	}


	static public function route() {
		$coreProfile = AT_Core::get_instance();

		try {
			if ( is_admin() ){
				// init admin panel
				if (isset($_GET['page'])){
					if($segments = AT_Route::admin($_GET['page'])) {
						// if (isset($_GET['tab']))
						// 	$segments[2] = $_GET['tab'];
						throw new Exception( serialize($segments) );
					}
				} else if ( isset( $_GET['activated'] ) ){
					global $pagenow;
					if( $pagenow === 'themes.php' ) {
						throw new Exception( serialize( array( 'admin', 'install', 'redirect' ) ) );
					}
				}
			} else {	
				////////////////////////////////////////////////////
				// view inited URI
				////////////////////////////////////////////////////
				//$segments =  explode( '/', ltrim( trim($_SERVER['REQUEST_URI'], '/' ), 'index.php' ) );

				$host = explode( rtrim( $_SERVER['HTTP_HOST'], '/' ), rtrim( home_url(), '/' ) );
				$request_uri = trim($_SERVER['REQUEST_URI'], '/' );
				if( !empty( $host[1] ) ) {
					$request_uri = substr_replace($request_uri, '', 0, strlen($host[1]));
					$request_uri = trim($request_uri, '/' );
				}
				
				$request_uri = explode( '?', $request_uri );
				$segments =  explode( '/', trim($request_uri[0], '/' ) );


				if ( !empty( $segments[0] ) && array_key_exists( $segments[0], AT_Route::fronted() ) ){
					$param_segment = AT_Route::fronted( $segments[0] );
					array_splice( $segments, 0, $param_segment['segment_start'] );
					if (!isset($segments[0])) {
						$segments[0] = 'vehicles';	
					}
					if ( $param_segment['without_index'] ){
						self::$without_index = $param_segment['without_index'];
					}
					throw new Exception( serialize($segments) );
				}

				////////////////////////////////////////////////////
				// view inited single post type
				////////////////////////////////////////////////////
				if (is_single() && in_array( get_post_type(), AT_Posttypes::get_post_types() ) ){
					$segments = array(get_post_type(), 'single' );
					throw new Exception( serialize($segments) );
				}

				if( is_archive() && ( is_category() || is_tag() || is_day() || is_month() || is_year() || is_author() || is_tax() ) ) {
						throw new Exception( serialize( array( 'post', 'archive' ) ) );
				}

				////////////////////////////////////////////////////
				// view inited archive custom post type
				// news, reviews
				////////////////////////////////////////////////////
				if ( is_archive() && in_array( get_post_type(), AT_Posttypes::get_post_types() ) ){
					if( isset($segments[1]) && $segments[1] == 'page' && isset($segments[2]) && is_numeric($segments[2]) )
						throw new Exception( serialize( array( get_post_type(), 'archive', $segments[2] ) ) );
					else
						throw new Exception( serialize( array( get_post_type(), 'archive' ) ) );
					//$segments = array(get_post_type(), 'archive' );
					throw new Exception( serialize($segments) );
				}

				////////////////////////////////////////////////////
				// page view
				////////////////////////////////////////////////////
				if ( is_page() ) {
					$segments = array('page', 'index' );
					throw new Exception( serialize($segments) );
				}

				////////////////////////////////////////////////////
				// category view
				////////////////////////////////////////////////////
				if (is_category()) {
					throw new Exception( serialize( array( 'post', 'archive' ) ) );	
				}

				////////////////////////////////////////////////////
				// front page view
				////////////////////////////////////////////////////
				if (is_front_page()) {
					throw new Exception( serialize( array( 'blog', 'index' ) ) );
				}

				////////////////////////////////////////////////////
				// blog view
				////////////////////////////////////////////////////
				if (is_home()) {
					if( isset($segments[1]) && $segments[1] == 'page' && isset($segments[2]) && is_numeric($segments[2]) )
						throw new Exception( serialize( array( 'blog', 'index', $segments[2] ) ) );
					else
						throw new Exception( serialize( array( 'blog', 'index' ) ) );
				}
				
				////////////////////////////////////////////////////
				// search view
				////////////////////////////////////////////////////
				if (is_search()) {
					throw new Exception( serialize( array( 'search', 'index' ) ) );
				}

				////////////////////////////////////////////////////
				// 404 view
				////////////////////////////////////////////////////
				if (is_404()) {
					throw new Exception( serialize( array( 'errors', 'show_404' ) ) );
				}

				if (is_attachment()) {
					throw new Exception( serialize( array( 'post', 'single' ) ) );
				}

				if (is_single()) {
					throw new Exception( serialize( array( 'post', 'single' ) ) );
				}

				if ( is_feed() ) {
					throw new Exception('');
				}

				// other pages && post types
				throw new Exception( serialize( array( 'errors', 'show_404' ) ) );
				//echo "don't find";
				die();
			}

		} catch (Exception $e) {
			if ( $e->getMessage() != '' ){
				$route = AT_Router::get_instance( unserialize($e->getMessage()) );
				$route->run();
			}
		}
	}
}
?>