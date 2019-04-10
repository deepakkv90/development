<?php
/*
  $Id: ostatuspro_orders_bottom.php, v 1.2.0.0 2008/01/28 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

global $oID, $order;

if (defined('MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_STATUS') && MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_STATUS == 'True') {
  //check if customer on hold list
  $customer_hold_query = tep_db_query("SELECT * from `orders_hold_list` WHERE holdlist_email = '" . $order->customer['email_address'] . "'");
  $customer_hold = tep_db_fetch_array($customer_hold_query);
  if ($customer_hold['holdlist_id'] != NULL) {
    $bcolor = "#FFD7D7";
  } else {
    $bcolor = "#DDFFDD";
  }
  $rci = '';
  $rci .= '<tr>' . "\n";
  $rci .= '  <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n";
  $rci .= '</tr>' . "\n";
  $rci .= '<tr>' . "\n";
  $rci .= '  <td><table border="1" cellspacing="0" cellpadding="0">'. "\n";
  $rci .= '    <tr>' . "\n";
  $rci .= '      <td><table bgcolor=' . $bcolor . ' border="0" cellspacing="0" cellpadding="4">' . "\n";
  $rci .= '        <tr>' . "\n";
  $rci .= '          <td class="main">' . "\n";
  $rci .= '            <b>' . TEXT_OSTATUSPRO_CUSTOMER_HOLD_LIST . '</b>&nbsp;<a href="' . FILENAME_ORDERS_HOLD_LIST . '" target="_blank">' . TEXT_OSTATUSPRO_MANAGE . '</a>' . "\n";
  $rci .= '          </td>' . "\n";
  $rci .= '        </tr>' . "\n";
  $rci .= '        <tr>' . "\n";
  $rci .= '          <td align="center" class="main">'. "\n";
  if ($customer_hold['holdlist_id'] != NULL) {
    $rci .= '            <span>' . TEXT_OSTATUSPRO_CUSTOMER_ON_HOLD_LIST . '</span>&nbsp;&nbsp;<a href="' . FILENAME_ORDERS_HOLD_LIST . '?oID=' . $oID . '&email=' . $order->customer['email_address'] . '&action=autodelete">'. TEXT_OSTATUSPRO_REMOVE . '</a>'. "\n";
  } else {
    $rci .= '            <a href="' . FILENAME_ORDERS_HOLD_LIST . '?oID=' . $oID . '&email=' . $order->customer['email_address'] . '&action=autoinsert">' . TEXT_OSTATUSPRO_ADD_CUSTOMER_TO_HOLD_LIST . '</a>'. "\n";
  }
  $rci .= '          </td>' . "\n";
  $rci .= '        </tr>' . "\n";
  $rci .= '      </table></td>' . "\n";
  $rci .= '    </tr>' . "\n";
  $rci .= '  </table></td>' . "\n";
  $rci .= '</tr>' . "\n";
  
  return $rci;
}
?>