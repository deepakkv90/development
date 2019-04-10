<?php
/*
  $Id: faq.php,v 1.2 2004/03/12 19:28:57 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

$cID = 0;
$display_mode = '';

if (isset($_GET['cID']) && tep_not_null($_GET['cID'])) {
  $cID = (int)$_GET['cID'];
  $display_mode = 'faq';
}

if (isset($_GET['CDpath']) && tep_not_null($_GET['CDpath'])) {
  $CDpath = (int)$_GET['CDpath'];
  $display_mode = 'faq';
}

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FAQ);

// faq breadcrumb
$faq_categories_query = tep_db_query("select icd.categories_name from " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " icd, " . TABLE_FAQ_CATEGORIES . " ic where ic.categories_id = icd.categories_id and icd.categories_id = '" . (int)$cID . "' and icd.language_id = '" . (int)$languages_id . "' and ic.categories_status = '1'");
$faq_categories_value = tep_db_fetch_array($faq_categories_query);

if ($display_mode == 'faq') {
  $breadcrumb->add(NAVBAR_TITLE, FILENAME_FAQ);

  if (tep_not_null($faq_categories_value['categories_name'])) {
    $breadcrumb->add($faq_categories_value['categories_name'], FILENAME_FAQ . '?cID=' . $cID);
  }
} else {
  $breadcrumb->add(NAVBAR_TITLE, FILENAME_FAQ);
}

$content = CONTENT_FAQ;

require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
