<?php
/*

  Copyright (c) 2005 Chainreactionworks.com

  Released under the GNU General Public License
  Original Auhtor:
  Updates by:
  
*/
  require('includes/application_top.php');

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
    
<?php
 require(DIR_WS_LANGUAGES . $language . '/help/document_help.php') ;
 if ($help_id == '1') {  
define('HEADING_TITLE', HEADING_TITLE_01);
}
 if ($help_id == '2') {  
define('HEADING_TITLE', HEADING_TITLE_02);
}
if ($help_id == '3') {  
define('HEADING_TITLE', HEADING_TITLE_03);
}
 if ($help_id == '4') {  
define('HEADING_TITLE', HEADING_TITLE_04);
}
if ($help_id == '5') {  
define('HEADING_TITLE', HEADING_TITLE_05);
}

?>

    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0"> 
                         <tr>
                     <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                       <tr>
                         <td class="pageHeading"><?php echo HEADING_TITLE ; ?></td>
                        </tr>
                        <tr>
                        <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                       </tr>
                     </table></td>
                   </tr>
<tr class="attributeBoxContent">

 <td>
 <?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'  ; ?>
</td>
</tr>
  <tr>
    <td>
            <b><!-- Document Index --> <?php echo DOCUMENT_INDEX;?></b>
            <br><a href="<?php echo tep_href_link(FILENAME_DOCUMENT_HELP, '&help_id=1') . '">'. MENU_ITEM_01 . '</a>' ;?>
            <br><a href="<?php echo tep_href_link(FILENAME_DOCUMENT_HELP, '&help_id=2') . '">'. MENU_ITEM_02 . '</a>' ;?>
      <br><a href="<?php echo tep_href_link(FILENAME_DOCUMENT_HELP, '&help_id=3') . '">'. MENU_ITEM_03 . '</a>' ;?>
            <br><a href="<?php echo tep_href_link(FILENAME_DOCUMENT_HELP, '&help_id=4') . '">'. MENU_ITEM_04 . '</a>' ;?>
            <br><a href="<?php echo tep_href_link(FILENAME_DOCUMENT_HELP, '&help_id=5') . '">'. MENU_ITEM_05 . '</a>' ;?>
    </td>
   </tr>
    <tr>
        <td>
 <?php if ($help_id == '1') {  
include(DIR_WS_LANGUAGES . $language . '/help/doc/index.html') ;
}
 if ($help_id == '2') {  
include(DIR_WS_LANGUAGES . $language . '/help/doc/index2.html') ;
}
 if ($help_id == '3') {  
include(DIR_WS_LANGUAGES . $language . '/help/doc/index3.html') ;
}
 if ($help_id == '4') {  
include(DIR_WS_LANGUAGES . $language . '/help/doc/index4.html') ;
}

 if ($help_id == '5') {  
include(DIR_WS_LANGUAGES . $language . '/help/et/index5.html') ;
}


?>

</td>
  </tr>
<tr>
 <td>
&nbsp; &nbsp;  
              <?php echo tep_draw_form('return', FILENAME_EDIT_TEXT, '&lng=' . $lng, 'post', '', 'SSL');?>
              <?php echo tep_image_submit('button_return.gif', IMAGE_RETURN) ; ?>
                     </form>

 </td>
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


