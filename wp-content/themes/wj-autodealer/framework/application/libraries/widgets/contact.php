<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Contact_Widget extends WP_Widget {
    
    public function AT_Contact_Widget() {
		$widget_ops = array( 'classname' => 'at_contact_widget', 'description' => __( 'Quickly add contact info to your sidebar (e.g. address, phone #, email)', AT_ADMIN_TEXTDOMAIN ) );
		$control_ops = array( 'width' => 250, 'height' => 200 );
		$this->WP_Widget( 'contact', sprintf( __( '%1$s - Contact Us', AT_ADMIN_TEXTDOMAIN ), THEME_NAME ), $widget_ops, $control_ops );
    }

    public function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', empty($instance['title']) ? __('Contact Info', AT_TEXTDOMAIN) : $instance['title'], $instance, $this->id_base);
		$name = ( isset( $instance['name'] ) ? $instance['name'] : '' );
		$address = ( isset( $instance['address'] ) ? $instance['address'] : '' );
		$city = ( isset( $instance['city'] ) ? $instance['city'] : '' );
		$state = ( isset( $instance['state'] ) ? $instance['state'] : '' );
		$zip = ( isset( $instance['zip'] ) ? $instance['zip'] : '' );
		$phone = ( isset( $instance['phone'] ) ? $instance['phone'] : '' );
		$fax = ( isset( $instance['fax'] ) ? $instance['fax'] : '' );
		$email = ( isset( $instance['email'] ) ? $instance['email'] : '' );
		$site = ( isset( $instance['site'] ) ? $instance['site'] : '' );
		$web = ( isset( $instance['web'] ) ? $instance['web'] : '' );
		
        if (isset( $instance['type'] ) && $instance['type'] == 'page_content' ) {
			echo '<h2>' . $title . '</h2>';
			echo '<div class="at_contact_info">';
				if ( !empty( $name ) )
					echo $name . '<br/>';
				if ( !empty( $address ) || !empty( $zip ) || !empty( $state ) || !empty( $city ) )
					echo '<span>Adress:  </span>' . $address . ' ' . $zip . ' ' . $city . ( $state ? ', ' . $state : '' ) . '<br/><br/>';
				if ( !empty( $phone ) )
					echo '<span>Phone:  </span>' . $phone . '<br/>';
				if ( !empty( $fax ) )
					echo '<span>Fax:  </span>' . $fax . '<br/>';
				if ( !empty( $email ) )
					echo '<span>Email:  </span>' . $email . '<br/>';
				if ( !empty( $web ) )
					echo '<span>Web:  </span>' . $web . '<br/>';
			echo '</div>';
		} else {

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


	        echo $before_widget;
	        // . $before_title . $title . $after_title ;
			echo $title;
			echo '<div class="contacts_widget">';
				
				if ( !empty( $address ) || !empty( $zip ) || !empty( $state ) || !empty( $city ) ) {
					echo '<i class="icon-marker"></i>';
					echo '<div class="f_contact f_contact_1"><strong>' . __( 'Address', AT_TEXTDOMAIN ) . ':<br></strong>' . $address . ' ' . $zip . ' ' . $city . ( $state ? ', ' . $state : '' ) . '</div>';
				}				

				if ( !empty( $phone ) || !empty( $fax ) ) {
					echo '<i class="icon-phone"></i>';
					echo '<div class="f_contact f_contact_2">';
					if ( !empty( $phone )  ) {
						echo '<strong>' . __( 'Phone', AT_TEXTDOMAIN ) . ':</strong> ' . $phone;
					}
					if ( !empty( $fax )  ) {
						echo '<br><strong>' . __( 'Fax', AT_TEXTDOMAIN ) . ':</strong> ' . $fax;
					}
					echo '</div>';
				}
				
				if ( !empty( $email ) ) {
					echo '<i class="icon-email"></i>';
					echo '<div class="f_contact f_contact_3"><strong>' . __( 'Email', AT_TEXTDOMAIN ) . ':</strong> <a href="mailto:' . $email . '">' . $email . '</a></div>';
				}
				
				if ( !empty( $site ) ) {
					echo '<i class="icon-marker"></i>';
					echo '<div class="f_contact f_contact_4"><strong>' . __( 'Web Site', AT_TEXTDOMAIN ) . ':</strong> <a href="' . $site . '">' . $site . '</a></div>';
				}
			echo '</div>';	        echo $after_widget;
	    }
	}

    public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['name'] = strip_tags($new_instance['name']);
		$instance['address'] = strip_tags($new_instance['address']);
		$instance['city'] = strip_tags($new_instance['city']);
		$instance['state'] = strip_tags($new_instance['state']);
		$instance['zip'] = strip_tags($new_instance['zip']);
		$instance['phone'] = strip_tags($new_instance['phone']);
		$instance['fax'] = strip_tags($new_instance['fax']);
		$instance['email'] = strip_tags($new_instance['email']);
		$instance['site'] = strip_tags($new_instance['site']);
			
        return $instance;
    }

    public function form($instance) {				
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$name = isset($instance['name']) ? esc_attr($instance['name']) : '';
		$address = isset($instance['address']) ? esc_attr($instance['address']) : '';
		$city = isset($instance['city']) ? esc_attr($instance['city']) : '';
		$state = isset($instance['state']) ? esc_attr($instance['state']) : '';
		$zip = isset($instance['zip']) ? esc_attr($instance['zip']) : '';
		$phone = isset($instance['phone']) ? esc_attr($instance['phone']) : '';
		$fax = isset($instance['fax']) ? esc_attr($instance['fax']) : '';
		$email = isset($instance['email']) ? esc_attr($instance['email']) : '';
		$site = isset($instance['site']) ? esc_attr($instance['site']) : '';
		
		echo '
			<p><label for="' . $this->get_field_id('title') . '">' .  _e( 'Title:', AT_ADMIN_TEXTDOMAIN ) . '</label>
			<input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></p>

			<p><label for="' . $this->get_field_id('name') . '">' .  _e( 'Name:', AT_ADMIN_TEXTDOMAIN ) . '</label>
			<input class="widefat" id="' . $this->get_field_id('name') . '" name="' . $this->get_field_name('name') . '" type="text" value="' . $name . '" /></p>

			<p><label for="' . $this->get_field_name('address') . '">' .  _e( 'Address:', AT_ADMIN_TEXTDOMAIN ) . '</label>
			<input class="widefat" id="' . $this->get_field_id('address') . '" name="' . $this->get_field_name('address') . '" type="text" value="' . $address . '" /></p>

			<p><label for="' . $this->get_field_id('city') . '">' .  _e( 'City:', AT_ADMIN_TEXTDOMAIN ) . '</label>
			<input class="widefat" id="' . $this->get_field_id('city') . '" name="' . $this->get_field_name('city') . '" type="text" value="' . $city . '" /></p>

			<p><label for="' . $this->get_field_id('state') . '">' .  _e( 'State:', AT_ADMIN_TEXTDOMAIN ) . '</label>
			<input class="widefat" id="' . $this->get_field_id('state') . '" name="' . $this->get_field_name('state') . '" type="text" value="' . $state . '" /></p>

			<p><label for="' . $this->get_field_id('zip') . '">' .  _e( 'Zip:', AT_ADMIN_TEXTDOMAIN ) . '</label>
			<input class="widefat" id="' . $this->get_field_id('zip') . '" name="' . $this->get_field_name('zip') . '" type="text" value="' . $zip . '" /></p>

			<p><label for="' . $this->get_field_id('phone') . '">' .  _e( 'Phone:', AT_ADMIN_TEXTDOMAIN ) . '</label>
			<input class="widefat" id="' . $this->get_field_id('phone') . '" name="' . $this->get_field_name('phone') . '" type="text" value="' . $phone . '" /></p>

			<p><label for="' . $this->get_field_id('fax') . '">' .  _e( 'Fax:', AT_ADMIN_TEXTDOMAIN ) . '</label>
			<input class="widefat" id="' . $this->get_field_id('fax') . '" name="' . $this->get_field_name('fax') . '" type="text" value="' . $fax . '" /></p>

			<p><label for="' . $this->get_field_name('email') . '">' .  _e( 'Email:', AT_ADMIN_TEXTDOMAIN ) . '</label>
			<input class="widefat" id="' . $this->get_field_id('email') . '" name="' . $this->get_field_name('email') . '" type="text" value="' . $email . '" /></p>

			<p><label for="' . $this->get_field_name('site') . '">' .  _e( 'Web Site:', AT_ADMIN_TEXTDOMAIN ) . '</label>
			<input class="widefat" id="' . $this->get_field_id('site') . '" name="' . $this->get_field_name('site') . '" type="text" value="' . $site . '" /></p>
		';
    }

}
