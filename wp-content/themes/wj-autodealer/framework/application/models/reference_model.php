<?php
if (!defined("AT_DIR")) die('!!!');

class AT_reference_model extends AT_Model{

//////////////////////////////////////////////////////////////////////////////
// UPDATE|INSERT reference  Start
//////////////////////////////////////////////////////////////////////////////

	public function update_reference( $table, $item_id, $data ){
		if ( $table == '_equipments_table' ) $where = array( 'alias' => $item_id );
		else $where =  array( 'id' => $item_id );
		return $this->wpdb->update( $this->$table, $data, $where );
	}

	public function insert_reference( $table, $ins, $check = false ){
		// check exists
		$exists = false;
		if( $check ) {
			$res = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->$table . " WHERE alias =  %s LIMIT 1", $ins['alias'] ), ARRAY_A );
			if ( count( $res ) > 0 ) {
				if( $res['is_delete'] ) {
					$ins['is_delete'] = 0;
					if ( $table == '_equipments_table' ) $id = $res['alias'];
					else  $id = $res['id'];
					$this->update_reference( $table, $id, $ins );
					$insert_id = $id;
					$exists = true;
				} else {
					return false;
				}
			}
			//$insert_id = 0;
		}
		if( !$exists ) {
			$this->wpdb->insert( $this->$table, $ins );
			if ( $table == '_equipments_table' ) $insert_id = true;
			else $insert_id = $this->wpdb->insert_id;

      	}
      	return $insert_id;
	}

//////////////////////////////////////////////////////////////////////////////
// UPDATE Reference  End
//////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////
// Manufactures Start
//////////////////////////////////////////////////////////////////////////////
	public function get_manufacturers(){
		return $this->wpdb->get_results("SELECT * FROM " . $this->_manufacturers_table . " WHERE is_delete = 0 ORDER BY name", ARRAY_A );
	}

	public function get_manufacturer_by_id( $manufacturer_id ){
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_manufacturers_table . " WHERE id =  %d LIMIT 1", $manufacturer_id ), ARRAY_A );
	}

	public function get_multiple_manufacturer_by_id( $manufacturers = array() ){
		$manufacturers = implode(',',$manufacturers);
		return $this->wpdb->get_results( "SELECT * FROM " . $this->_manufacturers_table . " WHERE id IN (" . $manufacturers . ")", ARRAY_A );
	}

	public function get_all_manufacturers(){
		return $this->wpdb->get_results( "SELECT * FROM " . $this->_manufacturers_table, ARRAY_A );
	}

	public function get_manufacturer_by_alias( $manufacturer_alias ){
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_manufacturers_table . " WHERE alias =  %s LIMIT 1", $manufacturer_alias ), ARRAY_A );
	}

	public function delete_manufacturer_by_id( $manufacturer_id ){
		$this->wpdb->query('START TRANSACTION');
		try {
			$res = $this->wpdb->update( $this->_manufacturers_table, array( 'is_delete' => 1 ), array( 'id' => $manufacturer_id ) );
			if (!$res) throw new Exception();

			if ($this->get_models_by_manufacturer_id($manufacturer_id)) {
				$res = $this->wpdb->update( $this->_models_table, array( 'is_delete' => 1 ), array( 'manufacturer_id' => $manufacturer_id ) );
				if (!$res) throw new Exception();
			}
			
			$this->wpdb->query('COMMIT');
			return true;
		} catch(Exception $e) {
			$this->wpdb->query('ROLLBACK');
        	return false;
    	}
	}
