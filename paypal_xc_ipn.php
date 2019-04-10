<?php
/*
  $Id: paypal_xc_ipn.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/

  include('includes/application_top.php');
  include(DIR_WS_MODULES . 'payment/paypal_xc/paypal_xc_base.php');

  if ( MODULE_PAYMENT_PAYPAL_XC_DEBUGGING == 'True' ) {
    $message = print_r($_POST, true);
    $log_file = DIR_FS_CATALOG . 'debug/paypal_xc_debug.txt';
    $fp = @fopen($log_file, 'a');
    @fwrite($fp, 'Log Time: ' . date('Y-m-d H:i:s') . ' by: ' . $PHP_SELF . "\n");
    @fwrite($fp, 'Paypal IPN: ' . $message . "\n\n");
    @fclose($fp);
  }
  
  $payment_date = paypal_xc_base::get_payment_date($_POST['payment_date']);
  $sql_data = array('mc_gross' => $_POST['mc_gross'],
                    'address_status' => $_POST['address_status'],
                    'tax' => $_POST['tax'],
                    'payment_status' => $_POST['payment_status'],
                    'mc_fee' => $_POST['mc_fee'],
                    'address_name' => $_POST['address_name'],
                    'payer_status' => $_POST['payer_status'],
                    'address_country' => $_POST['address_country'],
                    'address_city' => $_POST['address_city'],
                    'quantity' => $_POST['quantity'],
                    'verify_sign' => $_POST['verify_sign'],
                    'payer_email' => $_POST['payer_email'],
                    'business' => $_POST['business'],
                    'last_name' => $_POST['last_name'],
                    'address_state' => $_POST['address_state'],
                    'receiver_email' => $_POST['receiver_email'],
                    'payment_fee' => $_POST['payment_fee'],
                    'payment_date' => $payment_date['date'] . ' ' . $payment_date['time'],
                    'txn_type' => $_POST['txn_type'],
                    'payment_time_zone' => $payment_date['timezone'],
                    'payment_gross' => $_POST['payment_gross']);
                    
  $txn_query = tep_db_query("select paypal_id from paypal where txn_id = '" . $_POST['txn_id'] . "'");
  if ( tep_db_num_rows($txn_query) > 0 ) {
    tep_db_perform('paypal', $sql_data, 'update', "txn_id = '" . $_POST['txn_id'] . "'");
  } else {
    $sql_data_extra = array('reason_code' => $_POST['reason_code'],
                            'parent_txn_id' => $_POST['parent_txn_id'],
                            'txn_id' => $_POST['txn_id'],
                            'payment_type' => $_POST['payment_type'],
                            'receiver_id' => $_POST['receiver_id'],
                            'mc_currency' => $_POST['mc_currency'],
                            'payer_id' => $_POST['payer_id'],                            
                            'date_added' => 'now()');
    $sql_data = array_merge($sql_data, $sql_data_extra);
    tep_db_perform('paypal', $sql_data);
  }

  switch ($_POST['payment_status']) {
    case 'Refunded':
      $parent = tep_db_fetch_array(tep_db_query("select paypal_id from paypal where txn_id = '" . $_POST['parent_txn_id'] . "'"));
      $order = tep_db_fetch_array(tep_db_query("select orders_id from " . TABLE_ORDERS . " where payment_id = '" . $parent['paypal_id'] . "'"));
      tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . MODULE_PAYMENT_PAYPAL_XC_REFUND_ORDER_STATUS_ID . "', last_modified = 'now()' where orders_id = '" . $order['orders_id'] . "'");
      $sql_data = array('orders_id' => $order['orders_id'],
                        'orders_status_id' => MODULE_PAYMENT_PAYPAL_XC_REFUND_ORDER_STATUS_ID,
                        'date_added' => 'now()',
                        'comments' => TEXT_PAYPAL_REFUND);
      tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data);
      break;
    default:
      break;
  }
  
?>