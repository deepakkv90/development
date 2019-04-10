<?php
/*
  $Id: affiliate_english.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

define('BOX_INFORMATION_AFFILIATE', 'The Affiliate Program');
define('BOX_HEADING_AFFILIATE', 'Affiliate Program');
define('BOX_HEADING_AFFILIATE_NEWS', 'Affiliate News');
define('BOX_AFFILIATE', 'Affiliates'); 
define('BOX_AFFILIATE_CLICKS', 'Clicks'); 
define('BOX_AFFILIATE_NEWSLETTER_MANAGER', 'Affiliate Newsletter'); 

define('BOX_AFFILIATE_CENTRE', 'Affiliate Center');
define('BOX_AFFILIATE_BANNER_CENTRE', 'Affiliate Links');
define('BOX_AFFILIATE_REPORT_CENTRE', 'Affiliate Reports');
define('BOX_AFFILIATE_INFO', 'Affiliate Information');
define('BOX_AFFILIATE_SUMMARY', 'Affiliate Summary');
define('BOX_AFFILIATE_PASSWORD', 'Change Password');
define('BOX_AFFILIATE_NEWS', 'Affiliate News');
define('BOX_AFFILIATE_NEWSLETTER', 'Newsletter');
define('BOX_AFFILIATE_ACCOUNT', 'Edit Affiliate Account');
define('BOX_AFFILIATE_REPORTS', 'Affiliate Reports');
define('BOX_AFFILIATE_CLICKRATE', 'Clickthrough Report');
define('BOX_AFFILIATE_PAYMENT', 'Payment Report');
define('BOX_AFFILIATE_SALES', 'Sales Report');
define('BOX_AFFILIATE_BANNERS', 'Affiliate Banners');
define('BOX_AFFILIATE_BANNERS_BANNERS', 'WebSite Banners');
define('BOX_AFFILIATE_BANNERS_BUILD_CAT', 'Build Category Link');
define('BOX_AFFILIATE_BANNERS_BUILD', 'Build Product Link');
define('BOX_AFFILIATE_BANNERS_PRODUCT', 'Product Banners');
define('BOX_AFFILIATE_BANNERS_CATEGORY', 'Category Banners');
define('BOX_AFFILIATE_BANNERS_TEXT', 'Text Links');
define('TEXT_PAYMENT_ID', 'Shows the ID Number, of payments.');
define('TEXT_SALES_PAYMENT_DATE', 'Shows the date, of payments.');
define('TEXT_SALES_PAYMENT_Ammount', 'Affiliate Earnings represents the commission due on the sale');
define('TEXT_PAYMENT_STATUS', 'Sale Status represents the status the sale.');
define('BOX_AFFILIATE_CONTACT', 'Contact Us');
define('BOX_AFFILIATE_FAQ', 'Affiliate Program FAQ');
define('BOX_AFFILIATE_LOGIN', 'Affiliate Log In');
define('BOX_AFFILIATE_LOGOUT', 'Affiliate Log Out');

define('ENTRY_AFFILIATE_PAYMENT_DETAILS', 'Payable to:');
define('ENTRY_AFFILIATE_ACCEPT_AGB', 'Check here to indicate that you have read and agree to the <a target="_new" href="' . tep_href_link(FILENAME_AFFILIATE_TERMS, '', 'SSL') . '">Associates Terms & Conditions</a>.');
define('ENTRY_AFFILIATE_ACCEPT_AGB_TEXT', 'Affiliate Program Terms and Conditions ');
if (!defined('ENTRY_AFFILIATE_AGB_ERROR')) {
define('ENTRY_AFFILIATE_AGB_ERROR', ' &nbsp;<small><font color="#FF0000">You must accept our Affiliate Program Terms & Conditions</font></small>');
}
define('ENTRY_AFFILIATE_PAYMENT_CHECK', 'Check Payee Name:');
define('ENTRY_AFFILIATE_PAYMENT_CHECK_TEXT', '');
define('ENTRY_AFFILIATE_PAYMENT_CHECK_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');
define('ENTRY_AFFILIATE_PAYMENT_PAYPAL', 'PayPal Account Email:');
define('ENTRY_AFFILIATE_PAYMENT_PAYPAL_TEXT', '');
define('ENTRY_AFFILIATE_PAYMENT_PAYPAL_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');
define('ENTRY_AFFILIATE_PAYMENT_BANK_NAME', 'Bank Name:');
define('ENTRY_AFFILIATE_PAYMENT_BANK_NAME_TEXT', '');
define('ENTRY_AFFILIATE_PAYMENT_BANK_NAME_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');
define('ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME', 'Account Name:');
define('ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME_TEXT', '');
define('ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');
define('ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER', 'Account Number:');
define('ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER_TEXT', '');
define('ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');
define('ENTRY_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER', 'ABA/BSB number (branch number):');
define('ENTRY_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER_TEXT', '');
define('ENTRY_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');
define('ENTRY_AFFILIATE_PAYMENT_BANK_SWIFT_CODE', 'SWIFT Code:');
define('ENTRY_AFFILIATE_PAYMENT_BANK_SWIFT_CODE_TEXT', '');
define('ENTRY_AFFILIATE_PAYMENT_BANK_SWIFT_CODE_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');
define('ENTRY_AFFILIATE_COMPANY', 'Company:');
define('ENTRY_AFFILIATE_COMPANY_TEXT', '');
define('ENTRY_AFFILIATE_COMPANY_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');
define('ENTRY_AFFILIATE_COMPANY_TAXID', 'Tax ID:');
define('ENTRY_AFFILIATE_COMPANY_TAXID_TEXT', '');
define('ENTRY_AFFILIATE_COMPANY_TAXID_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');
define('ENTRY_AFFILIATE_HOMEPAGE', 'Homepage:');
define('ENTRY_AFFILIATE_HOMEPAGE_TEXT', '&nbsp;<small><font color="#AABBDD">required (http://)</font></small>');
define('ENTRY_AFFILIATE_HOMEPAGE_ERROR', '&nbsp;<small><font color="#FF0000">required (http://)</font></small>');
define('ENTRY_AFFILIATE_NEWSLETTER', 'Affiliate Newsletter');
define('ENTRY_AFFILIATE_NEWSLETTER_TEXT', '');
define('ENTRY_AFFILIATE_NEWSLETTER_ERROR', '&nbsp;<small><font color="#FF0000">required</font></small>');

define('CATEGORY_PAYMENT_DETAILS', 'You Get Your Money By');


define('TEXT_AFFILIATE_BANNERS_BANNERS', 'Web Site Banner Links');
define('TEXT_AFFILIATE_BANNERS', 'Affiliate Banners');
define('TEXT_AFFILIATE_BANNERS_BUILD', 'Build Product Link');
define('TEXT_AFFILIATE_BANNERS_CAT', 'Build Category Link');
define('TEXT_AFFILIATE_BANNERS_CATEGORY', 'Pre defined Category Link');
define('TEXT_AFFILIATE_BANNERS_PRODUCT', 'Pre defined Product Links');
define('TEXT_AFFILIATE_BANNERS_TEXT', 'Text Links');

define('AFFILIATE_MALE', 'Male');
define('AFFILIATE_FEMALE', 'Female');

//moved from english.php
define("AFFILIATE_SHOW_BANNER_CHECK_PATHES",'Check the paths! (catalog/includes/configure.php)');
define("AFFILIATE_SHOW_BANNER_ABSOLUTE_PATH",'absolute path to picture:');
define("AFFILIATE_SHOW_BANNER_BUILD_WITH_1",'build with:');
define("AFFILIATE_SHOW_BANNER_BUILD_WITH_2",'DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG . DIR_WS_IMAGES . $banner');
define("AFFILIATE_SHOW_BANNER_DIR_FS_DOCUMENT_ROOT",'DIR_FS_DOCUMENT_ROOT');
define("AFFILIATE_SHOW_BANNER_DIR_WS_CATALOG",'DIR_WS_CATALOG');
define("AFFILIATE_SHOW_BANNER_DIR_WS_IMAGES",'DIR_WS_IMAGES');
define("AFFILIATE_SHOW_BANNER_BANNER",'$banner');
define("AFFILIATE_SHOW_BANNER_SQL_QUERY_USED",'SQL-Query used:');
define("AFFILIATE_SHOW_BANNER_TRY_TO_FIND_ERROR",'Try to find error:');
define("AFFILIATE_SHOW_BANNER_SQL_QUERY",'SQL-Query:');
define("AFFILIATE_SHOW_BANNER_LOCATING_PIC",'Locating Pic');
?>