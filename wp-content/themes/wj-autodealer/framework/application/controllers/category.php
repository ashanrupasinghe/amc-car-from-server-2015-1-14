<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Category extends AT_Controller{
	public function __construct() {
		parent::__construct();
	}

	public function index(){
		$this->view->use_layout('header_content_footer')
			->add_block('content', 'blog/view');
	}

}