<?php
if (!defined("AT_DIR")) die('!!!');

class AT_VC_Helper {

	static public function get_manufacturers(){
		$reference_model = AT_Loader::get_instance()->model('reference_model');
		$return = array( 'Any' => 0 );
		foreach( $reference_model->get_manufacturers() as $manufacturer ){
			$return[$manufacturer['name']] = $manufacturer['id'];
		}
		return $return;
	}

	static public function get_years_range(){
		return range(date("Y"),1912);
	}
	static public function get_price_range(){
		return range(500,500000,1000);
	}
	static public function get_mileage_range(){
		return range(10000,2000000,10000);
	}

}