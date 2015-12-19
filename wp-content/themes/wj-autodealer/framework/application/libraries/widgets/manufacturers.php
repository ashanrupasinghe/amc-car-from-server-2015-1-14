<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Manufacturers_Widget extends WP_Widget { 
  
    public function AT_Manufacturers_Widget() {
        $widget_ops = array('description' => __('Display manufacturers catalog.',AT_ADMIN_TEXTDOMAIN) );
        $control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'manufacturers' );
        $this->WP_Widget( 'manufacturers', sprintf( __('%1$s - Make Catalog', AT_ADMIN_TEXTDOMAIN ), THEME_NAME ), $widget_ops, $control_ops );
    }
  
    public function widget($args, $instance) {
        extract($args);
        $defaults = array( 'title' => '', 'manufacturer' => '' );
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

        echo $before_widget;

        echo '<div class="catalog_widget tabs_wrapper section">';

        echo $title;

        $car_model = AT_Loader::get_instance()->model('car_model');

        $manufacturers = $car_model->get_manufacturers();

        echo '<ul>';

        foreach ( $manufacturers as $manufacturer ) {
            if ( is_array($instance['manufacturer']) ) {
    			if(array_key_exists($manufacturer['alias'], $instance['manufacturer'])){
    				echo '<li><a href="' . AT_Common::site_url('catalog/' . $manufacturer['alias'] ) . '">' . $manufacturer['name'] . '</a></li>';
    			}
            } else {
                echo '<li><a href="' . AT_Common::site_url('catalog/' . $manufacturer['alias'] ) . '">' . $manufacturer['name'] . '</a></li>';
            }
        }
        echo '</ul>';

        echo '</div>';

        echo $after_widget;
    }
  
    /* Store */
    public function update( $new_instance, $old_instance ) {  
        $instance = $old_instance; 
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['manufacturer'] = $new_instance['manufacturer'];
        // $instance['description'] = strip_tags( $new_instance['description'] );
        // $instance['type'] = $new_instance['type'];
        // $instance['limit'] = $new_instance['limit'];

        return $instance;
    }

    /* Settings */
    public function form($instance) {
        $defaults = array( 'title' => '' );
        $instance = wp_parse_args( (array) $instance, $defaults );

        // field: Widget title
        echo '<p>';
        echo '<label for="' . $this->get_field_id( 'title' ) . '">Widget Title:</label>';
        echo '<input class="widefat" id="' . $this->get_field_id( 'title' ) .'" name="' . $this->get_field_name( 'title' ) . '" value="' . $instance['title'] . '" />';
        echo '</p>';
		echo '<p>';
		$car_model = AT_Loader::get_instance()->model('car_model');
		$manufacturers = $car_model->get_manufacturers();

        $checked = '';
        
        echo '<table><tr>';
        $i = 1;
        foreach ( $manufacturers as $manufacturer ) {
            if ( isset($instance['manufacturer']) && is_array($instance['manufacturer']) ) {
    			$checked = array_key_exists($manufacturer['alias'], $instance['manufacturer']) ? 'checked="checked"': '';
            }
            echo '<td style="padding-left: 10px;">';
            echo 	'<label>';
			echo 		'<input type="checkbox" value="1" ' . $checked . ' id="'.$manufacturer['alias'].'" name="'.$this->get_field_name('manufacturer]['.$manufacturer['alias']). '">';
            echo 		$manufacturer['name'];
            echo 	'</label>';
            echo '</td>';
            if($i % 3 == 0)echo "</tr><tr>";
			$i++;
        }
        
        echo '</tr></table>';
		echo '</p>';

        // // field: Widget title
        // echo '<p>';
        // echo '<label for="' . $this->get_field_id( 'limit' ) . '">Posts limit:</label>';
        // echo '<input class="widefat" type="number" id="' . $this->get_field_id( 'limit' ) .'" name="' . $this->get_field_name( 'limit' ) . '" value="' . $instance['limit'] . '" />';
        // echo '</p>';

        // // select: list available cars
        // echo '<p>';
        // echo '<label for="' . $this->get_field_id( 'type' ) . '">Select post type from list:</label>';
        // echo '<select class="widefat" id="' . $this->get_field_id( 'type' ) . '" name="' . $this->get_field_name( 'type' ) . '">';
        // echo '</p>';
        // echo '<option value="car" ' . selected( 'car', $instance['type'] ) . '>Cars</option>';
        // echo '<option value="post" ' . selected( 'post', $instance['type'] ) . '>Posts</option>';
        // echo '</select>';

        // // textarea: Description field
        // echo '<p>';
        // echo '<label for="' . $this->get_field_id( 'description' ) . '">Custom Text:</label>';
        // echo '<textarea class="widefat" id="' . $this->get_field_id( 'description' ) . '" name="' . $this->get_field_name( 'description' ) . '">' . $instance['description'] . '</textarea>';
        // echo '</p>';
    }
}
