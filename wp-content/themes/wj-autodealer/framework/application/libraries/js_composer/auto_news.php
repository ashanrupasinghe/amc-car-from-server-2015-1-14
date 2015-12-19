<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Autonews_VC_ShortCode extends AT_VC_ShortCode{

	public static function at_auto_news ( $atts = null, $content = null ) {
		if( $atts == 'generator' ) {
			return array(
		        "name"      => __( "Auto news vertical column", AT_ADMIN_TEXTDOMAIN ),
		        "base"      => "at_auto_news",
				'icon'      => "im-icon-spinner-4",
		        "class"     => "at-auto-news-class",
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
		                "type" => "range",
		                "heading" => __( "Limit", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "limit",
		                "value" => "2",
		                "min" => "0",
		                "max" => "20",
		                "step" => "1",
		                "unit" => '',
		                "description" => __( "Select limit to display on this block", AT_ADMIN_TEXTDOMAIN )
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
                    'limit' => 2,
                    'el_class' => '',
                    'width'=> '1/1',
                ), $atts ) );

        $width = wpb_translateColumnWidthToSpan( $width );

		$query = array(
			'post_type' => 'news',
			'posts_per_page' => $limit,
			'nopaging' => 0,
			'ignore_sticky_posts' => 1
		);

		$results = new WP_Query();
		$results->query( $query );

        $output = '<div class="news_wrapper ">';
        $output = '<div class="news ">';
		$output .= '<h2>' . $title . '</h2>';
		if( $results->have_posts() ) {
			$c = 0;
			while( $results->have_posts() ) {
				$c++;
				$class="news_box";
				$results->the_post();
				$output .= '<div class="' . $class . '">';
				if ( has_post_thumbnail() ) {
					$output .= '<a class="thumb" href="' . get_permalink() . '">' . get_the_post_thumbnail( $post_id = get_the_ID(), $size = array( 178, 178 ) ) . '</a>';
				}
				$output .= '<h5><a href="' . get_permalink() . '">' . strtoupper( get_the_title() ) . '</a></h5>';
				$output .= '<div class="date">' . get_the_date() . '</div>';
				$output .= '<div class="post"><p>' . AT_Common::truncate( $content = get_the_excerpt(), $limit = 40 ) . '</p></div>';
				$output .= '</div>';
				if ( $c === 2 ) {
					$class .= ' last';
					$output .= '<div class="clear"></div>';
					$c = 0;
				}
			}
		}
		$output .= '<div class="all_wrapper"><a class="all_news btn7" href="' . get_post_type_archive_link( 'news' ) . '">' . __( "All news", AT_TEXTDOMAIN ) . '</a></div>';
		$output .= '</div>';
		$output .= '</div>';
		wp_reset_postdata();
        return $output;
    }
}