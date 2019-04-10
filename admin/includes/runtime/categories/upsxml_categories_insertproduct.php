<?php

/*
  $Id: upsxml_categories_insertproduct.php,v 1.1.1.1 2008/06/17 23:38:51 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

global $products_id, $vendors_id;
$flag = false;
foreach ($vendors_id as $id) {
  if (defined('MODULE_SHIPPING_UPSXML_RATES_STATUS_' . $id)) {
    $code = eval('$status = MODULE_SHIPPING_UPSXML_RATES_STATUS_' . $id . ';');
    if ($status == 'True') {
      $flag = true;
      break;
    }
  }
}
if ((defined('MODULE_SHIPPING_UPSXML_RATES_STATUS') && MODULE_SHIPPING_UPSXML_RATES_STATUS == 'True') || $flag) {
  $sql_data_array = array();
  if (isset($_GET['action'])) {
    $action = $_GET['action'] ;
  } else if (isset($_POST['action'])) {
    $action = $_POST['action'] ;
    } else {
    $action = '' ;
  }
  if ($action == 'insert_product') {
    $sql_data_array['products_length'] = isset($_POST['products_length']) ? tep_db_prepare_input($_POST['products_length']) : 0;
    $sql_data_array['products_width'] = isset($_POST['products_width']) ? tep_db_prepare_input($_POST['products_width']) : 0;
    $sql_data_array['products_height'] = isset($_POST['products_height']) ? tep_db_prepare_input($_POST['products_height']) : 0;
    $sql_data_array['products_ready_to_ship'] = isset($_POST['products_ready_to_ship']) ? tep_db_prepare_input($_POST['products_ready_to_ship']) : 0;
    $sql_data_array['last_modified'] = 'now()';
    $sql_data_array['products_id'] = $products_id;
    tep_db_perform(TABLE_UPSXML_PRODUCTS_DIMENSION, $sql_data_array);
  }
}

?>