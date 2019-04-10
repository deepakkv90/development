<?php
/*
  $Id: contact_us.php,v 1.1.1.1 2004/03/04 23:37:58 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONTACT_US);

  $error = false;
  if (isset($_GET['action']) && ($_GET['action'] == 'send')) {
    $name = tep_db_prepare_input($_POST['name']);
    $email_address = tep_db_prepare_input($_POST['email']);
    $enquiry = tep_db_prepare_input($_POST['enquiry']);

    $telephone = tep_db_prepare_input($_POST['telephone']);
    $company = tep_db_prepare_input($_POST['company']);
    $street = tep_db_prepare_input($_POST['street']);
    $city = tep_db_prepare_input($_POST['city']);
    $state = tep_db_prepare_input($_POST['state']);
    $postcode = tep_db_prepare_input($_POST['postcode']);
    $country = tep_db_prepare_input($_POST['country']);

    $topic = tep_db_prepare_input($_POST['topic']);
    $subject = tep_db_prepare_input($_POST['subject']);

    $urgent = (tep_db_prepare_input($_POST['urgent']) == 'on') ? '1' : '0';
    $self = (tep_db_prepare_input($_POST['self']) == 'on') ? '1' : '0';

    $urgent_string = '';
    if ($urgent == '1') {
      $urgent_string = '(' . TEXT_SUBJECT_URGENT . ')';
    }

    $subject_string = TEXT_SUBJECT_PREFIX . ' ' . $topic . ": " . $subject . $urgent_string;
    $message_string = $topic . ": " . $subject . "\n\n" .
      $enquiry . "\n\n" .
      ENTRY_COMPANY . ' ' . $company . "\n" .
      ENTRY_NAME . ' ' . $name . "\n" .
      ENTRY_EMAIL . ' ' . $email_address . "\n" .
      ENTRY_STREET_ADDRESS . ' ' . $street . "\n" .
      ENTRY_CITY . ' ' . $city . "\n" .
      ENTRY_STATE . ' ' . $state . "\n" .
      ENTRY_POST_CODE . ' ' . $postcode . "\n" .
      ENTRY_COUNTRY . ' ' . tep_get_country_name($country) . "\n" .
      ENTRY_TELEPHONE_NUMBER . ' ' . $telephone . "\n";

    $ipaddress = $_SERVER['REMOTE_ADDR'];
    $message_string .= "\n\n" . 'IP: ' . $ipaddress . "\n";

    if ($email_address == '') {
      $error = true;
      $messageStack->add('contact', ENTRY_EMAIL_ADDRESS_BLANK_ERROR);
      $subject = (isset($subject) && $subject != '' ? $subject : '');
      $enquiry = (isset($enquiry) && $enquiry != '' ? $enquiry : '');
      $name = (isset($name) && $name != '' ? $name : '');
    }

    if (!tep_validate_email($email_address) && $email_address != '') {
      $error = true;
      $messageStack->add('contact', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
      $subject = (isset($subject) && $subject != '' ? $subject : '');
      $enquiry = (isset($enquiry) && $enquiry != '' ? $enquiry : '');
      $name = (isset($name) && $name != '' ? $name : '');
      $email = "";
    }
  
    //VISUAL VERIFY CODE start
    if (defined('VVC_SITE_ON_OFF') && VVC_SITE_ON_OFF == 'On'){
    if (defined('VVC_CONTACT_US_ON_OFF') && VVC_CONTACT_US_ON_OFF == 'On'){
    $code_query = tep_db_query("select code from " . TABLE_VISUAL_VERIFY_CODE . " where oscsid = '" . tep_session_id() . "'");
    $code_array = tep_db_fetch_array($code_query);
    tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . tep_session_id() . "'"); //remove the visual verify code associated with this session to clean database and ensure new results
    if ( isset($_POST['visual_verify_code']) && tep_not_null($_POST['visual_verify_code']) && 
         isset($code_array['code']) &&  tep_not_null($code_array['code']) && 
         strcmp($_POST['visual_verify_code'], $code_array['code']) == 0) {   //make the check case sensitive
         //match is good, no message or error.
         } else {
        $error = true;
        $messageStack->add('contact', VISUAL_VERIFY_CODE_ENTRY_ERROR);
    }
  }
}
//VISUAL VERIFY CODE stop
  
    if(!$error){
           $mimemessage = new email(array('X-Mailer: osCommerce bulk mailer'));
           $mimemessage->add_html($message_string);
           $mimemessage->build_message();
           $mimemessage->send(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $name, $email_address, $subject_string);
      // email a copy to sender, if opted
    if ($self == '1') {
        //tep_mail($name, $email_address, $subject_string, $message_string, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
           $mimemessage = new email(array('X-Mailer: osCommerce bulk mailer'));
           $mimemessage->add_html($message_string);
           $mimemessage->build_message();
           $mimemessage->send($name, $email_address, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $subject_string);
      }
      tep_redirect(tep_href_link(FILENAME_CONTACT_US, 'action=success', 'SSL'));
    } 
 }
 
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CONTACT_US, '', 'SSL'));
  $content = CONTENT_CONTACT_US;
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
