<?php
/*
  $Id: quickcom_help.php,v 1.2 2004/03/13 15:09:11 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html <?php echo HTML_PARAMS; ?>>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo QUICKCOM_HELP_TXT_1;?></title>
<script type="text/javascript" src="includes/prototype.js"></script> 
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="admin/includes/general.js"></script>
</head>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>  <!-- left_navigation //-->
  <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
  <!-- left_navigation_eof //-->
  <!-- body_text //-->
  <td class="page-container" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo QUICKCOM_HELP_TXT_2;?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
       <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
         <td class="main">

<div align="center"><h2><?php echo QUICKCOM_HELP_TXT_3;?></h2></div>
</td>
</tr>
<tr>
 <td align="center"> <?php echo QUICKCOM_HELP_TXT_4;?>
</td>
</tr>
<tr>
<td>
<hr align="center" size="4" width="450">
</td>
</tr>
<tr>
<td>
<?php echo QUICKCOM_HELP_TXT_5;?>
<hr align="center" size="4" width="450">
  </tr>
        </td></table>
          </tr>
         </td>

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
