<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Calltoaction_VC_ShortCode extends AT_VC_ShortCode{

	public static function at_calltoaction ( $atts = null, $content = null ) {
			if( $atts == 'generator' ) {
			return array(
		        "name"      => __( "Call to action", AT_ADMIN_TEXTDOMAIN ),
		        "base"      => "at_calltoaction",
				'icon'      => "im-icon-spinner-4",
		        "class"     => "at-calltoaction-class",
				'category'  => __("Theme Short-Codes", AT_ADMIN_TEXTDOMAIN ),
		        "params"    => array(
		            array(
		                "type" => "dropdown",
		                "heading" => __( "Action", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "action",
		                "width" => 200,
		                "value" => array(
		                    "Search" => "banner_1",
		                    "Join" => "banner_2",
		                ),
		                "description" => ''
		            ),
		            array(
		                "type" => "dropdown",
		                "heading" => __( "Visibility", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "visible",
		                "width" => 200,
		                "value" => array(
		                    "Always visible" => "always",
		                    "Visible only for unauthorised visitors" => "guest",
		                    "Visible only for members" => "members",
		                ),
		                "description" => ''
		            ),
		            array(
		                "type" => "textfield",
		                "heading" => __( "Title", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "title",
		                "value" => "",
		                "description" => __( "Enter block caption.", AT_ADMIN_TEXTDOMAIN )
		            ),

		            array(
		                "type" => "textfield",
		                "heading" => __( "Link", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "url",
		                "value" => "",
		                "description" => __( "Internal or external URI.", AT_ADMIN_TEXTDOMAIN )
		            ),

		            array(
		                "type" => "textfield",
		                "heading" => __( "Button", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "button",
		                "value" => "",
		                "description" => __( "Button caption.", AT_ADMIN_TEXTDOMAIN )
		            ),
		            array(
		                "type" => "textarea",
		                "heading" => __( "Description", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "description",
		                "value" => "",
		                "description" => __( "Please enter few words about action.", AT_ADMIN_TEXTDOMAIN )
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
                    'url' => '',
                    'visible' => 'guest',
                    'description' => '',
                    'button' => '',
                    'action' => '',
                    'el_class' => '',
                    'width'=> '1/1',
                ), $atts ) );

        $width = wpb_translateColumnWidthToSpan( $width );



        $output = '<div class="main_banner ' . $action . ' ' . $el_class . '">';
		$output .= '<div class="text_wrapper">';
		if ( !empty($title) ) {
			$output .= '<p class="title">' . $title . '</p>';
		}

		if ( !empty($description) ) {
			$output .= '<p class="desc">' . $description . '</p>';
		}
		$output .= '</div>';

		if ( !empty($url) ) {
			$output .= '<a href="' . $url . '" class="btn4">' . $button . '</a>';
		}

		$output .= '</div>';

		// OUTPUT
		if ( ( AT_Common::is_user_logged() == false && $visible == 'guest' ) || ( $visible == 'always' ) || ( $visible == 'member' && AT_Common::is_user_logged() ) ) {
	        return $output;
	    }
    }
}