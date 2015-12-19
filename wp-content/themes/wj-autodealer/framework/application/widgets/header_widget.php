<?php
if (!defined("AT_DIR")) die('!!!');
class AT_header_widget extends AT_Model {

    private $_method = '';

    public function __construct($params = array()) {
        parent::__construct();
    }

    public function render($params = array()) {
        $view = new AT_View();
        $view->use_widget('header');
        if (is_admin()) {
            $view->add_block('content', 'admin_header');
        } else {
            add_action('wp_head', array( 'AT_header_widget', 'additional_head' ));
            $params['content_auto_width'] = isset($params['content_auto_width']) ? $params['content_auto_width'] : false;
            $view->add_block('content', 'header', array_merge( array( 'content_auto_width' => $params['content_auto_width'] ), $this->_frontend_controller($params)));
        }
        return $view->render()->display(TRUE);
    }

    private function _frontend_controller($params) {
        $controller = AT_Router::get_instance()->get_controller();
        $method_exec = '_' . AT_Router::get_instance()->get_controller() . '_data';
        $data = array_merge( $this->_frontend_header( $params ), method_exists($this, $method_exec) ? $this->$method_exec(AT_Router::get_instance()->get_method(), $params) : $this->_default_data() );
        AT_Registry::get_instance()->set( 'header_data', $data );
        return $data;
    }

    private function _AT_Catalog_data($method, $params ) {
        $page_title = __( 'Car\'s Catalog | ', AT_TEXTDOMAIN ) . get_bloginfo('name');
        return array( 'page_title' => $page_title );
    }

    private function _AT_Payments_data($method, $params ) {
        $page_title = __( 'Payments | ', AT_TEXTDOMAIN ) . get_bloginfo('name');
        return array( 'page_title' => $page_title );
    }

    private function _AT_News_data( $method, $params ) {
        switch ( $method ) {
            case 'archive':
                $page_title = $this->core->get_option( 'news_title', __( 'News', AT_TEXTDOMAIN ) ) . ' | ' . get_bloginfo('name');
                break;
            default:
                return $this->_default_data();
                break;
        }
        return array( 'page_title' => $page_title );
    }

    private function _AT_Reviews_data( $method, $params ) {
        switch ( $method ) {
            case 'archive':
                $page_title = $this->core->get_option( 'reviews_title', __( 'Reviews', AT_TEXTDOMAIN ) ) . ' | ' . get_bloginfo('name');
                break;
            default:
                return $this->_default_data();
                break;
        }
        return array( 'page_title' => $page_title );
    }

    private function _AT_Welcome_data( $method, $params ) {
        switch ( $method ) {
            default:
                return $this->_default_data();
                break;
        }
        return array( 'page_title' => $page_title );
    }

    private function _AT_Auth_data( $method, $params ) {
        switch ( $method ) {
            case 'recovery':
            case 'recovery_pass':
                $page_title = __( 'Recovery password | ', AT_TEXTDOMAIN ) . get_bloginfo('name');
                break;
            case 'login':
                $page_title = __( 'Sign in | ', AT_TEXTDOMAIN ) . get_bloginfo('name');
                break;
            case 'registration':
                $page_title = __( 'Regisration | ', AT_TEXTDOMAIN ) . get_bloginfo('name');
                break;
            case 'confirm_email':
                $page_title = __( 'Confirm Email | ', AT_TEXTDOMAIN ) . get_bloginfo('name');
                break;
            default:
                return $this->_default_data();
                break;
        }
        return array( 'page_title' => $page_title );
    }

    private function _AT_Errors_data($method, $params ) {
        switch ( $method ) {
            case 'show_underconstruction':
                $page_title = __( 'Site under construction | ', AT_TEXTDOMAIN ) . get_bloginfo('name');
                break;
            
            default:
                $page_title = __( 'Page Not Found | ', AT_TEXTDOMAIN ) . get_bloginfo('name');
                break;
        }
        return array( 'page_title' => $page_title );
    }

