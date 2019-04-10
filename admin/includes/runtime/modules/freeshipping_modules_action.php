<?php

/*
  $Id: freeshipping_modules_action.php,v 1.1.1.1 2008/06/08 23:38:02 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

global $action, $set;

if ($_GET['module'] == 'freeshipper' && $set == 'shipping' && $action == 'save') {
  reset($_POST['configuration']);
  $list_array = array('MODULE_SHIPPING_FREESHIPPER_OVER', 'MODULE_SHIPPING_FREESHIPPER_COST', 'MODULE_SHIPPING_FREESHIPPER_TAX_CLASS', 'MODULE_SHIPPING_FREESHIPPER_ZONE');
  while (list($key, $value) = each($_POST['configuration'])) {
    if (is_array($value)) {
      if (in_array($key, $list_array)) {
        $new_value = '';
        foreach ($value as $group_id => $group_value) {
          $new_value .= $group_id . '-' . $group_value . ',';
        }
        $new_value = substr($new_value, 0, strlen($new_value) - 1);
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $new_value . "' where configuration_key = '" . $key . "'");
      }
    }
  }
}
?>