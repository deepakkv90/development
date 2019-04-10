<?php
/*
  $Id: login.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);

  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
    $email_address = tep_db_prepare_input($_POST['email_address']);
    $log_times = (isset($_POST['log_times']) ? $_POST['log_times']+1 : 1);
  if ( $log_times >= 4 ) {
      $_SESSION['password_forgotten'] = true;
    }

// Check if email exists
    $check_admin_query = tep_db_query("select admin_id as check_id, admin_firstname as check_firstname, admin_lastname as check_lastname, admin_email_address as check_email_address from " . TABLE_ADMIN . " where admin_email_address = '" . tep_db_input($email_address) . "'");
    if (!tep_db_num_rows($check_admin_query)) {
      $_GET['login'] = 'fail';
    } else {
      $check_admin = tep_db_fetch_array($check_admin_query);
      $_GET['login'] = 'success';
      $makePassword = tep_create_hard_pass();
      
      tep_mail($check_admin['check_firstname'] . ' ' . $check_admin['admin_lastname'], $check_admin['check_email_address'], ADMIN_EMAIL_SUBJECT, sprintf(ADMIN_EMAIL_TEXT, $check_admin['check_firstname'], HTTP_SERVER . DIR_WS_ADMIN, $check_admin['check_email_address'], $makePassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      tep_db_query("update " . TABLE_ADMIN . " set admin_password = '" . tep_encrypt_password($makePassword) . "' where admin_id = '" . $check_admin['check_id'] . "'");
      }
    }
  
    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
  if  (isset($_GET['login']) && $_GET['login'] == 'success' ) {
    $success_message = TEXT_FORGOTTEN_SUCCESS;
  } elseif  (isset($_GET['login']) && $_GET['login'] == 'fail' ) {
    $info_message = TEXT_FORGOTTEN_ERROR;
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
</head>
<body>



<table border="0" cellpadding="0" cellspacing="0" width="600" style="margin: 100px auto;">
   <tr>
      <td></td>
      <td style="padding-bottom: 1em;" align="left"><a href="http://www.namebadgesinternational.com.au" target="_blank"><img src="images/window-logo.png" alt="view front end" width="82" height="86" /></a></td>
     <td></td>
   </tr>
   <tr>
      <td class="box-top-left">&nbsp;</td><td class="box-top">&nbsp;</td><td class="box-top-right">&nbsp;</td>
   </tr>
   <tr>
      <td class="box-left">&nbsp;</td>
      <td class="box-content">
        <?php echo tep_draw_form('login', FILENAME_PASSWORD_FORGOTTEN, 'action=process');?>
        <table border="0" cellpadding="0" cellspacing="0">
        <?php
        if (isset($_SESSION['password_forgotten'])) {
          ?>
          <tr><td colspan="2"><h2 class="message"><?php echo TEXT_FORGOTTEN_FAIL; ?></h2></td></tr>
          <tr><td colspan="2"><span class="forgot"><?php echo TEXT_FORGOTTEN_SUPPORT_MESSAGE; ?></span></td></tr>
          <?php
          $success_message = '';
        } elseif (isset($success_message)) {
          $success_message = TEXT_FORGOTTEN_SUCCESS . '<br><br><a href="' . tep_href_link(FILENAME_LOGIN, '' , 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
        } else {
          if (isset($info_message)) {
            echo '<tr><td colspan="2"><div class="message">' . $info_message . '</div></td></tr>' . tep_draw_hidden_field('log_times', $log_times);
          } else {
            echo tep_draw_hidden_field('log_times', '0');
          }
        }
        if (!isset($success_message) && !isset($_SESSION['password_forgotten'])){
          ?>
          <tr>
            <td colspan="2"><h3><?php echo TEXT_PASSWORD_FORGOTTEN_TITLE;?></h3></td>
          </tr>
          <tr>
            <td colspan="2"><p><?php echo TEXT_FORGOTTEN_USER_MESSAGE;?></p></td>
          </tr>
          <tr>
            <td class="form-label"><label for="email_address"><?php echo ENTRY_EMAIL_ADDRESS; ?></label></td><td class="form-value"><?php echo tep_draw_input_field('email_address','','id="email_address" class="string"'); ?></td>
          </tr>
          <tr>
            <td class="form-label"></td>
            <td class="form-value button-container">
              <input type="submit" name="button" id="button" class="cssButtonSubmit" value="<?php echo IMAGE_SEND;?>" />
              <a href="./index.php"><?php echo IMAGE_CANCEL;?></a>
            </td>
          </tr>
        <?php 
        } else {
          ?>
          <tr>
            <td>
              <?php echo $success_message; ?>
            </td>
          </tr>
          <?php
        }
        ?>
        </table>
        </form>
      </td>
      <td class="box-right">&nbsp;</td>
   </tr>
   <tr>
      <td class="box-bottom-left">&nbsp;</td><td class="box-bottom">&nbsp;</td><td class="box-bottom-right">&nbsp;</td>
   </tr>
   <tr>
      <td></td>
      <td align="left" style="font-size: 11px; color: #444;">&nbsp;</td>
     <td></td>
   </tr>
</table>
<?php
require('includes/application_bottom.php');
?>
</body>
</html>
