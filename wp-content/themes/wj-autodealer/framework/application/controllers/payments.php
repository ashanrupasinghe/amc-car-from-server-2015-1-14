<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Payments extends AT_Controller{

	private $_publish_limit = 0;

	public function __construct() {
		parent::__construct();
		// if ( !AT_Common::is_user_logged() && $this->uri->segments( 1 ) != 'upload' ) {
		// 	AT_Common::redirect('/');
		// }
	}

	public function index() {
// global $wp_rewrite;
// $wp_rewrite->flush_rules();

		$payments_model = $this->load->model( 'payments_model' );
		$payments_model->check_valid_hash('asdas');
		$car_id = AT_Session::get_instance()->userdata('paidEntityID');
		$car_model = $this->load->model( 'car_model' );

		if ( AT_Common::validate_id( $car_id ) && ( $car_model->check_user_cars( $car_id, AT_Common::get_logged_user_id(), 'publish' ) > 0 ) ) {
			$this->view->use_layout('header_content_footer')
				->add_block( 'content', 'payments/prepare', array(
					'cars' => $car_model->get_car_info( $car_id ),
					// 'currency' => $reference_model->get_currency_by_id( $_POST['item_id'] )
					'methods' => array(
						'paypal' => array(
							'state' => $this->core->get_option( 'paypal_state', 0 ),
							'name' => 'PayPal',
							'logo' => 'https://www.paypalobjects.com/webstatic/mktg/Logo/AM_mc_vs_ms_ae_UK.png',
						)
					),
					'plans' => $this->core->get_option( 'merchant_plan', false ),
					'paid'  => array(
						'featured' => $this->core->get_option( 'merchant_module_featured', false ),
						'top' => $this->core->get_option( 'merchant_module_promote', false ),
					),



				) );
		} else {
			$this->view->use_layout('header_content_footer')
				->add_block( 'content', 'payments/denied', array( ) );		
		}

	}
	private function _update() {
		
	}
	public function success() {
		
		$plans = $this->core->get_option( 'merchant_plan', false );

		$plan_id = AT_Session::get_instance()->userdata('paymentPlanID');

		if (AT_Session::get_instance()->userdata('checkoutAllower') == true) {

			$plan = $plans[$plan_id];
			$expirity = date('Y-m-d h:s:i', strtotime('+' . $plan['period'] . ' days'));
			update_post_meta( AT_Session::get_instance()->userdata('paidEntityID'), '_' . AT_Session::get_instance()->userdata('paidEntity') . '_exp', $expirity );

			AT_Session::get_instance()->set_userdata('checkoutAllower',false);
			AT_Session::get_instance()->unset_userdata(
				array(
					'paymentMethod',
					'paymentPlanID',
					'recent_transaction_id',
					'paypal_transaction_id',
					'paidEntity',
					'paidEntityID',
					'checkoutAllower'
				));

				$this->view->use_layout('header_content_footer')
					->add_block( 'content', 'payments/success', array( 'response' => '', 'transaction_id' => false ) );
		} else {
			$this->view->use_layout('header_content_footer')
				->add_block( 'content', 'payments/denied', array( ) );		

		}

	}
	public function checkout() {
		if ( !empty( $_POST ) ) {
			$plan_id = $_POST['plan'];
			$car_id = AT_Session::get_instance()->userdata('paidEntityID');
			$car_model = $this->load->model( 'car_model' );
			$plans = $this->core->get_option( 'merchant_plan', false );

			// $entityID = $plans[$plan_id];
			$price = $plan['rate'];
			if ( isset( $_POST['payment_method'] ) ) {
				if ( $_POST['payment_method'] === 'paypal' ) {
					// Save session data
					AT_Session::get_instance()->set_userdata('paymentMethod',$_POST['payment_method']);
					// AT_Session::get_instance()->set_userdata('paymentAmount',$price);
					AT_Session::get_instance()->set_userdata('paymentPlanID',$plan_id);

					AT_Session::get_instance()->set_userdata('checkoutAllower',true);

					AT_Common::redirect( 'merchant_paypal/query' );

				}
			}
		} else {
			AT_Session::get_instance()->unset_userdata('checkoutAllower');

			$this->view->use_layout('header_content_footer')
				->add_block( 'content', 'payments/denied', array( ) );
		}
	}

	public function callback() {
		if ( !empty( $_POST ) ) {

			// $plan_id = $_POST['plan'];

			// $car_id = AT_Session::get_instance()->userdata('paidEntityID');

			// $car_model = $this->load->model( 'car_model' );

			// $plans = $this->core->get_option( 'merchant_plan', false );

			// $plan = $plans[$plan_id];

			// $price = $plan['rate'];

			// if ( $_POST['payment_method'] === 'paypal' ) {


			// 	// $at_paypal = new AT_Paypal();


			// }



			// if ( AT_Common::validate_id( $car_id ) && ( $car_model->check_user_cars( $car_id, AT_Common::get_logged_user_id(), 'publish' ) > 0 ) ) {
			// 	$this->view->use_layout('header_content_footer')
			// 		->add_block( 'content', 'payments/checkout', array(
			// 			'cars' => $car_model->get_car_info( $car_id ),
			// 		) );
			// } else {
			// 	$this->view->use_layout('header_content_footer')
			// 		->add_block( 'content', 'payments/denied', array( ) );		
			// }
		} else {
			$this->view->use_layout('header_content_footer')
				->add_block( 'content', 'payments/denied', array( ) );		
		}
	}

	public function paypal() {
		if ( !empty( $_POST ) ) {
			print_r( $_POST );
		}
	}

}