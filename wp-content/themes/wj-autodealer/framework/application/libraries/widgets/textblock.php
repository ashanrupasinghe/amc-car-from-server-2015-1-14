<?php
if (!defined("AT_DIR")) die('!!!');

class AT_TextBlock_Widget extends WP_Widget { 
  
    public function AT_TextBlock_Widget() {
        $widget_ops = array('description' => __('Display custom text block.',AT_ADMIN_TEXTDOMAIN) );
        $control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'text_block' );
        $this->WP_Widget( 'text_block', sprintf( __('%1$s - Text Block', AT_ADMIN_TEXTDOMAIN ), THEME_NAME ), $widget_ops, $control_ops );
    }
  
    public function widget($args, $instance) {
        extract($args);
        $defaults = array( 'title' => '', 'label' => '', 'url'=>'', 'content' => '');
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = '';
        $html = '';

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

        if ( !empty($instance['label']) ) {
            $label = $instance['label'];
        } else {
            $label = __( 'Read more', AT_TEXTDOMAIN );
        }

        echo '<div class="widget">';
        echo $before_widget;
        if (isset( $instance['type'] ) && $instance['type'] == 'page_content' ) {
            echo '<h2>' . $title . '</h2>';
        } else {
            echo '<h3>' . $title . '</h3>';
        }

        // display content
        echo '<div class="post">';
        echo $instance['content'];
        echo '</div>';
        // display link
        echo !empty($instance['url']) ? '<a href="' . $instance['url'] . '" class="more markered">' . $label . '</a>' : '';
        echo $after_widget;
        echo '</div>';
    }
  
    /* Store */
    public function update( $new_instance, $old_instance ) {  
        $instance = $old_instance; 
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['content'] = $new_instance['content'];
        $instance['url'] = strip_tags( $new_instance['url'] );
        $instance['label'] = strip_tags( $new_instance['label'] );

        return $instance;
    }

    /* Settings */
    public function form($instance) {
        $defaults = array( 'title' => 'Text block', 'url' => '', 'label' => '', 'content' => 'Enter your content here.');
        $instance = wp_parse_args( (array) $instance, $defaults );

        // field: Widget title
        echo '<p>';
        echo '<label for="' . $this->get_field_id( 'title' ) . '">Widget Title:</label>';
        echo '<input class="widefat" id="' . $this->get_field_id( 'title' ) .'" name="' . $this->get_field_name( 'title' ) . '" value="' . $instance['title'] . '" />';
        echo '</p>';

        // textarea: Description field
        echo '<p>';
        echo '<label for="' . $this->get_field_id( 'content' ) . '">Content:</label>';
        echo '<textarea class="widefat" id="' . $this->get_field_id( 'content' ) . '" name="' . $this->get_field_name( 'content' ) . '">' . $instance['content'] . '</textarea>';
        echo '</p>';

        // field: external url
        echo '<p>';
        echo '<label for="' . $this->get_field_id( 'url' ) . '">External URL:</label>';
        echo '<input class="widefat" id="' . $this->get_field_id( 'url' ) .'" name="' . $this->get_field_name( 'url' ) . '" value="' . $instance['url'] . '" />';
        echo '</p>';

        // field: link label
        echo '<p>';
        echo '<label for="' . $this->get_field_id( 'label' ) . '">Link label:</label>';
        echo '<input class="widefat" id="' . $this->get_field_id( 'label' ) .'" name="' . $this->get_field_name( 'label' ) . '" value="' . $instance['label'] . '" />';
        echo '</p>';
    }
}
