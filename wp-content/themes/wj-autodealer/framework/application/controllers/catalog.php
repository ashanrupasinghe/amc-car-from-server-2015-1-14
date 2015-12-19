<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Catalog extends AT_Controller{

	private $_layout_items = array(
		'left_content' => array( 'sidebar', 'content' ),
		'content_right' => array( 'content', 'sidebar' ),
	);

	public function __construct() {
		parent::__construct();
	}

	public function index( $manufacturer = '', $model = '' ){
		$this->breadcrumbs->add_item( 'Catalog', 'catalog/' );

		$car_model = $this->load->model('car_model');
		$reference_model = $this->load->model('reference_model');

		if( !empty( $_GET['view'] ) ) {
			switch ($_GET['view']) {
				case 'list':
					$view = 'list';
					break;
				case 'grid':
					$view = 'grid';
					break;
				default:
					$view = $this->core->get_option( 'catalog_car_type_view_default', 'list' );
					break;
			}
		} else {
			$view = $this->core->get_option( 'catalog_car_type_view_default', 'list' );
		}

		$params = array();

		if ( !empty($manufacturer) && !is_numeric($manufacturer) ) {
			if( $manufacturer_data =  $reference_model->get_manufacturer_by_alias( $manufacturer ) ) {
				$this->breadcrumbs->add_item( $manufacturer_data['name'], 'catalog/' . $manufacturer_data['alias'] );
				$_GET['manufacturer_id'] = $manufacturer_data['id'];
				if ( !empty($model) && !is_numeric($model) && ( $model_data =  $reference_model->get_model_by_alias( $model ) ) ) {
					$this->breadcrumbs->add_item( $model_data['name'], 'catalog/' . $manufacturer_data['alias'] . '/' . $model_data['alias'] );
					$_GET['model_id'] = $model_data['id'];
				}
			}
		}

		$params['cars_categories'] = isset( $_GET['cars_categories'] ) ? $_GET['cars_categories'] : 'all';
		$params['manufacturer_id'] = isset( $_GET['manufacturer_id'] ) ? (int)$_GET['manufacturer_id'] : 0;
		$params['model_id'] = isset( $_GET['model_id'] ) ? (int)$_GET['model_id'] : 0;
		$params['price_from'] = isset( $_GET['price_from'] ) && $_GET['price_from']!='' ? (int)$_GET['price_from'] : '';
		$params['price_to'] = isset( $_GET['price_to'] ) && $_GET['price_to']!='' ? (int)$_GET['price_to'] : '';
		$params['mileage_from'] = isset( $_GET['mileage_from'] ) && $_GET['mileage_from']!='' ? (int)$_GET['mileage_from'] : '';
		$params['mileage_to'] = isset( $_GET['mileage_to'] ) && $_GET['mileage_to']!='' ? (int)$_GET['mileage_to'] : '';
		$params['fabrication_from'] = isset( $_GET['fabrication_from'] ) ? (int)$_GET['fabrication_from'] : '';
		$params['fabrication_to'] = isset( $_GET['fabrication_to'] ) ? (int)$_GET['fabrication_to'] : '';
		

		$transport_types = $reference_model->get_transport_types();
		$car_transport_types = $this->core->get_option( 'car_transport_types', array( 'default' => 0, 'is_view_all' => true ) );
        if ( !$car_transport_types['is_view_all'] && ($car_transport_types['default'] == 0) && count($transport_types) > 0) {
            $car_transport_types['default'] = $transport_types[0]['id'];
        }

		$params['transport_type_id'] = isset( $_GET['transport_type_id'] ) ? (int)$_GET['transport_type_id'] : $car_transport_types['default'];

		$params['body_type_id'] = isset( $_GET['body_type_id'] ) ? (int)$_GET['body_type_id'] : 0;
		$params['fuel_id'] = isset( $_GET['fuel_id'] ) ? (int)$_GET['fuel_id'] : 0;
		$params['engine_from'] = isset( $_GET['engine_from'] ) ? (int)$_GET['engine_from'] : 0;
		$params['engine_to'] = isset( $_GET['engine_to'] ) ? (int)$_GET['engine_to'] : 0;
		$params['transmission_id'] = isset( $_GET['transmission_id'] ) ? (int)$_GET['transmission_id'] : 0;
		$params['door_id'] = isset( $_GET['door_id'] ) ? (int)$_GET['door_id'] : 0;
		$params['region_id'] = isset( $_GET['region_id'] ) ? (int)$_GET['region_id'] : 0;
		$params['state_id'] = isset( $_GET['state_id'] ) ? (int)$_GET['state_id'] : 0;
		$params['drive_id'] = isset( $_GET['drive_id'] ) ? (int)$_GET['drive_id'] : 0;
		$params['color_id'] = isset( $_GET['color_id'] ) ? (int)$_GET['color_id'] : 0;

		$sorted_fields = array(
			'date' => 'post_date',
			'price' => '_price',
			'name' => 'post_title',
			'manufacturer' => 'manufacturer',
		);

		$catalog_sorted_fields =  $this->core->get_option( 'catalog_sorted_fields', array( array( 'name' => __( 'Publish date', AT_TEXTDOMAIN ), 'field' => 'date', 'direction' => 'desc' ) ) );

		if ( count( $catalog_sorted_fields ) > 0 ) {
			$sorted_field = (isset( $_GET['sorted_field'] ) && array_key_exists( $_GET['sorted_field'], $sorted_fields )) ? $_GET['sorted_field'] : $catalog_sorted_fields[0]['field'];
			$sorted_direction = (isset( $_GET['sorted_direction'] ) && in_array( $_GET['sorted_direction'], array( 'asc', 'desc' ) )) ? $_GET['sorted_direction'] : $catalog_sorted_fields[0]['direction'];
		} else {
			$sorted_field = 'date';
			$sorted_direction = 'desc';
		}

		// pagination 
		$catalog_per_pages = $this->core->get_option( 'catalog_per_pages', array( array( 'pages' => 12 ) ));
		$min = $catalog_per_pages[0]['pages'];
		$max = $catalog_per_pages[0]['pages'];
		foreach ($catalog_per_pages as $key => $value) {
			if ( $value['pages'] > $max ) $max = $value['pages'];
			if ( $value['pages'] < $min ) $min = $value['pages'];
		}
		$view_on_page = isset( $_GET['view_on_page'] ) ? (int)$_GET['view_on_page'] : $min;
		$view_on_page = ($view_on_page < $min) ? $min : (($view_on_page > $max) ? $max : $view_on_page);
		$paginator = $this->load->library('paginator');
		$count_cars = $car_model->get_cars_count( $params );
		$paginator = $paginator->get(2, $count_cars, $view_on_page);

		$layout = $this->core->get_option( 'catalog_layout', 'left_content' );

		$this->view->use_layout('header_content_footer')
			->add_block('content', 'catalog/filters', array(
				//'manufacturers' => $reference_model->get_manufacturers(),
				//'models' => $reference_model->get_models_by_manufacturer_id($params['manufacturer_id']),
				'view' => $view,
				'count_cars' => $count_cars,
				'view_on_page' => $view_on_page,
				'sorted_field' => $sorted_field,
				'sorted_direction' => $sorted_direction,
				'catalog_sorted_fields' => $catalog_sorted_fields,
				'params' => $params,
				'layout' => $layout,
				'layout_items' => $this->_layout_items[$layout]
			))
			->add_block('content/loan_calculator', 'additions/loan_calculator')
			->add_block('content/pagination', 'general/pagination', $paginator)
			->add_block('content/cars', 'catalog/' . $view, array(
				'cars' => $car_model->get_cars( $params, $paginator['offset'], $paginator['per_page'], 'publish', $sorted_fields[$sorted_field], $sorted_direction ),
			));
	}


	public function ajax_get_models( $manufacturer = '' ){
		//$manufacturer_id = (int)$manufacturer_id;
		if( !$this->uri->is_ajax_request() /*|| $manufacturer_id == 0*/ ) {
			AT_Core::show_404();
		}
		$reference_model = $this->load->model( 'reference_model' );
		if ( is_numeric($manufacturer) ) {
			if($response = $reference_model->get_models_by_manufacturer_id( $manufacturer )){
				$this->view->add_json($response)->display();
			}
		}
		if( $manufacturer_data = $reference_model->get_manufacturer_by_alias( $manufacturer ) ){
			$response = $reference_model->get_models_by_manufacturer_id( $manufacturer_data['id'] );
			$this->view->add_json($response)->display();
		}
		// AT_Core::show_404();
	}

	public function ajax_get_region( $region = 0 ){
		if( !$this->uri->is_ajax_request() ) {
			AT_Core::show_404();
		}
		$reference_model = $this->load->model( 'reference_model' );
		if ( is_numeric($region) ) {
			if($response = $reference_model->get_region_by_id( $region )){
				$this->view->add_json($response)->display();
			}
		}
		// AT_Core::show_404();
	}

	public function ajax_get_state( $state = 0 ){
		if( !$this->uri->is_ajax_request() ) {
			AT_Core::show_404();
		}
		$reference_model = $this->load->model( 'reference_model' );
		if ( is_numeric($state) ) {
			if($response = $reference_model->get_state_by_id( $state )){
				$this->view->add_json($response)->display();
			}
		}
		AT_Core::show_404();
	}

	public function ajax_get_states( $region = 0 ){
		if( !$this->uri->is_ajax_request() ) {
			AT_Core::show_404();
		}
		$reference_model = $this->load->model( 'reference_model' );
		if ( is_numeric($region) ) {
			if($response = $reference_model->get_states_by_region_id( $region )){
				$this->view->add_json($response)->display();
			}
		}
		// AT_Core::show_404();
	}

	public function ajax_get_manufacturer_by_type( $type = '' ){
		//$manufacturer_id = (int)$manufacturer_id;
		if( !$this->uri->is_ajax_request() /*|| $manufacturer_id == 0*/ ) {
			AT_Core::show_404();
		}
		$reference_model = $this->load->model( 'reference_model' );
		if ( is_numeric($type) ) {
			if($response = $reference_model->get_manufacturer_by_transport_type_by_id( $type )){
				$this->view->add_json($response)->display();
			}
		}
		if( $manufacturer_data = $reference_model->get_transport_type_by_alias( $type ) ){
			$response = $reference_model->get_manufacturer_by_transport_type_by_id( $manufacturer_data['id'] );
			$this->view->add_json($response)->display();
		}
		//AT_Core::show_404();
	}

	public function ajax_add_offer( $car_id = '' ){
		$car_id = (int)$car_id;
		if( !$this->uri->is_ajax_request() || $car_id == 0 ) {
			AT_Core::show_404();
		}
		$car_model = $this->load->model( 'car_model' );
		if ( !( $car_info = $car_model->get_car_info( $car_id ) ) ) {
			AT_Core::show_404();	
		}
		try {
			if (!$this->validation->run( 'add_offer' )) {
				throw new Exception( serialize($this->validation->get_errors()) );
			}

			$mail_model = $this->load->model( 'mail_model' );
			$user_model = $this->load->model( 'user_model' );


			$owner_info = $user_model->get_user_by_id( $car_info['options']['_owner_id'] );

			$dealer_email = '';
			if ( $owner_info['is_dealer'] ) {
				if( $car_info['options']['_affiliate_id'] > 0 ) {
					$affiliate = $user_model->get_dealer_affiliate_by_id( $car_info['options']['_affiliate_id'] );
				}
				if( empty( $affiliate ) ) {
					$affiliate = $user_model->get_dealer_main_affiliate( $car_info['options']['_owner_id'] );	
				}
				if( $affiliate ) {
					$dealer_email = $affiliate['email'];
				}
			} 
			if ( !$owner_info['is_dealer'] || $dealer_email == '' ) {
				$dealer_email = $owner_info['email'];
			}

			$cost = AT_Common::show_full_price($value = $car_info['options']['_price'], $currency = $car_info['options']['_currency_id']);

			$data = array(
				'dealer_name' => $owner_info['name'],
				'username' => $_POST['fullname'],
				'user_email' => $_POST['email'],
				'car_name' => trim( $car_info['options']['_manufacturer_id']['name'] . ' ' . $car_info['options']['_model_id']['name'] . ' ' .$car_info['options']['_version'] ),
				'cost' => $cost,
				'offer_details' => $_POST['offer_details'],
				'link_car' => get_permalink( $car_id )
			);
			if( !$mail_model->send( 'template_mail_add_offer', $dealer_email, $data, $_POST['email'], $_POST['fullname'] ) ) {
				throw new Exception( serialize( array( 'email' => __( 'Error send email! Try later.', AT_TEXTDOMAIN )) ) );	
			}
			$response = array( 'status' => 'OK', 'message' =>  __('The offer was sent.', AT_TEXTDOMAIN ) );
		} catch(Exception $e) {
			$response =  array( 'status' => 'ERROR',  'message' => unserialize($e->getMessage()) );
		}
		$this->view->add_json($response)->display();
	}
}