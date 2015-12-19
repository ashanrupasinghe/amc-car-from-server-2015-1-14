<?php
if (!defined("AT_DIR")) die('!!!');

class AT_FeaturedCar_Widget extends WP_Widget { 
  
    public function AT_FeaturedCar_Widget() {
        $widget_ops = array('description' => __('Display featured car banner.',AT_ADMIN_TEXTDOMAIN) );
        $control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'featured_car' );
        $this->WP_Widget( 'featured_car', sprintf( __('%1$s - Featured Car', AT_ADMIN_TEXTDOMAIN ), THEME_NAME ), $widget_ops, $control_ops );
    }
  
    public function widget($args, $instance) {
        extract($args);
        $defaults = array( 'title' => '', 'car_id' => 0, 'description' => '');
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
                $title = "<h2>" . $title . "</h2>";
            }
        }

        echo '<div class="widget">';
        echo $before_widget;
        echo $title;

        if ( !isset($instance['car_id']) || empty($instance['car_id']) || $instance['car_id'] == 0 ) {
            _e( 'Car ID not specified. Please edit widget and define car.', AT_TEXTDOMAIN );
        } else {

            // Query args
            $args = Array(
                'posts_per_page' => 1,
                'post_status' => 'publish',
                'post_type' => 'car',
                'p' => $instance['car_id']
            );

            // WP query
            $widget_query = new WP_Query( $args );

            while ( $widget_query->have_posts() ) {
                $widget_query->the_post();
                // display image
                echo '<div class="thumb_box">';
                echo '<a href="' . get_permalink() . '" class="thumb">' . the_post_thumbnail( array( 200,200 ) ) . '</a>';
                echo '</div>';
                // display excerpy
                echo '<div class="post">';
                echo '<p><strong>' . get_the_title() . '</strong> ' . get_the_excerpt() . '</p>';
                echo '</div>';
                // display link
                echo '<a href="' . get_permalink() . '" class="more markered">' . __( 'Read more', AT_TEXTDOMAIN ) . '</a>';
            }
        }
        // display description
        echo ( !empty($instance['description']) ) ? '<p><!-- WHITESPACE --></p><ul><p>' . $instance['description'] . '</p></ul>' : '';
        echo $after_widget;
        echo '</div>';
    }
  
    /* Store */
    public function update( $new_instance, $old_instance ) {  
        $instance = $old_instance; 
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['description'] = strip_tags( $new_instance['description'] );
        $instance['car_id'] = $new_instance['car_id'];

        return $instance;
    }

    /* Settings */
    public function form($instance) {
        $defaults = array( 'title' => 'Featured car', 'car_id' => 0, 'description' => 'Website powered by WinterJuice theme');
        $instance = wp_parse_args( (array) $instance, $defaults );

        // field: Widget title
        echo '<p>';
        echo '<label for="' . $this->get_field_id( 'title' ) . '">Widget Title:</label>';
        echo '<input class="widefat" id="' . $this->get_field_id( 'title' ) .'" name="' . $this->get_field_name( 'title' ) . '" value="' . $instance['title'] . '" />';
        echo '</p>';

        // textarea: Description field
        echo '<p>';
        echo '<label for="' . $this->get_field_id( 'description' ) . '">Custom Text:</label>';
        echo '<input class="widefat" id="' . $this->get_field_id( 'description' ) . '" name="' . $this->get_field_name( 'description' ) . '" value="' . $instance['description'] . '" />';
        echo '</p>';

        echo '<h3>Available Cars:</h3>';
        echo '<div class="custom-socials">';

        // select: list available cars
        echo '<p>';
        echo '<label for="' . $this->get_field_id( 'car_id' ) . '">Select car from list:</label>';
        echo '<select class="widefat" id="' . $this->get_field_id( 'car_id' ) . '" name="' . $this->get_field_name( 'car_id' ) . '">';
        echo '</p>';

        // prepare WP query args
        $args = Array(
            // 'showposts' => 1,
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'post_type' => 'car',
        );

        // WP query
        $widget_query = new WP_Query( $args );

        while ( $widget_query->have_posts() ) {
            $widget_query->the_post();
            $_id = get_the_ID();
            echo '<option value="' . $_id . '" ' . selected( $_id, $instance['car_id'] ) . '>' . get_the_title() . '</option>';
        }

        echo '</select>';
        echo '</div>';
    }
}
