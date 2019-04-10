<?php
/*
  $Id: buysafe_editorders_updateorder.php,v 1.0.0.0 2007/08/16 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
require('includes/application_top.php'); 
require_once(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

$oID = (isset($_GET['oID'])) ? (int)$_GET['oID'] : 0;
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
    $buysafe_result = $buysafe->call_api('SetShoppingCartCancelOrder', $buysafe_check);
    if (tep_not_null($buysafe_result['faultstring'])) {
      $messageStack->add_session('search', 'buySAFE fault: ' . $buysafe_result['faultstring'], 'error');
      tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit', 'SSL'));
    } else {
      // successfully canceled, now remove ot_buysafe entry from buysafe and orders_total tables
      tep_db_query("DELETE from " . TABLE_BUYSAFE . " WHERE orders_id = '" . $oID . "'");
      // get the bond amount
      $bond = tep_db_fetch_array(tep_db_query("SELECT value 
                                                 from " . TABLE_ORDERS_TOTAL . " 
                                               WHERE orders_id = '" . $oID . "' 
                                                 and class = 'ot_buysafe'"));
      $bond_amt = (isset($bond['value'])) ? $bond['value'] : 0;
      // adjust order total for deleted bond
      tep_db_query("UPDATE " . TABLE_ORDERS_TOTAL . " SET value = (value - " . $bond_amt . ") WHERE orders_id = '" . $oID . "' and class = 'ot_total'");
      // get the new OT value
      $new = tep_db_fetch_array(tep_db_query("SELECT value 
                                                from " . TABLE_ORDERS_TOTAL . " 
                                              WHERE orders_id = '" . $oID . "' 
                                                and class = 'ot_total'"));
      $new_value = (isset($new['value'])) ? $new['value'] : 0;
      $new_value_text = '<b>' . $currencies->format($new_value) . '</b>';      
      tep_db_query("UPDATE " . TABLE_ORDERS_TOTAL . " SET text = '" . $new_value_text . "' WHERE orders_id = '" . $oID . "' and class = 'ot_total'");
      // delete the ot_buysafe OT entry 
      tep_db_query("DELETE from " . TABLE_ORDERS_TOTAL . " WHERE orders_id = '" . $oID . "' and class = 'ot_buysafe'");
      $messageStack->add_session('search', TEXT_CANCEL_SUCCESS, 'success');   
    }
  }
}  
tep_redirect(tep_href_link(FILENAME_ORDERS, '', 'SSL'));
require('includes/application_bottom.php'); 
?>