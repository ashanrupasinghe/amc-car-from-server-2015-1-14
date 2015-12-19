<?php
if (!defined("AT_DIR")) die('!!!');

class AT_user_model extends AT_Model{

	public function create( $data ) {
      $salt = $this->generate_string( 20 );
  		$ins = array();

      // requried fields
  		$ins['name']      = $data['name'];
  		$ins['email']     = $data['email'];
  		$ins['password']  = $this->get_hash( $data['password'], $salt );
  		$ins['salt']      = $salt;
      $ins['date_create'] = current_time('mysql');
      
      if(isset($data['date_active'])) $ins['date_active'] = $data['date_active'];

      $ins['phone']     = isset($data['phone']) ? $data['phone'] : '';
      $ins['phone_2']   = isset($data['phone_2']) ? $data['phone_2'] : '';
  		//$ins['country_id']= isset($data['country_id']) ? $data['country_id'] : '';
  		$ins['state_id']  = isset($data['state_id']) ? $data['state_id'] : '';
      $ins['city']      = isset($data['city']) ? $data['city'] : '';
      $ins['is_dealer'] = isset($data['is_dealer']) ? $data['is_dealer'] : 1;
  		$ins['alias']     = isset($data['alias']) ? $data['alias'] : 1;
  		$this->wpdb->insert( $this->_users_table, $ins );
      return $this->wpdb->insert_id;
	}

  public function update( $user_id, $data ) {
    return $this->wpdb->update( $this->_users_table, $data, array( 'id' => $user_id ) );
  }


  public function get_user_transactions_by_id( $user_id ) {
    $user_transactions = $this->wpdb->get_results( $this->wpdb->prepare( "SELECT * FROM " . $this->_transactions_table . " WHERE uid = %d ORDER BY created_at DESC", $user_id ), ARRAY_A );
    return $user_transactions;
  }

