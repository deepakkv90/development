<?php
/*
  $Id: buysafe_editorders_updateorder.php,v 1.0.0.0 2007/08/16 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
global $check_status, $status, $oID, $messageStack;

if (defined('MODULE_ADDONS_BUYSAFE_STATUS') &&  MODULE_ADDONS_BUYSAFE_STATUS == 'True') {
  if ($check_status['orders_status'] != $status && $status == '6') { // change to Canceled 
    $buysafe_check = tep_db_fetch_array(tep_db_query("SELECT bs.orders_id, bs.buysafe_cart_id, bs.buysafe_client_ip, bs.buysafe_session_id, o.date_purchased, o.customers_name from " . TABLE_BUYSAFE . " bs, " . TABLE_ORDERS . " o WHERE bs.orders_id = '" . (int)$oID . "' and o.orders_id = '" . (int)$oID . "'"));
    if (tep_not_null($buysafe_check['buysafe_cart_id'])) {
      $class = 'buysafe.php';
      $buysafe_directory = DIR_FS_CATALOG_MODULES . 'addons/';
      if (file_exists($buysafe_directory . $class)) {
        include_once($buysafe_directory . $class);
        $buysafe = new buysafe;
       $buysafe_result = $buysafe->call_api('SetShoppingCartCancelOrder', $buysafe_check);
        if (tep_not_null($buysafe_result['faultstring'])) {
          $messageStack->add_session('search', 'buySAFE fault: ' . $buysafe_result['faultstring'], 'error');
          tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'));
       } 
      }
    }  
  }
  if ($check_status['orders_status'] != $status && $check_status['orders_status'] == '6') { // change from Canceled
    $buysafe_check = tep_db_fetch_array(tep_db_query("SELECT bs.orders_id, bs.buysafe_cart_id, bs.buysafe_client_ip, bs.buysafe_session_id, o.date_purchased, o.customers_name  from " . TABLE_BUYSAFE . " bs, " . TABLE_ORDERS . " o WHERE bs.orders_id = '" . (int)$oID . "'  and o.orders_id = '" . (int)$oID . "'"));
    if (tep_not_null($buysafe_check['buysafe_cart_id'])) {
      include_once(DIR_WS_CLASSES . 'order.php');
      $order = new order($oID);
      $class = 'buysafe';
      $buysafe_directory = DIR_FS_CATALOG_MODULES . 'addons/';
      if (file_exists($buysafe_directory . $class)) {
        include_once($buysafe_directory . $class);
        $buysafe = new buysafe;
        $buysafe_check['WantsBond'] = 'true';
        $buysafe_check['buysafe_previous_cart_id'] = $buysafe_check['buysafe_cart_id'];
        $buysafe_check['buysafe_cart_id'] .= 'r';
        $buysafe_result = $buysafe->call_api('AddUpdateShoppingCart', $buysafe_check, $order);
        if ($buysafe_result['IsBuySafeEnabled'] != 'true') {
          $messageStack->add_session('search', 'buySAFE fault: ' . $buysafe_result['faultstring'], 'error');
          tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'));
        }
        $buysafe_result = $buysafe->call_api('SetShoppingCartCheckout', $buysafe_check, $order);
        if ($buysafe_result['IsBuySafeEnabled'] != 'true') {
          $messageStack->add_session('search', 'buySAFE fault: ' . $buysafe_result['faultstring'], 'error');
          tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'));
        } else {
          tep_db_query("update " . TABLE_BUYSAFE . " set buysafe_cart_id = '" . tep_db_input($buysafe_check['buysafe_cart_id']) . "' where orders_id = '" . (int)$oID . "'");
        }
      }
    }
  }
}
?>