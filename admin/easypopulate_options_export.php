<?php
/*
  $Id: easypopulate.php,v 3.01 2004/09/21  zip1 Exp $
  
    Released under the GNU General Public License
*/
$curver = '3.01 Advance';

//*******************************
// S T A R T
// INITIALIZATION
//*******************************

require('epconfigure.php');
include ('includes/functions/easypopulate_functions.php');
include (DIR_WS_LANGUAGES . $language . '/easypopulate.php');
//*******************************

$action = isset($_GET['action']) ? $_GET['action'] : '' ;
$method = isset($_POST['download']) ? $_POST['download'] : '';
$sort_order = isset($_POST['optsort']) ? (int)$_POST['optsort'] : 0;

switch ($action) {
  case 'options':
    require('ep_export_options.php');
    break;
  case 'values':
    require('ep_export_values.php');
    break;
  case 'attributes':
    require('ep_export_attributes.php');
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
<!-- BOF options --> 
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo EASY_VERSION_C . EASY_VER_A . EASY_EXPORT; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent"><b><?php echo EASY_LABEL_CREATE . '</b>' ;
         echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_file_export') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
?>                 </td>
               </tr>
               <tr>
               <td>
 <?php echo tep_draw_form('localfile_export', 'easypopulate_options_export.php', 'action=options', 'post', 'ENCTYPE="multipart/form-data"'); ?>
                 </td>
               </tr>
               <tr>
                 <td>
                 <b><?php echo EASY_LABEL_CREATE_SELECT. '</b>' ;
         echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_select_method') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
         echo '&nbsp;';?>
      <select name="download">
      <option selected value ="stream" size="10"><?php echo EASY_LABEL_DOWNLOAD . '<b> ';?>
      <option value="tempfile" size="10"><?php echo EASY_LABEL_CREATE_SAVE;?>
      </select>
                   </td>
      </tr>
      <tr>
       <td>
      
      
 <b><?php echo EASY_LABEL_SORT . '</b>';
  echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_select_sort') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'?>
      <select name="optsort">
            <option value="ID" size="10"><?php echo EASY_LABEL_OPTIONS_ID ;?>
            <option value="Name" size="10"><?php echo EASY_LABEL_OPTIONS_NAME ;?>
      </select>
       </td>
      </tr>
      <tr>
       <td>
          <?php echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;' . tep_image_submit('button_start_file_creation.gif', EASY_LABEL_PRODUCT_START); ?>
        </form>
        </td>
      </tr>
<!-- EOF options --> 
      <tr><td>&nbsp;</td></tr>
<!-- BOF values -->      
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo EASY_VERSION_D . EASY_VER_A . EASY_EXPORT; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent"><b><?php echo EASY_LABEL_CREATE . '</b>' ;
         echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_file_export') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
?>                 </td>
               </tr>
               <tr>
               <td>
 <?php echo tep_draw_form('localfile_export', 'easypopulate_options_export.php', 'action=values', 'post', 'ENCTYPE="multipart/form-data"'); ?>
                 </td>
               </tr>
               <tr>
                 <td>
                 <b><?php echo EASY_LABEL_CREATE_SELECT. '</b>' ;
         echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_select_method') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
         echo '&nbsp;';?>
      <select name="download">
      <option selected value ="stream" size="10"><?php echo EASY_LABEL_DOWNLOAD . '<b> ';?>
      <option value="tempfile" size="10"><?php echo EASY_LABEL_CREATE_SAVE;?>
      </select>
                   </td>
      </tr>
      <tr>
       <td>
      
      
 <b><?php echo EASY_LABEL_SORT . '</b>';
  echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_select_sort') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'?>
      <select name="optsort">
            <option value="ID" size="10"><?php echo EASY_LABEL_VALUES_ID ;?>
            <option value="Name" size="10"><?php echo EASY_LABEL_VALUES_NAME ;?>
      </select>
       </td>
      </tr>
      <tr>
       <td>
          <?php echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;' . tep_image_submit('button_start_file_creation.gif', EASY_LABEL_PRODUCT_START); ?>
        </form>
        </td>
      </tr>
<!-- EOF values --> 
      <tr><td>&nbsp;</td></tr>
<!-- BOF attributs --> 
     <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo EASY_VERSION_E . EASY_VER_A . EASY_EXPORT; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent"><b><?php echo EASY_LABEL_CREATE . '</b>' ;
         echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_file_export') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
?>                 </td>
               </tr>
               <tr>
               <td>
 <?php echo tep_draw_form('localfile_export', 'easypopulate_options_export.php', 'action=attributes', 'post', 'ENCTYPE="multipart/form-data"'); ?>
                 </td>
               </tr>
               <tr>
                 <td>
                 <b><?php echo EASY_LABEL_CREATE_SELECT. '</b>' ;
         echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_select_method') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
         echo '&nbsp;';?>
      <select name="download">
      <option selected value ="stream" size="10"><?php echo EASY_LABEL_DOWNLOAD . '<b> ';?>
      <option value="tempfile" size="10"><?php echo EASY_LABEL_CREATE_SAVE;?>
      </select>
                   </td>
      </tr>
      <tr>
       <td>
      
      
 <b><?php echo EASY_LABEL_SORT . '</b>';
  echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_select_sort') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'?>
      <select name="optsort">
            <option value="ID" size="10"><?php echo EASY_LABEL_VALUES_ID ;?>
            <option value="Name" size="10"><?php echo EASY_LABEL_VALUES_NAME ;?>
      </select>
       </td>
      </tr>
      <tr>
       <td>
          <?php echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;' . tep_image_submit('button_start_file_creation.gif', EASY_LABEL_PRODUCT_START); ?>
        </form>
        </td>
      </tr> 
<!-- EOF attributs --> 
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