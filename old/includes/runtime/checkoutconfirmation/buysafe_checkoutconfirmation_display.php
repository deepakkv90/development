<?php
/*
  $Id: buysafe_checkoutconfirmation_display.php,v 1.0.0.0 2007/08/16 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
global $buysafe_result;

$rci = '';
if (defined('MODULE_ADDONS_BUYSAFE_STATUS') &&  MODULE_ADDONS_BUYSAFE_STATUS == 'True') {    
  if (is_array($buysafe_result) && $buysafe_result['IsBuySafeEnabled'] == 'true') {
    $rci .= '  <tr>' . "\n";
    $rci .= '    <td>' . tep_draw_separator('pixel_trans.gif', '100%', '10') . '</td>' . "\n";
    $rci .= '  </tr>' . "\n";
    $rci .= '  <tr>' . "\n";
    $rci .= '    <td class="main" align="center"><a href="' . $buysafe_result['CartDetailsUrl'] . '" target="_blank" style="text-decoration:underline">' . $buysafe_result['CartDetailsDisplayText'] . '</a></td>' . "\n";
    $rci .= '  </tr>' . "\n";
  } 
  return $rci;
}
?>