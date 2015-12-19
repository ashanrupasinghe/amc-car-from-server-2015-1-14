<?php
if (!defined("AT_DIR")) die('!!!');

class AT_AsideMenu_Widget extends WP_Widget { 
  
    public function AT_AsideMenu_Widget() {
        $widget_ops = array('description' => __('Display custom menu from Appearance &rarr menu.',AT_ADMIN_TEXTDOMAIN) );
        $control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'aside_menu' );
        $this->WP_Widget( 'aside_menu', sprintf( __('%1$s - Custom Menu', AT_ADMIN_TEXTDOMAIN ), THEME_NAME ), $widget_ops, $control_ops );
    }
  
    public function widget($args, $instance) {
        extract($args);
        $defaults = array( 'title' => '', 'menu' => 'primary', 'description' => '');
        $instance = wp_parse_args( (array) $instance, $defaults );
    $locations = get_nav_menu_locations();

        $title = '';

        // Create beautiful title
        if (isset($instance['title'])) {
            $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
            if ( !empty($title) ) {
                $title_array = explode("\x20",$title);
                $title = "";
                foreach($title_array as $tcount => $word) {
                    if ( $tcount == 0 ) {
                        $word = "<strong>" . $word . "</strong>";
                    }
                    $title .= $word . " ";
                }
                $title = "<h2>" . $title . "</h2>";
            }
        }


        // Build menu options
        $opts = array(
            'theme_location'  => '',
            'menu'            => $locations[isset($instance['menu']) ? $instance['menu'] : 'primary-menu'],
            'container'       => '',
            'container_class' => '',
            'container_id'    => '',
            'menu_class'      => 'menu',
            'menu_id'         => '',
            'echo'            => true,
            'fallback_cb'     => 'wp_page_menu',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'depth'           => 0,
            'walker'          => ''
        );

        // Create widget
        echo '<div class="widget">';
    echo $before_widget;
        echo $title;
        
        // Build menu
        wp_nav_menu( $opts );

        echo ( !empty($instance['description']) ) ? '<p><!-- WHITESPACE --></p><ul><p>' . $instance['description'] . '</p></ul>' : '';
        echo $after_widget;
        echo '</div>';
    }
  
    /* Store */
    public function update( $new_instance, $old_instance ) {  
        $instance = $old_instance; 
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['description'] = strip_tags( $new_instance['description'] );
        $instance['menu'] = strip_tags( $new_instance['menu'] );

        return $instance;
    }

    /* Settings */
    public function form($instance) {
        $defaults = array( 'title' => 'Links list', 'description' => 'Website powered by WinterJuice theme');
        $instance = wp_parse_args( (array) $instance, $defaults );

        // field: Widget title
        echo '<p>';
        echo '<label for="' . $this->get_field_id( 'title' ) . '">Widget Title:</label>';
        echo '<input class="widefat" id="' . $this->get_field_id( 'title' ) .'" name="' . $this->get_field_name( 'title' ) . '" value="' . $instance['title'] . '" />';
        echo '</p>';

        // textarea: Description field
        echo '<p>';
        echo '<label for="' . $this->get_field_id( 'title' ) . '">Custom Text:</label>';
        echo '<input class="widefat" id="' . $this->get_field_id( 'description' ) . '" name="' . $this->get_field_name( 'description' ) . '" value="' . $instance['description'] . '" />';
        echo '</p>';

        // prepare: menu
        $menu = get_registered_nav_menus();
        if ( !isset($instance['menu']) ) {
            $instance['menu'] = 0;
        }
        echo '<h3>Available Menu:</h3>';
        echo '<div class="custom-socials">';

        // select: Custom menu
        echo '<p>';
        echo '<label for="' . $this->get_field_id( 'menu' ) . '">Menu:</label>';
        echo '<select class="widefat" id="' . $this->get_field_id( 'menu' ) . '" name="' . $this->get_field_name( 'menu' ) . '">';
        echo '</p>';
        foreach ($menu as $id => $name) {
            echo '<option value="' . $id . '" ' . selected( $id, $instance['menu'] ) . '>' . $name . '</option>';
        }
        echo '</select>';
        echo '</div>';

        // render
        //echo $html;
    }
}
