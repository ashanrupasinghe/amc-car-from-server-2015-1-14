<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Sociable_Widget extends WP_Widget { 
  
    public function AT_Sociable_Widget() {
        $widget_ops = array('description' => __('Display social icons from Theme Options &rarr; Sociable.',AT_ADMIN_TEXTDOMAIN) );
        $control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'social_icons' );
        $this->WP_Widget( 'social_icons', sprintf( __('%1$s - Social Icons', AT_ADMIN_TEXTDOMAIN ), THEME_NAME ), $widget_ops, $control_ops );
    }
  
    public function widget($args, $instance) {
        extract($args);
        $defaults = array( 'title' => '', 'skin' => 'default', 'description' => '');
        $instance = wp_parse_args( (array) $instance, $defaults );

        $title = '';

        if (isset($instance['title'])) {
          $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        }

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
            }
        }

        $skin = $instance['skin'];
        $description = $instance['description'];
        $sociable = AT_Core::get_instance()->get_option( 'sociable' );

        $sociables = "";

        $total = count( $sociable ) - 1;
        $width = 100 / $total;
        $size = $width / 2;
        $sociables .= '<div class="custom-socials">';
        foreach ($sociable as $key => $item) {
            $sociable_link = ( !empty( $item['link'] ) ) ? $item['link'] : '#';
            $sociables .= '
                                <a href="' . esc_url( $sociable_link ) . '">
                                    <i class="' . $item['icon'] . '"></i>
                                </a>
            ';
        }
        $sociables .= '</div>';

        if ( !empty($description) ) {
            $description = '<p>' . $description . '</p>';
        }

        if ( isset( $instance['type'] ) && ( $instance['type'] == 'page_content' ) ) {
            echo '<div class="wj_social_icons">';
            if ( !empty($title) ) {
                echo '<h2>' . $title . '</h2>';
            }
            echo $sociables;
            echo '</div>';
        } else {
            echo $before_widget;
            echo '<h3>' . $title . '</h3>';
            echo $sociables;
            echo $description;
            echo $after_widget;
        }
    }
  
    /* Store */
    public function update( $new_instance, $old_instance ) {  
        $instance = $old_instance; 
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['description'] = strip_tags( $new_instance['description'] );

        return $instance;
    }

    /* Settings */
    public function form($instance) {
    $defaults = array( 'title' => 'Social networks', 'color' => '#0000FF', 'description' => 'Website powered by WinterJuice theme');
        $instance = wp_parse_args( (array) $instance, $defaults );

        if ( !isset($instance['skin']) )
          $instance['skin'] = 'default';

        echo '
            <p>
                <label for="' . $this->get_field_id( 'title' ) . '">Widget Title:</label>
                <input class="widefat" id="' . $this->get_field_id( 'title' ) .'" name="' . $this->get_field_name( 'title' ) . '" value="' . $instance['title'] . '" />
            </p>
            <p>
                <label for="' . $this->get_field_id( 'title' ) . '">Custom Text:</label>
                <input class="widefat" id="' . $this->get_field_id( 'description' ) . '" name="' . $this->get_field_name( 'description' ) . '" value="' . $instance['description'] . '" />
            </p>';

        $skin = $instance['skin'];
        $sociable = AT_Core::get_instance()->get_option( 'sociable' );
        $sociables = "";

        $total = count( $sociable ) - 1;
        $width = 100 / $total;
        $size = $width / 2;
        echo '<h3>Icons Preview:</h3>';
        $sociables .= '<div class="custom-socials">';
        foreach ($sociable as $key => $item) {
            $sociable_link = ( !empty( $item['link'] ) ) ? $item['link'] : '#';
            $sociables .= '
                                <a href="' . esc_url( $sociable_link ) . '">
                                    <i class="' . $item['icon'] . '"></i>
                                </a>
            ';
        }
        $sociables .= '</div>';
        echo $sociables;
        echo '<hr />';
    }
}
