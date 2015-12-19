<?php
if (!defined("AT_DIR")) die('!!!');

class AT_photo_model extends AT_Model{

	//var $_photo_path = '/usr_data/';
	var $car_sizes = array(
		'original' => array(
			'width' => 1000,
			'height' => 1000,
			'crop' => false
		),
		'480x290' => array(
			'width' => 480,
			'height' => 290,
			'crop' => false
		),
		'213x164' => array(
			'width' => 213,
			'height' => 164,
			'crop' => true
		),
		'165x120' => array(
			'width' => 165,
			'height' => 120,
			'crop' => true
		),
		'81x62' => array(
			'width' => 81,
			'height' => 62,
			'crop' => true
		)
	);

	var $user_sizes = array(
		'original' => array(
			'width' => 1000,
			'height' => 1000,
			'crop' => false
		),
		'138x138' => array(
			'width' => 138,
			'height' => 138,
			'crop' => false
		),
	);


	public function resize_uploaded_image( $image_path, $post_id, $post_type, $sizes = array(), $quality = 90, $featured_image = false ){
		$ins = array(
			'post_id' => $post_id,
			'post_type' => $post_type,
			'width' => 0,
			'height' => 0,
			'is_delete' => 1
		);
		$this->wpdb->insert( $this->_photos_table, $ins );
		$photo_id = $this->wpdb->insert_id;
		$target_path = AT_UPLOAD_DIR_THEME . '/' . $post_type . '/' . sprintf('%02s', substr($photo_id, -2, 2)) . '/';

		$image_obj = wp_get_image_editor( $image_path ); 

		if ( !is_wp_error( $image_obj ) && is_writable( $target_path) ) {
			foreach ( $sizes as $key=>$size ) {
				$image_obj->resize( $size['width'], $size['height'], $size['crop'] );
			    $image_obj->set_quality( $quality );
			    $image_obj->save( $target_path . $key . '/' . $photo_id . '.jpg' );
			    if ( $key == 'original' ){
			    	$image_size = $image_obj->get_size();
			    }
			}
			$update = array(
				'width' => $image_size['width'],
				'height' => $image_size['height'],
				'is_delete' => 0
			);
			if ( $this->get_count_photo_by_post( $post_id, $post_type ) == 0 ){
				$update = array_merge($update, array( 'is_main' => 1 ));
				if ( $post_type == 'car' && !$featured_image) {
					$this->add_features_image( $post_id, $image_path );
				}
			} else {
				if ( $post_type == 'user') {
					$this->wpdb->query('UPDATE ' .  $this->_photos_table . ' SET is_delete = 1, is_main = 0 
							WHERE ' . $this->wpdb->prepare( 'id <> %d AND post_id = %d AND post_type = %s', $photo_id, $post_id, $post_type ) );
					$update = array_merge($update, array( 'is_main' => 1 ));
				}
			}

			$this->wpdb->update( $this->_photos_table, $update, array( 'id' => $photo_id ));
			if ( strpos( $image_path, 'http', 0 ) === FALSE )
				unlink( $image_path );
			return $photo_id;
		}
		return false;
	}

	public function get_count_photo_by_post( $post_id, $post_type ){
		$res = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT count(*) as count FROM " . $this->_photos_table . " WHERE post_id =  %d and post_type = %s and is_delete = 0 ", $post_id, $post_type ), ARRAY_A );
		return $res['count'];
	}

	public function get_photo_by_post( $post_id, $post_type, $is_main = 0 ){
		$res = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_photos_table . " WHERE post_id =  %d and post_type = %s and is_main = %d and is_delete = 0", $post_id, $post_type, $is_main ), ARRAY_A );
		if ( count( $res ) > 0 ) {
			$res['photo_path'] = AT_UPLOAD_DIR_THEME . '/' . $post_type . '/' . sprintf('%02s', substr( $res['id'], -2, 2) ) . '/';
			$res['photo_url'] = AT_UPLOAD_URI_THEME . '/' . $post_type . '/' . sprintf('%02s', substr( $res['id'], -2, 2) ) . '/';
			$res['photo_name'] = $res['id'] . '.jpg';
		} else {
			if (has_post_thumbnail( $post_id ) ) {
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'full' );
				$method = $post_type . '_sizes';
				if( $this->resize_uploaded_image( $image['0'], $post_id, $post_type, $this->$method, 90, $featured_image = true ) ) {
					$res = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_photos_table . " WHERE post_id =  %d and post_type = %s and is_main = %d and is_delete = 0 ", $post_id, $post_type, $is_main ), ARRAY_A );
					if ( count( $res ) > 0 ) {
						$res['photo_path'] = AT_UPLOAD_DIR_THEME . '/' . $post_type . '/' . sprintf('%02s', substr( $res['id'], -2, 2) ) . '/';
						$res['photo_url'] = AT_UPLOAD_URI_THEME . '/' . $post_type . '/' . sprintf('%02s', substr( $res['id'], -2, 2) ) . '/';
						$res['photo_name'] = $res['id'] . '.jpg';
					}			
				}
			}
		}
		return $res;
	}

	public function get_photos_by_post( $post_id, $post_type ){
		$photos = $this->wpdb->get_results( $this->wpdb->prepare( "SELECT * FROM " . $this->_photos_table . " WHERE post_id =  %d and post_type = %s and is_delete = 0 ORDER BY sort ASC, is_main DESC", $post_id, $post_type), ARRAY_A );
		foreach ($photos as $key => &$photo) {
			$photo['photo_path'] = AT_UPLOAD_DIR_THEME . '/' . $post_type . '/' . sprintf('%02s', substr( $photo['id'], -2, 2) ) . '/';
			$photo['photo_url'] = AT_UPLOAD_URI_THEME . '/' . $post_type . '/' . sprintf('%02s', substr( $photo['id'], -2, 2) ) . '/';
			$photo['photo_name'] = $photo['id'] . '.jpg';
		}
		return $photos;
	}

	public function set_photo_main_by_id( $post_id, $post_type, $photo_id ){
		$this->wpdb->query('UPDATE ' .  $this->_photos_table . ' SET is_main = 0 
							WHERE ' . $this->wpdb->prepare( ' is_main = 1 AND post_id = %d AND post_type = %s', $post_id, $post_type ) );
		$this->wpdb->query('UPDATE ' .  $this->_photos_table . ' SET is_main = 1 
							WHERE ' . $this->wpdb->prepare( ' id = %d ', $photo_id ) );
		$image_path = AT_UPLOAD_DIR_THEME . '/' . $post_type . '/' . sprintf('%02s', substr($photo_id, -2, 2)) . '/original/' . $photo_id . '.jpg';
		$this->add_features_image( $post_id, $image_path );
	}

	public function set_photo_sort( $photo_id, $sort = 0 ){
		$this->wpdb->query( $this->wpdb->prepare( 'UPDATE ' .  $this->_photos_table . '  SET sort = %d 
							WHERE  id = %d ', $sort, $photo_id ) );
	}

	public function del_photos_by_post( $post_id, $post_type ){
		$this->wpdb->query('UPDATE ' .  $this->_photos_table . ' SET is_delete = 1 
							WHERE ' . $this->wpdb->prepare( ' post_id = %d AND post_type = %s', $post_id, $post_type ) );
		if ( $post_type == 'car' ){
			delete_post_thumbnail( $post_id );
		}
	}

	public function del_photo_by_id( $photo_id ){
		$this->wpdb->query('UPDATE ' .  $this->_photos_table . ' SET is_delete = 1 
							WHERE ' . $this->wpdb->prepare( ' id = %d ', $photo_id ) );
	}

	public function add_features_image( $post_id, $image_url ){
		$upload_dir = wp_upload_dir();
		$image_data = file_get_contents( $image_url );
		$filename = basename( $image_url );
		if( wp_mkdir_p( $upload_dir['path'] ) )
		    $file = $upload_dir['path'] . '/' . $filename;
		else
		    $file = $upload_dir['basedir'] . '/' . $filename;
		file_put_contents( $file, $image_data);

		$wp_filetype = wp_check_filetype( $filename, null );
		$attachment = array(
		    'post_mime_type' => $wp_filetype['type'],
		    'post_title' => sanitize_file_name( $filename ),
		    'post_content' => '',
		    'post_status' => 'inherit'
		);
		$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		set_post_thumbnail( $post_id, $attach_id );
	}

}