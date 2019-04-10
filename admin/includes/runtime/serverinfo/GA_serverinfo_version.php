<?php
/*
  $Id: GA_serverinfo_version.php,v 1.0.0 2008/05/30 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('MODULE_ADDONS_GOOGLEANALYTICS_STATUS') &&  MODULE_ADDONS_GOOGLEANALYTICS_STATUS == 'True') {
  $rci = '<!-- GA_serverinfo_version //-->' . "\n";
  $rci .= '<span class="content_heading">Google Analytics/Sitemap Module ' . MODULE_ADDONS_GOOGLEANALYTICS_VERSION . '</span><br>' . "\n";
  $rci .= '<!-- GA_serverinfo_version eof//-->' . "\n";
  
  return $rci;
}
?>