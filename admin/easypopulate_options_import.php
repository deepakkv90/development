<?php
/*
  $Id: easypopulate.php,v 3.01 2004/09/21  zip1 Exp $
  
    Released under the GNU General Public License
*/
$curver = '3.01 Advance';

require('epconfigure.php');
include ('includes/functions/easypopulate_functions.php');
include (DIR_WS_LANGUAGES . $language . '/easypopulate.php');

$action = isset($_GET['action']) ? $_GET['action'] : '';
$err_msg = '';
$verify_msg = '';

require('ep_import_options.php');
require('ep_import_values.php');
require('ep_import_attributes.php');

switch ($action) {
  case 'options_upload':
    $filename = tep_get_uploaded_file('usrfl');
    if (is_uploaded_file($filename['tmp_name'])) {
      tep_copy_uploaded_file($filename, DIR_FS_DOCUMENT_ROOT . $tempdir);
      $file = $filename['name'];
      $verify_msg = options_import_check(DIR_FS_CATALOG . $tempdir . $file);
    } else {
      $err_msg = EASY_ERROR_8;
    }
    break;
  case 'options_temp':
    $file = $_POST['localfile'];
    if ($file != '') {
      $verify_msg = options_import_check(DIR_FS_CATALOG . $tempdir . $file);
    } else {
      $err_msg = EASY_ERROR_8;
    }
    break;
  case 'values_upload':
    $filename = tep_get_uploaded_file('usrfl');
    if (is_uploaded_file($filename['tmp_name'])) {
      tep_copy_uploaded_file($filename, DIR_FS_DOCUMENT_ROOT . $tempdir);
      $file = $filename['name'];
      $verify_msg = values_import_check(DIR_FS_CATALOG . $tempdir . $file);
    } else {
      $err_msg = EASY_ERROR_8;
    }
    break;
  case 'values_temp':
    $file = $_POST['localfile'];
    if ($file != '') {
      $verify_msg = values_import_check(DIR_FS_CATALOG . $tempdir . $file);
    } else {
      $err_msg = EASY_ERROR_8;
    }
    break;
  case 'attributes_upload':
    $filename = tep_get_uploaded_file('usrfl');
    if (is_uploaded_file($filename['tmp_name'])) {
      tep_copy_uploaded_file($filename, DIR_FS_DOCUMENT_ROOT . $tempdir);
      $file = $filename['name'];
      $verify_msg = attributes_import_check(DIR_FS_CATALOG . $tempdir . $file);
    } else {
      $err_msg = EASY_ERROR_8;
    }
    break;
  case 'attributes_temp':
    $file = $_POST['localfile'];
    if ($file != '') {
      $verify_msg = attributes_import_check(DIR_FS_CATALOG . $tempdir . $file);
    } else {
      $err_msg = EASY_ERROR_8;
    }
    break;
  case 'confirm':
    $import = $_POST['import'];
    $file_name = $_POST['file_name'];
    switch ($import) {
      case 'options':
        options_import($file_name);
        break;
      case 'values':
        values_import($file_name);
        break;
      case 'attributes':
        attributes_import($file_name);
        break;
    }
    break;
  default:
    break;
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
<script language="javascript" src="includes/general.js"></script>
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=450,height=300%,screenX=150,screenY=150,top=150,left=150')
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
<?php
      if ($verify_msg != '') {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <?php echo tep_draw_form('localfile_import', 'easypopulate_options_import.php', 'action=confirm', 'post', 'ENCTYPE="multipart/form-data"'); ?>
            <td class="main"><b><?php echo $verify_msg; ?></b></td>
            <td class="main"><?php echo (strstr($verify_msg, '<input type="hidden" name="import"') ? tep_image_submit('button_process.gif', TEXT_PROCESS) : '') . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_EASYPOPULATE_OPTIONS_IMPORT) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </form>
          </tr>
        </table></td>
      </tr>
      <tr class="dataTableHeadingRow">
        <td colspan="2">&nbsp;</td>
      </tr>
<?php
      }
?>    
<!-- BOF options --> 
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo EASY_VERSION_C . EASY_VER_A . EASY_IMPORT; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
           <?php echo tep_draw_form('localfile_import', 'easypopulate_options_import.php', 'action=options_upload', 'post', 'ENCTYPE="multipart/form-data"'); ?>
        </td>
      </tr>
      <tr>
        <td><b>
<?php 
         echo EASY_UPLOAD_EP_FILE. '</b>' ;
         echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_file_upload') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
         echo '&nbsp;';
         echo  tep_draw_file_field('usrfl', '50') . tep_draw_separator('pixel_trans.gif', '20', '15') . '&nbsp;' . tep_image_submit('button_verify.gif', TEXT_VERIFY);
