<?php
if (!defined("AT_DIR")) die('!!!');

class AT_mail_model extends AT_Model{

	public function getTemplate( $template_name, $data = array() ) {
		$subject = '';
		$content = $this->core->get_option( $template_name, '' );
		if ( empty( $content ) ) {
			switch ( $template_name ) {
				case 'template_mail_recovery_password':
					$content = 'Dear, %username%<br/><br/>Your link to the password recovery: <a href="%recovery_link%">link</a>.<br/>Code: %recovery_code%<br/><br/>AutoDealer';
					break;
				case 'template_mail_add_offer':
					$content = 'Dear, %dealer_name%<br/><br/>Username: %username%<br/>User email: %user_email%<br />Car: %car_name%<br/>Cost: %cost%<br/>Offer details: %offer_details%<br/><a href="%link_car%">Reference to car</a><br/><br/>AutoDealer';
					break;
				case 'template_mail_notify_want_be_dealer':
					$content = 'I want to be a dealer.<br/><br/>Username: %username%<br/>Comment: %comment%<br/><br/>AutoDealer';
					break;
				case 'template_mail_confirm_email':
					$content = 'Dear %username%,<br/><br/>Link to email confirm: <a href="%confirm_url%">link</a>.<br/>Confirm Code: %confirm_code%<br/><br/>Auto Dealer';
					break;
				default:
					break;
			}
		}
		switch ( $template_name ) {
			case 'template_mail_recovery_password':
				$subject = __( 'Recovery password', AT_TEXTDOMAIN );
				break;
			case 'template_mail_add_offer':
				$subject = __( 'Add offer', AT_TEXTDOMAIN );
				break;
			case 'template_mail_notify_want_be_dealer':
				$subject = __( 'I want to be a dealer', AT_TEXTDOMAIN );
				break;
			case 'template_mail_confirm_email':
				$subject = __( 'Confirm email', AT_TEXTDOMAIN );
				break;
			default:
				break;
		}
		foreach ($data as $key => $placeholder) {
			$content = str_replace( '%' . $key . '%', $placeholder, $content );
		}
		return array( 'subject' => $subject, 'content' => $content );
	}

	public function send( $template_name, $recipient_email, $data, $sender_email = '', $sender_name = '' ){
		$template = $this->getTemplate( $template_name, $data );
		add_filter( 'wp_mail_content_type', array( 'AT_mail_model', 'set_html_content_type' ) );
		$headers = '';
		if ( $sender_email != '' ) {
		//	$headers .= 'From: ' . $sender_name . ' <' . $sender_email . '>' . "\r\n";
		} elseif ( $this->core->get_option( 'sender_email' ) ) {
		//	$headers .= 'From: ' . htmlspecialchars( $this->core->get_option( 'sender_name' ) ) . ' <' . $this->core->get_option( 'sender_email' ) . '>' . "\r\n";
		}
		return wp_mail( $recipient_email, stripslashes($template['subject']), stripslashes($template['content']), $headers );
		
	}

	static public function set_html_content_type(){
		return 'text/html';
	}
}