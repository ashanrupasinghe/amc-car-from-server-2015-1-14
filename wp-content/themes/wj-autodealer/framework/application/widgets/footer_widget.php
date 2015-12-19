<?php
if (!defined("AT_DIR")) die('!!!');
class AT_footer_widget extends AT_Model {

    private $_method = '';
    private $_settings = array(
        'top' => array(
            'layout_100' => array(
                'footer_top_1' => 'width_100'
            ),
            'layout_50_50' => array(
                'footer_top_1' => 'width_50',
                'footer_top_2' => 'width_50',
            ),
            'layout_25_25_50' => array(
                'footer_top_1' => 'width_25',
                'footer_top_2' => 'width_25',
                'footer_top_3' => 'width_50'
            ),
            'layout_50_25_25' => array(
                'footer_top_1' => 'width_50',
                'footer_top_2' => 'width_25',
                'footer_top_3' => 'width_25'
            ),
            'layout_25_75' => array(
                'footer_top_1' => 'width_25',
                'footer_top_2' => 'width_75'
            ),
            'layout_75_25' => array(
                'footer_top_1' => 'width_75',
                'footer_top_2' => 'width_25'
            ),
            'layout_25_25_25_25' => array(
                'footer_top_1' => 'width_25',
                'footer_top_2' => 'width_25',
                'footer_top_3' => 'width_25',
                'footer_top_4' => 'width_25'
            )
        ),
        'bottom' => array(
            'layout_100' => array(
                'footer_bottom_1' => 'width_100'
            ),
            'layout_50_50' => array(
                'footer_bottom_1' => 'width_50',
                'footer_bottom_2' => 'width_50',
            ),
            'layout_25_25_50' => array(
                'footer_bottom_1' => 'width_25',
                'footer_bottom_2' => 'width_25',
                'footer_bottom_3' => 'width_50'
            ),
            'layout_50_25_25' => array(
                'footer_bottom_1' => 'width_50',
                'footer_bottom_2' => 'width_25',
                'footer_bottom_3' => 'width_25'
            ),
            'layout_25_75' => array(
                'footer_bottom_1' => 'width_25',
                'footer_bottom_2' => 'width_75'
            ),
            'layout_75_25' => array(
                'footer_bottom_1' => 'width_75',
                'footer_bottom_2' => 'width_25'
            ),
            'layout_25_25_25_25' => array(
                'footer_bottom_1' => 'width_25',
                'footer_bottom_2' => 'width_25',
                'footer_bottom_3' => 'width_25',
                'footer_bottom_4' => 'width_25'
            )
        )
    );

    public function __construct($params = array()) {
        parent::__construct();
    }

    public function render($params = array()) {
        if (is_admin()) return '';
        
        add_action('wp_footer', array( 'AT_footer_widget', 'additional_footer' ));

        $view = new AT_View();
        $view->use_widget('footer');
        $view->add_block('content', 'footer', $this->_controler());
        return $view->render()->display(TRUE);
    }

    private function _controler(){
        return array_merge(
            array(
                'top_sidebars' => $this->_top_sidebars(),
                'bottom_sidebars' => $this->_bottom_sidebars()
            ),
            $this->_additional_data()
        );
    }

    private function _top_sidebars() {
        $sidebars = array();
        $footer_layout_top = AT_Core::get_instance()->get_option( 'footer_layout_top', 'layout_25_75');
        $settings = isset($this->_settings['top'][$footer_layout_top]) ? $this->_settings['top'][$footer_layout_top] : $this->_settings['top']['layout_25_75'];
        foreach ($settings as $key => $class) {
            ob_start();
                dynamic_sidebar( $key );
            $sidebars[$key] = array('content' => ob_get_clean(), 'class' => $class);
        }
        return $sidebars;
    }

    private function _bottom_sidebars() {
        $sidebars = array();
        $footer_layout_bottom = AT_Core::get_instance()->get_option( 'footer_layout_bottom', 'layout_25_25_25_25');
        $settings = isset($this->_settings['bottom'][$footer_layout_bottom]) ? $this->_settings['bottom'][$footer_layout_bottom] : $this->_settings['bottom']['layout_25_25_25_25'];
        foreach ($settings as $key => $class) {
            ob_start();
                dynamic_sidebar( $key );
            $sidebars[$key] = array('content' => ob_get_clean(), 'class' => $class);
        }
        return $sidebars;
    }

    private function _additional_data() {
        $data = array();
        if ( $this->core->get_option( 'footer_logo_src', '' ) != '' ) {
            $data['logo'] = '<img src="' . $this->core->get_option( 'footer_logo_src' ) . '">';
        }
        $data['sociable_view'] = $this->core->get_option( 'footer_sociable_view', true );
        $data['sociable'] = AT_Core::get_instance()->get_option( 'sociable', array() );
        return $data;
    }

    static public function additional_footer(){
        $view = new AT_View();
        $view->use_widget('footer');
        $view->add_block('content', 'additional_footer');
        echo $view->render()->display(TRUE);
    }
}
