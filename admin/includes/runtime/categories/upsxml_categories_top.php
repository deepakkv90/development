<?php

/*
  $Id: upsxml_categories_top.php,v 1.1.1.1 2008/06/17 23:38:51 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

global $vendors_id;
$vendors_id = array();
$flag = false;
if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
  $vendors_query = tep_db_query("SELECT vendors_id FROM " . TABLE_VENDORS . "");
  while ($vendors = tep_db_fetch_array($vendors_query)) {
    $vendors_id[] = $vendors['vendors_id'];
  }
  foreach ($vendors_id as $id) {
    if (defined('MODULE_SHIPPING_UPSXML_RATES_STATUS_' . $id)) {
      $code = eval('$status = MODULE_SHIPPING_UPSXML_RATES_STATUS_' . $id . ';');
      if ($status == 'True') {
        $flag = true;
        break;
      }
    }
  }
}

if ((defined('MODULE_SHIPPING_UPSXML_RATES_STATUS') && MODULE_SHIPPING_UPSXML_RATES_STATUS == 'True') || $flag) {
  $sql_data_array = array();
  $products_id = (int)$_GET['pID'];
  if (isset($_GET['action'])) {
    $action = $_GET['action'] ;
  } else if (isset($_POST['action'])) {
    $action = $_POST['action'] ;
    } else {
    $action = '' ;
  }
  if ($action == 'update_product') {
    $sql_data_array['products_length'] = isset($_POST['products_length']) ? tep_db_prepare_input($_POST['products_length']) : 0;
    $sql_data_array['products_width'] = isset($_POST['products_width']) ? tep_db_prepare_input($_POST['products_width']) : 0;
    $sql_data_array['products_height'] = isset($_POST['products_height']) ? tep_db_prepare_input($_POST['products_height']) : 0;
    $sql_data_array['products_ready_to_ship'] = isset($_POST['products_ready_to_ship']) ? tep_db_prepare_input($_POST['products_ready_to_ship']) : 0;
    $sql_data_array['last_modified'] = 'now()';
    $dimension_query = tep_db_query("SELECT * FROM " . TABLE_UPSXML_PRODUCTS_DIMENSION . " WHERE products_id = '" . $products_id . "'");
    if ($action == 'update_product' && tep_db_num_rows($dimension_query) > 0) {
      tep_db_perform(TABLE_UPSXML_PRODUCTS_DIMENSION, $sql_data_array, 'update', "products_id = '" . $products_id . "'");
    } else {
      $sql_data_array['products_id'] = $products_id;
      tep_db_perform(TABLE_UPSXML_PRODUCTS_DIMENSION, $sql_data_array);
    }
  }
}

?>