?>        
        </td>
      </tr>
      </form>
      <?php echo tep_draw_form('localfile_import', 'easypopulate_options_import.php', 'action=options_temp', 'post', 'ENCTYPE="multipart/form-data"'); ?>
      <tr>
       <td>
       <b><?php echo sprintf(TEXT_IMPORT_TEMP, $tempdir) . '</b>';
  echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_file_insert') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $dir = dir(DIR_FS_CATALOG . $tempdir);
        $contents = array(array('id' => '', 'text' => TEXT_SELECT_ONE_OPTIONS));
        while ($file = $dir->read()) {
          if ( ($file != '.') && ($file != 'CVS') && ($file != '..') && !(strstr($file, 'EPB')) && ($file != '.htaccess') && (strstr($file, 'EPA_options_'))) {
            //$file_size = filesize(DIR_FS_CATALOG . $tempdir . $file);

            $contents[] = array('id' => $file, 'text' => $file);
          }
        }
        echo tep_draw_pull_down_menu('localfile', $contents, (isset($localfile) ? $localfile : ''));
        echo tep_draw_separator('pixel_trans.gif', '20', '15') . '&nbsp;' . tep_image_submit('button_verify.gif', TEXT_VERIFY);
?>      
       </form>
       </td>
      </tr>
<!-- EOF options --> 
      <tr><td>&nbsp;</td></tr>
<!-- BOF values -->      
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo EASY_VERSION_D . EASY_VER_A . EASY_IMPORT; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
           <?php echo tep_draw_form('localfile_import', 'easypopulate_options_import.php', 'action=values_upload', 'post', 'ENCTYPE="multipart/form-data"'); ?>
        </td>
      </tr>
      <tr>
        <td><b>
<?php 
         echo EASY_UPLOAD_EP_FILE. '</b>' ;
         echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_file_upload') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
         echo '&nbsp;';
         echo  tep_draw_file_field('usrfl', '50') . tep_draw_separator('pixel_trans.gif', '20', '15') . '&nbsp;' . tep_image_submit('button_verify.gif', TEXT_VERIFY);
?>        
        </td>
      </tr>
      </form>
      <?php echo tep_draw_form('localfile_import', 'easypopulate_options_import.php', 'action=values_temp', 'post', 'ENCTYPE="multipart/form-data"'); ?>
      <tr>
       <td>
       <b><?php echo sprintf(TEXT_IMPORT_TEMP, $tempdir) . '</b>';
  echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_file_insert') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $dir = dir(DIR_FS_CATALOG . $tempdir);
        $contents = array(array('id' => '', 'text' => TEXT_SELECT_ONE_VALUES));
        while ($file = $dir->read()) {
          if ( ($file != '.') && ($file != 'CVS') && ($file != '..') && !(strstr($file, 'EPB')) && ($file != '.htaccess') && (strstr($file, 'EPA_values_'))) {
            //$file_size = filesize(DIR_FS_CATALOG . $tempdir . $file);

            $contents[] = array('id' => $file, 'text' => $file);
          }
        }
        echo tep_draw_pull_down_menu('localfile', $contents, (isset($localfile) ? $localfile : ''));
        echo tep_draw_separator('pixel_trans.gif', '20', '15') . '&nbsp;' . tep_image_submit('button_verify.gif', TEXT_VERIFY);
?>      
       </form>
       </td>
      </tr>
<!-- EOF values --> 
      <tr><td>&nbsp;</td></tr>
<!-- BOF attributs --> 
     <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo EASY_VERSION_E . EASY_VER_A . EASY_IMPORT; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
           <?php echo tep_draw_form('localfile_import', 'easypopulate_options_import.php', 'action=attributes_upload', 'post', 'ENCTYPE="multipart/form-data"'); ?>
        </td>
      </tr>
      <tr>
        <td><b>
<?php 
         echo EASY_UPLOAD_EP_FILE. '</b>' ;
         echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_file_upload') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
         echo '&nbsp;';
         echo  tep_draw_file_field('usrfl', '50') . tep_draw_separator('pixel_trans.gif', '20', '15') . '&nbsp;' . tep_image_submit('button_verify.gif', TEXT_VERIFY);
?>        
        </td>
      </tr>
      </form>
      <?php echo tep_draw_form('localfile_import', 'easypopulate_options_import.php', 'action=attributes_temp', 'post', 'ENCTYPE="multipart/form-data"'); ?>
      <tr>
       <td>
       <b><?php echo sprintf(TEXT_IMPORT_TEMP, $tempdir) . '</b>';
  echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_file_insert') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $dir = dir(DIR_FS_CATALOG . $tempdir);
        $contents = array(array('id' => '', 'text' => TEXT_SELECT_ONE_ATTRIBUTES));
        while ($file = $dir->read()) {
          if ( ($file != '.') && ($file != 'CVS') && ($file != '..') && !(strstr($file, 'EPB')) && ($file != '.htaccess') && (strstr($file, 'EPA_attributes_'))) {
            //$file_size = filesize(DIR_FS_CATALOG . $tempdir . $file);

            $contents[] = array('id' => $file, 'text' => $file);
          }
        }
        echo tep_draw_pull_down_menu('localfile', $contents, (isset($localfile) ? $localfile : ''));
        echo tep_draw_separator('pixel_trans.gif', '20', '15') . '&nbsp;' . tep_image_submit('button_verify.gif', TEXT_VERIFY);
?>      
       </form>
       </td>
      </tr>
<!-- EOF attributs --> 
<?php
  if ($err_msg != '') {
    echo '<tr><td>&nbsp;</td></tr>';
    echo '<tr><td>';
    echo $err_msg;
    echo '</td></tr>';
  }
?>
    </table></td>
  </tr>
    </table></td>
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
<?php
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>