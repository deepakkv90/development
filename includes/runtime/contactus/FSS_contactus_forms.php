<?php
/*
  $Id: FSS_contactus_forms.php,v 1.0.0 2008/05/22 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/  
if (defined('MODULE_ADDONS_FSS_STATUS') && MODULE_ADDONS_FSS_STATUS == 'True') { 
  require_once(DIR_WS_FUNCTIONS . 'fss_functions.php');
  $forms_id = tep_fss_get_forms_id_by_name('Contact Us', '1');
  $questions = tep_fss_get_forms_questions($forms_id);
  $rci  = '<tr>' . "\n";  
  $rci .= '  <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">' . "\n";  
  $rci .= '    <tr class="infoBoxContents">' . "\n";  
  $rci .= '      <td><table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n";  
  if (sizeof($questions) > 0) {
    foreach ($questions as $value) {
      if ($value['questions_type'] == 'Hidden') {
        $rci .= $value['html']['str'];
      } else {
        $rci  .= '      <tr>' . "\n";
        $rci  .= '        <td class="main" width="20%">' .  $value['questions_label'] . '</td>' . "\n";
        $rci  .= '        <td class="main" width="80%">' .  $value['html']['str'] . '</td>' . "\n";
        $rci  .= '      </tr>' . "\n";
      }
    }
    $rci .= tep_draw_hidden_field('forms_id', $forms_id);
    $rci .= '</table></td>' . "\n";
    $rci .= '          </tr>' . "\n";
    $rci .= '</table></td>' . "\n";
    $rci .= '      </tr>' . "\n";
}
?>