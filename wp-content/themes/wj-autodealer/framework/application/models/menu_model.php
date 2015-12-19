<?php
if (!defined("AT_DIR")) die('!!!');

class AT_menu_model extends AT_Model{

	public function get_menu( $menu, $active = '' ){
		return array( 'items' => call_user_func( array( $this, '_' . $menu . '_menu' ) ), 'active' => $active );
	}

	private function _main_menu() {
		$menu = array(
			'vehicles' => array( 'name' => __( 'My cars', AT_TEXTDOMAIN ), 'url' => '/profile/vehicles/' ),
			'vehicles_archive' => array( 'name' => __( 'Archive cars', AT_TEXTDOMAIN ), 'url' => '/profile/vehicles/archive/' ),
			//'important_information' => array( 'name' => __( 'Important information', AT_TEXTDOMAIN ), 'url' => '/profile/' ),
			'settings' => array( 'name' => __( 'Profile', AT_TEXTDOMAIN ), 'url' => '/profile/settings/' ),
			'dealer_affiliates' => array( 'name' => __( 'Contact affiliates', AT_TEXTDOMAIN ), 'url' => '/profile/settings/dealer_affiliates/' ),
			
		);

		if ( $this->core->get_option( 'merchant_status', false) === true ) {
			$menu['transactions'] = array( 'name' => __( 'Transactions', AT_TEXTDOMAIN ), 'url' => '/profile/settings/transactions/' );
		}

		$user_info = $this->registry->get( 'user_info' );

		if ( !$user_info['is_dealer'] ) unset( $menu['dealer_affiliates'] );
		return $menu;
	}
}