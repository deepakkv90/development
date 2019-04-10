<?php
/*
  $Id: fss_additional_information.php,v 1.0.0.0 2008/06/19 10:20:48 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
// if the customer is not logged on, redirect them to the login page
if (!isset($_SESSION['customer_id'])) {
  $navigation->set_snapshot();
  tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}
require(DIR_WS_FUNCTIONS . FILENAME_FSS_FUNCTIONS);
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FSS_ADDITIONAL_INFORMATION);
if (isset($_GET['action']) && $_GET['action'] == 'submit') {
  $error = false;
  $forms_query = tep_db_query("SELECT ff.forms_type, ff.forms_post_name, ff.send_email_to, ff.send_post_data, ff.enable_vvc, ffd.forms_name, ffd.forms_confirmation_content, ffd.forms_description 
                                 from " . TABLE_FSS_FORMS . " ff, 
                                      " . TABLE_FSS_FORMS_DESCRIPTION . " ffd 
                               WHERE ff.forms_id = '" . $_GET['forms_id'] . "' 
                                 and ff.forms_id = ffd.forms_id 
                                 and ffd.language_id = '" . $languages_id . "'");
  $forms = tep_db_fetch_array($forms_query);
  if (isset($forms['enable_vvc']) && $forms['enable_vvc'] == '1') {    
    $code_query = tep_db_query("select code from " . TABLE_VISUAL_VERIFY_CODE . "  where oscsid = '" . tep_session_id() . "'");
    $code_array = tep_db_fetch_array($code_query);
    $code = $code_array['code'];
    tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . tep_session_id() . "'");
    $user_entered_code = $_POST['visual_verify_code'];
    if (!(strcasecmp($user_entered_code, $code) == 0)) {
      $error = true;
      $messageStack->add('forms_post', VISUAL_VERIFY_CODE_ENTRY_ERROR);
    }
  }
  if (!$error) {
    $forms_id = $_GET['forms_id'];
    tep_db_query("insert into " . TABLE_FSS_FORMS_POSTS . " (forms_id, customers_id, posts_date) values ('" . $forms_id . "', '" . $_SESSION['customer_id'] . "', now())");
    $post_id = tep_db_insert_id();
    $email_text = (isset($forms['forms_post_name']) ? $forms['forms_post_name'] : '') . ': ' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . 'admin/fss_forms_posts_admin.php?fID=' . $forms_id . '&pID=' . $post_id . "\n\n";
    $questions = tep_fss_get_forms_questions($forms_id);
    $i = 0;
    $max_len = 0;
    foreach ($questions as $question) {
      if ( $max_len < strlen($question['questions_label']) ) {
        $max_len = strlen($question['questions_label']);
      }
    }
    foreach ($questions as $question) {
      $question_id = $question['questions_id'];
      if (tep_fss_is_unanwsered_question($_SESSION['customer_id'], $forms_id, $question_id) !== false) {      // already answered this question
        continue;
      }        
      $label = $question['questions_label'];
      $content = '';
      if (tep_not_null($question['questions_variable'])) {
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
          if (is_array($_POST[$name])) {
            foreach ($_POST[$name] as $value) {
              $content .= $value . ', ';
            }
            $content = tep_db_prepare_input(substr($content, 0, strlen($content) - 2));
          }
          break;
        default:
          $content = tep_db_prepare_input($_POST[$name]);
          break;
      }
      if (isset($forms['send_post_data']) && $forms['send_post_data'] == '1') {
        $email_text .= tep_fss_append_space($label . ':', $max_len + 2) . '  ' . $content . "\n";
      }
      $sql_data = array('forms_id' => $forms_id,
                        'forms_posts_id' => $post_id,
                        'questions_id' => $question_id,
                        'questions_variable' => $name,
                        'forms_fields_label' => $label,
                        'forms_fields_value' => $content);
      tep_db_perform(TABLE_FSS_FORMS_POSTS_CONTENT, $sql_data);
      if ($i == 0) {
        $first_field = $label;
        $first_value = $content;
      }
      $i++;
    }
    $subject = sprintf(EMAIL_SUBJECT, isset($forms['forms_post_name']) ? $forms['forms_post_name'] : '', $first_field, $first_value);
    if ( isset($forms['send_email_to']) && tep_not_null($forms['send_email_to']) ) {
      $email_address = str_replace(';', ',', $forms['send_email_to']);
      $headers = "From: " . STORE_OWNER_EMAIL_ADDRESS;
      //mail($email_address, $subject, $email_text, $headers);
      tep_mail('', $email_address, $subject, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    }
    if (tep_not_null(FSS_FORMS_COPY_ALL_EMAIL)) {
      $headers = "From: " . STORE_OWNER_EMAIL_ADDRESS;
      //mail(FSS_FORMS_COPY_ALL_EMAIL, $subject, $email_text, $headers);
      tep_mail('', FSS_FORMS_COPY_ALL_EMAIL, $subject, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    }
    if ( isset($_POST['return_url']) && tep_not_null($_POST['return_url']) ) {
      tep_redirect($_POST['return_url']);
    } else {
      tep_redirect(tep_href_link(FILENAME_FSS_FORMS_POST_SUCCESS, 'forms_id=' . $forms_id));
    }
  }
} else {
  $fss_query = tep_db_query("select forms_id, forms_name from " . TABLE_FSS_FORMS_DESCRIPTION . " where language_id = '" . $languages_id . "'");
  while ($fss_data = tep_db_fetch_array($fss_query)) {
    if ($fss_data['forms_name'] == 'Account') {
      $forms_id = $fss_data['forms_id'];
      break;
    }
  }
  if (tep_not_null($forms_id)) {
    $forms_query = tep_db_query("SELECT ff.forms_type, ff.forms_post_name, ff.send_email_to, ff.send_post_data, ff.enable_vvc, ffd.forms_name, ffd.forms_confirmation_content, ffd.forms_description 
                                   from " . TABLE_FSS_FORMS . " ff, 
                                        " . TABLE_FSS_FORMS_DESCRIPTION . " ffd 
                                 WHERE ff.forms_id = '" . $forms_id . "' 
                                   and ff.forms_id = ffd.forms_id 
                                   and ffd.language_id = '" . $languages_id . "'");
    $forms = tep_db_fetch_array($forms_query);
  }
}
$content = CONTENT_FSS_ADDITIONAL_INFORMATION;
$javascript = 'fss_forms.js.php';
$breadcrumb->add(NAVBAR_TITLE_ACCOUNT, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_FSS_ADDITIONAL_INFORMATION, tep_get_all_get_params(array()), 'SSL'));
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>