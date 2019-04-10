<?php
/*
  $Id: create_account_success.php,v 1.1.1.1 2004/03/04 23:38:21 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  THIS IS BETA - Use at your own risk!
  Step-By-Step Manual Order Entry Verion 0.5
  Customer Entry through Admin
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT_SUCCESS);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
  <title><?php echo TITLE ?></title>
  <script type="text/javascript" src="includes/prototype.js"></script> 
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td valign="top" class="main"><div align="center" class="pageHeading"><?php echo HEADING_TITLE; ?></div><br><?php echo TEXT_ACCOUNT_CREATED; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <table BORDER=0 CELLPADDING=0 WIDTH=80%>
            <?php
            // RCO start
            if ($cre_RCO->get('createaccountsuccess', 'buttoncreateorder') !== true) {
              ?>
              <tr>
                <td align="right"><?php echo BUTTON_TITLE1;?></td>
                <td WIDTH=10>&nbsp;</td>
                <td align="left">
                  <?php echo '<a href="' . tep_href_link(FILENAME_CREATE_ORDER, '', 'SSL') . '">' . tep_image_button('button_create_order.gif', IMAGE_BUTTON_CREATE_ORDER) . '</a>'; ?>
                </td>
              </tr>
              <?php
            }
            // RCO eof
            ?>
            <tr>
              <td>&nbsp;</td>
              <td WIDTH=10>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="right"><?php echo BUTTON_TITLE2; ?></td>
              <td WIDTH=10>&nbsp;</td>
              <td>
                <?php echo '<a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '">' . tep_image_button('button_create_customer.gif', IMAGE_BUTTON_CREATE_CUSTOMER) . '</a>'; ?>
              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td WIDTH=10>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="right"><?php echo BUTTON_TITLE3; ?></td>
              <td WIDTH=10>&nbsp;</td>
              <td>
                <?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'SSL') . '">' . tep_image_button('button_admin_home.gif', IMAGE_BUTTON_ADMIN_HOME) . '</a>'; ?>
               </td>
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