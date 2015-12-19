<?php
if (!defined("AT_DIR")) die('!!!');
/////////////////////////////////////////////////////////////////////
// main page
/////////////////////////////////////////////////////////////////////
class AT_Welcome extends AT_Controller{
	public function __construct() {
		parent::__construct();
		if($this->core->get_option( 'site_type', 'mode_soletrader') != 'mode_partnership' && $this->uri->segments( 1 ) != 'unlogged' )
			AT_Core::show_404();
	}

	public function index(){
		$this->view->use_layout('header_content_footer');
		$this->view->add_block( 'content', 'welcome/home' );
	}
}