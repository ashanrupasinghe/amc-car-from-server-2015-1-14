<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Install extends AT_Admin_Controller{
	public function __construct() {
		parent::__construct();
		// $this->core->set_option( 'theme_is_activated', false );
		// $this->core->save_option();
	}

	public function index(){
		if( $this->uri->is_ajax_request() && !empty( $_POST ) && !empty($_POST['action']) ) {
			return $this->_ajax();
		}
		if ( !$this->core->get_option( 'theme_is_activated', false ) ) {
			$this->view->use_layout('admin')
				->add_block( 'content', 'admin/install/view' );

		} else {
			wp_redirect('admin.php?page=at_site_options_general');
			die();
		}
	}

	private function _ajax(){
		$response = array();
		if ( $_POST['action'] == 'complete' ) {
			try {
				if (empty( $_POST['site_type'] ) || !in_array( $_POST['site_type'], array( 'mode_soletrader', 'mode_partnership', 'mode_board' ))) {
					throw new Exception( 'Error: Invalid data!' );
				}
				$this->core->set_option( 'site_type', $_POST['site_type'] );
				$this->core->save_option();

				$message = 'Finished...'; 
				$response = array( 'status' => 'OK', 'message' => $message );
			} catch(Exception $e) {
	        	$response =  array( 'status' => 'ERROR',  'message' => $e->getMessage() );
	    	}
		} elseif ( $_POST['action'] == 'install' ){ 		
			$install_model = $this->load->model('admin/install_model');
			try {
				if ( $this->core->get_option( 'theme_is_activated', false ) ) {
					throw new Exception( 'Error: Theme is already activated!' );	
				}
				if( empty($_POST['step']) || !isset($_POST['test_data'] ) ) {
					throw new Exception( 'Error: Invalid step or data!' );	
				}
				$test_data = ($_POST['test_data'] > 0) ? true : false ;
				switch ( $_POST['step'] ) {
					case 'check_environment':
						clearstatcache();
						if ( file_exists( AT_UPLOAD_DIR_THEME ) && !is_writable( AT_UPLOAD_DIR_THEME )) {
							throw new Exception( 'Directory is not writable:' . AT_UPLOAD_DIR_THEME  . '. Please update permissions manually and continue installation.');
						}
						// $dir_path = AT_DIR_THEME . '/usr_data/';
						// if (!@chmod($dir_path, 0755)) {
						// 	throw new Exception( 'Error: chmod(): Operation not permitted in:' . AT_DIR_THEME . '/usr_data/' );	
						// }
						$next_step = 'users';
						break;
					case 'users':	
						if( empty($_POST['name']) || empty($_POST['email'])  || empty($_POST['password']) ) {
							throw new Exception( 'Error: Invalid data!' );
						}
						$name = $_POST['name'];
						$email = $_POST['email'];
						$password = $_POST['password'];
						$install_model->create_users_table( array('name' => $name, 'email' => $email, 'password' => $password, 'date_active' => current_time('mysql') ) );
						$next_step = 'dealers_affiliates';
						break;
					case 'dealers_affiliates':
						$install_model->create_dealers_affiliates_table( $test_data );
						$next_step = 'manufacturers';
						break;
					case 'manufacturers':
						$install_model->create_manufacturers_table( $test_data );
						$next_step = 'models';
						break;
					case 'models':
						$install_model->create_models_table( $test_data );
						$next_step = 'body_types';
						break;
					case 'body_types':
						$install_model->create_body_types_table( $test_data );
						$next_step = 'transmissions';
						break;
					case 'transmissions':
						$install_model->create_transmissions_table( $test_data );
						$next_step = 'equipments';
						break;
					case 'equipments':
						$install_model->create_equipments_table( $test_data );
						$next_step = 'doors';
						break;
					case 'doors':
						$install_model->create_doors_table( $test_data );
						$next_step = 'fuels';
						break;
					case 'fuels':
						$install_model->create_fuels_table( $test_data );
						$next_step = 'technical_conditions';
						break;
					case 'technical_conditions':
						$install_model->create_technical_conditions_table( $test_data );
						$next_step = 'currencies';
						break;
					case 'currencies':
						$install_model->create_currencies_table( $test_data );
						$next_step = 'transport_types';
						break;
					case 'transport_types':
						$install_model->create_transport_types_table( $test_data );
						$next_step = 'regions';
						break;
					case 'regions':
						$install_model->create_regions_table( $test_data );
						$next_step = 'states';
						break;
					case 'states':
						$install_model->create_states_table( $test_data );
						$next_step = 'cities';
						break;
					case 'cities':
						$install_model->create_cities_table( $test_data );
						$next_step = 'region_types';
						break;
					case 'region_types':
						$install_model->create_region_types_table( $test_data );
						$next_step = 'drive';
						break;
					case 'drive':
						$install_model->create_drive_table( $test_data );
						$next_step = 'colors';
						break;
					case 'colors':
						$install_model->create_colors_table( $test_data );
						$next_step = 'recovery_password';
						break;
					case 'recovery_password':
						$install_model->create_recovery_password_table( $test_data );
						$next_step = 'transactions';
						break;
					case 'transactions':
						$install_model->create_transactions_table( $test_data );
						$next_step = 'photos';
						break;
					case 'photos':
						$install_model->create_photos_table( $test_data );
						$next_step = 'usr_data';
						break;
					case 'usr_data':
						//if (is_writable( AT_DIR_THEME . '/usr_data/' )) {
						if ($install_model->create_upload_dir()) {
							if(!$install_model->create_photo_car_structure()){
								throw new Exception( 'Error: chmod(): Operation not permitted in:' . AT_UPLOAD_DIR_THEME . '/car, please update permissions manually and continue installation.' );
							}
							if(!$install_model->create_photo_user_structure()){
								throw new Exception( 'Error: chmod(): Operation not permitted in:' . AT_UPLOAD_DIR_THEME . '/user, please update permissions manually and continue installation.' );
							}
						} else {
							throw new Exception( 'Directory is not writable:' . AT_UPLOAD_DIR_THEME . '. Please update permissions manually and continue installation.');
						}
						$next_step = 'options';
						break;
					case 'options':
						global $wp_rewrite;
						$wp_rewrite->flush_rules();
						$install_model->restore_options();
						$this->core->set_option( 'theme_is_activated', true );
						$this->core->set_option( 'current_theme_version', THEME_VERSION );
						$this->core->save_option();
						$next_step = 'finish';
						break;
				}
				if ( $next_step != 'finish' ) {
					$response = array( 'status' => 'NEXT', 'next_step' => $next_step );
				} else {
					$message = 'Complite...'; 
					$response = array( 'status' => 'OK', 'message' => $message );
				}
			} catch(Exception $e) {
	        	$response =  array( 'status' => 'ERROR',  'message' => $e->getMessage() );
	    	}
	    }
	 	$this->view->add_json($response)->display();
	}

	public function redirect(){
		// new install
		$install_model = $this->load->model('admin/install_model');

		$install_model->create_users_table( false );
		$install_model->create_dealers_affiliates_table( false );
		$install_model->create_manufacturers_table( false );
		$install_model->create_models_table( false );
		$install_model->create_body_types_table( false );
		$install_model->create_transmissions_table( false );
		$install_model->create_equipments_table( false );
		$install_model->create_doors_table( false );
		$install_model->create_fuels_table( false );
		$install_model->create_technical_conditions_table( false );
		$install_model->create_currencies_table( false );
		$install_model->create_transport_types_table( false );
		$install_model->create_regions_table( false );
		$install_model->create_states_table( false );
		$install_model->create_cities_table( false );
		$install_model->create_region_types_table( false );
		$install_model->create_drive_table( false );
		$install_model->create_colors_table( false );
		$install_model->create_recovery_password_table( false );
		$install_model->create_transactions_table( false );
		$install_model->create_photos_table( false );
		if ($install_model->create_upload_dir()) {
			$install_model->create_photo_car_structure();
			$install_model->create_photo_user_structure();
		}
		global $wp_rewrite;

		if ($wp_rewrite->permalink_structure != '/%postname%/') {
			$wp_rewrite->set_permalink_structure('/%postname%/');
		}
		$wp_rewrite->flush_rules();
		$install_model->restore_options();
		wp_redirect('admin.php?page=at_theme_install');
		die();
	}

}