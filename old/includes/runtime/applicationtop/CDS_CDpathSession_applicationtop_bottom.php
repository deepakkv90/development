<?php
/*
  $Id: CDS_CDpathSession_applicationtop_bottom.php,v 1.0.0.0 2007/03/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
require_once(DIR_WS_FUNCTIONS . FILENAME_CDS_FUNCTIONS);
if (isset($_GET['CDpath'])) {
  $CDpath_array = cre_pages_parse_categories_path($_GET['CDpath']);
  if (sizeof($CDpath_array) > 0 ) {
    $_GET['CDpath'] = implode('_', $CDpath_array);  // reset in case the supplied data was invalid
    $_SESSION['CDpath'] = $_GET['CDpath'];
  } else {
    unset($_GET['CDpath']);
  }
 }
?>