<?php
/*
  $Id: data.php,v 2.0.0.0 2008/05/13 13:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

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
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="body-table">
  <tr>
  <!-- left_navigation //-->
  <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
  <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0" class="menuBoxHeading">
      <?php
      // RCI start
      echo $cre_RCI->get('data', 'listingtop');
      // RCI eof
      ?>     
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
      <tr>
        <td valign="top"><table width="70%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td colspan="2" class="main"><strong><?php echo WELCOME_TO_DATA_EXPORT_IMPORT_SYSTEM;?></strong></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo tep_draw_separator('pixel_black.gif', '100%', '1'); ?></td>
  </tr>
  <tr>
    <td width="50%" valign="top" class="main"><p style="font-weight:bold;"><?php echo TEXT_HELP_EASY_POPULATE;?></p>
      <ul>                                                                                                                       
        <li><?php echo '<a href="' . tep_href_link(FILENAME_DATA_HELP, 'help_id=1') . '">' . TEXT_EP_INTRO . '</a>'; ?></li>
        <li><?php echo '<a href="' . tep_href_link(FILENAME_DATA_HELP, 'help_id=2') . '">' . TEXT_EP_ADV_IMPORT . '</a>'; ?></li>
        <li><?php echo '<a href="' . tep_href_link(FILENAME_DATA_HELP, 'help_id=3') . '">' . TEXT_EP_ADV_EXPORT . '</a>'; ?></li>
        <li><?php echo '<a href="' . tep_href_link(FILENAME_DATA_HELP, 'help_id=4') . '">' . TEXT_EP_BASIC_IMPORT . '</a>'; ?></li>
        <li><?php echo '<a href="' . tep_href_link(FILENAME_DATA_HELP, 'help_id=5') . '">' . TEXT_EP_BASIC_EXPORT . '</a>'; ?></li>
        <li><?php echo '<a href="' . tep_href_link(FILENAME_DATA_HELP, 'help_id=6') . '">' . TEXT_EP_EDITING_FILE . '</a>'; ?></li>
    </ul></td>
    <!-- td width="50%" valign="top" class="main"><p style="font-weight:bold;"><?php echo TEXT_HELP_DATA_FEEDER_SYSTEM;?></p>
      <ul>
        <li><?php //echo '<a href="' . tep_href_link(FILENAME_DATA_HELP, 'help_id=9') . '">' . TEXT_DATA_INTRO . '</a>'; ?></li>
        <li><?php //echo '<a href="' . tep_href_link(FILENAME_DATA_HELP, 'help_id=10') . '">' . TEXT_DATA_FIRST_GOOGLE_FEED . '</a>'; ?></li>
        <li><?php //echo '<a href="' . tep_href_link(FILENAME_DATA_HELP, 'help_id=11') . '">' . TEXT_DATA_CONFIGURE_FEED . '</a>'; ?></li>
        <li><?php //echo '<a href="' . tep_href_link(FILENAME_DATA_HELP, 'help_id=12') . '">' . TEXT_DATA_RUN_FEED . '</a>'; ?></li>
    </ul></td -->
  </tr>
</table>
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