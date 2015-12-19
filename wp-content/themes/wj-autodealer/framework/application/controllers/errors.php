<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Errors extends AT_Controller{
	public function __construct() {
		parent::__construct();
	}

	public function show_404(){
		if (is_admin()) return;
		header("HTTP/1.0 404 Not Found");
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found"); 
		AT_Core::$is_404 = true;
		$this->view->use_layout('header_content_footer');
		$this->view->add_block( 'content', 'errors/404' );
		$this->view->render()->display();
		die();
	}

	public function show_underconstruction(){
		if (is_admin()) return;

		$protocol = "HTTP/1.0";
		if ( "HTTP/1.1" == $_SERVER["SERVER_PROTOCOL"] ) {
		  $protocol = "HTTP/1.1";
		}


		// header( $protocol . ' 503 Service Temporarily Unavailable');
		// header('Status: 503 Service Temporarily Unavailable');
		$datetime_retry_after = $this->core->get_option( 'datetime_retry_after' );
		if (!empty($datetime_retry_after) && strlen($datetime_retry_after) == 16) {
			$date = new DateTime( $datetime_retry_after );
			$now = new DateTime( 'now' );
			header('Retry-After: ' . $datetime_retry_after );
			// header('Retry-After: ' . $date->getTimestamp() - $now->getTimestamp() );
			$diff = $date->diff( $now );
			$counter_options = array(
				'months' => $diff->format("%m"),
				'days' => $diff->format("%d"),
				'hours' => $diff->format("%H"),
				'minutes' => $diff->format("%i"),
				'seconds' => $diff->format("%S"),
			);
		} else {
			header('Retry-After: 300');//300 seconds
			$counter_options = array();
		}

		AT_Core::$is_404 = true;
		$this->view->use_layout('header_content_footer');
		$this->view->add_block( 'content', 'errors/underconstruction', array( 'counter_options' => $counter_options ) );
		$this->view->render()->display();
		die();
	}
}