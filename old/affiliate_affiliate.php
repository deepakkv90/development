<?php
/*
  $Id: affiliate_affiliate.php,v 1.1.1.1 2004/03/04 23:37:54 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
    $affiliate_username = tep_db_prepare_input($_POST['affiliate_username']);
    $affiliate_password = tep_db_prepare_input($_POST['affiliate_password']);

// Check if username exists
    $check_affiliate_query = tep_db_query("select affiliate_id, affiliate_firstname, affiliate_password, affiliate_email_address from " . TABLE_AFFILIATE . " where affiliate_email_address = '" . tep_db_input($affiliate_username) . "'");
    if (!tep_db_num_rows($check_affiliate_query)) {
      $_GET['login'] = 'fail';
    } else {
      $check_affiliate = tep_db_fetch_array($check_affiliate_query);
// Check that password is good
      if (!tep_validate_password($affiliate_password, $check_affiliate['affiliate_password'])) {
        $_GET['login'] = 'fail';
      } else {
        $_SESSION['affiliate_id'] = $check_affiliate['affiliate_id'];

        $date_now = date('Ymd');

        tep_db_query("update " . TABLE_AFFILIATE . " set affiliate_date_of_last_logon = now(), affiliate_number_of_logons = affiliate_number_of_logons + 1 where affiliate_id = '" . $_SESSION['affiliate_id'] . "'");

        tep_redirect(tep_href_link(FILENAME_AFFILIATE_SUMMARY,'','SSL'));
      }
    }
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE, '', 'SSL'));


  $content = CONTENT_AFFILIATE;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
