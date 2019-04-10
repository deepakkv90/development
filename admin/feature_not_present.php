<?php
/*
  $Id: validate_New.php,v 1.1.1.1 2004/03/04 23:38:20 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

 THIS IS BETA - Use at your own risk!
  Step-By-Step Manual Order Entry Verion 0.5
  Customer Entry through Admin
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
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
    <td valign="top" class="page-container">

  <table border="0" cellpadding="0" cellspacing="0" style="margin: 1em auto; width: 600px;" align="center">
    <tr>
       <td></td>
       <td style="padding-bottom: 1em;" align="left"><a href="http://www.creloaded.com/products/shoppingcarts/" target="_blank"><img border="0" src="images/window-logo.png" /></a></td>
       <td></td>
    </tr>
     <tr>
       <td class="box-top-left">&nbsp;</td>
       <td class="box-top">&nbsp;</td>
       <td class="box-top-right" style="width: 48px;" >&nbsp;</td>
     </tr>
     <!--
     <tr>
        <td class="box-left">&nbsp;</td>
        <td style="background: #f0f8fc url(images/window-right.png) repeat-y right;" width="600" colspan="2">
          <img src="images/window-pro.png" align="right" /><img src="images/window-available.png" align="right" style="margin-right: 4px;" />
        </td>
     </tr>
     -->
     <tr>
        <td class="box-left">&nbsp;</td>
        <td class="box-content" valign="top">
          <div class="box-text" style="padding: 1em 0; line-height: 1.8em; font-size: 14px; color: #333;">
          <p>
          This feature is not available with your current CRE Loaded product.  If you'd like to upgrade, you'll get additional features, support, and flexibility.</p><p><a href="http://www.creloaded.com/products/shoppingcarts/creloadedpro/" target="_blank">CRE Loaded Professional</a> and <a href="http://www.creloaded.com/creloaded/products/shoppingcarts/creloadedb2b/" target="_blank">CRE Loaded Professional B2B</a> include a complete Content Management System, automatic URL search engine optimization and additional email and phone support.  Plus, they have increased capabilities with additional features such as one page checkout and abandoned cart recovery.
          </p>
          </div>
        </td> 
        <td class="box-right" style="width: 48px;">&nbsp;</td>
      </tr>
     <tr>
        <td class="box-left">&nbsp;</td>
        <td align="right" class="box-content" valign="bottom">
        <div style="padding-top: 1em;">
        <a href="http://www.creloaded.com/products/shoppingcarts/" target="_blank"><img src="images/window-more.png" /></a>
        </div>
        </td>
        <td class="box-right" style="width: 48px;">&nbsp;</td>
     </tr>
     <tr>
       <td class="box-bottom-left">&nbsp;</td>
       <td class="box-bottom">&nbsp;</td>
       <td class="box-bottom-right" style="width: 48px;">&nbsp;</td>
     </tr>
  </table>
      
    </td>
    <!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php
    require(DIR_WS_INCLUDES . 'footer.php');
?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>