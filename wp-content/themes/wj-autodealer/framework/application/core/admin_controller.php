<?php
if (!defined("AT_DIR")) die('!!!');
abstract class AT_Admin_Controller {

	public $view = null;
	public $load = null;
	public $core = null;

	private $_block_id = 0;
	protected $_group_struct = '';
	protected $_group_name = '';
	protected $_group_item_values = array();

	public function __construct() {
		$this->core = AT_Core::get_instance();

		if ( !$this->core->get_option( 'theme_is_activated', false ) && isset($_GET['page']) && $_GET['page'] != 'at_theme_install' ) {
			wp_redirect('admin.php?page=at_theme_install');
		}

		$this->load = AT_Loader::get_instance();
		if (is_null($this->view)) {
			$this->view = $this->core->view;
			$this->uri = AT_Router::get_instance();
		}
	}

	protected function _parse_fields( $fields = array() ){
		foreach( $fields as $key => $item ) {
			$field = '_field_' . $item['type'];
			$item['name'] = $this->_get_field_name( $key );
			$this->$field( $item );
			$this->_block_id++;
		}
	}

	protected function _parse_values( $fields = array() ){
		$core = AT_Core::get_instance();
		foreach( $fields as $key => &$item ) {
			if (is_numeric($key)) continue;
			if ( !empty( $this->_group_name ) ) {
				$val = isset( $this->_group_item_values[$key] ) ? $this->_group_item_values[$key] : null;
			} else {
				$val = $core->get_option( $key );
			}
			$item['value'] = is_null( $val ) ? $item['default'] : $val;
		}
		return $fields;
	}

	protected function _save_fields( $fields = array(), $post = array(), $return = false ){
		if ( count( $fields ) == 0 ) return false;
		$core = AT_Core::get_instance();
		$values = array();
		foreach( $fields as $key => $item ) {
			switch( $item['type'] ) {
				case 'catalog_search_form':
					$value = isset( $post[$key]['value'] ) ? $post[$key]['value'] : array();
					break;
				case 'block':
					$value = isset( $post[$key] ) ? $this->_save_fields( $item['fields'], $post[$key], true) : $item['default'];
					break;
				case 'group':
					$value = isset( $post[$key] ) ? $this->_save_group( $item['fields'], $post[$key] ) : $item['default'];
					break;
				case 'backup':
				case 'info':
				case 'extensions_check':
				case 'permissions_check':
				case 'php_info':
					$value = '';
					continue 2;
					break;
				case 'restore':
					if ( !empty( $post[$key] ) ){
						$options =  unserialize( base64_decode( $post[$key] ) ) ;
						$core->set_options( $options );
					}
					continue 2;
					break;
				case 'checkbox':
					$value = isset( $post[$key] ) ? true : false;
					break;
				case 'checkbox':
					$value = false;
					break;
				default:
					$value = isset( $post[$key] ) ? $post[$key] : $item['default'];
					break;
			}
			if (!$return){
				$core->set_option( $key, $value );
			} else {
				$values[$key] = $value;
			}
		}
		if (!$return) $core->save_option();
		return $values;
	}

	private function _save_group( $fields, $post ){
		$group = array();
		foreach ($post as $key => $post_items) {
			$group[] = $this->_save_fields( $fields, $post_items, true);
		}
		return $group;
	}

	private function _get_block_id(){
		return ( !empty( $this->_group_struct ) ? ( $this->_group_struct . '/' ) : 'content/content/' ) . $this->_block_id;
	}

	private function _get_field_name( $name ){
		return ( !empty($this->_group_name ) ? $this->_group_name : THEME_PREFIX . 'options' ) . '[' . $name . ']'  ;
	}

	private function _field_input_text( $args ){
		$defaults = array(
			'title' => '',
			'description' => '',
			'disabled' => '',
			'name' => '',
			'toggle_class' => '',
			'toggle' => false,
			'input' => 'text',
			'value' => ''
		);
		$args = wp_parse_args( $args, $defaults );
		$args['value'] = htmlspecialchars( stripslashes( $args['value'] ) );
		$this->view->add_block( $this->_get_block_id(), 'admin/fields/input_'.$args['input'], $args );
	}

	private function _field_checkbox( $args ){
		$defaults = array(
			'title' => '',
			'description' => '',
			'name' => '',
			'toggle_class' => '',
			'toggle' => '',
			'value' => false
		);
		$args = wp_parse_args( $args, $defaults );
		$this->view->add_block( $this->_get_block_id(), 'admin/fields/checkbox', $args );
	}

