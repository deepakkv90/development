<?php
/*
  $Id: popup_get_loaded.php,v 1.0.0.0 2008/06/16 23:38:52 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$page = (isset($_GET['page'])) ? $_GET['page'] : '';
if ($page == 'login') {
  if (isset($_SESSION['from_login'])) unset($_SESSION['from_login']);
   $email_address = isset($_POST['email_address']) ? $_POST['email_address'] : '';
   $password = isset($_POST['password']) ? $_POST['password'] : '';
  ?>
  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
  <html> 
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
  <title>Get CRE Loaded</title>
  <script type="text/javascript" src="includes/prototype.js"></script> 
  <link href="includes/stylesheet.css" rel="stylesheet" type="text/css" />
  <style>
  body {
     background-color: #fff !important;
     width: 100%;
     height: 100%;
  }
  </style>
  <body>
  <table border="0" cellpadding="0" cellspacing="0" width="600" style="margin: 100px auto;">
    <tr>
       <td></td>
       <td style="padding-bottom: 1em;" align="left"><a href="http://www.creloaded.com/" target="_blank"><img border="0" src="images/window-logo.png" /></a></td>
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
          <table border="0" cellpadding="0" cellspacing="0">
             <tr>
                <td>
                   <iframe name="fr1" src="messages.php?s=login" scrolling="no" allowtransparency="true" frameborder="0" marginheight="0" marginwidth="0" height="200" width="550" align="center"></iframe>
                    <div style="margin-top: 1em;">
                      <a href="<?php echo tep_href_link(FILENAME_DEFAULT, '', 'SSL'); ?>"><?php echo IMAGE_BUTTON_CONTINUE; ?></a>
                    </div>
                </td>
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
  </table>
  </body>
  </html>
  <?php
} else if ($page == 'new_admin_member' || $page == 'new_admin_group') {
  ?>
  <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto; width: 600px;" align="center">
     <tr>
       <td class="box-top-left">&nbsp;</td>
       <td class="box-top">&nbsp;</td>
       <td class="box-top-right" style="width: 48px;" >&nbsp;</td>
     </tr>
     <tr>
        <td class="box-left"">&nbsp;</td>
        <td style="background: #f0f8fc url(images/window-right.png) repeat-y right;" width="600" colspan="2">
          <?php 
        if ($page == 'new_admin_member') {
          ?>
            <img src="images/window-multi-admin.png" align="left" />
            <?php 
        } else if ($page == 'new_admin_group') { 
          ?>
            <img src="images/window-admin-groups.png" align="left" />
            <?php 
        } 
        ?>
          <img src="images/window-pro.png" align="right" /><img src="images/window-available.png" align="right" style="margin-right: 4px;"   />
        </td>
     </tr>
     <tr>
        <td class="box-left">&nbsp;</td>
        <td class="box-content" valign="top">
          <div class="box-text" style="padding: 1em 0; line-height: 1.8em; font-size: 14px; color: #333;">
          <?php if ($page == 'new_admin_member') { ?>
Multiple Administrators allow unique admin access for you, your webmaster, your staff and anyone else you want.  Upgrade to CRE Pro and make running your online business easier.
          <?php } else if ($page == 'new_admin_group') { ?>
Multiple Admin Groups allow you control over user access. Decide who gets access to orders and shipping and who can change the templates.  Upgrade to CRE Pro and stay in control of who can do what.         
          <?php } ?>
          </div>
        </td> 
        <td class="box-right" style="width: 48px;">&nbsp;</td>
      </tr>
     <tr>
        <td class="box-left">&nbsp;</td>
        <td align="right" class="box-content" valign="bottom">
        <div style="padding-top: 1em;">
        <a href="javascript:void(0)" onclick="hideNewAdminMember()">No Thanks</a>&nbsp;&nbsp;
        <a href="http://www.creloaded.com/" onclick="hideNewAdminMember()" target="_blank"><img src="images/window-more.png" /></a>
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
  <?php
} else {
  echo 'Feature not available in Standard version.';
}
?>