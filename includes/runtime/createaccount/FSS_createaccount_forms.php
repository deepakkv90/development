<?php
/*
  $Id: FSS_createaccount_forms.php,v 1.0.0 2008/05/22 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/ 
if (defined('MODULE_ADDONS_FSS_STATUS') && MODULE_ADDONS_FSS_STATUS == 'True') { 
  require_once(DIR_WS_FUNCTIONS . 'fss_functions.php');
  $forms_id = tep_fss_get_forms_id_by_name('Account', '1');
  $questions = tep_fss_get_forms_questions($forms_id);
  $rci = '';
  if (sizeof($questions) > 0) {
    foreach ($questions as $value) {
      if ($value['questions_type'] == 'Hidden') {
        $rci .= $value['html']['str'];
      } else {
      $rci .= '<tr>' . "\n";
      $rci .= '  <td class="main"> ' . $value['questions_label'] . '</td>' . "\n";
      $rci .= '  <td class="main">' . $value['html']['str'] . '</td>' . "\n";
      $rci .= '</tr>' . "\n";
      }
    }
    $rci .= tep_draw_hidden_field('forms_id', $forms_id);
  }  
  return $rci;
}
?>