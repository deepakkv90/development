<?php
/*
  $Id: general.func.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

  function paypal_include_lng($base_dir, $lng_dir, $lng_file) {
    if(file_exists($base_dir . $lng_dir . '/' . $lng_file)) {
      include_once($base_dir . $lng_dir . '/' . $lng_file);
    } elseif (file_exists($base_dir . 'english/' . $lng_file)) {
      include_once($base_dir . 'english/' . $lng_file);
    }
  }

  function paypal_payment_status($order_id) {
     include_once(DIR_FS_CATALOG_MODULES . 'payment/paypal/database_tables.inc.php');
     $paypal_payment_status_query = tep_db_query("select p.payment_status from " . TABLE_PAYPAL . " p left join " . TABLE_ORDERS . " o on p.paypal_id = o.payment_id where o.orders_id ='" . (int)$order_id . "'");
     $paypal_payment_status = tep_db_fetch_array($paypal_payment_status_query);
     //quick work around for unkown order status id
     return $paypal_payment_status_value = (tep_not_null($paypal_payment_status['payment_status'])) ? $paypal_payment_status['payment_status'] : '';
  }

  function paypal_remove_order($order_id) {
    include_once(DIR_FS_CATALOG_MODULES . 'payment/paypal/database_tables.inc.php');
    $ipn_query = tep_db_query("select payment_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
    if (tep_db_num_rows($ipn_query)) { // this is a ipn order (PayPal or StormPay)
      $ipn_order = tep_db_fetch_array($ipn_query);
      $paypal_id = $ipn_order['payment_id'];
      $txn_query = tep_db_query("select txn_id from " . TABLE_PAYPAL . " where paypal_id ='" . (int)$paypal_id . "'");
      $txn = tep_db_fetch_array($txn_query);
      tep_db_query("delete from " . TABLE_PAYPAL . " where paypal_id = '" . (int)$paypal_id . "'");
      tep_db_query("delete from " . TABLE_PAYPAL . " where parent_txn_id = '" . tep_db_input($txn['txn_id']) . "'");
      if (defined('TABLE_PAYPAL_AUCTION')) tep_db_query("delete from " . TABLE_PAYPAL_AUCTION . " where paypal_id = '" . (int)$paypal_id . "'");

    }
    tep_db_query("delete from " . TABLE_ORDERS_SESSION_INFO . " where orders_id = '" . (int)$order_id . "'");
  }

?>
