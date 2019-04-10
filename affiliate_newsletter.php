<?php
/*
  $Id: affiliate_newsletter.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!isset($_SESSION['affiliate_id'])) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_AFFILIATE, '', 'SSL'));
  }

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_NEWSLETTER);

  $newsletter_query = tep_db_query("select affiliate_newsletter from " . TABLE_AFFILIATE . " where affiliate_id = '" . (int)$_SESSION['affiliate_id'] . "'");
  $newsletter = tep_db_fetch_array($newsletter_query);

  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    if (isset($_POST['newsletter_affiliate']) && is_numeric($_POST['newsletter_affiliate'])) {
      $newsletter_affiliate = tep_db_prepare_input($_POST['newsletter_affiliate']);
    } else {
      $newsletter_affiliate = '0';
    }

    if ($newsletter_affiliate != $newsletter['affiliate_newsletter']) {
      $newsletter_affiliate = (($newsletter['affiliate_newsletter'] == '1') ? '0' : '1');

      tep_db_query("update " . TABLE_AFFILIATE . " set affiliate_newsletter = '" . (int)$newsletter_affiliate . "' where affiliate_id = '" . (int)$_SESSION['affiliate_id'] . "'");
    }

    $messageStack->add_session('account', SUCCESS_NEWSLETTER_UPDATED, 'success');

    tep_redirect(tep_href_link(FILENAME_AFFILIATE_CENTRAL, '', 'SSL'));
  }

  $breadcrumb->add(NAVBAR_TITLE_AFFILIATE_CENTRAL, tep_href_link(FILENAME_AFFILIATE_CENTRAL));
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_NEWSLETTER, '', 'SSL'));

  $content = CONTENT_AFFILIATE_NEWSLETTER;
  $javascript = 'popup_window_general.js';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
