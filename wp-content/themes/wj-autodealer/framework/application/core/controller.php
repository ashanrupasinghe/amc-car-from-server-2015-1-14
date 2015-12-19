<?php
if (!defined("AT_DIR")) die('!!!');
abstract class AT_Controller {

	public $view = null;
	public $load = null;
	public $core = null;

	public function __construct() {
		$this->core = AT_Core::get_instance();

		// if ( !$this->core->get_option( 'theme_is_activated', false ) && is_user_logged_in() ) {
		// 	AT_Notices::set_frontend_notice(
		// 		'<h3>'.__( 'New to AutoDealer?' , AT_TEXTDOMAIN ) . '</h3>' .
		// 		__( 'You almost ready to use full theme features. Please complete two last steps before move your website to production mode.' , AT_TEXTDOMAIN ) . 
		// 		'<br />' . 
		// 		sprintf(__( '<a href="%1$s">Click here to continue &rarr;</a>' , AT_TEXTDOMAIN ), get_admin_url() . 'admin.php?page=at_site_options_general'),
		// 		$class = 'notice'
		// 	);
		// 	Header('Location: ' . get_admin_url() . 'admin.php?page=at_site_options_general');
		// 	die();
		// 	// exit( __( 'Theme is not activated' , AT_TEXTDOMAIN ) );
		// }
		// SSL and ajax tricks				
		// if ( ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] != rtrim( home_url(), '/' ) ) {
		// 	AT_Common::redirect( AT_Router::get_instance()->server('REQUEST_URI'), '301' );
		// }

		$this->uri = AT_Router::get_instance();

		if ( $this->uri->get_method() != 'show_underconstruction' &&  $this->core->get_option( 'status_site', 'production' ) == 'underconstruction' ) {
			AT_Core::show_underconstruction();
		}

		$this->view = $this->core->view;
		$this->load = AT_Loader::get_instance();
		$this->session = AT_Session::get_instance();
		$this->registry = AT_Registry::get_instance();

		$this->load->library('breadcrumbs');
		$this->breadcrumbs = AT_Breadcrumbs::get_instance();

		$validation_rules = $this->load->helper('validation_rules', true);
		$this->validation = $this->load->library('form_validation', true, $validation_rules->rules);
		//$this->validation->set_rules();

		if ( AT_Common::is_user_logged() && $this->core->get_option( 'theme_is_activated', false ) ) {
			$user_model = $this->load->model('user_model');
			$user_info = $user_model->get_user_by_id( AT_Common::get_logged_user_id() );
			$this->registry->set( 'user_info', $user_info );

			if ( $this->core->get_option( 'confirm_email_enable', true ) && !in_array( $this->uri->segments(1), array( 'confirm_email', 'unlogged' ) ) && ( is_null( $user_info['date_active'] ) || empty( $user_info['date_active'] ) ) ){
				AT_Common::redirect( 'auth/confirm_email' );
			}
		}
	}
}
?>