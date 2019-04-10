<?php
/*
  $Id: affiliate_password_forgotten.php,v 1.1.1.1 2004/03/04 23:37:54 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 -2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_PASSWORD_FORGOTTEN);
  
    $error = false;
  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
    $email_address = strtolower(tep_db_prepare_input($_POST['email_address']));

    if ($email_address == '') {
      $error = true;
      $messageStack->add('password_forgotten', ENTRY_EMAIL_ADDRESS_BLANK_ERROR);
    }
    
    if (!tep_validate_email($email_address) && $email_address != '') {
      $error = true;
      $messageStack->add('password_forgotten', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    }
  
    //VISUAL VERIFY CODE start
  if (defined('VVC_SITE_ON_OFF') && VVC_SITE_ON_OFF == 'On'){
  if (defined('VVC_AFFILIATE_PASSWORD_FORGOT_ON_OFF') && VVC_AFFILIATE_PASSWORD_FORGOT_ON_OFF == 'On'){
    $code_query = tep_db_query("select code from " . TABLE_VISUAL_VERIFY_CODE . " where oscsid = '" . tep_session_id() . "'");
    $code_array = tep_db_fetch_array($code_query);
    tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . tep_session_id() . "'"); //remove the visual verify code associated with this session to clean database and ensure new results
    if ( isset($_POST['visual_verify_code']) && tep_not_null($_POST['visual_verify_code']) && 
         isset($code_array['code']) &&  tep_not_null($code_array['code']) && 
         strcmp($_POST['visual_verify_code'], $code_array['code']) == 0) {   //make the check case sensitive
         //match is good, no message or error.
         } else {
        $error = true;
        $messageStack->add('password_forgotten', VISUAL_VERIFY_CODE_ENTRY_ERROR);
    }
  }
}
//VISUAL VERIFY CODE stop

if(!$error){
    $check_affiliate_query = tep_db_query("select affiliate_firstname, affiliate_lastname, affiliate_password, affiliate_id from " . TABLE_AFFILIATE . " where affiliate_email_address = '" .  $email_address . "'");
    if (tep_db_num_rows($check_affiliate_query)) {
      $check_affiliate = tep_db_fetch_array($check_affiliate_query);
      // Crypted password mods - create a new password, update the database and mail it to them
      $newpass = tep_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
      $crypted_password = tep_encrypt_password($newpass);
      tep_db_query("update " . TABLE_AFFILIATE . " set affiliate_password = '" . $crypted_password . "' where affiliate_id = '" . $check_affiliate['affiliate_id'] . "'");
      
      tep_mail($check_affiliate['affiliate_firstname'] . " " . $check_affiliate['affiliate_lastname'],  $email_address, EMAIL_PASSWORD_REMINDER_SUBJECT, nl2br(sprintf(EMAIL_PASSWORD_REMINDER_BODY, $newpass)), STORE_OWNER, AFFILIATE_EMAIL_ADDRESS);
      tep_redirect(tep_href_link(FILENAME_AFFILIATE, 'info_message=' . urlencode(TEXT_PASSWORD_SENT), 'SSL', true, false));
    } else {
      $messageStack->add('password_forgotten', TEXT_NO_EMAIL_ADDRESS_FOUND);
    }
}
}

  $breadcrumb->add(NAVBAR_TITLE_AFFILIATE_CENTRAL, tep_href_link(FILENAME_AFFILIATE_CENTRAL));
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_PASSWORD_FORGOTTEN, '', 'SSL'));

  $content = CONTENT_AFFILIATE_PASSWORD_FORGOTTEN;
  $javascript = 'popup_window.js';
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');

?>