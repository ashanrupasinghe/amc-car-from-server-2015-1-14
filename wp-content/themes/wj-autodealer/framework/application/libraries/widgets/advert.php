<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Advert_Widget extends WP_Widget { 
  
    public function AT_Advert_Widget() {
        $widget_ops = array('description' => __('Display your Sponsor Links. Recommended image width is 300px. If one of link is empty, then banner will not shown.',AT_ADMIN_TEXTDOMAIN) );
        $control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'sponsor_banners' );
        $this->WP_Widget( 'sponsor_banners', sprintf( __( '%1$s - Sponsor Banners', AT_ADMIN_TEXTDOMAIN ), THEME_NAME ), $widget_ops, $control_ops );
    }
  
    public function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $sponsor1 = $instance['sponsor1'];
        $sponsorimg1 = $instance['sponsorimg1'];
        $sponsor2 = $instance['sponsor2'];
        $sponsorimg2 = $instance['sponsorimg2'];
        $sponsor3 = $instance['sponsor3'];
        $sponsorimg3 = $instance['sponsorimg3'];
        $sponsor4 = $instance['sponsor4'];
        $sponsorimg4 = $instance['sponsorimg4'];
        print $before_widget;
        print $before_title . $title . $after_title;
        print '<div class="sponsors wide">';
            if (!empty($sponsorimg1)) { print '<a href="'.$sponsor1.'" target="_blank"><img src="'.$sponsorimg1.'" /></a>';}
            if (!empty($sponsorimg2)) { print '<a href="'.$sponsor2.'" target="_blank"><img src="'.$sponsorimg2.'" /></a>';}
            if (!empty($sponsorimg3)) { print '<a href="'.$sponsor3.'" target="_blank"><img src="'.$sponsorimg3.'" /></a>';}
            if (!empty($sponsorimg4)) { print '<a href="'.$sponsor4.'" target="_blank"><img src="'.$sponsorimg4.'" /></a>';}
        print '</div>';
        print $after_widget;
    }

    public function update( $new_instance, $old_instance ) {  
        $instance = $old_instance; 
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['sponsor1'] = strip_tags( $new_instance['sponsor1'] );
        $instance['sponsorimg1'] = strip_tags( $new_instance['sponsorimg1'] );
        $instance['sponsor2'] = strip_tags( $new_instance['sponsor2'] );
        $instance['sponsorimg2'] = strip_tags( $new_instance['sponsorimg2'] );
        $instance['sponsor3'] = strip_tags( $new_instance['sponsor3'] );
        $instance['sponsorimg3'] = strip_tags( $new_instance['sponsorimg3'] );
        $instance['sponsor4'] = strip_tags( $new_instance['sponsor4'] );
        $instance['sponsorimg4'] = strip_tags( $new_instance['sponsorimg4'] );

        return $instance;
    }

    public function form($instance) {
        $default = Array(
          "1" => Array (
            "icon" => "http://winterjuice.com/shared/banners/300/ad01.png",
            "url" => "http://themeforest.net"
          ),
          "2" => Array (
            "icon" => "http://winterjuice.com/shared/banners/300/tf_300x250_v3.gif",
            "url" => "http://codecanyon.com"
          ),
          "3" => Array (
            "icon" => "http://winterjuice.com/shared/banners/300/ad03.png",
            "url" => "http://mac.appstorm.net/"
          ),
          "4" => Array (
            "icon" => "http://winterjuice.com/shared/banners/300/ad04.png",
            "url" => "http://photodune.com/"
          ),
        );
        $defaults = array( 'title' => 'Sponsor Banners Widget', 'sponsor1' => $default[1]['url'], 'sponsorimg1' => $default[1]['icon'], 'sponsor2' => $default[2]['url'], 'sponsorimg2' => $default[2]['icon'], 'sponsor3' => $default[3]['url'], 'sponsorimg3' => $default[3]['icon'], 'sponsor4' => $default[4]['url'], 'sponsorimg4' => $default[4]['icon'] );
        $instance = wp_parse_args( (array) $instance, $defaults );
        echo '
            <p>
              <label for="' . $this->get_field_id( 'title' ) . '">Widget Title:</label>
              <input class="widefat" id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '" value="' . $instance['title'] . '" />
            </p>
            <p>
              <label for="' . $this->get_field_id( 'sponsor1' ) . '">Sponsor 1 URL:</label>
              <input class="widefat" id="' . $this->get_field_id( 'sponsor1' ) . '" name="' . $this->get_field_name( 'sponsor1' ) . '" value="' . $instance['sponsor1'] . '" />
            </p>

            <p>
              <label for="' . $this->get_field_id( 'sponsorimg1' ) . '">Sponsor 1 Image URL:</label>
              <input class="widefat" id="' . $this->get_field_id( 'sponsorimg1' ) . '" name="' . $this->get_field_name( 'sponsorimg1' ) . '" value="' . $instance['sponsorimg1'] . '" />
            </p>
            <p>
              <label for="' . $this->get_field_id( 'sponsor2' ) . '">Sponsor 2 URL:</label>
              <input class="widefat" id="' . $this->get_field_id( 'sponsor2' ) . '" name="' . $this->get_field_name( 'sponsor2' ) . '" value="' . $instance['sponsor2'] . '" />
            </p>

            <p>
              <label for="' . $this->get_field_id( 'sponsorimg2' ) . '">Sponsor 2 Image URL:</label>
              <input class="widefat" id="' . $this->get_field_id( 'sponsorimg2' ) . '" name="' . $this->get_field_name( 'sponsorimg2' ) . '" value="' . $instance['sponsorimg2'] . '" />
            </p>
            <p>
              <label for="' . $this->get_field_id( 'sponsor3' ) . '">Sponsor 3 URL:</label>
              <input class="widefat" id="' . $this->get_field_id( 'sponsor3' ) . '" name="' . $this->get_field_name( 'sponsor3' ) . '" value="' . $instance['sponsor3'] . '" />
            </p>

            <p>
              <label for="' . $this->get_field_id( 'sponsorimg3' ) . '">Sponsor 3 Image URL:</label>
              <input class="widefat" id="' . $this->get_field_id( 'sponsorimg3' ) . '" name="' . $this->get_field_name( 'sponsorimg3' ) . '" value="' . $instance['sponsorimg3'] . '" />
            </p>
            <p>
              <label for="' . $this->get_field_id( 'sponsor4' ) . '">Sponsor 4 URL:</label>
              <input class="widefat" id="' . $this->get_field_id( 'sponsor4' ) . '" name="' . $this->get_field_name( 'sponsor4' ) . '" value="' . $instance['sponsor4'] . '" />
            </p>

            <p>
              <label for="' . $this->get_field_id( 'sponsorimg4' ) . '">Sponsor 4 Image URL:</label>
              <input class="widefat" id="' . $this->get_field_id( 'sponsorimg4' ) . '" name="' . $this->get_field_name( 'sponsorimg4' ) . '" value="' . $instance['sponsorimg4'] . '" />
            </p>';
    }
}
