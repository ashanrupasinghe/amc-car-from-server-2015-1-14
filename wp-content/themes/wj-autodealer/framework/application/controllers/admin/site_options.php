<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Site_options extends AT_Admin_Controller{
	
	protected $_block_id = 0;
	protected $_get_params = false;
	
	public function __construct() {
		parent::__construct();

		// $this->core->set_option( 'theme_is_activated', false );
		// $this->core->save_option();

		$fields = call_user_func( array( $this, $this->uri->segments(2) ) );
		$fields = $this->_parse_values( $fields );
		if( $this->uri->is_ajax_request() && !empty( $_POST ) ) {
			try {
				if ( empty( $_POST[THEME_PREFIX . 'options'] ) ) {
					//throw new Exception( 'Save Failed!' );
					$_POST[THEME_PREFIX . 'options'] = array();
				}
				$this->_save_fields( $fields, $_POST[THEME_PREFIX . 'options'] );
                $response = array( 'status' => 'OK', 'message' => __( 'Options Saved', AT_ADMIN_TEXTDOMAIN ));

			} catch(Exception $e) {
            	$response =  array( 'status' => 'ERROR',  'message' => $e->getMessage());
        	}
		 	$this->view->add_json($response)->display();
			exit;
		}

		$this->view->use_layout('admin');
		$this->_parse_fields( $fields );
	}

	public function general(){
		$fields = array(
			'site_type' =>
				array(
					'type' => 'radio_image',
					'title' => __( 'Site type', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'You may specify your site type - sole trader, or multiple dealers (partnership mode). In sole trader mode you may only add your own cars, but in partnership mode you may add dealers and they will have permission to add their own vehicles, and office locations, btw in this mode you may also add your products from your frontend account.', AT_ADMIN_TEXTDOMAIN ),
					'width' => '200px',
					'items' => 
						array(
							'mode_soletrader' => AT_URI . '/assets/images/admin/site_type/mode_soletrader.png',
							'mode_partnership' => AT_URI . '/assets/images/admin/site_type/mode_partnership.png',
							'mode_board' => AT_URI . '/assets/images/admin/site_type/mode_board.png',
						),
					'default' => 'mode_soletrader'
				),
		);	
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/site_options/content', array( 'title' => __( 'General Site Options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'general' ));
		}
		return $fields;
	}
	public function labels(){
		$fields = array(
			'pname_singular' =>
				array(
					'type' => 'input_text',
					'title' => __( 'Singular product name:', AT_ADMIN_TEXTDOMAIN ),
					'description' => 'Specify primary product name.',
					'default' => 'Car'
				),
			'pname_plural' => 
				array(
					'type' => 'input_text',
					'title' => __( 'Plural product name:', AT_ADMIN_TEXTDOMAIN ),
					'description' => 'Specify primary product name for plural form.',
					'default' => 'Cars'
				),
			'label_add_car' =>
				array(
					'type' => 'input_text',
					'title' => __( 'Add item label', AT_ADMIN_TEXTDOMAIN ),
					'description' => 'Specify caption for add item section.',
					'default' => 'Add Car'
				),
			'label_my_items' =>
				array(
					'type' => 'input_text',
					'title' => __( 'My items label', AT_ADMIN_TEXTDOMAIN ),
					'description' => 'Specify caption for my item section.',
					'default' => 'My Vehicles'
				),
		);	
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/site_options/content', array( 'title' => __( 'General Site Options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'general' ));
		}
		return $fields;
	}
	public function maintenance(){
		$fields = array(
			'status_site' => 
				array(
					'type' => 'radio',
					'title' => __( 'Site status:', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'You may change site status from production to underconstruction.', AT_ADMIN_TEXTDOMAIN ),
					'items' => 
						array(
							'production' => __( 'Production', AT_ADMIN_TEXTDOMAIN ),
							'underconstruction' => __( 'Underconstruction', AT_ADMIN_TEXTDOMAIN ),
						),
					'default' => 'production'
				),
			'underconstruction_text' =>
				array(
					'type' => 'input_text',
					'title' => __( 'Underconstruction text:', AT_ADMIN_TEXTDOMAIN ),
					'description' => '',
					'default' => 'We’ll be here soon with a new website. Estimated time remaining:'
				),
			'datetime_retry_after' => 
				array(
					'type' => 'date',
					'title' => __( 'Date & Time Retry After (SEO):', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Underconstruction: search engine date and time retry after rule.', AT_ADMIN_TEXTDOMAIN ),
					'format' => 'd.m.Y H:i',
					'min_date' => date('d.m.Y'),
					'default' => ''
				),
			'sender_email' => 
				array(
					'type' => 'input_text',
					'title' => __( 'Sender email:', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Used to handle sender-from email.', AT_ADMIN_TEXTDOMAIN ),
					'default' => ''
				),
			'sender_name' => 
				array(
					'type' => 'input_text',
					'title' => __( 'Sender name:', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Used to handle sender-from name.', AT_ADMIN_TEXTDOMAIN ),
					'default' => ''
				),
		);	
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/site_options/content', array( 'title' => __( 'Maintenance Options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'maintenance' ));
		}
		return $fields;
	}

	public function catalog(){
		$fields = array(
			'catalog_layout' =>
				array(
					'type' => 'radio_image',
					'title' => __( 'Catalog layout:', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'You may specify custom catalog layout.', AT_ADMIN_TEXTDOMAIN ),
					'items' => 
						array(
							'left_content' => AT_URI . '/assets/images/admin/layouts/page/2.png',
							'content_right' => AT_URI . '/assets/images/admin/layouts/page/3.png',
						),
					'default' => 'left_content'
				),
			'catalog_car_type_view_default' =>
				array(
					'type' => 'radio',
					'title' => __( 'Catalog car view:', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Specify defaults for catalog view.', AT_ADMIN_TEXTDOMAIN ),
					'items' => 
						array(
							'list' => __( 'List', AT_ADMIN_TEXTDOMAIN ),
							'grid' => __( 'Grid', AT_ADMIN_TEXTDOMAIN ),
						),
					'default' => 'list'
				),
			'catalog_filters_disable' => 
				array(
					'type' => 'checkbox',
					'title' => __( 'Disabled catalog filters:', AT_ADMIN_TEXTDOMAIN ),
					'description' => '',
					'default' => false
				),
			'catalog_car_categories_disable' => 
				array(
					'type' => 'checkbox',
					'title' => __( 'Disable catalog categories:', AT_ADMIN_TEXTDOMAIN ),
					'description' => '',
					'default' => false
				),
			'catalog_sorted_fields' =>
				array(
					'type' => 'group',
					'title' => __( 'Order Fields:', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Specify order by fields', AT_ADMIN_TEXTDOMAIN ),
					'submit' => __( "Add Item", AT_ADMIN_TEXTDOMAIN ),
					'default' => array(
						array( 'name' => __( 'Publish date', AT_ADMIN_TEXTDOMAIN ), 'field' => 'date', 'direction' => 'DESC' ),
						array( 'name' => __( 'Price ASC', AT_ADMIN_TEXTDOMAIN ), 'field' => 'price', 'direction' => 'asc' ),
						array( 'name' => __( 'Price DESC', AT_ADMIN_TEXTDOMAIN ), 'field' => 'price', 'direction' => 'desc' ),
						array( 'name' => __( 'Name ASC', AT_ADMIN_TEXTDOMAIN ), 'field' => 'name', 'direction' => 'asc' ),
						array( 'name' => __( 'Name DESC', AT_ADMIN_TEXTDOMAIN ), 'field' => 'name', 'direction' => 'desc' ),
					),
					'fields' => array(
						'name' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Title field:', AT_ADMIN_TEXTDOMAIN ),
								'description' => '',
								'default' => ''
							),
						'field' =>
							array(
								'type' => 'radio',
								'title' => __( 'Choose field:', AT_ADMIN_TEXTDOMAIN ),
								'description' => '',
								'items' => 
									array(
										'date' => __( 'Date', AT_ADMIN_TEXTDOMAIN ),
										'price' => __( 'Price', AT_ADMIN_TEXTDOMAIN ),
										'name' => __( 'Name', AT_ADMIN_TEXTDOMAIN ),
										'manufacturer' => __( 'Make', AT_ADMIN_TEXTDOMAIN ),
									),
								'default' => 'date'
							),
						'direction' =>
							array(
								'type' => 'radio',
								'title' => __( 'Choose Field:', AT_ADMIN_TEXTDOMAIN ),
								'description' => '',
								'items' => 
									array(
										'asc' => __( 'ASC', AT_ADMIN_TEXTDOMAIN ),
										'desc' => __( 'DESC', AT_ADMIN_TEXTDOMAIN ),
									),
								'default' => 'desc'
							),

					)
				),
			'catalog_per_pages' =>
				array(
					'type' => 'group',
					'title' => __( 'Pagination limits:', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Specify pagination limits for page', AT_ADMIN_TEXTDOMAIN ),
					'submit' => __( "Add Item", AT_ADMIN_TEXTDOMAIN ),
					'default' => array(
						array( 'pages' => 12 ),
						array( 'pages' => 24 ),
						array( 'pages' => 48 ),
						array( 'pages' => 96 ),
					),
					'fields' => array(
						'pages' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Number:', AT_ADMIN_TEXTDOMAIN ),
								'description' => '',
								'default' => ''
							),
					)
				),
			'catalog_top_pagination_disable' => 
				array(
					'type' => 'checkbox',
					'title' => __( 'Disable top toolbar pagination:', AT_ADMIN_TEXTDOMAIN ),
					'description' => '',
					'default' => false
				),
			'catalog_bottom_pagination_disable' => 
				array(
					'type' => 'checkbox',
					'title' => __( 'Disable bottom pagination:', AT_ADMIN_TEXTDOMAIN ),
					'description' => '',
					'default' => false
				),
			'catalog_search_form' =>
				array(
					'type' => 'catalog_search_form',
					'title' => __( 'Search Form:', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'You may adjust the display catalog form.', AT_ADMIN_TEXTDOMAIN ),
					'default' => array(),
					'sets' => 
						array(
							'title' => array('title' => __( 'Title Form', AT_ADMIN_TEXTDOMAIN ) ),
							'transport_type' => array('title' => __( 'Transport type', AT_ADMIN_TEXTDOMAIN ) ),
							'manufacturer_model' => array('title' => __( 'Make & Model', AT_ADMIN_TEXTDOMAIN ) ),
							'body_type' => array('title' => __( 'Type Body', AT_ADMIN_TEXTDOMAIN ) ),
							'fuel' => array('title' => __( 'Fuel', AT_ADMIN_TEXTDOMAIN ) ),
							'cilindrics' => array('title' => __( 'Engine', AT_ADMIN_TEXTDOMAIN ) ),
							'transmission' => array('title' => __( 'Transmision', AT_ADMIN_TEXTDOMAIN ) ),
							'mileage' => array('title' => __( 'Mileage, ', AT_ADMIN_TEXTDOMAIN ) . AT_Common::car_mileage() ),
							'doors' => array('title' => __( 'Doors', AT_ADMIN_TEXTDOMAIN ) ),
							'price' => array('title' => __( 'Price, euro', AT_ADMIN_TEXTDOMAIN ) ),
							'year' => array('title' => __( 'Year', AT_ADMIN_TEXTDOMAIN ) ),
							'region' => array('title' => __( 'Region', AT_ADMIN_TEXTDOMAIN ) ),
							'region_state' => array('title' => __( 'Country & State', AT_ADMIN_TEXTDOMAIN ) ),
							'drive' => array('title' => __( 'Drive', AT_ADMIN_TEXTDOMAIN ) ),
							'color' => array('title' => __( 'Color', AT_ADMIN_TEXTDOMAIN ) ),
							//'only_new_car' => array('title' => __( 'Only new cars', AT_ADMIN_TEXTDOMAIN ) ),
							'submit' => array('title' => __( 'Submit', AT_ADMIN_TEXTDOMAIN ) ),
						)
				),
		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/site_options/content', array( 'title' => __( 'Catalog Site Options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'catalog' ));
		}
		return $fields;
	}

	public function filter(){
		$fields = array(
				array(
					'type' => 'info',
					'title' => __( 'Search Filters', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'This option will help you create custom pattern for your search-filters. You may continue using your Short Code search filter but you will see additional option which allow you specify custom filter options. This options is available since Auto Dealer 1.5 release.', AT_ADMIN_TEXTDOMAIN ),
				),
			'shortcode_search_forms' =>
				array(
					'type' => 'group',
					'title' => __( 'Shortcode Search Forms Options', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'This options will allow you customize search filter query.', AT_ADMIN_TEXTDOMAIN ),
					'submit' => __( "Add Shortcode", AT_ADMIN_TEXTDOMAIN ),
					'default' => array(
						
					),
					'fields' => array(
						'option' =>
							array(
								'type' => 'catalog_search_form',
								'title' => __( 'Shortcode Search Form:', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'You may adjust the display search form.', AT_ADMIN_TEXTDOMAIN ),
								'view_title' => false,
								'default' => array(),
								'sets' => 
									array(
										'title' => array('title' => __( 'Title Form', AT_ADMIN_TEXTDOMAIN ) ),
										'transport_type' => array('title' => __( 'Transport type', AT_ADMIN_TEXTDOMAIN ) ),
										'manufacturer_model' => array('title' => __( 'Make & Model', AT_ADMIN_TEXTDOMAIN ) ),
										'body_type' => array('title' => __( 'Type Body', AT_ADMIN_TEXTDOMAIN ) ),
										'fuel' => array('title' => __( 'Fuel', AT_ADMIN_TEXTDOMAIN ) ),
										'cilindrics' => array('title' => __( 'Engine', AT_ADMIN_TEXTDOMAIN ) ),
										'transmission' => array('title' => __( 'Transmision', AT_ADMIN_TEXTDOMAIN ) ),
										'mileage' => array('title' => __( 'Mileage, ', AT_ADMIN_TEXTDOMAIN ) . AT_Common::car_mileage() ),
										'doors' => array('title' => __( 'Doors', AT_ADMIN_TEXTDOMAIN ) ),
										'price' => array('title' => __( 'Price, euro', AT_ADMIN_TEXTDOMAIN ) ),
										'year' => array('title' => __( 'Year', AT_ADMIN_TEXTDOMAIN ) ),
										'region' => array('title' => __( 'Region', AT_ADMIN_TEXTDOMAIN ) ),
										'region_state' => array('title' => __( 'Country & State', AT_ADMIN_TEXTDOMAIN ) ),
										'drive' => array('title' => __( 'Drive', AT_ADMIN_TEXTDOMAIN ) ),
										'color' => array('title' => __( 'Color', AT_ADMIN_TEXTDOMAIN ) ),
										'only_new_car' => array('title' => __( 'Only new cars', AT_ADMIN_TEXTDOMAIN ) ),
										'submit' => array('title' => __( 'Submit', AT_ADMIN_TEXTDOMAIN ) ),
									)
							),
					)
				),
		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/site_options/content', array( 'title' => __( 'Search Filters', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'filter' ));
		}
		return $fields;
	}

	public function car(){
		
		$reference_model = $this->load->model( 'reference_model' );
		$transport_types['0'] = 'all';
		foreach ($reference_model->get_data_for_options( 'get_transport_types' ) as $key => $value) {
			$transport_types[$key] = $value;
		}

		$fields = array(
			'car_limit_publish' =>
				array(
					'type' => 'range',
					'title' => __( 'Vehicles limit for users', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( '0 - unlimit.', AT_ADMIN_TEXTDOMAIN ),
					'min' => 0,
					'max' => 200,
					'step' => 1,
					'unit' => 'cars',
					'default' => 10
				),
			'car_limit_publish_dealer' =>
				array(
					'type' => 'range',
					'title' => __( 'Vehicles limit for dealers', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( '0 - unlimit.', AT_ADMIN_TEXTDOMAIN ),
					'min' => 0,
					'max' => 200,
					'step' => 1,
					'unit' => 'cars',
					'default' => 50
				),
			'car_limit_photos' =>
				array(
					'type' => 'range',
					'title' => __( 'Image upload limit for users', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Please specify max upload files, 0 - unlimit.', AT_ADMIN_TEXTDOMAIN ),
					'min' => 0,
					'max' => 50,
					'step' => 1,
					'unit' => 'photos',
					'default' => 6
				),
			'car_limit_photos_dealer' =>
				array(
					'type' => 'range',
					'title' => __( 'Image upload limit for dealers', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Please specify max upload files, 0 - unlimit.', AT_ADMIN_TEXTDOMAIN ),
					'min' => 0,
					'max' => 50,
					'step' => 1,
					'unit' => 'photos',
					'default' => 6
				),
			'dealer_map' => 
				array(
					'type' => 'checkbox',
					'title' => __( 'Show dealer map:', AT_ADMIN_TEXTDOMAIN ),
					'description' => '',
					'default' => false
				),

			'add_offer_btn' => 
				array(
					'type' => 'checkbox',
					'title' => __( 'Hide offer button:', AT_ADMIN_TEXTDOMAIN ),
					'description' => '',
					'default' => false
				),

			'dealer_map_height' => 
				array(
					'type' => 'range',
					'title' => __( 'Map height', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Please specify map height in pixels.', AT_ADMIN_TEXTDOMAIN ),
					'min' => 100,
					'max' => 300,
					'step' => 10,
					'unit' => 'px',
					'default' => 100
				),
			'car_mileage' =>
				array(
					'type' => 'radio',
					'title' => __( 'Car mileage:', AT_ADMIN_TEXTDOMAIN ),
					'description' => '',
					'items' => 
						array(
							'miles' => __( 'Miles', AT_ADMIN_TEXTDOMAIN ),
							'kilometers' => __( 'Kilometers', AT_ADMIN_TEXTDOMAIN ),
						),
					'default' => 'miles'
				),
			'car_transport_types' => 
				array(
					'type' => 'block',
					'title' => __( 'Car Transport Types Params', AT_ADMIN_TEXTDOMAIN ),
					'fields' =>
						array(
							'is_view_all' =>
								array(
									'type' => 'checkbox',
									'title' => __( 'Show Item "All"', AT_ADMIN_TEXTDOMAIN ),
									'description' => __( 'Turn on/off item "All"."', AT_ADMIN_TEXTDOMAIN ),
									'default' => true
								),
							'icon' =>
								array(
									'type' => 'select',
									'first_not_view' => true,
									'title' => __( 'Transport type Icon', AT_ADMIN_TEXTDOMAIN ),
									'description' => __( 'Select transport type icon.', AT_ADMIN_TEXTDOMAIN ),
									'items' => AT_Common::get_icons( 'transport_types' ),
									'default' => 'filter-icon-all'
								),
							'default' =>
								array(
									'type' => 'select',
									'first_not_view' => true,
									'title' => __( 'Default Transport Type', AT_ADMIN_TEXTDOMAIN ),
									'description' => __( 'Select default transport type.', AT_ADMIN_TEXTDOMAIN ),
									'items' => $transport_types,
									'default' => '0'
								),
						),
					'default' => array()
				),
			'car_engine_range' => 
				array(
					'type' => 'block',
					'title' => __( 'Car Engine Params', AT_ADMIN_TEXTDOMAIN ),
					'fields' =>
						array(
							'min' =>
								array(
									'type' => 'range',
									'title' => __( 'Engine min value', AT_ADMIN_TEXTDOMAIN ),
									'description' => __( 'You may specify minimal engine value', AT_ADMIN_TEXTDOMAIN ),
									'min' => 100,
									'max' => 11900,
									'step' => 100,
									'unit' => 'cm³',
									'default' => 900,
								),
							'max' =>
								array(
									'type' => 'range',
									'title' => __( 'Engine max value', AT_ADMIN_TEXTDOMAIN ),
									'description' => __( 'You may specify maximal engine value', AT_ADMIN_TEXTDOMAIN ),
									'min' => 200,
									'max' => 12000,
									'step' => 100,
									'unit' => 'cm³',
									'default' => 6500,
								),
						),
					'default' => array()
				),
		);	
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/site_options/content', array( 'title' => __( 'Transport Options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'car' ));
		}
		return $fields;
	}

	public function registration(){
		$fields = array(
			'registration_enable' =>
				array(
					'type' => 'checkbox',
					'title' => __( 'Enable Registration', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Turn on/off website front-end registration (only for multiple users in board mode)."', AT_ADMIN_TEXTDOMAIN ),
					'default' => true
				),
			'confirm_email_enable' =>
				array(
					'type' => 'checkbox',
					'title' => __( 'Enable Confirm Email', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Turn on/off website front-end confirm email."', AT_ADMIN_TEXTDOMAIN ),
					'default' => true
				),
			'session_settings' => 
				array(
					'type' => 'block',
					'title' => __( 'Session options', AT_ADMIN_TEXTDOMAIN ),
					'fields' =>
						array(
							'sess_expiration' =>
								array(
									'type' => 'input_text',
									'title' => __( 'Session expiration time', AT_ADMIN_TEXTDOMAIN ),
									'description' => __( 'Value in sec. 0 will turn off expiration time.', AT_ADMIN_TEXTDOMAIN ),
									'default' => '7200'
								),
							'sess_time_to_update' =>
								array(
									'type' => 'input_text',
									'title' => __( 'Session update time', AT_ADMIN_TEXTDOMAIN ),
									'description' => __( 'Value in sec. Default: 300.', AT_ADMIN_TEXTDOMAIN ),
									'default' => '300'
								),
							'sess_expire_on_close' =>
								array(
									'type' => 'checkbox',
									'title' => __( 'Expire On Close', AT_ADMIN_TEXTDOMAIN ),
									'description' => '',
									'default' => false
								),
							'sess_match_ip' =>
								array(
									'type' => 'checkbox',
									'title' => __( 'Session match IP', AT_ADMIN_TEXTDOMAIN ),
									'description' => __( 'Link IP address for session.', AT_ADMIN_TEXTDOMAIN ),
									'default' => false
								),
							'sess_match_useragent' =>
								array(
									'type' => 'checkbox',
									'title' => __( 'Session match useragent', AT_ADMIN_TEXTDOMAIN ),
									'description' => __( 'Link session to user browser.', AT_ADMIN_TEXTDOMAIN ),
									'default' => true
								),
							'sess_cookie_name' =>
								array(
									'type' => 'input_text',
									'title' => __( 'Session cookie name', AT_ADMIN_TEXTDOMAIN ),
									'description' => '',
									'default' => 'at_session'
								),
							'cookie_prefix' =>
								array(
									'type' => 'input_text',
									'title' => __( 'Cookie prefix', AT_ADMIN_TEXTDOMAIN ),
									'description' => __( 'Default empty.', AT_ADMIN_TEXTDOMAIN ),
									'default' => ''
								),
							'cookie_path' =>
								array(
									'type' => 'input_text',
									'title' => __( 'Cookie path', AT_ADMIN_TEXTDOMAIN ),
									'description' => __( 'Default: /', AT_ADMIN_TEXTDOMAIN ),
									'default' => '/'
								),
							'cookie_domain' =>
								array(
									'type' => 'input_text',
									'title' => __( 'Cookie domain', AT_ADMIN_TEXTDOMAIN ),
									'description' => __( 'Default empty.', AT_ADMIN_TEXTDOMAIN ),
									'default' => ''
								),
							'cookie_secure' =>
								array(
									'type' => 'checkbox',
									'title' => __( 'Secure cookie', AT_ADMIN_TEXTDOMAIN ),
									'description' => __( 'Default: off.', AT_ADMIN_TEXTDOMAIN ),
									'default' => false
								),
							'encryption_key' =>
								array(
									'type' => 'input_text',
									'title' => __( 'Encryption salt key', AT_ADMIN_TEXTDOMAIN ),
									'description' => __( 'This key will make stronger password encryption. Replace by your own.', AT_ADMIN_TEXTDOMAIN ),
									'default' => 'RePlAcE_ThIs_KeY!!'
								),
						),
					'default' => array()
				),
			'want_be_dealer_enable' =>
				array(
					'type' => 'checkbox',
					'title' => __( 'Enable submit "I want to be a dealer"', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Turn on/off requests(only for users in board mode)."', AT_ADMIN_TEXTDOMAIN ),
					'default' => true
				),
		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/site_options/content', array( 'title' => __( 'Registration & Autorization Options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'registration' ));
		}
		return $fields;
	}

	public function advertisement(){
		$fields = array(
			array(
				'type' => 'info',
				'title' => __( 'Advertisement info', AT_ADMIN_TEXTDOMAIN ),
				'description' => __( "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.", AT_ADMIN_TEXTDOMAIN ),
			),
		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/site_options/content', array( 'title' => __( 'Advertisement Site Options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'advertisement' ));
		}
		return $fields;
	}

	public function texts(){
		$fields = array(
			'text_contact_details' =>
				array(
					'type' => 'textarea',
					'title' => __( 'Contact details', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Contact details text in car\'s page', AT_ADMIN_TEXTDOMAIN ),
					'default' => 'AutoMarket does not store additional information about the seller except for those contained in the announcement.'
				),
			// array(
			// 	'type' => 'info',
			// 	'title' => __( 'Texts Info', AT_ADMIN_TEXTDOMAIN ),
			// 	'description' => __( "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.", AT_ADMIN_TEXTDOMAIN ),
			// ),
		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/site_options/content', array( 'title' => __( 'Text Site Options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'texts' ));
		}
		return $fields;
	}

	public function mailtemplate(){
		$fields = array(
			'template_mail_confirm_email' =>
				array(
					'type' => 'textarea',
					'title' => __( 'Mail template: "Confirm Email"', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Available values: %username%, %confirm_code%, %confirm_url%', AT_ADMIN_TEXTDOMAIN ),
					'default' => "Dear %username%,<br/><br/>We have prepared confirmation code for you. Please <a href=\"%confirm_url%\">click here</a> to complete your registration, or use code below.<br />Confirmation code: %confirm_code%<br/><br/>Auto Dealer",
					//Dear %username%,<br/><br/>Link to email confirm: <a href=\"%confirm_url%\">link</a>.<br/>Confirm Code: %confirm_code%<br/><br/>Auto Dealer"
				),
			'template_mail_recovery_password' =>
				array(
					'type' => 'textarea',
					'title' => __( 'Mail template: "Recovery Password"', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Available values: %username%, %recovery_link%, %recovery_code%', AT_ADMIN_TEXTDOMAIN ),
					'default' => "%username%,<br/><br/>Yor have required password recovery action. Link to password recovery: <a href=\"%recovery_link%\">link</a>.<br/>Protection Code: %recovery_code%<br/><br/>Auto Dealer"
				),
			'template_mail_add_offer' =>
				array(
					'type' => 'textarea',
					'title' => __( 'Mail template: "Add offer"', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( '<strong>Important options:</strong> %dealer_name%, %username%, %user_email%, %car_name%, %cost%, %offer_details%, %link_car%', AT_ADMIN_TEXTDOMAIN ),
					'default' => 'Dear, %dealer_name%<br/><br/>Username: %username%<br/>User email: %user_email%<br/>Car: %car_name%<br/>Cost: %cost%<br/>Offer details: %offer_details%<br/><a href="%link_car%">Reference to car</a><br/><br/>AutoDealer'
				),
			'template_mail_notify_want_be_dealer' =>
				array(
					'type' => 'textarea',
					'title' => __( 'Mail template: "I want to be a dealer"', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( '<strong>Important options:</strong> %username%, %comment%', AT_ADMIN_TEXTDOMAIN ),
					'default' => 'I want to be a dealer.<br/><br/>Username: %username%<br/>Comment: %comment%<br/><br/>AutoDealer'
				),
		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/site_options/content', array( 'title' => __( 'Mail Template Options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'mailtemplate' ));
		}
		return $fields;
	}

	public function loans(){
		$fields = array(
			'loans' =>
				array(
					'type' => 'group',
					'title' => __( 'Loans:', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Available loans', AT_ADMIN_TEXTDOMAIN ),
					'submit' => __( "Add Loan", AT_ADMIN_TEXTDOMAIN ),
					'default' => array(
						array( 'name' => 'Example Plan #1', 'downpay' => '30', 'annual_rate' => '5%', 'period_min' => '1', 'period_max' => '3' )
					),
					'fields' => array(
						'name' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Name:', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'Enter loan name identifier (eg: Holidays 2014)', AT_ADMIN_TEXTDOMAIN ),
								'default' => ''
							),
						'downpay' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Down Payment:', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'Enter down payment amount in percents', AT_ADMIN_TEXTDOMAIN ),
								'default' => '30'
							),
						'annual_rate' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Annual Rate:', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'Enter annual rate in percents', AT_ADMIN_TEXTDOMAIN ),
								'default' => '5'
							),
						'period_min' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Minimal period:', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'Enter minimal period count in years', AT_ADMIN_TEXTDOMAIN ),
								'default' => '1'
							),
						'period_max' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Maximal period:', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'Enter highest period count in years', AT_ADMIN_TEXTDOMAIN ),
								'default' => '3'
							),
					)
				),
		);
		// $fields = array(
		// 	array(
		// 		'type' => 'info',
		// 		'title' => __( 'Texts Info', AT_ADMIN_TEXTDOMAIN ),
		// 		'description' => __( "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.", AT_ADMIN_TEXTDOMAIN ),
		// 	),
		// );
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/site_options/content', array( 'title' => __( 'Loans Options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'loans' ));
		}
		return $fields;
	}
}
