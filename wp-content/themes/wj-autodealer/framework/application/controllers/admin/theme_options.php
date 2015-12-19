<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Theme_options extends AT_Admin_Controller{

	protected $_block_id = 0;
	protected $_get_params = false;
	
	public function __construct() {
		parent::__construct();

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
			'default_page_layout' =>
				array(
					'type' => 'radio_image',
					'title' => __( 'Specify page layout:', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'You may specify custom default page layout. For example, you see single column layout, layout wiht right or left sidebar and double column layout.', AT_ADMIN_TEXTDOMAIN ),
					'items' => 
						array(
							'content' => AT_URI . '/assets/images/admin/layouts/page/1.png',
							'left_content' => AT_URI . '/assets/images/admin/layouts/page/2.png',
							'content_right' => AT_URI . '/assets/images/admin/layouts/page/3.png',
						),
					'default' => 'content'
				),
			'disable_breadcrumbs' =>
				array(
					'type' => 'checkbox',
					'title' => __( 'Disable Breadcrumbs', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Check this option to disable breadcrumbs.', AT_ADMIN_TEXTDOMAIN ),
					'default' => false
				),
			'disable_responsiveness' =>
				array(
					'type' => 'checkbox',
					'title' => __( 'Disable Responsiveness', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Check this option to disable responsiveness.', AT_ADMIN_TEXTDOMAIN ),
					'default' => false
				),
			'breadcrumb_delimiter' =>
				array(
					'type' => 'input_text',
					'title' => __( 'Breadcrumb Delimiter', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Delimiter will separate breadcrumbs locations. Example:<br/><code>/ &gt; - → , | &dot; &gt;&gt;</code>', AT_ADMIN_TEXTDOMAIN ),
					'default' => '/'
				),
			'currency_location' =>
				array(
					'type' => 'radio',
					'title' => __( 'Currency sign location', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Specify currency sign location. Example: $3,500 or 3,500$', AT_ADMIN_TEXTDOMAIN ),
					'items' => 
						array(
							1 => __( 'Left', AT_ADMIN_TEXTDOMAIN ),
							2 => __( 'Right', AT_ADMIN_TEXTDOMAIN ),
						),
					'default' => 1
				),
			'currency_style' =>
				array(
					'type' => 'radio',
					'title' => __( 'Currency display', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Specify currency display overall look. Example: 3,500$ or 3,500USD', AT_ADMIN_TEXTDOMAIN ),
					'items' => 
						array(
							'name' => __( 'Display Code.', AT_ADMIN_TEXTDOMAIN ),
							'alias' => __( 'Display Sign ($,£,¥)', AT_ADMIN_TEXTDOMAIN ),
						),
					'default' => 'alias'
				),
			'favicon' =>
				array(
					'type' => 'upload',
					'id' => 'favicon',
					'title' => __( 'Custom Favicon', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Upload your favicon.', AT_ADMIN_TEXTDOMAIN ),
					'default' => ''
				),
			'google_analytics' =>
				array(
					'type' => 'textarea',
					'title' => __( 'Google Analytics', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Paste Google Analytics code here.', AT_ADMIN_TEXTDOMAIN ),
					'class' => 'code',
					'default' => ''
				),
			'custom_css' =>
				array(
					'type' => 'textarea',
					'title' => __( 'Custom CSS', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Place for custom styles. This option may customise any style of the website. Example:<br/><code>.logo a { color: blue; }</code>', AT_ADMIN_TEXTDOMAIN ),
					'class' => 'code',
					'default' => ''
				),
			'custom_js' =>
				array(
					'type' => 'textarea',
					'title' => __( 'Custom JavaScript', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Place for custom javascript. Required some skills. Example:<br/><code>alert("Hello");</code>', AT_ADMIN_TEXTDOMAIN ),
					'class' => 'code',
					'default' => ''
				),
		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/theme_options/content', array( 'title' => __( 'General options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'general' ));
		}
		return $fields;
	}

	public function header(){
		$fields = array(
			'logo_settings' =>
				array(
					'type' => 'radio',
					'title' => __( 'Logo Settings', AT_ADMIN_TEXTDOMAIN ),
					'description' => 'Additional logo options.',
					'items' => 
						array(
							'logo_image' => 'Custom Image Logo',
							// 'logo_text' => 'Custom Text Logo',
							// 'logo_site' => 'Display Site Title (<a href="options-general.php">click here to edit site title)</a>',
						),
					'default' => 'logo_image'
				),
			'header_logo_src' =>
				array(
					'type' => 'upload',
					'id' => 'header_logo_src',
					'title' => __( 'Custom Image Logo', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Upload your logo. PNG recommended.', AT_ADMIN_TEXTDOMAIN ),
					'default' => ''
				),
			'header_logo_width' => 
				array(
					'type' => 'input_text',
					'title' => __( 'Custom Image Logo Width', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Enter logotype width in pixels.', AT_ADMIN_TEXTDOMAIN ),
					'default' => ''
				),
			'header_logo_text' =>
				array(
					'type' => 'input_text',
					'title' => __( 'Custom Text Logo', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Leave blank to remove.', AT_ADMIN_TEXTDOMAIN ),
					'default' => ''
				),

			'header_content_style' =>
				array(
					'type' => 'radio',
					'title' => __( 'Header Style', AT_ADMIN_TEXTDOMAIN ),
					'description' => 'Additional logo options.',
					'items' => 
						array(
							'info' => 'Company information',
							'html' => 'Custom HTML (perfect for 468x60 ads)',
							// 'logo_site' => 'Display Site Title (<a href="options-general.php">click here to edit site title)</a>',
						),
					'toggle' => 'toggle_true',
					'default' => 'info'
				),


			'header_phone' => 
				array(
					'type' => 'input_text',
					'title' => __( 'Header Phone', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Display phone number inside header. Leave blank to remove.', AT_ADMIN_TEXTDOMAIN ),
					'toggle_class' => 'header_content_style_info',
					'default' => ''
				),
			'header_adress' => 
				array(
					'type' => 'input_text',
					'title' => __( 'Header Company Location', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Display company location in site header. Leave blank to remove.', AT_ADMIN_TEXTDOMAIN ),
					'toggle_class' => 'header_content_style_info',
					'default' => ''
				),
			'header_add_car_button' => 
				array(
					'type' => 'checkbox',
					'title' => __( 'Enabled Header Add Car Button', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Check this option to enable "Add Car" button inside header.', AT_ADMIN_TEXTDOMAIN ),
					'toggle_class' => 'header_content_style_info',
					'default' => true
				),
			'header_sociable_view' =>
				array(
					'type' => 'checkbox',
					'title' => __( 'Enable Header Sociable', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Check this option to enable sociable inside header.', AT_ADMIN_TEXTDOMAIN ),
					'toggle_class' => 'header_content_style_info',
					'default' => true
				),

			'header_custom_html' =>
				array(
					'type' => 'textarea',
					'title' => __( 'Header Custom HTML', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Paste ads or custom HTML code here.', AT_ADMIN_TEXTDOMAIN ),
					'toggle_class' => 'header_content_style_html',
					'default' => ''
				),

			'header_searchbox' =>
				array(
					'type' => 'checkbox',
					'title' => __( 'Enable Header Search', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Check this option to enable content search box in website header.', AT_ADMIN_TEXTDOMAIN ),
					'default' => true
				),

		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/theme_options/content', array( 'title' => __( 'Header options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'header' ));
		}
		return $fields;
	}

	public function footer(){
		$fields = array(
			'footer_layout_top' =>
				array(
					'type' => 'radio_image',
					'title' => __( 'Footer Column Layout Top', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Select which column layout you would like to display with your footer.', AT_ADMIN_TEXTDOMAIN ),
					'items' => 
						array(
							'layout_100' => AT_URI . '/assets/images/admin/layouts/footer/layout_100.png',
							'layout_50_50' => AT_URI . '/assets/images/admin/layouts/footer/layout_50_50.png',
							'layout_25_25_50' => AT_URI . '/assets/images/admin/layouts/footer/layout_25_25_50.png',
							'layout_50_25_25' => AT_URI . '/assets/images/admin/layouts/footer/layout_50_25_25.png',
							'layout_25_75' => AT_URI . '/assets/images/admin/layouts/footer/layout_25_75.png',
							'layout_75_25' => AT_URI . '/assets/images/admin/layouts/footer/layout_75_25.png',
							'layout_25_25_25_25' => AT_URI . '/assets/images/admin/layouts/footer/layout_25_25_25_25.png',
						),
					'default' => 'layout_25_75'
				),
			'footer_layout_bottom' =>
				array(
					'type' => 'radio_image',
					'title' => __( 'Footer Column Layout Bottom', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Select which column layout you would like to display with your footer.', AT_ADMIN_TEXTDOMAIN ),
					'items' => 
						array(
							'layout_100' => AT_URI . '/assets/images/admin/layouts/footer/layout_100.png',
							'layout_50_50' => AT_URI . '/assets/images/admin/layouts/footer/layout_50_50.png',
							'layout_25_25_50' => AT_URI . '/assets/images/admin/layouts/footer/layout_25_25_50.png',
							'layout_50_25_25' => AT_URI . '/assets/images/admin/layouts/footer/layout_50_25_25.png',
							'layout_25_75' => AT_URI . '/assets/images/admin/layouts/footer/layout_25_75.png',
							'layout_75_25' => AT_URI . '/assets/images/admin/layouts/footer/layout_75_25.png',
							'layout_25_25_25_25' => AT_URI . '/assets/images/admin/layouts/footer/layout_25_25_25_25.png',
						),
					'default' => 'layout_25_25_25_25'
				),
			'footer_logo_src' =>
				array(
					'type' => 'upload',
					'id' => 'footer_logo_src',
					'title' => __( 'Custom Footer Image Logo', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Upload an image to use as your footer logo for widget area. Please use high resolutions fot Retina ready devices.', AT_ADMIN_TEXTDOMAIN ),
					'default' => ''
				),
			'footer_sociable_view' =>
				array(
					'type' => 'checkbox',
					'title' => __( 'Enable Footer Sociable', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Check this option to enable sociable from footer.', AT_ADMIN_TEXTDOMAIN ),
					'default' => false
				),
			'footer_copyright_text' => 
				array(
					'type' => 'input_text',
					'title' => __( 'Copyright Text', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'You can display copyright information here. It will show below your footer on the right hand side.', AT_ADMIN_TEXTDOMAIN ),
					'default' => ''
				),
		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/theme_options/content', array( 'title' => __( 'Footer options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'footer' ));
		}
		return $fields;
	}

	public function sociable(){
		$fields = array(
			'sociable' => 
				array(
					'type' => 'group',
					'title' => __( 'Sociable', AT_ADMIN_TEXTDOMAIN ),
					'description' => '',
					'submit' => __( "Add New Sociable", AT_ADMIN_TEXTDOMAIN ),
					'default' => array(),
					'fields' => array(
						'icon' =>
							array(
								'type' => 'select',
								'title' => __( 'Sociable Icon', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'Select social network icon.', AT_ADMIN_TEXTDOMAIN ),
								'items' => AT_Common::get_icons('social'),
								'default' => ''
							),
						'link' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Sociable Link', AT_ADMIN_TEXTDOMAIN ),
								'description' => __( 'Add your custom URL to your network profile.', AT_ADMIN_TEXTDOMAIN ),
								'default' => ''
							),
					)
				)
		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/theme_options/content', array( 'title' => __( 'Sociable options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'sociable' ));
		}
		return $fields;
	}
	
	public function styled(){
		$fields = array(
			'custom_styled_css' => array(
				'type' => 'select',
				'title' => __( 'Styles', AT_ADMIN_TEXTDOMAIN ),
				'description' => __( 'Select theme look.', AT_ADMIN_TEXTDOMAIN ),
				'items' => array(
					'style1.css' => __( 'Primary (BETA)', AT_ADMIN_TEXTDOMAIN )
				),
				'default' => ''
			),
		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/theme_options/content', array( 'title' => __( 'Styled options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'styled' ));
		}
		return $fields;
	}

	public function blog(){
		$fields = array(
			'blog_title' =>
				array(
					'type' => 'input_text',
					'title' => __( 'Blog Title', AT_ADMIN_TEXTDOMAIN ),
					'description' => '',
					'default' => 'Blog'
				),
			'blog_layout' => 
					array(
						'type' => 'radio_image',
						'title' => __( 'Layout', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( 'You can choose between a left, right, or no sidebar layout for your blog page.', AT_ADMIN_TEXTDOMAIN ),
						'items' => 
							array(
								'content' => AT_URI . '/assets/images/admin/layouts/page/1.png',
								'left_content' => AT_URI . '/assets/images/admin/layouts/page/2.png',
								'content_right' => AT_URI . '/assets/images/admin/layouts/page/3.png',
							),
						'default' => 'left_content'
					),
			'blog_custom_sidebar' => 
					array(
						'type' => 'select',
						'title' => __( 'Custom Sidebar', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "Select the custom sidebar that you'd like to be displayed on this page.<br /><br />Note:  You will need to first create a custom sidebar under the &quot;Sidebar&quot; tab in your theme's option panel before it will show up here.", AT_ADMIN_TEXTDOMAIN ),
						'items' => AT_Sidebars::get_custom_sidebars(),
						'default' => ''
					),

		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/theme_options/content', array( 'title' => __( 'Blog options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'blog' ));
		}
		return $fields;
	}

	public function news(){
		$fields = array(
			'news_title' =>
				array(
					'type' => 'input_text',
					'title' => __( 'News Title', AT_ADMIN_TEXTDOMAIN ),
					'description' => '',
					'default' => 'News'
				),
			'news_layout' => 
					array(
						'type' => 'radio_image',
						'title' => __( 'Layout', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( 'You can choose between a left, right, or no sidebar layout for your news page.', AT_ADMIN_TEXTDOMAIN ),
						'items' => 
							array(
								'content' => AT_URI . '/assets/images/admin/layouts/page/1.png',
								'left_content' => AT_URI . '/assets/images/admin/layouts/page/2.png',
								'content_right' => AT_URI . '/assets/images/admin/layouts/page/3.png',
							),
						'default' => 'left_content'
					),
			'news_custom_sidebar' => 
					array(
						'type' => 'select',
						'title' => __( 'Custom Sidebar', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "Select the custom sidebar that you'd like to be displayed on this page.<br /><br />Note:  You will need to first create a custom sidebar under the &quot;Sidebar&quot; tab in your theme's option panel before it will show up here.", AT_ADMIN_TEXTDOMAIN ),
						'items' => AT_Sidebars::get_custom_sidebars(),
						'default' => ''
					),

		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/theme_options/content', array( 'title' => __( 'News options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'news' ));
		}
		return $fields;
	}

	public function reviews(){
		$fields = array(
			'reviews_title' =>
				array(
					'type' => 'input_text',
					'title' => __( 'Reviews Title', AT_ADMIN_TEXTDOMAIN ),
					'description' => '',
					'default' => 'Reviews'
				),
			'reviews_layout' => 
					array(
						'type' => 'radio_image',
						'title' => __( 'Layout', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( 'You can choose between a left, right, or no sidebar layout for your reviews page.', AT_ADMIN_TEXTDOMAIN ),
						'items' => 
							array(
								'content' => AT_URI . '/assets/images/admin/layouts/page/1.png',
								'left_content' => AT_URI . '/assets/images/admin/layouts/page/2.png',
								'content_right' => AT_URI . '/assets/images/admin/layouts/page/3.png',
							),
						'default' => 'left_content'
					),
			'reviews_custom_sidebar' => 
					array(
						'type' => 'select',
						'title' => __( 'Custom Sidebar', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "Select the custom sidebar that you'd like to be displayed on this page.<br /><br />Note:  You will need to first create a custom sidebar under the &quot;Sidebar&quot; tab in your theme's option panel before it will show up here.", AT_ADMIN_TEXTDOMAIN ),
						'items' => AT_Sidebars::get_custom_sidebars(),
						'default' => ''
					),

		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/theme_options/content', array( 'title' => __( 'Reviews options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'reviews' ));
		}
		return $fields;
	}
	
	public function sidebars(){
		$fields = array(
			'custom_sidebars' => 
				array(
					'type' => 'group',
					'title' => __( 'Create New Sidebar', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'You can create additional sidebars to use.<br>To display your new sidebar then you will need to select it in the "Custom Sidebar" dropdown when editing a post or page.', AT_ADMIN_TEXTDOMAIN ),
					'submit' => __( "Add Sidebar", AT_ADMIN_TEXTDOMAIN ),
					'default' => array(),
					'fields' => array(
						'name' =>
							array(
								'type' => 'input_text',
								'title' => __( 'Sidebar Name', AT_ADMIN_TEXTDOMAIN ),
								'description' => '',
								'default' => ''
							),
					)
				)
		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/theme_options/content', array( 'title' => __( 'Sidebars options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'sidebars' ));
		}
		return $fields;
	}

	public function backup(){
		$fields = array(
			'restore' =>
				array(
					'type' => 'restore',
					'title' => __( 'Import Theme & Site Options', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'Copy your export code here to import your theme settings', AT_ADMIN_TEXTDOMAIN ),
					'default' => ''
				),
			'backup' =>
				array(
					'type' => 'backup',
					'title' => __( 'Export Theme & Site Options', AT_ADMIN_TEXTDOMAIN ),
					'description' => __( 'When moving your site to a new Wordpress installation you can export your theme settings here.', AT_ADMIN_TEXTDOMAIN ),
					'default' => ''
				),
		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/theme_options/content', array( 'title' => __( 'Backup options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'backup' ));
		}
		return $fields;
	}

	public function support(){
		$fields = array(
			array(
				'type' => 'info',
				'title' => __( 'Theme Documentation', AT_ADMIN_TEXTDOMAIN ),
				'description' => __( 'Documentation has been provided with main theme files. Please open Documentation folder from downloaded directory.', AT_ADMIN_TEXTDOMAIN ),
			),
			array(
				'type' => 'info',
				'title' => __( 'Theme Support', AT_ADMIN_TEXTDOMAIN ),
				'description' => __( 'We put a ton of effort into providing top-notch WordPress tech support to all of our customers. With our dedicated support staff, you can be sure that you will have your blog up and running without a hitch regardless of your experience level.
										<br/><br/>
										Our plethora of Shortcodes, Page Templates and Theme Options give you full control over your website. Manage your site like never before by using the countless powerful features that come packaged.<br/>
										<a href="http://winterjuice.com/support/">Click to add your issue</a>', AT_ADMIN_TEXTDOMAIN ),
			),
		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/theme_options/content', array( 'title' => __( 'Support options', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'support' ));
		}
		return $fields;
	}

	public function release(){
		$fields = array(
			array(
				'type' => 'info',
				'title' => __( 'Theme Release Info', AT_ADMIN_TEXTDOMAIN ),
				'description' => '
				<h3>Whats new in 1.7</h3>
				<ol>
					<li>Visual Composer 4.3.3</li>
					<li>Favicon issue in the frontend fixed</li>
					<li>HTML fixes for better responsiveness</li>
					<li>Transport Type filter issue fixed</li>
					<li>Dropdown with manual data adding added</li>
					<li>Currency favicon issue fixed</li>
					<li>"Recent listings" shortcode images issue fixed</li>
					<li>User_email for "Add offer" mail template added</li>
					<li>Added comboboxes for adding new item</li>
					<li>Improved minor design issues</li>
				</ol>
				',
			),
		);
		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/theme_options/content', array( 'title' => __( 'Release Info', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'release' ));
		}

		return $fields;
	}

	public function troubleshooting(){
		$fields = array(

			'current_theme_version' =>
				array(
					'type' => 'input_text',
					'title' => __( 'Theme Version:', AT_ADMIN_TEXTDOMAIN ),
					'description' => '',
					'default' => 'This option may help with theme downgrade.'
				),

			// array(
			// 	'type' => 'pushtocall',
			// 	'title' => __( 'Migration from 1.5 fix', AT_ADMIN_TEXTDOMAIN ),
			// 	'action' => 'fix15',
			// 	'button' => __( 'Run fix', AT_ADMIN_TEXTDOMAIN ),
			// 	'description' => '<p>In case if you have updated from 1.5 version, we recommend run this script.</p>',
			// ),

			array(
				'type' => 'info',
				'title' => __( '1.4 migrating instructions', AT_ADMIN_TEXTDOMAIN ),
				'description' => '<p>If you have installed previously version 1.4 or earlier we may recommend you perform following actions:</p>
				<p><strong>BACKUP</strong><br>
				Please login to your FTP and backup user images located at wj-autodealer/framework/usr_dat directory. Download this directory, and after that – start updating the theme.</p>
				<p><strong>ACTIONS AFTER UPDATE</strong><br>
				After theme update/installation you MUST upload directories “car”, and “user” from downloaded “usr_data” or wj-autodealer/framework/usr_dat to “wp-content/uploads/at_usr_data”. In future this will give you guarantee that after another update and even theme removal, your images will be stored in the safe place.</p>',
			),
			array(
				'type' => 'info',
				'title' => __( 'Error 404 appears', AT_ADMIN_TEXTDOMAIN ),
				'description' => '<p>If you are continuing getting an error 404 after theme update, you need to update permalinks cache. Please navigate to Dashboard -> Settings -> Permalinks and save settings again.</p>',
			),
			array(
				'type' => 'permissions_check',
				'title' => __( 'Permissions Check', AT_ADMIN_TEXTDOMAIN ),
				'description' => ''
			),
			array(
				'type' => 'extensions_check',
				'title' => __( 'PHP extensions and libraries', AT_ADMIN_TEXTDOMAIN ),
				'description' => ''
			),
			array(
				'type' => 'php_info',
				'title' => __( 'PHP Information', AT_ADMIN_TEXTDOMAIN ),
				'description' => ''
			),
		);

		if (!$this->_get_params) {
			$this->view->add_block('content', 'admin/theme_options/content', array( 'title' => __( 'Troubleshooting', AT_ADMIN_TEXTDOMAIN ), 'alias' => 'troubleshooting' ));
		}

		return $fields;
	}

/*
$fields = array(
	'group' => 
		array(
			'type' => 'group',
			'title' => __( 'Group Sample', AT_ADMIN_TEXTDOMAIN ),
			'description' => __( 'Group Description Sample', AT_ADMIN_TEXTDOMAIN ),
			'submit' => __( "Add Item", AT_ADMIN_TEXTDOMAIN ),
			'default' => array(),
			'fields' => array(
				'text' =>
					array(
						'type' => 'input_text',
						'title' => __( 'Disable Breadcrumbs', AT_ADMIN_TEXTDOMAIN ),
						'description' => __( 'Check this option to remove breadcrumbs from site.', AT_ADMIN_TEXTDOMAIN ),
						'default' => 'Lorem ipsum'
					),
			)
	),
	array(
		'type' => 'info',
		'title' => __( 'Info', AT_ADMIN_TEXTDOMAIN ),
		'description' => __( "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.", AT_ADMIN_TEXTDOMAIN ),
	),
	'select' =>
		array(
			'type' => 'select',
			'title' => __( 'Select', AT_ADMIN_TEXTDOMAIN ),
			'description' => __( 'Change select option', AT_ADMIN_TEXTDOMAIN ),
			'items' => 
				array(
					'value1' => 'Option 1',
					'value2' => 'Option 2',
					'value3' => 'Option 3',
				),
			'default' => ''
		),
	'radio_image' =>
		array(
			'type' => 'radio_image',
			'title' => __( 'Radio Image', AT_ADMIN_TEXTDOMAIN ),
			'description' => __( 'Change radio image option', AT_ADMIN_TEXTDOMAIN ),
			'items' => 
				array(
					'value1' => 'http://wj-wordpress.local/wp-content/themes/winterjuice/framework/admin/assets/images/columns/home/1.png',
					'value2' => 'http://wj-wordpress.local/wp-content/themes/winterjuice/framework/admin/assets/images/columns/home/2.png',
					'value3' => 'http://wj-wordpress.local/wp-content/themes/winterjuice/framework/admin/assets/images/columns/home/3.png',
				),
			'default' => 'value1'
		),
	'radio' =>
		array(
			'type' => 'radio',
			'title' => __( 'Radio', AT_ADMIN_TEXTDOMAIN ),
			'description' => __( 'Change option', AT_ADMIN_TEXTDOMAIN ),
			'items' => 
				array(
					'value1' => 'Value 1',
					'value2' => 'Value 2',
					'value3' => 'Value 3',
				),
			'default' => 'value1'
		),
	'upload' =>
		array(
			'type' => 'upload',
			'title' => __( 'Upload', AT_ADMIN_TEXTDOMAIN ),
			'description' => '',
			'default' => 'default'
		),
	'text' =>
		array(
			'type' => 'input_text',
			'title' => __( 'Disable Breadcrumbs', AT_ADMIN_TEXTDOMAIN ),
			'description' => __( 'Check this option to remove breadcrumbs from site.', AT_ADMIN_TEXTDOMAIN ),
			'default' => 'default text'
		),
	'textarea' =>
		array(
			'type' => 'textarea',
			'title' => __( 'Textarea', AT_ADMIN_TEXTDOMAIN ),
			'description' => '',
			'default' => 'default'
		),
	// 'editor' =>
	// 	array(
	// 		'type' => 'editor',
	// 		'title' => __( 'Textarea', AT_ADMIN_TEXTDOMAIN ),
	// 		'description' => '',
	// 		'default' => 'default'
	// 	),
);

*/

}