    private function _AT_Vehicles_data($method, $params ) {
        switch ( $method ) {
            case 'index':
                $page_title = __( 'My cars | ', AT_TEXTDOMAIN );
                break;
            case 'archive':
                $page_title = __( 'My cars archived | ', AT_TEXTDOMAIN );
                break;
            case 'edit':
                $page_title = __( 'Edit item | ', AT_TEXTDOMAIN );
                break;
            case 'add':
                $page_title = __( 'Add items | ', AT_TEXTDOMAIN );
                break;
            default:
                $page_title = __( 'My vehicles | ', AT_TEXTDOMAIN );
                break;
        }
        $page_title = $page_title . get_bloginfo('name');
        return array( 'page_title' => $page_title );
    }

    private function _AT_Settings_data($method, $params ) {
        switch ( $method ) {
            case 'index':
                $page_title = __( 'Profile | ', AT_TEXTDOMAIN );
                break;
            case 'dealer_affiliates':
                $page_title = __( 'Dealer affiliates | ', AT_TEXTDOMAIN );
                break;
            default:
                $page_title = __( 'Profile | ', AT_TEXTDOMAIN );
                break;
        }
        $page_title = $page_title . get_bloginfo('name');
        return array( 'page_title' => $page_title );
    }

    private function _AT_Dealer_data($method, $params ) {
        switch ( $method ) {
            case 'info':
                $user_model = $this->load->model('user_model');
                $dealer = AT_Router::get_instance()->segments(2);
                if ( is_numeric($dealer) ){
                    $dealer_id = $dealer;
                }else{
                    $segment = explode('-', $dealer);
                    $dealer_id = $segment[count($segment) - 1];
                }
                $dealer_info = $user_model->get_user_by_id( $dealer_id );
                $page_title = $dealer_info['name'] . ' | ';
                break;
            default:
                $page_title = __( 'Dealer | ', AT_TEXTDOMAIN );
                break;
        }
        $page_title = $page_title . get_bloginfo('name');
        return array( 'page_title' => $page_title );
    }

    private function _default_data() {
        $page_title = '';
        if ( wp_title('', false) ) $page_title = wp_title('|', false, 'right');
        $page_title .= ' ' . get_bloginfo('name');
        return array( 'page_title' => $page_title );
    }

    private function _frontend_header( $params ) {
        $data = array();
        switch ( $this->core->get_option( 'logo_settings' ) ) {
            case 'logo_image':
                $width = $this->core->get_option( 'header_logo_width' ) != '' ? ' style="max-width:' . $this->core->get_option( 'header_logo_width' ) . ';"': '';
                if( $this->core->get_option( 'header_logo_src' ) != '' ) {
                    $data['logo'] = '<img src="' . $this->core->get_option( 'header_logo_src' ) . '"' . $width . '>';
                } else {
                    $data['logo'] = '<img src="' . AT_Common::static_url( '/assets/images/pics/logo_auto_dealer.png' ) . '"' . $width . '>';
                }
                break;
            case 'logo_text':
                $data['logo'] = $this->core->get_option( 'header_logo_text' );
                break;
            default:
                $data['logo'] = get_bloginfo('name');
                break;
        }

        $data['header_style'] = $this->core->get_option( 'header_content_style', 'info' );
        $data['add_car_button'] = $this->core->get_option( 'header_add_car_button', true );
        $data['add_car_button'] = $this->core->get_option( 'header_add_car_button', true );
        $data['sociable_view'] = $this->core->get_option( 'header_sociable_view', true );
        $data['sociable'] = AT_Core::get_instance()->get_option( 'sociable', array() );
        $data['site_type'] = $this->core->get_option( 'site_type', 'mode_soletrader');
        $data['phone'] = $this->core->get_option( 'header_phone', '');
        $data['adress'] = $this->core->get_option( 'header_adress', '');
        $data['header_custom_html'] = $this->core->get_option( 'header_custom_html', '' );
        $data['searchbox'] = $this->core->get_option( 'header_searchbox', true );
        return $data;

    }

    static public function additional_head(){
        $view = new AT_View();
        $view->use_widget('header');
        $view->add_block('content', 'additional_head');
        echo $view->render()->display(TRUE);
    }
}