//////////////////////////////////////////////////////////////////////////////
// Manufactures End
//////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////
// Models Start
//////////////////////////////////////////////////////////////////////////////
	public function get_models_by_manufacturer_id( $manufacturer_id ){
		return $this->wpdb->get_results( $this->wpdb->prepare( "SELECT * FROM " . $this->_models_table . " WHERE manufacturer_id =  %d and is_delete = 0 ORDER BY name", $manufacturer_id ), ARRAY_A );
	}

	public function get_model_by_id( $model_id ){
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_models_table . " WHERE id =  %d LIMIT 1", $model_id ), ARRAY_A );
	}

	public function get_model_by_alias( $model_alias ){
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_models_table . " WHERE alias =  %s LIMIT 1", $model_alias ), ARRAY_A );
	}

	public function delete_model_by_id( $model_id ){
		return $this->wpdb->update( $this->_models_table, array( 'is_delete' => 1 ), array( 'id' => $model_id ) );
	}
//////////////////////////////////////////////////////////////////////////////
// Models End
//////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////
// Body Type Start
//////////////////////////////////////////////////////////////////////////////
	public function get_body_types(){
		return $this->wpdb->get_results("SELECT * FROM " . $this->_body_types_table . " WHERE is_delete = 0 ORDER BY sort, name", ARRAY_A );
	}

	public function get_body_type_by_id( $body_type_id ){
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_body_types_table . " WHERE id =  %d LIMIT 1", $body_type_id ), ARRAY_A );
	}

	public function delete_body_type_by_id( $body_type_id ){
		return $this->wpdb->update( $this->_body_types_table, array( 'is_delete' => 1 ), array( 'id' => $body_type_id ) );
	}
//////////////////////////////////////////////////////////////////////////////
// Body Type End
//////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////
// Transmissions Start
//////////////////////////////////////////////////////////////////////////////
	public function get_transmissions(){
		return $this->wpdb->get_results("SELECT * FROM " . $this->_transmissions_table . " WHERE is_delete = 0 ORDER BY sort, name", ARRAY_A );
	}

	public function get_transmission_by_id( $transmission_id ){
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_transmissions_table . " WHERE id =  %d LIMIT 1", $transmission_id ), ARRAY_A );
	}

	public function delete_transmission_by_id( $transmission_id ){
		return $this->wpdb->update( $this->_transmissions_table, array( 'is_delete' => 1 ), array( 'id' => $transmission_id ) );
	}
//////////////////////////////////////////////////////////////////////////////
// Transmissions End
//////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////
// Equipments Start
//////////////////////////////////////////////////////////////////////////////
	public function get_equipments_alias(){
		$results = $this->wpdb->get_col("SELECT alias FROM " . $this->_equipments_table . " WHERE is_delete = 0 ORDER BY name");
		$aliases = array();
		foreach ($results as $key => $value) {
			$aliases[$value] = true;
		}
		return $aliases;
	}

	public function get_equipments(){
		$results =  $this->wpdb->get_results("SELECT * FROM " . $this->_equipments_table . " WHERE is_delete = 0 ORDER BY name", ARRAY_A );
		$equipments = array();
		foreach ($results as $key => $value) {
			$equipments[$value['alias']] = $value;
		}
		return $equipments;
	}

	public function get_equipment_by_alias( $equipment_alias ){
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_equipments_table . " WHERE alias =  %s LIMIT 1", $equipment_alias ), ARRAY_A );
	}

	public function delete_equipment_by_alias( $equipment_alias ){
		return $this->wpdb->update( $this->_equipments_table, array( 'is_delete' => 1 ), array( 'alias' => $equipment_alias ) );
	}
//////////////////////////////////////////////////////////////////////////////
// Equipments End
//////////////////////////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////////////////////////
// Doors Start
//////////////////////////////////////////////////////////////////////////////
	public function get_doors(){
		return $this->wpdb->get_results("SELECT * FROM " . $this->_doors_table . " WHERE is_delete = 0 ORDER BY name", ARRAY_A );
	}

	public function get_door_by_id( $door_id ){
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_doors_table . " WHERE id =  %d LIMIT 1", $door_id ), ARRAY_A );
	}

	public function delete_door_by_id( $door_id ){
		return $this->wpdb->update( $this->_doors_table, array( 'is_delete' => 1 ), array( 'id' => $door_id ) );
	}
