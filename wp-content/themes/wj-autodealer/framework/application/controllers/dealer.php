<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Dealer extends AT_Controller{
	public function __construct() {
		parent::__construct();
	}

	public function info( $dealer = '' ){
		try {
			if( empty($dealer) ) {
				throw new Exception();
			}
			if ( is_numeric($dealer) ){
				$dealer_id = $dealer;
			}else{
				$segment = explode('-', $dealer);
				if(count($segment) == 1 ){
					throw new Exception();
				}
				$dealer_id = $segment[count($segment) - 1];
				if(!is_numeric($dealer_id)){
					throw new Exception();	
				}
			}

			$user_model = $this->load->model('user_model');
			if( !($dealer_info = $user_model->get_user_by_id( $dealer_id )) || !$dealer_info['is_dealer'] || $dealer_info['is_block'] ){
				throw new Exception();
			}
			if ( is_numeric($dealer) && !empty( $dealer_info['alias'] ) ){
				wp_redirect( AT_Common::site_url( 'dealer/info/' . trim( $dealer_info['alias'] . '-'  . $dealer_info['id'], '-') . '/' ), 301 );
				exit;
			}

		} catch(Exception $e) {
        	AT_Core::show_404();
    	}

		switch ($dealer_info['layout']) {
			case 'layout_2':
				$layout = 'content';
				$right_side = 'content/right_side';
				break;
			default:
				$layout = 'content_right';
				$right_side = 'right_side';
				break;
		}

		$car_model = $this->load->model('car_model');
		$reference_model = $this->load->model('reference_model');

		$dealer_contact = array();
		$affiliate = $user_model->get_dealer_main_affiliate( $dealer_info['id'] );
		if( $affiliate ) {
			$phones = array();
			if( trim( $affiliate['phone'] ) != '' ) $phones[] = trim($affiliate['phone'] );
			if( trim( $affiliate['phone_2'] ) != '' ) $phones[] = trim( $affiliate['phone_2'] );
			$dealer_contact = array(
				'phones' => implode( '<br/>', $phones ),
				'email' => $affiliate['email'],
				'adress' => (!empty($affiliate['region']) ? $affiliate['region'] . ', ' : '') . $affiliate['adress'],
				'url' => AT_Common::site_url( 'dealer/info/' . trim( $dealer_info['alias'] . '-'  . $dealer_info['id'], '-') . '/' ),
			);
		}

		$affiliates = $user_model->get_dealer_affiliates( $dealer_info['id'] );

		$paginator = $this->load->library('paginator');
		$paginator = $paginator->get(3, $car_model->get_cars_count_by_user_id( $dealer_info['id'] ), $dealer_info['per_page'] );

		$this->breadcrumbs->add_item( __( 'Catalog', AT_TEXTDOMAIN ), 'catalog');
		$this->breadcrumbs->add_item( $dealer_info['name'], 'dealer/info/' . trim( $dealer_info['alias'] . '-'  . $dealer_info['id'], '-') . '/'  );
		$this->view->use_layout( 'header_' . $layout . '_footer' )
			->add_block('page_title', 'general/page_title', array( 'page_title' => $dealer_info['name'] ))
			->add_block($right_side, 'dealer/right_side', array( 
				'dealer_info' => $dealer_info,
				'dealer_contact' => $dealer_contact,
				'affiliate' => $affiliate,
				'affiliates' => $user_model->get_dealer_affiliates( $dealer_info['id'] ),
			))
			->add_block('content', 'dealer/info', array(
				'layout' => $layout,
				'best_offers' => $car_model->get_best_offers( $dealer_info['id'] ),
				'cars' => $car_model->get_cars_by_user_id( $dealer_info['id'], $paginator['offset'], $paginator['per_page'] )
			))
			->add_block( 'content/pagination', 'general/pagination', $paginator );

	}

}