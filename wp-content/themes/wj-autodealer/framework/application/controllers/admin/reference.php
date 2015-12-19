<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Reference extends AT_Admin_Controller{
	public function __construct() {
		parent::__construct();
	}

	public function index(){
		$tab = !empty($_GET['tab']) ? $_GET['tab'] : 'manufacturers';

		if ($this->uri->is_ajax_request() && isset($_GET['get_references']) ) {
			return $this->_get_references();
		}

		if( $this->uri->is_ajax_request() && !empty( $_POST ) ) {
			return $this->_ajax();
		}

		$reference_model = $this->load->model('reference_model');
		$additionals = array();
		switch ($tab) {
			case 'models':
				$template = 'models';
				$additionals['manufacturers'] = $reference_model->get_manufacturers();
				$additionals['manufacturer_id'] = !empty($_GET['manufacturer_id']) ? (int)$_GET['manufacturer_id'] : '0';
				$data = $reference_model->get_models_by_manufacturer_id($additionals['manufacturer_id']);
				$sortable = false;
				break;
			case 'body_types':
				$template = 'two_columns';
				$data = $reference_model->get_body_types();
				$sortable = true;
				break;
			case 'currencies':
				$template = 'three_columns';
				$data = $reference_model->get_currencies();
				$sortable = true;
				break;
			case 'doors':
				$template = 'two_columns';
				$data = $reference_model->get_doors();
				$sortable = false;
				break;
			case 'equipments':
				$template = 'equipments';
				$data = $reference_model->get_equipments();
				$sortable = false;
				break;
			case 'fuels':
				$template = 'two_columns';
				$data = $reference_model->get_fuels();
				$sortable = true;
				break;
			case 'technical_conditions':
				$template = 'two_columns';
				$data = $reference_model->get_technical_conditions();
				$sortable = true;
				break;
			case 'transmissions':
				$template = 'two_columns';
				$data = $reference_model->get_transmissions();
				$sortable = true;
				break;
			case 'transport_types':
				$template = 'three_columns';
				$data = $reference_model->get_transport_types();
				$sortable = true;
				break;
			case 'states':
				$template = 'states';
				$additionals['regions'] = $reference_model->get_regions();
				$additionals['region_id'] = !empty($_GET['region_id']) ? (int)$_GET['region_id'] : '0';
				$data = $reference_model->get_states_by_region_id($additionals['region_id']);
				$sortable = false;
				break;
			case 'regions':
				$template = 'three_columns';
				$data = $reference_model->get_regions();
				$sortable = true;
				break;
			case 'drive':
				$template = 'two_columns';
				$data = $reference_model->get_drive();
				$sortable = true;
				break;
			case 'colors':
				$template = 'three_columns';
				$data = $reference_model->get_colors();
				$sortable = true;
				break;
			default:
				$tab = 'manufacturers';
				$template = 'three_columns';
				$data = $reference_model->get_manufacturers();
				$sortable = false;
				break;
		}

		$menu_model = $this->load->model('admin/menu_model');
		$this->view->use_layout('admin')
			->add_block('content', 'admin/reference/content', array('tab' => $tab))
			->add_block('content/tabs', 'admin/general/tabs', $menu_model->get_menu('reference', $tab))
			->add_block('content/items', 'admin/reference/' . $template, array('items' => $data, 'additionals' => $additionals, 'tab' => $tab, 'sortable' => $sortable));
	}

	private function _ajax(){
		try {
			if ( empty( $_POST['action'] ) || empty( $_POST['tab'] ) ) {
				throw new Exception( 'Error!' );
			}
			if ( $_POST['action'] != 'get_add_form' && $_POST['action'] != 'add_item' && $_POST['action'] != 'save_sort' ) {
				if ( $_POST['tab'] != 'equipments' ){
					$_POST['item_id'] = (int)$_POST['item_id'];
					if ( $_POST['item_id'] == 0 ) {
						throw new Exception( 'Error!' );
					}
				} else {
					if ( empty($_POST['item_id']) ) {
						throw new Exception( 'Error!' );
					}
				}
			}

			$reference_model = $this->load->model('reference_model');
			switch ( $_POST['action'] ) {
				case 'item_delete':
					switch ( $_POST['tab'] ) {
						case 'manufacturers':
							$res = $reference_model->delete_manufacturer_by_id($_POST['item_id']);
							break;
						case 'models':
							$res = $reference_model->delete_model_by_id($_POST['item_id']);
							break;
						case 'body_types':
							$res = $reference_model->delete_body_type_by_id($_POST['item_id']);
							break;
						case 'currencies':
							$res = $reference_model->delete_currency_by_id($_POST['item_id']);
							break;
						case 'doors':
							$res = $reference_model->delete_door_by_id($_POST['item_id']);
							break;
						case 'equipments':
							$res = $reference_model->delete_equipment_by_alias($_POST['item_id']);
							break;
						case 'fuels':
							$res = $reference_model->delete_fuel_by_id($_POST['item_id']);
							break;
						case 'technical_conditions':
							$res = $reference_model->delete_technical_condition_by_id($_POST['item_id']);
							break;
						case 'transmissions':
							$res = $reference_model->delete_transmission_by_id($_POST['item_id']);
							break;
						case 'transport_types':
							$res = $reference_model->delete_transport_type_by_id($_POST['item_id']);
							break;
						case 'regions':
							$res = $reference_model->delete_region_by_id($_POST['item_id']);
							break;
						case 'states':
							$res = $reference_model->delete_state_by_id($_POST['item_id']);
							break;
						case 'drive':
							$res = $reference_model->delete_drive_by_id($_POST['item_id']);
							break;
						case 'colors':
							$res = $reference_model->delete_color_by_id($_POST['item_id']);
							break;
						default:
							throw new Exception( 'Error!' );
							break;
					}
					if (!$res)
						throw new Exception( 'Failed deleted!' );
					$message = __( 'Item was deleted.', AT_ADMIN_TEXTDOMAIN );
					break;
				case 'get_edit_form':
					switch ( $_POST['tab'] ) {
						case 'manufacturers':
							$template = 'form';
							$get_alias = true;
							$item = $reference_model->get_manufacturer_by_id( $_POST['item_id'] );
							break;
						case 'models':
							$template = 'form';
							$get_alias = true;
							$item = $reference_model->get_model_by_id( $_POST['item_id'] );
							break;
						case 'body_types':
							$template = 'form';
							$get_alias = false;
							$item = $reference_model->get_body_type_by_id( $_POST['item_id'] );
							break;
						case 'currencies':
							$template = 'form';
							$get_alias = true;
							$item = $reference_model->get_currency_by_id( $_POST['item_id'] );
							break;
						case 'doors':
							$template = 'form';
							$get_alias = false;
							$item = $reference_model->get_door_by_id( $_POST['item_id'] );
							break;
						case 'equipments':
							$template = 'form';
							$get_alias = false;
							$item = $reference_model->get_equipment_by_alias( $_POST['item_id'] );
							break;
						case 'fuels':
							$template = 'form';
							$get_alias = false;
							$item = $reference_model->get_fuel_by_id( $_POST['item_id'] );
							break;
						case 'technical_conditions':
							$template = 'form';
							$get_alias = false;
							$item = $reference_model->get_technical_condition_by_id( $_POST['item_id'] );
							break;
						case 'transmissions':
							$template = 'form';
							$get_alias = false;
							$item = $reference_model->get_transmission_by_id( $_POST['item_id'] );
							break;
						case 'transport_types':
							$template = 'form';
							$get_alias = true;
							$item = $reference_model->get_transport_type_by_id( $_POST['item_id'] );
							$item['icons'] = AT_Common::get_transport_icons();
							break;
						case 'regions':
							$template = 'form';
							$get_alias = true;
							$item = $reference_model->get_region_by_id( $_POST['item_id'] );
							break;
						case 'states':
							$template = 'form';
							$get_alias = false;
							$item = $reference_model->get_state_by_id( $_POST['item_id'] );
							break;
						case 'drive':
							$template = 'form';
							$get_alias = false;
							$item = $reference_model->get_drive_by_id( $_POST['item_id'] );
							break;
						case 'colors':
							$template = 'form';
							$get_alias = true;
							$item = $reference_model->get_color_by_id( $_POST['item_id'] );
							$item['alias'] = ltrim( $item['alias'], '#' );
							break;
						default:
							throw new Exception( 'Error!' );
							break;
					}
					$view = new AT_View();
					$message = $view->use_layout('content')->add_block( 'content', 'admin/reference/' . $template, array(
						'tab' => $_POST['tab'],
						'item' => $item,
						'get_alias' => $get_alias
						) )->render()->display( TRUE );
					break;
				case 'save_item':
					switch ( $_POST['tab'] ) {
						case 'manufacturers':
						case 'transport_types':
						case 'currencies':
						case 'models':
							$data = array(
								'name' => $_POST['name'],
								'alias' => strtolower( $_POST['alias'] )
							);
							$item = $reference_model->update_reference( '_' . $_POST['tab'] . '_table', $_POST['item_id'], $data );
							break;
						case 'states':
							$data = array(
								'name' => $_POST['name'],
								'alias' => strtolower( $_POST['alias'] )
							);
							$item = $reference_model->update_reference( '_' . $_POST['tab'] . '_table', $_POST['item_id'], $data );
							break;
						case 'colors':
							$data = array(
								'name' => $_POST['name'],
								'alias' => '#' . ltrim( strtolower( $_POST['alias'] ), '#' )
							);
							$item = $reference_model->update_reference( '_' . $_POST['tab'] . '_table', $_POST['item_id'], $data );
							break;
						case 'body_types':
						case 'doors':
						case 'equipments':
						case 'fuels':
						case 'technical_conditions':
						case 'transmissions':
						case 'regions':
						case 'drive':
							$data = array(
								'name' => $_POST['name']
							);
							$item = $reference_model->update_reference( '_' . $_POST['tab'] . '_table', $_POST['item_id'], $data );
							break;
						default:
							throw new Exception( 'Error!' );
							break;
					}
					$message = __( 'Item has been saved.', AT_ADMIN_TEXTDOMAIN );
					break;
				case 'save_sort':
					if (!isset( $_POST['item'] ) && $_POST['tab'] != 'equipments') {
						throw new Exception( 'Error!' );
					}
					switch ( $_POST['tab'] ) {
						case 'body_types':
							$data = $reference_model->get_body_types();
							break;
						case 'currencies':
							$data = $reference_model->get_currencies();
							break;
						// case 'equipments':
						// 	$items = array();
						// 	foreach ($_POST as $key => $value) {
						// 		if ( strpos( $key, 'item_' ) == 0 ) {
						// 			$str = substr( $key, 5, strlen( $key ) );
						// 			if ( is_array( $value ) ) {
						// 				foreach ($value as $k => $val) {
						// 					$items[] = 	$str . '_' . $val;
						// 				}
						// 			} else {
						// 				$items[] = 	$str . '_' . $value;
						// 			}
						// 		}
						// 	}
						// 	$data = $reference_model->get_equipments();
						// 	break;
						case 'fuels':
							$data = $reference_model->get_fuels();
							break;
						case 'technical_conditions':
							$data = $reference_model->get_technical_conditions();
							break;
						case 'transmissions':
							$data = $reference_model->get_transmissions();
							break;
						case 'transport_types':
							$data = $reference_model->get_transport_types();
							break;
						case 'regions':
							$data = $reference_model->get_regions();
							break;
						case 'drive':
							$data = $reference_model->get_drive();
							break;
						case 'colors':
							$data = $reference_model->get_colors();
							break;
						default:
							throw new Exception( 'Error!' );
							break;
					}
					if ( count( $data ) == count( $_POST['item'] ) ) {
						foreach ($_POST['item'] as $key => $sort_id) {
							$reference_model->update_reference( '_' . $_POST['tab'] . '_table', $sort_id, array( 'sort' => $key ) );
						}
					}
					$message = __( 'Sort has been saved.', AT_ADMIN_TEXTDOMAIN );
					break;
				case 'get_add_form':
					$item = array();
					switch ( $_POST['tab'] ) {
						case 'models':
							$template = 'form';
							$get_alias = true;
							$item['manufacturer_id'] = $_POST['item_id'];
							break;
						case 'manufacturers':
						case 'equipments':
						case 'colors':
							$template = 'form';
							$get_alias = true;
							break;
						case 'transport_types':
							$template = 'form';
							$get_alias = true;
							$item['icons'] = AT_Common::get_transport_icons();
							break;
						case 'states':
							$template = 'form';
							$get_alias = false;
							$item['region_id'] = $_POST['item_id'];
							break;
						case 'body_types':
						case 'currencies':
						case 'doors':
						case 'fuels':
						case 'technical_conditions':
						case 'transmissions':
						case 'regions':
						case 'drive':
							$template = 'form';
							$get_alias = false;
							break;
						default:
							throw new Exception( 'Error!' );
							break;
					}

					$view = new AT_View();
					$message = $view->use_layout('content')->add_block( 'content', 'admin/reference/' . $template, array(
						'tab' => $_POST['tab'],
						'get_alias' => $get_alias,
						'item' => $item
						) )->render()->display( TRUE );
					break;
				case 'add_item':
					switch ( $_POST['tab'] ) {
						case 'manufacturers':
						case 'transport_types':
						// case 'models':
							$data = array(
								'name' => $_POST['name'],
								'alias' => strtolower( $_POST['alias'] )
							);
							$res = $reference_model->insert_reference( '_' . $_POST['tab'] . '_table', $data, true );
							break;
						case 'models':
							$data = array(
								'name' => $_POST['name'],
								'manufacturer_id' => $_POST['manufacturer_id'],
								'alias' => strtolower( $_POST['alias'] )
							);
							$res = $reference_model->insert_reference( '_' . $_POST['tab'] . '_table', $data, true );
							break;
						case 'equipments':
							$data = array(
								'name' => $_POST['name'],
								'alias' => '_' . ltrim( $_POST['alias'], '_' )
							);
							$res = $reference_model->insert_reference( '_' . $_POST['tab'] . '_table', $data, true );
							break;
						case 'colors':
							$data = array(
								'name' => $_POST['name'],
								'alias' => '#' . ltrim( $_POST['alias'], '#' )
							);
							$res = $reference_model->insert_reference( '_' . $_POST['tab'] . '_table', $data, true );
							break;
						case 'states':
							$data = array(
								'name' => $_POST['name'],
								'region_id' => $_POST['region_id'],
							);
							$res = $reference_model->insert_reference( '_' . $_POST['tab'] . '_table', $data, false );
							break;
						case 'body_types':
						case 'currencies':
						case 'doors':
						case 'fuels':
						case 'technical_conditions':
						case 'transmissions':
						case 'regions':
						case 'drive':
							$data = array(
								'name' => $_POST['name']
							);
							$res = $reference_model->insert_reference( '_' . $_POST['tab'] . '_table', $data );
							break;
						default:
							throw new Exception( 'Error!' );
							break;
					}
					if ( !$res ) throw new Exception( 'Error! This alias exist!' );
					$message = __( 'Item was added.', AT_ADMIN_TEXTDOMAIN );
					break;
					break;
				default:
					throw new Exception( 'Error!' );
					break;
			}
            $response = array( 'status' => 'OK', 'message' => $message );

		} catch(Exception $e) {
        	$response =  array( 'status' => 'ERROR',  'message' => $e->getMessage() );
    	}
	 	$this->view->add_json($response)->display();
		exit;
	}

	private function _get_references(){
		$response = array();
		$reference_model = $this->load->model('reference_model');
		switch ( $_GET['get_references'] ) {
			case 'models':
				$manufacturer_id = !empty($_GET['manufacturer_id']) ? (int)$_GET['manufacturer_id'] : '0';
				$response = $reference_model->get_models_by_manufacturer_id($manufacturer_id);
				break;	
			case 'states':
				$region_id = !empty($_GET['region_id']) ? (int)$_GET['region_id'] : '0';
				$response = $reference_model->get_states_by_region_id($region_id);
				break;	
		}
		$this->view->add_json($response)->display();
		exit;	
	}
}