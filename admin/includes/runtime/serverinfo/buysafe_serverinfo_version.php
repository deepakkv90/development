<?php
/*
  $Id: buysafe_serverinfo_version.php,v 1.2 2009/06/11 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
if (defined('MODULE_ADDONS_BUYSAFE_STATUS') &&  MODULE_ADDONS_BUYSAFE_STATUS == 'True') { 
  $rci = '<!-- buySAFE_serverinfo_version //-->' . "\n";
  $rci .= '<span class="content_heading">buySAFE Module v1.7</span><br>' . "\n";
  $rci .= '<!-- buySAFE_serverinfo_version eof//-->' . "\n";
  
  return $rci;
}
?>