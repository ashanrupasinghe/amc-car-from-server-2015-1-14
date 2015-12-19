<?php
if (!defined("AT_DIR")) die('!!!');
/////////////////////////////////////////////////////////////////////
// main page
/////////////////////////////////////////////////////////////////////

class AT_Merchant_paypal extends AT_Controller {

	protected $methodName;
	protected $method;
	protected $nvp;
	protected $username;
	protected $password;
	protected $signature;
	protected $mode;
	protected $version;
	protected $lang;
	protected $urlPrefix = '';
	protected $urlReturn = 'http://autodealer.winterjuice.com/merchant_paypal/callback';
	protected $urlCancel = 'http://autodealer.winterjuice.com/gateway/paypal/cancel';

	public function __construct() {
		parent::__construct();

		$this->validation();

		$this->method = 'SetExpressCheckout';
		$this->nvp = '';
		$this->username = $this->core->get_option( 'paypal_username' ); //'mm-facilitator_api1.winterjuice.com';
		$this->password = $this->core->get_option( 'paypal_password' );
		$this->signature = $this->core->get_option( 'paypal_signature' );
		$this->mode = $this->core->get_option( 'paypal_mode' );
		$this->version = $this->core->get_option( 'paypal_version' );
		$this->lang = strtoupper(substr(get_bloginfo ( 'language' ), 0, 2)); //GB or EN

	}
	public function validation() {
		if ( AT_Session::get_instance()->userdata('checkoutAllowed') === true ) {
			die();
			return $this->view->use_layout('header_content_footer')
				->add_block( 'content', 'payments/denied', array( ) );
		}
	}
	public function destroy() {
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
	}
	public function query() {
		$this->validation();

		if( AT_Session::get_instance()->userdata('paymentMethod') === 'paypal' ) {
			//Mainly we need 4 variables from product page Item Name, Item Price, Item Number and Item Quantity.
			$planID=AT_Session::get_instance()->userdata('paymentPlanID');
			$plan = $this->core->get_option( 'merchant_plan', array() );
			$plan = $plan[$planID];

			$ItemName 		= $plan['name'];
			$ItemPrice 		= number_format ($plan['rate'],2);
			$ItemNumber 	= AT_Session::get_instance()->userdata('paidEntityID'); //Item Number
			$ItemDesc 		= $plan['name'] . __('for ',AT_TEXTDOMAIN) . '#' . AT_Session::get_instance()->userdata('paidEntityID');
			$ItemQty 		= 1; // Item Quantity
			$ItemTotalPrice = ($ItemPrice*$ItemQty); //(Item Price x Quantity = Total) Get total amount of product; 
			
			//Other important variables like tax, shipping cost
			$TotalTaxAmount 	= 0.00;
			$HandalingCost 		= 0.00;
			$InsuranceCost 		= 0.00;
			$ShippinDiscount 	= 0.00;
			$ShippinCost 		= 0.00;
			
			$GrandTotal = ($ItemTotalPrice + $TotalTaxAmount + $HandalingCost + $InsuranceCost + $ShippinCost + $ShippinDiscount);
			
			//Parameters for SetExpressCheckout, which will be sent to PayPal
			$this->nvp ='&METHOD=SetExpressCheckout'.
						'&RETURNURL='.urlencode($this->urlReturn).
						'&CANCELURL='.urlencode($this->urlCancel).
						'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
						
						'&L_PAYMENTREQUEST_0_NAME0='.urlencode($ItemName).
						'&L_PAYMENTREQUEST_0_NUMBER0='.urlencode($ItemNumber).
						'&L_PAYMENTREQUEST_0_DESC0='.urlencode($ItemDesc).
						'&L_PAYMENTREQUEST_0_AMT0='.urlencode($ItemPrice).
						'&L_PAYMENTREQUEST_0_QTY0='. urlencode($ItemQty).
						
						'&NOSHIPPING=1'. //set 1 to hide buyer's shipping address, in-case products that does not require shipping
						
						'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).
						'&PAYMENTREQUEST_0_TAXAMT='.urlencode($TotalTaxAmount).
						'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($ShippinCost).
						'&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($HandalingCost).
						'&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($ShippinDiscount).
						'&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($InsuranceCost).
						'&PAYMENTREQUEST_0_AMT='.urlencode($GrandTotal).
						'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($this->core->get_option( 'paypal_currency_code', 'USD' )).
						'&LOCALECODE='. $this->lang . //PayPal pages to match the language on your website.
						'&LOGOIMG='. $this->core->get_option( 'header_logo_src' ) . //site logo
						'&CARTBORDERCOLOR=FFFFFF'. //border color of cart
						'&ALLOWNOTE=1';
				//We need to execute the "SetExpressCheckOut" method to obtain paypal token
				$response = $this->connect();

				$data = array(
					'uid' => AT_Common::get_logged_user_id(),
					'tid' => '',
					'sid' => 0,
					'amount' => number_format($GrandTotal,0, '.', ''),
					'ack' => $response["ACK"],
					'msg' => $response["L_SHORTMESSAGE0"],
					'entity' => AT_Session::get_instance()->userdata('paidEntity'),
					'entity_id' => AT_Session::get_instance()->userdata('paidEntityID'),
					'created_at' => date('Y-m-d H:s:i'),
				);


				$payments_model = $this->load->model( 'payments_model' );
				$transaction_id = $payments_model->insert_transaction( $data );

				// Register session
				AT_Session::get_instance()->set_userdata('recent_transaction_id',$transaction_id);
				// AT_Session::get_instance()->userdata('recent_transaction_id'),

				//Respond according to message we receive from Paypal
				if("SUCCESS" == strtoupper($response["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($response["ACK"]))
				{

							  // `id` int(11) NOT NULL AUTO_INCREMENT,
							  // `uid` int(11) NOT NULL,
							  // `tid` varchar(128) NOT NULL,
							  // `sid` tinyint(1) DEFAULT '0',
							  // `amount` varchar(50) NOT NULL,
							  // `ack` varchar(50) NULL,
							  // `msg` varchar(255) NOT NULL,
							  // `token` varchar(128) NULL,
							  // `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
							  // `completed_at` datetime DEFAULT NULL,
							  // `timestamp` datetime DEFAULT NULL,

					$data = array(
						'uid' => AT_Common::get_logged_user_id(),
						'tid' => '',
						'sid' => 0,
						'amount' => number_format($GrandTotal,0, '.', ''),
						'ack' => $response["ACK"],
						'msg' => $response["L_SHORTMESSAGE0"],
						'token' => $response["TOKEN"],
						'created_at' => date('Y-m-d H:s:i'),
						'timestamp' => $response["TIMESTAMP"],
					);

					$payments_model->update_transaction( $transaction_id, $data );

					//Redirect user to PayPal store with Token received.

					$paypalurl ='https://www.' . ( ( $this->mode =='sandbox' ) ? 'sandbox' : '' ) . '.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$response["TOKEN"];
					echo $paypalurl;

					header('Location: '.$paypalurl);

					// echo $paypalurl;

				} else {
					//Show error message
					// $this->destroy();
					$data = array(
						'sid' => 3,
						'ack' => $httpParsedResponseAr["ACK"],
						'msg' => $httpParsedResponseAr["L_SHORTMESSAGE0"],
					);

					$payments_model->update_transaction( $transaction_id, $data );

					$this->view->use_layout('header_content_footer')
						->add_block( 'content', 'payments/paypal/error', array( 'response' => $response, 'msg' => $response["L_LONGMESSAGE0"] ) );		

				}

		} else {
			$this->view->use_layout('header_content_footer')
				->add_block( 'content', 'payments/denied', array( ) );		
		}

	}

	public function callback() {

		$this->validation();

		//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID


		if(isset($_GET["token"]) && isset($_GET["PayerID"])) {

			$payments_model = $this->load->model( 'payments_model' );

			//we will be using these two variables to execute the "DoExpressCheckoutPayment"
			//Note: we haven't received any payment yet.
			
			$token = $_GET["token"];
			$payer_id = $_GET["PayerID"];
			
			//get session variables


			$planID=AT_Session::get_instance()->userdata('paymentPlanID');
			$plan = $this->core->get_option( 'merchant_plan', array() );
			$plan = $plan[$planID];

			$ItemName 		= $plan['name'];
			$ItemPrice 		= number_format ($plan['rate'],2);
			$ItemNumber 	= AT_Session::get_instance()->userdata('paidEntityID'); //Item Number
			$ItemDesc 		= $plan['name'] . __('for ',AT_TEXTDOMAIN) . '#' . AT_Session::get_instance()->userdata('paidEntityID');
			$ItemQty 		= 1; // Item Quantity
			$ItemTotalPrice = ($ItemPrice*$ItemQty); //(Item Price x Quantity = Total) Get total amount of product; 
			
			//Other important variables like tax, shipping cost
			$TotalTaxAmount 	= 0.00;
			$HandalingCost 		= 0.00;
			$InsuranceCost 		= 0.00;
			$ShippinDiscount 	= 0.00;
			$ShippinCost 		= 0.00;
			
			$GrandTotal = ($ItemTotalPrice + $TotalTaxAmount + $HandalingCost + $InsuranceCost + $ShippinCost + $ShippinDiscount);

			$padata = 	'&TOKEN='.urlencode($token).
						'&PAYERID='.urlencode($payer_id).
						'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").

						'&RETURNURL='.urlencode($this->urlReturn).
						'&CANCELURL='.urlencode($this->urlCancel).

						//set item info here, otherwise we won't see product details later	
						'&L_PAYMENTREQUEST_0_NAME0='.urlencode($ItemName).
						'&L_PAYMENTREQUEST_0_NUMBER0='.urlencode($ItemNumber).
						'&L_PAYMENTREQUEST_0_DESC0='.urlencode($ItemDesc).
						'&L_PAYMENTREQUEST_0_AMT0='.urlencode($ItemPrice).
						'&L_PAYMENTREQUEST_0_QTY0='. urlencode($ItemQty).

						'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).
						'&PAYMENTREQUEST_0_TAXAMT='.urlencode($TotalTaxAmount).
						'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($ShippinCost).
						'&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($HandalingCost).
						'&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($ShippinDiscount).
						'&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($InsuranceCost).
						'&PAYMENTREQUEST_0_AMT='.urlencode($GrandTotal).
						'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($this->core->get_option( 'paypal_currency_code', 'USD' ));
			
			//We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
			// $paypal= new MyPayPal();
			$this->method = 'DoExpressCheckoutPayment';
			$this->nvp = $padata;
			$httpParsedResponseAr = $this->connect();


			//Check if everything went ok..
			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
			{

					$remote_transaction_id = isset($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]) ? $httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"] : false;

					$data = array(
						'sid' => 2,
						'ack' => $httpParsedResponseAr["ACK"],
						'msg' => '',
						'payerid' => $payer_id,
						'timestamp' => $httpParsedResponseAr["TIMESTAMP"],
					);

					$payments_model->update_transaction_by_token( $token, $data );

					// $this->view->use_layout('header_content_footer')
					// 	->add_block( 'content', 'payments/paypal/success', array( 'response' => $httpParsedResponseAr, 'transaction_id' => $httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"] ) );

					
						/*
						//Sometimes Payment are kept pending even when transaction is complete. 
						//hence we need to notify user about it and ask him manually approve the transiction
						*/
						
						if(isset($httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]) && 'Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"])
						{


							$data = array(
								'sid' => 1,
								'payerid' => $payer_id,
								'completed_at' => date('Y-m-d H:s:i'),
							);

							$payments_model->update_transaction_by_token( $token, $data );

							//AT_Session::get_instance()->set_userdata('paypal_transaction_id',$httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);

							// $this->destroy();

							AT_Common::redirect( 'payments/success' );
							// $this->view->use_layout('header_content_footer')
							// 	->add_block( 'content', 'payments/success', array( 'response' => $httpParsedResponseAr, 'transaction_id' => $remote_transaction_id ) );

						}
						elseif(isset($httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]) && 'Pending' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"])
						{
							// AT_Session::get_instance()->set_userdata('paypal_transaction_id',$httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);

							// $this->destroy();

							$this->view->use_layout('header_content_footer')
								->add_block( 'content', 'payments/paypal/pending', array( 'response' => $httpParsedResponseAr, 'transaction_id' => $remote_transaction_id ) );
						}

						// we can retrive transection details using either GetTransactionDetails or GetExpressCheckoutDetails
						// GetTransactionDetails requires a Transaction ID, and GetExpressCheckoutDetails requires Token returned by SetExpressCheckOut



						// $padata =           
						//                         '&TOKEN='.urlencode($token).
						//                         '&PAYERID='.urlencode($payer_id).
						//                         '&PAYMENTACTION='.urlencode("SALE").
						//                         '&AMT='.urlencode($GrandTotal).
						//                         '&CURRENCYCODE='.urlencode($this->core->get_option( 'paypal_currency_code', 'USD' ));

						// $padata = '&TOKEN='.urlencode($token);
						// $paypal= new MyPayPal();
						// DoExpressCheckoutPayment
						$this->method = 'GetExpressCheckoutDetails';
						$this->nvp = $padata;
						$httpParsedResponseAr = $this->Connect();

						// $httpParsedResponseAr = $paypal->PPHttpPost('GetExpressCheckoutDetails', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

						if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
						{


							$data = array(
								'sid' => 1,
								'ack' => $httpParsedResponseAr["ACK"],
								'msg' => '',
								'payerid' => $payer_id,
								'completed_at' => date('Y-m-d H:s:i'),
							);
							$payments_model->update_transaction_by_token( $token, $data );

							// $this->destroy();

							AT_Common::redirect( 'payments/success' );

							// $this->view->use_layout('header_content_footer')
							// 	->add_block( 'content', 'payments/success', array( 'response' => $httpParsedResponseAr, 'transaction_id' => $remote_transaction_id ) );

							// echo '<br /><b>Stuff to store in database :</b><br /><pre>';							
							// echo '<pre>';
							// print_r($httpParsedResponseAr);
							// echo '</pre>';
						} else  {

							// $this->destroy();

							$data = array(
								'sid' => 3,
								'ack' => $httpParsedResponseAr["ACK"],
								'payerid' => $payer_id,
								'msg' => $httpParsedResponseAr["L_SHORTMESSAGE0"],
							);
							$payments_model->update_transaction_by_token( $token, $data );

							$this->view->use_layout('header_content_footer')
								->add_block( 'content', 'payments/paypal/error', array( 'response' => $httpParsedResponseAr, 'msg' => $httpParsedResponseAr["L_LONGMESSAGE0"] ) );		

							// echo '<div style="color:red"><b>GetTransactionDetails failed:</b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
							// echo '<pre>';
							// print_r($httpParsedResponseAr);
							// echo '</pre>';

						}
			
			}else{
					// $this->destroy();

					$data = array(
						'sid' => 3,
						'ack' => $httpParsedResponseAr["ACK"],
						'msg' => $httpParsedResponseAr["L_SHORTMESSAGE0"],
					);
					$payments_model->update_transaction_by_token( $token, $data );
					// $payments_model->update_transaction( $transaction_id, $data );

					$this->view->use_layout('header_content_footer')
						->add_block( 'content', 'payments/paypal/error', array( 'response' => $httpParsedResponseAr, 'msg' => $httpParsedResponseAr["L_LONGMESSAGE0"] ) );		
					// echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
					// echo '<pre>';
					// print_r($httpParsedResponseAr);
					// echo '</pre>';
			}
		}
	}
	public function connect() {
			$this->validation();

			// Set up your API credentials, PayPal end point, and API version.
			$API_UserName = urlencode($this->username);
			$API_Password = urlencode($this->password);
			$API_Signature = urlencode($this->signature);

			$this->urlPrefix = ($this->mode =='sandbox') ? 'api-3t.sandbox' : 'api-3t';
	
			$API_Endpoint = "https://".$this->urlPrefix.".paypal.com/nvp";
			// echo $API_Endpoint;
			$version = urlencode($this->version);
		
			// Set the curl parameters.
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
		
			// Turn off the server and peer verification (TrustManager Concept).
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
		
			$this->method = 'SetExpressCheckout';

			// Set the API operation, version, and API signature in the request.
			$nvpreq = "METHOD=" . $this->method . "&VERSION=" . $this->version . "&PWD={$API_Password}&USER={$API_UserName}&SIGNATURE={$API_Signature}" . $this->nvp;
		
			// Set the request as a POST FIELD for curl.
			curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
		
			// Get response from the server.
			$httpResponse = curl_exec($ch);
		
			if(!$httpResponse) {
				exit("{$this->method} failed: ".curl_error($ch).'('.curl_errno($ch).')');
			}
		
			// Extract the response details.
			$httpResponseAr = explode("&", $httpResponse);
		
			$httpParsedResponseAr = array();
			foreach ($httpResponseAr as $i => $value) {
				$tmpAr = explode("=", $value);
				if(sizeof($tmpAr) > 1) {
					$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
				}
			}
		
			if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
				exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
			}
		return $httpParsedResponseAr;
	}
}

// Preparing first call
// $at_paypal = new AT_Paypal();
// $at_paypal->bringUp();

// // Sending Query
// if( isset($_POST["checkout"]) && isset($_POST["merchantID"]) && $_POST["merchantID"] == "PayPal" ) {
// 	$at_paypal->query();
// }

// // Receiving Response
// if(isset($_GET["token"]) && isset($_GET["PayerID"])) {
// 	$at_paypal->callback();
// }
