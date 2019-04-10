<?php
/*
  $Id: fdm_file_icons.php,v 1.0.0.0 2006/10/12 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  $is_62 = (defined('PROJECT_VERSION') && ereg('6.2', PROJECT_VERSION)) ? true : false;
  $languages = tep_get_languages();
  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
        if ($small_file = new upload('file_icon_upload_small', DIR_FS_CATALOG . 'images/file_icons/')) {
          $small_file_name = $small_file->filename;
          if ($small_file_name == '') {
            $icon_small = $_POST['icon_small'];
          } else {
            $icon_small = $small_file_name;
          }
          }
        if ($large_file = new upload('file_icon_upload_large', DIR_FS_CATALOG . 'images/file_icons/')) {
          $large_file_name = $large_file->filename;
          if ($large_file_name == '') {
            $icon_large = $_POST['icon_large'];
          } else {
            $icon_large = $large_file_name;
          }
        }
        tep_db_query("insert into " . TABLE_FILE_ICONS . " (file_ext, icon_small, icon_large) values ('" . $_POST['file_ext'] . "', '" . $icon_small . "', '" . $icon_large . "')");
        tep_redirect(tep_href_link(FILENAME_FILE_ICONS));
        break;

      case 'save':
        if ($small_file = new upload('file_icon_upload_small', DIR_FS_CATALOG . 'images/file_icons/')) {
          $small_file_name = $small_file->filename;
          if ($small_file_name == '') {
            $icon_small = $_POST['icon_small'];
          } else {
            $icon_small = $small_file_name;
          }
        }
        if ($large_file = new upload('file_icon_upload_large', DIR_FS_CATALOG . 'images/file_icons/')) {
          $large_file_name = $large_file->filename;
           if ($large_file_name == '') {
             $icon_large = $_POST['icon_large'];
           } else {
             $icon_large = $large_file_name;
           }
         }
         tep_db_query("update " . TABLE_FILE_ICONS . " set file_ext = '" . $_POST['file_ext'] . "', icon_small = '" . $icon_small . "', icon_large = '" . $icon_large . "' where icon_id = '" . $_GET['iID'] . "'");
         tep_redirect(tep_href_link(FILENAME_FILE_ICONS, 'page=' . $_GET['page'] . '&iID=' . $_GET['iID']));
         break;

      case 'deleteconfirm':
        tep_db_query("delete from " . TABLE_FILE_ICONS . " where icon_id = '" . $_GET['iID'] . "'");
        tep_redirect(tep_href_link(FILENAME_FILE_ICONS, 'page=' . $_GET['page']));
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
<script language="javascript">
<!--
function set_Img_small() {
  var w = document.file_icon.icon_small.selectedIndex;
  document.file_icon.icon_image_small.src = "../images/file_icons/" + document.file_icon.icon_small.options[w].text;
  if (document.file_icon.icon_small.options[w].text != "<?php echo TEXT_FILE_ICON_SELECT;?>") {
    document.file_icon.file_icon_upload_small.value = '';
  }
}
function set_Img_large() {
  var w = document.file_icon.icon_large.selectedIndex;
  document.file_icon.icon_image_large.src = "../images/file_icons/" + document.file_icon.icon_large.options[w].text;
  if (document.file_icon.icon_large.options[w].text != "<?php echo TEXT_FILE_ICON_SELECT;?>") {
    document.file_icon.file_icon_upload_large.value = '';
  }
}
-->
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo HEADING_TITLE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <?php
              $fileicon_query_raw = "select * from " . TABLE_FILE_ICONS . " order by file_ext";
              $fileicon_split = new splitPageResults($_GET['page'], FDMS_MAX_DISPLAY_SEARCH_RESULTS, $fileicon_query_raw, $fileicon_query_numrows);
              $fileicon_query = tep_db_query($fileicon_query_raw);
              while ($fileicon = tep_db_fetch_array($fileicon_query)) {
                if ((!isset($_GET['iID']) || (isset($_GET['iID']) && ($_GET['iID'] == $fileicon['icon_id']))) && !isset($iInfo) && (substr($action, 0, 3) != 'new')) {
                  $iInfo = new objectInfo($fileicon);
                }
                if (isset($iInfo) && is_object($iInfo) && ($fileicon['icon_id'] == $iInfo->icon_id)) {
                  echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_FILE_ICONS, 'page=' . $_GET['page'] . '&iID=' . $iInfo->icon_id . '&action=edit') . '\'">' . "\n";
                } else {
                  echo'<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_FILE_ICONS, 'page=' . $_GET['page'] . '&iID=' . $fileicon['icon_id']) . '\'">' . "\n";
                }
                ?>
                <td class="dataTableContent"><?php echo tep_image('../images/file_icons/' . $fileicon['icon_small']) . '&nbsp;&nbsp;' . $fileicon['file_ext']; ?></td>
                <td class="dataTableContent" align="right">
                  <?php
                    if (isset($iInfo) && is_object($iInfo) && ($fileicon['icon_id'] == $iInfo->icon_id)) { 
                      echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', '');
                      } else { 
                      echo '<a href="' . tep_href_link(FILENAME_FILE_ICONS, 'page=' . $_GET['page'] . '&iID=' . $fileicon['icon_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';
                    } 
                    ?>&nbsp;</td>
                </tr>
                <?php
                $last_product_id = $fileicon['icon_id'];
              }
              ?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $fileicon_split->display_count($fileicon_query_numrows, FDMS_MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_FILE_ICON); ?></td>
                    <td class="smallText" align="right"><?php echo $fileicon_split->display_links($fileicon_query_numrows, FDMS_MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                  <?php
                  if (empty($action)) {
                    ?>
                    <tr>
                      <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_FILE_ICONS, 'page=' . $_GET['page'] . '&action=new&last_id=' . $last_product_id ) . '">' . tep_image_button('button_new_file_icon.gif', IMAGE_NEW_FILE_ICON) . '</a>'; ?></td>
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
  
            switch ($action) {
              case 'new':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_FILE_ICON . '</b>');
                $contents = array('form' => tep_draw_form('file_icon', FILENAME_FILE_ICONS, 'page=' . $_GET['page'] . '&action=insert', 'post', 'enctype="multipart/form-data"'));
                $contents[] = array('text' => '<table><tr><td class="smallText">' . TEXT_FILE_ICON_EXT . '</td><td>' . tep_draw_input_field('file_ext') . '</td></tr>');
                if ($handle = opendir(DIR_FS_CATALOG . 'images/file_icons/')) {
                  $file_icons[] = array('id' => '', 'text' => TEXT_FILE_ICON_SELECT);
                  while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != ".." && (substr($file, -4, 4) == ".gif" || substr($file, -4, 4) == ".jpg")) {
                      $file_icons[] = array('id' => $file, 'text' => $file);
                    }
                  }
                  closedir($handle);
                }
                $contents[] = array('text' =>  '<tr><td class="smallText">' . TEXT_FILE_ICON_SMALL . '</td><td>' . tep_draw_pull_down_menu('icon_small', $file_icons, '', 'onChange="javascript:set_Img_small()"') . '</td></tr>');
                $contents[] = array('text' =>  '<tr><td class="smallText">' . TEXT_FILE_ICON_SMALL_IMAGE . '</td><td>' . tep_image('../images/file_icons/' . $file_icons[0]['text'], '', '', '', 'id="icon_image_small"') . '</td></tr>');
                $contents[] = array('text' =>  '<tr><td class="smallText">' . TEXT_FILE_ICON_LARGE . '</td><td>' . tep_draw_pull_down_menu('icon_large', $file_icons, '', 'onChange="javascript:set_Img_large()"') . '</td></tr>');
                $contents[] = array('text' =>  '<tr><td class="smallText">' . TEXT_FILE_ICON_LARGE_IMAGE . '</td><td>' . tep_image('../images/file_icons/' . $file_icons[0]['text'], '', '', '', 'id="icon_image_large"') . '</td></tr>');
                $contents[] = array('text' =>  '<tr><td class="smallText">' . TEXT_FILE_ICON_UPLOAD_SMALL . '</td><td>' . tep_draw_file_field('file_icon_upload_small') . '</td></tr>');
                $contents[] = array('text' =>  '<tr><td class="smallText">' . TEXT_FILE_ICON_UPLOAD_LARGE . '</td><td>' . tep_draw_file_field('file_icon_upload_large') . '</td></tr>');
                $contents[] = array('align' => 'center', 'text' => '<tr><td colspan="2" align="center">' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . '&nbsp;<a href="' . tep_href_link(FILENAME_FILE_ICONS, 'page=' . $_GET['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></td></tr></table>');
                break;

              case 'edit':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_FILE_ICON . '</b>');
                $contents = array('form' => tep_draw_form('file_icon', FILENAME_FILE_ICONS, 'page=' . $_GET['page'] . '&iID=' . $_GET['iID'] . '&action=save', 'post', 'enctype="multipart/form-data"'));
                $contents[] = array('text' => '<table><tr><td class="smallText">' . TEXT_FILE_ICON_EXT . '</td><td>' . tep_draw_input_field('file_ext', $iInfo->file_ext) . '</td></tr>');
                if ($handle = opendir(DIR_FS_CATALOG . 'images/file_icons/')) {
                  while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != ".." && (substr($file, -4, 4) == ".gif" || substr($file, -4, 4) == ".jpg")) {
                      $file_icons[] = array('id' => $file, 'text' => $file);
                    }
                  }
                  closedir($handle);
                  $icon_selected = tep_db_fetch_array(tep_db_query("select * from " . TABLE_FILE_ICONS . " where icon_id = '" . $_GET['iID'] . "'"));
                }
                $contents[] = array('text' =>  '<tr><td class="smallText">' . TEXT_FILE_ICON_SMALL . '</td><td>' . tep_draw_pull_down_menu('icon_small', $file_icons, $icon_selected['icon_small'], 'onChange="javascript:set_Img_small()"') . '</td></tr>');
                $contents[] = array('text' =>  '<tr><td class="smallText">' . TEXT_FILE_ICON_SMALL_IMAGE . '</td><td>' . tep_image('../images/file_icons/' . $iInfo->icon_small, '', '', '', 'id="icon_image_small"') . '</td></tr>');
                $contents[] = array('text' =>  '<tr><td class="smallText">' . TEXT_FILE_ICON_LARGE . '</td><td>' . tep_draw_pull_down_menu('icon_large', $file_icons, $icon_selected['icon_large'], 'onChange="javascript:set_Img_large()"') . '</td></tr>');
                $contents[] = array('text' =>  '<tr><td class="smallText">' . TEXT_FILE_ICON_LARGE_IMAGE . '</td><td>' . tep_image('../images/file_icons/' . $iInfo->icon_large, '', '', '', 'id="icon_image_large"') . '</td></tr>');
                $contents[] = array('text' =>  '<tr><td class="smallText">' . TEXT_FILE_ICON_UPLOAD_SMALL . '</td><td>' . tep_draw_file_field('file_icon_upload_small') . '</td></tr>');
                $contents[] = array('text' =>  '<tr><td class="smallText">' . TEXT_FILE_ICON_UPLOAD_LARGE . '</td><td>' . tep_draw_file_field('file_icon_upload_large') . '</td></tr>');
                $contents[] = array('align' => 'center', 'text' => '<tr><td colspan="2" align="center">' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_FILE_ICONS, 'page=' . $_GET['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></td></tr></table>');
                break;

              case 'delete':
                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_FILE_ICON . '</b>');
                $contents = array('form' => tep_draw_form('file_icon', FILENAME_FILE_ICONS, 'page=' . $_GET['page'] . '&iID=' . $_GET['iID'] . '&action=deleteconfirm'));
                $contents[] = array('text' => TEXT_DELETE_FILE_INTRO);
                $contents[] = array('text' => '<br><b>' . $iInfo->file_ext . '</b>');
                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_FILE_ICONS, 'page=' . $_GET['page'] . '&iID=' . $iInfo->icon_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                 break;

              default:
              if (isset($iInfo) && is_object($iInfo)) {
                $heading[] = array('text' => '<b>' . $iInfo->file_ext . '</b>');
                $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_FILE_ICONS, 'page=' . $_GET['page'] . '&iID=' . $iInfo->icon_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_FILE_ICONS, 'page=' . $_GET['page'] . '&iID=' . $iInfo->icon_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
                $contents[] = array('text' => TEXT_FILE_ICON_SMALL . $iInfo->icon_small);
              }
              break;
            }
            if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
              echo '<td width="330" valign="top">' . "\n";
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
</div> 
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>