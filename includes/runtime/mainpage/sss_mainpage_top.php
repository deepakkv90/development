<?php
/*
  $Id: sss_mainpage_top.php,v 1.0.0.0 2008/07/25 01:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (isset($_SESSION['demo_mode']) && $_SESSION['demo_mode'] === true) {
  $rci  = '<table id="demo-header-container" width="100%" height="75" border="0" cellpadding="0" cellspacing="0">' . "\n"; 
  $rci .= '<tr>' . "\n"; 
  $rci .= '  <td align="left" valign="top" style="padding-left: 24px; width: 105px;"><a href="http://www.creloaded.com/">' . tep_image(DIR_WS_IMAGES . 'demo-logo.png') . '</a></td>' . "\n"; 
  $rci .= '  <td align="center" style="font-family: Arial, sans-serif; font-size: 18px; color: white; line-height: 60px;" valign="top">This Store Is In <span style="color: #81aa68; font-weight: bold;">Demo Mode</span>. <span style="font-style: italic;">Orders Will Not Be Shipped.</span></td>' . "\n"; 
  $rci .= '  <td align="right" style="width: 105px; padding-right: 24px;"></td>' . "\n"; 
  $rci .= '</tr>' . "\n"; 
  $rci .= '</table>' . "\n"; 
  
  return $rci;
}
?>