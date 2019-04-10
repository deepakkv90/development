<?php
/*
  $Id: fdm_library_files_edit.php,v 1.0.0.0 2006/10/12 13:41:11 eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  $is_62 = (defined('PROJECT_VERSION') && ereg('6.2', PROJECT_VERSION)) ? true : false;
  $action = (isset($_POST['action']) ? $_POST['action'] : $_GET['action']);
  $newSelect = (isset($_GET['newSelect']) ? $_GET['newSelect'] : '');
  $nfSelect = (isset($_GET['nfSelect']) ? $_GET['nfSelect'] : '');
  $chgSelect = (isset($_GET['chgSelect']) ? $_GET['chgSelect'] : '');
  
  if ( (!isset($_SESSION['library_known_files'])) || (!isset($_SESSION['library_disk_files'])) || ($action == 'rescan') ) {
    $_SESSION['library_known_files'] = array();
    $_SESSION['library_disk_files'] = array();
    $_SESSION['scan_new_files'] = array();
    $_SESSION['scan_nf_files'] = array();
    $_SESSION['scan_chg_files'] = array();
    
    // build an array of all the files known to the system
    $files_query = tep_db_query('select files_id, files_name, files_md5, files_date_added from ' . TABLE_LIBRARY_FILES . ' order by files_name');
    while ($files = tep_db_fetch_array($files_query)) {
      $_SESSION['library_known_files'][$files['files_name']] = array('id' => $files['files_id'],
                                                 'md5' => $files['files_md5'],
                                                 'added' => $files['files_date_added']);
    }
    //  print_r($_SESSION['library_known_files']);
    // build an array of all the files on the disk
    $dir = opendir( DIR_FS_CATALOG . LIBRARY_DIRECTORY );
    while( $file = readdir( $dir ) ) {
      if ( $file == "." || $file == ".." ) continue;
      $fullpath = DIR_FS_CATALOG . LIBRARY_DIRECTORY . $file;
      if ( is_file( $fullpath ) ) {
//        $file_string = file_get_contents( $fullpath );
        $md5 = md5( $fullpath . filesize($fullpath));
        $_SESSION['library_disk_files'][$file] = array('md5' => $md5,
                                   'u_date' => filemtime( $fullpath ));
      }
    }
    
    //compare the dat read from the database and the disk  
    // find all files that the name and md5 match and removed them from the array
    // store any files that the name matchs but the md5 has changed
    // store any file that is n longer found on the disk
    foreach ( $_SESSION['library_known_files'] as $name => $d ) {
      if ( array_key_exists( $name, $_SESSION['library_disk_files'] ) ) { 
        if ( $d['md5'] <> $_SESSION['library_disk_files'][$name]['md5'] ) {    
          $_SESSION['scan_chg_files'][$name] = $d;
        }
        unset( $_SESSION['library_known_files'][$name] );
        unset( $_SESSION['library_disk_files'][$name] );
      } else {
        $_SESSION['scan_nf_files'][$name] = $d;
        unset( $_SESSION['library_known_files'][$name] );
      }
    }
    // at this point, the only files left are new
    $_SESSION['scan_new_files'] = $_SESSION['library_disk_files'];
  }
  
  if ($action == 'deleteconfirm') {
    $file_name = $_POST['file_name'];
    $id_query = tep_db_query("select files_id from " . TABLE_LIBRARY_FILES . " where files_name = '" . $file_name . "'");
    $file_id =  tep_db_fetch_array($id_query);
    tep_db_query("delete from " . TABLE_LIBRARY_FILES_TO_FOLDERS . " where files_id = '" . $file_id['files_id'] . "'");
    tep_db_query("delete from " . TABLE_LIBRARY_FILES . " where files_id = '" . $file_id['files_id'] . "'");
    tep_db_query("delete from " . TABLE_LIBRARY_FILES_DESCRIPTION . " where files_id = '" . $file_id['files_id'] . "'");
    @unlink(DIR_FS_CATALOG . LIBRARY_DIRECTORY . $file_name);
    unset($_SESSION['library_known_files']);
    unset($_SESSION['library_disk_files']);
    unset($_SESSION['scan_new_files']);
    unset($_SESSION['scan_nf_files']);
    unset($_SESSION['scan_chg_files']);
    tep_redirect(tep_href_link(FILENAME_LIBRARY_SCAN));
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
  }
  else {
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
              <td align="right"><?php echo tep_draw_form('rescan', FILENAME_LIBRARY_SCAN, 'action=rescan'); ?><?php echo tep_image_submit('button_rescan.gif', IMAGE_RESCAN) . tep_draw_hidden_field('action', 'rescan'); ?></form></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr class="dataTableHeadingRow">
                  <td class="dataTableHeadingContent"><?php echo TABLE_NEW_HEADING_NAME; ?></td>
                  <td class="dataTableHeadingContent" align="center"><?php echo TABLE_NEW_HEADING_DATE; ?></td>
                  <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                </tr>
                <?php // process the NEW files
                $files_listed = 0;
                if (sizeof($_SESSION['scan_new_files']) > 0) {
                  foreach ( $_SESSION['scan_new_files'] as $name => $d ) {
                    if (!eregi('htaccess', $name)) {                       
                      $files_listed++;
                      if ($newSelect == $name || $newSelect == '') {
                        $newSelect = $name;
                        echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
                      } else {
                        echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_LIBRARY_SCAN, 'action=select&newSelect=' . $name) . '\'">' . "\n";
                      }
                      ?>
                      <td class="dataTableContent"><?php echo '<b>' . $name . '</b>'; ?></td>
                      <td class="dataTableContent" align="center"><?php echo date("m/d/Y", $d['u_date']); ?></td>
                      <td class="dataTableContent" align="right"><?php if ( $newSelect == $name ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_LIBRARY_SCAN, 'action=select&newSelect=' . $name) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                    </tr>
                    <?php
                    }
                  }                  
                }
                ?>
                <tr>
                  <td colspan="3" class="smallText"><?php echo TEXT_FILES . '&nbsp;' . $files_listed; ?></td>
                </tr>
              </table></td>
              <?php
              $heading = array();
              $contents = array();
              
              switch ($action) {
                case 'delete':
                  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CONFIRM . '</b>');
                  $contents = array('form' => tep_draw_form('deleteconfirm', FILENAME_LIBRARY_SCAN, tep_get_all_get_params(), 'post'));
                  $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br><br>' . tep_draw_hidden_field('action', 'deleteconfirm') . tep_draw_hidden_field('file_name', $newSelect));
                  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_LIBRARY_SCAN, tep_get_all_get_params()) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                  break;

                default :
                  if ($newSelect <> '') {
                    $add_form = tep_draw_form('add_new', FILENAME_LIBRARY_FILES_EDIT);
                    $add_form .= tep_draw_hidden_field('action', 'load');
                    $add_form .= tep_draw_hidden_field('file_name', $newSelect);
                    $add_form .= tep_draw_hidden_field('return', tep_href_link(FILENAME_LIBRARY_SCAN, 'action=rescan'));
                    $add_form .= tep_image_submit('button_add.gif', IMAGE_ADD);
                    $add_form .= '</form>';
                    $del_form = tep_draw_form('del_new', FILENAME_LIBRARY_SCAN,'newSelect=' . $newSelect);
                    $del_form .= tep_draw_hidden_field('action', 'delete');
                    $del_form .= tep_draw_hidden_field('file_id', $newSelect);
                    $del_form .= tep_image_submit('button_delete.gif', IMAGE_DELETE);
                    $del_form .= '</form>';
                    $heading[] = array('text' => '<b>' . $newSelect . '</b>');
                    $contents[] = array('align' => 'center', 'text' => $add_form . '&nbsp;' . $del_form );
                  } else {
                    $add_form = tep_draw_form('add_new', FILENAME_LIBRARY_FILES_EDIT);
                    $add_form .= tep_draw_hidden_field('action', 'load');
                    $add_form .= tep_draw_hidden_field('return', tep_href_link(FILENAME_LIBRARY_SCAN, 'action=rescan'));
                    $add_form .= tep_image_submit('button_add.gif', IMAGE_ADD);
                    $add_form .= '</form>';
                    $heading[] = array('text' => '<b>');
                    $contents[] = array('align' => 'center', 'text' => $add_form . '&nbsp;' . $del_form );
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
              <?php
              if (count( $_SESSION['scan_chg_files'] ) > 0) {
                ?>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                </tr>
                <tr>
                  <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent"><?php echo TABLE_CHG_HEADING_NAME; ?></td>
                      <td class="dataTableHeadingContent" align="center"><?php echo TABLE_CHG_HEADING_DATE; ?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                    </tr>
                    <?php //process the CHANGED files
                    $files_listed = 0;
                    foreach ( $_SESSION['scan_chg_files'] as $name => $d ) {
                      if (!eregi('htaccess', $name)) {                        
                        $files_listed++;
                        if ( $chgSelect == $name || $chgSelect == '' ) {
                          $chgSelect = $name;
                          $chgId = $d['id'];
                          echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
                        } else {
                          echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_LIBRARY_SCAN, 'action=select&chgSelect=' . $name) . '\'">' . "\n";
                        }
                        ?>
                        <td class="dataTableContent"><?php echo '<b>' . $name . '</b>'; ?></td>
                        <td class="dataTableContent" align="center"><?php echo date("m/d/Y", filectime(DIR_FS_CATALOG . LIBRARY_DIRECTORY . $name)); ?></td>
                        <td class="dataTableContent" align="right"><?php if ( $chgSelect == $name ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_LIBRARY_SCAN, 'action=select&chgSelect=' . $name) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                      </tr>
                      <?php
                      }
                    }               
                    ?>
                    <tr>
                      <td colspan="3" class="smallText"><?php echo TEXT_FILES . '&nbsp;' . $files_listed; ?></td>
                    </tr>
                  </table></td>
                  <?php
                  $heading = array();
                  $contents = array();

                  switch ($action) {
                    case 'delete_chg':
                      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CONFIRM . '</b>');
                      $contents = array('form' => tep_draw_form('delete_changed_file', FILENAME_LIBRARY_SCAN, tep_get_all_get_params()));
                      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br><br>' . tep_draw_hidden_field('action', 'deleteconfirm') . tep_draw_hidden_field('file_name', $chgSelect));
                      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_LIBRARY_SCAN, tep_get_all_get_params()) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                      break;

                    default :
                      if ( $chgSelect <> '' ) {
                        $edit_form = tep_draw_form('file_edit', FILENAME_LIBRARY_FILES_EDIT);
                        $edit_form .= tep_draw_hidden_field('action', 'edit');
                        $edit_form .= tep_draw_hidden_field('file_id', $chgId);
                        $add_form .= tep_draw_hidden_field('file_name', $chgSelect);
                        $edit_form .= tep_draw_hidden_field('return', tep_href_link(FILENAME_LIBRARY_SCAN, 'action=rescan'));
                        $edit_form .= tep_image_submit('button_edit.gif', IMAGE_EDIT);
                        $edit_form .= '</form>';
                        $del_form = tep_draw_form('del_new', FILENAME_LIBRARY_SCAN, 'chgSelect=' . $chgSelect);
                        $del_form .= tep_draw_hidden_field('action', 'delete_chg');
                        $del_form .= tep_draw_hidden_field('file_id', $chgSelect);
                        $del_form .= tep_image_submit('button_delete.gif', IMAGE_DELETE);
                        $del_form .= '</form>';
                        $heading[] = array('text' => '<b>' . $chgSelect . '</b>');
                        $contents[] = array('align' => 'center', 'text' => $edit_form . '&nbsp;' . $del_form );
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
                <?php
              }
              if ( count( $_SESSION['scan_nf_files'] ) > 0 ) {
                ?>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                </tr>
                <tr>
                  <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent"><?php echo TABLE_NF_HEADING_NAME; ?></td>
                      <!-- td class="dataTableHeadingContent" align="center"><?php echo TABLE_NF_HEADING_DATE; ?></td -->
                      <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                    </tr>
                    <?php  // process the NOT FOUND Files
                    $files_listed = 0;
                    foreach ( $_SESSION['scan_nf_files'] as $name => $d ) {
                      if (!eregi('htaccess', $name)) {                         
                        $files_listed++;
                        if ( $nfSelect == $name || $nfSelect == '' ) {
                          $nfSelect = $name;
                          $nfId = $d['id'];
                          echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
                        } else {
                          echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_LIBRARY_SCAN, 'action=select&nfSelect=' . $name) . '\'">' . "\n";
                        }
                        ?>
                        <td class="dataTableContent"><?php echo '<b>' . $name . '</b>'; ?></td>
                        <!-- td class="dataTableContent" align="center"><?php echo date("m/d/Y", filectime(DIR_FS_CATALOG . LIBRARY_DIRECTORY . $name)); ?></td -->
                        <td class="dataTableContent" align="right"><?php if ( $nfSelect == $name ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_LIBRARY_SCAN, 'action=select&nfSelect=' . $name) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                      </tr>
                      <?php
                      }                    
                    }
                    ?>
                    <tr>
                      <td colspan="3" class="smallText"><?php echo TEXT_FILES . '&nbsp;' . $files_listed; ?></td>
                    </tr>
                  </table></td>
                  <?php
                    $heading = array();
                    $contents = array();

                    switch ($action) {
                      case 'delete_nf':
                        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CONFIRM . '</b>');
                        $contents = array('form' => tep_draw_form('deleteconfirm', FILENAME_LIBRARY_SCAN, tep_get_all_get_params()));
                        $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br><br>' . tep_draw_hidden_field('action', 'deleteconfirm') . tep_draw_hidden_field('file_name', $nfSelect));
                        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_LIBRARY_SCAN, tep_get_all_get_params()) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                         break;
    
                      default :
                        if ( $nfSelect <> '' ) {
                          $edit_form = tep_draw_form('file_edit', FILENAME_LIBRARY_FILES_EDIT);
                          $edit_form .= tep_draw_hidden_field('action', 'edit');
                          $edit_form .= tep_draw_hidden_field('file_id', $nfId);
                          $add_form .= tep_draw_hidden_field('file_name', $nfSelect);
                          $edit_form .= tep_draw_hidden_field('return', tep_href_link(FILENAME_LIBRARY_SCAN, 'action=rescan'));
                          $edit_form .= tep_image_submit('button_edit.gif', IMAGE_EDIT);
                          $edit_form .= '</form>';
                          $del_form = tep_draw_form('del_new', FILENAME_LIBRARY_SCAN, 'nfSelect=' . $nfSelect);
                          $del_form .= tep_draw_hidden_field('action', 'delete_nf');
                          $del_form .= tep_draw_hidden_field('file_id', $nfSelect);
                          $del_form .= tep_image_submit('button_delete.gif', IMAGE_DELETE);
                          $del_form .= '</form>';
                          $heading[] = array('text' => '<b>' . $nfSelect . '</b>');
                          $contents[] = array('align' => 'center', 'text' => $edit_form . '&nbsp;' . $del_form );
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
                    <?php
                    }
                    ?>
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