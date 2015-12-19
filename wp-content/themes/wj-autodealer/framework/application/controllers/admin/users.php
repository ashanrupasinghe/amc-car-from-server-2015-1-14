<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Users extends AT_Admin_Controller{
	public function __construct() {
		parent::__construct();
	}

	public function index(){
		if( $this->uri->is_ajax_request() && !empty( $_POST ) ) {
			return $this->_ajax();
		}
		
		$tab = !empty($_GET['tab']) ? $_GET['tab'] : 'active';

		$user_model = $this->load->model('user_model');
		$paginator = $this->load->library('paginator');
		$paginator = $paginator->get_query_string( 'paged', $user_model->get_count_users(), 15, 1, 2, 'admin.php' );

		$orderby = (isset($_GET['orderby']) && in_array($_GET['orderby'], array( 'name', 'email', 'date_create', 'date_active' ) ) ) ? $_GET['orderby'] : 'name';
		$order = (isset($_GET['order']) && in_array($_GET['order'], array( 'asc', 'desc' ) ) ) ? $_GET['order'] : 'asc';

		$menu_model = $this->load->model('admin/menu_model');

		$this->view->use_layout('admin')
			->add_block( 'content', 'admin/users/list', array( 
				'items' => $user_model->get_users( $paginator['offset'], $paginator['per_page'], $orderby, $order, ( $tab == 'trash' ? true : false ) ),
				'orderby' => $orderby,
				'order' => $order,
				'tab' => $tab
			) )
			->add_block('content/tabs', 'admin/general/tabs', $menu_model->get_menu('users', $tab))
			->add_block( 'content/pagination', 'general/pagination', $paginator );
	}

	private function _ajax(){
		try {
			$_POST['user_id'] = (int)$_POST['user_id'];
			if ( empty( $_POST['action'] ) || ( $_POST['user_id'] == 0 && !in_array( $_POST['action'], array( 'get_add_form', 'add_user', 'get_all_users' ) ) ) ) {
				throw new Exception( 'Error!' );
			}
			$user_model = $this->load->model('user_model');
			switch ($_POST['action']) {
				case 'get_affiliates':
					$items = array();
					// foreach ( as $key => $value) {
					// 	$items[$value['id']] = $value['name'];
					// }
					$message = $user_model->get_dealer_affiliates( $_POST['user_id'] );
					break;
				case 'get_edit_form':
					$user_model = $this->load->model('user_model');
					$message = $this->view->use_layout('content')
						->add_block('content', 'admin/users/form', $user_model->get_user_by_id( $_POST['user_id'] ) )
						->render()->display( true );
					break;
				case 'get_add_form':
					$message = $this->view->use_layout('content')
						->add_block('content', 'admin/users/form' )
						->render()->display( true );
					break;
				case 'save_user':
					$user_model = $this->load->model('user_model');
					$data = array(
						'name' => $_POST['name'],
						'phone' => $_POST['phone'],
						'phone_2' => $_POST['phone_2'],
						'is_dealer' => (int)$_POST['is_dealer'],
					);
					if(isset($_POST['alias'])) $data['alias'] = $_POST['alias'];
					$user_model->update( $_POST['user_id'], $data );
					$message = __( 'User updated', AT_ADMIN_TEXTDOMAIN );
					//throw new Exception( 'Error!' );
					break;
				case 'add_user':
					$message = __( 'User added', AT_ADMIN_TEXTDOMAIN );
					$user_model = $this->load->model('user_model');
					if (!$user_model->create( $_POST ))
						throw new Exception( 'Failed create user!' );
					break;
				case 'user_block':
					$message = 'User blocked!';
					if (!$user_model->blocked($_POST['user_id'], $_POST['action_vehicles'], (int)$_POST['assign_user_id'] ))
						throw new Exception( 'Failed blocked!' );
					break;
				case 'user_unblock':
					$message = 'User unblocked!';
					if (!$user_model->unblocked($_POST['user_id']))
						throw new Exception( 'Failed unblocked!' );
					break;
				case 'user_change_password':
					$message = 'User\'s password has been changed!';

					if ( empty( $_POST['password'] ) ) {
						throw new Exception( 'Password incorrect!' );
					}

					$user_model = $this->load->model( 'user_model' );
					$salt = $user_model->generate_string( 20 );
					$data = array(
						'salt' => $salt,
						'password' => $user_model->get_hash( $_POST['password'], $salt )
					);
					if (!$user_model->update( $_POST['user_id'], $data ))
						throw new Exception( 'Failed changed!' );
					break;
				case 'get_all_users':
					$message = $user_model->get_data_for_options( 'get_all_users' );
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
}