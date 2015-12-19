<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Searchauto_VC_ShortCode extends AT_VC_ShortCode{

	public static function at_search_auto ( $atts = null, $content = null ) {
		if( $atts == 'generator' ) {
			$shortcode_options = array();
			$view = new AT_View;
			if ( is_array($view->get_option( 'shortcode_search_forms', array())) ) {
	        	foreach ($view->get_option( 'shortcode_search_forms', array()) as $key => $value) {
	        		$shortcode_options['Item ' . ($key + 1) ] = $key;
	        	}
	        }
			
			return array(
		        "name"      => __( "Search auto", AT_ADMIN_TEXTDOMAIN ),
		        "base"      => "at_search_auto",
				'icon'      => "im-icon-spinner-4",
		        "class"     => "at-search-auto-class",
				'category'  => __("Theme Short-Codes", AT_ADMIN_TEXTDOMAIN ),
		        "params"    => array(
		            array(
		                "type" => "textfield",
		                "heading" => __( "Title", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "title",
		                "value" => "",
		                "description" => '',
						'dependency' => array(
							'element' => 'content_type', 
							'value' => array('custom_text'),
						),
		            ),
		            array(
		                "type" => "dropdown",
		                "heading" => __( "Shortcode Options", AT_ADMIN_TEXTDOMAIN ),
		                "param_name" => "shortcode_options",
		                "width" => 200,
		                "value" => $shortcode_options,
		                "description" => __( '<b>You can edit settings form`s fields in <a target="_blank" href="admin.php?page=at_site_options_catalog">Catalog Site Options</a> ( "Shortcode Search Form" )</b>', AT_ADMIN_TEXTDOMAIN ),
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
                    'el_class' => '',
                    'shortcode_options' => '0',
                    'width'=> '1/1',
                ), $atts ) );
        
        $view = new AT_View;
        $shortcode_search_forms = $view->get_option( 'shortcode_search_forms', array() );

        if ( $shortcode_options == '' || empty( $shortcode_search_forms ) || !isset( $shortcode_search_forms[$shortcode_options] ) ) {
        	return '';
        }
        $width = wpb_translateColumnWidthToSpan( $width );

		$view->add_script( 'jquery.selectik', 'assets/js/jquery/jquery.selectik.js');
		$view->add_script( 'catalog', 'assets/js/catalog.js', array( 'jquery' ));

        $output = '<form method="GET" class="vehicle-filter" action="' . AT_Common::site_url('catalog') . '/">
        				<div class="search_auto">';

        
		foreach ($shortcode_search_forms[$shortcode_options]['option'] as $key => $value) {
		  	switch ( $key ) { 
	  			case 'title':

	  				$output .= '<h3>' . $title . '</h3>';
	  			break;
	  			case 'transport_type':
					$output .= '<div class="categories">';
					foreach ($view->add_widget( 'reference_widget', array( 'method' => 'get_transport_types' ), true ) as $key => $value) {
						$output .= '<input class="transport_type" type="radio" id="search_radio_' . $value['id'] . '" value="' . $value['id'] . '" name="transport_type_id"' . ( $value['is_default'] ? 'checked="checked"' : '' ) . ' />
						<label for="search_radio_' . $value['id'] . '" title="' . $value['name'] . '"><i class="' . $value['alias'] . '"></i></label>';
					}
					$output .= '</div>';
				break;
				case 'manufacturer_model':
					$output .= '
							<div class="clear"></div>
							<label><strong>' . __( "Make", AT_TEXTDOMAIN ) . ':</strong></label>
							<div class="select_box_1">
								<select name="manufacturer_id" id="manufacturer_id" class="custom-select select_1 select2">';
									$output .= '<option value="0">' . __( "Any", AT_TEXTDOMAIN ) . '</option>';
									foreach ($view->add_widget( 'reference_widget', array( 'method' => 'get_manufacturers' ), true ) as $key => $value) {
										$output .= '<option value="' . $value['alias'] . '">' . $value['name'] . '</option>';
									}
									// foreach (AT_VC_Helper::get_manufacturers() as $value => $key) {
									// 	$output .= '<option value="' . $key. '">' . $value . '</option>';
									// }
									$output .= '
								</select>
							</div>
							<label><strong>' . __( "Model", AT_TEXTDOMAIN ) . ':</strong></label>
							<div class="select_box_1">
								<select name="model_id" id="model_id" class="custom-select select_1 select2">
									<option value="0">' . __( "Any", AT_TEXTDOMAIN ) . '</option>
								</select>
							</div>';
				break;
				case 'year':
					$output .= '<label><strong>' . __( "Year", AT_TEXTDOMAIN ) . ':</strong></label>
						<div class="select_box_2">
							<select name="fabrication_from" id="fabrication_from" class="custom-select select_2 select2">
								<option value="0">' . __( "From", AT_TEXTDOMAIN ) . '</option>';
								foreach (AT_VC_Helper::get_years_range() as $value) {
									$output .= '<option value="' . $value. '">' . $value . '</option>';
								}
								$output .= '
							</select>
							<select name="fabrication_to" id="fabrication_to" class="custom-select select_2 select2">
								<option value="0">' . __( "To", AT_TEXTDOMAIN ) . '</option>';
								foreach (AT_VC_Helper::get_years_range() as $value) {
									$output .= '<option value="' . $value. '">' . $value . '</option>';
								}
								$output .= '
							</select>
							<div class="clear"></div>
						</div>';
				break;
				case 'price':
					$output .= '<label><strong>' . __( "Price", AT_TEXTDOMAIN ) . ':</strong></label>
							<div class="select_box_2">
								<input type="text" name="price_from" placeholder="' . __( 'From', AT_TEXTDOMAIN ) . '" id="price_from" value="" class="txb custom-text"/>
								<input type="text" name="price_to" placeholder="' . __( 'To', AT_TEXTDOMAIN ) . '" id="price_to" value="" class="txb custom-text"/>
								<div class="clear"></div>
							</div>';
				break;
				case 'mileage':
					$output .= '<label><strong>' . __( "Mileage", AT_TEXTDOMAIN ) . ':</strong></label>
							<div class="select_box_2">' .
								'<input type="text" name="mileage_from" placeholder="' . __( 'From', AT_TEXTDOMAIN ) . '" id="mileage_from"  value="" class="txb custom-text"/>
								<input type="text" name="mileage_to" placeholder="' . __( 'To', AT_TEXTDOMAIN ) . '" id="mileage_to" value="" class="txb custom-text"/>
								<div class="clear"></div>
							</div>';
				break;
				case 'body_type':
					$output .= '<label><strong>' . __( 'Body type:', AT_TEXTDOMAIN ) . '</strong></label>
						<div class="select_box_1">
							<select class="custom-select select_3" id="body_type_id">
								<option value="0">' . __( 'Any', AT_TEXTDOMAIN ) . '</option>';
								foreach ($view->add_widget( 'reference_widget', array( 'method' => 'get_body_types' ), true ) as $key => $value) {
									$output .= '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
								}
							$output .= '</select>
						</div>';
				break;
				case 'fuel':
					$output .= '<label><strong>' . __( 'Fuel:', AT_TEXTDOMAIN ) .'</strong></label>
							<div class="select_box_1">
								<select class="custom-select select_3" id="fuel_id">
									<option value="0">' . __( 'Any', AT_TEXTDOMAIN ) .'</option>';
									foreach ($view->add_widget( 'reference_widget', array( 'method' => 'get_fuels' ), true ) as $key => $value) {
										$output .= '<option value="' . $value['id'] . '">' . $value['name'] .'</option>';
									}
								$output .= '</select>
							</div>';
				break;
				case 'transmission':
					$output .= '<label><strong>' . __( 'Transmission:', AT_TEXTDOMAIN ) . '</strong></label>
						<div class="select_box_1">
							<select class="custom-select select_3" id="transmission_id">
								<option value="0">' . __( 'Any', AT_TEXTDOMAIN ) . '</option>';
								foreach ($view->add_widget( 'reference_widget', array( 'method' => 'get_transmissions' ), true ) as $key => $value) {
									$output .= '<option value="' .  $value['id'] . '">' . $value['name'] . '</option>';
								}
							$output .= '</select>
						</div>';
				break;
				case 'doors':
					$output .= '<label><strong>' . __( 'Doors:', AT_TEXTDOMAIN ) .'</strong></label>
						<div class="select_box_1">
							<select class="custom-select select_3" id="door_id">
								<option value="0">' . __( 'Any', AT_TEXTDOMAIN ) .'</option>';
								foreach ($view->add_widget( 'reference_widget', array( 'method' => 'get_doors' ), true ) as $key => $value) {
									$output .= '<option value="' . $value['id'] . '">' . $value['name'] .'</option>';
								}
							$output .= '</select>
						</div>';
				break;
				case 'region':
					$output .= '<label><strong>' .__( 'Region:', AT_TEXTDOMAIN ) . '</strong></label>
						<div class="select_box_1">
							<select class="custom-select select_3" id="region_id">
								<option value="0">' . __( 'Any', AT_TEXTDOMAIN ) . '</option>';
								foreach ($view->add_widget( 'reference_widget', array( 'method' => 'get_regions' ), true ) as $key => $value) {
									$output .= '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
								}
							$output .= '</select>
						</div>';
				break;

				case 'region_state':
					$output .= '
							<div class="clear"></div>
							<label><strong>' . __( "Country", AT_TEXTDOMAIN ) . ':</strong></label>
							<div class="select_box_1">
								<select name="region_id" id="region_id" class="custom-select select_1 select2">';
									$output .= '<option value="0">' . __( "Any", AT_TEXTDOMAIN ) . '</option>';
									foreach ($view->add_widget( 'reference_widget', array( 'method' => 'get_regions' ), true ) as $key => $value) {
										$output .= '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
									}
									// foreach (AT_VC_Helper::get_manufacturers() as $value => $key) {
									// 	$output .= '<option value="' . $key. '">' . $value . '</option>';
									// }
									$output .= '
								</select>
							</div>
							<label><strong>' . __( "State", AT_TEXTDOMAIN ) . ':</strong></label>
							<div class="select_box_1">
								<select name="state_id" id="state_id" class="custom-select select_1 select2">
									<option value="0">' . __( "Any", AT_TEXTDOMAIN ) . '</option>
								</select>
							</div>';
				break;

				case 'drive':
					$output .= '<label><strong>' . __( 'Drive:', AT_TEXTDOMAIN ) .'</strong></label>
						<div class="select_box_1">
							<select class="custom-select select_3" id="drive_id">
								<option value="0">' . __( 'Any', AT_TEXTDOMAIN ) . '</option>';
								foreach ($view->add_widget( 'reference_widget', array( 'method' => 'get_drive' ), true ) as $key => $value) {
									$output .= '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
								}
							$output .= '</select>
						</div>';
				break;
				case 'color':
					$output .= '<label><strong>' . __( 'Color:', AT_TEXTDOMAIN ) . '</strong></label>
						<div class="select_box_1">
							<select class="custom-select select_3" id="color_id">
								<option value="0">' . __( 'Any', AT_TEXTDOMAIN ) .'</option>';
								foreach ($view->add_widget( 'reference_widget', array( 'method' => 'get_colors' ), true ) as $key => $value) {
									$output .= '<option data-selectik="<span style=\'background-color:' . $value['alias'] . '\'></span>' . $value['name'] . '" data-color="' . $value['alias'] . '" value="' . $value['id'] . '">' . $value['name'] . '</option>';
								}
							$output .= '</select>
						</div>';
				break;
				case 'cilindrics':
					$output .= '<label><strong>' . __( 'Engine, cm³:', AT_TEXTDOMAIN ) . '</strong></label>
						<div class="select_box_2">
							<select class="custom-select select_4" name="engine_from" id="engine_from">
								<option value="0">' . __( 'From', AT_TEXTDOMAIN ) . '</option>';
								$car_engine_range = $view->get_option( 'car_engine_range', array( 'min' => 900, 'max' => 6500 ) );
								for($i = $car_engine_range['min']; $i <= $car_engine_range['max']; $i=$i+100) {
									$output .= '<option value="' . $i . '">' . $i . '</option>';
								}
							$output .= '</select>
							<select class="custom-select select_4" name="engine_to" id="engine_to">
								<option value="0">' . __( 'To', AT_TEXTDOMAIN ) . '</option>';
								$car_engine_range = $view->get_option( 'car_engine_range', array( 'min' => 900, 'max' => 6500 ) );
								for($i = $car_engine_range['min']; $i <= $car_engine_range['max']; $i=$i+100) {
									$output .= '<option value="' . $i . '">' . $i . '</option>';
								}
							$output .= '</select>
							<div class="clear"></div>
						</div>';
				break;
				case 'only_new_car':
					$output .= '<div class="chb_wrapper">
								<input id="cars_categories" name="cars_categories" type="checkbox" value="new" />
								<label class="check_label" for="cars_categories">' . __( "Only new cars", AT_TEXTDOMAIN ) . '</label>
							</div>';
				break;
				case 'submit':
					$output .= '<input type="submit" value="' . __( "Search", AT_TEXTDOMAIN ) . '" class="btn_search btn5 float-right" id="search_car_shortcode"/>';
				break;
	  		}
	  	}
		$output .= '<div class="clear"></div>
				</div>
				<input type="hidden" name="view" value="' . $view->get_option( 'catalog_car_type_view_default', 'list' ) . '"/>
				<div class="clear"></div>
				</form>';
        return $output;
    }
}