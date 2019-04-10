<?php
/*
  $Id: email_subjects.php, v1 07/11/2005

  Copyright (c) 2005 PassionSeed
  http://PassionSeed.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        if (isset($_GET['gID'])) $email_subjects_id = tep_db_prepare_input($_GET['gID']);
        $email_subjects_name = tep_db_prepare_input($_POST['email_subjects_name']);
        $email_subjects_category = tep_db_prepare_input($_POST['email_subjects_category']);

        $sql_data_array = array('email_subjects_name' => $email_subjects_name,
                                'email_subjects_category' => $email_subjects_category);

        if ($action == 'insert') {
          $insert_sql_data = array('date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_EMAIL_SUBJECTS, $sql_data_array);
          $email_subjects_id = tep_db_insert_id();
        } elseif ($action == 'save') {
          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          tep_db_perform(TABLE_EMAIL_SUBJECTS, $sql_data_array, 'update', "email_subjects_id = '" . (int)$email_subjects_id . "'");
        }


        if (USE_CACHE == 'true') {
          tep_reset_cache_block('email_subjects');
        }

        tep_redirect(tep_href_link(FILENAME_EMAIL_SUBJECTS, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'gID=' . $email_subjects_id));
        break;
      case 'deleteconfirm':
        $email_subjects_id = tep_db_prepare_input($_GET['gID']);

        tep_db_query("delete from " . TABLE_EMAIL_SUBJECTS . " where email_subjects_id = '" . (int)$email_subjects_id . "'");

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('email_subjects');
        }

        tep_redirect(tep_href_link(FILENAME_EMAIL_SUBJECTS, 'page=' . $_GET['page']));
        break;
    }
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script type="text/javascript" src="includes/general.js"></script>
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_EMAIL_SUBJECTS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_EMAIL_SUBJECTS_CATEGORY; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $email_subjects_query_raw = "select email_subjects_id, email_subjects_name, email_subjects_category, date_added, last_modified from " . TABLE_EMAIL_SUBJECTS . " order by email_subjects_name";
  $email_subjects_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $email_subjects_query_raw, $email_subjects_query_numrows);
  $email_subjects_query = tep_db_query($email_subjects_query_raw);
  while ($email_subjects = tep_db_fetch_array($email_subjects_query)) {
    if ((!isset($_GET['gID']) || (isset($_GET['gID']) && ($_GET['gID'] == $email_subjects['email_subjects_id']))) && !isset($mInfo) && (substr($action, 0, 3) != 'new')) {

      $mInfo_array = array_merge($email_subjects);
      $mInfo = new objectInfo($mInfo_array);
    }

    if (isset($mInfo) && is_object($mInfo) && ($email_subjects['email_subjects_id'] == $mInfo->email_subjects_id)) {
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_EMAIL_SUBJECTS, 'page=' . $_GET['page'] . '&gID=' . $email_subjects['email_subjects_id'] . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_EMAIL_SUBJECTS, 'page=' . $_GET['page'] . '&gID=' . $email_subjects['email_subjects_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $email_subjects['email_subjects_name']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $email_subjects['email_subjects_category']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($mInfo) && is_object($mInfo) && ($email_subjects['email_subjects_id'] == $mInfo->email_subjects_id)) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png'); } else { echo '<a href="' . tep_href_link(FILENAME_EMAIL_SUBJECTS, 'page=' . $_GET['page'] . '&gID=' . $email_subjects['email_subjects_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>            </table>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $email_subjects_split->display_count($email_subjects_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_EMAIL_SUBJECTS); ?></td>
                    <td class="smallText" align="right"><?php echo $email_subjects_split->display_links($email_subjects_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
  if (empty($action)) {
?>
              <tr>
                <td align="right" colspan="3" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_EMAIL_SUBJECTS, 'page=' . $_GET['page'] . '&gID=' . $mInfo->email_subjects_id . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?>&nbsp;</td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_EMAIL_SUBJECT . '</b>');
      $email_subjects_category = (isset($email_subjects_category) ? $email_subjects_category : '');
      $contents = array('form' => tep_draw_form('email_subjects', FILENAME_EMAIL_SUBJECTS, 'action=insert', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_NEW_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_EMAIL_SUBJECTS_NAME . '<br>' . tep_draw_input_field('email_subjects_name'));
      $contents[] = array('text' => '<br>' . TEXT_EMAIL_SUBJECTS_CATEGORY . '<br>' . tep_draw_input_field('email_subjects_category', $email_subjects_category) . '<br>' . TEXT_EMAIL_SUBJECTS_CATEGORY_CHOICE);

      $email_subject_inputs_string = '';
      $languages = tep_get_languages();

      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_EMAIL_SUBJECTS, 'page=' . $_GET['page'] . '&gID=' . $_GET['gID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_save.gif', IMAGE_SAVE));
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_EMAIL_SUBJECT . '</b>');

      $contents = array('form' => tep_draw_form('email_subjects', FILENAME_EMAIL_SUBJECTS, 'page=' . $_GET['page'] . '&gID=' . $mInfo->email_subjects_id . '&action=save', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_EMAIL_SUBJECTS_NAME . '<br>' . tep_draw_input_field('email_subjects_name', $mInfo->email_subjects_name));
      $contents[] = array('text' => '<br>' . TEXT_EMAIL_SUBJECTS_CATEGORY . '<br>' . tep_draw_input_field('email_subjects_category', $mInfo->email_subjects_category) . '<br>' . TEXT_EMAIL_SUBJECTS_CATEGORY_CHOICE);

      $email_subject_inputs_string = '';
      $languages = tep_get_languages();

      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_EMAIL_SUBJECTS, 'page=' . $_GET['page'] . '&gID=' . $mInfo->email_subjects_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'. tep_image_submit('button_save.gif', IMAGE_SAVE));
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_EMAIL_SUBJECT . '</b>');

      $contents = array('form' => tep_draw_form('email_subjects', FILENAME_EMAIL_SUBJECTS, 'page=' . $_GET['page'] . '&gID=' . $mInfo->email_subjects_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $mInfo->email_subjects_name . '</b>');

      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_EMAIL_SUBJECTS, 'page=' . $_GET['page'] . '&gID=' . $mInfo->email_subjects_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_delete.gif', IMAGE_DELETE));
      break;
    default:
      if (isset($mInfo) && is_object($mInfo)) {
        $heading[] = array('text' => '<b>' . $mInfo->email_subjects_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_EMAIL_SUBJECTS, 'page=' . $_GET['page'] . '&gID=' . $mInfo->email_subjects_id . '&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a><a href="' . tep_href_link(FILENAME_EMAIL_SUBJECTS, 'page=' . $_GET['page'] . '&gID=' . $mInfo->email_subjects_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' <b>' . tep_date_short($mInfo->date_added) . '</b>');
        if (tep_not_null($mInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' <b>' . tep_date_short($mInfo->last_modified). '</b>');
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
