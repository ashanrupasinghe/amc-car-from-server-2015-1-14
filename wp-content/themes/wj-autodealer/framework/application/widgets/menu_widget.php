<?php
if (!defined("AT_DIR")) die('!!!');
class AT_menu_widget extends AT_Model {

    private $_method = '';

    public function __construct($params = array()) {
        parent::__construct();
    }

    public function render($params = array()) {
        add_filter('nav_menu_css_class' , array( 'AT_menu_widget', 'current_class' ) , 10 , 2);
        $out = wp_nav_menu(
            array(
            'theme_location' => 'primary-menu',
            //'container_class' => 'main_navigation',
            'menu_class' => ( !has_nav_menu( 'primary-menu' ) ? '' : ''),
            'echo' => false,
            //'walker' => ( has_nav_menu( 'primary-menu' ) ?  new wjDescriptionWalker() : '')
        ));
        echo $out;
    }

    private function _controller($params) {

    }

    public static function current_class($classes, $item){
        if( AT_Router::get_instance()->segments(0) == 'catalog' && $item->title == "Catalog"){
                $classes[] = "current-menu-item";
        } else if( AT_Router::get_instance()->segments(0) == 'blog' && $item->title == "Blog"){
                $classes[] = "current-menu-item";
        }
        return $classes;
    }
}
