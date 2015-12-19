<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Workhours_Widget extends WP_Widget {
    
    public function AT_Workhours_Widget() {
		$widget_ops = array( 'classname' => 'at_workhours_widget', 'description' => __( 'Office work hours widget.', AT_ADMIN_TEXTDOMAIN ) );
		$control_ops = array( 'width' => 250, 'height' => 200 );
		$this->WP_Widget( 'workhours', sprintf( __( '%1$s - Work Hours', AT_ADMIN_TEXTDOMAIN ), THEME_NAME ), $widget_ops, $control_ops );
    }

    public function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', empty($instance['title']) ? __('Contact Info', AT_TEXTDOMAIN) : $instance['title'], $instance, $this->id_base);
		$descr_before = ( isset( $instance['descr_before'] ) ? $instance['descr_before'] : '' );
		$descr_after = ( isset( $instance['descr_after'] ) ? $instance['descr_after'] : '' );

		$workdays = Array (
			'mon' => ( isset( $instance['mon'] ) ? $instance['mon'] : '' ),
			'tue' => ( isset( $instance['tue'] ) ? $instance['tue'] : '' ),
			'wed' => ( isset( $instance['wed'] ) ? $instance['wed'] : '' ),
			'thu' => ( isset( $instance['thu'] ) ? $instance['thu'] : '' ),
			'fri' => ( isset( $instance['fri'] ) ? $instance['fri'] : '' ),
			'sat' => ( isset( $instance['sat'] ) ? $instance['sat'] : '' ),
			'sun' => ( isset( $instance['sun'] ) ? $instance['sun'] : '' ),
		);

		$weekday = Array (
			'mon' => __( 'Monday',   AT_TEXTDOMAIN ),
			'tue' => __( 'Tuesday',  AT_TEXTDOMAIN ),
			'wed' => __( 'Wednesday',AT_TEXTDOMAIN ),
			'thu' => __( 'Thursday', AT_TEXTDOMAIN ),
			'fri' => __( 'Friday',   AT_TEXTDOMAIN ),
			'sat' => __( 'Saturday', AT_TEXTDOMAIN ),
			'sun' => __( 'Sunday',   AT_TEXTDOMAIN )
		);
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
                $title = "<h3>" . $title . "</h3>";
            }
        }

		echo '<div class="divide">';

        echo $before_widget;
        // . $before_title . $title . $after_title ;
		echo $title;

		if ( !empty( $descr_before ) ) {
			echo '<div>';
			echo do_shortcode( $descr_before );
			echo '</div>';
		}
		echo '<ul class="schedule">';

			foreach( $workdays as $days => $day ) {
				if ( !empty( $day ) ) {
					$values = explode(',', $day);
					echo '<li class="weekday_' . $days . '">';
					echo '<strong> ' . $weekday[ $days ] . '</strong>';
					echo '<span>';
					foreach( $values as $key => $time ) {
						if ( $key === 1 ) {
							echo ' - ';
						}
						echo $time;
					}
					echo '</span>';
					echo '</li>';
				}
			}

		echo '</ul>';
		if ( !empty( $descr_after ) ) {
			echo '<div>';
			echo do_shortcode( $descr_after );
			echo '</div>';
		}
        echo $after_widget;
		echo '</div>';

	}

    public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['descr_before'] = strip_tags($new_instance['descr_before']);
		$instance['descr_after'] = strip_tags($new_instance['descr_after']);
		$instance['mon'] = strip_tags($new_instance['mon']);
		$instance['tue'] = strip_tags($new_instance['tue']);
		$instance['wed'] = strip_tags($new_instance['wed']);
		$instance['thu'] = strip_tags($new_instance['thu']);
		$instance['fri'] = strip_tags($new_instance['fri']);
		$instance['sat'] = strip_tags($new_instance['sat']);
		$instance['sun'] = strip_tags($new_instance['sun']);
			
        return $instance;
    }

    public function form($instance) {				

        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $descr_before = isset($instance['descr_before']) ? esc_attr($instance['descr_before']) : '';
        $descr_after = isset($instance['descr_after']) ? esc_attr($instance['descr_after']) : '';
        $mon = isset($instance['mon']) ? esc_attr($instance['mon']) : '9am,5pm';
        $tue = isset($instance['tue']) ? esc_attr($instance['tue']) : '9am,5pm';
        $wed = isset($instance['wed']) ? esc_attr($instance['wed']) : '9am,5pm';
        $thu = isset($instance['thu']) ? esc_attr($instance['thu']) : '9am,5pm';
        $fri = isset($instance['fri']) ? esc_attr($instance['fri']) : '9am,5pm';
        $sat = isset($instance['sat']) ? esc_attr($instance['sat']) : '11am,4pm';
        $sun = isset($instance['sun']) ? esc_attr($instance['sun']) : 'closed';
		echo '
		<p><label for="' . $this->get_field_id('title') . '">' . __( 'Title:', AT_ADMIN_TEXTDOMAIN ) . '</label>
		<input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></p>

		<p><label for="' . $this->get_field_id('descr_before') . '">' . __( 'Description Before:', AT_ADMIN_TEXTDOMAIN ) . '</label>
		<textarea class="widefat" id="' . $this->get_field_id('descr_before') . '" name="' . $this->get_field_name('descr_before') . '" type="text">' . $descr_before . '</textarea></p>

		<!-- Monday -->
		<p><label for="' . $this->get_field_name('mon') . '">' . __( 'Monday:', AT_ADMIN_TEXTDOMAIN ) . '</label>
		<input class="widefat" id="' . $this->get_field_id('mon') . '" name="' . $this->get_field_name('mon') . '" type="text" value="' . $mon . '" /></p>

		<!-- Tuesday -->
		<p><label for="' . $this->get_field_name('tue') . '">' . __( 'Tuesday:', AT_ADMIN_TEXTDOMAIN ) . '</label>
		<input class="widefat" id="' . $this->get_field_id('tue') . '" name="' . $this->get_field_name('tue') . '" type="text" value="' . $tue . '" /></p>

		<!-- Wednesday -->
		<p><label for="' . $this->get_field_name('wed') . '">' . __( 'Wednesday:', AT_ADMIN_TEXTDOMAIN ) . '</label>
		<input class="widefat" id="' . $this->get_field_id('wed') . '" name="' . $this->get_field_name('wed') . '" type="text" value="' . $wed . '" /></p>

		<!-- Thursday -->
		<p><label for="' . $this->get_field_name('thu') . '">' . __( 'Thursday:', AT_ADMIN_TEXTDOMAIN ) . '</label>
		<input class="widefat" id="' . $this->get_field_id('thu') . '" name="' . $this->get_field_name('thu') . '" type="text" value="' . $thu . '" /></p>

		<!-- Friday -->
		<p><label for="' . $this->get_field_name('fri') . '">' . __( 'Friday:', AT_ADMIN_TEXTDOMAIN ) . '</label>
		<input class="widefat" id="' . $this->get_field_id('fri') . '" name="' . $this->get_field_name('fri') . '" type="text" value="' . $fri . '" /></p>

		<!-- Saturday -->
		<p><label for="' . $this->get_field_name('sat') . '">' . __( 'Saturday:', AT_ADMIN_TEXTDOMAIN ) . '</label>
		<input class="widefat" id="' . $this->get_field_id('sat') . '" name="' . $this->get_field_name('sat') . '" type="text" value="' . $sat . '" /></p>

		<!-- Sunday -->
		<p><label for="' . $this->get_field_name('sun') . '">' . __( 'Sunday:', AT_ADMIN_TEXTDOMAIN ) . '</label>
		<input class="widefat" id="' . $this->get_field_id('sun') . '" name="' . $this->get_field_name('sun') . '" type="text" value="' . $sun . '" /></p>


		<p><label for="' . $this->get_field_id('descr_after') . '">' . __( 'Description After:', AT_ADMIN_TEXTDOMAIN ) . '</label>
		<textarea class="widefat" id="' . $this->get_field_id('descr_after') . '" name="' . $this->get_field_name('descr_after') . '" type="text">' . $descr_after . '</textarea></p>';
    }

}