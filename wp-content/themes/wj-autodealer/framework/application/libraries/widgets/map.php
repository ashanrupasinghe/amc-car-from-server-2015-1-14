<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Map_Widget extends WP_Widget { 
  
    public function AT_Map_Widget() {
        $widget_ops = array('description' => __('Display custom map.',AT_ADMIN_TEXTDOMAIN) );
        $control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'map' );
        $this->WP_Widget( 'map', sprintf( __('%1$s - Map', AT_ADMIN_TEXTDOMAIN ), THEME_NAME ), $widget_ops, $control_ops );
    }
  
    public function widget($args, $instance) {
        extract($args);
        $defaults = array( 'title' => '', 'map' => '');
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = '';
        $html = do_shortcode('[vc_gmaps type="m" zoom="14" bubble="1" link="' . $instance['map'] . '"]');

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


        echo '<div class="widget">';
        echo $before_widget;
        echo $title;

        // display content
        echo '<div class="post">';
        echo $html;
        echo '</div>';
        // display link
        echo $after_widget;
        echo '</div>';
    }
  
    /* Store */
    public function update( $new_instance, $old_instance ) {  
        $instance = $old_instance; 
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['map'] = $new_instance['map'];

        return $instance;
    }

    /* Settings */
    public function form($instance) {
        $defaults = array( 'title' => '', 'url' => '', 'label' => '', 'map' => '');
        $instance = wp_parse_args( (array) $instance, $defaults );

        // field: Widget title
        echo '<p>';
        echo '<label for="' . $this->get_field_id( 'title' ) . '">Widget Title:</label>';
        echo '<input class="widefat" id="' . $this->get_field_id( 'title' ) .'" name="' . $this->get_field_name( 'title' ) . '" value="' . $instance['title'] . '" />';
        echo '</p>';

        // textarea: Description field
        echo '<p>';
        echo '<label for="' . $this->get_field_id( 'map' ) . '">Map link:</label>';
        echo '<textarea class="widefat" id="' . $this->get_field_id( 'map' ) . '" name="' . $this->get_field_name( 'map' ) . '">' . $instance['map'] . '</textarea>';
        echo '</p>';

    }
}
