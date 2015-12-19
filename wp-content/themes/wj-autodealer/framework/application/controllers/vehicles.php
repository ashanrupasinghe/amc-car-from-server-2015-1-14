<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Vehicles extends AT_Controller{

	private $_publish_limit = 0;

	public function __construct() {
		parent::__construct();
		if ( !AT_Common::is_user_logged() && $this->uri->segments( 1 ) != 'upload' ) {
			AT_Common::redirect('/');
		}

		$user_model = $this->load->model( 'user_model' );
		$user_info = $user_model->get_user_by_id( AT_Common::get_logged_user_id() );
		if( $user_info['is_dealer'] ) {
			$this->_publish_limit = $this->core->get_option( 'car_limit_publish_dealer', 50 );
		} else {
			$this->_publish_limit = $this->core->get_option( 'car_limit_publish', 10 );
		}
	}

	public function index( $page = 1 ){

		if( $this->uri->is_ajax_request() && !empty( $_POST ) ) {
			try {
				if ( !AT_Common::get_logged_user_id() ) {
					throw new Exception( 'You dont\'t autorized!' );
				}
				if(empty($_POST['action']) || empty($_POST['car_id']) || !is_numeric($_POST['car_id']) ) 
					throw new Exception( __( 'Error!', AT_TEXTDOMAIN ) );
				switch ( $_POST['action'] ) {
					case 'car_archive':
						$post = array(
						 	'ID' => 	$_POST['car_id'],
							'post_status'	=> 'archive',
						);
						wp_update_post($post);
						$message = __( 'Car was archived!', AT_TEXTDOMAIN );
						break;
					case 'car_republish':
						if( $this->_get_limit_publish() ) {
							$post_date = current_time( 'mysql');
							$post_date_gmt = current_time( 'mysql', true );
							$post = array(
							 	'ID' => 	$_POST['car_id'],
								'post_date'	=> $post_date,
								'post_date_gmt'	=> $post_date_gmt
							);
							wp_update_post($post);
							$message = date( 'F d, Y', strtotime( $post_date ) );
						} else {
							throw new Exception( __( 'Publish limit!', AT_TEXTDOMAIN ) );
						}
						break;
					case 'car_add_best_offer':
						update_post_meta( $_POST['car_id'], '_best_offer', true );
						$message =  __( 'Remove best offer', AT_TEXTDOMAIN );
						break;
					case 'car_remove_best_offer':
						update_post_meta( $_POST['car_id'], '_best_offer', false );
						$message =  __( 'Add best offer', AT_TEXTDOMAIN );
						break;
					case 'promote_top':
						//update_post_meta( $_POST['car_id'], '_best_offer', true );
						AT_Session::get_instance()->set_userdata('paidEntityID',$_POST['car_id']);
						AT_Session::get_instance()->set_userdata('paidEntity','promote_top');
						$redirect_url = AT_Common::site_url('payments');
						$message =  __( 'Promote to top', AT_TEXTDOMAIN );
						break;
					case 'promote_featured':
						//update_post_meta( $_POST['car_id'], '_best_offer', true );
						AT_Session::get_instance()->set_userdata('paidEntityID',$_POST['car_id']);
						AT_Session::get_instance()->set_userdata('paidEntity','promote_featured');
						$message =  __( 'Promote to featured', AT_TEXTDOMAIN );
						$redirect_url = AT_Common::site_url('payments');
						break;
					default:
						throw new Exception( __( 'Error!', AT_TEXTDOMAIN ) );
						break;
				}

                $response = array( 'status' => 'OK', 'message' => $message );

                if ( isset( $redirect_url ) && !empty( $redirect_url ) ) {
                	$response['redirect'] = $redirect_url;
                }

			} catch(Exception $e) {
            	$response =  array( 'status' => 'ERROR',  'message' => $e->getMessage());
        	}

			$this->view->add_json($response)->display();
			exit;
		}

		$car_model = $this->load->model( 'car_model' );
		$user_model = $this->load->model( 'user_model' );
		$paginator = $this->load->library('paginator');
		$count_cars = $car_model->get_cars_count_by_user_id( AT_Common::get_logged_user_id(), 'publish' );
		if( $page < 1 ) $page = 1;
		$paginator = $paginator->get(4, $count_cars, 10, 1, 2, 'profile/vehicles/index/' . $page . '/', 'profile/vehicles/' );

		$this->view->use_layout('profile'); 
		$this->view->add_block( 'content', 'vehicles/list', array( 
			'cars' => $car_model->get_cars_by_user_id( AT_Common::get_logged_user_id(), $paginator['offset'], $paginator['per_page'], 'publish' ), 
			'count_cars' => $count_cars,
			'car_status' => 'publish',
			'paid' => array(
				'featured' => $this->core->get_option( 'merchant_module_featured', false ),
				'top' => $this->core->get_option( 'merchant_module_promote', false ),
			),

			'user_info' => $user_model->get_user_by_id( AT_Common::get_logged_user_id() ),
			'publish_limit' => $this->_publish_limit
			) )
			->add_block( 'content/pagination', 'general/pagination', $paginator );

		$this->breadcrumbs->add_item( __( 'Account', AT_TEXTDOMAIN ), 'profile/' );
		$this->breadcrumbs->add_item( __( 'My cars', AT_TEXTDOMAIN ), 'profile/vehicles/' );

		$menu_model = $this->load->model('menu_model');
		$this->view->add_block( 'left_side', 'general/navigation', $menu_model->get_menu('main', 'vehicles') );
	}

	public function archive( $page = 1 ){

		if( $this->uri->is_ajax_request() && !empty( $_POST ) ) {
			try {
				if ( !AT_Common::get_logged_user_id() ) {
					throw new Exception( 'You dont\'t autorized!' );
				}
				if(empty($_POST['action']) || empty($_POST['car_id']) || !is_numeric($_POST['car_id']) ) 
					throw new Exception( __( 'Error!', AT_TEXTDOMAIN ) );
				switch ( $_POST['action'] ) {
					case 'car_publish':
						if( $this->_get_limit_publish() ) {
							$post_date = current_time( 'mysql');
							$post_date_gmt = current_time( 'mysql', true );
							$post = array(
							 	'ID' => 	$_POST['car_id'],
								'post_date'	=> $post_date,
								'post_date_gmt'	=> $post_date_gmt,
								'post_status'	=> 'publish'
							);
							wp_update_post($post);
							$message = __( 'Car was published!', AT_TEXTDOMAIN );	
						} else {
							throw new Exception( __( 'Publish limit!', AT_TEXTDOMAIN ) );
						}
						break;
					default:
						throw new Exception( __( 'Error!', AT_TEXTDOMAIN ) );
						break;
				}

                $response = array( 'status' => 'OK', 'message' => $message );

			} catch(Exception $e) {
            	$response =  array( 'status' => 'ERROR',  'message' => $e->getMessage());
        	}

			$this->view->add_json($response)->display();
			exit;
		}

		$car_model = $this->load->model( 'car_model' );
		$paginator = $this->load->library('paginator');
		$count_cars = $car_model->get_cars_count_by_user_id( AT_Common::get_logged_user_id(), 'archive' );
		if( $page < 1 ) $page = 1;
		$paginator = $paginator->get(4, $count_cars, 10, 1, 2, 'profile/vehicles/archive/' . $page . '/', 'profile/vehicles/' );

		$this->view->use_layout('profile'); 
		$this->view->add_block( 'content', 'vehicles/list', array( 
			'cars' => $car_model->get_cars_by_user_id( AT_Common::get_logged_user_id(), $paginator['offset'], $paginator['per_page'], 'archive' ), 
			'count_cars' => $count_cars,
			'car_status' => 'archive'
			) )
			->add_block( 'content/pagination', 'general/pagination', $paginator );

		$this->breadcrumbs->add_item( __( 'Account', AT_TEXTDOMAIN ), 'profile/' );
		$this->breadcrumbs->add_item( __( 'My cars archived', AT_TEXTDOMAIN ), 'profile/vehicles/' );

		$menu_model = $this->load->model('menu_model');
		$this->view->add_block( 'left_side', 'general/navigation', $menu_model->get_menu('main', 'vehicles_archive') );
	}

	public function add( ){
		wp_enqueue_script( 'jquery-ui-core' );
  		wp_enqueue_script( 'jquery-ui-sortable' );

		if( $this->uri->is_ajax_request() && !empty( $_POST ) ) {
			try {
				// Dinamic Validator
				// if ( empty( $_POST['post_title'] ) || empty( $_POST['post_content'] ) ) {
				// 	throw new Exception( 'Enter Title && Content!' );
				// }
				if ( !AT_Common::get_logged_user_id() ) {
					throw new Exception( 'You dont\'t autorized!' );
				}
				$car_model = $this->load->model( 'car_model' );
				$reference_model = $this->load->model('reference_model');

				$manufacturer = $reference_model->get_manufacturer_by_id( $_POST['_manufacturer_id'] );
				$model = $reference_model->get_model_by_id( $_POST['_model_id'] );
				$version = isset($_POST['_version']) ? $_POST['_version'] : '';
				$_POST['post_title'] = trim( $manufacturer['name'] . ' ' . $model['name'] . ' ' . $version );
				
				//throw new Exception( $_POST['post_title'] );

				if( isset( $_POST['_price'] ) ) {
					$_POST['_price'] = str_replace(array(',', ' '), array('', ''), strip_tags($_POST['_price']) );
				}
				
				if( $this->_get_limit_publish() ) {
					$_POST['post_status'] = 'publish';
				} else {
					$_POST['post_status'] = 'archive';
				}

				$car_id = $car_model->add_car_info( array_merge( $_POST, array( '_owner_id' => AT_Common::get_logged_user_id() ) ) );

				if (isset($_POST['photos'])){
                	$photo_model = $this->load->model('photo_model');
                	$sort = 0;
                	foreach ($_POST['photos'] as $key => $photo) {
                		$sort ++;
                		$key = explode('/', $key);
                		if ( count( $key ) > 1 ) continue;
                		if( ( $photo_id = $photo_model->resize_uploaded_image( AT_DIR_THEME . '/uploads/' . $key[0], $car_id, 'car', $photo_model->car_sizes )) && $photo ) {
            				$photo_model->set_photo_main_by_id( $car_id, 'car', $photo_id  );
            				$photo_model->set_photo_sort( $key, $sort );
            			} else {
		        			$photo_model->set_photo_sort( $key, $sort );
		        		}

                	}
                }
                
                if( $this->_get_limit_publish() ) {
                	$redirect_url = AT_Common::site_url('profile/vehicles');
				} else {
					$redirect_url = AT_Common::site_url('profile/vehicles/archive');
				}
                $response = array( 'status' => 'OK', 'redirect_url' => $redirect_url );

			} catch(Exception $e) {
            	$response =  array( 'status' => 'ERROR',  'message' => $e->getMessage());
        	}

			$this->view->add_json($response)->display();
			exit;
		}

		$this->view->use_layout('header_content_footer');
		if (AT_Common::is_user_logged()) {
			$this->breadcrumbs->add_item( __('My Vehicles', AT_TEXTDOMAIN), 'profile/vehicles/' );
		}
		$this->breadcrumbs->add_item( __('Add Car', AT_TEXTDOMAIN), '' );

		$reference_model = $this->load->model('reference_model');
		$user_model = $this->load->model('user_model');
		$owner_info = $user_model->get_user_by_id( AT_Common::get_logged_user_id() );

		$this->view->add_block( 'content', 'vehicles/add', array( 
			'manufacturers' => $reference_model->get_manufacturers(),
			'equipments' =>  $reference_model->get_equipments(),
			'body_types' =>  $reference_model->get_body_types(),
			'fuels' =>  $reference_model->get_fuels(),
			'transmissions' =>  $reference_model->get_transmissions(),
			'doors' =>  $reference_model->get_doors(),
			'technical_conditions' =>  $reference_model->get_technical_conditions(),
			'currencies' =>  $reference_model->get_currencies(),
			'transport_types' =>  $reference_model->get_transport_types(),
			'regions' =>  $reference_model->get_regions(),
			'drive' =>  $reference_model->get_drive(),
			'colors' =>  $reference_model->get_colors(),
			'affiliates' => $owner_info['is_dealer'] ? $user_model->get_dealer_affiliates( AT_Common::get_logged_user_id() ) : array(),
			'owner_info' => $owner_info
		));

		$menu_model = $this->load->model('menu_model');
		$this->view->add_block( 'right_side', 'general/navigation', $menu_model->get_menu('main', 'vehicles') );
	}

	public function edit( $car_id = '' ){
		wp_enqueue_script( 'jquery-ui-core' );
  		wp_enqueue_script( 'jquery-ui-sortable' );

		$car_id = (int)$car_id;
		$car_model = $this->load->model( 'car_model' );
		if( !$car_id || ( $car_info = $car_model->get_car_info( $car_id ) ) === false || $car_info['options']['_owner_id'] != AT_Common::get_logged_user_id() ){
			AT_Core::show_404();
		}

		if( $this->uri->is_ajax_request() && !empty( $_POST ) ) {
			try {
				// if ( empty( $_POST['post_title'] ) || empty( $_POST['post_content'] ) ) {
				// 	throw new Exception( 'Enter Title && Content!' );
				// }

				if( isset( $_POST['_price'] ) ) {
					$_POST['_price'] = str_replace(array(',', ' '), array('', ''), $_POST['_price'] );
				}

				$reference_model = $this->load->model('reference_model');

				$manufacturer = $reference_model->get_manufacturer_by_id( $_POST['_manufacturer_id'] );
				$model = $reference_model->get_model_by_id( $_POST['_model_id'] );
				$version = isset($_POST['_version']) ? $_POST['_version'] : '';
				
				$region = $reference_model->get_manufacturer_by_id( $_POST['_region_id'] );
				$state = $reference_model->get_model_by_id( $_POST['_state_id'] );

				$_POST['post_title'] = trim( $manufacturer['name'] . ' ' . $model['name'] . ' ' . $version );
				$car_model->update_car_info( $car_id, $_POST );
				
				$photo_model = $this->load->model('photo_model');
				$photos = $photo_model->get_photos_by_post( $car_id, 'car' );
				$main_photo = $photo_model->get_photo_by_post( $car_id, 'car', 1 );
                if (isset($_POST['photos'])){
                	foreach ($photos as $key => $value) {
                		if( !isset( $_POST['photos'][$value['id']] ) ) {
                			$photo_model->del_photo_by_id( $value['id'] );
                		} else if( $_POST['photos'][$value['id']] && ( $value['id'] != $main_photo['id'] ) ) {
                			$photo_model->set_photo_main_by_id( $car_id, 'car', $value['id'] );
                		}
                	}
                	$sort = 0;
                	foreach ($_POST['photos'] as $key => $photo) {
                		$sort ++;
                		if( !is_numeric( $key ) ) {
                			$key = explode('/', $key);
                			if ( count( $key ) > 1 ) continue;
                			if( ( $photo_id = $photo_model->resize_uploaded_image( AT_DIR_THEME . '/uploads/' . $key[0], $car_id, 'car', $photo_model->car_sizes )) && $photo ) {
                				$photo_model->set_photo_main_by_id( $car_id, 'car', $photo_id  );
                				$photo_model->set_photo_sort( $key, $sort );
                			}
                		} else {
		        			$photo_model->set_photo_sort( $key, $sort );
		        		}
                	}
                } else if( count( $photos ) > 0 ) {
                	$photo_model->del_photos_by_post( $car_id, 'car' );
                }

                $response = array( 'status' => 'OK', 'redirect_url' =>  AT_Common::site_url('profile/vehicles/'));

			} catch(Exception $e) {
            	$response =  array( 'status' => 'ERROR',  'message' => $e->getMessage());
        	}
			$this->view->add_json($response)->display();
		}

		$this->breadcrumbs->add_item( 'My vehicles', 'profile/vehicles/' );
		$this->breadcrumbs->add_item( 'Edit ' . $car_info['post_title'], '' );

		$reference_model = $this->load->model('reference_model');
		$photo_model = $this->load->model('photo_model');
		$user_model = $this->load->model('user_model');
		$owner_info = $user_model->get_user_by_id( $car_info['options']['_owner_id'] );


		$block_params = array( 
			'car_info' => $car_info,
			'manufacturers' => $reference_model->get_manufacturers(),
			'equipments' =>  $reference_model->get_equipments(),
			'body_types' =>  $reference_model->get_body_types(),
			'fuels' =>  $reference_model->get_fuels(),
			'transmissions' =>  $reference_model->get_transmissions(),
			'doors' =>  $reference_model->get_doors(),
			'technical_conditions' =>  $reference_model->get_technical_conditions(),
			'currencies' =>  $reference_model->get_currencies(),
			'transport_types' =>  $reference_model->get_transport_types(),
			'regions' =>  $reference_model->get_regions(),
			'states' => array(),
			'drive' =>  $reference_model->get_drive(),
			'colors' =>  $reference_model->get_colors(),
			'photos' =>  $photo_model->get_photos_by_post( $car_id, 'car' ),
			'affiliates' => $owner_info['is_dealer'] ? $user_model->get_dealer_affiliates( $car_info['options']['_owner_id'] ) : array(),
			'owner_info' => $owner_info
		);
		if ( isset($car_info['options']) && isset($car_info['options']['_region_id']) && !empty($car_info['options']['_region_id']) ) {
			$block_params['states'] = $reference_model->get_states_by_region_id($car_info['options']['_region_id']);
		}

		$this->view->use_layout('header_content_footer');
		$this->view->add_block( 'content', 'vehicles/add', $block_params);

		$menu_model = $this->load->model('menu_model');
		$this->view->add_block( 'right_side', 'general/navigation', $menu_model->get_menu('main', 'vehicles') );
	}

	public function upload() {
		if( empty( $_POST ) || empty($_FILES) || !isset($_FILES["file"]) ) {
			AT_Core::show_404();
		}
		$_file_a = explode( '.', $_FILES["file"]["name"] );
		if (count($_file_a) <= 1) {;
			//AT_Core::show_404();
			$file_name = uniqid("car_") . '.jpg';
		} else {
			$file_name = uniqid("car_") . '.' . $_file_a[count($_file_a)-1];
		}

		//$file_name = uniqid("car_") . '.' . $_file_a[count($_file_a)-1];

		$targetDir = AT_DIR_THEME . '/uploads';
		$cleanupTargetDir = false; 	// Remove old files
		$maxFileAge = 5 * 3600; 	// Temp file age in seconds

		$filePath = $targetDir . DIRECTORY_SEPARATOR . $file_name;

		// Chunking might be enabled
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

		try {
			// Open temp file
			if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
				throw new Exception('{"status" : "ERROR", "code": 102, "message": "Failed to open output stream."}');
			}

			if (!empty($_FILES)) {
				if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
					throw new Exception('{"status" : "ERROR", "code": 103, "message": "Failed to move uploaded file."}');
				}
				// Read binary input stream and append it to temp file
				if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
					throw new Exception('{"status" : "ERROR", "code": 101, "message": "Failed to open input stream."}');
				}
			} else {	
				if (!$in = @fopen("php://input", "rb")) {
					throw new Exception('{"status" : "ERROR", "code": 101, "message": "Failed to open input stream."}');
				}
			}

			while ($buff = fread($in, 4096)) {
				fwrite($out, $buff);
			}

			@fclose($out);
			@fclose($in);

			// Check if file has been uploaded
			if (!$chunks || $chunk == $chunks - 1) {
				// Strip the temp .part suffix off 
				rename("{$filePath}.part", $filePath);
			}

			// Return Success JSON-RPC response
			$response = '{"status" : "OK", "file_name" : "' . $file_name . '", "file_name_url" : "' . AT_Common::static_url('uploads/' . $file_name) . '"}';
			throw new Exception($response);
		} catch(Exception $e) {
        	$this->view->add_json(json_decode($e->getMessage()))->display();
    	}
	}

	private function _get_limit_publish( ){
		if ( $this->_publish_limit > 0 ) {
			$car_model = $this->load->model( 'car_model' );
			$count_cars = $car_model->get_cars_count_by_user_id( AT_Common::get_logged_user_id(), 'publish' );
			$limit = $this->_publish_limit - $count_cars;
			if ( $limit < 0 ) $limit = 0;
		} else {
			$limit = 999999;
		}
		return $limit;
	}
}