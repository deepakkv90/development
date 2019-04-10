<?php
/*
  $Id: FSS_checkoutshipping_top.php,v 1.0.0 2008/05/22 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('MODULE_ADDONS_FSS_STATUS') && MODULE_ADDONS_FSS_STATUS == 'True') {
  $rci = '';
  if (FSS_FORMS_ACCOUNT_CUSTOMER_ALERT == 'true') {  
    require_once(DIR_WS_FUNCTIONS . FILENAME_FSS_FUNCTIONS);
    if (tep_fss_has_unanwsered_questions($_SESSION['customer_id'])) {
      $rci .= '<table width="100%">' . "\n";
      $rci .= '<tr>' . "\n";
      $rci .= '<td class="messageStackError" width="100%"><img src="images/icons/error.gif" alt="Error" title=" Error " border="0" height="10" width="10">&nbsp;' . FSS_ACCOUNT_UPDATE_WARNING . '</td>' . "\n";
      $rci .= '</tr>' . "\n";
      $rci .= '</table>' . "\n";
    }
  }
  return $rci;
}
?>