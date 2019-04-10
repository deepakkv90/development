<?php
/*
  $Id: FDMS_categories_subproducts.php, v 1.1.1.1 2006/12/29 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
global $currentsubs;
if (defined('MODULE_ADDONS_FDM_STATUS') && MODULE_ADDONS_FDM_STATUS == 'True') { 
  $rci = '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_LIBRARY_PRODUCT, tep_get_all_get_params(array('pID')) . '&pID=' . $currentsubs['products_id']) . '">' . tep_image_button('button_attach_files_sm.gif', IMAGE_ATTACH_FILES) . '</a>';
}
?>