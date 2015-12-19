<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Manufacturers_VC_ShortCode extends AT_VC_ShortCode{

	public static function at_manufacturers ( $atts = null, $content = null ) {
			if( $atts == 'generator' ) {
			return array(
		        "name"      => __( "Make", AT_ADMIN_TEXTDOMAIN ),
		        "base"      => "at_manufacturers",
				'icon'      => "im-icon-spinner-4",
		        "class"     => "at-manufacturers-class",
				'category'  => __("Theme Short-Codes", AT_ADMIN_TEXTDOMAIN ),
		        "params"    => array(
		            array(
		                "type" => "textfield",
		                "heading" => __( "Title", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "title",
		                "value" => "",
		                "description" => __( "Enter shortcode title.", AT_ADMIN_TEXTDOMAIN ),
						'dependency' => array(
							'element' => 'content_type', 
							'value' => array('custom_text'),
						),
		            ),
		            array(
		                "type" => "range",
		                "heading" => __( "Columns", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "columns",
		                "default" => "1",
		                "min" => "1",
		                "max" => "7",
		                "step" => "1",
		                "unit" => 'col',
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
                    'columns' => '1',
                    'el_class' => '',
                ), $atts ) );

        $car_model = AT_Loader::get_instance()->model('car_model');

        $manufacturers = $car_model->get_manufacturers();

        $output = '';
		if ( !empty($title) ) { $output .= '<h2>' . $title . '</h2>'; }

		if ( $columns > 1 ) {
			$output .= '<div class="auto-columns col-' . $columns . '">';
		}

        $output .= '<ul>';
        foreach ( $manufacturers as $manufacturer ) {
            $output .= '<li><a href="' . AT_Common::site_url('catalog/' . $manufacturer['alias'] ) . '">' . $manufacturer['name'] . '</a></li>';
        }
        $output .= '</ul>';

		if ( $columns > 1 ) {
			$output .= '</div>';
		}

		$output .= '<div class="clear"></div>';

        return $output;
    }
}