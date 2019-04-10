<?php
/*
  $Id: buysafe_shoppingcart_infoboxoffsettotal.php,v 1.0.0.0 2007/09/04 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/

global $buysafe_result, $WantsBond, $offset_amount, $currencies, $cart;

if (defined('MODULE_ADDONS_BUYSAFE_STATUS') &&  MODULE_ADDONS_BUYSAFE_STATUS == 'True') {    
  if (is_array($buysafe_result) && $buysafe_result['IsBuySafeEnabled'] == 'true' && $WantsBond == 'true') {
    $offset_amount = $buysafe_result['TotalBondCost'];
    $rci = '<table width="100%" cellspacing="0" cellpadding="1" border="0"><tr><td class="boxText">' . $buysafe_result['MiniCartLineDisplayText'] . '</td><td class="boxText" align="right">' . $buysafe_result['BondCostDisplayText'] . '</td></tr><tr><td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td></tr><tr><td class="boxText">&nbsp;</td><td class="boxText" align="right">' . $currencies->format($cart->show_total() + $buysafe_result['TotalBondCost']) . '</td></tr></table>';
  } else {
    $offset_amount = 0;
    $rci = '';
  }
  return $rci;
}   
?>