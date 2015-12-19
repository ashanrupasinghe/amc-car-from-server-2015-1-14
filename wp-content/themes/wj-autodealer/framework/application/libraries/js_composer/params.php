<?php
if (!defined("AT_DIR")) die('!!!');

class AT_Shortcode_params {

	static public function init(){
		$js_uri = AT_URI . '/' . AT_JS_COMPOSER__JS;
		if ( function_exists( 'add_shortcode_param' ) ) {
			add_shortcode_param('range', array( 'AT_Shortcode_params', 'at_wbp_range' ), $js_uri .'/range.js');
			add_shortcode_param('switch', array( 'AT_Shortcode_params', 'at_wbp_switch' ), $js_uri .'/bootstrap.switch.js');
			add_shortcode_param('at_upload', array( 'AT_Shortcode_params', 'at_wbp_upload' ), $js_uri .'/upload.js');
			add_shortcode_param('volume', array( 'AT_Shortcode_params', 'at_wbp_volume' ), $js_uri .'/range.js');
			add_shortcode_param('number', array( 'AT_Shortcode_params', 'at_wbp_number' ));
			add_shortcode_param('at_textfield', array( 'AT_Shortcode_params', 'at_wbp_textfield' ));
			add_shortcode_param('at_color_small', array( 'AT_Shortcode_params', 'at_wbp_color_small' ));
			add_shortcode_param('at_color', array( 'AT_Shortcode_params', 'at_wbp_color' ));
			add_shortcode_param('at_icon', array( 'AT_Shortcode_params', 'at_wbp_icon' ), $js_uri .'/icons.js' );
		}
		if ( function_exists('vc_add_param') ) {
			self::_custom_params();
		}
	}

	/**
	 * Range
	 */
	static public function at_wbp_range($param, $param_value) {
		$dependency = vc_generate_dependencies_attributes($param);
		$value = $param_value;
		$value = $param_value;
		$html = '<div class="at-range-option at-range-input"><input name="'.$param['param_name'].'" min="'.$param['min'].'" max="'.$param['max'].'" step="'.$param['step'].'" class="range-input-selector range-input-composer wpb_vc_param_value '.$param['param_name'].' '.$param['type'].'" type="range" value="'.$value.'" ' . $dependency . '/><span class="value">' . $value . '</span><span class="unit">' . $param['unit'] . '</span></div>';
		return $html;
	}										

	static public function at_wbp_switch($param, $param_value) {
		$dependency = vc_generate_dependencies_attributes($param);

		$value = $param_value;
		$out  = '';
		$extra = '';

		$checked = '';

		if ( $value == 'on' ) {
			$checked .= ' checked';
		}

		if ( isset($param['on']) ) {
			$extra .= ' data-on-label="' . $param['on'] . '"';
		}
		if ( isset($param['off']) ) {
			$extra .= ' data-off-label="' . $param['off'] . '"';
		}

		$out .= '<div class="at-switch-option at-switch-checkbox switch switch-square"' . $extra . '>';
		$out .= '<input name="'.$param['param_name'].'_switch" type="checkbox" class="switch-input-selector switch-input-composer '.$param['param_name'].' '.$param['type'].'" data-toggle="switch"' . $checked . ' />';
		$out .= '<input name="'.$param['param_name'].'" value="' . $value . '" class="switch-param switch-input-selector switch-input-composer wpb_vc_param_value '.$param['param_name'].' '.$param['type'].'" type="hidden" ' . $dependency . '/>';
		$out .= '</div>';

		return $out;
	}

	/**
	 * Upload
	 */
	static public function at_wbp_upload($param, $param_value) {
		$dependency = vc_generate_dependencies_attributes($param);

		// $regexp = "/^.*\.(jpg|jpeg|png|gif)$/i";

		$value = $param_value;

		$html = '<div class="at-upload upload-option" ' . $dependency . '>';
		$html .= '<input class="at-upload-url wpb_vc_param_value '.$param['param_name'].' '.$param['type'].'" type="text" id="' . $param['param_name']. '" name="' . $param['param_name'] . '" size="50"  value="'.$value.'" ' . $dependency . ' /><a class="option-upload-button thickbox" id="' . $param['param_name'] . '_button" href="#">'.__( 'Upload', AT_ADMIN_TEXTDOMAIN ).'</a>';
		$html .= '<span id="'.$param['param_name'].'-preview" class="show-upload-image" alt="">';

		if ( isset($value) && !empty($value) ) {
			$ext = explode('.', $value);

	// print_r($ext);

			$ext_c = count( $ext ) - 1;
			$ext = $ext[ $ext_c ];


			$_audio = array('mp3','wav','wma');
			$_video = array('mov','avi','webm','mp4');
			$_image = array('jpg','png','jpeg','gif');

			if ( in_array($ext, $_video) ) {
				$html .= '<video src="'.$value.'" style="max-width: 100%" class="preview" controls />';
			}

			if ( in_array($ext, $_audio) ) {
				$html .= '<audio src="'.$value.'" style="max-width: 100%" class="preview" controls />';
			}

			if ( in_array($ext, $_image) ) {
				$html .= '<img src="'.$value.'" title="" style="max-width: 100%" class="preview" />';
			}

		}

		$html .= '</span></div>';

		return $html;
	}

