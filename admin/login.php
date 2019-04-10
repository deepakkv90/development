<?php
/*
  $Id: login.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  if ($session_started == false) {
    echo 'session not started';
  }
  $error = false;
  if ( (isset($_POST['action']) && ($_POST['action'] == 'process')) || (isset($_POST['password']) && isset($_POST['email_address'])) ) {
    $email_address = tep_db_prepare_input($_POST['email_address']);
    $password = tep_db_prepare_input($_POST['password']);
    // Check if email exists
    $check_admin_query = tep_db_query("select admin_id as login_id, admin_groups_id as login_groups_id, admin_firstname as login_firstname, admin_email_address as login_email_address, admin_password as login_password, admin_modified as login_modified, admin_logdate as login_logdate, admin_lognum as login_lognum from " . TABLE_ADMIN . " where admin_email_address = '" . tep_db_input($email_address) . "'");
    if (!tep_db_num_rows($check_admin_query)) {
      $_POST['login'] = 'fail';
    } else {
      $check_admin = tep_db_fetch_array($check_admin_query);
      // Check that password is good
      if (!tep_validate_password($password, $check_admin['login_password'])) {
        $_POST['login'] = 'fail';
      } else {
        if (isset($_SESSION['password_forgotten'])) {
          unset($_SESSION['password_forgotten']);
        }
        $login_email_address = $check_admin['login_email_address'];
        $login_logdate = $check_admin['login_logdate'];
        $login_lognum = $check_admin['login_lognum'];
        $login_modified = $check_admin['login_modified'];
        $_SESSION['login_id'] = $check_admin['login_id'];
        $_SESSION['login_groups_id'] = $check_admin['login_groups_id'];
        $_SESSION['login_firstname'] = $check_admin['login_firstname'];
        //$date_now = date('Ymd');
        tep_db_query("update " . TABLE_ADMIN . " set admin_logdate = now(), admin_lognum = admin_lognum+1 where admin_id = '" . $_SESSION['login_id'] . "'");
        $_SESSION['from_login'] = true;
        if (sizeof($navigation->snapshot) > 0) {
          $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
          $navigation->clear_snapshot();
          tep_redirect($origin_href);
        } else {
          tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'SSL'));
        }
      }
    }
  }
  $password = (isset($_GET['password'])) ? $_GET['password'] : '';
  $email_address = (isset($_GET['email_address'])) ? $_GET['email_address'] : '';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<style type="text/css">
body {
  background-color: #fff !important;
  width: 100%;
  height: 100%;
}
</style>
</head>
<body onload="document.getElementById('email_address').focus()">
<table border="0" cellpadding="0" cellspacing="0" width="400" style="margin: 100px auto;">
   <tr>
      <td></td>
      <td align="left"><a href="<?php echo HTTPS_SERVER; ?>" target="_blank"><img src="images/window-logo.png" width="82" height="86" /></a></td>
     <td></td>
   </tr>
   <tr>
      <td class="box-top-left">&nbsp;</td><td class="box-top">&nbsp;</td><td class="box-top-right">&nbsp;</td>
   </tr>
   <tr>
      <td class="box-left">&nbsp;</td>
      <td class="box-content">
         <table border="0" cellpadding="0" cellspacing="0">
            <tr>
               <td colspan="2" style="padding-bottom: 1em;" align="left"><img src="images/window-login.png" /></td>
            </tr>
            <?php
            if (isset($_POST['login']) && $_POST['login'] == 'fail') {
               $info_message = TEXT_LOGIN_ERROR;
            }
            if (isset($info_message)) {
              ?>
              <tr>
                 <td colspan="2"><?php echo tep_image(DIR_WS_ICONS . 'warning.gif','Warning') . $info_message; ?></td>
              </tr>
              <?php 
            }
            echo tep_draw_form('login', FILENAME_LOGIN, '', 'post', '', 'SSL') . tep_draw_hidden_field("action","process"); 
            ?>
            <tr>
               <td class="form-label"><label for="email_address"><?php echo ENTRY_EMAIL_ADDRESS; ?></label></td>
               <td class="form-value">
                  <?php echo tep_draw_input_field('email_address', $email_address, 'id="email_address" class="string short"'); ?>
               </td>
            </tr>
            <tr>
               <td class="form-label"><label for="password"><?php echo ENTRY_PASSWORD; ?></label></td>
               <td class="form-value">
                  <?php echo tep_draw_password_field('password', $password, false, 'class="string short"'); ?>
               </td>
            </tr>
            <tr>
               <td class="form-label"></td>
               <td class="form-value">
                  <?php
                  echo '<input type="submit" name="button" id="button" class="cssButtonSubmit" value="Login" />';
                  ?>
               </td>
            </tr>
            <tr>
               <td class="form-label"></td>
               <td class="form-value">
                  <?php
                  echo '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>';
                  ?>
               </td>
            </tr>
            </form>
         </table>
      </td>
      <td class="box-right">&nbsp;</td>
   </tr>
   <tr>
      <td class="box-bottom-left">&nbsp;</td><td class="box-bottom">&nbsp;</td><td class="box-bottom-right">&nbsp;</td>
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