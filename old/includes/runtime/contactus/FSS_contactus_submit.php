<?php
/*
  $Id: FSS_contactus_submit.php,v 1.0.0 2008/05/22 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/ 
global $error, $messageStack, $enquiry, $name, $email;
if (defined('MODULE_ADDONS_FSS_STATUS') && MODULE_ADDONS_FSS_STATUS == 'True') {   
  foreach ($_POST as $key => $value) {
    $$key = tep_db_prepare_input($value);
  }
  $urgent_string = '';
  if ($urgent == 'on') {
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
  ENTRY_COUNTRY . ' ' . $country . "\n" . 
  ENTRY_TELEPHONE_NUMBER . ' ' . $telephone . "\n";
  $ipaddress = $_SERVER["REMOTE_ADDR"];
  $message_string .= "\n\n" . 'IP: ' . $ipaddress . "\n";
  if (tep_validate_email($email_address)) {
    tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $subject_string, $message_string, $name, $email_address); 
    if ($self == 'on') {
      tep_mail($name, $email_address, $subject_string, $message_string, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS); 
    }
    tep_redirect(tep_href_link(FILENAME_CONTACT_US, 'action=success', 'SSL'));
  } else {
    $error = true;
    $messageStack->add('contact', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    $enquiry = "";
    $name = "";
    $email = "";      
  }
}
?>