  public function get_user_by_id( $user_id ) {
    $user_info = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_users_table . " WHERE id = %d LIMIT 1", $user_id ), ARRAY_A );
    //photo
    if (!is_null( $user_info )) {
      $photo_model = $this->load->model('photo_model');
      $user_info['photo'] = $photo_model->get_photo_by_post( $user_id, 'user', 1 );
    }
    return  is_null( $user_info ) ? false : $user_info;
  }

  public function get_user_by_email( $email ) {
    $user_info = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_users_table . " WHERE email = %s LIMIT 1", $email ), ARRAY_A );
    return is_null( $user_info ) ? false : $user_info;
  }

  public function is_email_exist( $email ) {
    $user_info = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_users_table . " WHERE email = %s LIMIT 1", $email ), ARRAY_A );
    return is_null( $user_info ) ? false : true; 
  }

  public function generate_string( $length, $upper = false ) {
    $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
    $numChars = strlen($chars);
    $hash = '';
    for ($i = 0; $i < $length; $i++) {
      $hash .= substr($chars, mt_rand(1, $numChars) - 1, 1);
    }
    return $upper ? strtoupper($hash) : $hash;
  }

  public function get_hash( $password, $salt ) {
    return sha1($password .'(-__-)'. $salt);
  }

  public function get_all_users() {
    $users = $this->wpdb->get_results( "SELECT id FROM " . $this->_users_table . ' ORDER by name', ARRAY_A );
    foreach ($users as &$user) {
      $user = $this->get_user_by_id( $user['id'] );
    }
    return $users;
  }
  
  // return count all users & dealers
  // use in admin
  public function get_count_users( $is_block = false ) {
    $count = $this->wpdb->get_var( $this->wpdb->prepare( "SELECT COUNT(id) FROM " . $this->_users_table . " WHERE is_block = %d", $is_block ) );
    return $count;
  }
  // return all users & dealers
  // use in admin
  public function get_users( $offset = 0, $per_page = 10, $orderby = 'name', $order = 'asc', $is_block = false ) {
    $users = $this->wpdb->get_results( $this->wpdb->prepare( "SELECT id FROM " . $this->_users_table . " WHERE is_block = %d ORDER BY  " . $orderby . " " . $order . " LIMIT %d, %d", $is_block, $offset, $per_page), ARRAY_A );
    foreach ($users as &$user) {
      $user = $this->get_user_by_id( $user['id'] );
    }
    return $users;
  }

  // return active dealers
  public function get_dealers( $offset = 0, $per_page = 999 ) {
    $users = $this->wpdb->get_results( $this->wpdb->prepare( "SELECT id FROM " . $this->_users_table . " WHERE is_dealer = 1 and is_block = 0  ORDER BY id DESC LIMIT %d, %d", $offset, $per_page), ARRAY_A );
    foreach ($users as &$user) {
      $user = $this->get_user_by_id( $user['id'] );
    }
    return $users;
  }

  // return @string total dealers count
  public function get_dealers_count() {
    $users = $this->wpdb->get_var( "SELECT COUNT(id) FROM " . $this->_users_table . " WHERE is_dealer = 1 and is_block = 0" );
    return $users;
  }

  public function blocked( $user_id, $action_vehicles, $assign_user_id){
    try {
      $this->wpdb->query('START TRANSACTION');
      $user_update = $this->wpdb->update( $this->_users_table, array( 'is_block' => 1 ), array( 'id' => $user_id ) );
      if(!$user_update) {
        throw new Exception();
      }
      switch ( $action_vehicles ) {
        case 'archive':
          $car_model = $this->load->model('car_model');
          if( $car_model->get_cars_count_by_user_id( $user_id, 'publish') ){
            $ids = $this->wpdb->get_results('SELECT posts.ID, posts.post_date 
                FROM ' . $this->wpdb->posts . ' posts
                INNER JOIN ' . $this->wpdb->postmeta .' owner
                  ON owner.post_id = posts.ID 
                  AND owner.meta_key = \'_owner_id\'
                  AND owner.meta_value = \'' . $user_id . '\'
                WHERE posts.post_status = \'publish\' AND posts.post_type = \'car\'',  ARRAY_A);
            foreach($ids as $key => $value){
              $this->wpdb->query('UPDATE ' . $this->wpdb->posts . ' posts
              SET posts.post_status = \'archive\'
              WHERE posts.ID = ' . $value['ID'] );
            }
          }
          break;
        case 'another':
          if($assign_user_id <= 0) {
            throw new Exception();
          }
          $this->wpdb->query('UPDATE ' . $this->wpdb->postmeta . ' owner
              SET owner.meta_value = ' . $assign_user_id . '
              WHERE owner.meta_key = \'_owner_id\' AND owner.meta_value = \'' . $user_id . '\'' );
          break;
        default:
          throw new Exception();
          break;
      }

    } catch(Exception $e) {
      $this->wpdb->query('ROLLBACK');
      return false;
    }
    $this->wpdb->query('COMMIT');
    return true;
  }

  public function unblocked($user_id){
    $this->wpdb->query('START TRANSACTION');
    $user_update = $this->wpdb->update( $this->_users_table, array( 'is_block' => 0 ), array( 'id' => $user_id ) );
    if($user_update) {
        $this->wpdb->query('COMMIT');
        return true;
    }
    else {
        $this->wpdb->query('ROLLBACK');
        return false;
    }
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////////
  // Dealers
  ////////////////////////////////////////////////////////////////////////////////////////////////////

  public function get_dealer_affiliates( $dealer_id ) {
    $affiliates =  $this->wpdb->get_results( $this->wpdb->prepare( "SELECT * FROM " . $this->_dealers_affiliates_table . " WHERE dealer_id = %d and is_delete = 0", $dealer_id ), ARRAY_A );
    $reference_model = $this->load->model( 'reference_model' );
    foreach ($affiliates as $key => &$affiliate) {   
        $affiliate['schedule'] = unserialize( $affiliate['schedule'] );
        if ( $affiliate['region_id'] > 0 ) {
          $region = $reference_model->get_region_by_id( $affiliate['region_id'] );
          $affiliate['region'] = $region['name'];
        } else {
          $affiliate['region'] = '';
        }
    }
    return $affiliates;
  }

  public function get_dealer_affiliate_by_id( $affiliate_id ) {
    $affiliate = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_dealers_affiliates_table . " WHERE is_delete = 0 and id = %d LIMIT 1", $affiliate_id ), ARRAY_A );
    if ( !is_null( $affiliate ) ) {
        if ( $affiliate['region_id'] > 0 ) {
            $reference_model = $this->load->model( 'reference_model' );
            $region = $reference_model->get_region_by_id( $affiliate['region_id'] );
            $affiliate['region'] = $region['name'];
        } else {
            $affiliate['region'] = '';
        }
        $affiliate['schedule'] = unserialize( $affiliate['schedule'] );
    } else {
        $affiliate = false;
    }
    return $affiliate;

  }

  public function get_dealer_main_affiliate( $dealer_id ) {
    $affiliate = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_dealers_affiliates_table . " WHERE dealer_id = %d and is_main = 1 LIMIT 1", $dealer_id ), ARRAY_A );
    if ( !is_null( $affiliate ) ) {
        if ( $affiliate['region_id'] > 0 ) {
            $reference_model = $this->load->model( 'reference_model' );
            $region = $reference_model->get_region_by_id( $affiliate['region_id'] );
            $affiliate['region'] = $region['name'];
        } else {
          $affiliate['region'] = '';
        }
        $affiliate['schedule'] = unserialize( $affiliate['schedule'] );
    } else {
        $affiliate = false;
    }
    return $affiliate;

  }

  public function update_dealer_affiliate( $affiliate_id, $data ) {
    return $this->wpdb->update( $this->_dealers_affiliates_table, $data, array( 'id' => $affiliate_id ) );
  }

  public function insert_dealer_affiliate( $data ) {
    $this->wpdb->insert( $this->_dealers_affiliates_table, $data );
    return $this->wpdb->insert_id;
  }

  public function get_recovery_hash( $user_id ) {
    $data = array(
      'salt' => $this->generate_string( 16 ),
      'user_id' => $user_id
    );
    $this->wpdb->insert( $this->_recovery_password_table, $data );
    $this->wpdb->insert_id;

    $row = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_recovery_password_table . " WHERE id = %d  LIMIT 1", $this->wpdb->insert_id ), ARRAY_A );
    $data = array(
      'hash' => $this->get_hash( $row['user_id'] . $row['id'], $row['salt'] )
    );
    $this->wpdb->update( $this->_recovery_password_table, $data, array( 'id' => $row['id'] ) );
    return $data['hash'];
  } 

  public function check_valid_hash( $hash ) {
    $row = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_recovery_password_table . " WHERE hash = %s and is_used = 0  LIMIT 1", $hash ), ARRAY_A );
    return ( $this->get_hash( $row['user_id'] . $row['id'], $row['salt'] ) == $hash ) ? $row['user_id'] : false;
  }

  public function set_used_hashes_by_user_id( $user_id ) {
    $this->wpdb->update( $this->_recovery_password_table, array( 'is_used' => 1, 'date_used' => current_time('mysql') ), array( 'user_id' => $user_id, 'is_used' => 0 ) );
  }

  public function get_confirm_email_code( $user_id, $email ){
    return $this->get_hash( $user_id, $email );
  }

}