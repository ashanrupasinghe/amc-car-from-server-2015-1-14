<?php
if (!defined("AT_DIR")) die('!!!');
class AT_sidebar_widget extends AT_Model {

    private $_method = '';

    public function __construct($params = array()) {
        parent::__construct();
    }

    public function render($params = array()) {
        $view = new AT_View();
        $view->use_widget('sidebar');
        $view->add_block('content', 'view', $this->_controller( $view, $params ) );
        return $view->render()->display(TRUE);
    }

    private function _controller( $view, $params ) {
        $content = '';
        if( empty( $params['name'] ) ) {
            if ( !empty( AT_Core::get_instance()->view->global_sidebar ) ){
                $params['name'] = AT_Core::get_instance()->view->global_sidebar;
            } else if( is_category() || is_tag() || is_day() || is_month() || is_year() || is_author() || is_tax() || is_search() ){
                $params['name'] = 'primary';
            } else {
                if ( is_home() ) {
                    $post_id = get_option( 'page_for_posts' );
                } else {
                    $post_id = get_queried_object_id();
                }
                $layout = get_post_meta( $post_id, '_layout', true );
            }
            if (isset( $params['layout'] )){
                $layout = $params['layout'];
            }
        }
        if ( empty( $layout ) || !$view->check_layout( $layout ) ) $layout = $this->core->get_option( 'default_page_layout', 'content_right' );
        if ( $layout != 'content' || !empty( $params['name'] ) ) {
            if ( !empty( $params['name'] ) ) {
                $sidebar_name = $params['name'];
            } else if( ( $custom_sidebar = get_post_meta( $post_id, '_custom_sidebar', true ) ) != '' ) {
                $sidebar_name = $custom_sidebar;
            } else {
                $sidebar_name = 'primary';
            }
            ob_start();
            dynamic_sidebar( $sidebar_name );
            $content = ob_get_clean();
        }
        return array( 'content' => $content );
    }
}
