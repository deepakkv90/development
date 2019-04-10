<?php
/*
  $Id: FSS_account_menu.php,v 1.0.0 2008/05/22 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('MODULE_ADDONS_FSS_STATUS') && MODULE_ADDONS_FSS_STATUS == 'True') { 
  $rci  = '<tr>' . "\n";
  $rci .= '      <td>' . tep_draw_separator('pixel_trans.gif', '100%', '10') . '</td>' . "\n"; 
  $rci .= '    </tr>' . "\n"; 
  $rci .= '    <tr>' . "\n"; 
  $rci .= '      <td><table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n"; 
  $rci .= '        <tr>' . "\n"; 
  $rci .= '          <td class="main"><b>' . FSS_HEADING_FORMS_AND_SURVEY . '</b></td>' . "\n"; 
  $rci .= '        </tr>' . "\n"; 
  $rci .= '      </table></td>' . "\n"; 
  $rci .= '    </tr>' . "\n"; 
  $rci .= '    <tr>' . "\n"; 
  $rci .= '      <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">' . "\n"; 
  $rci .= '        <tr class="infoBoxContents">' . "\n"; 
  $rci .= '          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n"; 
  $rci .= '            <tr>' . "\n"; 
  $rci .= '              <td width="10">' . tep_draw_separator('pixel_trans.gif', '10', '1') . '</td>' . "\n"; 
  $rci .= '              <td width="60">' . tep_image(DIR_WS_IMAGES . 'form_survey.gif') . '</td>' . "\n"; 
  $rci .= '              <td width="10">' . tep_draw_separator('pixel_trans.gif', '10', '1') . '</td>' . "\n"; 
  $rci .= '              <td><table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n"; 
  $rci .= '                <tr>' . "\n"; 
  $rci .= '                  <td class="main">' . tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_FSS_ADDITIONAL_INFORMATION, '', 'SSL') . '">' . FSS_TEXT_UPDATE_ADDITIONAL_INFO . '</a></td>' . "\n"; 
  $rci .= '                </tr>' . "\n"; 
  $rci .= '                <tr>' . "\n"; 
  $rci .= '                  <td class="main">' . tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_FSS_UNCOMPLETED_SURVEYS, '', 'SSL') . '">' . FSS_TEXT_UNCOMPLETED_SURVEYS . '</a></td>' . "\n"; 
  $rci .= '                </tr>' . "\n"; 
  $rci .= '                <tr>' . "\n"; 
  $rci .= '                  <td class="main">' . tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_FSS_COMPLETED_SURVEYS, '', 'SSL') . '">' . FSS_TEXT_COMPLETED_SURVEYS . '</a></td>' . "\n"; 
  $rci .= '                </tr>' . "\n"; 
  $rci .= '              </table></td>' . "\n"; 
  $rci .= '              <td width="10" align="right">' . tep_draw_separator('pixel_trans.gif', '10', '1') . '</td>' . "\n"; 
  $rci .= '            </tr>' . "\n"; 
  $rci .= '          </table></td>' . "\n"; 
  $rci .= '        </tr>' . "\n"; 
  $rci .= '      </table></td>' . "\n"; 
  $rci .= '    </tr>' . "\n"; 
  return $rci;
}