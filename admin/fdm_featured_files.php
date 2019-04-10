<?php
/*
  $Id: fdm_featured_files.php,v 1.0.0.0 2008/01/24 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  require(DIR_WS_FUNCTIONS . FILENAME_FDM_FUNCTIONS);
  $is_62 = (defined('PROJECT_VERSION') && ereg('6.2', PROJECT_VERSION)) ? true : false;
  
  function tep_set_featured_status($featured_id, $status) {
    if ($status == '1') {
      return tep_db_query("update " . TABLE_FEATURED_FILES . " set status = '1', expires_date = NULL, date_status_change = NULL where featured_id = '" . $featured_id . "'");
    } elseif ($status == '0') {
      return tep_db_query("update " . TABLE_FEATURED_FILES . " set status = '0', date_status_change = now() where featured_id = '" . $featured_id . "'");
    } else {
      return -1;
    }
  }

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  switch ($_GET['action']) {
    case 'setflag':
      tep_set_featured_status($_GET['id'], $_GET['flag']);
      tep_redirect(tep_href_link(FILENAME_FEATURED_FILES, '', 'NONSSL'));
      break;
    case 'insert':
      $expires_date = '';
      if ($_POST['day'] && $_POST['month'] && $_POST['year']) {
        $expires_date = $_POST['year'];
        $expires_date .= (strlen($_POST['month']) == 1) ? '0' . $_POST['month'] : $_POST['month'];
        $expires_date .= (strlen($_POST['day']) == 1) ? '0' . $_POST['day'] : $_POST['day'];
      }

      tep_db_query("insert into " . TABLE_FEATURED_FILES . " (files_id, featured_date_added, expires_date, status) values ('" . $_POST['files_id'] . "', now(), '" . $expires_date . "', '1')");
      tep_redirect(tep_href_link(FILENAME_FEATURED_FILES, 'page=' . $_GET['page']));
      break;
    case 'update':
      $expires_date = '';
      if ($_POST['day'] && $_POST['month'] && $_POST['year']) {
        $expires_date = $_POST['year'];
        $expires_date .= (strlen($_POST['month']) == 1) ? '0' . $_POST['month'] : $_POST['month'];
        $expires_date .= (strlen($_POST['day']) == 1) ? '0' . $_POST['day'] : $_POST['day'];
      }

      tep_db_query("update " . TABLE_FEATURED_FILES . " set featured_last_modified = now(), expires_date = '" . $expires_date . "' where featured_id = '" . $_POST['featured_id'] . "'");
      tep_redirect(tep_href_link(FILENAME_FEATURED_FILES, 'page=' . $_GET['page'] . '&sID=' . $featured_id));
      break;
    case 'deleteconfirm':
      $featured_id = tep_db_prepare_input($_GET['sID']);

      tep_db_query("delete from " . TABLE_FEATURED_FILES . " where featured_id = '" . tep_db_input($featured_id) . "'");

      tep_redirect(tep_href_link(FILENAME_FEATURED_FILES, 'page=' . $_GET['page']));
      break;
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

echo '<script language="javascript" src="includes/general.js"></script>' . "\n";

  if ( ($_GET['action'] == 'new') || ($_GET['action'] == 'edit') ) {
      echo '<link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">' . "\n";
      echo '<script language="JavaScript" src="includes/javascript/calendarcode.js"></script>' . "\n";
  }
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<div id="popupcalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<?php $padding = ($is_62 == true) ? 2 : 0; ?>
<table border="0" width="100%" cellspacing="<?php echo $padding; ?>" cellpadding="<?php echo $padding; ?>" class="body-table">
  <tr>
    <!-- left_navigation //-->
    <?php 
    if ($is_62 == true) echo '<td width="' . BOX_WIDTH . '" valign="top"><table border="0" width="' . BOX_WIDTH . '" cellspacing="1" cellpadding="1" class="columnLeft">' . "\n";
    require(DIR_WS_INCLUDES . 'column_left.php'); 
    if ($is_62 == true) echo '</table></td>' . "\n";
    ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if ( ($_GET['action'] == 'new') || ($_GET['action'] == 'edit') ) {
    $form_action = 'insert';
    if ( ($_GET['action'] == 'edit') && ($_GET['sID']) ) {
    $form_action = 'update';

      $file_query = tep_db_query("select p.files_id, pd.files_descriptive_name, s.expires_date from " . TABLE_LIBRARY_FILES . " p, " . TABLE_LIBRARY_FILES_DESCRIPTION . " pd, " . TABLE_FEATURED_FILES . " s where p.files_id = pd.files_id and pd.language_id = '" . $languages_id . "' and p.files_id = s.files_id and s.featured_id = '" . $_GET['sID'] . "' order by pd.files_descriptive_name");
      $file = tep_db_fetch_array($file_query);

      $sInfo = new objectInfo($file);
    } else {
      $sInfo = new objectInfo(array());

// create an array of featured products, which will be excluded from the pull down menu of products
// (when creating a new featured product)
      $featured_array = array();
      $featured_query = tep_db_query("select p.files_id from " . TABLE_LIBRARY_FILES . " p, " . TABLE_FEATURED_FILES . " s where s.files_id = p.files_id");
      while ($featured = tep_db_fetch_array($featured_query)) {
        $featured_array[] = $featured['files_id'];
      }
    }
?>
      <tr><form name="new_feature" <?php echo 'action="' . tep_href_link(FILENAME_FEATURED_FILES, tep_get_all_get_params(array('action', 'info', 'sID')) . 'action=' . $form_action, 'NONSSL') . '"'; ?> method="post"><?php if ($form_action == 'update') echo tep_draw_hidden_field('featured_id', $_GET['sID']); ?>
        <td><br><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_FEATURED_PRODUCT; ?>&nbsp;</td>
            <td class="main"><?php echo ($sInfo->files_descriptive_name) ? $sInfo->files_descriptive_name : tep_draw_files_pull_down('files_id', 'style="font-size:10px"', $featured_array); echo tep_draw_hidden_field('products_price', $sInfo->products_price); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_FEATURED_EXPIRES_DATE; ?>&nbsp;</td>
            <td class="main"><?php echo tep_draw_input_field('day', substr($sInfo->expires_date, 8, 2), 'size="2" maxlength="2" class="cal-TextBox"') . tep_draw_input_field('month', substr($sInfo->expires_date, 5, 2), 'size="2" maxlength="2" class="cal-TextBox"') . tep_draw_input_field('year', substr($sInfo->expires_date, 0, 4), 'size="4" maxlength="4" class="cal-TextBox"'); ?><a class="so-BtnLink" href="javascript:calClick();return false;" onmouseover="calSwapImg('BTN_date', 'img_Date_OVER',true);" onmouseout="calSwapImg('BTN_date', 'img_Date_UP',true);" onclick="calSwapImg('BTN_date', 'img_Date_DOWN');showCalendar('new_feature','dteWhen','BTN_date');return false;"><?php echo tep_image(DIR_WS_IMAGES . 'cal_date_up.gif', 'Calendar', '22', '17', 'align="middle" name="BTN_date"'); ?></a></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" align="right" valign="top"><br><?php echo (($form_action == 'insert') ? tep_image_submit('button_insert.gif', IMAGE_INSERT) : tep_image_submit('button_update.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FEATURED_FILES, 'page=' . $_GET['page'] . '&sID=' . $_GET['sID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="right">&nbsp;</td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $featured_query_raw = "select p.files_id, pd.files_descriptive_name, s.featured_id, s.featured_date_added, s.featured_last_modified, s.expires_date, s.date_status_change, s.status from " . TABLE_LIBRARY_FILES . " p, " . TABLE_FEATURED_FILES . " s, " . TABLE_LIBRARY_FILES_DESCRIPTION . " pd where p.files_id = pd.files_id and pd.language_id = '" . $languages_id . "' and p.files_id = s.files_id order by pd.files_descriptive_name";
    $featured_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $featured_query_raw, $featured_query_numrows);
    $featured_query = tep_db_query($featured_query_raw);
    while ($featured = tep_db_fetch_array($featured_query)) {
      if ( ((!$_GET['sID']) || ($_GET['sID'] == $featured['featured_id'])) && (!$sInfo) ) {

        $files_query = tep_db_query("select ffi.icon_large from " . TABLE_LIBRARY_FILES . " lf, " . TABLE_FILE_ICONS . " ffi where lf.files_icon = ffi.icon_id and lf.files_id = '" . $featured['files_id'] . "'");
        $files = tep_db_fetch_array($files_query);
        $sInfo_array = array_merge($featured, $files);
        $sInfo = new objectInfo($sInfo_array);
      }

      if ( (is_object($sInfo)) && ($featured['featured_id'] == $sInfo->featured_id) ) {
        echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_FEATURED_FILES, 'page=' . $_GET['page'] . '&sID=' . $sInfo->featured_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_FEATURED_FILES, 'page=' . $_GET['page'] . '&sID=' . $featured['featured_id']) . '\'">' . "\n";
      }
?>
                <td  class="dataTableContent"><?php echo $featured['files_descriptive_name']; ?></td>
                <td  class="dataTableContent" align="right">&nbsp;</td>
                <td  class="dataTableContent" align="right">
<?php
      if ($featured['status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FEATURED_FILES, 'action=setflag&flag=0&id=' . $featured['featured_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_FEATURED_FILES, 'action=setflag&flag=1&id=' . $featured['featured_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($sInfo)) && ($featured['featured_id'] == $sInfo->featured_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_FEATURED_FILES, 'page=' . $_GET['page'] . '&sID=' . $featured['featured_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
      </tr>
<?php
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellpadding="0"cellspacing="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $featured_split->display_count($featured_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_FEATURED); ?></td>
                    <td class="smallText" align="right"><?php echo $featured_split->display_links($featured_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
  if (!$_GET['action']) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_FEATURED_FILES, 'page=' . $_GET['page'] . '&action=new') . '">' . tep_image_button('button_new_file.gif', IMAGE_NEW_FILE) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($_GET['action']) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_FEATURED . '</b>');

      $contents = array('form' => tep_draw_form('featured', FILENAME_FEATURED_FILES, 'page=' . $_GET['page'] . '&sID=' . $sInfo->featured_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $sInfo->files_descriptive_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_FEATURED_FILES, 'page=' . $_GET['page'] . '&sID=' . $sInfo->featured_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($sInfo)) {
        $heading[] = array('text' => '<b>' . $sInfo->files_descriptive_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_FEATURED_FILES, 'page=' . $_GET['page'] . '&sID=' . $sInfo->featured_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_FEATURED_FILES, 'page=' . $_GET['page'] . '&sID=' . $sInfo->featured_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($sInfo->featured_date_added));
        $contents[] = array('text' => '' . TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($sInfo->featured_last_modified));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_info_image('file_icons/' . $sInfo->icon_large, $sInfo->files_descriptive_name, FDM_LARGE_ICON_IMAGE_WIDTH, FDM_LARGE_ICON_IMAGE_HEIGHT));

        $contents[] = array('text' => '<br>' . TEXT_INFO_EXPIRES_DATE . ' <b>' . tep_date_short($sInfo->expires_date) . '</b>');
        $contents[] = array('text' => '' . TEXT_INFO_STATUS_CHANGE . ' ' . tep_date_short($sInfo->date_status_change));
      }
      break;
  }
  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
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
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>