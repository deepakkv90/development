<?php
/*
  $Id: paypal_order.class.php,v 2.6a 2004/07/14 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License

*/

class paypal_order {

  function paypal_order($orders_id = '', $txn_signature = '') {
    $this->orders_id =  tep_not_null($orders_id) ? $orders_id : '';
    $this->txn_signature = tep_not_null($txn_signature) ? $txn_signature : '';
  }

  function check_order_status($start = false) {
    
    include_once(DIR_WS_MODULES . 'payment/paypal/database_tables.inc.php');
    if(isset($_SESSION['paypal_order'])) {
      $orders_session_query = tep_db_query("select paypal_ipn_id from " . TABLE_ORDERS . " where orders_id ='" . (int)$paypal_order->orders_id . "'");
      $orders_session_check = tep_db_fetch_array($orders_session_query);
      if ($orders_session_check['paypal_ipn_id'] > 0 ) {
        paypal_order::reset_checkout_cart_session();
        return true;
      }
      return false;
    }
    return false;
  }

  function reset_checkout_cart_session() {
    global $cart;
    $cart->reset(true);
    unset($_SESSION['sendto']);
    unset($_SESSION['billto']);
    unset($_SESSION['shipping']);
    unset($_SESSION['payment']);
    unset($_SESSION['comments']);
    unset($_SESSION['paypal_order']);
  }

  function unserialize($broken) {
    for(reset($broken);$kv=each($broken);) {
      $key=$kv['key'];
      if (gettype($this->$key)!="user function")
      $this->$key=$kv['value'];
    }
  }
}//end class
?>
