<?php

class AT_Filters{

	static public function remove_media_tab( $strings ) {
		unset(
			$strings["insertFromUrlTitle"],
			$strings['createGalleryTitle']
		);
		return $strings;
	}

	static public function remove_medialibrary_tab( $tabs ) {
	    //if ( !current_user_can( 'update_core' ) ) {
		trigger_error('!');
	        unset($tabs['library']);
	        unset($tabs['library']);
	        return $tabs;
	    // }
	}

	static public function users_own_attachments( $wp_query_obj ) {

	    global $current_user, $pagenow;

	    if( !is_a( $current_user, 'WP_User') )
	        return;

	    if( 'upload.php' != $pagenow )
	        return;

	    // if( !current_user_can('delete_pages') )
	    //     $wp_query_obj->set('author', $current_user->id );

	    return;
	}

	static public function add_body_class() {
		$classes = array();
		$classes[] = 'car';
		$classes[] = 'page';
		$classes[] = AT_Core::get_instance()->get_option( 'site_type', 'mode_soletrader');
		return $classes;
	}
}
?>