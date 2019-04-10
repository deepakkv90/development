<?php
/*
  $Id: fdm_library_files.php,v 1.0.0.0 2006/10/09 23:39:49 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_FUNCTIONS . FILENAME_FDM_FUNCTIONS);
  $is_62 = (defined('PROJECT_VERSION') && ereg('6.2', PROJECT_VERSION)) ? true : false;
  
  $parm_exclude = array('fPath','fldID','fID');
  $action = (isset($_POST['action']) ? $_POST['action'] : $_GET['action']);
  $order = (isset($_GET['order']) ? $_GET['order'] : '');
  $fPath = (isset($_GET['fPath']) ? $_GET['fPath'] : '0');
  $fldID = (isset($_GET['fldID']) ? $_GET['fldID'] : '');
  $fID = (isset($_GET['fID']) ? $_GET['fID'] : '');

  switch ($order) {
    case 'asc' :
    case 'desc' :
      $order = strtoupper($order);
      break;
    default:
      $order = '';
      break;
  }

  switch ( $action ) {
    case 'setflag':
        if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
          if (isset($_GET['fID'])) {
            tep_set_files_status($_GET['fID'], $_GET['flag']);
          }
        }
        break;
    case 'delete_folder' :
      $fldID = (isset($_POST['folder_id']) ? $_POST['folder_id'] : '');
      break;
    case 'delete_file' :
      $fID = (isset($_POST['file_id']) ? $_POST['file_id'] : '');
      break;
    case 'folder_confirm' :
      $fldID = (isset($_POST['folder_id']) ? $_POST['folder_id'] : '');   
    
      $folders_query = tep_db_query("select folders_parent_id from " . TABLE_LIBRARY_FOLDERS . " where folders_id = '" . (int)$fldID . "'");
      $folders = tep_db_fetch_array($folders_query);
      $removesub = (isset($_POST['removesub']) ? '' : $folders['folders_parent_id']);
      $removefiles = (isset($_POST['removefiles']) ? '' : $folders['folders_parent_id']);
      tep_remove_folder_tree($fldID, $removesub, $removefiles);
      $fldID = '';
      $fID = '';
      $action = '';
      break;

    case 'file_confirm' :
      $fID = (isset($_POST['file_id']) ? $_POST['file_id'] : '');
      $file_query = tep_db_query("select files_name from " . TABLE_LIBRARY_FILES . " where files_id = '" . (int)$fID . "'");
      $file = tep_db_fetch_array($file_query);
      $fullpath = DIR_FS_CATALOG . LIBRARY_DIRECTORY . $file['files_name'];
      @unlink( $fullpath );
      tep_db_query("delete from " . TABLE_LIBRARY_FILES . " where files_id = '" . (int)$fID . "'");
      tep_db_query("delete from " . TABLE_LIBRARY_FILES_DESCRIPTION . " where files_id = '" . (int)$fID . "'");
      tep_db_query("delete from " . TABLE_LIBRARY_FILES_TO_FOLDERS . " where files_id = '" . (int)$fID . "'");
      $fID = '';
      $action = '';
      break;

    case 'folder_move_confirm' :
      $fldID = tep_db_prepare_input($_POST["folders_id"]);
      $new_parent_id = tep_db_prepare_input($_POST["file_folder"]);
      $check_query = tep_db_query("select * from " . TABLE_LIBRARY_FOLDERS . " where folders_id = '" . (int)$fldID . "' and folders_parent_id = '" . (int)$new_parent_id . "'");
      if(tep_db_num_rows($check_query) == 0) {
        tep_db_query("update " . TABLE_LIBRARY_FOLDERS . " set folders_parent_id = '" . (int)$new_parent_id . "', folders_last_modified = now() where folders_id = '" . (int)$fldID . "'"); 
      } else {
        $messageStack->add(ERROR_FOLDER_EXISTS, 'error');
      }
      $fldID = '';
      $fID = '';
      $action = '';
      break;

    case 'file_confirm_move' :
      $fldID = tep_db_prepare_input($_POST["file_id"]);
      $new_parent_id = tep_db_prepare_input($_POST["file_folder"]); 
      $check_query = tep_db_query("select * from ". TABLE_LIBRARY_FILES_TO_FOLDERS . " where files_id = '" . (int)$fldID . "' and folders_id = '" . (int)$new_parent_id . "' ");
      if(tep_db_num_rows($check_query) == 0) {
        tep_db_query("update " . TABLE_LIBRARY_FILES_TO_FOLDERS . " set folders_id = '" . (int)$new_parent_id . "' where files_id = '" . (int)$fldID . "'");
      } else {
        $messageStack->add(ERROR_FILES_EXISTS, 'error');
      }
      $fldID = '';
      $fID = '';
      $action = '';
      break;
   case 'file_copy_to_confirm' :  
      $fID = tep_db_prepare_input($_POST["file_id"]);
      $fldID = tep_db_prepare_input($_POST["file_folder"]);
      if ($_POST['copy_as'] == 'link') {          
        $check_query = tep_db_query("select count(*) as total from " . TABLE_LIBRARY_FILES_TO_FOLDERS . " where files_id = '" . (int)$fID . "' and folders_id = '" . (int)$fldID . "'");
        $check = tep_db_fetch_array($check_query);
        if ($check['total'] < '1') {
          tep_db_query("insert into " . TABLE_LIBRARY_FILES_TO_FOLDERS . " (files_id, folders_id) values ('" . (int)$fID . "', '" . (int)$fldID . "')");
        }            
      }   
      $fldID = '';
      $fID = '';
      $action = ''; 
      break;
  } 
// check if the library directory exists and is writeable
if (defined('LIBRARY_DIRECTORY') && is_dir('../' . LIBRARY_DIRECTORY)) {
  if (!is_writeable('../' . LIBRARY_DIRECTORY)) $messageStack->add('search', ERROR_LIBRARY_DIRECTORY_NOT_WRITEABLE, 'error');
} else {
  $messageStack->add('search', ERROR_LIBRARY_DIRECTORY_DOES_NOT_EXIST, 'error');
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

<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=650,height=500,screenX=150,screenY=150,top=150,left=150')
}
function checkdelete(del_query) {
  var thereturnvalue = true;
  var agree=confirm(del_query);
  if (agree) {
      thereturnvalue = true;
  }   else {
    thereturnvalue = false;
  }
  return thereturnvalue;
}
//--></script>
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
          <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
                <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                      <td class="smallText" align="right"><?php
                        echo tep_draw_form('search', FILENAME_LIBRARY_FILES);
                        echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search');
                        if(isset($_GET[tep_session_name()])) {
                        echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
                        }
                        echo '</form>';
                    ?>
                      </td>
                    </tr>
                    <tr>
                      <td class="smallText" align="right"><?php echo tep_draw_form('goto', FILENAME_LIBRARY_FILES, '', 'get');  
                      echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('fPath', tep_get_folders_tree(), '', 'onChange="this.form.submit();"'); 
                      if(isset($_GET[tep_session_name()])) {
                      echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
                      }
                      ?>
                        </form>
                      </td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', 1, 2); ?></td>
        </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NAME; ?></td>
                      <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_FILENAME;?></td>
                      <td class="dataTableHeadingContent" align="right">&nbsp;</td>
                      <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                      <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                    </tr>
                    <?php 
              $folders_listed = 0;
              $files_listed = 0;
              if (isset($_POST['search'])) {
                $search = tep_db_prepare_input($_POST['search']);
                $search_txt = " and (fd.folders_name like '%".$search."%' ) ";
                $folders_query = tep_db_query("select f.folders_id, fd.folders_name, f.folders_parent_id, f.folders_date_added, f.folders_last_modified,fd.folders_description from " . TABLE_LIBRARY_FOLDERS . " f, " . TABLE_LIBRARY_FOLDERS_DESCRIPTION . " fd where f.folders_id = fd.folders_id and fd.language_id = '" . (int)$_SESSION['languages_id'] . "' ".$search_txt." " . $folder_orderby);
              } else {
                $folders_query = tep_db_query("select f.folders_id, f.folders_image, fd.folders_name, f.folders_parent_id, f.folders_date_added, f.folders_last_modified,fd.folders_description from " . TABLE_LIBRARY_FOLDERS . " f, " . TABLE_LIBRARY_FOLDERS_DESCRIPTION . " fd where f.folders_parent_id = '" . $fPath . "' and f.folders_id = fd.folders_id and fd.language_id = '" . (int)$_SESSION['languages_id'] . "'" . $folder_orderby);
              }
              while ($folders = tep_db_fetch_array($folders_query)) {
                $folders_listed++;
                if ( $fldID == $folders['folders_id'] || ( $fldID == '' && $fID == '' ) ) {
                  $fldID = $folders['folders_id'];
                  $fldInfo = new objectInfo($folders);
                  echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_LIBRARY_FILES, tep_get_all_get_params($parm_exclude) . 'fPath=' . $fldInfo->folders_id) . '\'">' . "\n";
                } else {
                  echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_LIBRARY_FILES, tep_get_all_get_params($parm_exclude) . 'fPath=' . $fPath . '&fldID=' . $folders['folders_id']) . '\'">' . "\n";
                }
                ?>
                    <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_LIBRARY_FILES, tep_get_all_get_params($parm_exclude) . 'fPath=' . $folders['folders_id']) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>&nbsp;<b>' . $folders['folders_name'] . '</b>'; ?></td>
                      <td class="dataTableContent" align="right">&nbsp;</td>
                      <td class="dataTableContent" align="right">&nbsp;</td>
                      <td class="dataTableContent" align="center">&nbsp;</td>
                      <td class="dataTableContent" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_LIBRARY_FOLDERS_EDIT, 'fPath=' . $fPath . '&fldID=' . $folders['folders_id'] . '&action=edit_category') . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', ICON_EDIT) . '</a>'; ?>&nbsp;&nbsp;
                        <?php if ( is_object($fldInfo) && $fldInfo->folders_id == $folders['folders_id'] ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_LIBRARY_FILES, tep_get_all_get_params($parm_exclude) . 'fPath=' . $fPath . '&fldID=' . $folders['folders_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>
                        &nbsp;</td>
                    </tr>
                    <?php
              }
              if (isset($_POST['search'])) {
                $search = tep_db_prepare_input($_POST['search']);
                $search_txt = " and (f.files_name like '%".$search."%' or fd.files_descriptive_name like '%".$search."%' or f.files_id = '".$search."') ";
                $sql_file=("select f.files_id, f.files_name, fd.files_descriptive_name, f.files_status, f.files_date_added, f.file_date_created, f.files_last_modified, f.file_availability, f.files_general_display, f.files_product_display, fd.files_description, fi.icon_small from " . TABLE_LIBRARY_FILES . " f, " . TABLE_LIBRARY_FILES_DESCRIPTION . " fd, " . TABLE_LIBRARY_FILES_TO_FOLDERS . " ff , ".TABLE_FILE_ICONS." fi where f.files_id = ff.files_id and f.files_id = fd.files_id and fi.icon_id = f.files_icon and fd.language_id = '" . (int)$_SESSION['languages_id'] . "' ".$search_txt."" . $file_orderby);
              } else{
                $sql_file=("select f.files_id, f.files_name, fd.files_descriptive_name, f.files_status, f.files_date_added, f.file_date_created, f.files_last_modified, f.file_availability, f.files_general_display, f.files_product_display, fd.files_description, fi.icon_small from " . TABLE_LIBRARY_FILES . " f, " . TABLE_LIBRARY_FILES_DESCRIPTION . " fd, " . TABLE_LIBRARY_FILES_TO_FOLDERS . " ff , ".TABLE_FILE_ICONS." fi where ff.folders_id = '" . $fPath . "' and f.files_id = ff.files_id and f.files_id = fd.files_id and fi.icon_id = f.files_icon and fd.language_id = '" . (int)$_SESSION['languages_id'] . "'" . $file_orderby);
              }
              $files_query = tep_db_query($sql_file);
              while ($files = tep_db_fetch_array($files_query)) {
                $files_listed++;
                if ( $fID == $files['files_id'] || ( $fldID == '' && $fID == '' ) ) {
                  $fID = $files['files_id'];
                  $fInfo = new objectInfo($files);
                  echo '<tr id="defaultSelected" class="dataTableRowSelected" >' . "\n";
                } else {
                  echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_LIBRARY_FILES, tep_get_all_get_params($parm_exclude) . 'fPath=' . $fPath . '&fID=' . $files['files_id']) . '\'">' . "\n";
                }
                ?>
                    <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_LIBRARY_FILES, tep_get_all_get_params($parm_exclude) . 'fPath=' . $fPath . '&fID=' . $files['files_id']) . '">' . tep_image(DIR_WS_ICONS . 'file.gif', ICON_FILE) . '</a>&nbsp;<b>' . $files['files_descriptive_name'] . '</b>'; ?></td>
                      <td class="dataTableContent" align="left"><table>
                          <tr>
                            <td valign="middle"><img border="0" src="<?php echo DIR_WS_CATALOG_IMAGES . 'file_icons/' . $files['icon_small']; ?>"></td>
                            <td valign="middle"><?php echo $files['files_name']; ?></td>
                          </tr>
                        </table></td>
                      <td class="dataTableContent" align="right"><?php echo $file_missed; ?></td>
                      <td class="dataTableContent" align="right"><?php
                  if ($files['files_status'] == '1') {
                    echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_LIBRARY_FILES, 'action=setflag&flag=0&fID=' . $files['files_id'] . '&fPath=' . $fPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
                  } else {
                    echo '<a href="' . tep_href_link(FILENAME_LIBRARY_FILES, 'action=setflag&flag=1&fID=' . $files['files_id'] . '&fPath=' . $fPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
                  }
                  ?>
                      </td>
                      <td class="dataTableContent" align="right"><?php 
                  $filename = DIR_FS_CATALOG . LIBRARY_DIRECTORY . $files['files_name'];
                  if( file_exists($filename) )  {
                    $file = FILENAME_ADMIN_DOWNLOAD_FILE;
                    ?>
                        <a href="<?php echo $file; ?>?fileid=<?php echo $files['files_id'];  ?>"><?php echo tep_image(DIR_WS_ICONS . 'file_download.gif', ICON_FILE_DOWNLOAD); ?></a>&nbsp;&nbsp;
                        <?php
                  }  else  {
                    echo tep_image(DIR_WS_ICONS . 'file_download_grey.gif', ICON_FILE_DOWNLOAD) . '&nbsp;&nbsp;&nbsp;';
                  }

                  $_SESSION['return'] = tep_href_link(FILENAME_LIBRARY_FILES, 'fPath=' . $fPath);
                  echo '<a href="' . tep_href_link(FILENAME_LIBRARY_FILES_EDIT, 'fPath=' . (int)$fPath . '&fID=' . $files['files_id'] .  '&action=edit') . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', ICON_EDIT) . '</a>'; ?>
                        &nbsp;
                        <?php if ( is_object($fInfo) && $fInfo->files_id == $files['files_id'] ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_LIBRARY_FILES, tep_get_all_get_params($parm_exclude) . 'fPath=' . (int)$fPath . '&fID=' . $files['files_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>
                        &nbsp;</td>
                    </tr>
                    <?php
              }
              $fPath_back = '';
              if ( $fPath <> 0 ) {  
                $parent_query = tep_db_query("select folders_parent_id from " . TABLE_LIBRARY_FOLDERS . " where folders_id = '" . $fPath . "'");
                $parent = tep_db_fetch_array($parent_query);
                $fPath_back =  '<a href="' . tep_href_link(FILENAME_LIBRARY_FILES, 'fPath=' . $parent['folders_parent_id']) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;';
              }
              $new_file = tep_draw_form('new_file', FILENAME_LIBRARY_FILES_EDIT, tep_get_all_get_params($parm_exclude) . 'fPath=' . $fPath . '&fldID=' . $folders['folders_id']);
              $new_file .= tep_draw_hidden_field('action', 'new');
              $new_file .= tep_draw_hidden_field('fPath', $fPath);
              $new_file .= tep_draw_hidden_field('return', tep_href_link(FILENAME_LIBRARY_FILES, 'fPath=' . $fPath));
              $new_file .= tep_image_submit('button_new_file.gif', IMAGE_NEW_FILE);
              $new_file .= '</form>';
              $new_fld = tep_draw_form('new_folder', FILENAME_LIBRARY_FOLDERS_EDIT, tep_get_all_get_params($parm_exclude) . 'fPath=' . $fPath . '&fldID=' . $folders['folders_id']);
              $new_fld .= tep_draw_hidden_field('action', 'new');
              $new_fld .= tep_draw_hidden_field('fPath', $fPath);
              $new_fld .= tep_draw_hidden_field('return', tep_href_link(FILENAME_LIBRARY_FILES, 'fPath=' . $fPath));
              $new_fld .= tep_image_submit('button_new_folder.gif', IMAGE_NEW_FOLDER);
              $new_fld .= '</form>';
              ?>
                    <tr>
                      <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                          <tr>
                            <td class="smallText"><?php echo TEXT_FOLDERS . '&nbsp;' . $folders_listed . '<br>' . TEXT_FILES . '&nbsp;' . $files_listed; ?></td>
                            <td align="right" class="smallText"><?php echo $fPath_back . $new_fld . '&nbsp;' . $new_file; ?>&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
                <?php
            $heading = array();
            $contents = array();

            switch ($action) {
              case 'delete_folder':
                if ( isset($fldInfo) && is_object($fldInfo) ) {
                  $heading[] = array('text' => '<b>' . TEXT_DELETE_FOLDER_HEADER . '<br>' . $fldInfo->folders_name . '</b>');
                  $contents = array('form' => tep_draw_form('folder_del', FILENAME_LIBRARY_FILES, tep_get_all_get_params($parm_exclude) . 'fPath=' . $fPath . '&fldID=' . $_GET['fldID'],'post', 'onSubmit="return checkdelete(\'' . TEXT_INFO_DELETE_FOLDER . '\')";') . tep_draw_hidden_field('action', 'folder_confirm') . tep_draw_hidden_field('folder_id', $_GET['fldID']));
                  $contents[] = array('text' => TEXT_DELETE_FOLDER_INTRO . '<br><br>');
                  $contents[] = array('text' => TEXT_DELETE_FOLDER_ADDED . '&nbsp;' . $fldInfo->folders_date_added . '<br>');
                  $contents[] = array('text' => TEXT_DELETE_FOLDER_DESCRIPTION . '&nbsp;<b>' . $fldInfo->folders_description . '</b><br>');
                  $contents[] = array('text' => TEXT_FOLDER_IMAGE . '&nbsp;<b>' . tep_image(HTTP_SERVER.DIR_WS_CATALOG_IMAGES.$fldInfo->folders_image) . '</b><br>');
                  $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('removesub') . '&nbsp;' . TEXT_DELETE_SUBFOLDER_MSG);
                  $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('removefiles') . '&nbsp;' . TEXT_DELETE_SUBFILE_MSG);
                  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_LIBRARY_FILES) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                }
                break;

              case 'delete_file':
                if ( isset($fInfo) && is_object($fInfo) ) {
                  $heading[] = array('text' => '<b>' . TEXT_DELETE_FILE_HEADER . '<br>' . $fInfo->files_name . '</b>');
                  $contents = array('form' => tep_draw_form('folder_del', FILENAME_LIBRARY_FILES, tep_get_all_get_params($parm_exclude) . 'fPath=' . $fPath . '&fldID=' . $folders['folders_id'],'post', 'onSubmit="return checkdelete(\'' . TEXT_INFO_DELETE_QUERY . '\')";') . tep_draw_hidden_field('action', 'file_confirm') . tep_draw_hidden_field('file_id', $fInfo->files_id));
                  $contents[] = array('text' => TEXT_DELETE_FILE_INTRO . '<br><br>');
                  $contents[] = array('text' => TEXT_DELETE_FILE_ADDED . '&nbsp;' . $fInfo->files_date_added . '<br>');
                  $contents[] = array('text' => TEXT_DELETE_FILE_DESCRIPTIVE_NAME . '&nbsp;<b>' . $fInfo->files_descriptive_name . '</b><br>');
                  $contents[] = array('text' => TEXT_DELETE_FILE_DESCRIPTION . '&nbsp;<b>' . $fInfo->files_description . '</b><br>');
                  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_LIBRARY_FILES) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                }
                break;

              case 'move':
                $heading[] = array('text' => '<b>' . "Move Folder" . '<br><b>' );
                $contents = array('form' => tep_draw_form('folder_move', FILENAME_LIBRARY_FILES, tep_get_all_get_params($parm_exclude) . 'fPath=' . $fPath . '&fldID=' . $folders['folders_id'],'post') . tep_draw_hidden_field('action', 'folder_move_confirm') . tep_draw_hidden_field('folders_id', $fldInfo->folders_id));
                $contents[] = array('text' => sprintf(TEXT_MOVE_FOLDERS_INTRO,$fldInfo->folders_name) . '<br><br>');
                $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE,$fldInfo->folders_name) . '<br>' . tep_draw_pull_down_menu('file_folder', tep_get_folders_tree(), $fPath));
                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_LIBRARY_FILES) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                break;
 
              case 'move_file':
                $heading[] = array('text' => '<b>' . TEXT_MOVE_FILE_HEADER . '<br>' .'</b>');
                $contents = array('form' => tep_draw_form('file_move', FILENAME_LIBRARY_FILES, tep_get_all_get_params($parm_exclude) . 'fPath=' . $fPath . '&fldID=' . $folders['folders_id'],'post') . tep_draw_hidden_field('action', 'file_confirm_move') . tep_draw_hidden_field('file_id', $fInfo->files_id));
                $contents[] = array('text' => sprintf(TEXT_MOVE_FILE_INTRO,$fInfo->files_name) . '<br>'); 
                $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_FOLDERS . '<br><b>' . tep_output_generated_folder_path($fInfo->files_id, 'file') . '</b>');
                $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE,$fInfo->files_name) . '<br>' . tep_draw_pull_down_menu('file_folder', tep_get_folders_tree(), $fPath));     
                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_LIBRARY_FILES) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                 break;

              case 'copy_to':
                $heading[] = array('text' => '<b>' . TEXT_FILE_INFO_HEADING_COPY_TO . '</b>');
                $contents = array('form' => tep_draw_form('file_copy_to', FILENAME_LIBRARY_FILES, tep_get_all_get_params($parm_exclude) . 'fPath=' . $fPath . '&fldID=' . $fInfo->files_id,'post') . tep_draw_hidden_field('action', 'file_copy_to_confirm') . tep_draw_hidden_field('file_id', $fInfo->files_id));
                $contents[] = array('text' => TEXT_FILE_INFO_COPY_TO_INTRO);     
                $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_FOLDERS . '<br><b>' . tep_output_generated_folder_path($fInfo->files_id, 'file') . '</b>');
                $contents[] = array('text' => '<br>' . TEXT_FOLDERS . '<br>' . tep_draw_pull_down_menu('file_folder', tep_get_folders_tree(), $fPath));
                $contents[] = array('text' => '<br>' . TEXT_HOW_TO_COPY . '<br>' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK );  
                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', IMAGE_COPY) . ' <a href="' . tep_href_link(FILENAME_LIBRARY_FILES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                 break;

              default :
                if ( isset($fldInfo) && is_object($fldInfo) ) {
                  $edit_form = tep_draw_form('folder_edit', FILENAME_LIBRARY_FOLDERS_EDIT, tep_get_all_get_params($parm_exclude) . 'fPath=' . $fPath . '&fldID=' . $fldInfo->folders_id);
                  $edit_form .= tep_draw_hidden_field('action', 'edit');
                  $edit_form .= tep_draw_hidden_field('folder_id', $fldInfo->folders_id);
                  $edit_form .= tep_draw_hidden_field('fPath', $fPath);
                  $edit_form .= tep_draw_hidden_field('return', tep_href_link(FILENAME_LIBRARY_FILES, 'fPath=' . $fPath));
                  $edit_form .= tep_image_submit('button_edit.gif', IMAGE_EDIT);
                  $edit_form .= '</form>';
                                  
                  $del_form = tep_draw_form('folder_del', FILENAME_LIBRARY_FILES, tep_get_all_get_params($parm_exclude) . 'fPath=' . $fPath . '&fldID=' .  $fldInfo->folders_id);
                  $del_form .= tep_draw_hidden_field('action', 'delete_folder');
                  $del_form .= tep_draw_hidden_field('folder_id', $fldInfo->folders_id);
                  $del_form .= tep_image_submit('button_delete.gif', IMAGE_DELETE) . '<br>';
                  $del_form .= '</form>';

                  $move_form = tep_draw_form('folder_move', FILENAME_LIBRARY_FILES, tep_get_all_get_params($parm_exclude) . 'fPath=' . $fPath . '&fldID=' . $fldInfo->folders_id);
                  $move_form .= tep_draw_hidden_field('action', 'move');
                  $move_form .= tep_draw_hidden_field('folder_id', $fldInfo->folders_id);
                  $move_form .= tep_draw_hidden_field('fPath', $fPath);
                  $move_form .= tep_draw_hidden_field('return', tep_href_link(FILENAME_LIBRARY_FILES, 'fPath=' . $fPath));
                  $move_form .= tep_image_submit('button_move.gif', IMAGE_MOVE);
                  $move_form .= '</form>';
      
                  $heading[] = array('text' => '<b>' . $fldInfo->folders_name . '</b>');    
                  $content_disp = '</td><tr><td align="left" class="infoBoxContent">&nbsp;<br>' . TEXT_DELETE_FOLDER_DESCRIPTION . '&nbsp;' . $fldInfo->folders_description . '<br><br/><center>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $fldInfo->folders_image) .'</center><br><br/>'. TEXT_FILES_LAST_MOD_DATE . '&nbsp;' . tep_date_short($fldInfo->folders_last_modified) . '</td></tr><td>';
                  $contents[] = array('align' => 'center', 'text' => $edit_form . '&nbsp;' . $del_form . '&nbsp;' . $move_form.$content_disp);
                } elseif ( isset($fInfo) && is_object($fInfo) ) {                 
                  $edit_form = tep_draw_form('file_edit', FILENAME_LIBRARY_FILES_EDIT, tep_get_all_get_params($parm_exclude) . 'fPath=' . $fPath . '&fID=' . $fInfo->files_id);
                  $edit_form .= tep_draw_hidden_field('action', 'edit');
                  $edit_form .= tep_draw_hidden_field('file_id', $fInfo->files_id);
                  $edit_form .= tep_draw_hidden_field('fPath', $fPath);
                  $edit_form .= tep_draw_hidden_field('return', tep_href_link(FILENAME_LIBRARY_FILES, 'fPath=' . $fPath));
                  $edit_form .= tep_image_submit('button_edit.gif', IMAGE_EDIT);
                  $edit_form .= '</form>';
                                    
                  $del_form = tep_draw_form('file_del', FILENAME_LIBRARY_FILES, tep_get_all_get_params($parm_exclude) . 'fPath=' . $fPath . '&fldID=' . $folders['folders_id']);
                  $del_form .= tep_draw_hidden_field('action', 'delete_file');
                  $del_form .= tep_draw_hidden_field('file_id', $fInfo->files_id);
                  $del_form .= tep_image_submit('button_delete.gif', IMAGE_DELETE) . '<br>';
                  $del_form .= '</form>';

                  $move_form = tep_draw_form('file_move', FILENAME_LIBRARY_FILES, tep_get_all_get_params($parm_exclude) . 'fPath=' . $fPath . '&fID=' . $fInfo->files_id);
                  $move_form .= tep_draw_hidden_field('action', 'move_file');
                  $move_form .= tep_draw_hidden_field('file_id', $fInfo->files_id);
                  $move_form .= tep_image_submit('button_move.gif', IMAGE_MOVE);
                  $move_form .= '</form>';

                  $copy_to_form = tep_draw_form('file_copy', FILENAME_LIBRARY_FILES, tep_get_all_get_params($parm_exclude) . 'fPath=' . $fPath . '&fID=' . $fInfo->files_id);
                  $copy_to_form .= tep_draw_hidden_field('action', 'copy_to');
                  $copy_to_form .= tep_draw_hidden_field('file_id', $fInfo->files_id);
                  $copy_to_form .= tep_image_submit('button_copy_to.gif', IMAGE_COPY_TO) . '<br>';
                  $copy_to_form .= '</form>';
                  
                  $attach_products = '<a href="' . tep_href_link(FILENAME_LIBRARY_FILES_PRODUCTS, tep_get_all_get_params($parm_exclude) . 'fPath=' . $fPath . '&fID=' . $fInfo->files_id) . '">' . tep_image_button('button_attach_products.gif', IMAGE_ATTACH_PRODUCTS) . '</a><br>';
                  
                  $downloads_report = '<a href="' . tep_href_link(FILENAME_DAILY_DOWNLOADS, 'files=' . $fInfo->files_id) . '">' . tep_image_button('button_downloads_report.gif', IMAGE_DOWNLOADS_REPORT) . '</a>';
                  
                  $heading[] = array('text' => '<b>' . $fInfo->files_name . '</b>');
                  $files_status = $fInfo->files_status == 1 ? TEXT_ENABLED : TEXT_DISABLED;
                  $content_disp = '</td><tr><td align="left" class="infoBoxContent"><br>' . TEXT_FILES_STATUS . '&nbsp;' . $files_status . '<br><br>' . TEXT_FILE_AVAILABILITY . '&nbsp;' . (($fInfo->file_availability == 0) ? TEXT_FREE : TEXT_REQUIRE_LOGIN) . '<br><br>' .  TEXT_DISPLAY_IN_DIR . '&nbsp;' . (($fInfo->files_general_display == 1) ? TEXT_ENABLED : TEXT_DISABLED) . '<br><br>' .  TEXT_ALLOW_ATTACH . '&nbsp;' . (($fInfo->files_product_display == 1) ? TEXT_ENABLED : TEXT_DISABLED) . '<br><br>' . TEXT_FILE_DATE_CREATED . '&nbsp;' . tep_date_short($fInfo->file_date_created) . '<br>' . TEXT_FILES_LAST_MOD_DATE . '&nbsp;' . tep_date_short($fInfo->files_last_modified) . '</td></tr><td>';
                  $contents[] = array('align' => 'center', 'text' => $edit_form . '&nbsp;' . $del_form. '&nbsp;' .$move_form . '&nbsp;' . $copy_to_form . '&nbsp;' . $attach_products . '&nbsp;' . $downloads_report . $content_disp);
                }
                break;
            }           
            if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
              echo '<td width="20%" valign="top">' . "\n";
              $box = new box;
              echo $box->infoBox($heading, $contents);
              echo '</td>' . "\n";
            } else {
              echo '<td width="20%" valign="top">&nbsp;</td>';
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