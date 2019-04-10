<?php
/*
  $Id: std_checkoutsuccess_insideformabovebuttons.php,v 1.0.0.0 2008/06/17 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('INSTALLED_VERSION_TYPE') && stristr(INSTALLED_VERSION_TYPE, 'Standard')) {
  $rci = '<br><tr><td align="center" class="main"><a href="http://www.creloaded.com" target="_blank">' . TEXT_POWERED_BY_CRE . '</a></td></tr>';
  return $rci;
}
?>