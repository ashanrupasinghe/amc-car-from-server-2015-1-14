<?php
if (!defined("AT_DIR")) die('!!!');
/////////////////////////////////////////////////////////////////////
// main page
/////////////////////////////////////////////////////////////////////
class AT_Auth extends AT_Controller{
	public function __construct() {
		parent::__construct();
		$site_type = $this->core->get_option( 'site_type', 'mode_soletrader');
		if(!in_array( $site_type, array( 'mode_partnership', 'mode_board' ) ) && $this->uri->segments( 1 ) != 'unlogged' ) {
			AT_Core::show_404();
		}
	}

		public function login(){
		if( $this->uri->is_ajax_request() && !empty( $_POST ) && !AT_Common::is_user_logged() ) {
			try {
				if (!$this->validation->run('login')) {
					throw new Exception( serialize($this->validation->get_errors()) );
				}

				$user_model = $this->load->model( 'user_model' );

				if( !$user_info  = $user_model->get_user_by_email( $_POST['email'] ) ) {
                    throw new Exception( serialize( array( 'email' => __( 'Email & Password incorrect!', AT_TEXTDOMAIN ), 'password' => '' ) ) );
                }

                if ( $user_info['password'] != $user_model->get_hash( $_POST['password'], $user_info['salt'] ) ) {
                	throw new Exception( serialize( array( 'email' => __( 'Email & Password incorrect!', AT_TEXTDOMAIN ), 'password' => '' ) ) );
                }
                if ( $user_info['is_block'] ) {
					throw new Exception( serialize( array( 'email' => __( 'Your account was blocked!', AT_TEXTDOMAIN ), 'password' => '' ) ) );                	
                }
                
                $data = array( 'logged' => true, 'user_id' => $user_info['id'] );
				$this->session->sess_create( $data );
                $response = array( 'status' => 'OK', 'redirect_url' =>  AT_Common::site_url('/profile/'));

			} catch(Exception $e) {
            	$response =  array( 'status' => 'ERROR',  'message' => unserialize($e->getMessage()));
        	}

			$this->view->add_json($response)->display();
			exit;
		}

		if( !AT_Common::is_user_logged() ) {
			$this->view->use_layout('header_content2_footer');
			$this->view->add_block( 'content', 'auth/login', array( 'background' => $this->_get_rand_bg() ) );
		} else {
			AT_Common::redirect( 'profile/' );
		}
	}

	public function registration(){

		if( $this->core->get_option( 'site_type', 'mode_soletrader' ) == 'mode_soletrader' || !$this->core->get_option( 'registration_enable', true )){
			AT_Core::show_404();
		}

		if( $this->uri->is_ajax_request() && !empty( $_POST ) && !AT_Common::is_user_logged() ) {
			try {
				if (!$this->validation->run('registration')) {
					throw new Exception( serialize($this->validation->get_errors()) );
				}
                $user_model = $this->load->model( 'user_model' );
				$data = array(
					'name' => $_POST['name'],
		  			'email' => $_POST['email'],
		  			'password' => $_POST['pass'],
		  			'is_dealer' => 0
				);
				$user_id = $user_model->create( $data );
				$data = array( 'logged' => true, 'user_id' => $user_id );
				$this->session->sess_create( $data );

				$user_model = $this->load->model( 'user_model' );
				$mail_model = $this->load->model( 'mail_model' );

				$user_info = $user_model->get_user_by_id( $user_id );

                $code = $user_model->get_confirm_email_code( $user_info['id'], $user_info['email'] );
                $data = array(
                	'username' => $user_info['name'],
                	'confirm_url' => AT_Common::site_url( 'auth/confirm_email/' . $code . '/' ),
                	'confirm_code' => $code
                );
                $mail_model->send( 'template_mail_confirm_email', $user_info['email'], $data );

                $response = array( 'status' => 'OK', 'redirect_url' =>  AT_Common::site_url('/profile/'));

			} catch(Exception $e) {
            	$response =  array( 'status' => 'ERROR',  'message' => unserialize($e->getMessage()));
        	}

			$this->view->add_json($response)->display();
			exit;
		}
		if( !AT_Common::is_user_logged() ) {
			$this->view->use_layout('header_content2_footer');
			$this->view->add_block( 'content', 'auth/registration', array( 'background' => $this->_get_rand_bg() ) );
		} else {
			AT_Common::redirect( 'profile/' );
		}
	}

	public function recovery_pass( $hash = '' ) {
		if( !AT_Common::is_user_logged() ) {
			$user_model = $this->load->model( 'user_model' );
			if( $this->uri->is_ajax_request() && !empty( $_POST ) ) {
				try {
					if ( !isset( $_POST['hash'] ) || !( $user_id = $user_model->check_valid_hash( $_POST['hash'] ) ) ){
						throw new Exception( serialize( array( 'hash' => __( 'Hash not found!', AT_TEXTDOMAIN )) ) );
					}
					if ( !isset( $_POST['new_password'] ) ){
						$response = array( 'status' => 'PASS', 'message' => '' );
					} else {
						if (!$this->validation->run('recovery_password_form')) {
							throw new Exception( serialize($this->validation->get_errors()) );
						}
						$salt = $user_model->generate_string( 20 );
						$data = array(
							'salt' => $salt,
							'password' => $user_model->get_hash( $_POST['new_password'], $salt )
						);
						$user_model->update( $user_id, $data );
						$user_model->set_used_hashes_by_user_id( $user_id );

						$response = array( 'status' => 'OK', 'message' => __( 'The password was changed.', AT_TEXTDOMAIN ), 'redirect_url' => AT_Common::site_url( '/' ) );
					}
				} catch(Exception $e) {
	            	$response =  array( 'status' => 'ERROR',  'message' => unserialize($e->getMessage()));
	        	}

				$this->view->add_json($response)->display();
				exit;
			}
			
			if( !( $valid = $user_model->check_valid_hash( $hash ) ) ){
				$hash = '';
			}

			$this->view->use_layout('header_content2_footer');
			$this->view->add_block( 'content', 'auth/recovery_pass', array( 'hash' => $hash, 'valid' => $valid, 'background' => $this->_get_rand_bg() ) );
		} else {
			AT_Common::redirect( '/' );
		}
	}

