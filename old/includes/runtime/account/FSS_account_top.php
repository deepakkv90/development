<?php
/*
  $Id: FSS_account_top.php,v 1.0.0 2008/05/22 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
global $customer_id, $messageStack;
if (defined('MODULE_ADDONS_FSS_STATUS') && MODULE_ADDONS_FSS_STATUS == 'True') {
  if (FSS_FORMS_ACCOUNT_CUSTOMER_ALERT == 'true') {
    require_once(DIR_WS_FUNCTIONS . FILENAME_FSS_FUNCTIONS);
    if (tep_fss_has_unanwsered_questions($customer_id)) {
      $messageStack->add('account', FSS_ACCOUNT_UPDATE_WARNING);
    }
  }
}
?>