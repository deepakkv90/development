<?php
/*
  $Id: links_submit.php,v 1.1.1.1 2008/05/29 23:38:00 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LINKS_SUBMIT);
$error = false;
$process = false;
if (isset($_POST['action']) && ($_POST['action'] == 'process') ) {
  $process = true;
  $links_title = tep_db_prepare_input($_POST['links_title']);
  $links_url = tep_db_prepare_input($_POST['links_url']);
  $links_category = tep_db_prepare_input($_POST['links_category']);
  $links_description = tep_db_prepare_input($_POST['links_description']);
  $links_image = tep_db_prepare_input($_POST['links_image']);
  $links_contact_name = tep_db_prepare_input($_POST['links_contact_name']);
  $links_contact_email = tep_db_prepare_input($_POST['links_contact_email']);
  $links_reciprocal_url = tep_db_prepare_input($_POST['links_reciprocal_url']);
  $error = false;
  if (strlen($links_title) < ENTRY_LINKS_TITLE_MIN_LENGTH) {
    $error = true;
    $messageStack->add('submit_link', ENTRY_LINKS_TITLE_ERROR);
  }
  if (strlen($links_url) < ENTRY_LINKS_URL_MIN_LENGTH) {
    $error = true;
    $messageStack->add('submit_link', ENTRY_LINKS_URL_ERROR);
  }
  if (strlen($links_description) < ENTRY_LINKS_DESCRIPTION_MIN_LENGTH) {
    $error = true;
    $messageStack->add('submit_link', ENTRY_LINKS_DESCRIPTION_ERROR);
  }
  if (strlen($links_contact_name) < ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH) {
    $error = true;
    $messageStack->add('submit_link', ENTRY_LINKS_CONTACT_NAME_ERROR);
  }
  if (strlen($links_contact_email) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
    $error = true;
    $messageStack->add('submit_link', ENTRY_EMAIL_ADDRESS_ERROR);
  } elseif (tep_validate_email($links_contact_email) == false) {
    $error = true;
    $messageStack->add('submit_link', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
  }
  if (strlen($links_reciprocal_url) < ENTRY_LINKS_URL_MIN_LENGTH) {
    $error = true;
    $messageStack->add('submit_link', ENTRY_LINKS_RECIPROCAL_URL_ERROR);
  }
  //VISUAL VERIFY CODE start
  if (defined('VVC_SITE_ON_OFF') && VVC_SITE_ON_OFF == 'On') {
    if (defined('VVC_LINK_SUBMITT_ON_OFF') && VVC_LINK_SUBMITT_ON_OFF == 'On'){
    $code_query = tep_db_query("select code from " . TABLE_VISUAL_VERIFY_CODE . " where oscsid = '" . tep_session_id() . "'");
    $code_array = tep_db_fetch_array($code_query);
    tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . tep_session_id() . "'"); //remove the visual verify code associated with this session to clean database and ensure new results
    if ( isset($_POST['visual_verify_code']) && tep_not_null($_POST['visual_verify_code']) && 
         isset($code_array['code']) &&  tep_not_null($code_array['code']) && 
         strcmp($_POST['visual_verify_code'], $code_array['code']) == 0) {   //make the check case sensitive
         //match is good, no message or error.
         } else {
        $error = true;
        $messageStack->add('submit_link', VISUAL_VERIFY_CODE_ENTRY_ERROR);
      }
    }
  }
  //VISUAL VERIFY CODE stop
  if ($error == false) {
    if($links_image == 'http://') {
      $links_image = '';
    }
    // default values
    $links_date_added = 'now()';
    $links_status = '1'; // Pending approval
    $links_rating = '0';
    $sql_data_array = array('links_url' => $links_url,
                            'links_image_url' => $links_image,
                            'links_contact_name' => $links_contact_name,
                            'links_contact_email' => $links_contact_email,
                            'links_reciprocal_url' => $links_reciprocal_url,
                            'links_date_added' => $links_date_added,
                            'links_status' => $links_status,
                            'links_rating' => $links_rating);
    tep_db_perform(TABLE_LINKS, $sql_data_array);
    $links_id = tep_db_insert_id();
    $categories_query = tep_db_query("SELECT link_categories_id 
                                        from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " 
                                      WHERE link_categories_name = '" . tep_db_input($links_category) . "' 
                                        and language_id = '" . (int)$languages_id . "'");
    $categories = tep_db_fetch_array($categories_query);
    $link_categories_id = $categories['link_categories_id'];
    tep_db_query("INSERT INTO " . TABLE_LINKS_TO_LINK_CATEGORIES . " (links_id, link_categories_id) 
                  VALUES ('" . (int)$links_id . "', '" . (int)$link_categories_id . "')");
    $language_id = (int)$languages_id;
    $sql_data_array = array('links_id' => $links_id,
                            'language_id' => $language_id,
                            'links_title' => $links_title,
                            'links_description' => $links_description);
    tep_db_perform(TABLE_LINKS_DESCRIPTION, $sql_data_array);
    // build the message content
    $name = $links_contact_name;
    $email_text = sprintf(EMAIL_GREET_NONE, $links_contact_name);
    $email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;
    tep_mail($name, $links_contact_email, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_OWNER_SUBJECT, EMAIL_OWNER_TEXT, $name, $links_contact_email);
    tep_redirect(tep_href_link(FILENAME_LINKS_SUBMIT_SUCCESS, '', 'SSL'));
  }
}
// links breadcrumb
$breadcrumb->add(NAVBAR_TITLE_1, FILENAME_LINKS);
if (isset($_GET['lPath'])) {
  $link_categories_query = tep_db_query("SELECT link_categories_name 
                                           from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " 
                                         WHERE link_categories_id = '" . (int)$_GET['lPath'] . "' 
                                           and language_id = '" . (int)$languages_id . "'");
  $link_categories_value = tep_db_fetch_array($link_categories_query);
  $breadcrumb->add($link_categories_value['link_categories_name'], FILENAME_LINKS . '?lPath=' . (int)$_GET['lPath']);
}
$breadcrumb->add(NAVBAR_TITLE_2);
$content = CONTENT_LINKS_SUBMIT;
$javascript = $content . '.js';
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>