<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Merchant extends AT_Admin_Controller{
	
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
			// 'paid_accound' =>
			// 	array(
			// 		'type' => 'checkbox',
			// 		'title' => __( 'Enable paid accounts and registration', AT_ADMIN_TEXTDOMAIN ),
			// 		'description' => __( 'Registration will be paid.', AT_ADMIN_TEXTDOMAIN ),
			// 		'default' => false
			// 	),

			'merchant_status' =>
				array(
					'type' => 'checkbox',
					'title' => __( 'Enable merchant (BETA)', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'This option will allow you use PayPal and other paid featured. Currently this option is in BETA-MODE.', AT_ADMIN_TEXTDOMAIN ),
					'default' => false
				),

			'merchant_module_promote' =>
				array(
					'type' => 'checkbox',
					'title' => __( 'Enable paid ads promote', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Promoted ads will shown ads on top with different color.', AT_ADMIN_TEXTDOMAIN ),
					'default' => false
				),

			// 'merchant_module_featured' =>
			// 	array(
			// 		'type' => 'checkbox',
			// 		'title' => __( 'Enable paid featured items', AT_ADMIN_TEXTDOMAIN ),
			// 		'description' => __( 'Featured ads will displayed in car gallery carousels.', AT_ADMIN_TEXTDOMAIN ),
			// 		'default' => false
			// 	),

			'merchant_plan' =>
				array(
					'type' => 'group',
					'title' => __( 'Ads promotion:', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Available plans for paid ads', AT_ADMIN_TEXTDOMAIN ),
					'submit' => __( "Add a plan", AT_ADMIN_TEXTDOMAIN ),
					'default' => array(
						array( 'name' => 'Sample Subscription', 'period' => '1', 'period_label' => 'Days', 'rate' => '1.00' )
					),
					'fields' => array(
						'name' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Name:', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'Enter plan name (eg: 1 Month Subscriptions)', AT_ADMIN_TEXTDOMAIN ),
								'default' => ''
							),
						'period' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Period:', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'Period in days.', AT_ADMIN_TEXTDOMAIN ),
								'default' => '7'
							),
						'period_label' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Period Label:', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'Enter label for period. This label will shown right side from a price.', AT_ADMIN_TEXTDOMAIN ),
								'default' => '7 days'
							),
						'rate' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Rate:', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'Enter rate for this period', AT_ADMIN_TEXTDOMAIN ),
								'default' => '1.00'
							),
					)
				),
			// 'merchant_price' =>
			// 	array(
			// 		'type' => 'input_text',
			// 		'title' => __( 'Price for 1 ad:', AT_ADMIN_TEXTDOMAIN ),
			// 		'description' => 'Only for paid ads withoud subscription.',
			// 		'default' => '1.00'
			// 	),
		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/merchant/content', array( 'title' => __( 'Merchant / General Options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'general' ));
		}
		return $fields;
	}
	public function paypal(){
		$fields = array(
			'paypal_state' => 
				array(
					'type' => 'radio',
					'title' => __( 'PayPal state:', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'You may switch paypal state to support payment gateway.', AT_ADMIN_TEXTDOMAIN ),
					'items' => 
						array(
							'0' => __( 'Disabled', AT_ADMIN_TEXTDOMAIN ),
							'express' => __( 'Express Checkout', AT_ADMIN_TEXTDOMAIN ),
							// 'nvp' => __( 'PayPal Payments Standard', AT_ADMIN_TEXTDOMAIN ),
							// 'pro' => __( 'PayPal Payments Pro', AT_ADMIN_TEXTDOMAIN ),
						),
					'default' => '0'
				),
			'paypal_username' =>
				array(
					'type' => 'input_text',
					'title' => __( 'API Username:', AT_ADMIN_TEXTDOMAIN ),
					'description' => '',
					'default' => ''
				),
			'paypal_currency_code' =>
				array(
					'type' => 'input_text',
					'title' => __( 'Currency Code:', AT_ADMIN_TEXTDOMAIN ),
					'description' => '',
					'default' => 'USD'
				),
			'paypal_password' =>
				array(
					'type' => 'input_text',
					'title' => __( 'API Password:', AT_ADMIN_TEXTDOMAIN ),
					'description' => '',
					'default' => ''
				),
			'paypal_signature' =>
				array(
					'type' => 'input_text',
					'title' => __( 'API Signature:', AT_ADMIN_TEXTDOMAIN ),
					'description' => '',
					'default' => ''
				),
			'paypal_version' =>
				array(
					'type' => 'input_text',
					'title' => __( 'API Version:', AT_ADMIN_TEXTDOMAIN ),
					'description' => '',
					'default' => ''
				),
			'paypal_mode' => 
				array(
					'type' => 'radio',
					'title' => __( 'PayPal Mode:', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'You may switch paypal mode between test (sandbox) and production.', AT_ADMIN_TEXTDOMAIN ),
					'items' => 
						array(
							'sandbox' => __( 'Sandbox', AT_ADMIN_TEXTDOMAIN ),
							'production' => __( 'Production', AT_ADMIN_TEXTDOMAIN ),
						),
					'default' => 'sandbox'
				),

			// 'paypal_host' =>
			// 	array(
			// 		'type' => 'input_text',
			// 		'title' => __( 'API Server:', AT_ADMIN_TEXTDOMAIN ),
			// 		'description' => '',
			// 		'default' => ''
			// 	),

			'paypal_callback' =>
				array(
					'type' => 'input_text',
					'title' => __( 'Return URL:', AT_ADMIN_TEXTDOMAIN ),
					'description' => '',
					'default' =>  get_home_url('/') . '/gateway/paypal/return',
				),
			'paypal_cancel' =>
				array(
					'type' => 'input_text',
					'title' => __( 'Cancel URL:', AT_ADMIN_TEXTDOMAIN ),
					'description' => '',
					'default' =>  get_home_url('/') . '/gateway/paypal/cancel',
				),
			'paypal_success' =>
				array(
					'type' => 'textarea',
					'title' => __( 'Success text:', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'This message will be displayed on successful transaction. Available values: %transaction_id%', AT_ADMIN_TEXTDOMAIN ),
					'default' => 'Thank you for choosing us. Your payment has been accepted.'
				),
			'paypal_fail' =>
				array(
					'type' => 'textarea',
					'title' => __( 'Fail text:', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'This message will be displayed on transaction error. Available values: %paypal_reason%', AT_ADMIN_TEXTDOMAIN ),
					'default' => 'Your payment has been declined. Please try later.'
				),
		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/merchant/content', array( 'title' => __( 'Merchant / PayPal Options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'paypal' ));
		}
		return $fields;
	}
	public function gateway(){
		$fields = array(
			'gateway_state' => 
				array(
					'type' => 'radio',
					'title' => __( 'Gateway state:', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'You may switch gateway state.', AT_ADMIN_TEXTDOMAIN ),
					'items' => 
						array(
							'0' => __( 'Disabled', AT_ADMIN_TEXTDOMAIN ),
							'1' => __( 'Enabled', AT_ADMIN_TEXTDOMAIN ),
						),
					'default' => '0'
				),
			'custom_gw' =>
				array(
					'type' => 'group',
					'title' => __( 'Gateway:', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Available gateways', AT_ADMIN_TEXTDOMAIN ),
					'submit' => __( "Add gateway", AT_ADMIN_TEXTDOMAIN ),
					'default' => array(
						array(
							'name' => 'Visa/Mastercard',
							'callback' => '',
							'merchant' => '',
							'mode' => 'internal',
							'access_token' => '',
							'access_secret' => '',
							'state_field' => 'state',
							'state_success' => '1',
							'state_error' => '2',
							'ssl' => '1',
							'lib' => '/framework/library/gateway/test.php',
							)
					),
					'fields' => array(
						'name' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Name:', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'Enter gateway name to identify (eg: Discovery or Visa)', AT_ADMIN_TEXTDOMAIN ),
								'default' => ''
							),
						'callback' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Local callback route:', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'Enter callback route will be used by remote server to respond order status.', AT_ADMIN_TEXTDOMAIN ),
								'default' => '/gateway/process'
							),
						'merchant' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Remote merchanr URL:', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'Enter merchant/bank URL (HTTPS)', AT_ADMIN_TEXTDOMAIN ),
								'default' => ''
							),
						'mode' => 
							array(
								'type' => 'radio',
								'title' => __( 'Verification model:', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'Please specify the way of user credentils validation.', AT_ADMIN_TEXTDOMAIN ),
								'items' => 
									array(
										'internal' => __( 'Internal - user will stay on your website. All all requests will be sent from your server via internal CURL library.', AT_ADMIN_TEXTDOMAIN ),
										'external' => __( 'External - user will be redirected to third-party merchant.', AT_ADMIN_TEXTDOMAIN ),
									),
								'default' => 'internal'
							),

						'access_token' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Access Token:', AT_ADMIN_TEXTDOMAIN ),
								'description' => '',
								'default' => ''
							),
						'access_secret' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Access Secret:', AT_ADMIN_TEXTDOMAIN ),
								'description' => '',
								'default' => ''
							),
						'state_field' =>
							array(
								'type' => 'input_text',
								'title' => __( 'State field:', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'Enter custom status response field.', AT_ADMIN_TEXTDOMAIN ),
								'default' => ''
							),
						'state_success' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Success code:', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'Enter success status code.', AT_ADMIN_TEXTDOMAIN ),
								'default' => '1'
							),
						'state_error' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Fail code:', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'Enter fail status code.', AT_ADMIN_TEXTDOMAIN ),
								'default' => '2'
							),
						'ssl' =>
							array(
								'type' => 'checkbox',
								'title' => __( 'Use SSL', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'Override SSL.', AT_ADMIN_TEXTDOMAIN ),
								'default' => '1'
							),
						'lib' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Gateway Library:', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'You may specify location to gateway library on this server. E.g.: /vendors/firstcenturybank/lib.php', AT_ADMIN_TEXTDOMAIN ),
								'default' => '/framework/library/gateway/test.php'
							),
					)
				),
			// 'recommendations' => 
			// 	array(
			// 		'type' => 'info',
			// 		'title' => __( 'PCI DSS recommendations:', AT_ADMIN_TEXTDOMAIN ),
			// 		'description' => 'To make your website more secure we recommend follow instructions bellow:
			// 		<ul>
			// 		<li>[' . ( ( $_SERVER['SERVER_PORT'] == 80 ) ? "FAIL" : "OK"  ) . '] Use SSL instead insecure HTTP protocol.</li>
			// 		</ul>
			// 		',
			// 	),
		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/merchant/content', array( 'title' => __( 'Merchant / Custom Gateway', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'general' ));
		}
		return $fields;
	}
}