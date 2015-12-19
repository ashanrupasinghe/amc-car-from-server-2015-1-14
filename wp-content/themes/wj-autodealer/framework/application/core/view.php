<?php
if (!defined("AT_DIR")) die('!!!');
class AT_View {

	private $_page_layouts = array( 'content', 'content_right', 'left_content' );
	private $_layout_name = 'content';
	private $_data = array('root' => array('_view_data' => array(), '_view_block' => array()));
	private $_local_template_data = array();
	// private static $_instance = null;
	private $_scripts = array();
	private $_localize_script = array();
	private $_styles = array();
	private $_content = '';

	public  $global_sidebar = '';

	private $_widget_name = '';

	public function __construct() {
		$this->registry = AT_Registry::get_instance();
	}

	public function use_layout($layout_name){
		$this->_data['root']['_view_name'] = 'layouts/' . $layout_name;
		$this->_layout_name = $layout_name;
		return $this;
	}

	public function use_widget($widget_name) {
		$this->_widget_name = $widget_name;
        return $this;
	}

	public function add_json( $data ) {
		$this->_layout_name = 'json';
		$this->_data = $data;
		return $this;
	}

	public function add_block( $block_name, $view_name = '', $data = array() ) {
		$parts = explode('/', $block_name);
		$cur_point = &$this->_data['root']['_view_block'];
		$last_point = &$this->_data['root'];
		foreach ($parts as $value) {
			if (!array_key_exists($value, $cur_point)) {
				$cur_point[$value] = array();
				$cur_point[$value]['_view_data'] = array();
				$cur_point[$value]['_view_block'] = array();
			}
			$last_point = &$cur_point[$value];
			$cur_point = &$cur_point[$value]['_view_block'];
		}
		if (substr($block_name, 0, 2) != '//') {
			$last_point['_view_name'] = 'blocks/' . $view_name;
			if ($this->_widget_name == '') $last_point['_view_name'] = 'blocks/' . $view_name;
			else $last_point['_view_name'] = 'widgets/' . $this->_widget_name . '/' . $view_name;
		} else {
			$last_point['_view_name'] = substr($viewName, 2);
		}
		if (count($data) > 0) {
			$last_point['_view_data'] = array_merge($last_point['_view_data'], $data);
		}
        return $this;
	}

	public function add_script( $name, $script = '', $deps = array( 'jquery' ) ) {
		 if ( !isset( AT_Core::get_instance()->view->_scripts[$name] ) ) {
		 	if ( !empty($script) )
		 		AT_Core::get_instance()->view->_scripts[$name] = array( 'script' => $script, 'deps' => $deps );
		 	else
		 		AT_Core::get_instance()->view->_scripts[$name] = array();
		 }
	}
	
	public function add_localize_script( $handle, $object_name, $value = '' ) {
	 	AT_Core::get_instance()->view->_localize_script[$handle][$object_name] = $value;
	}

	public function add_style( $name, $style, $deps = array( ), $media = 'all' ) {
		 if ( !isset( AT_Core::get_instance()->view->_styles[$style] ) ) AT_Core::get_instance()->view->_styles[$style] = array( 'name' => $name, 'deps' => $deps, 'media' => $media );
	}

	public function render() {
		switch ($this->_layout_name) {
			case 'header_content_footer':
			case 'header_left_content_footer':
			case 'header_content_right_footer':
			case 'profile':
				if(!AT_Core::get_instance()->get_option('disable_breadcrumbs', true)){
					$items = AT_Breadcrumbs::get_instance()->get_all();
					if (count($items) > 1) {
						$this->add_block('breadcrumbs', 'general/breadcrumbs', array( 'items' => $items ));
					}
				}
			break;
			case 'content':
			break;
		}
		$data = $this->_data;
		ob_start();
		$this->_render($data, '');
		$this->_content = ob_get_clean();
		return $this;
	}

