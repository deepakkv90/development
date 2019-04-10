<?php
/*
  $Id: orders_hold_list.php,v 1.1.1.1 2007/05/08 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require('includes/languages/english/orders_hold_list.php');

  switch ($_GET['action']) {
    case 'insert':
    case 'save':
      $holdlist_id = tep_db_prepare_input($_GET['bID']);
      $holdlist_email = tep_db_prepare_input($_POST['holdlist_email']);
      $sql_data_array = array('holdlist_email' => $holdlist_email);
      if ($_GET['action'] == 'insert') {
        $insert_sql_data = array('date_added' => 'now()');
        $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
        tep_db_perform(TABLE_ORDERS_HOLD_LIST, $sql_data_array);
        $holdlist_id = tep_db_insert_id();
      } elseif ($_GET['action'] == 'save') {
        $update_sql_data = array('last_modified' => 'now()');
        $sql_data_array = array_merge($sql_data_array, $update_sql_data);
        tep_db_perform(TABLE_ORDERS_HOLD_LIST, $sql_data_array, 'update', "holdlist_id = '" . tep_db_input($holdlist_id) . "'");
      }
      tep_redirect(tep_href_link(FILENAME_ORDERS_HOLD_LIST, 'page=' . $_GET['page'] . '&bID=' . $holdlist_id));
      break;

    case 'autoinsert':
      $holdlist_email = tep_db_prepare_input($_GET['email']);
      $sql_data_array = array('holdlist_email' => $holdlist_email);
      tep_db_perform(TABLE_ORDERS_HOLD_LIST, $sql_data_array);
      $holdlist_id = tep_db_insert_id();
      tep_redirect(tep_href_link(FILENAME_ORDERS, 'page=1&oID=' . $_GET['oID'] . '&action=edit'));
      break;

    case 'autodelete':
      $holdlist_email = tep_db_prepare_input($_GET['email']);
      tep_db_query("delete from " . TABLE_ORDERS_HOLD_LIST . " where holdlist_email = '" . tep_db_input($holdlist_email) . "'");
      tep_redirect(tep_href_link(FILENAME_ORDERS, 'page=1&oID=' . $_GET['oID'] . '&action=edit'));
      break;

    case 'deleteconfirm':
      $holdlist_id = tep_db_prepare_input($_GET['bID']);
      tep_db_query("delete from " . TABLE_ORDERS_HOLD_LIST . " where holdlist_id = '" . tep_db_input($holdlist_id) . "'");
      tep_redirect(tep_href_link(FILENAME_ORDERS_HOLD_LIST, 'page=' . $_GET['page']));
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
?>
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
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
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_HOLDLIST_EMAIL; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <?php
              $holdlist_query_raw = "select holdlist_id, holdlist_email, date_added, last_modified from " . TABLE_ORDERS_HOLD_LIST . " order by date_added DESC";
              $holdlist_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $holdlist_query_raw, $holdlist_query_numrows);
              $holdlist_query = tep_db_query($holdlist_query_raw);
              while ($holdlist = tep_db_fetch_array($holdlist_query)) {
                if (((!$_GET['bID']) || (@$_GET['bID'] == $holdlist['holdlist_id'])) && (!$bInfo) && (substr($_GET['action'], 0, 3) != 'new')) {
                  $holdlist_numbers_query = tep_db_query("select count(*) as holdlist_count from " . TABLE_ORDERS_HOLD_LIST . " where holdlist_id = '" . $holdlist['holdlist_id'] . "'");
                  $holdlist_numbers = tep_db_fetch_array($holdlist_numbers_query);
                  $bInfo_array = array_merge($holdlist, $holdlist_numbers);
                  $bInfo = new objectInfo($bInfo_array);
                }
                if ( (is_object($bInfo)) && ($holdlist['holdlist_id'] == $bInfo->holdlist_id) ) {
                  echo '<tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS_HOLD_LIST, 'page=' . $_GET['page'] . '&bID=' . $holdlist['holdlist_id'] . '&action=edit') . '\'">' . "\n";
                } else {
                  echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS_HOLD_LIST, 'page=' . $_GET['page'] . '&bID=' . $holdlist['holdlist_id']) . '\'">' . "\n";
                }
                ?>
                <td class="dataTableContent"><?php echo $holdlist['holdlist_email']; ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($bInfo)) && ($holdlist['holdlist_id'] == $bInfo->holdlist_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_ORDERS_HOLD_LIST, 'page=' . $_GET['page'] . '&bID=' . $holdlist['holdlist_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
              <?php
              }
              ?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $holdlist_split->display_count($holdlist_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_HOLDLIST_ENTRYS); ?></td>
                    <td class="smallText" align="right"><?php echo $holdlist_split->display_links($holdlist_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
              <?php
              if ($_GET['action'] != 'new') {
                ?>
                <tr>
                  <td align="right" colspan="2" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS_HOLD_LIST, 'page=' . $_GET['page'] . '&bID=' . $bInfo->holdlist_id . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
                </tr>
                <?php
              }
              ?>
            </table></td>
            <?php
            $heading = array();
            $contents = array();
            switch ($_GET['action']) {

            case 'new':
              $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_HOLDLIST_ENTRY . '</b>');
              $contents = array('form' => tep_draw_form('holdlisted', FILENAME_ORDERS_HOLD_LIST, 'action=insert', 'post', 'enctype="multipart/form-data"'));
              $contents[] = array('text' => TEXT_NEW_INTRO);
              $contents[] = array('text' => '<br>' . TEXT_HOLDLIST_ENTRY_EMAIL . '<br>' . tep_draw_input_field('holdlist_email'));
              $holdlist_inputs_string = '';
              $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_ORDERS_HOLD_LIST, 'page=' . $_GET['page'] . '&bID=' . $_GET['bID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
              break;

            case 'edit':
              $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_HOLDLIST_ENTRY . '</b>');
              $contents = array('form' => tep_draw_form('holdlisted', FILENAME_ORDERS_HOLD_LIST, 'page=' . $_GET['page'] . '&bID=' . $bInfo->holdlist_id . '&action=save', 'post', 'enctype="multipart/form-data"'));
              $contents[] = array('text' => TEXT_EDIT_INTRO);
              $contents[] = array('text' => '<br>' . TEXT_HOLDLIST_ENTRY_EMAIL . '<br>' . tep_draw_input_field('holdlist_email', $bInfo->holdlist_email));
              $holdlist_inputs_string = '';
              $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_ORDERS_HOLD_LIST, 'page=' . $_GET['page'] . '&bID=' . $mInfo->holdlist_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
              break;

            case 'delete':
              $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_HOLDLIST_ENTRY . '</b>');
              $contents = array('form' => tep_draw_form('holdlisted', FILENAME_ORDERS_HOLD_LIST, 'page=' . $_GET['page'] . '&bID=' . $bInfo->holdlist_id . '&action=deleteconfirm'));
              $contents[] = array('text' => TEXT_DELETE_INTRO);
              $contents[] = array('text' => '<br><b>' . $bInfo->holdlist_email . '</b>');
              $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_ORDERS_HOLD_LIST, 'page=' . $_GET['page'] . '&bID=' . $bInfo->holdlist_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
              break;

            default:
              if (is_object($bInfo)) {
                $heading[] = array('text' => '<b>' . $bInfo->holdlist_email . '</b>');
                $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS_HOLD_LIST, 'page=' . $_GET['page'] . '&bID=' . $bInfo->holdlist_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_HOLD_LIST, 'page=' . $_GET['page'] . '&bID=' . $bInfo->holdlist_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
                $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($bInfo->date_added));
                if (tep_not_null($bInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($bInfo->last_modified));
              }
              break;
            }
            if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
              echo '<td width="25%" valign="top">' . "\n";
              $box = new box;
              echo $box->infoBox($heading, $contents);
              echo '</td>' . "\n";
            }
            ?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>