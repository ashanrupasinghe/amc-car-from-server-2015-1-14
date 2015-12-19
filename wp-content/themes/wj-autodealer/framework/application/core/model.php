<?php
if (!defined("AT_DIR")) die('!!!');
abstract class AT_Model {

	public $wpdb = null;
	public $load = null;
	public $core = null;
	public $registry = null;

	public $_users_table = 'wj_users';
	public $_dealers_affiliates_table = 'wj_dealers_affiliates';
	public $_manufacturers_table = 'wj_manufacturers';
	public $_models_table = 'wj_models';
	public $_body_types_table = 'wj_body_types';
	public $_transmissions_table = 'wj_transmissions';
	public $_equipments_table = 'wj_equipments';
	public $_doors_table = 'wj_doors';
	public $_fuels_table = 'wj_fuels';
	public $_technical_conditions_table = 'wj_technical_conditions';
	public $_currencies_table = 'wj_currencies';
	public $_transport_types_table = 'wj_transport_types';
	public $_regions_table = 'wj_regions';
	public $_states_table = 'wj_states';
	public $_cities_table = 'wj_cities';
	public $_region_types_table = 'wj_region_types';
	public $_drive_table = 'wj_drive';
	public $_colors_table = 'wj_colors';
	public $_photos_table = 'wj_photos';
	public $_recovery_password_table = 'wj_recovery_password';
	public $_transactions_table = 'wj_transactions';
 
	public function __construct() {
		global $wpdb;
		$this->wpdb = &$wpdb;
		$this->_users_table = $wpdb->prefix . $this->_users_table;
		$this->_dealers_affiliates_table = $wpdb->prefix . $this->_dealers_affiliates_table;
		$this->_manufacturers_table = $wpdb->prefix . $this->_manufacturers_table;
		$this->_models_table = $wpdb->prefix . $this->_models_table;
		$this->_body_types_table = $wpdb->prefix . $this->_body_types_table;
		$this->_transmissions_table = $wpdb->prefix . $this->_transmissions_table;
		$this->_equipments_table = $wpdb->prefix . $this->_equipments_table;
		$this->_doors_table = $wpdb->prefix . $this->_doors_table;
		$this->_fuels_table = $wpdb->prefix . $this->_fuels_table;
		$this->_technical_conditions_table = $wpdb->prefix . $this->_technical_conditions_table;
		$this->_currencies_table = $wpdb->prefix . $this->_currencies_table;
		$this->_transport_types_table = $wpdb->prefix . $this->_transport_types_table;
		$this->_regions_table = $wpdb->prefix . $this->_regions_table;
		$this->_cities_table = $wpdb->prefix . $this->_cities_table;
		$this->_region_types_table = $wpdb->prefix . $this->_region_types_table;
		$this->_states_table = $wpdb->prefix . $this->_states_table;
		$this->_drive_table = $wpdb->prefix . $this->_drive_table;
		$this->_colors_table = $wpdb->prefix . $this->_colors_table;
		$this->_photos_table = $wpdb->prefix . $this->_photos_table;
		$this->_recovery_password_table = $wpdb->prefix . $this->_recovery_password_table;
		$this->_transactions_table = $wpdb->prefix . $this->_transactions_table;
		$this->load = AT_Loader::get_instance();
		$this->core = AT_Core::get_instance();
		$this->registry = AT_Registry::get_instance();
	}

	public function get_data_for_options( $method, $option = null ){
		$return = array();
		if ( $method == 'get_all_users' ) {
			foreach ($this->$method($option) as $key => $value) {
				$return[$value['id']] = $value['name'] . ' : ' . $value['email'];
			}
		} else {
			foreach ($this->$method($option) as $key => $value) {
				$return[$value['id']] = $value['name'];
			}
		}
		return $return;
	}
}