	public function display( $return = false ) {
		if (!AT_Core::$is_404 && $this->_widget_name == '' && !is_admin()) {
			header("HTTP/1.1 200 OK");
		}
		switch ($this->_layout_name) {
			case 'json':
				header('Content-type: application/json');
				$this->_content = json_encode( $this->_data );
			break;
			case 'content':
			break;
		}
		$content = $this->_content;
		$_layout_name = $this->_layout_name;
		$this->reset();
		if ( $return ) {
			return $content;
		} else {
			echo $content;
			unset($content);
		}
		if ($_layout_name == 'json'){
			exit();
		}
	}

	public function reset() {
		$this->_layout_name = 'content';
		$this->_widget_name = '';
		$this->_data = array('root' => array('_view_data' => array(), '_view_block' => array()));
		$this->_scripts = array();
		$this->_styles = array();
		$this->_content = '';

        return $this;
	}

	public function render_styles() {
		if ( !empty( $this->_styles ) ) {
			foreach ($this->_styles as $style => $item) {
            	wp_enqueue_style( THEME_PREFIX . $item['name'], AT_Common::static_url( $style ), ( empty( $item['deps'] ) ? false : $item['deps'] ), THEME_VERSION, $item['media'] ); //Required for printable version and screen
			}
		}
	}

	public function render_scripts() {
		if ( !empty( $this->_scripts ) ) {
			foreach ($this->_scripts as $name => $item) {
				if (count($item) > 0)
					wp_enqueue_script( THEME_PREFIX . $name, AT_Common::static_url( $item['script'] ), ( empty( $item['deps'] ) ? false : $item['deps'] ), THEME_VERSION );
				else
					wp_enqueue_script( $name,'','','');
					//wp_enqueue_script( $name );
			}
		}
		if ( !empty( $this->_localize_script ) ) {
			foreach ($this->_localize_script as $handle => $objects) {
				foreach ($objects as $object_name => $value) {
					wp_localize_script( THEME_PREFIX . $handle, $object_name, $value );
					
				}
			}
		}
	}

	public function render_admin_statics(){
		//wp_enqueue_script('post');
		//wp_enqueue_media();
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		$this->render_styles();
		$this->render_scripts();
	}

	private function _render($data, $full_name) {
		$blocks = array();
		foreach ($data as $key => $value) {
			$inner_blocks = array();
			if (count($value['_view_block']) > 0) {
				$inner_blocks = $this->_render($value['_view_block'], $full_name . '/' . $key);
			}
			if (!isset($value['_view_name'])) {
				$blocks[$key] = '';
				foreach ($inner_blocks as $value) $blocks[$key] .= $value;
			}	else {
				$value['_view_data']['block'] = $inner_blocks;
				//$blocks[$key] = $this->_smarty->view($value['_view_name'], $value['_view_data'], TRUE);
				$blocks[$key] = $this->_render_file($value['_view_name'], $value['_view_data']);
			}
			$block_name = substr($full_name . '/' . $key, 6);
		}

		if ($full_name != '') return $blocks;
		else echo $blocks['root'];
	}

	private function _render_file( $_file_name, $_file_data ) {
		extract( $_file_data );
		$this->_local_template_data = $_file_data;
		unset( $_file_data );
		ob_start();
		include AT_DIR . '/views/' . $_file_name . '.php';
		$content = ob_get_contents();  
	    ob_end_clean();
	    $this->_local_template_data = array();
	    return $content;
	}

	/*static public function get_instance() {
		if(is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}*/

	public function add_template( $template_name = '' ) {
		extract($this->_local_template_data);
		include AT_DIR . '/views/blocks/' . $template_name . '.php';
	}

	public function add_widget( $widget, $params = array(), $return = false ) {
		$object = AT_Loader::get_instance()->widget( $widget );
		$data = $object->render( $params );
		if (!$return) echo $data;
		else return $data;
	}

	public function get_option( $item, $default = null ) {
		if( $default != null )
			$value = AT_Core::get_instance()->get_option($item, $default);
		else
			$value = AT_Core::get_instance()->get_option($item);
		return ( !is_null($value) ? $value : '' );
	}

	public function check_layout( $layout_name ){
		return in_array( $layout_name, $this->_page_layouts );
	}

	// protected function __clone() {
	// }
}