//////////////////////////////////////////////////////////////////////////////
// Doors End
//////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////
// Fuels Start
//////////////////////////////////////////////////////////////////////////////
	public function get_fuels(){
		return $this->wpdb->get_results("SELECT * FROM " . $this->_fuels_table . " WHERE is_delete = 0 ORDER BY sort, name", ARRAY_A );
	}

	public function get_fuel_by_id( $fuel_id ){
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_fuels_table . " WHERE id =  %d LIMIT 1", $fuel_id ), ARRAY_A );
	}

	public function delete_fuel_by_id( $fuel_id ){
		return $this->wpdb->update( $this->_fuels_table, array( 'is_delete' => 1 ), array( 'id' => $fuel_id ) );
	}
//////////////////////////////////////////////////////////////////////////////
// Fuels End
//////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////
// Technical Conditions Start
//////////////////////////////////////////////////////////////////////////////
	public function get_technical_conditions(){
		return $this->wpdb->get_results("SELECT * FROM " . $this->_technical_conditions_table . " WHERE is_delete = 0 ORDER BY sort, name", ARRAY_A );
	}

	public function get_technical_condition_by_id( $condition_id ){
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_technical_conditions_table . " WHERE id =  %d LIMIT 1", $condition_id ), ARRAY_A );
	}

	public function delete_technical_condition_by_id( $condition_id ){
		return $this->wpdb->update( $this->_technical_conditions_table, array( 'is_delete' => 1 ), array( 'id' => $condition_id ) );
	}
//////////////////////////////////////////////////////////////////////////////
// Technical Conditions End
//////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////
// Currencies Start
//////////////////////////////////////////////////////////////////////////////
	public function get_currencies(){
		return $this->wpdb->get_results("SELECT * FROM " . $this->_currencies_table . " WHERE is_delete = 0 ORDER BY sort, name", ARRAY_A );
	}

	public function get_currency_by_id( $currency_id ){
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_currencies_table . " WHERE id =  %d LIMIT 1", $currency_id ), ARRAY_A );
	}

	public function delete_currency_by_id( $currency_id ){
		return $this->wpdb->update( $this->_currencies_table, array( 'is_delete' => 1 ), array( 'id' => $currency_id ) );
	}
//////////////////////////////////////////////////////////////////////////////
// Currencies End
//////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////
// Transport Types Start
//////////////////////////////////////////////////////////////////////////////
	public function get_transport_types(){
		return $this->wpdb->get_results("SELECT * FROM " . $this->_transport_types_table . " WHERE is_delete = 0 ORDER BY sort, name", ARRAY_A );	
	}

	public function get_transport_type_by_alias( $transport_type_alias ){
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_transport_types_table . " WHERE alias =  %s LIMIT 1", $transport_type_alias ), ARRAY_A );
	}

	public function get_transport_type_by_id( $transport_type_id ){
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_transport_types_table . " WHERE id =  %d LIMIT 1", $transport_type_id ), ARRAY_A );
	}

	public function get_manufacturer_by_transport_type_by_id( $transport_type_id ){
				// car.id, car.post_type,
				// " . $this->wpdb->posts . " as car,
		if ( $transport_type_id == 0 ) {
			return $this->get_all_manufacturers();
		}

		$row = $this->wpdb->get_results( $this->wpdb->prepare( "
			SELECT
				meta.meta_key, meta.meta_value, meta.post_id
			FROM
				" . $this->wpdb->postmeta . " meta
			WHERE
				meta.meta_key = '_transport_type_id' AND
				meta.meta_value = %d
			", $transport_type_id ), ARRAY_A );
		$cars = array();
		foreach( $row as $manufacturer ) {
			$cars[] = $manufacturer['post_id'];
		}

		if( empty($cars) ) return '';

		//$meta = $this->wpdb->get_results( $this->wpdb->prepare( "SELECT meta_key, meta_value, post_id FROM " . $this->wpdb->postmeta . " WHERE post_id IN('%d') AND meta_key='_manufacturer_id'", implode(',',$cars) ), ARRAY_A );
		$meta = $this->wpdb->get_results( "SELECT meta_key, meta_value, post_id FROM " . $this->wpdb->postmeta . " WHERE post_id IN(" . implode(',',$cars) . ") AND meta_key='_manufacturer_id'", ARRAY_A );
		$manufacturers = array();
		foreach( $meta as $mid ) {
			$manufacturers[] = $mid['meta_value'];
		}

		return $this->get_multiple_manufacturer_by_id($manufacturers);

	}


	public function delete_transport_type_by_id( $transport_type_id ){
		return $this->wpdb->update( $this->_transport_types_table, array( 'is_delete' => 1 ), array( 'id' => $transport_type_id ) );
	}
//////////////////////////////////////////////////////////////////////////////
// Transport Types End
//////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////
// Regions Start
//////////////////////////////////////////////////////////////////////////////
	public function get_regions(){
		return $this->wpdb->get_results("SELECT * FROM " . $this->_regions_table . " WHERE is_delete = 0 ORDER BY sort, name", ARRAY_A );
	}

	public function get_region_by_id( $region_id ){
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_regions_table . " WHERE id =  %d LIMIT 1", $region_id ), ARRAY_A );
	}

	public function delete_region_by_id( $region_id ){
		return $this->wpdb->update( $this->_regions_table, array( 'is_delete' => 1 ), array( 'id' => $region_id ) );
	}
