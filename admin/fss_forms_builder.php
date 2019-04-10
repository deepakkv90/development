<?php
/*
  $Id: fss_question_manager.php,v 1.0.0.0 2006/10/21 23:39:49 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  function tep_set_cat_flag($cid, $flag) {
    tep_db_query("UPDATE " . TABLE_FSS_CATEGORIES . " SET fss_categories_status = '" . $flag . "' WHERE fss_categories_id = '" . $cid . "'");
    $forms_query = tep_db_query("SELECT forms_id FROM " . TABLE_FSS_FORMS_TO_CATEGORIES . " WHERE categories_id = '" . $cid . "'");
    while ($forms = tep_db_fetch_array($forms_query)) {
      tep_db_query("UPDATE " . TABLE_FSS_FORMS . " SET forms_status = '" . $flag . "' WHERE forms_id = '" . $forms['forms_id'] . "'");
    }
    $sub_cat_query = tep_db_query("SELECT fss_categories_id FROM " . TABLE_FSS_CATEGORIES . " WHERE fss_categories_parent_id = '" . $cid . "'");
    while ($sub_cat = tep_db_fetch_array($sub_cat_query)) {
      tep_set_cat_flag($sub_cat['fss_categories_id'], $flag);
    }
  }
  
  require('includes/application_top.php');
  $is_62 = (INSTALLED_VERSION_MAJOR == 6 && INSTALLED_VERSION_MINOR == 2) ? true : false;
  require(DIR_WS_FUNCTIONS . FILENAME_FSS_FUNCTIONS);
  
  // RCI code start
  $cre_RCI->get('global', 'top');
  $cre_RCI->get('fssformsbuilder', 'top'); 
  // RCI code eof

  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  $forms_id = (isset($_GET['fID']) ? $_GET['fID'] : '');
  $questions_id = (isset($_GET['qID']) ? $_GET['qID'] : '');
  $qPath = (isset($_GET['qPath']) ? $_GET['qPath'] : '');
  $cPath = (isset($_GET['cPath']) ? $_GET['cPath'] : '0');
  
  $forms_name = tep_get_forms_name(tep_not_null($qPath) ? $qPath : $forms_id);
  $languages = tep_get_languages();

  if (tep_not_null($action)) {
    switch ($action) {
      case 'sort':
        if ( is_array($_POST['sort_order']) ) {
          foreach ($_POST['sort_order'] as $key => $value) {
            if ( isset($_GET['qPath']) && tep_not_null($_GET['qPath']) ) {
              tep_db_query("update " . TABLE_FSS_QUESTIONS . " set sort_order = '" . $value . "' WHERE questions_id = '" . $key . "'");
            } elseif ( isset($_GET['cPath']) && tep_not_null($_GET['cPath']) ) {
              tep_db_query("update " . TABLE_FSS_FORMS . " set sort_order = '" . $value . "' WHERE forms_id = '" . $key . "'");
            }
          }
        }
        if ( is_array($_POST['c_sort_order']) ) {
          foreach ($_POST['c_sort_order'] as $key => $value) {
            tep_db_query("update " . TABLE_FSS_CATEGORIES . " set sort_order = '" . $value . "' WHERE fss_categories_id = '" . $key . "'");            
          }
        }
        tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action'))));
        break;
      case 'setflag':
        if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
          if (isset($_GET['fID']) && $_GET['fID'] != '') {
            tep_db_query("update " . TABLE_FSS_FORMS . " set forms_status = '" . $_GET['flag'] . "' WHERE forms_id = '" . $_GET['fID'] . "'");
          }
        }
        tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'flag'))));
        break;
      case 'setcatflag':
        tep_set_cat_flag($_GET['cID'], $_GET['flag']);
        tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'flag'))));
        break;
      case 'setqflag':
        if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
          if (isset($_GET['qID']) && $_GET['qID'] != '') {
            tep_db_query("update " . TABLE_FSS_QUESTIONS . " set questions_status = '" . $_GET['flag'] . "' WHERE questions_id = '" . $_GET['qID'] . "'");
          }
        }
        tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'flag'))));
        break;
      case 'insert_form':
        $sql_data = array('forms_type' => $_POST['forms_type'],
                          'forms_post_name' => $_POST['forms_post_name'],
                          'send_email_to' => $_POST['send_email_to'],
                          'send_post_data' => $_POST['send_post_data'],
                          'enable_vvc' => $_POST['enable_vvc'],
                          'sort_order' => $_POST['sort_order'],
                          'date_added' => 'now()');
        if (is_uploaded_file($_FILES['forms_image']['tmp_name'])) {
          $target = DIR_FS_CATALOG . DIR_WS_IMAGES . $_FILES['forms_image']['name'];
          if (file_exists($target)) {
            @unlink($target);
          }
          @move_uploaded_file($_FILES['forms_image']['tmp_name'], $target);
          $sql_data['forms_image'] = $_FILES['forms_image']['name'];
        }
        tep_db_perform(TABLE_FSS_FORMS, $sql_data);
        $forms_id = tep_db_insert_id();
        $forms_name = $_POST['forms_name'];
        $forms_confirmation_content = $_POST['forms_confirmation_content'];
        $forms_description = $_POST['forms_description'];
        $forms_blurb = $_POST['forms_blurb'];
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $sql_data = array('forms_id' => $forms_id,
                            'language_id' => $languages[$i]['id'],
                            'forms_name' => $forms_name[$i],
                            'forms_confirmation_content' => $forms_confirmation_content[$i],
                            'forms_description' => $forms_description[$i],
                            'forms_blurb' => $forms_blurb[$i]);
          tep_db_perform(TABLE_FSS_FORMS_DESCRIPTION, $sql_data);
        }        
        $forms_questions = $_POST['forms_questions'];        
//        foreach ($forms_questions as $value) {
//          tep_db_query("insert into " . TABLE_FSS_QUESTIONS_TO_FORMS . " values ('" . $forms_id . "', '" . $value . "')");
//        }
        tep_db_query("insert into " . TABLE_FSS_FORMS_TO_CATEGORIES . " values ('" . $forms_id . "', '" . $cPath . "')");
        tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action'))));
        break;
      case 'save_form':
        $forms_id = $_GET['fID'];
        $sql_data = array('forms_type' => $_POST['forms_type'],
                          'forms_post_name' => $_POST['forms_post_name'],
                          'send_email_to' => $_POST['send_email_to'],
                          'send_post_data' => $_POST['send_post_data'],
                          'enable_vvc' => $_POST['enable_vvc'],
                          'sort_order' => $_POST['sort_order'],
                          'last_modified' => 'now()');
        if (isset($_POST['forms_image_delete']) && $_POST['forms_image_delete'] == '1') {
          $sql_data['forms_image'] = '';
        }
        if (is_uploaded_file($_FILES['forms_image']['tmp_name'])) {
          $target = DIR_FS_CATALOG . DIR_WS_IMAGES . $_FILES['forms_image']['name'];
          if (file_exists($target)) {
            @unlink($target);
          }
          @move_uploaded_file($_FILES['forms_image']['tmp_name'], $target);
          $sql_data['forms_image'] = $_FILES['forms_image']['name'];
        }
        tep_db_perform(TABLE_FSS_FORMS, $sql_data, 'update', "forms_id = '" . $forms_id . "'");
        $forms_name = $_POST['forms_name'];
        $forms_confirmation_content = $_POST['forms_confirmation_content'];
        $forms_description = $_POST['forms_description'];
        $forms_blurb = $_POST['forms_blurb'];
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $sql_data = array('forms_name' => $forms_name[$i],
                            'forms_confirmation_content' => $forms_confirmation_content[$i],
                            'forms_description' => $forms_description[$i],
                            'forms_blurb' => $forms_blurb[$i]);
          tep_db_perform(TABLE_FSS_FORMS_DESCRIPTION, $sql_data, 'update', "forms_id = '" . $forms_id . "' AND language_id = '" . $languages[$i]['id'] . "'");
        }
        $forms_questions = $_POST['forms_questions'];
//        tep_db_query("DELETE FROM " . TABLE_FSS_QUESTIONS_TO_FORMS . " WHERE forms_id = '" . $forms_id . "'");
//        foreach ($forms_questions as $value) {
//          tep_db_query("insert into " . TABLE_FSS_QUESTIONS_TO_FORMS . " values ('" . $forms_id . "', '" . $value . "')");
//        }
        tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action'))));
        break;
      case 'save_system_form':
        $forms_id = $_GET['fID'];
        $forms_confirmation_content = $_POST['forms_confirmation_content'];
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $sql_data = array('forms_confirmation_content' => $forms_confirmation_content[$i]);
          tep_db_perform(TABLE_FSS_FORMS_DESCRIPTION, $sql_data, 'update', "forms_id = '" . $forms_id . "' AND language_id = '" . $languages[$i]['id'] . "'");
        }
//        $forms_questions = $_POST['forms_questions'];
//        tep_db_query("DELETE FROM " . TABLE_FSS_QUESTIONS_TO_FORMS . " WHERE forms_id = '" . $forms_id . "'");
//        foreach ($forms_questions as $value) {
//          tep_db_query("insert into " . TABLE_FSS_QUESTIONS_TO_FORMS . " values ('" . $forms_id . "', '" . $value . "')");
//        }
        tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action'))));
        break;
      case 'save_categories':
        $categories_id = $_GET['cID'];
        $categories_name = $_POST['categories_name'];
        $sort_order = $_POST['categories_sort_order'];
        tep_db_query("update " . TABLE_FSS_CATEGORIES . " set fss_categories_name = '" . $categories_name . "', sort_order = '" . $sort_order . "', last_modified = now() WHERE fss_categories_id = '" . $categories_id . "'");
        tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action'))));
        break;
      case 'insert_categories':
        $categories_name = $_POST['categories_name'];
        $sort_order = $_POST['categories_sort_order'];
        $parent_id = $_POST['cPath'];
        tep_db_query("insert into " . TABLE_FSS_CATEGORIES . " (fss_categories_name, fss_categories_parent_id, sort_order, date_added) values ('" . $categories_name . "', '" . $parent_id . "', '" . $sort_order . "', now())");
        $categories_id = tep_db_insert_id();
        tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'cID')) . 'cID=' . $categories_id));
        break;
      case 'delete_categories_confirm':
        $categories_id = $_POST['categories_id'];
        tep_delete_fss_categories($categories_id);
        tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'cID'))));
        break;
      case 'move_categories_confirm':
        $categories_id = $_POST['categories_id'];
        $new_categories_id = $_POST['move_to_category_id'];
        if ($categories_id != $new_categories_id) {
          tep_move_fss_categories($categories_id, $new_categories_id);
        }
        tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'cID'))));
        break;
      case 'delete_form_confirm':
        $forms_id = $_POST['forms_id'];
        tep_delete_fss_forms($forms_id);
        tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'fID'))));
        break;
      case 'move_form_confirm':
        $forms_id = $_POST['forms_id'];
        $new_categories_id = $_POST['move_to_category_id'];        
        if ($new_categories_id != $cPath) {
          tep_move_fss_forms($forms_id, $new_categories_id, $cPath);          
        }
        tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'fID'))));
        break;
      case 'copy_to_form_confirm':
        $forms_id = $_POST['forms_id'];
        $new_categories_id = $_POST['copy_to_category_id'];
        $child_questions = $_POST['child_questions'];
        if ($new_categories_id != $cPath) {
          tep_copy_fss_forms($forms_id, $new_categories_id, $_POST['copy_as'], $child_questions);          
        }
        tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action'))));
        break;
      case 'insert_question':        
        $forms_id = $_GET['qPath'];
        $questions_label = tep_db_prepare_input($_POST['questions_label']);
        $questions_variable = tep_db_prepare_input($_POST['questions_variable']);
        $questions_help_text = tep_db_prepare_input($_POST['questions_help_text']);
        $questions_type = tep_db_prepare_input($_POST['questions_type']);
        $sort_order = tep_db_prepare_input($_POST['sort_order']);
        $languages_id_new = tep_db_prepare_input($_POST['language_id']);
        $updatable = tep_db_prepare_input($_POST['updatable']);
        $prefilled_variable = tep_db_prepare_input($_POST['prefilled_variable']);
        $questions_layout = tep_db_prepare_input($_POST['questions_layout']);
        $sql_data = array('questions_variable' => $questions_variable,
                          'questions_type' => $questions_type,
                          'questions_layout' => $questions_layout,
                          'prefilled_variable' => $prefilled_variable,
                          'updatable' => $updatable,
                          'sort_order' => $sort_order,
                          'date_added' => 'now()');
        tep_db_perform(TABLE_FSS_QUESTIONS, $sql_data);
        
        $questions_id = tep_db_insert_id();
        for ($i = 0; $i < sizeof($languages_id_new); $i++) {
          $sql_data = array('questions_id' => $questions_id,
                            'language_id' => $languages_id_new[$i],
                            'questions_label' => $questions_label[$i],
                            'questions_help' => $questions_help_text[$i]);
          tep_db_perform(TABLE_FSS_QUESTIONS_DESCRIPTION, $sql_data);
        }
        tep_db_query("insert into " . TABLE_FSS_QUESTIONS_TO_FORMS . " values ('" . $forms_id . "', '" . $questions_id . "')");
        
        tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'qID')) . 'qID=' . $questions_id));
        break;
      case 'save_question':
        $questions_id = $_GET['qID'];
        $questions_label = tep_db_prepare_input($_POST['questions_label']);
        $questions_variable = tep_db_prepare_input($_POST['questions_variable']);
        $questions_help_text = tep_db_prepare_input($_POST['questions_help_text']);
//        $questions_type = tep_db_prepare_input($_POST['questions_type']);
        $sort_order = tep_db_prepare_input($_POST['sort_order']);
        $languages_id_new = tep_db_prepare_input($_POST['language_id']);
        $updatable = tep_db_prepare_input($_POST['updatable']);
        $prefilled_variable = tep_db_prepare_input($_POST['prefilled_variable']);
        $questions_layout = tep_db_prepare_input($_POST['questions_layout']);
        $sql_data = array('questions_variable' => $questions_variable,
//                          'questions_type' => $questions_type,
                          'questions_layout' => $questions_layout,
                          'updatable' => $updatable,
                          'prefilled_variable' => $prefilled_variable,
                          'sort_order' => $sort_order);
        tep_db_perform(TABLE_FSS_QUESTIONS, $sql_data, 'update', "questions_id = '" . $questions_id . "'");
        
        for ($i = 0; $i < sizeof($languages_id_new); $i++) {
          $sql_data = array('questions_id' => $questions_id,
                            'language_id' => $languages_id_new[$i],
                            'questions_label' => $questions_label[$i],
                            'questions_help' => $questions_help_text[$i]);
          tep_db_perform(TABLE_FSS_QUESTIONS_DESCRIPTION, $sql_data, 'update', "questions_id = '" . $questions_id . "' AND language_id = '" . $languages_id_new[$i] . "'");
        }
        tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'qID')) . 'qID=' . $questions_id));
        break;
      case 'delete_question_confirm':
        $questions_id = tep_db_prepare_input($_GET['qID']);
        if (isset($_POST['purge_questions_data']) && $_POST['purge_questions_data'] == '1') {
          tep_db_query("DELETE FROM " . TABLE_FSS_FORMS_POSTS_CONTENT . " WHERE questions_id = '" . $questions_id . "'");
        }
        tep_db_query("DELETE FROM " . TABLE_FSS_QUESTIONS . " WHERE questions_id = '" . (int)$questions_id . "'");
        tep_db_query("DELETE FROM " . TABLE_FSS_QUESTIONS_DESCRIPTION . " WHERE questions_id = '" . (int)$questions_id . "'");
        tep_db_query("DELETE FROM " . TABLE_FSS_QUESTIONS_TO_FORMS . " WHERE questions_id = '" . (int)$questions_id . "'");
        tep_db_query("DELETE FROM " . TABLE_FSS_QUESTIONS_FIELDS_VALUES . " WHERE questions_id = '" . (int)$questions_id . "'");
        tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'qID'))));
        break;
      case 'purge_question_confirm':
        $questions_id = tep_db_prepare_input($_GET['qID']);
        tep_db_query("DELETE FROM " . TABLE_FSS_FORMS_POSTS_CONTENT . " WHERE questions_id = '" . $questions_id . "'");
        tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action'))));
        break;
      case 'move_question_confirm':
        $questions_id = tep_db_prepare_input($_POST['questions_id']);
        $forms_id = tep_db_prepare_input($_POST['move_to_forms_id']);
        $old_forms_id = tep_db_prepare_input($_GET['qPath']);
        if ($forms_id != $old_forms_id) {
          tep_move_fss_question($questions_id, $forms_id, $old_forms_id);
        }        
        tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'qID'))));
        break;
      case 'copy_to_question_confirm':
        $questions_id = tep_db_prepare_input($_POST['questions_id']);
        $forms_id = tep_db_prepare_input($_POST['copy_to_forms_id']);
        if ($forms_id != $_GET['qPath']) {
          tep_copy_fss_questions($questions_id, $forms_id, $_POST['copy_as']);          
        }
        tep_redirect(tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action'))));
        break;
    }
  }
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php
if ($is_62) {
    echo '<script language="javascript" src="includes/menu.js"></script>' . "\n";
} else {
    echo '<script type="text/javascript" src="includes/prototype.js"></script>' . "\n";
    echo '<!--[if IE]>' . "\n";
    echo '<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">' . "\n";
    echo '<![endif]-->' . "\n";
}
?>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript"><!--
  function confirm_delete(obj) {
    if (obj.delete_confirm.checked == true) {
      return true;
    } else {
      alert('<?php echo TEXT_WARRING_CONFIRM_DELETION; ?>');
      return false;
    }
  }
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0"> 
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE . (tep_not_null($forms_name) ? ' - ' . $forms_name : ''); ?></td>
            <td class="main" align="right">
<?php 
              if (!(isset($_GET['qPath']) && $_GET['qPath'] != '') && !tep_not_null($action)) { 
                echo tep_draw_form('forms', FILENAME_FSS_FORMS_BUILDER, '', 'get', '', 'SSL'); 
                echo tep_draw_hidden_field(tep_session_name(), tep_session_id());
                echo HEADING_TITLE_SEARCH . tep_draw_input_field('search') . '<br>' . HEADING_TITLE_GO_TO . tep_draw_pull_down_menu('cPath', tep_fss_get_folder_tree(), $_GET['cPath'], 'onchange="this.form.submit();"');
                echo '</form>';
              } else {
                echo '&nbsp;';
              }
?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">              
<?php
  if (isset($_GET['qPath']) && $_GET['qPath'] != '') {
    echo tep_draw_form('sort', FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action')) . 'action=sort');
?>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="30"><?php echo TEXT_HEADER_ID; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_QUESTIONS_LABEL; ?></td>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_QUESTIONS_TYPE; ?></td>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_QUESTIONS_VARIABLE; ?></td>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_STATUS; ?>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_SORT; ?>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?></td>
              </tr>
<?php
    $questions_query_raw = "SELECT fq.questions_id, fq.questions_variable, fq.questions_type, fq.updatable, fq.prefilled_variable, fq.sort_order, fqd.questions_label, fqd.questions_help, fq.questions_status, fq.date_added, fq.questions_layout FROM " . TABLE_FSS_QUESTIONS . " fq, " . TABLE_FSS_QUESTIONS_DESCRIPTION . " fqd, " . TABLE_FSS_QUESTIONS_TO_FORMS . " q2f WHERE q2f.questions_id = fq.questions_id AND q2f.forms_id = '" . $_GET['qPath'] . "' AND fq.questions_id = fqd.questions_id AND fqd.language_id = '" . $languages_id . "' order by fq.sort_order, fqd.questions_label";
    $questions_query = tep_db_query($questions_query_raw);
    $sort_order = 0;
    while ($questions = tep_db_fetch_array($questions_query)) {
      $sort_order += 10;
      if ((!isset($_GET['qID']) || (isset($_GET['qID']) && ($_GET['qID'] == $questions['questions_id']))) && !isset($qInfo) && (substr($action, 0, 3) != 'new')) {
        $qInfo = new objectInfo($questions);
      }
  
      if (isset($qInfo) && is_object($qInfo) && ($questions['questions_id'] == $qInfo->questions_id)) {
        $onclick = 'document.location.href=\'' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('qID')) . 'qID=' . $qInfo->questions_id . '&action=edit_question') . '\';';
        echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
      } else {
        $onclick = 'document.location.href=\'' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('qID', 'action')) . 'qID=' . $questions['questions_id']) . '\'';
        echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
      }
?>
                <td class="dataTableContent" align="left" onclick="<?php echo $onclick; ?>"><?php echo $questions['questions_id']; ?></td>
                <td class="dataTableContent" onclick="<?php echo $onclick; ?>"><?php echo '<a href="' . tep_href_link(FILENAME_FSS_VALUES_MANAGER, tep_get_all_get_params(array('action', 'qID')) . 'qID=' . $questions['questions_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>&nbsp;' . $questions['questions_label']; ?></td>
                <td class="dataTableContent" align="left" onclick="<?php echo $onclick; ?>"><?php echo $questions['questions_type']; ?></td>
                <td class="dataTableContent" align="left" onclick="<?php echo $onclick; ?>"><?php echo $questions['questions_variable']; ?></td>
                <td class="dataTableContent" align="left" onclick="<?php echo $onclick; ?>">
<?php
      if ($questions['questions_status'] == '1') {
        echo (tep_is_special_question($questions['questions_variable']) ? tep_image(DIR_WS_IMAGES . 'icon_status_gold.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) : tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10)) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'flag', 'qID')) . 'action=setqflag&flag=0&qID=' . $questions['questions_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'flag', 'qID')) . 'action=setqflag&flag=1&qID=' . $questions['questions_id']) . '">' . (tep_is_special_question($questions['questions_variable']) ? tep_image(DIR_WS_IMAGES . 'icon_status_gold_border.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) : tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10)) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?>                
                </td>    
                <td class="dataTableContent"><?php echo tep_draw_input_field('sort_order[' . $questions['questions_id'] . ']', $sort_order, 'size="3" tabindex="' . $sort_order . '"'); ?></td>
                <td class="dataTableContent" align="right" onclick="<?php echo $onclick; ?>"><?php 
                  if (isset($qInfo) && is_object($qInfo) && ($questions['questions_id'] == $qInfo->questions_id) ) { 
                    echo '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'qID')) . 'action=edit_question&qID=' . $qInfo->questions_id) . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', ICON_EDIT) . '</a> ' . tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); 
                  } else { 
                    echo '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'qID')) . 'action=edit_question&qID=' . $questions['questions_id']) . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', ICON_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('qID')) . 'qID=' . $questions['questions_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; 
                  } ?></td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">                  
<?php
    if (empty($action)) {
?>
                  <tr>
                    <td align="left"><?php echo TEXT_INFO_QUESTIONS . tep_db_num_rows($questions_query); ?></td>
                    <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'page', 'qPath', 'qID')) . 'fID=' . $_GET['qPath']) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> ' . tep_image_submit('button_update_sort.gif', IMAGE_UPDATE_SORT) . ' <a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action')) . 'action=new_question') . '">' . tep_image_button('button_new_question.gif', IMAGE_NEW_QUESTIONS) . '</a>' . (tep_has_special_question($_GET['qPath']) ? '' : ' <a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action')) . 'action=new_special_question') . '">' . tep_image_button('button_new_special_question.gif', IMAGE_NEW_SPECIAL_QUESTIONS) . '</a>'); ?></td>
                  </tr>                  
<?php
    }
?> 
                  </form>
<?php
  } elseif (isset($_GET['search']) && tep_not_null($_GET['search'])) {
?>
        <tr class="dataTableHeadingRow">
          <td class="dataTableHeadingContent" width="30"><?php echo TEXT_HEADER_ID; ?></td>
          <td class="dataTableHeadingContent"><?php echo TEXT_HEADING_FORM_NAME; ?></td>
          <td class="dataTableHeadingContent" align="right"><?php echo TEXT_HEADING_STATUS; ?></td>
          <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?></td>
        </tr>        
<?php
    $row = 0;
    $forms_query_raw = "SELECT DISTINCT ff.forms_id, ffd.forms_name, ff.forms_status, ff.sort_order, ff.last_modified, ff.date_added, ff.forms_image, ff2c.categories_id FROM " . TABLE_FSS_QUESTIONS_DESCRIPTION . " fqd, " . TABLE_FSS_QUESTIONS_TO_FORMS . " fq2f, " . TABLE_FSS_FORMS_DESCRIPTION . " ffd, " . TABLE_FSS_FORMS . " ff, " . TABLE_FSS_FORMS_TO_CATEGORIES . " ff2c WHERE ff2c.forms_id = ff.forms_id AND ff.forms_id = ffd.forms_id AND  ffd.forms_id = fq2f.forms_id AND fq2f.questions_id = fqd.questions_id AND ffd.language_id = fqd.language_id AND fqd.language_id = '" . $languages_id . "' AND (fqd.questions_label LIKE '%" . $_GET['search'] . "%' OR fqd.questions_help LIKE '%" . $_GET['search'] . "%' OR ffd.forms_name LIKE '%" . $_GET['search'] . "%' OR ffd.forms_confirmation_content LIKE '%" . $_GET['search'] . "%' OR ffd.forms_description LIKE '%" . $_GET['search'] . "%')";
//    $forms_query_raw = "SELECT ff.forms_id, ffd.forms_name, ff.forms_status, ff.sort_order FROM " . TABLE_FSS_FORMS . " ff, " . TABLE_FSS_FORMS_DESCRIPTION . " ffd WHERE ff.forms_id = ffd.forms_id AND ffd.language_id = '" . $languages_id . "' AND ffd.forms_name like '%" . $_GET['search'] . "%' order by ff.sort_order, ffd.forms_name";

    $forms_query = tep_db_query($forms_query_raw);
    while ($forms = tep_db_fetch_array($forms_query)) {
      $row++;
      if ($row == 1) {
        $forms_query_temp = tep_db_query("SELECT ff.forms_id, ff.forms_status, ff.forms_type, ff.forms_post_name, ff.send_email_to, ff.send_post_data, ff.enable_vvc, ff.sort_order, ff.last_modified, ff.date_added, ff.forms_image, ffd.forms_name, ffd.forms_confirmation_content, ff2c.categories_id FROM " . TABLE_FSS_FORMS . " ff, " . TABLE_FSS_FORMS_DESCRIPTION . " ffd, " . TABLE_FSS_FORMS_TO_CATEGORIES . " ff2c WHERE ff2c.forms_id = ff.forms_id AND ff.forms_id = ffd.forms_id AND ffd.language_id = '" . $languages_id . "' AND ff.forms_id = '" . (int)$forms['forms_id'] . "'");
        $ftemp_array = tep_db_fetch_array($forms_query_temp);
        $fInfo = new objectInfo($ftemp_array);
        $_GET['cPath'] = $fInfo->categories_id;
      }
  
      if (is_object($fInfo) && $forms['forms_id'] == $fInfo->forms_id && $action != 'new_form' && $action != 'new_categories') {
        $onclick = 'document.location.href=\'' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'cID', 'selected_box', 'search')) . 'action=edit_form') . '\'';
        echo '          <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
      } else {
        $onclick = 'document.location.href=\'' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('fID','action', 'cID', 'selected_box', 'search', 'cPath')) . 'cPath=' . $forms['categories_id'] . '&fID=' . $forms['forms_id']) . '\'';
        echo '          <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
       } 
?>
                 <td class="dataTableContent"><?php echo $forms['forms_id']; ?></td>
                <td class="dataTableContent" onclick="<?php echo $onclick; ?>"><?php echo '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('fID','action', 'cID', 'selected_box', 'search')) . 'qPath=' . $forms['forms_id']) . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', ICON_EDIT) . '</a>&nbsp;<b>' . $forms['forms_name'] . '</b>'; ?></td>
                <td class="dataTableContent" align="right" onclick="<?php echo $onclick; ?>">
<?php
      if ($forms['forms_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, 'action=setflag&flag=0&fID=' . $forms['forms_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, 'action=setflag&flag=1&fID=' . $forms['forms_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?>                
                </td>
                <td class="dataTableContent" align="right" onclick="<?php echo $onclick; ?>"><?php 
      if (isset($fInfo) && is_object($fInfo) && ($forms['forms_id'] == $fInfo->forms_id) && $action != 'new_form' && $action != 'new_categories') { 
        echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); 
      } else { 
        echo '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('fID', 'action', 'search', 'cPath')) . 'cPath=' . $forms['categories_id'] . '&fID=' . $forms['forms_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; 
      } 
?></td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  } else {        
    echo tep_draw_form('sort', FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action')) . 'action=sort');
?>
        <tr class="dataTableHeadingRow">
          <td class="dataTableHeadingContent" width="30"><?php echo TEXT_HEADER_ID; ?></td>
          <td class="dataTableHeadingContent"><?php echo TEXT_HEADING_FORM_NAME; ?></td>
          <td class="dataTableHeadingContent" align="right"><?php echo TEXT_HEADING_STATUS; ?></td>
          <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_SORT; ?></td>
          <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?></td>
        </tr>
<?php
    if (isset($_GET['cID']) && $_GET['cID'] != '') {
      $categories_query_temp = tep_db_query("SELECT * FROM " . TABLE_FSS_CATEGORIES . " WHERE fss_categories_id = '" . (int)$_GET['cID'] . "' order by sort_order, fss_categories_name");
    } else {
      $categories_query_temp = tep_db_query("SELECT * FROM " . TABLE_FSS_CATEGORIES . " WHERE fss_categories_parent_id = '" . $cPath . "' order by sort_order, fss_categories_name");
    }
    $categories_array = tep_db_fetch_array($categories_query_temp);
    if (is_array($categories_array)) {
      $categories_childs = array('childs_count' => tep_childs_in_categories_count($categories_array['fss_categories_id']));
      $categories_forms = array('forms_count' => tep_forms_in_categories_count($categories_array['fss_categories_id']));
      $categories_array = array_merge($categories_array, $categories_childs, $categories_forms);
      $cInfo = new objectInfo($categories_array);
    } else {
      $cInfo = new objectInfo(array());
    }
    $sort_order = 0;
    $fss_categories_query = tep_db_query("SELECT * FROM " . TABLE_FSS_CATEGORIES . " WHERE fss_categories_parent_id = '" . $cPath . "' order by sort_order, fss_categories_name");
    while ($fss_categories = tep_db_fetch_array($fss_categories_query)) {
      $sort_order += 10;
      if (((isset($_GET['cID']) && $_GET['cID'] == $fss_categories['fss_categories_id']) || (!isset($_GET['cID']) && !isset($_GET['fID']) && $fss_categories['fss_categories_id'] == $cInfo->fss_categories_id)) && $action != 'new_categories' && $action != 'new_form') {
        $onclick = 'document.location.href=\'' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'fID', 'cID', 'cPath')) . 'cPath=' . $fss_categories['fss_categories_id']) . '\'';
        echo '          <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
      } else {
        $onclick = 'document.location.href=\'' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'fID', 'cID')) . 'cID=' . $fss_categories['fss_categories_id']) . '\'';
        echo '          <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
       }
?>
                <td class="dataTableContent" onclick="<?php echo $onclick; ?>"><?php echo $fss_categories['fss_categories_id']?></td>
                <td class="dataTableContent" onclick="<?php echo $onclick; ?>"><a href="<?php echo tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'fID', 'cID', 'cPath')) . 'cPath=' . $fss_categories['fss_categories_id']); ?>"><?php echo tep_image(DIR_WS_ICONS . ($fss_categories['fss_categories_id'] == '1' ? 'folder_blue.gif' : 'folder.gif'), ICON_FOLDER) . '</a>&nbsp;<b>' . $fss_categories['fss_categories_name'] . '</b>'; ?></td>
                <td class="dataTableContent" align="right" onclick="<?php echo $onclick; ?>"><?php
      if ($fss_categories['fss_categories_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, 'action=setcatflag&flag=0&cID=' . $fss_categories['fss_categories_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, 'action=setcatflag&flag=1&cID=' . $fss_categories['fss_categories_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
                <td align="right"><?php echo ($fss_categories['fss_categories_id'] == '1' ? tep_draw_hidden_field('c_sort_order[' . $fss_categories['fss_categories_id'] . ']', '-9999') : tep_draw_input_field('c_sort_order[' . $fss_categories['fss_categories_id'] . ']', $sort_order, 'size="3"')); ?></td>
                <td class="dataTableContent" align="right" onclick="<?php echo $onclick; ?>">
<?php 
      if (((isset($_GET['cID']) && $_GET['cID'] == $fss_categories['fss_categories_id']) || (!isset($_GET['cID']) && !isset($_GET['fID']) && $fss_categories['fss_categories_id'] == $cInfo->fss_categories_id)) && $action != 'new_categories' && $action != 'new_form') { 
        echo ($fss_categories['fss_categories_id'] == 1 ? '' : '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('cID', 'action', 'search', 'cPath', 'fID')) . 'cPath=' . $_GET['cPath'] . '&cID=' . $fss_categories['fss_categories_id'] . '&action=edit_categories') . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', ICON_EDIT) . '</a>&nbsp;') . tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); 
      } else { 
        echo ($fss_categories['fss_categories_id'] == 1 ? '' : '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('cID', 'action', 'search', 'cPath', 'fID')) . 'cPath=' . $_GET['cPath'] . '&cID=' . $fss_categories['fss_categories_id'] . '&action=edit_categories') . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', ICON_EDIT) . '</a>&nbsp;') . '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $fss_categories['fss_categories_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; 
      } 
?></td>
              </tr>
<?php
    }    
    
    $forms_query_raw = "SELECT ff.forms_id, ffd.forms_name, ff.forms_status, ff.sort_order, ff.last_modified, ff.date_added, ff.forms_image FROM " . TABLE_FSS_FORMS . " ff, " . TABLE_FSS_FORMS_DESCRIPTION . " ffd, " . TABLE_FSS_FORMS_TO_CATEGORIES . " ff2c WHERE ff.forms_id = ff2c.forms_id AND ff.forms_id = ffd.forms_id AND ffd.language_id = '" . $languages_id . "' AND ff2c.categories_id = '" . $cPath . "' order by ff.sort_order";

    $forms_query = tep_db_query($forms_query_raw);
    while ($forms = tep_db_fetch_array($forms_query)) {
      $sort_order += 10;
      if ((isset($_GET['fID']) && ($_GET['fID'] == $forms['forms_id'])) || (!isset($_GET['fID']) && tep_childs_in_categories_count($cPath) == 0) && !is_object($fInfo)) {
        $forms_query_temp = tep_db_query("SELECT ff.forms_id, ff.forms_status, ff.forms_type, ff.forms_post_name, ff.send_email_to, ff.send_post_data, ff.enable_vvc, ff.sort_order, ff.last_modified, ff.date_added, ff.forms_image, ffd.forms_name, ffd.forms_confirmation_content FROM " . TABLE_FSS_FORMS . " ff, " . TABLE_FSS_FORMS_DESCRIPTION . " ffd WHERE ff.forms_id = ffd.forms_id AND ffd.language_id = '" . $languages_id . "' AND ff.forms_id = '" . (int)$forms['forms_id'] . "'");
        $ftemp_array = tep_db_fetch_array($forms_query_temp);
        $fInfo = new objectInfo($ftemp_array);
      }
  
      if (is_object($fInfo) && $forms['forms_id'] == $fInfo->forms_id && $action != 'new_form' && $action != 'new_categories') {
        $onclick = ($cPath == 1 ? '' : ' onclick="document.location.href=\'' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'cID', 'selected_box')) . 'action=edit_form') . '\'"');
        echo '          <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
      } else {
        $onclick = ' onclick="document.location.href=\'' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('fID','action', 'cID', 'selected_box')) . 'fID=' . $forms['forms_id']) . '\'"';
        echo '          <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
       } 
?>
                 <td class="dataTableContent"<?php echo $onclick; ?>><?php echo $forms['forms_id']; ?></td>
                <td class="dataTableContent"<?php echo $onclick; ?>><?php echo '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('fID','action', 'cID', 'selected_box')) . 'qPath=' . $forms['forms_id']) . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', ICON_EDIT) . '</a>&nbsp;<b>' . $forms['forms_name'] . '</b>'; ?></td>
                <td class="dataTableContent" align="right"<?php echo $onclick; ?>>
<?php
      if ($forms['forms_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, 'action=setflag&flag=0&fID=' . $forms['forms_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, 'action=setflag&flag=1&fID=' . $forms['forms_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?>
                </td>
                <td align="right"><?php echo ($cPath == '1' ? '&nbsp;' : tep_draw_input_field('sort_order[' . $forms['forms_id'] . ']', $sort_order, 'size="3"')); ?></td>
                <td class="dataTableContent" align="right"<?php echo $onclick; ?>><?php 
      if (isset($fInfo) && is_object($fInfo) && ($forms['forms_id'] == $fInfo->forms_id) && $action != 'new_form' && $action != 'new_categories') { 
        echo ($cPath == 1 ? '' : '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('fID', 'action', 'search', 'cPath', 'cID')) . 'cPath=' . $_GET['cPath'] . '&fID=' . $forms['forms_id'] . '&action=edit_form') . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', ICON_EDIT) . '</a>&nbsp;') . tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); 
      } else { 
        echo ($cPath == 1 ? '' : '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('fID', 'action', 'search', 'cPath', 'cID')) . 'cPath=' . $_GET['cPath'] . '&fID=' . $forms['forms_id'] . '&action=edit_form') . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', ICON_EDIT) . '</a>&nbsp;') . '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('fID', 'action')) . 'fID=' . $forms['forms_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; 
      } 
?></td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">                  
<?php
    if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php 
                    if ($cPath != '0') {
                      $parent = tep_db_fetch_array(tep_db_query("SELECT fss_categories_parent_id FROM " . TABLE_FSS_CATEGORIES . " WHERE fss_categories_id = '" . $cPath . "'"));
                      echo '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'cPath', 'cID', 'fID')) . 'cPath=' . $parent['fss_categories_parent_id']) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;';
                    }
                    echo tep_image_submit('button_update_sort.gif', IMAGE_UPDATE_SORT);
                    echo ($cPath == 1 ? '' : ' <a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action')) . 'action=new_categories') . '">' . tep_image_button('button_new_folder.gif', IMAGE_NEW_FOLDER) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action')) . 'action=new_form') . '">' . tep_image_button('button_new_form.gif', IMAGE_NEW_FORMS) . '</a>'); ?></td>
                  </tr>
<?php
    }
?>
                  </form>
<?php
  }  
?>
        <tr>
          <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>              
              <?php
              // RCI code start
              echo $cre_RCI->get('fssformsbuilder', 'listingbottom');
              // RCI code eof
              ?>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
  </table></td>
<?php
  $heading = array();
  $contents = array();
  $type_array_name = array('Input', 'Text Area', 'Drop Down Menu', 'Drop Down List', 'Radio Button Group', 'Check Box', 'File Upload', 'Hidden');
  foreach ($type_array_name as $value) {
    $type_array[] = array('id' => $value,
                          'text' => $value);
    if ($value == 'Input' || $value == 'Hidden') {
      $special_type_array[] = array('id' => $value,
                                    'text' => $value);
    }
  }
  
  switch ($action) {    
    case 'new_question':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_QUESTIONS . '</b>');

      $contents = array('form' => tep_draw_form('questions', FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action')) . 'action=insert_question'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $help_text = '<table>';
      $label = '<table>';
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $label .= '<tr><td>' . $languages[$i]['code'] . ':</td><td>' . tep_draw_input_field('questions_label[]') . tep_draw_hidden_field('language_id[]', $languages[$i]['id']) . '</td></tr>';
        $help_text .= '<tr><td>' . $languages[$i]['code'] . ':</td><td>' . tep_draw_textarea_field('questions_help_text[]', '', '20', '3') . '</td></tr>';
      }
      $label .= '</table>';
      $help_text .= '</table>';
      $contents[] = array('text' => '<table><tr><td valign="top"><b>' . TEXT_INFO_LABEL . '</b></td><td>' . $label . '</td></tr>' .
                                    '<tr><td><b>' . TEXT_INFO_VARIABLE . '</b></td><td>' . tep_draw_input_field('questions_variable') . '</td></tr>' .
                                    '<!-- tr><td><b>' . TEXT_PREFILLED_VARIABLE . '</b></td><td>' . tep_draw_input_field('prefilled_variable') . '</td></tr -->' .
                                    '<tr><td valign="top"><b>' . TEXT_INFO_HELP_TEXT . '</b></td><td>' . $help_text . '</td></tr>' .
                                    '<tr><td><b>' . TEXT_INFO_TYPE . '</b></td><td>' . tep_draw_pull_down_menu('questions_type', $type_array) . '</td></tr>' .
                                    '<tr><td><b>' . TEXT_INFO_TITLE_SORT_ORDER . '</b></td><td>' . tep_draw_input_field('sort_order', '', 'size="5"') . '</td></tr><tr><td><b>' . TEXT_UPDATABLE . '</b></td><td>' . tep_draw_checkbox_field('updatable', '1', $qInfo->updatable) . '</td></tr><tr><td><b>' . TEXT_QUESTION_LAYOUT . '</b></td><td>' . tep_draw_radio_field('questions_layout', '0', true) . TEXT_QUESTION_LAYOUT_WIDE . '&nbsp;&nbsp;&nbsp;' . tep_draw_radio_field('questions_layout', '1') . TEXT_QUESTION_LAYOUT_NARROW . '</td></tr></table>');
      // RCI code start
      $cre_RCI->get('fssformsbuilder', 'newquestion'); 
      // RCI code eof
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . '&nbsp;<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'new_special_question':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_SPECIAL_QUESTIONS . '</b>');

      $contents = array('form' => tep_draw_form('questions', FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action')) . 'action=insert_question'));
      $contents[] = array('text' => TEXT_INFO_INSERT_SPECIAL_INTRO);
      $help_text = '<table>';
      $label = '<table>';
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $label .= '<tr><td>' . $languages[$i]['code'] . ':</td><td>' . tep_draw_input_field('questions_label[]') . tep_draw_hidden_field('language_id[]', $languages[$i]['id']) . '</td></tr>';
        $help_text .= '<tr><td>' . $languages[$i]['code'] . ':</td><td>' . tep_draw_textarea_field('questions_help_text[]', '', '20', '3') . '</td></tr>';
      }
      $special_type = tep_fss_get_special_dropdown($_GET['qPath']);      
      $label .= '</table>';
      $help_text .= '</table>';
      $contents[] = array('text' => '<table><tr><td valign="top"><b>' . TEXT_INFO_LABEL . '</b></td><td>' . $label . '</td></tr>' .
                                    '<tr><td><b>' . TEXT_INFO_SPECIAL_TYPE . '</b></td><td>' . tep_draw_pull_down_menu('questions_variable', $special_type) . '</td></tr>' .
                                    '<!-- tr><td><b>' . TEXT_PREFILLED_VARIABLE . '</b></td><td>' . tep_draw_input_field('prefilled_variable') . '</td></tr -->' .
                                    '<tr><td valign="top"><b>' . TEXT_INFO_HELP_TEXT . '</b></td><td>' . $help_text . '</td></tr>' .
                                    '<tr><td><b>' . TEXT_INFO_TYPE . '</b></td><td>' . tep_draw_pull_down_menu('questions_type', $special_type_array) . '</td></tr>' .
                                    '<tr><td><b>' . TEXT_INFO_TITLE_SORT_ORDER . '</b></td><td>' . tep_draw_input_field('sort_order', '', 'size="5"') . '</td></tr><tr><td><b>' . TEXT_UPDATABLE . '</b></td><td>' . tep_draw_checkbox_field('updatable', '1', $qInfo->updatable) . '</td></tr><tr><td><b>' . TEXT_QUESTION_LAYOUT . '</b></td><td>' . tep_draw_radio_field('questions_layout', '0', true) . TEXT_QUESTION_LAYOUT_WIDE . '&nbsp;&nbsp;&nbsp;' . tep_draw_radio_field('questions_layout', '1') . TEXT_QUESTION_LAYOUT_NARROW . '</td></tr></table>');
      // RCI code start
      $cre_RCI->get('fssformsbuilder', 'newspecialquestion'); 
      // RCI code eof
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . '&nbsp;<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit_question':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_QUESTIONS . '</b>');
      $contents = array('form' => tep_draw_form('questions', FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action')) . 'action=save_question'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      
      $help_text = '<table>';
      $label = '<table>';
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $questions_description = tep_db_fetch_array(tep_db_query("SELECT questions_label,questions_help FROM " . TABLE_FSS_QUESTIONS_DESCRIPTION . " WHERE questions_id = '" . $qInfo->questions_id . "' AND language_id = '" . $languages[$i]['id'] . "'"));
        $label .= '<tr><td>' . $languages[$i]['code'] . ':</td><td>' . tep_draw_input_field('questions_label[]', $questions_description['questions_label']) . tep_draw_hidden_field('language_id[]', $languages[$i]['id']) . '</td></tr>';
        $help_text .= '<tr><td>' . $languages[$i]['code'] . ':</td><td>' . tep_draw_textarea_field('questions_help_text[]', '', '20', '3', $questions_description['questions_help']) . '</td></tr>';
      }
      $label .= '</table>';
      $help_text .= '</table>';
      if ($qInfo->questions_layout == '0') {
        $layout_wide = true;
        $layout_narrow = false;
      } else {
        $layout_wide = false;
        $layout_narrow = true;
      }
      $contents[] = array('text' => '<table><tr><td valign="top"><b>' . TEXT_INFO_LABEL . '</b></td><td>' . $label . '</td></tr>' .
                                    '<tr><td><b>' . TEXT_INFO_VARIABLE . '</b></td><td>' . tep_draw_input_field('questions_variable', $qInfo->questions_variable) . '</td></tr>' .
                                    '<!-- tr><td><b>' . TEXT_PREFILLED_VARIABLE . '</b></td><td>' . tep_draw_input_field('prefilled_variable', $qInfo->prefilled_variable) . '</td></tr -->' .
                                    '<tr><td valign="top"><b>' . TEXT_INFO_HELP_TEXT . '</b></td><td>' . $help_text . '</td></tr>' .
                                    '<tr><td><b>' . TEXT_INFO_TYPE . '</b></td><td>' . tep_draw_pull_down_menu('questions_type_show', $type_array, $qInfo->questions_type, 'disabled="true"') . tep_draw_hidden_field('questions_type', $qInfo->questions_type) . '</td></tr>' .
                                    '<tr><td><b>' . TEXT_INFO_TITLE_SORT_ORDER . '</b></td><td>' . tep_draw_input_field('sort_order', $qInfo->sort_order, 'size="5"') . '</td></tr><tr><td><b>' . TEXT_UPDATABLE . '</b></td><td>' . tep_draw_checkbox_field('updatable', '1', $qInfo->updatable) . '</td></tr><tr><td><b>' . TEXT_QUESTION_LAYOUT . '</b></td><td>' . tep_draw_radio_field('questions_layout', '0', $layout_wide) . TEXT_QUESTION_LAYOUT_WIDE . '&nbsp;&nbsp;&nbsp;' . tep_draw_radio_field('questions_layout', '1', $layout_narrow) . TEXT_QUESTION_LAYOUT_NARROW . '</td></tr></table>');
      // RCI code start
      $cre_RCI->get('fssformsbuilder', 'editquestion'); 
      // RCI code eof
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete_question':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_QUESTIONS . '</b>');

      $contents = array('form' => tep_draw_form('questions', FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'qID')) . 'qID=' . $qInfo->questions_id . '&action=delete_question_confirm', 'post', 'onsubmit="return confirm_delete(this);"'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $qInfo->questions_label . '</b>');
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('purge_questions_data', '1') . '&nbsp;' . TEXT_INFO_PURGE_DATA);
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_confirm', '1') . '&nbsp;' . TEXT_INFO_CONFIRM_DELETION);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('qID', 'action')) . 'qID=' . $qInfo->questions_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'purge_question':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_PURGE_QUESTIONS . '</b>');

      $contents = array('form' => tep_draw_form('questions', FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'qID')) . 'qID=' . $qInfo->questions_id . '&action=purge_question_confirm'));
      $contents[] = array('text' => TEXT_INFO_PURGE_INTRO);
      $contents[] = array('text' => '<br><b>' . $qInfo->questions_label . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_purge.gif', IMAGE_PURGE) . '&nbsp;<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('qID', 'action')) . 'qID=' . $qInfo->questions_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'move_question':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_QUESTION . '</b>');
      $contents = array('form' => tep_draw_form('questions', FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action')) . 'action=move_question_confirm') . tep_draw_hidden_field('questions_id', $qInfo->questions_id));
      $contents[] = array('text' => sprintf(TEXT_MOVE_QUESTION_INTRO, $qInfo->questions_label));
      $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $qInfo->questions_label) . '<br>' . tep_draw_pull_down_menu('move_to_forms_id', tep_get_forms(), $qPath));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'copy_to_question':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO_QUESTION . '</b>');
      $contents = array('form' => tep_draw_form('question', FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action')) . 'action=copy_to_question_confirm') . tep_draw_hidden_field('questions_id', $qInfo->questions_id));
      $contents[] = array('text' => sprintf(TEXT_COPY_TO_QUESTION_INTRO, $qInfo->questions_label));
      $contents[] = array('text' => '<br>' . sprintf(TEXT_COPY_TO, $qInfo->questions_label) . '<br>' . tep_draw_pull_down_menu('copy_to_forms_id', tep_get_forms(), $qPath));
      $contents[] = array('text' => '<br>' . TEXT_HOW_TO_COPY . '<br>' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_QUESTION_AS_LINK . '<br>' . tep_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_QUESTION_AS_DUPLICATE);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy_to.gif', IMAGE_COPY_TO) . ' <a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'new_form':
      $heading[] = array('align'=>'left', 'text' => '<b>' . TEXT_INFO_HEADING_NEW_FORMS . '</b>');
      $contents = array('form' => tep_draw_form('forms', FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action')) . 'action=insert_form', 'post', 'enctype="multipart/form-data"'));
      $str1 = '<table>';
      $str2 = '<table>';
      $str3 = '<table>';
      $str4 = '<table>';
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $str1 .= '<tr><td>' . $languages[$i]['code'] . ': </td><td>' . tep_draw_input_field('forms_name[]') . '</td></tr>';
        $str2 .= '<tr><td>' . $languages[$i]['code'] . ': </td><td>' . tep_draw_textarea_field('forms_confirmation_content[]', '', '28', '5') . '</td></tr>';
        $str3 .= '<tr><td>' . $languages[$i]['code'] . ': </td><td>' . tep_draw_textarea_field('forms_description[]', '', '28', '5') . '</td></tr>';
        $str4 .= '<tr><td>' . $languages[$i]['code'] . ': </td><td>' . tep_draw_textarea_field('forms_blurb[]', '', '28', '5') . '</td></tr>';
      }
      $str1 .= '</table>';
      $str2 .= '</table>';
      $str3 .= '</table>';
      $str4 .= '</table>';
      $contents[] = array( 'align' =>'left',  'text' => '<b>' . TEXT_INFO_TITLE_FORMS_NEW . '</b><br>'. $str1);
      $contents[] = array( 'align' =>'left',  'text' => '<b>' . TEXT_FORM_DESCRIPTION . '</b><br>'. $str3);
//      $contents[] = array( 'align' =>'left',  'text' => '<table><tr><td><b>' . TEXT_FORM_TYPE . '</b></td><td align="left">'. tep_draw_radio_field('forms_type', '0', true) . '&nbsp;' . TEXT_FORM_TYPE_FORM . '</td></tr><tr><td>&nbsp;</td><td>' . tep_draw_radio_field('forms_type', '1') . '&nbsp;' . TEXT_FORM_TYPE_SURVEY . '</td></tr><tr><td>&nbsp;</td><td>' . tep_draw_radio_field('forms_type', '2') . '&nbsp;' . TEXT_FORM_TYPE_POLL . '</td></tr></table>');
      $contents[] = array( 'align' =>'left',  'text' => '<table><tr><td><b>' . TEXT_FORM_TYPE . '</b></td><td align="left">'. tep_draw_radio_field('forms_type', '0', true) . '&nbsp;' . TEXT_FORM_TYPE_FORM . '</td></tr><tr><td>&nbsp;</td><td>' . tep_draw_radio_field('forms_type', '1') . '&nbsp;' . TEXT_FORM_TYPE_SURVEY . '</td></tr></table>');
      $contents[] = array( 'align' =>'left',  'text' => '<b>' . TEXT_INFO_FORMS_POST_NAME . '</b><br>'. tep_draw_input_field('forms_post_name') . '<br>');
      $contents[] = array( 'align' =>'left',  'text' => '<b>' . TEXT_INFO_FORMS_SEND_EMAIL_TO . '</b><br>'. tep_draw_input_field('send_email_to', '', 'size="30"') . '<br>' . TEXT_INFO_FORMS_SEND_EMAIL_TO_INTRO . '<br>');
      $contents[] = array('text' => '<b>' . TEXT_INFO_FORMS_IMAGE . '</b>' . tep_draw_file_field('forms_image', '15'));
      $contents[] = array( 'align' =>'left',  'text' => '<table><tr><td rowspan="2" valign="top"><b>' . TEXT_FORM_SEND_POST_DATA . '</b><br>' . TEXT_FORM_SEND_POST_DATA_INTRO . '</td><td align="left" valign="top">'. tep_draw_radio_field('send_post_data', '1', true) . '&nbsp;True</td></tr><tr><td align="left" valign="top">' . tep_draw_radio_field('send_post_data', '0') . '&nbsp;False</td></tr></table>');
      $contents[] = array( 'align' =>'left',  'text' => '<table><tr><td rowspan="2" valign="top"><b>' . TEXT_FORM_ENABLE_VVC . '</b></td><td align="left" valign="top">'. tep_draw_radio_field('enable_vvc', '1', true) . '&nbsp;True</td></tr><tr><td align="left" valign="top">' . tep_draw_radio_field('enable_vvc', '0') . '&nbsp;False</td></tr></table>');
      $contents[] = array( 'align' =>'left',  'text' => '<b>' . TEXT_FORM_CONFIRMATION_CONTENT . '</b><br>'. $str2);
      $contents[] = array( 'align' =>'left',  'text' => '<b>' . TEXT_FORM_FORMS_BLURB . '</b><br>'. $str4);
      $contents[] = array( 'align' =>'left',  'text' => '<b>' . TEXT_INFO_TITLE_SORT_ORDER . '</b>' . tep_draw_input_field('sort_order', '', 'size="3"'));
/*
      $questions_all = tep_fss_get_questions();
      $str = '<div style="overflow-x:scroll; width:200px; overflow: -moz-scrollbars-horizontal;"><SELECT name="forms_questions[]" size="' . sizeof($questions_all) .'" multiple>';
      for ($i = 0, $n = sizeof($questions_all); $i < $n; $i++) {
        $str .= '<option value="'.$questions_all[$i]['id'].'">'. $questions_all[$i]['label'] .'</option>';
      }
      $str .= '</select></div>';
      $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_QUESTIONS . '</b><br>&nbsp;' . $str);
*/
      // RCI code start
      $cre_RCI->get('fssformsbuilder', 'newform'); 
      // RCI code eof
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . ' <a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></form>');
      break;
    case 'edit_form':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_FORMS . '</b>');
      $contents = array('form' => tep_draw_form('forms', FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action')) . 'action=save_form', 'post', 'enctype="multipart/form-data"'));
      $str1 = '<table>';
      $str2 = '<table>';
      $str3 = '<table>';
      $str4 = '<table>';
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $f_name = tep_db_fetch_array(tep_db_query("SELECT forms_name, forms_confirmation_content, forms_description, forms_blurb FROM " . TABLE_FSS_FORMS_DESCRIPTION . " WHERE forms_id = '" . $fInfo->forms_id . "' AND language_id = '" . $languages[$i]['id'] . "'"));
        $str1 .= '<tr><td>' . $languages[$i]['code'] . ': </td><td>' . tep_draw_input_field('forms_name[]', $f_name['forms_name']) . '</td></tr>';
        $str2 .= '<tr><td>' . $languages[$i]['code'] . ': </td><td>' . tep_draw_textarea_field('forms_confirmation_content[]', '', '28', '5', $f_name['forms_confirmation_content']) . '</td></tr>';
        $str3 .= '<tr><td>' . $languages[$i]['code'] . ': </td><td>' . tep_draw_textarea_field('forms_description[]', '', '28', '5', $f_name['forms_description']) . '</td></tr>';
        $str4 .= '<tr><td>' . $languages[$i]['code'] . ': </td><td>' . tep_draw_textarea_field('forms_blurb[]', '', '28', '5', $f_name['forms_blurb']) . '</td></tr>';
      }
      $str1 .= '</table>';
      $str2 .= '</table>';
      $str3 .= '</table>';
      $str4 .= '</table>';
      for ($i = 0; $i < 3; $i++) {
        $var = 'forms_type' . $i;        
        if ($fInfo->forms_type == $i) {          
          $$var = true;
        } else {
          $$var = false;
        }
        $var = 'send_post_data' . $i;
        if ($fInfo->send_post_data == $i) {          
          $$var = true;
        } else {
          $$var = false;
        }
        $var = 'enable_vvc' . $i;
        if ($fInfo->enable_vvc == $i) {          
          $$var = true;
        } else {
          $$var = false;
        }
      }           
      $contents[] = array( 'align' =>'left',  'text' => TEXT_INFO_TITLE_FORMS_NEW . '<br>' . $str1);
      $contents[] = array( 'align' =>'left',  'text' => '<b>' . TEXT_FORM_DESCRIPTION . '</b><br>'. $str3);
//      $contents[] = array( 'align' =>'left',  'text' => '<table><tr><td><b>' . TEXT_FORM_TYPE . '</b></td><td align="left">'. tep_draw_radio_field('forms_type', '0', $forms_type0) . '&nbsp;' . TEXT_FORM_TYPE_FORM . '</td></tr><tr><td>&nbsp;</td><td>' . tep_draw_radio_field('forms_type', '1', $forms_type1) . '&nbsp;' . TEXT_FORM_TYPE_SURVEY . '</td></tr><tr><td>&nbsp;</td><td>' . tep_draw_radio_field('forms_type', '2', $forms_type2) . '&nbsp;' . TEXT_FORM_TYPE_POLL . '</td></tr></table>');
      $contents[] = array( 'align' =>'left',  'text' => '<table><tr><td><b>' . TEXT_FORM_TYPE . '</b></td><td align="left">'. tep_draw_radio_field('forms_type', '0', $forms_type0) . '&nbsp;' . TEXT_FORM_TYPE_FORM . '</td></tr><tr><td>&nbsp;</td><td>' . tep_draw_radio_field('forms_type', '1', $forms_type1) . '&nbsp;' . TEXT_FORM_TYPE_SURVEY . '</td></tr></table>');
      $contents[] = array( 'align' =>'left',  'text' => '<b>' . TEXT_INFO_FORMS_POST_NAME . '</b><br>'. tep_draw_input_field('forms_post_name', $fInfo->forms_post_name) . '<br>');
      $contents[] = array( 'align' =>'left',  'text' => '<b>' . TEXT_INFO_FORMS_SEND_EMAIL_TO . '</b><br>'. tep_draw_input_field('send_email_to', $fInfo->send_email_to, 'size="30"') . '<br>' . TEXT_INFO_FORMS_SEND_EMAIL_TO_INTRO . '<br>');
      $contents[] = array('text' => '<b>' . TEXT_INFO_FORMS_IMAGE . '</b>' . tep_draw_file_field('forms_image', '15'));
      $contents[] = array( 'align' =>'left',  'text' => '<table><tr><td rowspan="2" valign="top"><b>' . TEXT_FORM_SEND_POST_DATA . '</b><br>' . TEXT_FORM_SEND_POST_DATA_INTRO . '</td><td align="left" valign="top">'. tep_draw_radio_field('send_post_data', '1', $send_post_data1) . '&nbsp;True</td></tr><tr><td align="left" valign="top">' . tep_draw_radio_field('send_post_data', '0', $send_post_data0) . '&nbsp;False</td></tr></table>');      
      $contents[] = array( 'align' =>'left',  'text' => '<table><tr><td rowspan="2" valign="top"><b>' . TEXT_FORM_ENABLE_VVC . '</b></td><td align="left" valign="top">'. tep_draw_radio_field('enable_vvc', '1', $enable_vvc1) . '&nbsp;True</td></tr><tr><td align="left" valign="top">' . tep_draw_radio_field('enable_vvc', '0', $enable_vvc0) . '&nbsp;False</td></tr></table>');
      $contents[] = array( 'align' =>'left',  'text' => '<b>' . TEXT_FORM_CONFIRMATION_CONTENT . '</b><br>'. $str2 . '<br>');
      $contents[] = array( 'align' =>'left',  'text' => '<b>' . TEXT_FORM_FORMS_BLURB . '</b><br>'. $str4 . '<br>');
      $contents[] = array( 'align' =>'left',  'text' => '<b>' . TEXT_INFO_TITLE_SORT_ORDER . '</b>' . tep_draw_input_field('sort_order', $fInfo->sort_order, 'size="3"'));
      if (tep_not_null($fInfo->forms_image)) {
        $contents[] = array( 'align' =>'left',  'text' => '<b>' . TEXT_INFO_DELETE_FORMS_IMAGE . '</b>' . tep_draw_checkbox_field('forms_image_delete', '1'));
      }
/*      
      $questions_all = tep_fss_get_questions();
      $questions = tep_fss_get_questions($fInfo->forms_id);
      $str = '<SELECT name="forms_questions[]" size="' . sizeof($questions_all) .'" multiple>';
      for ($i = 0, $n = sizeof($questions_all); $i < $n; $i++) {
        if (in_array($questions_all[$i], $questions)) {
          $str .= '<option value="'.$questions_all[$i]['id'].'" selected>'. $questions_all[$i]['label'] .'</option>';
        } else {
          $str .= '<option value="'.$questions_all[$i]['id'].'">'. $questions_all[$i]['label'] .'</option>';
        }
      }
      $str .= '</select>';
      $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_QUESTIONS . '</b><br>&nbsp;' . $str);
*/
      // RCI code start
      $cre_RCI->get('fssformsbuilder', 'editform'); 
      // RCI code eof
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></form>');
      break;
    case 'edit_system_form':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_FORMS . '</b>');
      $contents = array('form' => tep_draw_form('forms', FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action')) . 'action=save_system_form'));
      $str1 = '<table>';
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $f_name = tep_db_fetch_array(tep_db_query("SELECT forms_name, forms_confirmation_content, forms_description FROM " . TABLE_FSS_FORMS_DESCRIPTION . " WHERE forms_id = '" . $fInfo->forms_id . "' AND language_id = '" . $languages[$i]['id'] . "'"));
        $str1 .= '<tr><td>' . $languages[$i]['code'] . ': </td><td>' . tep_draw_textarea_field('forms_confirmation_content[]', '', '20', '3', $f_name['forms_confirmation_content']) . '</td></tr>';        
      }
      $str1 .= '</table>';
      $contents[] = array( 'align' =>'left',  'text' => '<b>' . TEXT_FORM_CONFIRMATION_CONTENT . '</b><br>'. $str1 . '<br>');    
/*      
      $questions_all = tep_fss_get_questions();
      $questions = tep_fss_get_questions($fInfo->forms_id);
      $str = '<SELECT name="forms_questions[]" size="' . sizeof($questions_all) .'" multiple>';
      for ($i = 0, $n = sizeof($questions_all); $i < $n; $i++) {
        if (in_array($questions_all[$i], $questions)) {
          $str .= '<option value="'.$questions_all[$i]['id'].'" selected>'. $questions_all[$i]['label'] .'</option>';
        } else {
          $str .= '<option value="'.$questions_all[$i]['id'].'">'. $questions_all[$i]['label'] .'</option>';
        }
      }
      $str .= '</select>';
      $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_QUESTIONS . '</b><br>&nbsp;' . $str);
*/
      // RCI code start
      $cre_RCI->get('fssformsbuilder', 'editsystemform'); 
      // RCI code eof
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></form>');
      break;
    case 'edit_categories':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_CATEGORIES . '</b>');
      $contents = array('form' => tep_draw_form('categories', FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action')) . 'action=save_categories'));
      $contents[] = array( 'align' =>'left',  'text' => '<table><tr><td>' . TEXT_INFO_TITLE_CATEGORIES_NEW . '</td><td>' . tep_draw_input_field('categories_name', $cInfo->fss_categories_name) . '</td></tr>');
      $contents[] = array( 'align' =>'left',  'text' => '<tr><td>' . TEXT_INFO_TITLE_SORT_ORDER . '</td><td>' . tep_draw_input_field('categories_sort_order', $cInfo->sort_order, 'size="3"') . '</td></tr></table>');
      
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></form>');
      break;
    case 'new_categories':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_CATEGORIES . '</b>');
      $contents = array('form' => tep_draw_form('categories', FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action')) . 'action=insert_categories'));
      $contents[] = array( 'align' =>'left',  'text' => '<table><tr><td>' . TEXT_INFO_TITLE_CATEGORIES_NEW . '</td><td>' . tep_draw_input_field('categories_name') . '</td></tr>' . tep_draw_hidden_field('cPath', $cPath));
      $contents[] = array( 'align' =>'left',  'text' => '<tr><td>' . TEXT_INFO_TITLE_SORT_ORDER . '</td><td>' . tep_draw_input_field('categories_sort_order', '', 'size="3"') . '</td></tr></table>');
      
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . ' <a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></form>');
      break;
    case 'delete_categories':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORIES . '</b>');
      $contents = array('form' => tep_draw_form('categories', FILENAME_FSS_FORMS_BUILDER, 'action=delete_categories_confirm&cPath=' . $cPath, 'post', 'onsubmit="return confirm_delete(this);"') . tep_draw_hidden_field('categories_id', $cInfo->fss_categories_id));
      $contents[] = array('text' => TEXT_DELETE_CATEGORIES_INTRO);
      $contents[] = array('text' => '<br><b>' . $cInfo->fss_categories_name . '</b>');
      if ($cInfo->childs_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
      if ($cInfo->forms_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_FORMS, $cInfo->forms_count));
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_confirm', '1') . '&nbsp;' . TEXT_INFO_CONFIRM_DELETION);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, 'cPath=' . $cPath . '&cID=' . $cInfo->fss_categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'move_categories':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_CATEGORIES . '</b>');
      $contents = array('form' => tep_draw_form('categories', FILENAME_FSS_FORMS_BUILDER, 'action=move_categories_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->fss_categories_id));
      $contents[] = array('text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->fss_categories_name));
      $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $cInfo->fss_categories_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_folder_tree('0'), $cInfo->fss_categories_id));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, 'cPath=' . $cPath . '&cID=' . $cInfo->fss_categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete_form':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_FORM . '</b>');
      $contents = array('form' => tep_draw_form('articles', FILENAME_FSS_FORMS_BUILDER, 'action=delete_form_confirm&cPath=' . $cPath, 'post', 'onsubmit="return confirm_delete(this);"') . tep_draw_hidden_field('forms_id', $fInfo->forms_id));
      $contents[] = array('text' => TEXT_DELETE_FORM_INTRO);
      $contents[] = array('text' => '<br><b>' . $fInfo->forms_name . '</b>');
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_confirm', '1') . '&nbsp;' . TEXT_INFO_CONFIRM_DELETION);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER,  'cPath=' . $cPath . '&fID=' . $fInfo->forms_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'move_form':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_FORM . '</b>');
      $contents = array('form' => tep_draw_form('forms', FILENAME_FSS_FORMS_BUILDER, 'action=move_form_confirm&cPath=' . $cPath) . tep_draw_hidden_field('forms_id', $fInfo->forms_id));
      $contents[] = array('text' => sprintf(TEXT_MOVE_FORMS_INTRO, $fInfo->forms_name));
      $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $fInfo->forms_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_folder_tree(), $cPath));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, 'cPath=' . $cPath . '&fID=' . $fInfo->forms_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'copy_to_form':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO_FORM . '</b>');
      $contents = array('form' => tep_draw_form('forms', FILENAME_FSS_FORMS_BUILDER, 'action=copy_to_form_confirm&cPath=' . $cPath . '&fID=' . $fInfo->forms_id) . tep_draw_hidden_field('forms_id', $fInfo->forms_id));
      $contents[] = array('text' => sprintf(TEXT_COPY_TO_FORMS_INTRO, $fInfo->forms_name));
      $contents[] = array('text' => '<br>' . sprintf(TEXT_COPY_TO, $fInfo->forms_name) . '<br>' . tep_draw_pull_down_menu('copy_to_category_id', tep_get_folder_tree(), $cPath));
      $contents[] = array('text' => '<br>' . TEXT_HOW_TO_COPY . '<br>' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br>' . tep_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE . '<br>&nbsp;&nbsp;&nbsp;' . tep_draw_radio_field('child_questions', '0', true) . ' ' . TEXT_INFO_NO_CHILD_QUESTIONS . '<br>&nbsp;&nbsp;&nbsp;' . tep_draw_radio_field('child_questions', '1') . ' ' . TEXT_INFO_COPY_CHILD_QUESTIONS . '<br>&nbsp;&nbsp;&nbsp;' . tep_draw_radio_field('child_questions', '2') . ' ' . TEXT_INFO_LINK_CHILD_QUESTIONS);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy_to.gif', IMAGE_COPY_TO) . ' <a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, 'cPath=' . $cPath . '&fID=' . $fInfo->forms_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($fInfo) && is_object($fInfo)) {
        $heading[] = array('align'=>'left', 'text' => '<b>' . sprintf(TEXT_INFORBOX_FORMS_HEADING, $fInfo->forms_name). '</b>');        
        $contents[] = array('align' => 'center', 'text' => ((int)$_GET['cPath'] == 1 ? '<a href="' . tep_href_link(FILENAME_FSS_REPORTS, tep_get_all_get_params(array('action', 'fID', 'cPath')) . 'cPath=' . $_GET['cPath'] . '&fID=' . $fInfo->forms_id) . '">' . tep_image_button('button_report.gif', IMAGE_REPORT) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('fID', 'action', 'search', 'cPath')) . 'cPath=' . $_GET['cPath'] . '&fID=' . $fInfo->forms_id . '&action=edit_system_form') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a><br>' : '&nbsp;<a href="' . tep_href_link(FILENAME_FSS_FORMS_POSTS_ADMIN, tep_get_all_get_params(array('fID', 'action', 'search', 'cPath')) . 'fID=' . $fInfo->forms_id) . '">' . tep_image_button('button_posts.gif', IMAGE_POSTS) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_FSS_REPORTS, tep_get_all_get_params(array('action', 'fID', 'cPath')) . 'cPath=' . $_GET['cPath'] . '&fID=' . $fInfo->forms_id) . '">' . tep_image_button('button_report.gif', IMAGE_REPORT) . '</a><br><a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('fID', 'action', 'cPath')) . 'cPath=' . $_GET['cPath'] . '&fID=' . $fInfo->forms_id . '&action=delete_form') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('fID', 'action', 'search', 'cPath')) . 'cPath=' . $_GET['cPath'] . '&fID=' . $fInfo->forms_id . '&action=move_form') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a><br><a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('fID', 'action', 'search', 'cPath')) . 'cPath=' . $_GET['cPath'] . '&fID=' . $fInfo->forms_id . '&action=edit_form') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('fID', 'action', 'cPath', 'search')) . 'cPath=' . $_GET['cPath'] . '&fID=' . $fInfo->forms_id . '&action=copy_to_form') . '">' . tep_image_button('button_copy_to.gif', IMAGE_COPY_TO) . '</a>') . '<br><a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('fID','action', 'cID', 'selected_box', 'search', 'cPath')) . 'cPath=' . $_GET['cPath'] . '&qPath=' . $fInfo->forms_id) . '">' . tep_image_button('button_manage_questions.gif', IMAGE_MANAGE_QUESTIONS) . '</a>');
        $questions = tep_fss_get_questions($fInfo->forms_id);
        $contents[] = array('text' => '<br>&nbsp;<b>' . TEXT_INFO_QUESTIONS . '</b>' . sizeof($questions));
        $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_LAST_MODIFIED . '</b>' . tep_date_short($fInfo->last_modified));
        $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_POSTS . '</b>' . tep_count_forms_posts($fInfo->forms_id));
      } else if (is_object($qInfo)) {
        $heading[] = array('text' => '<b>' . $qInfo->questions_label . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'qID', 'search')) . 'action=edit_question&qID=' . $qInfo->questions_id) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'qID')) . 'action=delete_question&qID=' . $qInfo->questions_id) . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a><br>' . (tep_is_special_question($qInfo->questions_variable) ? '' : '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'qID')) . 'action=move_question&qID=' . $qInfo->questions_id) . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a> <a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'qID')) . 'action=copy_to_question&qID=' . $qInfo->questions_id) . '">' . tep_image_button('button_copy_to.gif', IMAGE_COPY_TO) . '</a><br><a href="' . tep_href_link(FILENAME_FSS_VALUES_MANAGER, tep_get_all_get_params(array('action', 'qID')) . 'qID=' . $qInfo->questions_id) . '">' . tep_image_button('button_edit_question_value.gif', IMAGE_EDIT_QUESTIONS_VALUES) . '</a>') . ' <a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('action', 'qID')) . 'action=purge_question&qID=' . $qInfo->questions_id) . '">' . tep_image_button('button_purge.gif', IMAGE_PURGE) . '</a>');
        $contents[] = array('text' => '<br><b>' . TEXT_INFO_CREATE_DATE . '</b>' . $qInfo->date_added);
        $questions_forms = tep_get_questions_forms($qInfo->questions_id);
        $str = '';
        foreach ($questions_forms as $form) {
          $str .= $form['name'] . ', ';
        }
        $str = substr($str, 0, strlen($str) - 2);
        $contents[] = array('text' => '<br><b>' . TEXT_INFO_FORMS . '</b>' . $str);
      } elseif (is_object($cInfo) && tep_not_null($cInfo->fss_categories_id)) {
        $heading[] = array('align'=>'left', 'text' => '<b>' . sprintf(TEXT_INFORBOX_FOLDERS_HEADING, $cInfo->fss_categories_name). '</b>');        
        $contents[] = array('align' => 'center', 'text' => ($cInfo->fss_categories_id == '1' ? '' : '<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('cID', 'action', 'search', 'cPath')) . 'cPath=' . $_GET['cPath'] . '&cID=' . $cInfo->fss_categories_id . '&action=edit_categories') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('cID', 'action', 'search', 'cPath')) . 'cPath=' . $_GET['cPath'] . '&cID=' . $cInfo->fss_categories_id . '&action=move_categories') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('cID', 'action', 'search', 'cPath')) . 'cPath=' . $_GET['cPath'] . '&cID=' . $cInfo->fss_categories_id . '&action=delete_categories') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>'));
        $contents[] = array('text' => '<br><b>' . TEXT_INFO_CREATE_DATE . '</b>' . $cInfo->date_added);
        $contents[] = array('text' => '<b>' . TEXT_INFO_LAST_MODIFIED . '</b>' . $cInfo->last_modified);
        $contents[] = array('text' => '<b>' . TEXT_INFO_SUB_FOLDERS . '</b>' . tep_childs_in_categories_count($cInfo->fss_categories_id));
        $contents[] = array('text' => '<b>' . TEXT_INFO_FORMS . '</b>' . tep_forms_in_categories_count($cInfo->fss_categories_id));
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";    
  }
?>
          </tr>
        </table></td>
      </tr>
      <?php
      // RCI code start
      $cre_RCI->get('fssformsbuilder', 'bottom'); 
      $cre_RCI->get('global', 'bottom');                                        
      // RCI code eof
      ?>
    </table></td>
    <!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>