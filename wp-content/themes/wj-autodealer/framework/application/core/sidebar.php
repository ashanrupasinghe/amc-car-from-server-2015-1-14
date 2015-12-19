<?php
if (!defined("AT_DIR")) die('!!!');
class AT_Sidebars {
	
	static public function register(){
		$sidebars = array(
			/** Sidebars **/
			'primary' => array(
				'name' => __( 'Primary Widget Area', AT_ADMIN_TEXTDOMAIN ),
				'desc' => __( 'The primary widget area', AT_ADMIN_TEXTDOMAIN )
			),
			'catalog' => array(
				'name' => __( 'Catalog Widget Area', AT_ADMIN_TEXTDOMAIN ),
				'desc' => __( 'The catalog widget area', AT_ADMIN_TEXTDOMAIN )
			),
			'car' => array(
				'name' => __( 'Car Widget Area', AT_ADMIN_TEXTDOMAIN ),
				'desc' => __( 'The car widget area', AT_ADMIN_TEXTDOMAIN )
			),
			/** Footer */
			'footer_top_1' => array(
				'name' => __( 'First Footer Top Widget Area', AT_ADMIN_TEXTDOMAIN ),
				'desc' => __( 'The first footer top widget area', AT_ADMIN_TEXTDOMAIN )
			),
			'footer_top_2' => array(
				'name' => __( 'Second Footer Top Widget Area', AT_ADMIN_TEXTDOMAIN ),
				'desc' => __( 'The second footer top widget area', AT_ADMIN_TEXTDOMAIN )
			),
			'footer_top_3' => array(
				'name' => __( 'Third Footer Top Widget Area', AT_ADMIN_TEXTDOMAIN ),
				'desc' => __( 'The third footer top widget area', AT_ADMIN_TEXTDOMAIN )
			),
			'footer_top_4' => array(
				'name' => __( 'Fourth Footer Top Widget Area', AT_ADMIN_TEXTDOMAIN ),
				'desc' => __( 'The fourth footer top widget area', AT_ADMIN_TEXTDOMAIN )
			),
			/** Closer **/
			'footer_bottom_1' => array(
				'name' => __( 'First Closer Widget Area', AT_ADMIN_TEXTDOMAIN ),
				'desc' => __( 'The first footer bottom widget area', AT_ADMIN_TEXTDOMAIN )
			),
			'footer_bottom_2' => array(
				'name' => __( 'Second Closer Widget Area', AT_ADMIN_TEXTDOMAIN ),
				'desc' => __( 'The second footer bottom widget area', AT_ADMIN_TEXTDOMAIN )
			),
			'footer_bottom_3' => array(
				'name' => __( 'Third Closer Widget Area', AT_ADMIN_TEXTDOMAIN ),
				'desc' => __( 'The third footer bottom widget area', AT_ADMIN_TEXTDOMAIN )
			),
			'footer_bottom_4' => array(
				'name' => __( 'Fourth Closer Widget Area', AT_ADMIN_TEXTDOMAIN ),
				'desc' => __( 'The fourth footer bottom widget area', AT_ADMIN_TEXTDOMAIN )
			),
		);

		foreach ( $sidebars as $type => $sidebar ){
			register_sidebar(array(
				'name' => $sidebar['name'],
				'id'=> $type,
				'description' => $sidebar['desc'],
				'before_widget' => '<div id="%1$s" class="%2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h3>',
				'after_title' => '</h3>',
				//'before_widget' => '<div id="%1$s" class="f_widget %2$s">',
				// 'after_widget' => '</div>',
				// 'before_title' => '<h3><strong>',
				// 'after_title' => '</strong></h3>',
			));
		}
		$custom_sidebars = AT_Core::get_instance()->get_option('custom_sidebars', array());
		foreach ( $custom_sidebars as $key => $sidebar ){
			register_sidebar(array(
				'name' => $sidebar['name'],
				'id'=> 'at_custom_sidebar_' . $key,
				'description' => '',
				'before_widget' => '<div id="%1$s" class="%2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h3>',
				'after_title' => '</h3>',
			));
		}
	}

	public static function get_custom_sidebars(){
		$data = AT_Core::get_instance()->get_option('custom_sidebars', array());
		$sidebars = array();
		foreach ($data as $key => $value) {
			$sidebars['at_custom_sidebar_' . $key] = $value['name'];
		}
		return $sidebars;
	}
}