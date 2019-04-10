<?php
/*
  $Id: RC_serverinfo_version.php,v 1.0.0 2008/05/22 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
if (defined('MODULE_ADDONS_RECOVERCARTS_STATUS') &&  MODULE_ADDONS_RECOVERCARTS_STATUS == 'True') {
  $rci = '<!-- RecoverCarts_serverinfo_version //-->' . "\n";
  $rci .= '<span class="content_heading">Recover Abandoned Carts Module ' . MODULE_ADDONS_RECOVERCARTS_VERSION . '</span><br>' . "\n";
  $rci .= '<!-- RecoverCarts_serverinfo_version eof//-->' . "\n";
  
  return $rci;
}
?>