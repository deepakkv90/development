<?php
/*
  $Id: sss_mainpage_top.php,v 1.0.0.0 2008/07/25 01:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (isset($_SESSION['admin_login']) && $_SESSION['admin_login'] === true) {
  $rci  = '<table width="100%" border="0" cellpadding="0" cellspacing="0">' . "\n"; 
  $rci .= '<tr>' . "\n"; 
  $rci .= '  <td align="center" style="background-color: #ffcc33; font-weight: bold;">ADMIN SESSION ACTIVE!</td>' . "\n"; 
  $rci .= '</tr>' . "\n"; 
  $rci .= '</table>' . "\n"; 
  
  return $rci;
}
?>