	public function recovery(){
		if( !AT_Common::is_user_logged() ) {
			if( $this->uri->is_ajax_request() && !empty( $_POST ) ) {
				try {
					if (!$this->validation->run('recovery_password')) {
						throw new Exception( serialize($this->validation->get_errors()) );
					}

					$user_model = $this->load->model( 'user_model' );
					$mail_model = $this->load->model( 'mail_model' );

					if( !$user_info  = $user_model->get_user_by_email( $_POST['email'] ) ) {
	                    throw new Exception( serialize( array( 'email' => __( 'Email not found!', AT_TEXTDOMAIN )) ) );
	                }
	                $code = $user_model->get_recovery_hash( $user_info['id'] );
	                $data = array(
	                	'username' => $user_info['name'],
	                	'recovery_link' => AT_Common::site_url( 'auth/recovery_pass/' . $code . '/' ),
	                	'recovery_code' => $code
	                );
	                if( !$mail_model->send( 'template_mail_recovery_password', $user_info['email'], $data ) ) {
	                	throw new Exception( serialize( array( 'email' => __( 'Error send email! Try later.', AT_TEXTDOMAIN )) ) );	
	                }

	                $response = array( 'status' => 'OK', 'message' => __( 'Message was sent!', AT_TEXTDOMAIN ), 'redirect_url' => AT_Common::site_url( 'auth/recovery_pass/' ) );

				} catch(Exception $e) {
	            	$response =  array( 'status' => 'ERROR',  'message' => unserialize($e->getMessage()));
	        	}

				$this->view->add_json($response)->display();
				exit;
			}

			$this->view->use_layout('header_content2_footer');
			$this->view->add_block( 'content', 'auth/recovery', array( 'background' => $this->_get_rand_bg() ) );
		} else {
			AT_Common::redirect( '/' );
		}
	}

	public function confirm_email( $confirm_code = '' ){
		if( AT_Common::is_user_logged() && $this->core->get_option( 'confirm_email_enable', true ) ) {
			$user_info = $this->registry->get( 'user_info' );
			if ( !empty( $user_info['date_active'] ) ){
				AT_Common::redirect( '/profile' );
			}

			if( $this->uri->is_ajax_request() && !empty( $_POST ) ) {
				try {

					$user_model = $this->load->model( 'user_model' );
					$user_info = $this->registry->get( 'user_info' );

					if( isset($_POST['action']) && $_POST['action'] == 'send_again' ) {
						$mail_model = $this->load->model( 'mail_model' );

		                $code = $user_model->get_confirm_email_code( $user_info['id'], $user_info['email'] );
		                $data = array(
		                	'username' => $user_info['name'],
		                	'confirm_url' => AT_Common::site_url( 'auth/confirm_email/' . $code . '/' ),
		                	'confirm_code' => $code
		                );
		                if( !$mail_model->send( 'template_mail_confirm_email', $user_info['email'], $data ) ) {
		                	throw new Exception( serialize( array( 'code' => __( 'Error send email! Try later.', AT_TEXTDOMAIN )) ) );	
		                }
		                $response = array( 'status' => 'OK', 'message' => __( 'Message was sent!', AT_TEXTDOMAIN ) );

					} else {

						if (!$this->validation->run('confirm_email')) {
							throw new Exception( serialize($this->validation->get_errors()) );
						}
						$code = $user_model->get_confirm_email_code( $user_info['id'], $user_info['email'] );
						if( $_POST['code'] != $code ) {
							throw new Exception( serialize( array( 'code' => __( 'Сode is not valid.', AT_TEXTDOMAIN )) ) );	
						}
						$user_model->update( $user_info['id'], array( 'date_active' =>  current_time('mysql') ));

		                $response = array( 'status' => 'OK', 'message' => __( 'Сode is valid!', AT_TEXTDOMAIN ), 'redirect_url' => AT_Common::site_url( 'profile/' ) );
	            	}

				} catch(Exception $e) {
	            	$response =  array( 'status' => 'ERROR',  'message' => unserialize($e->getMessage()));
	        	}

				$this->view->add_json($response)->display();
				exit;
			}

			if( !empty($confirm_code) ) {
				$user_model = $this->load->model( 'user_model' );
				$user_info = $this->registry->get( 'user_info' );
				$code = $user_model->get_confirm_email_code( $user_info['id'], $user_info['email'] );
				if( $confirm_code == $code ) {
					$user_model->update( $user_info['id'], array( 'date_active' =>  current_time('mysql') ));
					AT_Common::redirect( 'profile/' );
				}
			}

			$this->view->use_layout('header_content2_footer');
			$this->view->add_block( 'content', 'auth/confirm_email', array( 'background' => $this->_get_rand_bg() ) );
		} else {
			AT_Common::redirect( '/' );
		}
	}

	public function unlogged(){
		$this->session->sess_destroy();
		AT_Common::redirect( '/' );
	}

	private function _get_rand_bg(){
		$backgrounds = array( '01.jpg', '02.jpg', '03.jpg' );
		return $backgrounds[rand( 0, count($backgrounds) - 1 )];
	}

}
?>