	private function _field_radio( $args ){
		$defaults = array(
			'title' => '',
			'description' => '',
			'name' => '',
			'value' => '',
			'toggle_class' => '',
			'toggle' => '',
			'items' => array()
		);
		$args = wp_parse_args( $args, $defaults );
		$this->view->add_block( $this->_get_block_id(), 'admin/fields/radio', $args );
	}

	private function _field_radio_image( $args ){
		$defaults = array(
			'title' => '',
			'description' => '',
			'name' => '',
			'value' => '',
			'toggle_class' => '',
			'toggle' => '',
			'items' => array()
		);
		$args = wp_parse_args( $args, $defaults );
		$this->view->add_block( $this->_get_block_id(), 'admin/fields/radio_image', $args );
	}

	private function _field_select( $args ){
		$defaults = array(
			'id' => '',
			'title' => '',
			'description' => '',
			'name' => '',
			'value' => '',
			'first_not_view' => false,
			'items' => array(),
			'sub_fields' => false
		);
		$args = wp_parse_args( $args, $defaults );
		$this->view->add_block( $this->_get_block_id(), 'admin/fields/select', $args );
	}

	private function _field_info( $args ){
		$defaults = array(
			'title' => '',
			'description' => '',
		);
		$args = wp_parse_args( $args, $defaults );
		$this->view->add_block(  $this->_get_block_id(), 'admin/fields/info', $args );
	}

	private function _field_extensions_check( $args ){
		$defaults = array(
			'title' => '',
			'description' => '',
		);
		$args = wp_parse_args( $args, $defaults );
		$this->view->add_block(  $this->_get_block_id(), 'admin/fields/extensions_check', $args );
	}

	private function _field_pushtocall( $args ){
		$defaults = array(
			'title' => '',
			'description' => '',
			'button' => '',
			'action' => ''
		);
		$args = wp_parse_args( $args, $defaults );
		$this->view->add_block(  $this->_get_block_id(), 'admin/fields/pushtocall', $args );
	}

	private function _field_permissions_check( $args ){
		$defaults = array(
			'title' => '',
			'description' => '',
		);
		$args = wp_parse_args( $args, $defaults );
		$this->view->add_block(  $this->_get_block_id(), 'admin/fields/permissions_check', $args );
	}

	private function _field_php_info( $args ){
		$defaults = array(
			'title' => '',
			'description' => '',
		);
		$args = wp_parse_args( $args, $defaults );
		$this->view->add_block(  $this->_get_block_id(), 'admin/fields/php_info', $args );
	}

	private function _field_textarea( $args ){
		$defaults = array(
			'title' => '',
			'description' => '',
			'name' => '',
			'class' => 'default',
			'toggle_class' => '',
			'toggle' => '',
			'value' => ''
		);
		$args = wp_parse_args( $args, $defaults );
		$args['value'] = htmlspecialchars( stripslashes( $args['value'] ) );
		$this->view->add_block(  $this->_get_block_id(), 'admin/fields/textarea', $args );
	}

	private function _field_restore( $args ){
		$defaults = array(
			'title' => '',
			'description' => '',
			'name' => '',
			'value' => ''
		);
		$args = wp_parse_args( $args, $defaults );
		$this->view->add_block(  $this->_get_block_id(), 'admin/fields/textarea', $args );
	}

	private function _field_backup( $args ){
		$defaults = array(
			'title' => '',
			'description' => '',
			'name' => '',
			'value' => ''
		);
		$args = wp_parse_args( $args, $defaults );
		$args['value'] = base64_encode( serialize( AT_Core::get_instance()->get_options() ) );
		$this->view->add_block(  $this->_get_block_id(), 'admin/fields/textarea', $args );
	}

	private function _field_icon( $args ){
		$defaults = array(
			'title' => '',
			'description' => '',
			'toggle_class' => '',
			'toggle' => '',
			'name' => '',
			'value' => ''
		);
		$args = wp_parse_args( $args, $defaults );
		$args['value'] = htmlspecialchars( stripslashes( $args['value'] ) );
		$this->view->add_block(  $this->_get_block_id(), 'admin/fields/icon', $args );
	}

	private function _field_catalog_search_form( $args ){
		$defaults = array(
			'title' => '',
			'description' => '',
			'name' => '',
			'view_title' => true,
			'value' => array(),
			'sets' => array()
		);
		$args = wp_parse_args( $args, $defaults );
		foreach ($args['sets'] as $key => $item) {
			if (isset($args['value'][$key]) ) {
				$args['value'][$key] = $item;
				unset( $args['sets'][$key] );
			}
		}
		$this->view->add_block(  $this->_get_block_id(), 'admin/fields/catalog_search_form', $args );
	}

