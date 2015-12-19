<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Sliderposts_VC_ShortCode extends AT_VC_ShortCode{

	public static function at_slider_posts ( $atts = null, $content = null ) {
			if( $atts == 'generator' ) {
			return array(
		        "name"      => __( "Slider: Posts", AT_ADMIN_TEXTDOMAIN ),
		        "base"      => "at_slider_posts",
				'icon'      => "im-icon-spinner-4",
		        "class"     => "at-auto-gallery-class",
				'category'  => __("Theme Short-Codes", AT_ADMIN_TEXTDOMAIN ),
		        "params"    => array(
		            array(
		                "type" => "range",
		                "heading" => __( "Limit", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "limit",
		                "value" => "5",
		                "min" => "0",
		                "max" => "12",
		                "step" => "1",
		                "unit" => '',
		                "description" => __( "Select limit to display on this block", AT_ADMIN_TEXTDOMAIN )
		            ),
		            array(
		                "type" => "dropdown",
		                "heading" => __( "Post type", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "post_type",
		                "width" => 200,
		                "value" => array(
		                    "Posts" => "post",
		                    "News" => "news",
		                    "Reviews" => "reviews",
		                ),
		                "description" => ''
		            ),
		            array(
		                "type" => "dropdown",
		                "heading" => __( "Autoplay", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "autoplay",
		                "width" => 200,
		                "value" => array(
		                    "Yes" => "true",
		                    "No" => "false",
		                ),
		                "description" => ''
		            ),
		            array(
		                "type" => "textfield",
		                "heading" => __( "Pause", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "pause",
		                "value" => "4000",
		                "description" => __( "Specify slideshow timeout in ms.", AT_ADMIN_TEXTDOMAIN )
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
                    'limit' => 5,
                    'post_type' => 'post',
                    'autoplay' => 'false',
                    'pause' => '4000',
                    'el_class' => '',
                    'width'=> '1/1',
                ), $atts ) );

        $width = wpb_translateColumnWidthToSpan( $width );


		$query = array(
			'post_type' => $post_type,
			'showposts' => $limit,
			'nopaging' => false,
			'ignore_sticky_posts' => 1
		);
		$results = new WP_Query();
		$results->query( $query );
		if( $results->have_posts() ) {
        $output = '<div class="home_slider ' . $el_class . '">
							<div class="slider slider_1" data-settings=\'{
								"auto": ' . $autoplay . ',
								"slideWidth": 940,
								"pause": ' . $pause . ',
								"minSlides": 1,
								"infiniteLoop" : "true",
								"maxSlides": 1,
								"slideMargin": 0,
								"controls" : false}\'>';
		while( $results->have_posts() ) {
			$results->the_post();
			$title = get_the_title();


			if ( has_post_thumbnail() ) {
				$image = get_the_post_thumbnail( $post_id = get_the_ID(), $size = array( 640, 428 ) );
			} else {
				$image = '<img src="' . AT_Common::site_url( AT_URI_THEME . '/framework/assets/images/pics/noimage-large.jpg' ) . '" alt="' . $title . '"/>';
			}


			$output .= '<div class="slide" data-onclick="location.href=' . get_permalink( get_the_ID() ) . '">
							<a title="' . $title . '" href="' . get_permalink( get_the_ID() ) . '">' . $image . '</a>
							<div class="description">
								<a title="' . $title . '" href="' . get_permalink( get_the_ID() ) . '"><h2 class="title">' . $title . '</h2></a>
								<p class="desc">' . AT_Common::truncate( $content = get_the_excerpt(), $limit = 80 ) . '</p>
							</div>
						</div>';
		}
		$output .= '</div>
				</div>';
		} else {
			$output = '<div class="home_slider ' . $el_class . '">' . __("Please add more posts.", AT_TEXTDOMAIN ) . '</div>';
		}
        return $output;
    }
}