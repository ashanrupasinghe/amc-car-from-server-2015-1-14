<?php
if (!defined("AT_DIR")) die('!!!');

class AT_update_model extends AT_Model{

	public function __construct(){
		parent::__construct();
	}

	public function is_updated() {
		$current_theme_version = $this->core->get_option( 'current_theme_version', '1.0' );
		return ( THEME_VERSION == $current_theme_version );
	}

	public function update() {
		$current_theme_version = $this->core->get_option( 'current_theme_version', '1.0' );

		$update_version = array( '1.1', '1.9' );
		foreach ($update_version as $key => $value) {
			if( $this->_check( $current_theme_version, $value ) ){
				$this->_changesets( $value );
			}
		}
		$this->core->set_option( 'current_theme_version', THEME_VERSION );
		$this->core->save_option();
		add_action('admin_notices', array('AT_Notices','theme_updated_notice'));
	}

	private function _check( $value1, $value2 ){
		$value1 = explode('.', $value1);
		$value2 = explode('.', $value2);
		if( $value1[0] < $value2[0]){
			return true;
		} else if( $value1[0] == $value2[0]){
			if( $value1[1] < $value2[1]){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	private function _changesets( $version = '' ){
		$this->wpdb->query('START TRANSACTION');
		switch ( $version ) {
			case '1.9':
				$sql = '';
				$this->wpdb->query('ALTER TABLE ' . $this->_photos_table . ' ADD `sort` SMALLINT NOT NULL DEFAULT \'0\', ADD INDEX ( `sort` ) ;');
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );
				break;
			case '1.6':
				$sql = '';

				$this->wpdb->query('ALTER TABLE ' . $this->_regions_table . ' ADD `alias` VARCHAR( 2 ) NOT NULL AFTER `id`, ADD INDEX ( `alias` );');

				if( $this->wpdb->query("SHOW TABLES LIKE '" . $this->_states_table . "'") == 0 ) {
					$sql .= "CREATE TABLE IF NOT EXISTS " . $this->_states_table . " (
							`id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
							`region_id` smallint(3) unsigned NOT NULL,
							`name` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
							`type` smallint(3) unsigned NOT NULL,
							`is_delete` tinyint(1) NOT NULL DEFAULT '0',
							PRIMARY KEY (`id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
					";
				}
				if( $this->wpdb->query("SHOW TABLES LIKE '" . $this->_cities_table . "'") == 0 ) {
					$sql .= "
						CREATE TABLE IF NOT EXISTS " . $this->_cities_table . " (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `cid` int(11) NOT NULL,
						  `sid` int(11) NOT NULL,
						  `name` varchar(100) NOT NULL,
						  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
						  `sort` smallint(6) NOT NULL DEFAULT '0',
						  PRIMARY KEY (`id`),
						  KEY `sort` (`sort`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
					";
				}
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );
				break;
			case '1.5':
				global $wp_rewrite;
				$wp_rewrite->flush_rules();

				if(!$this->create_upload_dir()){
					die( 'Error: chmod(): Operation not permitted in:' . AT_UPLOAD_DIR_THEME . '. Please set upload directory permissions to writable.' );
				}
				$this->wpdb->query('ALTER TABLE ' . $this->_transport_types_table . ' ADD `sort` SMALLINT NOT NULL DEFAULT \'0\', ADD INDEX ( `sort` ) ;');
				$this->wpdb->query('ALTER TABLE ' . $this->_body_types_table . ' ADD `sort` SMALLINT NOT NULL DEFAULT \'0\', ADD INDEX ( `sort` ) ;');
				$this->wpdb->query('ALTER TABLE ' . $this->_colors_table . ' ADD `sort` SMALLINT NOT NULL DEFAULT \'0\', ADD INDEX ( `sort` ) ;');
				$this->wpdb->query('ALTER TABLE ' . $this->_currencies_table . ' ADD `sort` SMALLINT NOT NULL DEFAULT \'0\', ADD INDEX ( `sort` ) ;');
				$this->wpdb->query('ALTER TABLE ' . $this->_drive_table . ' ADD `sort` SMALLINT NOT NULL DEFAULT \'0\', ADD INDEX ( `sort` ) ;');
				$this->wpdb->query('ALTER TABLE ' . $this->_fuels_table . ' ADD `sort` SMALLINT NOT NULL DEFAULT \'0\', ADD INDEX ( `sort` ) ;');
				$this->wpdb->query('ALTER TABLE ' . $this->_regions_table . ' ADD `sort` SMALLINT NOT NULL DEFAULT \'0\', ADD INDEX ( `sort` ) ;');
				$this->wpdb->query('ALTER TABLE ' . $this->_technical_conditions_table . ' ADD `sort` SMALLINT NOT NULL DEFAULT \'0\', ADD INDEX ( `sort` ) ;');
				$this->wpdb->query('ALTER TABLE ' . $this->_transmissions_table . ' ADD `sort` SMALLINT NOT NULL DEFAULT \'0\', ADD INDEX ( `sort` ) ;');


				// $this->wpdb->query('ALTER TABLE ' . $this->_transactions_table . ' CHANGE `ack` varchar(50) NULL, CHANGE `token` varchar(128), ADD `payerid` varchar(32) NULL;');
				// $this->wpdb->query('ALTER TABLE ' . $this->_transactions_table . ' ADD `entity_id` int(11) NOT NULL,ADD `entity` varchar(50) NOT NULL;');

				$this->core->set_option( 'car_transport_types', array( 'is_view_all' => 1, 'icon' => 'filter-icon-all', 'default' => 0) );
				$this->core->set_option( 'shortcode_search_forms', array(array( 'option' => array( 'title' => '', 'transport_type' => '', 'manufacturer_model' => '', 'year' => '', 'price' => '', 'mileage' => '', 'only_new_car' => '',  'submit' => ''))) );
				$this->core->save_option();


				if( $this->wpdb->query("SHOW TABLES LIKE '" . $this->_transactions_table . "'") == 0 ) {
					$sql = "CREATE TABLE IF NOT EXISTS " . $this->_transactions_table . " (
							  `id` int(11) NOT NULL AUTO_INCREMENT,
							  `uid` int(11) NOT NULL,
							  `tid` varchar(128) NOT NULL,
							  `sid` tinyint(1) DEFAULT '0',
							  `amount` varchar(50) NOT NULL,
							  `ack` varchar(50) NOT NULL,
							  `msg` varchar(255) NOT NULL,
							  `token` varchar(128) NOT NULL,
							  `payerid` varchar(32) NOT NULL,
							  `entity` varchar(50) NOT NULL,
							  `entity_id` int(11) NOT NULL,
							  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
							  `completed_at` datetime DEFAULT NULL,
							  `timestamp` datetime DEFAULT NULL,
							  PRIMARY KEY (`id`)
							) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
					";
							  // UNIQUE KEY `token` (`token`)
					require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
					dbDelta( $sql );
				}
				break;
			case '1.4':
			case '1.3':
			case '1.2':
				global $wp_rewrite;
				$wp_rewrite->flush_rules();
				break;
			case '1.1':
				$this->wpdb->query('ALTER TABLE ' . $this->_users_table . ' ADD `date_active` DATETIME NULL AFTER `date_create` ;');
				$this->wpdb->query('ALTER TABLE ' . $this->_users_table . ' ADD `alias` VARCHAR( 50 ) NOT NULL AFTER `is_dealer`, ADD INDEX ( `alias` );');
				break;
			default:
				break;
		}
		$this->wpdb->query('COMMIT');
    	return true;
	}


// UPDATE METHODS

	public function create_upload_dir(){
		if (!file_exists(AT_UPLOAD_DIR_THEME)) {
			mkdir(AT_UPLOAD_DIR_THEME);
		}
		if (!@chmod(AT_UPLOAD_DIR_THEME, 0777)) {
			return false;
		}
		if ( !file_exists( AT_UPLOAD_DIR_THEME . '/index.html' ) ) {
			if ( file_put_contents( AT_UPLOAD_DIR_THEME . '/index.html', '' ) === false ) {
				return false;
			}
		}
		return true;
	}
}