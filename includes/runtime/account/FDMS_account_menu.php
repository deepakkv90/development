<?php
/*
  $Id: FDMS_account_menu.php,v 1.0.0.0 2006/11/21 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('MODULE_ADDONS_FDM_STATUS') && MODULE_ADDONS_FDM_STATUS == 'True') { 
  $rci  = '<tr>' . "\n";
  $rci .= '  <td><table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n"; 
  $rci .= '    <tr>' . "\n"; 
  $rci .= '      <td class="main"><b>'. FILE_MY_DOWNLOADS.'</b></td>' . "\n"; 
  $rci .= '    </tr>' . "\n"; 
  $rci .= '  </table></td>' . "\n"; 
  $rci .= '</tr>' . "\n"; 
  $rci .= '<tr>' . "\n"; 
  $rci .= '  <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">' . "\n"; 
  $rci .= '    <tr class="infoBoxContents">' . "\n"; 
  $rci .= '      <td><table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n"; 
  $rci .= '        <tr>' . "\n"; 
  $rci .= '          <td width="10">' . tep_draw_separator('pixel_trans.gif', '10', '1') . '</td>' . "\n"; 
  $rci .= '          <td width="60">' . tep_image(DIR_WS_IMAGES . 'fdms_logo.gif') . '</td>' . "\n"; 
  $rci .= '          <td width="2">' . tep_draw_separator('pixel_trans.gif', '2', '1') . '</td>' . "\n"; 
  $rci .= '          <td class="main">' . tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_DOWNLOADS_INDEX, 'customer_id=' . $_SESSION['customer_id'], 'SSL') . '">' . FILE_MY_DOWNLOADS . '</a></td>' . "\n"; 
  $rci .= '        </tr>' . "\n"; 
  $rci .= '      </table></td>' . "\n"; 
  $rci .= '    </tr>' . "\n"; 
  $rci .= '  </table></td>' . "\n"; 
  $rci .= '</tr>' . "\n"; 
  
  return $rci;
}
?>