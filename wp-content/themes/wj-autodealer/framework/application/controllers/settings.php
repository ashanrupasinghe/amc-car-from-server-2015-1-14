<?php
if (!defined("AT_DIR")) die('!!!');
class AT_Settings extends AT_Controller{
	public function __construct() {
		parent::__construct();
		if ( !AT_Common::is_user_logged()) {
			AT_Common::redirect('/');
		}
	}

	public function index(){
		$user_info = $this->registry->get( 'user_info' );

		if( $this->uri->is_ajax_request() && !empty( $_POST ) ) {
			try {
				if( $user_info['is_dealer'] ) $valid = 'settings_dealer';
				else $valid = 'settings_user';

				if (!$this->validation->run( $valid )) {
					throw new Exception( serialize($this->validation->get_errors()) );
				}
				$user_model = $this->load->model( 'user_model' );

				if( $user_info['is_dealer'] ) {
					$update_data = array(
						'name' => $_POST['name'],
						'layout' => $_POST['layout'],
						// 'map' => $_POST['map'],
						'about' => $_POST['about'],
						'per_page' => $_POST['per_page'],
					);
				} else {
					$update_data = array(
						'name' => $_POST['name'],
						'phone' => $_POST['phone_1'],
						'phone_2' => $_POST['phone_2'],
						'region_id' => $_POST['region_id'],
					);
				}

				// hide_number_ads
				if( !$user_model->update( AT_Common::get_logged_user_id(), $update_data ) ) {
                    //throw new Exception( __( 'Update failed!', AT_TEXTDOMAIN ) );
                }
                $response = array( 'status' => 'OK', 'message' =>  __('The changes was saved.', AT_TEXTDOMAIN ) );
			} catch(Exception $e) {
            	$response =  array( 'status' => 'ERROR',  'message' => unserialize($e->getMessage()) );
        	}

			$this->view->add_json($response)->display();
			exit;
		}
		if( $user_info['is_dealer'] ) $view = 'dealer_profile';
		else $view = 'user_profile';

		$reference_model = $this->load->model( 'reference_model' );
		$this->view->use_layout('profile');
		$this->view->add_block( 'content', 'settings/' . $view, array( 
			'regions' => $reference_model->get_regions()
		) );

		$this->breadcrumbs->add_item( __( 'Account', AT_TEXTDOMAIN ), 'profile/' );
		$this->breadcrumbs->add_item( __( 'Profile', AT_TEXTDOMAIN ), 'profile/settings/' );
		
		$menu_model = $this->load->model('menu_model');
		$this->view->add_block( 'left_side', 'general/navigation', $menu_model->get_menu('main', 'settings') );
	}