	/**
	 * Volume
	 */
	static public function at_wbp_volume($param, $param_value) {
		$dependency = vc_generate_dependencies_attributes($param);
		$value = $param_value;
		$value = ($param_value) ? $param_value : $param['default'];
		$html = '<div class="at-range-option at-range-input">
		<div class="volume icon"><i class="at-icon-volume-mute-2"></i></div>
		<input name="'.$param['param_name'].'" min="0.0" max="1.0" step="0.1" class="range-input-selector range-input-composer wpb_vc_param_value volume '.$param['param_name'].' '.$param['type'].'" type="range" value="'.$value.'" ' . $dependency . '/>
		<div class="volume icon"><i class="at-icon-volume-low"></i></div>
		<span class="value">' . ( $value * 100 ) . '</span><span class="unit">%</span></div>';

		return $html;
	}

	/**
	 * Number
	 */
	static public function at_wbp_number($param, $param_value) {
		$dependency = vc_generate_dependencies_attributes($param);
		$value = $param_value;
		$value = $param_value;

		$html = '<div class="at-range-option at-range-input"><input name="'.$param['param_name'].'" min="'.$param['min'].'" max="'.$param['max'].'" step="'.$param['step'].'" class="range-input-selector range-input-composer wpb_vc_param_value '.$param['param_name'].' '.$param['type'].'" type="number" value="'.$value.'" ' . $dependency . '/> <span class="unit">' . $param['unit'] . '</span></div>';

		return $html;
	}

	/**
	 * textfield
	 */
	static public function at_wbp_textfield($param, $param_value) {
		$dependency = vc_generate_dependencies_attributes($param);
	    $value = $param_value;
	    $value = $param_value;
	    $html = '<input size="80" name="'.$param['param_name'].'" class="wpb_vc_param_value wpb-textinput '.$param['param_name'].' '.$param['type'].'" type="text" value="'.$value.'" ' . $dependency . '/>';
	    $margin_bottom = isset( $param['margin_bottom'] ) ? $param['margin_bottom'] : '0';
	    $html .= '<div style="margin-bottom:'.$margin_bottom.'px"></div>';

		return $html;
	}

	/**
	 * Color
	 */
	static public function at_wbp_color_small($param, $param_value) {
		$dependency = vc_generate_dependencies_attributes($param);
		$value = $param_value;
		$value = $param_value;
		$html = '<input name="'.$param['param_name'].'" class="color-picker wpb_vc_param_value '.$param['param_name'].' '.$param['type'].'" type="minicolors" value="'.$value.'" ' . $dependency . '/>';
		$margin_bottom = isset( $param['margin_bottom'] ) ? $param['margin_bottom'] : '0';
		$html .= '<div style="margin-bottom:'.$margin_bottom.'px"></div>';
		return $html;
	}

	/**
	 * Color
	 */
	static public function at_wbp_color($param, $param_value) {
		$dependency = vc_generate_dependencies_attributes($param);
		$value = $param_value;
		$value = $param_value;
		$format = $param['format'] ? $param['format'] : 'rgba';
		$html  = '<div class="input-append color bootstrap-colorpicker" data-color="'.$value.'" data-color-format="' . $format . '" ' . $dependency . '/>';
		$html .= '<input type="text" class="color-picker wpb_vc_param_value '.$param['param_name'].' '.$param['type'].'" name="'.$param['param_name'].'" value="'.$value.'" data-color-format="' . $format . '" />';
		$html .= '<span class="add-on"><i style="background-color: ' . $value . ';"></i></span>';
		$html .= '</div>';
		$margin_bottom = isset( $param['margin_bottom'] ) ? $param['margin_bottom'] : '0';
		$html .= '<div style="margin-bottom:'.$margin_bottom.'px"></div>';
		return $html;
	}
	
