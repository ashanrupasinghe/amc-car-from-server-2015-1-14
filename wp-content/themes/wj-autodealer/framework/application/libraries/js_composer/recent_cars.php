<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Recentcars_VC_ShortCode extends AT_VC_ShortCode{

	public static function at_recent_cars ( $atts = null, $content = null ) {
			if( $atts == 'generator' ) {
			return array(
		        "name"      => __( "Recent Listings", AT_ADMIN_TEXTDOMAIN ),
		        "base"      => "at_recent_cars",
				'icon'      => "im-icon-spinner-4",
		        "class"     => "at-recent-cars-class",
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
		                "value" => "12",
		                "min" => "0",
		                "max" => "20",
		                "step" => "1",
		                "unit" => '',
		                "description" => __( "Select limit to display on this block", AT_ADMIN_TEXTDOMAIN )
		            ),
		            array(
		                "type" => "dropdown",
		                "heading" => __( "Make", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "manufacturer_id",
		                "width" => 200,
		                "value" => AT_VC_Helper::get_manufacturers(),
		                "description" => ''
		            ),
		            array(
		                "type" => "dropdown",
		                "heading" => __( "Model", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "model_id",
		                "width" => 200,
		                "value" => array(
		                    "Any" => "0",
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
                    'title' => '',
                    'manufacturer_id' => 0,
                    'model_id' => 0,
                    'limit' => 12,
                    'autoplay' => 'false',
                    'pause' => '4000',
                    'el_class' => '',
                    'width'=> '1/1',
                ), $atts ) );

        $width = wpb_translateColumnWidthToSpan( $width );

        $car_model = AT_Loader::get_instance()->model('car_model');
        $cars = array();
        $params = array();
        if ( $manufacturer_id > 0 ) {
        	$params['manufacturer_id'] = $manufacturer_id;
        	if ( $model_id == 0 ) {
        		$params['model_id'] = $model_id;
        	} 
        }

        $cars = $car_model->get_cars( $params, $offset = 0 , $limit );

        $output = '<div class="recent ' . $el_class . '">
					<h2>' . $title . '</h2>
					<div class="recent_carousel" data-settings=\'{
						"auto": ' . $autoplay . ',
						"pause": ' . $pause . ',
						"slideWidth": 220,
						"minSlides": 1,
						"infiniteLoop": 1,
						"maxSlides": 4,
						"slideMargin": 20,
						"controls" : true,
						"pager" : false,
						"infiniteLoop": true
					}\'>';

		foreach ($cars as $key => $car) {
			if ( isset( $car['photo']['photo_name'] ) && file_exists( $car['photo']['photo_path'] . '213x164/' . $car['photo']['photo_name'] ) ) {
				$static = $car['photo']['photo_url'] . '213x164/' . $car['photo']['photo_name'];
				$image = '<img src="' . AT_Common::static_url( $static ) . '" alt="' . $title . '"/>';
			} else {
				if ( has_post_thumbnail() ) {
					$image = get_the_post_thumbnail( $post_id = get_the_ID(), $size = array( 213, 164 ) );
				} else {
					$image = '<img src="' . AT_Common::site_url( AT_URI_THEME . '/framework/assets/images/pics/noimage-small.jpg' ) . '" alt="' . $title . '"/>';
				}

			}
			// OLD WAY:
			// <img src="' . AT_Common::static_url( $car['photo']['photo_path'] . '213x164/' . $car['photo']['photo_name'] ) . '" alt="' . $car['options']['_manufacturer_id']['name'] . ' ' . $car['options']['_model_id']['name'] . '"/>

			$cost = AT_Common::show_full_price($value = $car['options']['_price'], $currency = $car['options']['_currency_id']);

			$output .= '<div class="slide">
							<a href="' . get_permalink( $car['ID'] ) . '">
							' . $image . '
								<div class="description">
									Registration ' . $car['options']['_fabrication'] . '<br/>' . 
									(!empty($car['options']['_cilindrics']) ? $car['options']['_cilindrics'] . ' cm³ ' : '' ) . 
									(!empty($car['options']['_fuel_id']['name']) ? $car['options']['_fuel_id']['name'] . '<br/>' : '' ) . 
									(!empty($car['options']['_engine_power']) ? $car['options']['_engine_power'] . ' HP<br/>' : '' ) . 
									(!empty($car['options']['_body_type_id']['name']) ? 'Body ' . $car['options']['_body_type_id']['name'] . '<br/>' : '' ) . 
									(!empty($car['options']['_mileage']) ? number_format((int)$car['options']['_mileage'], 0, '', ' ')  . ' ' . AT_Common::car_mileage(0) : '' ) .
								'</div>
								<div class="title">' . $car['options']['_manufacturer_id']['name'] . ' ' . $car['options']['_model_id']['name'] . ' <span class="price">' . $cost . '</span></div>
							</a>
						</div>';
		}
		$output .= '</div>
				</div>';
        return $output;
    }
}