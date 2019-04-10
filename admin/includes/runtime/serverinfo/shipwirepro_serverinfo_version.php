<?php
/*
  $Id: shipwirepro_serverinfo_version.php,v 1.1.0 2008/06/20 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
if (defined('MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_STATUS') &&  MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_STATUS == 'True') {
  $rci = '<!-- shipwirepro_serverinfo_version //-->' . "\n";
  $rci .= '<span class="content_heading">Shipwire Pro CS Module ' . MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_VERSION . '</span><br>' . "\n";
  $rci .= '<!-- shipwirepro_serverinfo_version eof//-->' . "\n";
  return $rci;
}
?>