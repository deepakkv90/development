<?php
/*
  $Id: sss_register.php,v 1.0.0.0 2008/05/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$action = (isset($_GET['action']) && ($_GET['action'] != '')) ? $_GET['action'] : '';
if ($action == 'continue') {
  $_SESSION['continue'] = true;
  tep_redirect(FILENAME_DEFAULT, '', 'SSL');
}
$paction = (isset($_POST['action']) && ($_POST['action'] != '')) ? $_POST['action'] : ''; 
$error = (isset($_SESSION['update_error']) && $_SESSION['update_error'] == true) ? true : false;
$error_message = ($error == true) ? $_SESSION['update_error']['error_message'] : ''; 
// if no owner info present = HAI installation, redirect to ID input screen.
if (trim(str_replace(', ', '', $_SESSION['verify_array']['owner_name'])) == '') {
  tep_redirect(FILENAME_SSS_REGISTER . '?action=update', '', 'SSL');
}
if ($paction == 'update' && !$error) {
  $action = 'update';
  // check for blank email
  $email_address = tep_db_prepare_input($_POST['email_address']);
  $password = tep_db_prepare_input($_POST['password']); 
  if ($email_address == '' || $password == '') {
    $error = true;
    $error_message = TEXT_ERROR_BLANK;    
  }
  if (!$error) {
    // validate and update
    // instantiate the serial class
    require_once(DIR_WS_CLASSES . 'sss_verify.php');
    $sss = new sss_verify;
    $serial = $_SESSION['verify_array']['serial_1'];
    $return = $sss->updateRegistration($email_address, $password, $serial); 
    if (eregi('Error', $return['return_code'])) {
      $error = true;
      $error_message = $return['error_message'];
      $_SESSION['update_error'] = $return;
      tep_redirect(FILENAME_SSS_REGISTER, '', 'SSL'); 
    }
    // redirect to update registration complete page
    tep_redirect(FILENAME_SSS_REGISTER . '?action=complete', '', 'SSL');
  } else {
    // redirect to update error page
    $_SESSION['update_error']['error_message'] = $error_message;
    tep_redirect(FILENAME_SSS_REGISTER . '?action=update', '', 'SSL');
     
  }
} 
if ($error) $action = 'error'; 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
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
div.head {
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 1em;
}
div.text {
  font-size: 12px;
  margin-bottom: 1em;
  line-height: 1.5em;
}
div.info {
  color: #444;
  font-size: 11px;
  margin-bottom: 1em;
  line-height: 1.5em;
}
a.cancel {
  font-size: 11px;
}
</style>
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" width="600" style="margin: 100px auto;">
   <tr>
     <td></td>
     <td style="padding-bottom: 1em;" align="left"><a href="http://www.creloaded.com/" target="_blank"><img src="images/window-logo.png" /></a></td>
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
          <?php
          switch ($action) {
            case 'confirm' :
              $text  = '<tr>' . "\n";
              $text .= '  <td>' . "\n";
              $text .= '    <div class="head">' . TEXT_TITLE_PRODUCT_REGISTRATION . '</div>' . "\n";
              $text .= '    <div class="text">' . TEXT_PRODUCT_REGISTRATION_INFO1 . '</div>' . "\n";
              $text .= '    <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 1em;">' . "\n";
              $text .= '      <tr>' . "\n";
              $text .= '        <td class="form-label"></td>' . "\n";
              $text .= '        <td class="form-value" style="font-weight: bold;">' . $_SESSION['verify_array']['owner_name'] . '</td>' . "\n";
              $text .= '      </tr>' . "\n";
              $text .= '    </table>' . "\n";
              $text .= '    <div class="text">' . TEXT_PRODUCT_REGISTRATION_INFO2 . '</div>' . "\n";
              $text .= '    <div class="info">' . TEXT_PRODUCT_REGISTRATION_INFO3 . '</div>' . "\n";
              $text .= '    <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 1em;">' . "\n";
              $text .= '      <tr>' . "\n";
              $text .= '        <td class="form-label"></td>' . "\n";
              $text .= '        <td class="form-value">' . "\n";
              $text .= '          <div class="button-container">' . "\n";
              $text .= tep_draw_form('update', FILENAME_SSS_REGISTER, 'action=update', 'post', '', 'SSL') . tep_draw_hidden_field("action", "update"); 
              $text .= '          <a style="text-decoration: none !important;" href="' . tep_href_link(FILENAME_SSS_REGISTER, 'action=update', 'SSL') . '">' . tep_image_submit('button_update.gif', TEXT_BUTTON_UPDATE_REGISTRATION) . '</a>';
              $text .= '          </form>' . "\n";
              $text .= tep_draw_form('confirm', FILENAME_SSS_REGISTER, 'action=complete', 'post', '', 'SSL') . tep_draw_hidden_field("action", "confirm"); 
              $text .= '          <a style="text-decoration: none !important;" href="' . tep_href_link(FILENAME_SSS_REGISTER, 'action=complete', 'SSL') . '">' . tep_image_submit('button_continue.gif', TEXT_BUTTON_CONTINUE) . '</a>';
              $text .= '          </form>' . "\n";
              $text .= '          </div>' . "\n";
              $text .= '        </td>' . "\n";
              $text .= '      </tr>' . "\n";
              $text .= '    </table>' . "\n";
              $text .= '  </td>' . "\n";
              $text .= '</tr>' . "\n";
              break;
            case 'complete' :
              $text  = '<tr>' . "\n";
              $text .= '  <td>' . "\n";
              $text .= '    <div class="head">' . TEXT_TITLE_REGISTRATION_COMPLETE . '</div>' . "\n";
              $text .= '    <p>' . TEXT_REGISTRATION_COMPLETE1 . '</p>' . "\n"; 
              $text .= '    <table>' . "\n"; 
              if (isset($_SESSION['verify_array']['store_id']) && $_SESSION['verify_array']['store_id'] != '') {
                $text .= '      <tr>' . "\n"; 
                $text .= '        <td class="form-label" style="font-weight: bold;">' . TEXT_STORE_ID . '</td><td class="form-value">' . $_SESSION['verify_array']['store_id'] . '</td>' . "\n"; 
                $text .= '      </tr>' . "\n"; 
              }
              $text .= '      <tr>' . "\n"; 
              $text .= '        <td class="form-label" style="font-weight: bold;">' . TEXT_OWNER_INFO . '</td><td class="form-value">' . $_SESSION['verify_array']['owner_name']  . '</td>' . "\n"; 
              $text .= '      </tr>' . "\n"; 
              $text .= '      <tr>' . "\n"; 
              $text .= '        <td class="form-label" style="font-weight: bold;">' . TEXT_BILLABLE_INFO . '</td><td class="form-value">' . $_SESSION['verify_array']['billable_name']  . '</td>' . "\n"; 
              $text .= '      </tr>' . "\n"; 
              $text .= '    </table>' . "\n"; 
              $text .= '    <p>' . TEXT_REGISTRATION_COMPLETE2 . '</p>' . "\n"; 
              $text .= '    <table>' . "\n";
              $text .= '      <tr>' . "\n"; 
              $text .= '        <td class="form-label"></td>' . "\n";
              $text .= '        <td class="form-value">' . "\n";
              $text .= '          <div class="button-container">' . "\n";
              $text .= tep_draw_form('confirm', FILENAME_SSS_REGISTER, 'action=continue', 'post', '', 'SSL') . tep_draw_hidden_field("action", "continue"); 
              $text .= '          <a style="text-decoration: none !important;" href="' . tep_href_link(FILENAME_SSS_REGISTER, 'action=continue', 'SSL') . '">' . tep_image_submit('button_continue.gif', TEXT_BUTTON_CONTINUE) . '</a>';
              $text .= '          </div>' . "\n";
              $text .= '          </form>' . "\n";              
              $text .= '        </td>' . "\n";  
              $text .= '      </tr>' . "\n";                                                                                              
              $text .= '    </table>' . "\n"; 
              $text .= '  </td>' . "\n";
              $text .= '</tr>' . "\n";                                             
              break;   
            case 'update' :
            case 'error' :
              $text  = '<tr>' . "\n";
              $text .= '  <td>' . "\n";
              $text .= '    <div class="head">' . TEXT_TITLE_PRODUCT_REGISTRATION . '</div>' . "\n"; 
              $text .= '    <div class="text">' . TEXT_REGISTER_INFO1 . '</div>' . "\n"; 
              $text .= '    <div class="info">' . TEXT_REGISTER_INFO2 . '</div>' . "\n"; 
              $text .= '    <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 1em;">' . "\n"; 
              $text .= tep_draw_form('update', FILENAME_SSS_REGISTER, '', 'post', '', 'SSL') . tep_draw_hidden_field("action", "update"); 
              $text .= '      <tr>' . "\n"; 
              $text .= '        <td class="form-label">' . TEXT_USERNAME . '</td>' . "\n"; 
              $text .= '        <td class="form-value">' . tep_draw_input_field('email_address', $email_address, 'id="email_address", class="string"') . '</td>' . "\n"; 
              $text .= '      </tr>' . "\n"; 
              $text .= '      <tr>' . "\n"; 
              $text .= '        <td class="form-label">' . TEXT_PASSWORD . '</td>' . "\n"; 
              $text .= '        <td class="form-value">' . tep_draw_password_field('password', $password, false, 'class="string"') . '</td>' . "\n"; 
              $text .= '      </tr>' . "\n"; 
              if ($error) {
                $text .= '      <tr>' . "\n"; 
                $text .= '        <td class="form-label"></td>' . "\n"; 
                $text .= '        <td class="form-value"><div class="error">' . $error_message . '</div></td>' . "\n"; 
                $text .= '      </tr>' . "\n"; 
                if (isset($_SESSION['update_error'])) unset($_SESSION['update_error']);
                $error = false;
                $error_message = '';
              }
              $text .= '      <tr>' . "\n"; 
              $text .= '        <td class="form-label"></td>' . "\n"; 
              $text .= '        <td class="form-value">' . "\n"; 
              $text .= '          <div style="margin-top: .5em;">' . "\n"; 
              $text .= '          <input type="submit" class="cssButtonSubmit" value="' . TEXT_BUTTON_UPDATE_REGISTRATION . '" />' . "\n"; 
              $text .= '          </form>' . "\n";
              $text .= tep_draw_form('complete', FILENAME_SSS_REGISTER, 'action=complete', 'post', '', 'SSL') . tep_draw_hidden_field("action", "complete"); 
              $text .= '          <a href="' . tep_href_link(FILENAME_SSS_REGISTER, 'action=complete', 'SSL') . '">' . TEXT_CONTINUE_WITHOUT_UPDATE . '</a>'; 
              $text .= '          </form>' . "\n"; 
              $text .= '          </div>' . "\n"; 
              $text .= '        </td>' . "\n"; 
              $text .= '      </tr>' . "\n"; 
              $text .= '    </table>' . "\n"; 
              $text .= '  </td>' . "\n";
              $text .= '</tr>' . "\n";
              break;
            case 'grace' :
              $text  = '<tr>' . "\n";
              $text .= '  <td>' . "\n";
              $text .= '    <div class="head">' . TEXT_TITLE_EXPIRED_SERIAL . '</div>' . "\n";
              $text .= '    <div class="text">' . TEXT_EXPIRED_INFO1 . '</div>' . "\n";
              $text .= '    <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 1em;">' . "\n"; 
              $text .= '      <tr>' . "\n"; 
              $grace_days = (isset($_GET['id'])) ? (int)$_GET['id'] : 0;
              $text .= '        <td class="form-label"></td><td class="form-value"><div class="notice">' . sprintf(TEXT_EXPIRED_INFO2, $grace_days) . '</div></td>' . "\n"; 
              $text .= '      </tr>' . "\n"; 
              $text .= '    </table>' . "\n"; 
              $text .= '    <div class="text">' . TEXT_EXPIRED_INFO3 . '</div>' . "\n";  
              $text .= '    <div class="info">' . TEXT_EXPIRED_INFO4 . '</div>' . "\n"; 
              $text .= '    <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 1em;">' . "\n"; 
              $text .= '      <tr>' . "\n"; 
              $text .= '        <td class="form-label"></td>' . "\n";
              $text .= '        <td class="form-value">' . "\n";
              $text .= '          <div class="button-container">' . "\n";
              $text .= tep_draw_form('continue', FILENAME_SSS_REGISTER, 'action=continue', 'post', '', 'SSL') . tep_draw_hidden_field("action", "continue"); 
              $text .= '          <a style="text-decoration: none !important;" href="' . tep_href_link(FILENAME_SSS_REGISTER, 'action=continue', 'SSL') . '">' . tep_image_submit('button_continue.gif', TEXT_BUTTON_CONTINUE) . '</a>';
              $text .= '          </form>' . "\n"; 
              $text .= '          </div>' . "\n";              
              $text .= '        </td>' . "\n";  
              $text .= '      </tr>' . "\n"; 
              $text .= '    </table>' . "\n"; 
              $text .= '  </td>' . "\n";
              $text .= '</tr>' . "\n";
              break;                
              
          }
          echo $text;
          ?>
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
<?php
require('includes/application_bottom.php');
?>
</body>
</html>