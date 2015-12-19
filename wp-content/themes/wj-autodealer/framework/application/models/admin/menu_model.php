<?php
if (!defined("AT_DIR")) die('!!!');

class AT_menu_model extends AT_Model{

	public function get_menu( $menu, $active = '' ){
		return array( 'items' => call_user_func( array( $this, '_' . $menu . '_menu' ) ), 'active' => $active );
	}

	private function _reference_menu() {
		return array(
			'manufacturers' => array('name' => __( 'Make', AT_ADMIN_TEXTDOMAIN ), 'url' => 'admin.php?page=' . THEME_PREFIX . 'reference'),
			'models' => array('name' => __( 'Models', AT_ADMIN_TEXTDOMAIN ), 'url' => 'admin.php?page=' . THEME_PREFIX . 'reference&tab=models'),
			'body_types' =>  array('name' => __( 'Body Style', AT_ADMIN_TEXTDOMAIN ), 'url' => 'admin.php?page=' . THEME_PREFIX . 'reference&tab=body_types'),
			'currencies' => array('name' => __( 'Currencies', AT_ADMIN_TEXTDOMAIN ), 'url' => 'admin.php?page=' . THEME_PREFIX . 'reference&tab=currencies'),
			'doors' => array('name' => __( 'Doors', AT_ADMIN_TEXTDOMAIN ), 'url' => 'admin.php?page=' . THEME_PREFIX . 'reference&tab=doors'),
			'equipments' => array('name' => __( 'Equipments', AT_ADMIN_TEXTDOMAIN ), 'url' => 'admin.php?page=' . THEME_PREFIX . 'reference&tab=equipments'),
			'fuels' => array('name' => __( 'Fuel Type', AT_ADMIN_TEXTDOMAIN ), 'url' => 'admin.php?page=' . THEME_PREFIX . 'reference&tab=fuels'),
			'technical_conditions' => array('name' => __( 'Technical Conditions', AT_ADMIN_TEXTDOMAIN ), 'url' => 'admin.php?page=' . THEME_PREFIX . 'reference&tab=technical_conditions'),
			'transmissions' => array('name' => __( 'Transmissions', AT_ADMIN_TEXTDOMAIN ), 'url' => 'admin.php?page=' . THEME_PREFIX . 'reference&tab=transmissions'),
			'transport_types' => array('name' => __( 'Transport Types', AT_ADMIN_TEXTDOMAIN ), 'url' => 'admin.php?page=' . THEME_PREFIX . 'reference&tab=transport_types'),
			'regions' => array('name' => __( 'Countries', AT_ADMIN_TEXTDOMAIN ), 'url' => 'admin.php?page=' . THEME_PREFIX . 'reference&tab=regions'),
			'states' => array('name' => __( 'States', AT_ADMIN_TEXTDOMAIN ), 'url' => 'admin.php?page=' . THEME_PREFIX . 'reference&tab=states'),
			'drive' => array('name' => __( 'Drive', AT_ADMIN_TEXTDOMAIN ), 'url' => 'admin.php?page=' . THEME_PREFIX . 'reference&tab=drive'),
			'colors' => array('name' => __( 'Colors', AT_ADMIN_TEXTDOMAIN ), 'url' => 'admin.php?page=' . THEME_PREFIX . 'reference&tab=colors'),
		);
	}

	private function _users_menu() {
		return array(
			'active' => array('name' => __( 'Active', AT_ADMIN_TEXTDOMAIN ), 'url' => 'admin.php?page=' . THEME_PREFIX . 'users'),
			'trash' => array('name' => __( 'Trash', AT_ADMIN_TEXTDOMAIN ), 'url' => 'admin.php?page=' . THEME_PREFIX . 'users&tab=trash'),
		);	
	}
}