<?php

/*
  $Id: upsxml_categories_pedittop.php,v 1.1.1.1 2008/06/17 23:38:51 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

global $vendors_id;
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
if (((defined('MODULE_SHIPPING_UPSXML_RATES_STATUS') && MODULE_SHIPPING_UPSXML_RATES_STATUS == 'True') || $flag) && defined('TABLE_UPSXML_PRODUCTS_DIMENSION') && (defined('MODULE_SHIPPING_UPSXML_DIMENSIONS_SUPPORTED') && MODULE_SHIPPING_UPSXML_DIMENSIONS_SUPPORTED != 'No')) {
  $products_id = (int)$_GET['pID'];
  $dimension_query = tep_db_query("SELECT * FROM " . TABLE_UPSXML_PRODUCTS_DIMENSION . " WHERE products_id = '" . $products_id . "'");
  if (tep_db_num_rows($dimension_query) > 0) {
    $dimension = tep_db_fetch_array($dimension_query);
  } else {
    $dimension = array('products_length' => '',
                       'products_height' => '',
                       'products_width' => '',
                       'products_ready_to_ship' => '0');
  }
  $rci = '  <tr>' . "\n";
  $rci .= '    <td width="50%"><table  border="0" cellspacing="2" cellpadding="2">' . "\n";
  $rci .= '      <tr>' . "\n";
  $rci .= '       <td class="main">' . TEXT_PRODUCTS_LENGTH . '</td>' . "\n";
  $rci .= '       <td class="main">' . tep_draw_separator('pixel_trans.gif', '105', '15') . '&nbsp;' . tep_draw_input_field('products_length', $dimension['products_length']) . '</td>' . "\n";
  $rci .= '     </tr>' . "\n";
  $rci .= '     <tr>' . "\n";
  $rci .= '       <td class="main">' . TEXT_PRODUCTS_HEIGHT . '</td>' . "\n";
  $rci .= '       <td class="main">' . tep_draw_separator('pixel_trans.gif', '105', '15') . '&nbsp;' . tep_draw_input_field('products_height', $dimension['products_height']) . '</td>' . "\n";
  $rci .= '     </tr>' . "\n";
  $rci .= '   </table></td>' . "\n";
  $rci .= '    <td width="50%"><table  border="0" cellspacing="2" cellpadding="2">' . "\n";
  $rci .= '      <tr>' . "\n";
  $rci .= '       <td class="main">' . TEXT_PRODUCTS_WIDTH . '</td>' . "\n";
  $rci .= '       <td class="main">' . tep_draw_separator('pixel_trans.gif', '45', '15') . '&nbsp;' . tep_draw_input_field('products_width', $dimension['products_width']) . '</td>' . "\n";
  $rci .= '     </tr>' . "\n";
  $rci .= '     <tr>' . "\n";
  $rci .= '       <td class="main">' . TEXT_PRODUCTS_READY_TO_SHIP . '</td>' . "\n";
  $rci .= '       <td class="main">' . tep_draw_separator('pixel_trans.gif', '45', '15') . '&nbsp;' . tep_draw_checkbox_field('products_ready_to_ship', '1', $dimension['products_ready_to_ship']) . '</td>' . "\n";
  $rci .= '     </tr>' . "\n";
  $rci .= '   </table></td>' . "\n";
  $rci .= '  </tr>' . "\n";
  
  return $rci;
}
?>