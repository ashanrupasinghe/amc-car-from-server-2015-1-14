<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Meta_Options {

	static public function register(){
		$class_methods = get_class_methods( 'AT_Meta_Options_Config' );
		foreach( $class_methods as $value) {
			$options = AT_Meta_Options_Config::$value();
			if (!empty($options) && in_array( AT_Admin::get_current_post_type(), $options['pages'] ) )
				new AT_Meta_Box( $options );
		}
	}
}

class AT_Meta_Options_Config {

	static public function default_meta(){
		return array(
			'title' => sprintf( __( '%1$s Page Layout', AT_ADMIN_TEXTDOMAIN ), THEME_NAME ),
			'id' => 'at_side_meta_box',
			'pages' => array( 'page', 'post', 'news', 'reviews' ),
			'callback' => '',
			'context' => 'side',
			'priority' => 'default',
			'fields' => array(
				'_layout' => 
					array(
						'type' => 'radio_image',
						'title' => __( 'Layout', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( 'You can choose between a left, right, or no sidebar layout for your page.', AT_ADMIN_TEXTDOMAIN ),
						'items' => 
							array(
								'content' => AT_URI . '/assets/images/admin/layouts/page/1.png',
								'left_content' => AT_URI . '/assets/images/admin/layouts/page/2.png',
								'content_right' => AT_URI . '/assets/images/admin/layouts/page/3.png',
							),
						'default' => 'left_content'
					),
				'_page_tagline' => 
					array(
						'type' => 'input_text',
						'title' => __( 'Page Tagline', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( 'Alternative page title.', AT_ADMIN_TEXTDOMAIN ),
						'default' => ''
					),
				'_disable_page_title' =>
					array(
						'type' => 'checkbox',
						'title' => __( 'Disable Page H1 title', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( 'Check this option to disable page H1 title on this page', AT_ADMIN_TEXTDOMAIN ),
						'default' => false
					),
				'_disable_breadcrumbs' =>
					array(
						'type' => 'checkbox',
						'title' => __( 'Disable Breadcrumbs', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( 'Check this option to disable breadcrumbs on this page', AT_ADMIN_TEXTDOMAIN ),
						'default' => false
					),
				'_custom_sidebar' => 
					array(
						'type' => 'select',
						'title' => __( 'Custom Sidebar', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "Select the custom sidebar that you'd like to be displayed on this page.<br /><br />Note:  You will need to first create a custom sidebar under the &quot;Sidebar&quot; tab in your theme's option panel before it will show up here.", AT_ADMIN_TEXTDOMAIN ),
						'items' => AT_Sidebars::get_custom_sidebars(),
						'default' => ''
					),
			)
		);
	}

	static public function post_meta(){
		return array(
			'title' => sprintf( __( '%1$s General Post Options', AT_ADMIN_TEXTDOMAIN ), THEME_NAME ),
			'id' => 'at_post_meta_box',
			'pages' => array( 'post', 'news', 'reviews' ),
			'callback' => '',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				'_featured_video' =>
					array(
						'type' => 'input_text',
						'title' => __( 'Featured Video', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( 'You can paste a URL of a video here to display within your post.', AT_ADMIN_TEXTDOMAIN ),
						'default' => ''
					),
				'_disable_post_image' =>
					array(
						'type' => 'checkbox',
						'title' => __( 'Disable Featured Image', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "Check this box to disable featured post image inside.", AT_ADMIN_TEXTDOMAIN ),
						'default' => false
					),
			)
		);
	}

	static public function car_side_owner_meta(){
		$user_model = AT_Loader::get_instance()->model( 'user_model' );
		return array(
			'title' => sprintf( __( 'Owner', AT_ADMIN_TEXTDOMAIN ), THEME_NAME ),
			'id' => 'at_car_side_owner_meta_box',
			'pages' => array( 'car' ),
			'callback' => '',
			'context' => 'side',
			'priority' => 'default',
			'fields' => array(
				// '_promote_featured_exp' => 
				// 	array(
				// 		'id' => '_promote_featured_exp',
				// 		'type' => 'date',
				// 		'title' => __( 'Featured Expire', AT_ADMIN_TEXTDOMAIN ),
				// 		'description' => '',
				// 		'default' => date("Y-m-d H:s:i")
				// 	),
				'_promote_top_exp' => 
					array(
						'id' => '_promote_top_exp',
						'type' => 'date',
						'title' => __( 'Promote Date', AT_ADMIN_TEXTDOMAIN ),
						'description' => 'Item promote expire date. Items with higher date will always shown in the top. This option also available with Paid Options.',
						//'default' => date("Y-m-d H:s:i")
					),

				'_owner_id' => 
					array(
						'id' => '_owner_id',
						'type' => 'select',
						'title' => __( 'Owner', AT_ADMIN_TEXTDOMAIN ),
						'description' => '',
						'items' => $user_model->get_data_for_options( 'get_all_users' ),
						'default' => '0'
					),
				'_affiliate_id' => 
					array(
						'id' => '_affiliate_id',
						'type' => 'select',
						'title' => __( 'Affiliate', AT_ADMIN_TEXTDOMAIN ),
						'description' => '',
						'items' => array(),
						'default' => '0'
					),
				'_featured_car' =>
					array(
						'type' => 'checkbox',
						'title' => __( 'Make this item featured', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( 'Check this option to make this item featured', AT_ADMIN_TEXTDOMAIN ),
						'default' => false
					),
			)
		);
	}

	static public function car_side_photos_meta(){
		$user_model = AT_Loader::get_instance()->model( 'user_model' );
		return array(
			'title' => sprintf( __( 'Photos', AT_ADMIN_TEXTDOMAIN ), THEME_NAME ),
			'id' => 'at_car_side_photos_meta_box',
			'pages' => array( 'car' ),
			'callback' => '',
			'context' => 'side',
			'priority' => 'default',
			'fields' => array(
				array(
					'type' => 'car_photo_upload',
					//'title' => __( 'Photos', AT_ADMIN_TEXTDOMAIN ),
					//'description' => '',
					'items' => array(),
					'default' => '0'
				),
			)
		);
	}

	static public function car_meta(){

		function render_reference_options( $options ) {
			foreach ($options as $key => $value) {
				$options[$key] = array(
						'type' => 'checkbox',
						'title' => $value['name'],
						'description' => '',
						'default' => false
					);
			}
			return $options;
		}

		$reference_model = AT_Loader::get_instance()->model('reference_model');
		return array(
			'title' => __( 'Car Options', AT_ADMIN_TEXTDOMAIN ),
			'id' => 'at_post_meta_box',
			'pages' => array( 'car' ),
			'callback' => '',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array_merge(
				array(
				'_transport_type_id' => 
					array(
						'id' => '_transport_type_id',
						'type' => 'select',
						'title' => __( 'Transport type', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "Please select transport type.", AT_ADMIN_TEXTDOMAIN ),
						'items' => $reference_model->get_data_for_options( 'get_transport_types' ),
						'default' => ''
					),
				'_manufacturer_id' => 
					array(
						'id' => '_manufacturer_id',
						'type' => 'select',
						'title' => __( 'Make', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "Please select manufacturer.", AT_ADMIN_TEXTDOMAIN ),
						'items' => $reference_model->get_data_for_options( 'get_manufacturers' ),
						'default' => ''
					),
				'_model_id' => 
					array(
						'id' => '_model_id',
						'type' => 'select',
						'title' => __( 'Model', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "Please select Model.", AT_ADMIN_TEXTDOMAIN ),
						'items' => array(),
						'default' => ''
					),
				'_version' =>
					array(
						'type' => 'input_text',
						'title' => __( 'Version', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( 'You can paste a version', AT_ADMIN_TEXTDOMAIN ),
						'default' => ''
					),
				'_body_type_id' => 
					array(
						'type' => 'select',
						'title' => __( 'Body type', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "Please select body type.", AT_ADMIN_TEXTDOMAIN ),
						'items' => $reference_model->get_data_for_options( 'get_body_types' ),
						'default' => ''
					),
				'_transmission_id' => 
					array(
						'type' => 'select',
						'title' => __( 'Transmission', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "Please select transmission.", AT_ADMIN_TEXTDOMAIN ),
						'items' => $reference_model->get_data_for_options( 'get_transmissions' ),
						'default' => ''
					),
				'_door_id' => 
					array(
						'type' => 'select',
						'title' => __( 'Doors', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "Please select doors.", AT_ADMIN_TEXTDOMAIN ),
						'items' => $reference_model->get_data_for_options( 'get_doors' ),
						'default' => ''
					),
				'_fuel_id' => 
					array(
						'type' => 'select',
						'title' => __( 'Fuel', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "Please select fuel.", AT_ADMIN_TEXTDOMAIN ),
						'items' => $reference_model->get_data_for_options( 'get_fuels' ),
						'default' => ''
					),
				'_technical_condition_id' => 
					array(
						'type' => 'select',
						'title' => __( 'Technical condition', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "Please select technical condition.", AT_ADMIN_TEXTDOMAIN ),
						'items' => $reference_model->get_data_for_options( 'get_technical_conditions' ),
						'default' => ''
					),
				'_region_id' => 
					array(
						'type' => 'select',
						'id' => '_region_id',
						'title' => __( 'Region', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "Please select region.", AT_ADMIN_TEXTDOMAIN ),
						'items' => $reference_model->get_data_for_options( 'get_regions' ),
						'default' => ''
					),
				'_state_id' => 
					array(
						'id' => '_state_id',
						'type' => 'select',
						'title' => __( 'State', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "Please select state.", AT_ADMIN_TEXTDOMAIN ),
						'items' => array(),
						'default' => ''
					),
				'_drive_id' => 
					array(
						'type' => 'select',
						'title' => __( 'Drive', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "Please select drive.", AT_ADMIN_TEXTDOMAIN ),
						'items' => $reference_model->get_data_for_options( 'get_drive' ),
						'default' => ''
					),
				'_color_id' => 
					array(
						'type' => 'select',
						'title' => __( 'Color', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "Please select color.", AT_ADMIN_TEXTDOMAIN ),
						'items' => $reference_model->get_data_for_options( 'get_colors' ),
						'default' => ''
					),
				'_category_id' => 
					array(
						'type' => 'select',
						'title' => __( 'Condition Category', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "Please select item condition.", AT_ADMIN_TEXTDOMAIN ),
						'items' => array(
							'1' => __( 'New car', AT_ADMIN_TEXTDOMAIN ), 
							'2' => __( 'Used', AT_ADMIN_TEXTDOMAIN )
						),
						'default' => ''
					),
				'_price' =>
					array(
						'type' => 'input_text',
						'title' => __( 'Price', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "Specify item price (only numbers allowed).", AT_ADMIN_TEXTDOMAIN ),
						'default' => '',
						'input' => 'number'
					),
				'_currency_id' => 
					array(
						'type' => 'select',
						'title' => __( 'Сurrency', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "Please select currency.", AT_ADMIN_TEXTDOMAIN ),
						'items' => $reference_model->get_data_for_options( 'get_currencies' ),
						'default' => ''
					),
				'_mileage' =>
					array(
						'type' => 'input_text',
						'title' => __( 'Mileage', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( 'You can paste a mileage', AT_ADMIN_TEXTDOMAIN ),
						'default' => '',
						'input' => 'number'
					),
				'_vin' =>
					array(
						'type' => 'input_text',
						'title' => __( 'Vin code', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( 'You can paste a vin code', AT_ADMIN_TEXTDOMAIN ),
						'default' => ''
					),
				'_cilindrics' =>
					array(
						'type' => 'input_text',
						'title' => __( 'Engine, cm³', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( 'You can paste cilindrics', AT_ADMIN_TEXTDOMAIN ),
						'default' => '',
						'input' => 'number'
					),
				'_engine_power' =>
					array(
						'type' => 'input_text',
						'title' => __( 'Engine power', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( 'You can paste engine power', AT_ADMIN_TEXTDOMAIN ),
						'default' => '',
						'input' => 'number'
					),
				'_fabrication' =>
					array(
						'type' => 'input_text',
						'title' => __( 'Year', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( 'You can paste fabrication year', AT_ADMIN_TEXTDOMAIN ),
						'default' => ''
					),
				'_seats' =>
					array(
						'type' => 'input_text',
						'title' => __( 'Seats', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( 'You can paste seats', AT_ADMIN_TEXTDOMAIN ),
						'default' => ''
					),
				'_price_negotiable' =>
					array(
						'type' => 'checkbox',
						'title' => __( 'Price negotiable', AT_ADMIN_TEXTDOMAIN ),
						'description' => '',
						'default' => false
					),
			), render_reference_options( $reference_model->get_equipments() ) )
		);
	}
}

class AT_Meta_Box extends AT_Admin_Controller {
		
	protected $_block_id = 0;
	protected $_get_params = false;

	private $_meta_box;
	
	function __construct( $meta_box ) {
		if ( !is_admin() ) return;

		$this->view = new AT_View();
		$this->view->add_style( 'style.css', 'assets/css/admin/admin.css');
		$this->view->add_style( 'fonts.css', 'assets/css/fonts.css');
		$this->view->add_style( 'icons.css', 'assets/css/icons.css');
		$this->view->add_style( 'datetimepicker.css', 'assets/css/jquery/jquery.datetimepicker.css');
		$this->view->add_script( 'admin-common', 'assets/js/common.js');
		$this->view->add_style( 'select2.css', 'assets/css/select2/select2.css');
		$this->view->add_localize_script( 'admin-common', 'theme_site_url', AT_Common::site_url('/') );
		$this->view->add_script( 'admin-options', 'assets/js/admin/options/options.js');
		$this->view->add_script( 'select2', 'assets/js/select2/select2.min.js');
		$this->view->add_script( 'jquery.datetimepicker', 'assets/js/jquery/jquery.datetimepicker.js', array( 'jquery' ) );
		parent::__construct();
		$this->_meta_box = $meta_box;
		
		add_action( 'admin_menu', array( &$this, 'add' ) );
		add_action( 'save_post', array( &$this, 'save' ) );
	}

	function add() {
		foreach ( $this->_meta_box['pages'] as $page ) {
			add_meta_box( $this->_meta_box['id'], $this->_meta_box['title'], array( &$this, 'show' ), $page, $this->_meta_box['context'], $this->_meta_box['priority'] );
		}
	}

	function show() {
		global $post;
		foreach( $this->_meta_box['fields'] as $key => &$item ) {
			if (is_numeric($key)) continue;
			if ( !empty( $this->_group_name ) ) {
				$val = isset( $this->_group_item_values[$key] ) ? $this->_group_item_values[$key] : null;
			} else {
				$val = get_post_meta( $post->ID, $key, true );
			}
			switch ( $key ) {
				case '_model_id':
					$manufacturer_id = $this->_meta_box['fields']['_manufacturer_id']['value'];
					if ( !empty( $manufacturer_id ) && $manufacturer_id > 0 ) {
						$reference_model = AT_Loader::get_instance()->model( 'reference_model' );
						$item['items'] = $reference_model->get_data_for_options( 'get_models_by_manufacturer_id', $manufacturer_id );
					}
					unset( $manufacturer_id );
					break;
				case '_state_id':
					$region_id = $this->_meta_box['fields']['_region_id']['value'];
					if ( !empty( $region_id ) && $region_id > 0 ) {
						$reference_model = AT_Loader::get_instance()->model( 'reference_model' );
						$item['items'] = $reference_model->get_data_for_options( 'get_states_by_region_id', $region_id );
					}
					unset( $region_id );
					break;
				case '_owner_id':
					if ( !empty( $val ) ) {
						$user_model = AT_Loader::get_instance()->model( 'user_model' );
						$user_info = $user_model->get_user_by_id( $val );
						if ($user_info) {
							$item['title'] = $user_info['name'];
							$item['description'] = $user_info['email'] . '<br/>' . ( !empty( $user_info['phone'] ) ? $user_info['phone'] . '<br/>' : '' ) . ( !empty( $user_info['phone_2'] ) ? $user_info['phone_2'] . '<br/>' : '' );
						}
					}
					break;
				case '_affiliate_id':
					$owner_id = $this->_meta_box['fields']['_owner_id']['value'];
					if ( !empty( $owner_id ) && $owner_id > 0 ) {
						$user_model = AT_Loader::get_instance()->model( 'user_model' );
						foreach ($user_model->get_dealer_affiliates( $owner_id ) as $key => $value) {
							$item['items'][$value['id']] = $value['name'];
						}
					}
					unset( $owner_id );
					break;
			}
			$item['value'] = empty( $val ) ? ((isset($item['default']) && !empty($item['default'])) ? $item['default'] : '') : $val;
		}

		$this->view->use_layout('content');
		$this->_parse_fields( $this->_meta_box['fields'] );
		$out = $this->view->render()->display( true );

		# Use nonce for verification
		$out .= '<input type="hidden" name="' . $this->_meta_box['id'] . '_meta_box_nonce" value="' . wp_create_nonce( basename(__FILE__) ) . '" />';
		
		echo $out;
	}
	
	function save( $post_id ) {
		# check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		
		if( empty( $_POST[$this->_meta_box['id'] . '_meta_box_nonce'] ) )
			return $post_id;
		
		# verify nonce
		if ( !wp_verify_nonce( $_POST[$this->_meta_box['id'] . '_meta_box_nonce'], basename(__FILE__) ) ) {
			return $post_id;
		}

		# check permisions
		if ( 'page' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} elseif ( !current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		foreach( $this->_meta_box['fields'] as $key => &$item ) {
			if (is_numeric($key)) continue;
			if ( !empty( $this->_group_name ) ) {
				$val = isset( $this->_group_item_values[$key] ) ? $this->_group_item_values[$key] : null;
			} else {
				$val = get_post_meta( $post_id, $key, true );
			}
			$item['value'] = is_null( $val ) ? $item['default'] : $val;
			break;
		}

		if ( empty( $_POST[THEME_PREFIX . 'options'] ) ) {
			$_POST[THEME_PREFIX . 'options'] = array();
		}
		$save_values = $this->_save_fields( $this->_meta_box['fields'], $_POST[THEME_PREFIX . 'options'], true );

		foreach ($save_values as $key => $new) {
			if( $key == '_price' ) {
				$new = str_replace( array(',', ' '), array('', ''), $new );
			}
			$old = get_post_meta( $post_id, $key, true );
			if ( $new && $new != $old ) {
				update_post_meta( $post_id, $key, $new );
			} elseif ('' == $new && $old) {
				delete_post_meta( $post_id, $key, $old );
			}
		}
		if( $_POST['post_type'] == 'car' && $this->_meta_box['id'] == 'at_car_side_photos_meta_box' ) {
			$car_id = $post_id;
			$photo_model = $this->load->model('photo_model');
			$photos = $photo_model->get_photos_by_post( $car_id, 'car' );
			$main_photo = $photo_model->get_photo_by_post( $car_id, 'car', 1 );
			if (isset($_POST[THEME_PREFIX . 'options']['photos'])){
				$post_photos = $_POST[THEME_PREFIX . 'options']['photos'];
				unset( $_POST[THEME_PREFIX . 'options']['photos'] );

				foreach ($photos as $key => $value) {
	        		if( !isset( $post_photos[$value['id']] ) ) {
	        			$photo_model->del_photo_by_id( $value['id'] );
	        		} else if( $post_photos[$value['id']] && ( $value['id'] != $main_photo['id'] ) ) {
	        			$photo_model->set_photo_main_by_id( $car_id, 'car', $value['id'] );
	        		}
	        	}
	        	$sort = 0;
	        	foreach ($post_photos as $key => $photo) {
	        		$sort ++;
	        		if( !is_numeric( $key ) ) {
	        			$key = explode('/', $key);
	        			if ( count( $key ) > 1 ) continue;
	        			if( ( $photo_id = $photo_model->resize_uploaded_image( AT_DIR_THEME . '/uploads/' . $key[0], $car_id, 'car', $photo_model->car_sizes )) && $photo ) {
	        				$photo_model->set_photo_main_by_id( $car_id, 'car', $photo_id  );
	        				$photo_model->set_photo_sort( $photo_id, $sort );
	        			}
	        		} else {
	        			$photo_model->set_photo_sort( $key, $sort );
	        		}
	        	}
	        	unset( $post_photos );
	        } else if( count( $photos ) > 0 ) {
	        	$photo_model->del_photos_by_post( $car_id, 'car' );
	        }
	    }

	}
	
}

?>