	/**
	 * Icons
	 */
	static public function at_wbp_icon($param, $param_value) {
		$dependency = vc_generate_dependencies_attributes($param);
		$value = $param_value;
		$value = $param_value;
		$html = "";
		if ( is_admin() ) {
		    //$html .= '<script src="' . IRISHFRAMEWORK_JS_COMPOSER_URI .'/js/icons.js"></script>';
		}

		$html .= '<form class="at-filter-icons" action="#">';
		$html .= '<input autocomplete="off" size="60" placeholder="Search an icon..." type="text" class="page-composer-icon-filter" value="" name="icon-filter-by-name" />';
		$html .= '</form>';
		$html .= '<div class="btn-group" style="width:100%;"><a style="text-decoration: none;" href="#" class="btn at-toggle-icons">' . __('Show Icons', AT_ADMIN_TEXTDOMAIN ) . '</a><a class="btn disabled at-icon-preview"><i class="' . $value . '"></i></a></div>';
		$html .= '<div class="at-visual-selector at-font-icons-wrapper" style="display: none">';
		if( isset($param['encoding']) && $param['encoding'] == 'true') {
		     foreach ( $param['value'] as $option => $key ) {
		       if($key) {
		      $html .= '<a class="at_icon_selector" href="#" title="Class Name : '.$key.'" rel="'.$key.'"><i class="'.$key.'" ></i></a>';
		        } else {
		            $html .= '<a class="at-no-icon" href="#" rel="">x</a>';
		        }
		      }   
		} else {
		    foreach ( $param['value'] as $option => $key ) {
		    if($key) {
		        $html .= '<a class="at_icon_selector at_' . $key . '" href="#" title="Class: '.$key.'" rel="'.$key.'"><i class="'.$key.'" ></i><span class="hidden">' . $key .'</span></a>';
		        } else {
		            $html .= '<a class="at-no-icon" href="#" rel="">x</a>';
		        }
		    }
		}

		$html .= '<input name="'.$param['param_name'].'" id="'.$param['param_name'].'" class="wpb_vc_param_value '.$param['param_name'].' '.$param['type'].'" type="hidden" value="'.$value.'" ' . $dependency . '/>';
		$html .= '</div>';
		return $html;
	}

	
	static private function _custom_params(){

		$custom_params = array();

		// Horizontal Tabs
		$custom_params['#hTabs'] = array(
			'heading' => __( 'Display Style', AT_ADMIN_TEXTDOMAIN ),
			'description' => __( 'Select display style.', AT_ADMIN_TEXTDOMAIN ),
			'param_name' => 'el_class',
			'value' => array(
				__('Style #1', AT_ADMIN_TEXTDOMAIN ) => 'style1',
				__('Style #2', AT_ADMIN_TEXTDOMAIN ) => 'style2',
			),
			'type' => 'dropdown',
		);
		vc_add_param('vc_tabs', $custom_params['#hTabs']);

		$custom_params['#vc_accordion'] = array(
			'heading' => __( 'Display Style', AT_ADMIN_TEXTDOMAIN ),
			'description' => __( 'Select display style.', AT_ADMIN_TEXTDOMAIN ),
			'param_name' => 'el_class',
			'value' => array(
				__('Style #1', AT_ADMIN_TEXTDOMAIN ) => 'style1',
				__('Style #2', AT_ADMIN_TEXTDOMAIN ) => 'style2',
				__('Style #3', AT_ADMIN_TEXTDOMAIN ) => 'style3',
				__('Style #4', AT_ADMIN_TEXTDOMAIN ) => 'style4',
				__('Style #5', AT_ADMIN_TEXTDOMAIN ) => 'style5',
			),
			'type' => 'dropdown',
		);
		vc_add_param('vc_accordion', $custom_params['#vc_accordion']);

		$custom_params['#vc_row'] = array(
			'heading' => __( 'Display Style', AT_ADMIN_TEXTDOMAIN ),
			'description' => __( 'Select display style.', AT_ADMIN_TEXTDOMAIN ),
			'param_name' => 'el_class',
			'value' => array(
				__('Without borders', AT_ADMIN_TEXTDOMAIN ) => '',
				__('With borders on left and right sides', AT_ADMIN_TEXTDOMAIN ) => 'borders_left_right',
			),
			'type' => 'dropdown',
		);
		vc_add_param('vc_row', $custom_params['#vc_row']);
	}
}