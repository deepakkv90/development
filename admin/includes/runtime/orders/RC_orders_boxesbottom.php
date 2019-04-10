<?php
/*
  $Id: RC_order_boxesbottom.php,v 1.0.0 2008/05/22 00:36:41 datazen Exp $    

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
global $tdate;

if (defined('MODULE_ADDONS_RECOVERCARTS_STATUS') && MODULE_ADDONS_RECOVERCARTS_STATUS == 'True') {
  if (defined('PROJECT_VERSION') && ereg('6.2', PROJECT_VERSION)) { 
    $rci = tep_admin_files_boxes(FILENAME_RECOVER_ABANDONED_CARTS, BOX_RECOVER_ABANDONED_CARTS, 'SSL','tdate=' . $tdate, '0');
  } else {
    $rci = tep_admin_files_boxes(FILENAME_RECOVER_ABANDONED_CARTS, BOX_RECOVER_ABANDONED_CARTS, 'SSL','tdate=' . $tdate, '2');
  }
}
?>