<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Recentdealers_VC_ShortCode extends AT_VC_ShortCode{

	public static function at_recent_dealers ( $atts = null, $content = null ) {
			if( $atts == 'generator' ) {
			return array(
		        "name"      => __( "Recent Dealers", AT_ADMIN_TEXTDOMAIN ),
		        "base"      => "at_recent_dealers",
				'icon'      => "im-icon-spinner-4",
		        "class"     => "at-resent-posts-class",
				'category'  => __("Theme Short-Codes", AT_ADMIN_TEXTDOMAIN ),
		        "params"    => array(
		            array(
		                "type" => "textfield",
		                "heading" => __( "Title", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "title",
		                "value" => "",
		                "description" => __( "Enter custom title.", AT_ADMIN_TEXTDOMAIN ),
						'dependency' => array(
							'element' => 'content_type', 
							'value' => array('custom_text'),
						),
		            ),
		            // array(
		            //     "type" => "textfield",
		            //     "heading" => __( "Limit", AT_ADMIN_TEXTDOMAIN ),
		            //     "param_name" => "limit",
		            //     "value" => "3",
		            //     "description" => __( "Specify post limit.", AT_ADMIN_TEXTDOMAIN ),
		            // ),
		            array(
		                "type" => "range",
		                "heading" => __( "Limit", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "limit",
		                "value" => "12",
		                "min" => "0",
		                "max" => "20",
		                "step" => "1",
		                "unit" => '',
		                "description" => __( "Select limit to display on this block", AT_ADMIN_TEXTDOMAIN )
		            ),
		            array(
		                "type" => "dropdown",
		                "heading" => __( "Carousel", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "carousel",
		                "width" => 'carousel',
		                "value" => array(
		                    "Use carousel" => "carousel",
		                    "Static" => "static",
		                ),
		                "description" => ''
		            ),

		            array(
		                "type" => "range",
		                "heading" => __( "View post", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "spans",
		                "value" => "4",
		                "min" => "0",
		                "max" => "6",
		                "step" => "1",
		                "unit" => '',
		                "description" => __( "Select limit to display on one row", AT_ADMIN_TEXTDOMAIN )
		            ),
		            array(
		                "type" => "textfield",
		                "heading" => __( "Extra class name", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "el_class",
		                "value" => "",
		                "description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in Custom CSS Shortcode or Masterkey Custom CSS option.", AT_ADMIN_TEXTDOMAIN )
		            )
		        )
		    );
		}
		$shortcode_id = self::_shortcode_id();

        extract( shortcode_atts( array(
                    'title' => '',
                    'limit' => 3,
                    'trim' => 80,
                    'spans' => 4,
                    'disable' => '',
					'carousel' => 'carousel',
                    'el_class' => '',
                    'width'=> '1/1',
                    'post_type' => 'post',
                ), $atts ) );

        $style = '';

        if ( $carousel == 'static' ) {
        	$style .=' style="';
        	$style .= 'max-width:' . (100/$spans) . '%';
        	$style .= '"';
        }

        $width = wpb_translateColumnWidthToSpan( $width );

		$dealers = AT_Loader::get_instance()->model('user_model');
		$results = $dealers->get_dealers(0, $limit);
		$total = $dealers->get_dealers_count();
		$output = '';
		if( count( $results > 0 ) ) {
			$c = 0;

			$class="dealer-wrapper";
			$output .= '<div class="' . $class . '">';
			// $output .= '<a href="#" class="all">Show all...</a>';
			$output .= '<div class="results"><span>' . sprintf( __( 'Total %s dealers', AT_TEXTDOMAIN ), $total ) . '</span></div>';

			$output .= '<div class="clear"></div>';
			$output .= '<div class="tabs_' . $carousel . '">';
			foreach( $results as $dealer) {
				$c++;
				
				$url = AT_Common::site_url( 'dealer/info/' . trim( $dealer['alias'] . '-'  . $dealer['id'], '-') . '/' );

				if ( !file_exists( $dealer['photo']['photo_path'] . '138x138/' . $dealer['photo']['photo_name'] ) ) {
					$photo = AT_URI . '/assets/images/default-dealer-small.jpg';
				} else {
					$photo = $dealer['photo']['photo_url'] . '138x138/' . $dealer['photo']['photo_name'];
				}
				$output .= '<div class="slide"' . $style . '>';
				$output .= '<a class="thumb img" href="' . $url . '"><img src="' . $photo . '" /></a>';
				$output .= '<a class="title" href="' . $url . '">' . $dealer['name'] . '</a>';
				$output .= '</div>';
			}
			$output .= '</div>';
			$output .= '</div>';
		}
		$output .= '<div class="clear"></div>';
        return $output;
    }
}