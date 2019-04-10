<?php
/*
  $Id: FSS_createaccount_submit.php,v 1.0.0 2008/05/22 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/ 
if (defined('MODULE_ADDONS_FSS_STATUS') && MODULE_ADDONS_FSS_STATUS == 'True') {   
  if ( isset($_POST['forms_id']) && tep_not_null($_POST['forms_id']) ) {
    $forms_id = $_POST['forms_id'];
    $forms_query = tep_db_query("SELECT ff.forms_type, ff.forms_post_name, ff.send_email_to, ff.send_post_data, ff.enable_vvc, ffd.forms_name, ffd. forms_confirmation_content 
                                   from " . TABLE_FSS_FORMS . " ff, 
                                        " . TABLE_FSS_FORMS_DESCRIPTION . " ffd 
                                 WHERE ff.forms_id = '" . $forms_id . "' 
                                   and ff.forms_id = ffd.forms_id 
                                   and ffd.language_id = '" . $_SESSION['languages_id'] . "'");
    if (tep_db_num_rows($forms_query) > 0) {
      $forms = tep_db_fetch_array($forms_query);
      require_once(DIR_WS_FUNCTIONS . 'fss_functions.php');
      tep_db_query("insert into " . TABLE_FSS_FORMS_POSTS . " (forms_id, posts_date, customers_id) values ('" . $forms_id . "', now(), '" . $_SESSION['customer_id'] . "')");
      $post_id = tep_db_insert_id();  
      $questions = tep_fss_get_forms_questions($forms_id);
      foreach ($questions as $question) {
        $question_id = $question['questions_id'];
        $label = $question['questions_label'];
        $content = '';
        if (isset($question['questions_variable']) && tep_not_null($question['questions_variable'])) {
          $name = addslashes($question['questions_variable']);
        } else {
          $name = 'question_' . $question_id;
        }
        switch ($question['questions_type']) {
          case 'File Upload':
            $files = $_FILES[$name];
            if ( tep_not_null($files) ) {
              @move_uploaded_file($files['tmp_name'], DIR_FS_CATALOG . FSS_UPLOAD_FILE_PATH . $files['name']);
              $content = tep_db_prepare_input($files['name']);
            }
            break;
          case 'Drop Down List':
          case 'Check Box':
            if (isset($_POST[$name]) && is_array($_POST[$name])) {
              foreach ($_POST[$name] as $value) {
                $content .= $value . ', ';
              }
              $content = tep_db_prepare_input(substr($content, 0, strlen($content) - 2));
            }
            break;
          default:
            $content = tep_db_prepare_input(isset($_POST[$name]) ? $_POST[$name] : '');
            break;
        }
        $sql_data = array('forms_id' => $forms_id,
                          'forms_posts_id' => $post_id,
                          'questions_id' => $question_id,
                          'questions_variable' => $name,
                          'forms_fields_label' => $label,
                          'forms_fields_value' => $content);
        tep_db_perform(TABLE_FSS_FORMS_POSTS_CONTENT, $sql_data);
      }
    }
  }
}
?>