	public function upload(){
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

			// save file $file_name
			$photo_model = $this->load->model('photo_model');
    		$photo = explode('/', $file_name);
    		$photo_model->resize_uploaded_image( AT_DIR_THEME . '/uploads/' . $photo[0], AT_Common::get_logged_user_id(), 'user', $photo_model->user_sizes );

 			$user_model = $this->load->model( 'user_model' );
 			$user_info = $user_model->get_user_by_id(AT_Common::get_logged_user_id());
			// Return Success JSON-RPC response
			$response = '{"status" : "OK", "file_name" : "' . $file_name . '", "file_name_url" : "' . AT_Common::static_url( $user_info['photo']['photo_url'] . '138x138/' . $user_info['photo']['photo_name'] ) . '"}';
			throw new Exception($response);
		} catch(Exception $e) {
        	$this->view->add_json(json_decode($e->getMessage()))->display();
    	}
	}

	public function del_photo(){
		if( $this->uri->is_ajax_request() ) {
			try {
				$photo_model = $this->load->model('photo_model');
				$user_model = $this->load->model( 'user_model' );
				$user_info = $user_model->get_user_by_id(AT_Common::get_logged_user_id());
				if (!empty($user_info['photo'])){
					$photo_model->del_photo_by_id( $user_info['photo']['id'] );
				}
				$response = '{"status" : "OK", "file_name_url" : "' . AT_Common::static_url('assets/images/no_photo_profile.png') . '"}';
				throw new Exception($response);
			} catch(Exception $e) {
	        	$this->view->add_json(json_decode($e->getMessage()))->display();
	    	}
	    } else {
    		AT_Core::show_404();
    	}
	}

	public function change_password(){
		if( $this->uri->is_ajax_request() ) {
			try {
				if( empty( $_POST['old_password'] ) || empty( $_POST['new_password'] ) || empty( $_POST['repeat_password'] ) || ( $_POST['new_password'] != $_POST['repeat_password'] ) ){
					throw new Exception( __( 'Values incorrect!', AT_TEXTDOMAIN ) );
				}
				
				$user_model = $this->load->model( 'user_model' );
				$user_info = $user_model->get_user_by_id( AT_Common::get_logged_user_id() );
				if ( $user_model->get_hash( $_POST['old_password'], $user_info['salt'] ) !=  $user_info['password'] ) {
					throw new Exception( __( 'Old password incorrect!', AT_TEXTDOMAIN ) );
				}
				$salt = $user_model->generate_string( 20 );
				$data = array(
					'salt' => $salt,
					'password' => $user_model->get_hash( $_POST['new_password'], $salt )
				);
				$user_model->update( AT_Common::get_logged_user_id(), $data );

				$response = array( 'status' => 'OK', 'message' => __( 'The password was changed.', AT_TEXTDOMAIN ) );
			} catch(Exception $e) {
				$response = array( 'status' => 'ERROR', 'message' => $e->getMessage() );
	    	}
	    	$this->view->add_json($response)->display();
	    }
	}

	public function dealer_affiliates() {

		if( $this->uri->is_ajax_request() && !empty( $_POST ) ) {
			try {
				if (!$this->validation->run('affiliate')) {
					throw new Exception( serialize($this->validation->get_errors()) );
				}
				$user_model = $this->load->model( 'user_model' );
				$reference_model = $this->load->model( 'reference_model' );
				
				$data = array(
					'dealer_id' => AT_Common::get_logged_user_id(),
					'name' => $_POST['name'],
					'email' => $_POST['email'],
					'adress' => isset( $_POST['adress'] ) ? $_POST['adress'] : '',
					'phone' => isset( $_POST['phone_1'] ) ? $_POST['phone_1'] : '',
					'phone_2' => isset( $_POST['phone_2'] ) ? $_POST['phone_2'] : '',
					'region_id' => isset( $_POST['region_id'] ) ? $_POST['region_id'] : '',
					'schedule' => isset( $_POST['schedule'] ) ? serialize( $_POST['schedule'] ) : serialize( array( 'monday' => '', 'tuesday' => '', 'wednesday' => '', 'thursday' => '', 'friday' => '', 'saturday' => '', 'sunday' => '' ) ),
				);

				$_POST['affiliate_id'] = (int)$_POST['affiliate_id'];
				if ( $_POST['affiliate_id'] == 0 ) {
					$_POST['affiliate_id'] = $user_model->insert_dealer_affiliate( $data );
				} else {
					$user_model->update_dealer_affiliate( $_POST['affiliate_id'], $data );
				}
				$view = new AT_View();
				$view->use_layout('content')
					->add_block( 'content', 'settings/dealer_affiliate_item', array(
						'affiliate' => $user_model->get_dealer_affiliate_by_id( $_POST['affiliate_id'] ),
						'regions' => $reference_model->get_regions()
					) );
				$content = $view->render()->display( TRUE );

				unset($view);

                $response = array( 'status' => 'OK', 'content' => $content , 'message' =>  __('The changes was saved.', AT_TEXTDOMAIN ) );
			} catch(Exception $e) {
            	$response =  array( 'status' => 'ERROR',  'message' => unserialize( $e->getMessage() ) );
        	}

			$this->view->add_json($response)->display();
			exit;
		}

		$user_model = $this->load->model( 'user_model' );
		$reference_model = $this->load->model( 'reference_model' );

		$this->view->use_layout('profile');
		$this->view->add_block( 'content', 'settings/dealer_affiliates', array( 
			'affiliates' => $user_model->get_dealer_affiliates( AT_Common::get_logged_user_id() ),
			'regions' => $reference_model->get_regions()
		) );

		$this->breadcrumbs->add_item( __( 'Account', AT_TEXTDOMAIN ), 'profile/' );
		$this->breadcrumbs->add_item( __( 'Dealer affiliates', AT_TEXTDOMAIN ), 'profile/settings/dealer_affiliates' );
		
		$menu_model = $this->load->model('menu_model');
		$this->view->add_block( 'left_side', 'general/navigation', $menu_model->get_menu('main', 'dealer_affiliates') );
	}

	public function dealer_affiliate_acions( $affiliate_id = '' ) {
		$affiliate_id = (int)$affiliate_id;
		if( $this->uri->is_ajax_request() && !empty( $_POST ) && isset( $_POST['action'] ) && $affiliate_id > 0 ) {
			try {
				$user_model = $this->load->model( 'user_model' );
				$affiliate = $user_model->get_dealer_affiliate_by_id( $affiliate_id );

				switch( $_POST['action'] ) {
					case 'delete':
						$user_model->update_dealer_affiliate( $affiliate_id, array( 'is_delete' => 1, 'is_main' => 0 ) );
						if ( $affiliate['is_main'] ){
							$affiliates = $user_model->get_dealer_affiliates( $affiliate['dealer_id'] );
							if ( count( $affiliates ) > 0 ) {
								$user_model->update_dealer_affiliate( $affiliates[0]['id'], array( 'is_main' => 1 ) );
							}
						}
						$response = array( 'status' => 'OK', 'message' =>  __('The affiliate was deleted.', AT_TEXTDOMAIN ) );
						break;
					case 'main':
						if ( $main_affiliate = $user_model->get_dealer_main_affiliate( $affiliate['dealer_id'] ) ){
							$user_model->update_dealer_affiliate( $main_affiliate['id'], array( 'is_main' => 0 ) );
						}
						$user_model->update_dealer_affiliate( $affiliate_id, array( 'is_main' => 1 ) );
						$response = array( 'status' => 'OK', 'message' =>  __('The affiliate was changed.', AT_TEXTDOMAIN ) );
						break;
				}
			} catch(Exception $e) {
            	$response =  array( 'status' => 'ERROR',  'message' => 'Error' );
        	}

			$this->view->add_json($response)->display();
			exit;
		} else {
			AT_Core::show_404();
		}
	}

	public function transactions() {

		if( $this->uri->is_ajax_request() && !empty( $_POST ) ) {
			try {
				if (!$this->validation->run('affiliate')) {
					throw new Exception( serialize($this->validation->get_errors()) );
				}
				$user_model = $this->load->model( 'user_model' );
				$reference_model = $this->load->model( 'reference_model' );
				
				$data = array(
					'dealer_id' => AT_Common::get_logged_user_id(),
					'name' => $_POST['name'],
					'email' => $_POST['email'],
					'adress' => isset( $_POST['adress'] ) ? $_POST['adress'] : '',
					'phone' => isset( $_POST['phone_1'] ) ? $_POST['phone_1'] : '',
					'phone_2' => isset( $_POST['phone_2'] ) ? $_POST['phone_2'] : '',
					'region_id' => isset( $_POST['region_id'] ) ? $_POST['region_id'] : '',
					'schedule' => isset( $_POST['schedule'] ) ? serialize( $_POST['schedule'] ) : serialize( array( 'monday' => '', 'tuesday' => '', 'wednesday' => '', 'thursday' => '', 'friday' => '', 'saturday' => '', 'sunday' => '' ) ),
				);

				$_POST['affiliate_id'] = (int)$_POST['affiliate_id'];
				if ( $_POST['affiliate_id'] == 0 ) {
					$_POST['affiliate_id'] = $user_model->insert_dealer_affiliate( $data );
				} else {
					$user_model->update_dealer_affiliate( $_POST['affiliate_id'], $data );
				}
				$view = new AT_View();
				$view->use_layout('content')
					->add_block( 'content', 'settings/dealer_affiliate_item', array(
						'affiliate' => $user_model->get_dealer_affiliate_by_id( $_POST['affiliate_id'] ),
						'regions' => $reference_model->get_regions()
					) );
				$content = $view->render()->display( TRUE );

				unset($view);

                $response = array( 'status' => 'OK', 'content' => $content , 'message' =>  __('The changes was saved.', AT_TEXTDOMAIN ) );
			} catch(Exception $e) {
            	$response =  array( 'status' => 'ERROR',  'message' => unserialize( $e->getMessage() ) );
        	}

			$this->view->add_json($response)->display();
			exit;
		}

		$user_model = $this->load->model( 'user_model' );
		$reference_model = $this->load->model( 'reference_model' );

		$this->view->use_layout('profile');
		$this->view->add_block( 'content', 'settings/transactions', array( 
			'transactions' => $user_model->get_user_transactions_by_id( AT_Common::get_logged_user_id() )
		) );

		$this->breadcrumbs->add_item( __( 'Account', AT_TEXTDOMAIN ), 'profile/' );
		$this->breadcrumbs->add_item( __( 'Transactions', AT_TEXTDOMAIN ), 'profile/settings/transactions' );
		
		$menu_model = $this->load->model('menu_model');
		$this->view->add_block( 'left_side', 'general/navigation', $menu_model->get_menu('main', 'transactions') );
	}

	public function want_be_dealer(){
		if( !$this->uri->is_ajax_request() || empty( $_POST ) || !isset( $_POST['comment'] ) ) {
			AT_Core::show_404();
		}
		try {
			$comment = trim($_POST['comment']);
			if ( empty( $comment ) ){
				throw new Exception( __( 'Comment is empty!', AT_TEXTDOMAIN ) );	
			}
			$mail_model = $this->load->model( 'mail_model' );
			$user_info = $this->registry->get( 'user_info' );
			$data = array(
				'username' => $user_info['name'],
				'comment' => $comment
			);
			$adm_email = get_option('admin_email');
			if( !$mail_model->send( 'template_mail_notify_want_be_dealer', $adm_email, $data, $user_info['email'], $user_info['name'] ) ) {
            	throw new Exception( __( 'Error send email! Try later.', AT_TEXTDOMAIN ) );	
            }

			$response = array( 'status' => 'OK', 'message' =>  __('The request was sent.', AT_TEXTDOMAIN ) );
		} catch(Exception $e) {
        	$response =  array( 'status' => 'ERROR',  'message' => $e->getMessage() );
    	}

		$this->view->add_json($response)->display();
		exit;
	}
}