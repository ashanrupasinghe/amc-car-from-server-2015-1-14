<?php
if (!defined("AT_DIR")) die('!!!');

class AT_car_model extends AT_Model{

	public function get_car_options(){
		$reference_model = $this->load->model('reference_model');
		return array_merge($reference_model->get_equipments_alias(), array(
			'_manufacturer_id' => array( 'method' => 'get_manufacturer_by_id'),
			'_model_id' => array( 'method' => 'get_model_by_id'),
			'_body_type_id' => array( 'method' => 'get_body_type_by_id'),
			'_transmission_id' => array( 'method' => 'get_transmission_by_id'),
			'_door_id' => array( 'method' => 'get_door_by_id'),
			'_fuel_id' => array( 'method' => 'get_fuel_by_id'),
			'_technical_condition_id' => array( 'method' => 'get_technical_condition_by_id'),
			'_currency_id' => array( 'method' => 'get_currency_by_id'),
			'_transport_type_id' => array( 'method' => 'get_transport_type_by_id'),
			'_region_id' => array( 'method' => 'get_region_by_id'),
			'_state_id' => array( 'method' => 'get_state_by_id'),
			'_drive_id' => array( 'method' => 'get_drive_by_id'),
			'_color_id' => array( 'method' => 'get_color_by_id'),
			'_owner_id' => true,
			'_affiliate_id' => true,

			'_vin' => true,
			'_fabrication' => true,
			'_cilindrics' => true,
			'_price' => true,
			'_mileage' => true,
			'_price_negotiable' => true,
			'_version' => true,
			'_seats' => true,
			'_engine_power' => true,
			'_best_offer' => true,
			'_category_id' => true
		));
	}

	// Data without description options
	private function _get_car_info( $car_id ){
		$car_info = get_post( $car_id, ARRAY_A);
		if (!is_null($car_info)) {
			$car_info['options'] = array();
			$reference_model = $this->load->model('reference_model');
			foreach ($this->get_car_options() as $key => $value) {
				$car_info['options'][$key] = get_post_meta( $car_id, $key, true);
			}
			//$car_info['options']['_price'] = (float)$car_info['options']['_price'];
		}
		return (!is_null( $car_info ) ? $car_info : false);
	}

	// Data with description options, use _get_car_info
	public function get_car_info( $car_id ){
		$car_info = $this->_get_car_info( $car_id );
		if ($car_info) {
			//$car_info['options'] = array();
			$reference_model = $this->load->model('reference_model');
			foreach ($this->get_car_options() as $key => $value) {
				//$car_info['options'][$key] = get_post_meta( $car_id, $key, true);
				if ( is_array( $value ) && !is_null( $car_info['options'][$key] ) ) {
					$car_info['options'][$key] = $reference_model->$value['method']($car_info['options'][$key]);
				}
			}
			$photo_model = $this->load->model('photo_model');
			$car_info['photo'] = $photo_model->get_photo_by_post( $car_id, 'car', 1 );
		}
		return (!is_null( $car_info ) ? $car_info : false);
	}


	public function update_car_info( $car_id, $data ){
		$post = array(
		 	'ID' => 	$car_id,
			'post_title'	=> strip_tags($data['post_title']),
			'post_content'	=> strip_tags($data['post_content']),
		);
		wp_update_post($post);
		foreach ($this->get_car_options() as $key => $value) {
			if (isset($data[$key])) {
				update_post_meta( $car_id, $key, $data[$key] );
				if ( !$value ) {
					// update_option_in_table
				}
			}
		}
	}

	public function add_car_info( $data ){
		$post = array(
			'post_title'	=> strip_tags($data['post_title']), // replace on concat _manufacturer_id _model_id
			'post_content'	=> strip_tags($data['post_content']),
			// 'post_category'	=> $_POST['cat'],
			// 'tags_input'	=> $tags,
			'post_status'	=> isset($data['post_status']) ? $data['post_status'] : 'publish',
			'post_type'		=> 'car'
		);
		$car_id = wp_insert_post($post);
		foreach ($this->get_car_options() as $key => $value) {
			$update_value = (!isset($data[$key]) ? false : $data[$key] );
			add_post_meta( $car_id, $key, $update_value );
			if ( !$value ) {
				// update_option_in_table
			}
		}
		return $car_id;
	}

