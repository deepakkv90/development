<?php
/*
  $Id: sss_index_top.php,v 1.2.0.0 2009/01/26 01:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
global $return;

if ( (eregi('login.php', basename($_SERVER['HTTP_REFERER']))) || 
     (eregi('popup_get_loaded.php', basename($_SERVER['HTTP_REFERER']))) ) {
  $stored_serial = ''; 
  $version = (defined('INSTALLED_VERSION_TYPE')) ? strtolower(INSTALLED_VERSION_TYPE) : ''; 
  $components_query = tep_db_query("SELECT serial_1, validation_product, status from " . TABLE_COMPONENTS);
  if (tep_db_num_rows($components_query) > 0) {
    // compare validationProduct to INSTALLED_VERSION_TYPE
    while ($components = tep_db_fetch_array($components_query)) {
      if ($version == strtolower($components['validation_product'])) {
        $stored_serial = $components['serial_1'];
        $stored_status = $components['status'];
        break;  
      }
    }
    if (($stored_serial == '') || ($stored_status == false)) $_SESSION['new_registration'] = true;  
  } else {
    $_SESSION['new_registration'] = true;
  }
  require_once(DIR_WS_CLASSES . 'sss_verify.php');
  $sss = new sss_verify;
  $return = $sss->verifySerial($stored_serial);
  if (eregi('Error', $return['return_code'])) {
    $_SESSION['validate_error'] = $return;
    tep_redirect(FILENAME_SSS_VALIDATE, '', 'SSL'); 
  }
  if ((int)$return['grace_days'] > 0) {
    tep_redirect(FILENAME_SSS_REGISTER . '?action=grace&amp;id=' . (int)$return['grace_days'], '', 'SSL'); 
  }  
}
?>