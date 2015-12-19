<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Autogallery_VC_ShortCode extends AT_VC_ShortCode{

	public static function at_auto_gallery ( $atts = null, $content = null ) {
			if( $atts == 'generator' ) {
			return array(
		        "name"      => __( "Slider: Auto Gallery", AT_ADMIN_TEXTDOMAIN ),
		        "base"      => "at_auto_gallery",
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
		                "heading" => __( "Display options", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "display",
		                "width" => 200,
		                "value" => array(
		                    "Show all item" => "all",
		                    "Only featured" => "featured",
		                ),
		                "description" => ''
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
		                "value" => array_merge(
		                    array("Any" => "0"),
		                	AT_VC_Helper::get_manufacturers()
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
                    'manufacturer_id' => 0,
                    'model_id' => 0,
                    'display' => 'all',
                    'limit' => 5,
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
		if ( $display == "featured" ) {
			$params['featured'] = true;
		}

        $cars = $car_model->get_cars( $params, $offset = 0 , $limit );

		if ( count($cars) > 0 ) {
	        $output = '<div class="home_slider ' . $el_class . '">
								<div class="slider slider_1" data-settings=\'{
									"auto": ' . $autoplay . ',
									"slideWidth": 940,
									"pause": ' . $pause . ',
									"minSlides": 1,
									"infiniteLoop" : true,
									"maxSlides": 1,
									"slideMargin": 0,
									"controls" : false}\'>';
			foreach ($cars as $key => $car) {
				$title = $car['options']['_manufacturer_id']['name'] . ' ' . $car['options']['_model_id']['name'];

				if ( isset( $car['photo']['photo_name'] ) && file_exists( $car['photo']['photo_path'] . 'original/' . $car['photo']['photo_name'] ) ) {
					$static = $car['photo']['photo_url'] . 'original/' . $car['photo']['photo_name'];
					$image = '<img src="' . AT_Common::static_url( $static ) . '" alt="' . $title . '"/>';
				} else {
					if ( has_post_thumbnail() ) {
						$image = get_the_post_thumbnail( $post_id = $car['ID'], $size = array( 640, 428 ) );
					} else {
						$image = '<img src="' . AT_Common::site_url( AT_URI_THEME . '/framework/assets/images/pics/noimage-large.jpg' ) . '" alt="' . $title . '"/>';
					}
				}

				$cost = AT_Common::show_full_price($value = $car['options']['_price'], $currency = $car['options']['_currency_id']);

				$output .= '<div class="slide" data-onclick="location.href=' . get_permalink( $car['ID'] ) . '">
								<a title="' . $title . '" href="' . get_permalink( $car['ID'] ) . '">' . $image . '</a>
								<div class="description">
									<a title="' . $title . '" href="' . get_permalink( $car['ID'] ) . '"><h2 class="title">' . $car['options']['_fabrication'] . ' ' . $title . '</h2></a>
									<p class="desc">' .
										(!empty($car['options']['_mileage']) ? '<span><strong>' .  AT_Common::car_mileage( 0 ) . ': </strong>' . number_format((int)$car['options']['_mileage'], 0, '', ',') . '</span>' : '' ) .
										(!empty($car['options']['_cilindrics']) ? '<span><strong>' . __( 'Engine', AT_TEXTDOMAIN ) . ': </strong>' . $car['options']['_cilindrics'] . ' ' . __( 'cm³', AT_TEXTDOMAIN ) . '</span> ' : '' ) .
									'</p>
									<div class="price">' . $cost . '</div>
								</div>
							</div>';
			}
			$output .= '</div>
					</div>';
		} else {
			$output = '<div class="home_slider ' . $el_class . '">' . __( 'Login and add new vehicles.', AT_TEXTDOMAIN ) . '</div>';
		}
        return $output;
    }
}