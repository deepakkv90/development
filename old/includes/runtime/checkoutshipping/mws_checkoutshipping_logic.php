<?php
/*
  $Id: mws_checkoutshipping_logic.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
global $order;

$_SESSION['warehouse_zone_name'] = '';
if (isset($order->delivery['country_id']) && isset($order->delivery['zone_id'])) {
  $sql_query = tep_db_query("SELECT ztwz.warehouse_zone_id, wz.warehouse_zone_name 
                               from " . TABLE_ZONES_TO_WAREHOUSE_ZONES . " ztwz, " . TABLE_WAREHOUSE_ZONES . " wz 
                             WHERE (ztwz.zone_country_id = " . $order->delivery['country_id'] . " 
                               and ztwz.warehouse_zone_id = wz.warehouse_zone_id 
                               and ztwz.zone_id = " . $order->delivery['zone_id'] . ") 
                               or (ztwz.zone_country_id = " . $order->delivery['country_id'] . " 
                               and ztwz.zone_id = 0)"); 
  $warehouse_zone_id_array = tep_db_fetch_array($sql_query);
  $warehouse_zone_id = $warehouse_zone_id_array['warehouse_zone_id'];
  if ($warehouse_zone_id != null) {
    $sql_query = tep_db_query("SELECT warehouse_zone_zip_code 
                                 from " . TABLE_WAREHOUSE_ZONES . " 
                               WHERE warehouse_zone_id = " . $warehouse_zone_id);
    $warehouse_zone_zip_code_array = tep_db_fetch_array($sql_query);
    $warehouse_zone_zip_code = $warehouse_zone_zip_code_array['warehouse_zone_zip_code'];
    if ($warehouse_zone_zip_code != null) {
      $shipping_org_zip = SHIPPING_ORIGIN_ZIP;
      $shipping_org_country = SHIPPING_ORIGIN_COUNTRY;
      define('SHIPPING_ORIGIN_ZIP', $warehouse_zone_zip_code);
      define('SHIPPING_ORIGIN_COUNTRY', $order->delivery['country_id']);
      $_SESSION['warehouse_zone_name'] = $warehouse_zone_id_array['warehouse_zone_name'];
    }
  }
}
?>