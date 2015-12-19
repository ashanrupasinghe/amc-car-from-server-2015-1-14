<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Recent_Widget extends WP_Widget { 
  
    public function AT_Recent_Widget() {
        $widget_ops = array('description' => __('Display most recent and popular posts.',AT_ADMIN_TEXTDOMAIN) );
        $control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'recent_popular' );
        $this->WP_Widget( 'recent_popular', sprintf( __('%1$s - Recent & Popular', AT_ADMIN_TEXTDOMAIN ), THEME_NAME ), $widget_ops, $control_ops );
    }
  
    public function widget($args, $instance) {
        extract($args);
        $defaults = array( 'title' => '', 'type' => 'car', 'limit' => '3', 'description' => '');
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

        // echo '<div class="widget">';
        echo $before_widget;
        echo '<div class="tabs_widget tabs_wrapper section">';
        echo $title;
        echo '<ul class="tabs">';
        echo '<li class="current">' . __( 'Recent', AT_TEXTDOMAIN ) . '</li>';
        echo '<li>' . __( 'Popular', AT_TEXTDOMAIN ) . '</li>';
        echo '</ul>';

        // Render recent posts container
        echo '<div class="box visible" style="display: block;">';
        $recent_query = new WP_Query(array(
            'showposts' => $instance['limit'],
            'nopaging' => 0,
            'post_type' => $instance['type'],
            'orderby'=> 'post_date',
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1
        ));
        $i = 0;
        while ( $recent_query->have_posts() ) {
            $recent_query->the_post();
            echo '<div class="tab_post">';
                if ( has_post_thumbnail() ) {
                    echo '<a href="' . esc_url( get_permalink() ) . '" class="thumb">';
                    the_post_thumbnail( array( 57,45 ) );
                    echo '</a>';
                }
                echo '<div class="desc">';
                    echo '<a href="' . esc_url( get_permalink() ) . '">' . AT_Common::trim_content(get_the_excerpt(), 40) . '</a>';
                echo '</div>';
            echo '</div>';
        }

        echo '</div>';

        // Render popular posts container
        echo '<div class="box" style="display: none;">';
        $popular_query = new WP_Query(array(
            'showposts' => $instance['limit'],
            'nopaging' => 0,
            'post_type' => $instance['type'],
            'orderby'=> 'comment_count',
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1
        ));
        $i = 0;
        while ( $popular_query->have_posts() ) {
            $popular_query->the_post();
            echo '<div class="tab_post">';
                if ( has_post_thumbnail() ) {
                    echo '<a href="' . esc_url( get_permalink() ) . '" class="thumb">';
                    the_post_thumbnail( array( 57,45 ) );
                    echo '</a>';
                }
                echo '<div class="desc">';
                    echo '<a href="' . esc_url( get_permalink() ) . '">' . AT_Common::trim_content(get_the_excerpt(), 40) . '</a>';
                echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';

        // display description
        echo ( !empty($instance['description']) ) ? '<p><!-- WHITESPACE --></p><ul><p>' . $instance['description'] . '</p></ul>' : '';
        echo $after_widget;
        // echo '</div>';
    }
  
    /* Store */
    public function update( $new_instance, $old_instance ) {  
        $instance = $old_instance; 
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['description'] = strip_tags( $new_instance['description'] );
        $instance['type'] = $new_instance['type'];
        $instance['limit'] = $new_instance['limit'];

        return $instance;
    }

    /* Settings */
    public function form($instance) {
        $defaults = array( 'title' => '', 'type' => 'car', 'limit' => '3', 'description' => 'Website powered by WinterJuice theme');
        $instance = wp_parse_args( (array) $instance, $defaults );

        // field: Widget title
        echo '<p>';
        echo '<label for="' . $this->get_field_id( 'title' ) . '">Widget Title:</label>';
        echo '<input class="widefat" id="' . $this->get_field_id( 'title' ) .'" name="' . $this->get_field_name( 'title' ) . '" value="' . $instance['title'] . '" />';
        echo '</p>';

        // field: Widget title
        echo '<p>';
        echo '<label for="' . $this->get_field_id( 'limit' ) . '">Posts limit:</label>';
        echo '<input class="widefat" type="number" id="' . $this->get_field_id( 'limit' ) .'" name="' . $this->get_field_name( 'limit' ) . '" value="' . $instance['limit'] . '" />';
        echo '</p>';

        // select: list available cars
        echo '<p>';
        echo '<label for="' . $this->get_field_id( 'type' ) . '">Select post type from list:</label>';
        echo '<select class="widefat" id="' . $this->get_field_id( 'type' ) . '" name="' . $this->get_field_name( 'type' ) . '">';
        echo '</p>';
        echo '<option value="car" ' . selected( 'car', $instance['type'] ) . '>Cars</option>';
        echo '<option value="post" ' . selected( 'post', $instance['type'] ) . '>Posts</option>';
        echo '</select>';

        // textarea: Description field
        echo '<p>';
        echo '<label for="' . $this->get_field_id( 'description' ) . '">Custom Text:</label>';
        echo '<textarea class="widefat" id="' . $this->get_field_id( 'description' ) . '" name="' . $this->get_field_name( 'description' ) . '">' . $instance['description'] . '</textarea>';
        echo '</p>';
    }
}
