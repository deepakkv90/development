<?php
/*
  $Id: buysafe_editorders_updateorder.php,v 1.0.0.0 2007/08/16 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
global $oInfo;

$rci = '';
if (defined('MODULE_ADDONS_BUYSAFE_STATUS') &&  MODULE_ADDONS_BUYSAFE_STATUS == 'True') {
  function dateDifference($day_1, $day_2) {
    $diff = abs(strtotime($day_1) - strtotime($day_2));
    $sec   = $diff % 60;
    $diff  = intval($diff / 60);
    $min   = $diff % 60;
    $diff  = intval($diff / 60);
    $hours = $diff % 24;
    $days  = intval($diff / 24);
    $total_hours = ($days * 24) + $hours;
    
    return $total_hours;
  }
  // check if order was bonded
  $bs_query = tep_db_query("SELECT buysafe_id  
                              from " . TABLE_BUYSAFE . " 
                            WHERE orders_id = '" . $oInfo->orders_id . "'");
  if (tep_db_num_rows($bs_query) > 0) {        
    $diff = dateDifference(date("Y-m-d H:i:s"), $oInfo->date_purchased);
    $rtime = (72 - $diff);
    if ($rtime > 0 && $rtime <= 72) {
      // merchant has 72 hours from purchase time to cancel bond    
      $rci = '<a href="' . tep_href_link('buysafe_cancel.php', 'oID=' . $oInfo->orders_id) . '">' . tep_image_button('button_cancel_bond.png', IMAGE_CANCEL_BOND). '</a><br><span class="errorText">Cancel Hours Remaining: ' . $rtime . '</span>';
    }
  }
}
?>