<?php
/*
  $Id: tell_a_friend.php,v 1.1.1.1 2008/06/29 23:38:03 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_TELL_A_FRIEND);
//check for valid product
$valid_product = "false";
$tell_products_id = (isset($_GET['products_id']) && $_GET['products_id'] != '') ? (int)$_GET['products_id'] : 0;
if (!isset($_SESSION['customer_id']) && (ALLOW_GUEST_TO_TELL_A_FRIEND == 'false')) {
  $navigation->set_snapshot();
  tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
} elseif (isset($_SESSION['customer_id'])) {
  $account_query = tep_db_query("SELECT customers_firstname, customers_lastname, customers_email_address 
                                   from " . TABLE_CUSTOMERS . " 
                                 WHERE customers_id = '" . (int)$_SESSION['customer_id'] . "'");
  $account = tep_db_fetch_array($account_query);
  $from_name = $account['customers_firstname'] . ' ' . $account['customers_lastname'];
  $from_email_address = $account['customers_email_address'];
}
$product_info_query = tep_db_query("SELECT pd.products_name 
                                      from " . TABLE_PRODUCTS . " p, 
                                           " . TABLE_PRODUCTS_DESCRIPTION . " pd 
                                    WHERE p.products_status = '1' 
                                      and p.products_id = '" . $tell_products_id . "' 
                                      and p.products_id = pd.products_id 
                                      and pd.language_id = '" . (int)$languages_id . "'");
if (tep_db_num_rows($product_info_query)) {
  $valid_product = "true";
  $product_info = tep_db_fetch_array($product_info_query);
} else{
  $valid_product = "false";
  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $tell_products_id));
}
$error = false;
if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
  $_POST['to_email_address'] = preg_replace( "/\n/", " ", $_POST['to_email_address'] );
  $_POST['to_name'] = preg_replace( "/\n/", " ", $_POST['to_name'] );
  $_POST['to_email_address'] = preg_replace( "/\r/", " ", $_POST['to_email_address'] );
  $_POST['to_name'] = preg_replace( "/\r/", " ", $_POST['to_name'] );
  $_POST['to_email_address'] = str_replace("Content-Type:","",$_POST['to_email_address']);
  $_POST['to_name'] = str_replace("Content-Type:","",$_POST['to_name']);
  $to_email_address = strtolower(tep_db_prepare_input($_POST['to_email_address']));
  $to_name = tep_db_prepare_input($_POST['to_name']);
  $from_email_address = strtolower(tep_db_prepare_input($_POST['from_email_address']));
  $from_name = tep_db_prepare_input($_POST['from_name']);
  $message = tep_db_prepare_input($_POST['message']);
  if (empty($from_name)) {
    $error = true;
    $messageStack->add('friend', ERROR_FROM_NAME);
  }
  if ($from_email_address == '') {
    $error = true;
    $messageStack->add('friend', ENTRY_EMAIL_ADDRESS_BLANK_ERROR);
  }
  if (!tep_validate_email($from_email_address) && $from_email_address != '') {
    $error = true;
    $messageStack->add('friend', ERROR_FROM_ADDRESS);
  }
  if (empty($to_name)) {
    $error = true;
    $messageStack->add('friend', ERROR_TO_NAME);
  }
  if (!tep_validate_email($to_email_address)) {
    $error = true;
    $messageStack->add('friend', ERROR_TO_ADDRESS);
  }
  //VISUAL VERIFY CODE start
  if (defined('VVC_SITE_ON_OFF') && VVC_SITE_ON_OFF == 'On'){
    if (defined('VVC_TELL_FRIEND_ON_OFF') && VVC_TELL_FRIEND_ON_OFF == 'On'){
    $code_query = tep_db_query("select code from " . TABLE_VISUAL_VERIFY_CODE . " where oscsid = '" . tep_session_id() . "'");
    $code_array = tep_db_fetch_array($code_query);
    tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . tep_session_id() . "'"); //remove the visual verify code associated with this session to clean database and ensure new results
    if ( isset($_POST['visual_verify_code']) && tep_not_null($_POST['visual_verify_code']) && 
         isset($code_array['code']) &&  tep_not_null($code_array['code']) && 
         strcmp($_POST['visual_verify_code'], $code_array['code']) == 0) {   //make the check case sensitive
         //match is good, no message or error.
         } else {
        $error = true;
        $messageStack->add('friend', VISUAL_VERIFY_CODE_ENTRY_ERROR);
      }
    }
  }
  //VISUAL VERIFY CODE stop
  if(!$error){
    $email_subject = sprintf(TEXT_EMAIL_SUBJECT, $from_name, STORE_NAME);
    $email_body = sprintf(TEXT_EMAIL_INTRO, $to_name, $from_name, $product_info['products_name'], STORE_NAME) . "\n\n";
    if (tep_not_null($message)) {
      $email_body .= $message . "\n\n";
    }
    if (TELL_PRODUCT_EMAIL_USE_HTML == "false") {
      $email_body .= TEXT_EMAIL_LINK_TEXT . '<a href="' . HTTP_SERVER  . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?products_id='.$tell_products_id .'">' .  HTTP_SERVER  . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?products_id='. $tell_products_id . '</a>' . "\n\n";
      $email_body .= TEXT_EMAIL_SIGNATURE. STORE_NAME . "\n" . '<a href="' . HTTP_SERVER  . DIR_WS_CATALOG .'">' .  HTTP_SERVER  . DIR_WS_CATALOG . '</a>' . "\n\n";
    } else {
      $email_body .= TEXT_EMAIL_LINK . '<a href="' . HTTP_SERVER  . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?products_id='.$tell_products_id .'">' .  HTTP_SERVER  . DIR_WS_CATALOG . FILENAME_PRODUCT_INFO . '?products_id='. $tell_products_id . '</a>' . "\n\n";
      $email_body .= TEXT_EMAIL_SIGNATURE. STORE_NAME . "\n" . '<a href="' . HTTP_SERVER  . DIR_WS_CATALOG .'">' .  HTTP_SERVER  . DIR_WS_CATALOG . '</a>' . "\n\n";
    }
    if (isset($_SESSION['is_std'])) {
      if (defined('EMAIL_USE_HTML') && EMAIL_USE_HTML == 'true') {
        $email_body .= '<a href="http://www.creloaded.com" target="_blank">' . TEXT_POWERED_BY_CRE . '</a>' . "\n\n";
      } else {
        $email_body .= TEXT_POWERED_BY_CRE . "\n\n";
      }
    }    
    $mimemessage = new email(array('X-Mailer: osCommerce bulk mailer'));
    if (TELL_PRODUCT_EMAIL_USE_HTML == "false") {
      $mimemessage->add_text($email_body);
    } else {
      $mimemessage->add_html($email_body);
    }
    $mimemessage->build_message();
    $mimemessage->send($to_name, $to_email_address, $from_name, $from_email_address, $email_subject);
    $messageStack->add_session('header', sprintf(TEXT_EMAIL_SUCCESSFUL_SENT, $product_info['products_name'], tep_output_string_protected($to_name)), 'success');
    tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $tell_products_id));
  }
}
$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_TELL_A_FRIEND, 'products_id=' . $tell_products_id));
$content = CONTENT_TELL_A_FRIEND;
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>