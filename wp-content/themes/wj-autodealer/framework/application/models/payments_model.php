<?php
if (!defined("AT_DIR")) die('!!!');

class AT_payments_model extends AT_Model{

  public function update_dealer_affiliate( $affiliate_id, $data ) {
    return $this->wpdb->update( $this->_dealers_affiliates_table, $data, array( 'id' => $affiliate_id ) );
  }

  public function insert_dealer_affiliate( $data ) {
    //$this->wpdb->insert( $this->_dealers_affiliates_table, $data );
    return $this->wpdb->insert_id;
  }

  public function check_valid_hash( $hash ) {
    $row = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->_recovery_password_table . " WHERE hash = %s and is_used = 0  LIMIT 1", $hash ), ARRAY_A );
    return false;
  }

  public function insert_transaction( $data ) {
    $this->wpdb->insert( $this->_transactions_table, $data );
    return $this->wpdb->insert_id;
  }
  public function update_transaction( $transaction_id = 0, $data ) {
    // if ( $transaction_id > 0 ) {
      return $this->wpdb->update( $this->_transactions_table, $data, array( 'id' => $transaction_id ) );
    // }
  }
  public function update_transaction_by_token( $token_id = 0, $data ) {
    // if ( $transaction_id > 0 ) {
      return $this->wpdb->update( $this->_transactions_table, $data, array( 'token' => $token_id ) );
    // }
  }

}