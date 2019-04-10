<?php
/*
  $Id: buysafe_orders_top.php,v 1.0.0.0 2007/08/16 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
if (defined('MODULE_ADDONS_BUYSAFE_STATUS') &&  MODULE_ADDONS_BUYSAFE_STATUS == 'True') {
  function daysDifference($day_1, $day_2) {
    $diff = abs(strtotime($day_1) - strtotime($day_2));
    $sec   = $diff % 60;
    $diff  = intval($diff / 60);
    $min   = $diff % 60;
    $diff  = intval($diff / 60);
    $hours = $diff % 24;
    $days  = intval($diff / 24);    
     
    return $days;
  }
  $oID = tep_db_prepare_input($_GET['oID']); 
  $action = (isset($_GET['action'])) ? $_GET['action'] : '';
  if ($action == 'deleteconfirm') {
    include_once(DIR_WS_CLASSES . 'order.php');
    $order = new order($oID);    
    // check if order was bonded
    $bs_query = tep_db_query("SELECT buysafe_id  
                                from " . TABLE_BUYSAFE . " 
                              WHERE orders_id = '" . $oID . "'");
    if (tep_db_num_rows($bs_query) > 0) {        
      $diff = daysDifference(date("Y-m-d H:i:s"), $order->info['date_purchased']);
      if ($diff >= 0 && $diff <= 20) {
        // if invoice deleted within 20 days, also delete bond

        $buysafe_check = tep_db_fetch_array(tep_db_query("SELECT bs.orders_id, bs.buysafe_cart_id, bs.buysafe_client_ip, bs.buysafe_session_id, o.date_purchased, o.customers_name 
                                                    from " . TABLE_BUYSAFE . " bs, " . TABLE_ORDERS . " o 
                                                  WHERE bs.orders_id = '" . $oID . "' 
                                                    and o.orders_id = '" . $oID . "'"));                                                    
        if (tep_not_null($buysafe_check['buysafe_cart_id'])) {
          $class = 'buysafe.php';
          $buysafe_directory = DIR_FS_CATALOG_MODULES . 'addons/';
          if (file_exists($buysafe_directory . $class)) {
            include_once($buysafe_directory . $class);
            $buysafe = new buysafe;
            // cancel the bond at buysafe
            $buysafe_result = $buysafe->call_api('SetShoppingCartCancelOrder', $buysafe_check);
            // successfully canceled, now remove from buysafe table
            tep_db_query("DELETE from " . TABLE_BUYSAFE . " WHERE orders_id = '" . $oID . "'");
          }
        }
      }
    }
  }
}
?>