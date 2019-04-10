<?php
/*
  $Id: login.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
//tep_session_destroy();
unset($_SESSION['login_id']);
unset($_SESSION['login_firstname']);
unset($_SESSION['login_groups_id']);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<style>
body {
  background-color: #fff !important;
  width: 100%;
  height: 100%;
}
img {
  border: 0;
}
</style>
</head>
<body onload="document.getElementById('email_address').focus()">
<table border="0" cellpadding="0" cellspacing="0" width="400" style="margin: 100px auto;">
  <tr>
    <td></td>
    <td align="left"><a href="http://namebadgesinternational.com.au" target="_blank"><img src="images/window-logo.png" width="82" height="86" /></a></td>
    <td></td>
  </tr>
  <tr>
    <td class="box-top-left">&nbsp;</td>
    <td class="box-top">&nbsp;</td>
    <td class="box-top-right">&nbsp;</td>
  </tr>
  <tr>
    <td class="box-left">&nbsp;</td>
    <td class="box-content">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td style="padding-bottom: 1em;" valign="top"><img src="images/window-login.png" /></td>
          <td style="padding-bottom: 1em;" rowspan="2" align="right"></td>
        </tr>
        <tr>
          <td style="padding-bottom: 1em;" valign="top"><?php echo TEXT_MAIN; ?></td>
        </tr>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td align="left"><?php echo '<a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '">' . TEXT_RELOGIN . '</a>'; ?></td>
          <td align="right"><?php echo '<a href="../index.php">' . TEXT_VIEW_CATALOG . '</a>'; ?></td>
        </tr>
      </table>
    </td>
    <td class="box-right">&nbsp;</td>
  </tr>
  <tr>
    <td class="box-bottom-left">&nbsp;</td>
    <td class="box-bottom">&nbsp;</td>
    <td class="box-bottom-right">&nbsp;</td>
  </tr>
  <tr>
    <td></td>
   
    <td></td>
  </tr>
</table>
<?php
require('includes/application_bottom.php');
?>
</body>
</html>