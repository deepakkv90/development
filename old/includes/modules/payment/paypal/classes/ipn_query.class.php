<?php
/*
  $Id: ipn_info.class.php,v 2.6a 2004/07/14 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/
  class ipn_query {
    var $info, $txn, $customer;

    function ipn_query($paypal_ipn_id) {
      $this->info = array();
      $this->txn = array();
      $this->customer = array();
      $this->query($paypal_ipn_id);
    }

    function query($paypal_ipn_id) {
      $ipn_query = tep_db_query("select * from " . TABLE_PAYPAL . " where paypal_ipn_id = '" . (int)$paypal_ipn_id . "'");
      $ipn = tep_db_fetch_array($ipn_query);
      $this->info = array(
        'txn_type'            => $ipn['txn_type'],
        'reason_code'         => $ipn['reason_code'],
        'payment_type'        => $ipn['payment_type'],
        'payment_status'      => $ipn['payment_status'],
        'pending_reason'      => $ipn['pending_reason'],
        'invoice'             => $ipn['invoice'],
        'mc_currency'         => $ipn['mc_currency'],
        'payment_date'        => $ipn['payment_date'],
        'business'            => $ipn['business'],
        'receiver_email'      => $ipn['receiver_email'],
        'receiver_id'         => $ipn['receiver_id'],
        'paypal_address_id'   => $ipn['papal_address_id'],
        'txn_id'              => $ipn['txn_id'],
        'parent_txn_id'       => $ipn['parent_txn_id'],
        'notify_version'      => $ipn['notify_version'],
        'verify_sign'         => $ipn['verify_sign'],
        'last_modified'       => $ipn['last_modified'],
        'date_added'          => $ipn['date_added']);

      $this->txn = array(
        'num_cart_items'      => $ipn['num_cart_items'],
        'mc_gross'            => $ipn['mc_gross'],
        'mc_fee'              => $ipn['mc_fee'],
        'payment_gross'       => $ipn['payment_gross'],
        'payment_fee'         => $ipn['payment_fee'],
        'settle_amount'       => $ipn['settle_amount'],
        'settle_currency'     => $ipn['settle_currency'],
        'exchange_rate'       => $ipn['exchange_rate']);

      $this->customer = array(
        'first_name'          => $ipn['first_name'],
        'last_name'           => $ipn['last_name'],
        'payer_business_name' => $ipn['payer_business_name'],
        'address_name'        => $ipn['address_name'],
        'address_street'      => $ipn['address_street'],
        'address_city'        => $ipn['address_city'],
        'address_state'       => $ipn['address_state'],
        'address_zip'         => $ipn['address_zip'],
        'address_country'     => $ipn['address_country'],
        'address_status'      => $ipn['address_status'],
        'address_owner'       => $ipn['address_owner'],
        'payer_email'         => $ipn['payer_email'],
        'payer_id'            => $ipn['payer_id'],
        'payer_status'        => $ipn['payer_status'],
        'memo'                => $ipn['memo']);
    }
  }//end class
?>
