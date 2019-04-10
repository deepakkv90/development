<?php
/*
  $Id: sss_validate.php,v 1.0.0.0 2008/05/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$error = (isset($_SESSION['validate_error']) && $_SESSION['validate_error'] == true) ? true : false;
$error_message = ($error == true) ? $_SESSION['validate_error']['error_message'] : '';
$action = (isset($_GET['action']) && ($_GET['action'] != '')) ? $_GET['action'] : ''; 
$_SESSION['force_registration'] = true;
$paction = (isset($_POST['action']) && ($_POST['action'] != '')) ? $_POST['action'] : '';
$serial = (isset($_POST['serial']) && ($_POST['serial'] != '')) ? tep_db_prepare_input($_POST['serial']) : '';
if (($paction == 'validate') || $serial && !$error) {
  // check for blank serial
    if ($serial == '') {
    $error = true;
    $error_message = TEXT_ERROR_BLANK_SERIAL;    
  }
  // validation logic  
  if (!$error) {
    // instantiate the serial class
    require_once(DIR_WS_CLASSES . 'sss_verify.php');
    $sss = new sss_verify;
    $verify_array = $sss->verifySerial($serial);
    if ($verify_array['verified'] == true) {
      if (isset($_SESSION['new_registration']) && $_SESSION['new_registration'] == true) {
        unset($_SESSION['new_registration']);
        if (isset($_SESSION['force_registration'])) unset($_SESSION['force_registration']);
        $_SESSION['verify_array'] = $verify_array;
        tep_redirect(FILENAME_SSS_REGISTER . '?action=confirm', '', 'SSL');
      } else {
        $_SESSION['continue'] = true;
        tep_redirect(FILENAME_DEFAULT, '', 'SSL');  
      }
    } else {
      $error = true;
      $error_message = $verify_array['error_message'];   
    }    
  }
}
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
          echo tep_draw_form('serial', FILENAME_SSS_VALIDATE, '', 'post', '', 'SSL') . tep_draw_hidden_field("action", "validate"); 
          ?>
         <tr>
            <td>
              <div class="head"><?php echo (($error == true) ? TEXT_TITLE_VALIDATION_ERROR : TEXT_TITLE_PRODUCT_REGISTRATION); ?></div>
              <div class="text"><?php echo (($error == true) ? TEXT_VALIDATION_ERROR1 : TEXT_PRODUCT_REGISTRATION_INFO1); ?></div>
              <div class="info"><?php echo (($error == true) ? TEXT_VALIDATION_ERROR2 : TEXT_PRODUCT_REGISTRATION_INFO2); ?></div>
              <table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="form-label"><?php echo TEXT_SERIAL_NUMBER; ?></td>
                  <td class="form-value">
                    <?php 
                    $input_serial = (isset($_SESSION['validate_error'])) ? $_SESSION['validate_error']['serial_1'] : $serial;
                    echo tep_draw_input_field('serial', $input_serial, 'id="serial", class="string"'); 
                    ?>  
                  </td>
                </tr>
                <?php
                // display errors
                if ($error == true) {
                  ?>
                  <tr>
                    <td class="form-label"></td>
                    <td class="form-value"><div class="error"><?php echo $error_message; ?></div></td>
                  </tr>
                  <?php
                }                  
                ?>       
                <tr>
                  <td class="form-label"></td>
                  <td class="form-value">
                  <div style="margin-top: .5em;"> 
                    <?php
                   // if ($error) {
                      if (eregi('expired', $error_message) || isset($_SESSION['is_std'])) {
                        echo '<input type="submit" class="cssButtonSubmit" value="' . TEXT_BUTTON_VALIDATE_SERIAL . '"/>&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_SSS_REGISTER, 'action=continue', 'SSL') . '">Continue to Administration</a>'; 
                      } else {
                        echo '<input type="submit" class="cssButtonSubmit" value="' . TEXT_BUTTON_VALIDATE_SERIAL . '"/>';
                      }
                  //  }
                    ?>                    
                  </div>
                  </td>
                  <?php
                  if (isset($_SESSION['validate_error'])) unset($_SESSION['validate_error']);
                  $error = false;
                  $error_message = '';
                  ?>
                </tr>
              </table>
            </td>
          </tr>
          </form>
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