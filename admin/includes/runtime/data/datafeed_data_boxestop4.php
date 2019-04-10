<?php
/*
  $Id: datafeed_data_boxestop4.php,v 1.0.0 2009/06/14 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('MODULE_ADDONS_DATAFEED_STATUS') && MODULE_ADDONS_DATAFEED_STATUS == 'True') {
  $rci = tep_admin_files_boxes(FILENAME_DATA_MANAGER, BOX_DATA_MANAGER, 'NONSSL','','2');

  return $rci;
}
?>