//////////////////////////////////////////////////////////////////////////////
// Regions End
//////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////
// State/County
//////////////////////////////////////////////////////////////////////////////
	public function get_states_by_region_id( $region_id ){
		return $this->wpdb->get_results( $this->wpdb->prepare( "SELECT * FROM " . $this->_states_table . " WHERE region_id =  %d and is_delete = 0 ORDER BY name", $region_id ), ARRAY_A );
	}

	public function get_state_by_id( $state_id ){
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_states_table . " WHERE id =  %d LIMIT 1", $state_id ), ARRAY_A );
	}

	// public function get_state_by_alias( $state_alias ){
	// 	return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_states_table . " WHERE alias =  %s LIMIT 1", $state_alias ), ARRAY_A );
	// }

	public function delete_state_by_id( $state_id ){
		return $this->wpdb->update( $this->_states_table, array( 'is_delete' => 1 ), array( 'id' => $state_id ) );
	}
//////////////////////////////////////////////////////////////////////////////
// State/County End
//////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////
// Drive Start
//////////////////////////////////////////////////////////////////////////////
	public function get_drive(){
		return $this->wpdb->get_results("SELECT * FROM " . $this->_drive_table . " WHERE is_delete = 0 ORDER BY sort, name", ARRAY_A );
	}

	public function get_drive_by_id( $drive_id ){
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_drive_table . " WHERE id =  %d LIMIT 1", $drive_id ), ARRAY_A );
	}

	public function delete_drive_by_id( $drive_id ){
		return $this->wpdb->update( $this->_drive_table, array( 'is_delete' => 1 ), array( 'id' => $drive_id ) );
	}
//////////////////////////////////////////////////////////////////////////////
// Drive End
//////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////
// Colors Start
//////////////////////////////////////////////////////////////////////////////
	public function get_colors(){
		return $this->wpdb->get_results("SELECT * FROM " . $this->_colors_table . " WHERE is_delete = 0 ORDER BY sort, name", ARRAY_A );
	}

	public function get_color_by_id( $color_id ){
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_colors_table . " WHERE id =  %d LIMIT 1", $color_id ), ARRAY_A );
	}

	public function delete_color_by_id( $color_id ){
		return $this->wpdb->update( $this->_colors_table, array( 'is_delete' => 1 ), array( 'id' => $color_id ) );
	}
//////////////////////////////////////////////////////////////////////////////
// Colors End
//////////////////////////////////////////////////////////////////////////////

}