<?php
/*
  $Id: buysafe_checkoutsuccess_bottom.php,v 1.0.0.0 2007/08/16 13:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
global $order, $order_id;

if (defined('MODULE_ADDONS_BUYSAFE_STATUS') &&  MODULE_ADDONS_BUYSAFE_STATUS == 'True') { 
  if (!isset($order_id) || $order_id == '' || $order_id == 0) {
    $order_id = (isset($_GET['order_id'])) ? (int)$_GET['order_id'] : 0;
  } 
  // re-create order object
  require_once(DIR_WS_CLASSES . 'order.php');  
  if ($order_id == '') return;
  $order = new order($order_id);
  
  require_once(DIR_WS_CLASSES . 'buysafe.php');
  $buysafe_module = new buysafe_class;
  
  // check if unique cart id exists and generate one if not exist
  if (!isset($_SESSION['cre_buySafe_unique_CartId']) || $_SESSION['cre_buySafe_unique_CartId'] == '') {
    $timeStamp = strtotime ('now'); 
    $cre_buySafe_unique_cart_id = $timeStamp . $_SERVER['REMOTE_ADDR']; 
    $cre_buySafe_unique_cart_id = str_replace('.', '',$cre_buySafe_unique_cart_id);
    // unique format = timestamp + remote_address + mixed random
    $_SESSION['cre_buySafe_unique_CartId'] = MODULE_ADDONS_BUYSAFE_CART_PREFIX . '-' . $cre_buySafe_unique_cart_id . tep_create_random_value(6);
    // check to see if the order contains a bond
    if (is_array($order->totals)) {
      foreach($order->totals as $value) {
        if (stristr($value, 'buysafe')) $_SESSION['WantsBond'] = true;
      }    
    }
  }

  $buysafe_cart_id = $_SESSION['cre_buySafe_unique_CartId'];
  $WantsBond = (isset($_SESSION['WantsBond'])) ? $_SESSION['WantsBond'] : false;
  
  $checkout_params = array('WantsBond' => ($WantsBond == true) ? 'true' : 'false', 'orders_id' => $order_id, 'buysafe_cart_id' => $buysafe_cart_id);
  
  $buysafe_result = $buysafe_module->call_api('AddUpdateShoppingCart', $checkout_params); 

  $checkout_result = $buysafe_module->call_api('SetShoppingCartCheckout', $checkout_params);

  if (is_array($checkout_result) && $checkout_result['IsBuySafeEnabled'] == 'true' && $checkout_result['BondCostDisplayText'] != '') {
    $update_data_array = array('orders_id' => $order_id,
                               'buysafe_cart_id' => $buysafe_cart_id,
                               'buysafe_client_ip' => getenv('REMOTE_ADDR'),
                               'buysafe_session_id' => tep_session_id());
    $exists = tep_db_fetch_array(tep_db_query("SELECT orders_id from " . TABLE_BUYSAFE . " WHERE orders_id = '" . (int)$order_id . "'"));
    if ($exists) {                                                          
      tep_db_perform(TABLE_BUYSAFE, $update_data_array, 'update', "orders_id = '" . (int)$order_id . "'");
    } else {
      tep_db_perform(TABLE_BUYSAFE, $update_data_array, 'insert');
    }
  }
}
if (isset($_SESSION['WantsBond'])) unset($_SESSION['WantsBond']);
if (isset($_SESSION['cre_buySafe_unique_CartId'])) unset($_SESSION['cre_buySafe_unique_CartId']);
?>