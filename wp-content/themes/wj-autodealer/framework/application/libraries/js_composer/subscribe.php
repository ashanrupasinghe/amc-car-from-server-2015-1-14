<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Subscribe_VC_ShortCode extends AT_VC_ShortCode{

	public static function at_subscribe ( $atts = null, $content = null ) {
			if( $atts == 'generator' ) {
			return array(
		        "name"      => __( "Subscribe Form", AT_ADMIN_TEXTDOMAIN ),
		        "base"      => "at_subscribe",
				'icon'      => "im-icon-spinner-4",
		        "class"     => "at-subscribe-class",
				'category'  => __("Theme Short-Codes", AT_ADMIN_TEXTDOMAIN ),
		        "params"    => array(
		            array(
		                "type" => "textfield",
		                "heading" => __( "Title", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "title",
		                "value" => "",
		                "description" => __( "Enter block caption.", AT_ADMIN_TEXTDOMAIN )
		            ),
		            array(
		                "type" => "textfield",
		                "heading" => __( "Custom subscribe URL (optional)", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "action",
		                "value" => "",
		                "description" => __( "If you wish to use your own newsletter system, please enter custom URL here.", AT_ADMIN_TEXTDOMAIN )
		            ),
		            array(
		                "type" => "textfield",
		                "heading" => __( "Email field name (optional)", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "email",
		                "value" => "",
		            ),
		            array(
		                "type" => "textarea",
		                "heading" => __( "Custom FORM fields (optional)", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "fields",
		                "value" => "",
		                "description" => __( "If you wish to use your own newsletter system with custo fields, please enter below. HTML allowed.", AT_ADMIN_TEXTDOMAIN )
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
                    'email' => 'email',
                    'fields' => '',
                    'method' => 'GET',
                    'action' => '',
                    'el_class' => '',
                    'width'=> '1/1',
                ), $atts ) );

        $width = wpb_translateColumnWidthToSpan( $width );

        $output = '<div class="get_news_box ' . $el_class . '">';

		if ( !empty($title) ) {
			$output .= '<h3>' . $title . '</h3>';
		}

		if ( !empty($action) ) {
			$action = ' action="' . $action . '"';
		}

		$placeholder = __( 'Enter your email', AT_TEXTDOMAIN );

		$output .= '<form method="' . $method . '"' . $action . '>';

		$output .= '<input type="text" name="' . $email . '" onblur="if(this.value==\'\') this.value=\'' . $placeholder . '\';" onfocus="if(this.value==\'' . $placeholder . '\') this.value=\'\';" value="' . $placeholder . '" class="txb">';

		if ( !empty($fields) ) {
			$output .= $fields;
		}

		$output .= '<input type="submit" value="' . __( 'Subscribe', AT_TEXTDOMAIN ) . '" class="btn_subscribe btn4">';

		$output .= '</form>';

		$output .= '</div>';

        return $output;
    }
}