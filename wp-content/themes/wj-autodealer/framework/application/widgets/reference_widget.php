<?php
if (!defined("AT_DIR")) die('!!!');
class AT_reference_widget extends AT_Model {

    private $_method = '';

    public function __construct($params = array()) {
        parent::__construct();
    }

    public function render($params = array()) {
        $reference_model = $this->load->model( 'reference_model' );
        if (!isset($params['params_arr'])) $params['params_arr'] = array();

        $data = call_user_func_array ( array( $reference_model, $params['method'] ), $params['params_arr'] );

        if ( $params['method'] == 'get_transport_types' ) {
            $data = $this->_get_transport_types( $data );
        }
        return $data;
    }

    private function _controller($params) {

    }

    private function _get_transport_types( $data ) {
        $car_transport_types = $this->core->get_option( 'car_transport_types', array() );
        if ( count( $car_transport_types ) == 0 ) {
            $car_transport_types['default'] = 0;
            $car_transport_types['is_view_all'] = true;
            $car_transport_types['icon'] = 'filter-icon-all';
        }
        if ( !$car_transport_types['is_view_all'] && ($car_transport_types['default'] == 0) && count($data) > 0) {
            $car_transport_types['default'] = $data[0]['id'];
        }

        if( $car_transport_types['is_view_all'] ) {
            array_unshift( $data, array( 'id' => 0, 'name' => __( 'All', AT_TEXTDOMAIN ), 'alias' => $car_transport_types['icon'] ));
        }

        foreach ($data as $key => &$item) {
            if( $car_transport_types['default'] == $item['id'] ) {
                $item['is_default'] = true;
            } else {
                $item['is_default'] = false;
            }
        }
        return $data;
    }
}
