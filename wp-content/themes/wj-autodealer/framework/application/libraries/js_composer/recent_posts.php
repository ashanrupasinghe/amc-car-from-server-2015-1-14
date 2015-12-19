<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Recentposts_VC_ShortCode extends AT_VC_ShortCode{

	public static function at_recent_posts ( $atts = null, $content = null ) {
			if( $atts == 'generator' ) {
			return array(
		        "name"      => __( "Recent Posts", AT_ADMIN_TEXTDOMAIN ),
		        "base"      => "at_recent_posts",
				'icon'      => "im-icon-spinner-4",
		        "class"     => "at-resent-posts-class",
				'category'  => __("Theme Short-Codes", AT_ADMIN_TEXTDOMAIN ),
		        "params"    => array(
		            array(
		                "type" => "textfield",
		                "heading" => __( "Title", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "title",
		                "value" => "",
		                "description" => __( "Description will appear below each chart.", AT_ADMIN_TEXTDOMAIN ),
						'dependency' => array(
							'element' => 'content_type', 
							'value' => array('custom_text'),
						),
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
		                "default" => 12,
		                "min" => 0,
		                "max" => 20,
		                "step" => 1,
		                "unit" => '',
		                "description" => __( "Select limit to display on this block", AT_ADMIN_TEXTDOMAIN )
		            ),
		            // array(
		            //     "type" => "textfield",
		            //     "heading" => __( "View Post", AT_ADMIN_TEXTDOMAIN ),
		            //     "param_name" => "view_post",
		            //     "value" => "3",
		            //     "description" => __( "Specify post limit.", AT_ADMIN_TEXTDOMAIN ),
		            // ),
		            array(
		                "type" => "textfield",
		                "heading" => __( "Description length", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "trim",
		                "value" => "80",
		                "description" => __( "Enter truncated content length in symbols.", AT_ADMIN_TEXTDOMAIN ),
		            ),

		            array(
		                "type" => "range",
		                "heading" => __( "View post", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "view_post",
		                "default" => "3",
		                "min" => "0",
		                "max" => "4",
		                "step" => "1",
		                "unit" => '',
		                "description" => __( "Select limit to display on one row", AT_ADMIN_TEXTDOMAIN )
		            ),

					array(
						'heading' => __( "Hide <small>(optional)</small>", AT_ADMIN_TEXTDOMAIN ),
						'description' => __( "You may hide away some screen items.", AT_ADMIN_TEXTDOMAIN ),
						'param_name' => "disable",
						'value' => array( 
							__("Date", AT_ADMIN_TEXTDOMAIN ) => "date",
							__("Featured image", AT_ADMIN_TEXTDOMAIN ) => "featured",
							__("Teaser", AT_ADMIN_TEXTDOMAIN ) => "excerpt",
						),
						'type' => 'checkbox',
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
	$view_post = 0;
        extract( shortcode_atts( array(
                    'title' => '',
                    'limit' => '3',
                    'trim' => 80,
                    'view_post' => '3',
                    'disable' => '',
                    'el_class' => '',
                    'width'=> '1/1',
                    'post_type' => 'post',
                ), $atts ) );
        $width = wpb_translateColumnWidthToSpan( $width );
        $cwidth = ( ( 100 - ( 2.5 * ( $view_post - 1 ) ) ) / $view_post);
		$query = array(
			'post_type' => $post_type,
			'showposts' => $limit,
			'nopaging' => false,
			'ignore_sticky_posts' => 1
		);
		$results = new WP_Query();
		$results->query( $query );
		$output = '<div class="recent_blog ">';
		$output .= '<h2>' . $title . '</h2>';
		$view_post = (int)$view_post;
		if( $results->have_posts() ) {
			$c = 0;
			while( $results->have_posts() ) {
				$c++;
				$class="post_block";
				$results->the_post();
				$margin = 2.5;
				if ( $c === $view_post ) {
					$class .= ' last';
					$margin = 0;
				}

				$output .= '<div class="' . $class . '" style="max-width: ' . $cwidth . '%; margin-right: ' . $margin . '%;">';

				// Show featured image
				if ( has_post_thumbnail() && (strpos($disable,'featured') === false) ) {

        // $car_model = AT_Loader::get_instance()->model('car_model');
        // $cars = array();
						// <a title="' . $title . '" href="' . get_permalink( $car['ID'] ) . '"><img src="' . AT_Common::static_url( $car['photo']['photo_path'] . 'original/' . $car['photo']['photo_name'] ) . '" alt="' . $title . '"/></a>

					$output .= '<a class="thumb" href="' . get_permalink() . '">' . get_the_post_thumbnail( $post_id = get_the_ID(), $size = array( 180, 180 ) );
					if ( get_post_meta( get_the_ID(), '_featured_video', true ) ) {
						$output .= '<i class="icon-youtube-play has_video"></i>';
					}
					$output .= '</a>';
				}
				$output .= '<h5><a href="' . get_permalink() . '">' . strtoupper( get_the_title() ) . '</a></h5>';

				// Show date
				if ( strpos($disable,'date') === false ) {
					$output .= '<div class="date">' . get_the_date() . '</div>';
				}
				// Show teaser
				if ( strpos($disable,'excerpt') === false ) {
					$output .= '<div class="post"><p>' . AT_Common::truncate( $content = get_the_excerpt(), $limit = $trim ) . '</p></div>';
				}
				$output .= '</div>';
				if ( $c === $view_post ) {
					$output .= '<div class="clear"></div>';
					$c = 0;
				}
			}
		}
		$output .= '<div class="clear"></div></div>';
        return $output;
    }
}