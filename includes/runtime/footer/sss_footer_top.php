<?php
/*
  $Id: sss_footer_top.php,v 1.0 2008/07/25 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
if (isset($_SESSION['demo_mode']) && $_SESSION['demo_mode'] === true) {
  $rci  = '<table id="demo-footer-container" width="100%" border="1" cellpadding="0" cellspacing="0">' . "\n"; 
  $rci .= '  <tr>' . "\n"; 
  $rci .= '    <td id="demo-footer-container" align="center">Demo Mode</td>' . "\n"; 
  $rci .= '  </tr>' . "\n"; 
  $rci .= '</table>' . "\n"; 

  return $rci;
}
?>