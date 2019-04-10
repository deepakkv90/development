<?php
/*
  $Id: ostatuspro_tools_boxesbottom.php, v 1.0.0.0 2008/01/28 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_STATUS') && MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_STATUS == 'True') {
  $rci = tep_admin_files_boxes(FILENAME_ORDERS_HOLD_LIST, BOX_TOOLS_ORDERS_HOLD_LIST);
}
?>