	public function check_user_cars( $car_id, $user_id, $post_status = 'publish'  ){
		$res = $this->wpdb->get_row('SELECT count( ' . $this->wpdb->posts .'.ID ) AS count
		    FROM ' . $this->wpdb->posts . ', ' . $this->wpdb->postmeta . '
		    WHERE '. $this->wpdb->posts . '.ID = \'' . $car_id . '\'
		    AND ' . $this->wpdb->postmeta . '.meta_key = \'_owner_id\' 
		    AND ' . $this->wpdb->postmeta . '.meta_value = \'' . $user_id . '\'
		    AND ' . $this->wpdb->posts . '.post_status = \'' . $post_status . '\' 
		    AND ' . $this->wpdb->posts . '.post_type = \'car\' LIMIT 1',  ARRAY_A);
		return $res['count'];
	}

	public function get_cars_count_by_user_id( $user_id, $post_status = 'publish'  ){
		$res = $this->wpdb->get_row('SELECT count( ' . $this->wpdb->posts .'.ID ) AS count
		    FROM ' . $this->wpdb->posts . ', ' . $this->wpdb->postmeta . '
		    WHERE ' . $this->wpdb->posts . '.ID = ' . $this->wpdb->postmeta . '.post_id 
		    AND ' . $this->wpdb->postmeta . '.meta_key = \'_owner_id\' 
		    AND ' . $this->wpdb->postmeta . '.meta_value = \'' . $user_id . '\'
		    AND ' . $this->wpdb->posts . '.post_status = \'' . $post_status . '\' 
		    AND ' . $this->wpdb->posts . '.post_type = \'car\'',  ARRAY_A);
		return $res['count'];
	}

