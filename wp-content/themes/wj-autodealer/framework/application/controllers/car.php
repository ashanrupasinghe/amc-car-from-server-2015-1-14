<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Car extends AT_Controller{
	public function __construct() {
		parent::__construct();
	}

	public function single(){
		$car_model = $this->load->model('car_model');
		$user_model = $this->load->model('user_model');
		if( !($car_info = $car_model->get_car_info( get_the_ID() )) ){
			AT_Core::show_404();
		}

		$car_model->set_car_views( get_the_ID() );
		$reference_model = $this->load->model('reference_model');
		$photo_model = $this->load->model('photo_model');

		$owner_info = $user_model->get_user_by_id( $car_info['options']['_owner_id'] );

		$contacts_owner = array();
		if ( $owner_info['is_dealer'] ) {
			if( $car_info['options']['_affiliate_id'] > 0 ) {
				$affiliate = $user_model->get_dealer_affiliate_by_id( $car_info['options']['_affiliate_id'] );
			}
			if( empty( $affiliate ) ) {
				$affiliate = $user_model->get_dealer_main_affiliate( $car_info['options']['_owner_id'] );	
			}
			if( $affiliate ) {
				$phones = array();
				if( !empty( $affiliate['phone'] ) ) $phones[] = $affiliate['phone'];
				if( !empty( $affiliate['phone_2'] ) ) $phones[] = $affiliate['phone_2'];
				$contacts_owner = array(
					'phones' => implode( ', ', $phones ),
					'email' => $affiliate['email'],
					'adress' => (!empty($affiliate['region']) ? $affiliate['region'] . ', ' : '') . $affiliate['adress'],
					'url' => AT_Common::site_url( 'dealer/info/' . trim( $owner_info['alias'] . '-'  . $car_info['options']['_owner_id'], '-') . '/' ),
					'add_offer' => true,
					'is_dealer' => true,
					'name' => $owner_info['name'],
					'photo' => $owner_info['photo'],
				);
			} else{
				$phones = array();
				if( !empty( $owner_info['phone'] ) ) $phones[] = $owner_info['phone'];
				if( !empty( $owner_info['phone_2'] ) ) $phones[] = $owner_info['phone_2'];
				$contacts_owner = array(
					'phones' => implode( ', ', $phones ),
					'email' => '',
					'adress' => '',
					'url' => AT_Common::site_url( 'dealer/info/' . trim( $owner_info['alias'] . '-'  . $car_info['options']['_owner_id'], '-') . '/' ),
					'add_offer' => true,
					'is_dealer' => true,
					'name' => $owner_info['name'],
					'photo' => $owner_info['photo'],
				);
			}
		} else {
			$phones = array();
			if( !empty( $owner_info['phone'] ) ) $phones[] = $owner_info['phone'];
			if( !empty( $owner_info['phone_2'] ) ) $phones[] = $owner_info['phone_2'];
			if ( isset( $owner_info['region_id'] ) ) {
				$region = $reference_model->get_region_by_id( $owner_info['region_id'] );
			}
			$contacts_owner = array(
				'phones' => implode( ', ', $phones ),
				'email' => $owner_info['email'],
				'adress' => !empty($region['name']) ? $region['name'] : '',
				'url' => '',
				'add_offer' => false,
				'is_dealer' => false,
				'name' => $owner_info['name'],
				'photo' => $owner_info['photo'],
			);
		}

		$this->breadcrumbs->add_item( __( 'Catalog', AT_TEXTDOMAIN ), 'catalog/' );

		if( $manufacturer_data =  $reference_model->get_manufacturer_by_id( $car_info['options']['_manufacturer_id'] ) ) {
			$this->breadcrumbs->add_item( $manufacturer_data['name'], 'catalog/' . $manufacturer_data['alias'] );
			if ( $model_data =  $reference_model->get_model_by_id( $car_info['options']['_model_id'] ) ) {
				$this->breadcrumbs->add_item( $model_data['name'], 'catalog/' . $manufacturer_data['alias'] . '/' . $model_data['alias'] );
			}
		}


		$this->breadcrumbs->add_item( $car_info['post_title'], '' );
		$this->view->use_layout('header_content_footer')
			->add_block('content', 'car/view', array( 
				'car_info' => $car_info, 
				'contacts_owner' => $contacts_owner, 
				'car_photos' => $photo_model->get_photos_by_post( get_the_ID(), 'car' ),
				'equipments' =>  $reference_model->get_equipments(),
				'car_views' =>  $car_model->get_car_views( get_the_ID() ),
				'is_dealer' => $owner_info['is_dealer'],
			))
			->add_block('content/recent_cars', 'car/recent_cars', array( 'cars' => $car_model->get_similar_car( get_the_ID() ) ) );
			//->add_block('content/loan_calculator', 'additions/loan_calculator');
	}

}