	private function _field_block( $args ){
		$defaults = array(
			'title' => '',
			'value' => array(),
		);
		$args = wp_parse_args( $args, $defaults );
		$local_block_id = $this->_get_block_id();
		$this->view->add_block(  $local_block_id, 'admin/fields/block', $args );


		$this->_group_name = $args['name'];
		$this->_group_item_values = $args['value'];
		$fields = $this->_parse_values( $args['fields'] );
		
		$this->_group_struct = $local_block_id . '/items';
		$this->_parse_fields( $fields );

		$this->_group_struct = '';
		$this->_group_name = '';
		$this->_group_item_values = array();
	}

	private function _field_group( $args ){
		$defaults = array(
			'title' => '',
			'description' => '',
			'submit' => __( "Add Item", AT_ADMIN_TEXTDOMAIN ),
		);
		$args = wp_parse_args( $args, $defaults );

		$this->view->add_block(  $this->_get_block_id(), 'admin/fields/group', $args );
		
		$local_block_id = $this->_get_block_id();

		// Empty Item
		$this->view->add_block( $local_block_id . '/items/0', 'admin/fields/group_item', 
				array( 
					'title' => __( 'Add Item {n}', AT_ADMIN_TEXTDOMAIN ), 
					'empty_form' => true 
				) );
		$this->_group_struct = $local_block_id . '/items/0/item';
		$this->_group_name = $args['name'] . '[{id}]';
		$this->_group_item_values = array();
		$fields = $this->_parse_values( $args['fields'] );
		$this->_parse_fields( $fields );

		// last saved items
		$i = 1;
		
		foreach ($args['value'] as $key => $items) {
			$this->_group_name = $args['name'] . '[' . $i . ']';
			$this->_group_item_values = $items;
			$fields = $this->_parse_values( $args['fields'] );
			reset($fields);
			$first_item = current($fields);
			$this->view->add_block( $local_block_id . '/items/' . $i, 'admin/fields/group_item', 
				array( 
					'title' => __( 'Item: ', AT_ADMIN_TEXTDOMAIN ) . ( !is_array( $first_item['value'] ) ? $first_item['value'] : $i ),
					//'title' => __( 'Item: ', AT_ADMIN_TEXTDOMAIN ) . $i,
					'id' => $i

				) );
			
			$this->_group_struct = $local_block_id . '/items/' . $i . '/item';
			$this->_parse_fields( $fields );
			$i++;
		}

		$this->_group_struct = '';
		$this->_group_name = '';
		$this->_group_item_values = array();
	}

	private function _field_editor( $args ){
		$defaults = array(
			'title' => '',
			'description' => '',
			'name' => '',
			'value' => ''
		);
		$args = wp_parse_args( $args, $defaults );
		ob_start();
		wp_editor( $args['value'], $args['name'], array( "textarea_name" => $args['name'] ) );
		$args['editor'] = ob_get_contents();
		ob_end_clean();
		$this->view->add_block(  $this->_get_block_id(), 'admin/fields/editor', $args );
	}

	private function _field_upload( $args ){
		$defaults = array(
			'id' => '',
			'title' => '',
			'description' => '',
			'name' => '',
			'value' => ''
		);
		$args = wp_parse_args( $args, $defaults );
		$this->view->add_block(  $this->_get_block_id(), 'admin/fields/upload', $args );
	}

	private function _field_car_photo_upload( $args ){
		$defaults = array(
			'value' => '',
			'items' => ''
		);
		$args = wp_parse_args( $args, $defaults );
		global $post;
		if ( $post->post_type == 'car' ) {
			$photo_model = $this->load->model( 'photo_model' );
			$args['items'] = $photo_model->get_photos_by_post( $post->ID, 'car' );
			$this->view->add_block(  $this->_get_block_id(), 'admin/fields/car_photo_upload', $args );
		}
	}

	private function _field_range( $args ){
		$defaults = array(
			'title' => '',
			'description' => '',
			'value' => '',
			'min' => '',
			'max' => '',
			'step' => '',
			'unit' => '',
		);
		$args = wp_parse_args( $args, $defaults );
		$this->view->add_block(  $this->_get_block_id(), 'admin/fields/range', $args );
	}

	private function _field_date( $args ){
		$defaults = array(
			'title' => '',
			'description' => '',
			'format' => '',
			'min_date' => '',
			'value' => '',
		);
		$args = wp_parse_args( $args, $defaults );
		$this->view->add_block(  $this->_get_block_id(), 'admin/fields/date', $args );
	}

}
?>