	public function get_cars_by_user_id( $user_id, $offset = 0 , $limit = 10, $post_status = 'publish' ){
		$ids = $this->wpdb->get_results('SELECT posts.ID, posts.post_date 
		    FROM ' . $this->wpdb->posts . ' posts
		    INNER JOIN ' . $this->wpdb->postmeta .' owner
					ON owner.post_id = posts.ID 
					AND owner.meta_key = "_owner_id"
					AND owner.meta_value = "' . $user_id . '"
		    WHERE posts.post_status = "' . $post_status . '" AND posts.post_type = "car"
		    ORDER BY posts.ID DESC, posts.post_date DESC
		    LIMIT ' . $offset . ', ' . $limit,  ARRAY_A);
		
		$cars = array();
		$photo_model = $this->load->model('photo_model');
		foreach($ids as $key => $value){
			$cars[] = array_merge($this->get_car_info( $value['ID'] ),
			array('count_photos' => count($photo_model->get_photos_by_post( $value['ID'], 'car' )),
			'views' =>  $this->get_car_views( $value['ID'] )));
		}
		return $cars;
	}

	public function get_cars( $params, $offset = 0 , $limit = 10, $post_status = 'publish', $sorted_field = 'post_date', $sorted_direction = 'DESC' ) {
		$join = '';
		$where = array();

		$sorted = 'posts.post_date';

		if ( $sorted_field == 'post_title' ){
			$sorted = 'posts.post_title';
			if(empty($sorted_direction)) $sorted_direction = 'ASC';
		}

		if ( isset( $params['featured'] ) && $params['featured'] == true ) {
			$where[] = 'featured.meta_value = \'1\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' featured
						ON featured.post_id = posts.ID AND
						featured.meta_key = \'_featured_car\'';
			// get_post_meta( $car['ID'], '_featured_car', true) );
		}

		if (!empty( $params['cars_categories'] )){
			if( $params['cars_categories'] == 'new' ) {
				$where[] = 'cars_categories.meta_value = \'1\'';
				$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' cars_categories
							ON cars_categories.post_id = posts.ID AND
							cars_categories.meta_key = \'_category_id\'';
			} else if( $params['cars_categories'] == 'used' ) {
				$where[] = '( cars_categories.meta_value = \'2\' OR cars_categories.meta_value is NULL)';
				$join .= ' LEFT JOIN ' . $this->wpdb->postmeta .' cars_categories
							ON cars_categories.post_id = posts.ID AND
							cars_categories.meta_key = \'_category_id\'';
			}
		}

		if ( $sorted_field == 'manufacturer' || (isset( $params['manufacturer_id'] ) && $params['manufacturer_id'] > 0) ){
			if (isset( $params['manufacturer_id']) && $params['manufacturer_id'] > 0) $where[] = 'manufacturer.meta_value = \'' . $params['manufacturer_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' manufacturer
						ON manufacturer.post_id = posts.ID AND
						manufacturer.meta_key = \'_manufacturer_id\'';
		}
		if ($sorted_field == 'manufacturer'){
			$join .= ' LEFT JOIN ' . $this->_manufacturers_table . '
						ON ' . $this->_manufacturers_table . '.id = manufacturer.meta_value';
			$sorted = $this->_manufacturers_table . '.name';
		}

		if (isset( $params['model_id'] ) && $params['model_id'] > 0){
			$where[] = 'model.meta_value = \'' . $params['model_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' model
						ON model.post_id = posts.ID AND
						model.meta_key = \'_model_id\'';
		}

		if (($sorted_field == '_price') || (isset( $params['price_from'] ) && $params['price_from'] != '') || (isset( $params['price_to'] ) && $params['price_to'] != '')){
			if (isset( $params['price_from'] ) && $params['price_from'] != '')
				$where[] = 'price.meta_value >= ' . $params['price_from'];
			if (isset( $params['price_to'] ) && $params['price_to'] != '')
				$where[] = 'price.meta_value <= ' . $params['price_to'];
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' price
						ON price.post_id = posts.ID AND
						price.meta_key = \'_price\'';
			if ($sorted_field == '_price') $sorted = 'cast(price.meta_value AS signed)';
			if(empty($sorted_direction)) $sorted_direction = 'ASC';
		}

		if ((isset( $params['mileage_from'] ) && $params['mileage_from'] != '') || (isset( $params['mileage_to'] ) && $params['mileage_to'] != '')){
			if (isset( $params['mileage_from'] ) && $params['mileage_from'] != '')
				$where[] = 'mileage.meta_value >= ' . $params['mileage_from'];
			if (isset( $params['mileage_to'] ) && $params['mileage_to'] != '')
				$where[] = 'mileage.meta_value <= ' . $params['mileage_to'];
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' mileage
						ON mileage.post_id = posts.ID AND
						mileage.meta_key = \'_mileage\'';
		}

		if ((isset( $params['fabrication_from'] ) && $params['fabrication_from'] != '') || (isset( $params['fabrication_to'] ) && $params['fabrication_to'] != '')){
			if (isset( $params['fabrication_from'] ) && $params['fabrication_from'] != '')
				$where[] = 'fabrication.meta_value >= ' . $params['fabrication_from'];
			if (isset( $params['fabrication_to'] ) && $params['fabrication_to'] != '')
				$where[] = 'fabrication.meta_value <= ' . $params['fabrication_to'];
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' fabrication
						ON fabrication.post_id = posts.ID AND
						fabrication.meta_key = \'_fabrication\'';
		}

		if (isset( $params['transport_type_id'] ) && $params['transport_type_id'] > 0){
			$where[] = 'transport_type.meta_value = \'' . $params['transport_type_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' transport_type
						ON transport_type.post_id = posts.ID AND
						transport_type.meta_key = \'_transport_type_id\'';
		}

		if (isset( $params['body_type_id'] ) && $params['body_type_id'] > 0){
			$where[] = 'body_type.meta_value = \'' . $params['body_type_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' body_type
						ON body_type.post_id = posts.ID AND
						body_type.meta_key = \'_body_type_id\'';
		}

		if (isset( $params['fuel_id'] ) && $params['fuel_id'] > 0){
			$where[] = 'fuel.meta_value = \'' . $params['fuel_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' fuel
						ON fuel.post_id = posts.ID AND
						fuel.meta_key = \'_fuel_id\'';
		}

		if ((isset( $params['engine_from'] ) && $params['engine_from'] > 0 ) || (isset( $params['engine_to'] ) && $params['engine_to'] != '')){
			if (isset( $params['engine_from'] ) && $params['engine_from'] > 0)
				$where[] = 'cilindrics.meta_value >= ' . $params['engine_from'];
			if (isset( $params['engine_to'] ) && $params['engine_to'] > 0)
				$where[] = 'cilindrics.meta_value <= ' . $params['engine_to'];
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' cilindrics
						ON cilindrics.post_id = posts.ID AND
						cilindrics.meta_key = \'_cilindrics\'';
		}

		if (isset( $params['transmission_id'] ) && $params['transmission_id'] > 0){
			$where[] = 'transmission.meta_value = \'' . $params['transmission_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' transmission
						ON transmission.post_id = posts.ID AND
						transmission.meta_key = \'_transmission_id\'';
		}

		if (isset( $params['door_id'] ) && $params['door_id'] > 0){
			$where[] = 'door.meta_value = \'' . $params['door_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' door
						ON door.post_id = posts.ID AND
						door.meta_key = \'_door_id\'';
		}

		if (isset( $params['region_id'] ) && $params['region_id'] > 0){
			$where[] = 'region.meta_value = \'' . $params['region_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' region
						ON region.post_id = posts.ID AND
						region.meta_key = \'_region_id\'';
		}

		if (isset( $params['state_id'] ) && $params['state_id'] > 0){
			$where[] = 'state.meta_value = \'' . $params['state_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' state
						ON state.post_id = posts.ID AND
						state.meta_key = \'_state_id\'';
		}

		if (isset( $params['drive_id'] ) && $params['drive_id'] > 0){
			$where[] = 'drive.meta_value = \'' . $params['drive_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' drive
						ON drive.post_id = posts.ID AND
						drive.meta_key = \'_drive_id\'';
		}

		if (isset( $params['color_id'] ) && $params['color_id'] > 0){
			$where[] = 'color.meta_value = "' . $params['color_id'] . '"';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' color
						ON color.post_id = posts.ID AND
						color.meta_key = "_color_id"';
		}
		
		if($this->core->get_option('merchant_module_promote', false)){
			///// JOIN PROMOTED TO TOP COLUMNS
			$join .= ' LEFT JOIN ' . $this->wpdb->postmeta .' promoted
						ON promoted.post_id = posts.ID AND
						promoted.meta_key = "_promote_top_exp"';

			// ADDITIONAL PROMOTED CHECK
			$sorted = 'promoted.meta_value DESC, ' . $sorted;
		}
		$where = (($where = implode(' AND ', $where)) != '') ? (' AND ' . $where) : '';
		$ids = $this->wpdb->get_results('SELECT posts.ID 
		    FROM ' . $this->wpdb->posts . ' posts
		    ' . $join . '
		    WHERE posts.post_status = \'' . $post_status . '\' AND posts.post_type = \'car\'
			' . $where . '    
		    ORDER BY ' . $sorted . ' ' . $sorted_direction .'
		    LIMIT ' . $offset . ' , ' . $limit . ';',  ARRAY_A);

		$cars = array();
		$user_model = $this->load->model('user_model');
		foreach($ids as $key => $value){
			$car = $this->get_car_info( $value['ID'] );
			$cars[] = array_merge($car, array('owner_info' => $user_model->get_user_by_id( $car['options']['_owner_id'] ) ));
		}
		//print_r($cars);
		return $cars;
	}


	public function get_cars_count( $params, $post_status = 'publish' ) {
		$join = '';
		$where = array();
		
		if (!empty( $params['cars_categories'] )){
			if( $params['cars_categories'] == 'new' ) {
				$where[] = 'cars_categories.meta_value = \'1\'';
				$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' cars_categories
							ON cars_categories.post_id = posts.ID AND
							cars_categories.meta_key = \'_category_id\'';
			} else if( $params['cars_categories'] == 'used' ) {
				$where[] = '( cars_categories.meta_value = \'2\' OR cars_categories.meta_value is NULL)';
				$join .= ' LEFT JOIN ' . $this->wpdb->postmeta .' cars_categories
							ON cars_categories.post_id = posts.ID AND
							cars_categories.meta_key = \'_category_id\'';
			}
		}

		if (isset( $params['manufacturer_id'] ) && $params['manufacturer_id'] > 0){
			$where[] = 'manufacturer.meta_value = \'' . $params['manufacturer_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' manufacturer
						ON manufacturer.post_id = posts.ID AND
						manufacturer.meta_key = \'_manufacturer_id\'';
		}

		if (isset( $params['model_id'] ) && $params['model_id'] > 0){
			$where[] = 'model.meta_value = \'' . $params['model_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' model
						ON model.post_id = posts.ID AND
						model.meta_key = \'_model_id\'';
		}

		if ((isset( $params['price_from'] ) && $params['price_from'] != '') || (isset( $params['price_to'] ) && $params['price_to'] != '')){
			if (isset( $params['price_from'] ) && $params['price_from'] != '')
				$where[] = 'price.meta_value >= ' . $params['price_from'];
			if (isset( $params['price_to'] ) && $params['price_to'] != '')
				$where[] = 'price.meta_value <= ' . $params['price_to'];
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' price
						ON price.post_id = posts.ID AND
						price.meta_key = \'_price\'';
		}

		if ((isset( $params['mileage_from'] ) && $params['mileage_from'] != '') || (isset( $params['mileage_to'] ) && $params['mileage_to'] != '')){
			if (isset( $params['mileage_from'] ) && $params['mileage_from'] != '')
				$where[] = 'mileage.meta_value >= ' . $params['mileage_from'];
			if (isset( $params['mileage_to'] ) && $params['mileage_to'] != '')
				$where[] = 'mileage.meta_value <= ' . $params['mileage_to'];
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' mileage
						ON mileage.post_id = posts.ID AND
						mileage.meta_key = \'_mileage\'';
		}

		if ((isset( $params['fabrication_from'] ) && $params['fabrication_from'] != '') || (isset( $params['fabrication_to'] ) && $params['fabrication_to'] != '')){
			if (isset( $params['fabrication_from'] ) && $params['fabrication_from'] != '')
				$where[] = 'fabrication.meta_value >= ' . $params['fabrication_from'];
			if (isset( $params['fabrication_to'] ) && $params['fabrication_to'] != '')
				$where[] = 'fabrication.meta_value <= ' . $params['fabrication_to'];
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' fabrication
						ON fabrication.post_id = posts.ID AND
						fabrication.meta_key = \'_fabrication\'';
		}

		if (isset( $params['transport_type_id'] ) && $params['transport_type_id'] > 0){
			$where[] = 'transport_type.meta_value = \'' . $params['transport_type_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' transport_type
						ON transport_type.post_id = posts.ID AND
						transport_type.meta_key = \'_transport_type_id\'';
		}

		if (isset( $params['body_type_id'] ) && $params['body_type_id'] > 0){
			$where[] = 'body_type.meta_value = \'' . $params['body_type_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' body_type
						ON body_type.post_id = posts.ID AND
						body_type.meta_key = \'_body_type_id\'';
		}

		if (isset( $params['fuel_id'] ) && $params['fuel_id'] > 0){
			$where[] = 'fuel.meta_value = \'' . $params['fuel_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' fuel
						ON fuel.post_id = posts.ID AND
						fuel.meta_key = \'_fuel_id\'';
		}

		if ((isset( $params['engine_from'] ) && $params['engine_from'] > 0 ) || (isset( $params['engine_to'] ) && $params['engine_to'] != '')){
			if (isset( $params['engine_from'] ) && $params['engine_from'] > 0)
				$where[] = 'cilindrics.meta_value >= ' . $params['engine_from'];
			if (isset( $params['engine_to'] ) && $params['engine_to'] > 0)
				$where[] = 'cilindrics.meta_value <= ' . $params['engine_to'];
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' cilindrics
						ON cilindrics.post_id = posts.ID AND
						cilindrics.meta_key = \'_cilindrics\'';
		}

		if (isset( $params['transmission_id'] ) && $params['transmission_id'] > 0){
			$where[] = 'transmission.meta_value = \'' . $params['transmission_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' transmission
						ON transmission.post_id = posts.ID AND
						transmission.meta_key = \'_transmission_id\'';
		}

		if (isset( $params['door_id'] ) && $params['door_id'] > 0){
			$where[] = 'door.meta_value = \'' . $params['door_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' door
						ON door.post_id = posts.ID AND
						door.meta_key = \'_door_id\'';
		}

		if (isset( $params['region_id'] ) && $params['region_id'] > 0){
			$where[] = 'region.meta_value = \'' . $params['region_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' region
						ON region.post_id = posts.ID AND
						region.meta_key = \'_region_id\'';
		}

		if (isset( $params['state_id'] ) && $params['state_id'] > 0){
			$where[] = 'state.meta_value = \'' . $params['state_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' state
						ON state.post_id = posts.ID AND
						state.meta_key = \'_state_id\'';
		}

		if (isset( $params['drive_id'] ) && $params['drive_id'] > 0){
			$where[] = 'drive.meta_value = \'' . $params['drive_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' drive
						ON drive.post_id = posts.ID AND
						drive.meta_key = \'_drive_id\'';
		}

		if (isset( $params['color_id'] ) && $params['color_id'] > 0){
			$where[] = 'color.meta_value = \'' . $params['color_id'] . '\'';
			$join .= ' INNER JOIN ' . $this->wpdb->postmeta .' color
						ON color.post_id = posts.ID AND
						color.meta_key = \'_color_id\'';
		}

		$where = (($where = implode(' AND ', $where)) != '') ? (' AND ' . $where) : '';

		$res = $this->wpdb->get_row('SELECT count(*) AS count
		    FROM ' . $this->wpdb->posts . ' posts
		    ' . $join . '
		    WHERE posts.post_status = \'' . $post_status . '\' AND posts.post_type = \'car\'
			' . $where . ';',  ARRAY_A);
		return $res['count'];
	}


	public function get_best_offers( $dealer_id ){
		$ids = $this->wpdb->get_results('SELECT posts.ID 
		    FROM ' . $this->wpdb->posts . ' posts
		    INNER JOIN ' . $this->wpdb->postmeta .' owner
					ON owner.post_id = posts.ID
					AND owner.meta_key = \'_owner_id\'
					AND owner.meta_value = \'' . $dealer_id . '\'
			INNER JOIN ' . $this->wpdb->postmeta .' best_offer
					ON best_offer.post_id = posts.ID AND
					best_offer.meta_key = \'_best_offer\'
					AND best_offer.meta_value = \'1\'
		    WHERE posts.post_status = \'publish\' AND posts.post_type = \'car\'
		    ORDER BY posts.post_date DESC
		    LIMIT 0,10;',  ARRAY_A);
		$cars = array();
		foreach($ids as $key => $value){
			$cars[] = $this->get_car_info( $value['ID'] );
		}
		return $cars;
	}

	public function get_manufacturers(){
		$manufacturers = $this->wpdb->get_results('SELECT manufacturer.id, manufacturer.name, manufacturer.alias, manufacturer.is_delete, models.is_delete, models.manufacturer_id
		    FROM ' . $this->_manufacturers_table . ' manufacturer, ' . $this->_models_table . ' models
		    WHERE manufacturer.is_delete = 0
		    	AND models.is_delete = 0
		    	AND models.manufacturer_id = manufacturer.id
		    GROUP BY manufacturer.name
		    ;',  ARRAY_A);
		return $manufacturers;
	}

	public function get_similar_car( $car_id ){
		$car_info = $this->_get_car_info( $car_id );
		$ids = $this->wpdb->get_results('SELECT posts.ID 
		    FROM ' . $this->wpdb->posts . ' posts
		    INNER JOIN ' . $this->wpdb->postmeta .' manufacturer
					ON manufacturer.post_id = posts.ID AND
					manufacturer.meta_key = \'_manufacturer_id\'
			INNER JOIN ' . $this->wpdb->postmeta .' model
					ON model.post_id = posts.ID AND
					model.meta_key = \'_model_id\'
		    WHERE posts.post_status = \'publish\' AND posts.post_type = \'car\'
				AND manufacturer.meta_value = \'' . $car_info['options']['_manufacturer_id'] . '\'
				AND model.meta_value = \'' . $car_info['options']['_model_id'] . '\'
				AND posts.ID != \'' . $car_id . '\'
		    ORDER BY posts.post_date DESC
		    LIMIT 0,4;',  ARRAY_A);

		$cars = array();
		foreach($ids as $key => $value){
			$cars[] = $this->get_car_info( $value['ID'] );
		}
		return $cars;
	}


//////////////////////////////////////////////////////////////////////////////
// Car Views Start
//////////////////////////////////////////////////////////////////////////////
	public function get_car_views( $car_id ){
	    $count = get_post_meta( $car_id, '_car_views_count', true );
	    if( $count == '' ){
	        delete_post_meta( $car_id, '_car_views_count' );
	        add_post_meta( $car_id, '_car_views_count', '0' );
	        return 0;
	    }
	    return $count;
	}

	public function set_car_views( $car_id ) {
	    $count = get_post_meta( $car_id, '_car_views_count', true );
	    if( $count == '' ) {
	        $count = 0;
	        delete_post_meta( $car_id, '_car_views_count');
	        add_post_meta( $car_id, '_car_views_count', '0' );
	    } else {
	        $count++;
	        update_post_meta( $car_id, '_car_views_count', $count );
	    }
	}
//////////////////////////////////////////////////////////////////////////////
// Car Views End
//////////////////////////////////////////////////////////////////////////////



/*
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 View";
    }
    return $count.' Views';
}

// function to count views.
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}


// Add it to a column in WP-Admin
add_filter('manage_posts_columns', 'posts_column_views');
add_action('manage_posts_custom_column', 'posts_custom_column_views',5,2);
function posts_column_views($defaults){
    $defaults['post_views'] = __('Views');
    return $defaults;
}
function posts_custom_column_views($column_name, $id){
	if($column_name === 'post_views'){
        echo getPostViews(get_the_ID());
    }
}

*/
}