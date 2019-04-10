<?php
/*
  $Id: fss_questions_preview.php,v 1.0.0.0 2008/06/19 23:39:49 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
include('includes/application_top.php');
include(DIR_FS_CATALOG . 'includes/functions/' . FILENAME_FSS_FUNCTIONS);
include(DIR_FS_CATALOG . 'includes/languages/' . $language . '/' . FILENAME_FSS_FORMS_DETAIL);
include(DIR_WS_FUNCTIONS . FILENAME_FSS_FUNCTIONS);
$questions_id = $_POST['qID'];
$fields = array();
$i = 0;
foreach ($_POST as $key => $value) {
  if (substr($key, 0, 6) == 'field_') {
    $tmp_array = explode('_', $key);
    $values_type_id = $tmp_array[1];
    $fields[$i]['fields_id'] = tep_fss_get_fields_id($values_type_id);
    $fields_type = tep_fss_get_fields_type($fields[$i]['fields_id']);
    if ($fields_type == 'textarea' || $fields_type == 'dropdownmenudynamic') {
      $fields[$i]['fields_value'] = '';
      $fields[$i]['fields_value_text'] = $value;
    } else {
      $fields[$i]['fields_value'] = $value;
      $fields[$i]['fields_value_text'] = '';
    }
  }  
  $i++;
}
$questions = tep_db_fetch_array(tep_db_query("select fq.questions_variable, fq.questions_type, fqd.questions_label, fqd.questions_help from " . TABLE_FSS_QUESTIONS . " fq, " . TABLE_FSS_QUESTIONS_DESCRIPTION . " fqd where fq.questions_id = fqd.questions_id and fq.questions_id = '" . $questions_id . "' and fqd.language_id = '" . $languages_id . "' and fq.questions_status = '1'"));
$return = array('questions_id' => $questions_id,
                'questions_variable' => $questions['questions_variable'],
                'questions_type' => $questions['questions_type'],
                'questions_label' => $questions['questions_label'],
                'questions_help' => $questions['questions_help'],
                'html' => tep_fss_get_html($questions_id, tep_fss_get_html_value($questions_id, $fields)));  
?>
<hr>
<table>
  <tr>
    <td><?php echo $return['questions_label'] . ':'; ?></td>
    <td><?php echo $return['html']['str']; ?></td>
  </tr>
</table>
<hr>