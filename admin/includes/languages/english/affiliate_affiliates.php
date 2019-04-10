<?php
/*
  $Id: affiliate_affiliates.php,v 2.0

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Affiliates');
define('HEADING_TITLE_SEARCH', 'Search:');

define('TABLE_HEADING_FIRSTNAME', 'First Name');
define('TABLE_HEADING_LASTNAME', 'Last Name');
define('TABLE_HEADING_USERHOMEPAGE', 'Homepage');
define('TABLE_HEADING_COMMISSION','Commission');
define('TABLE_HEADING_ACCOUNT_CREATED', 'Account Created');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_AFFILIATE_ID','Affiliate ID');

define('TEXT_DATE_ACCOUNT_CREATED', 'Account Created:');
define('TEXT_DATE_ACCOUNT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_INFO_DATE_LAST_LOGON', 'Last Logon:');
define('TEXT_INFO_NUMBER_OF_LOGONS', 'Number of Logons:');
define('TEXT_INFO_TEMPLATE_ASIGNED','Template Asigned:');
define('TEXT_INFO_COMMISSION','Commission');
define('TEXT_INFO_NUMBER_OF_SALES', 'Number of Sales:');
define('TEXT_INFO_COUNTRY', 'Country:');
define('TEXT_INFO_SALES_TOTAL', 'Total Sales:');
define('TEXT_INFO_AFFILIATE_TOTAL', 'Commission:');
define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this affiliate?');
define('TEXT_INFO_HEADING_DELETE_CUSTOMER', 'Delete Affiliate');
define('TEXT_DISPLAY_NUMBER_OF_AFFILIATES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> affiliates)');

define('ENTRY_AFFILIATE_PAYMENT_DETAILS', 'Payable to:');
define('ENTRY_AFFILIATE_PAYMENT_CHECK','Check Payee Name:');
define('ENTRY_AFFILIATE_PAYMENT_PAYPAL','PayPal Account Email:');
define('ENTRY_AFFILIATE_PAYMENT_BANK_NAME','Bank Name:');
define('ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME','Account Name:');
define('ENTRY_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER','Account Number:');
define('ENTRY_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER','ABA/BSB number (branch number)');
define('ENTRY_AFFILIATE_PAYMENT_BANK_SWIFT_CODE','SWIFT Code ');
define('ENTRY_AFFILIATE_COMPANY','Company');
define('ENTRY_AFFILIATE_COMPANY_TAXID','Tax-Id.:');
define('ENTRY_AFFILIATE_HOMEPAGE','Homepage');
define('ENTRY_AFFILIATE_COMMISSION',' Pay Per Sale Payment % Rate');
define('ENTRY_AFFILIATE_TEMPLATE','Affiliate Template');

define('CATEGORY_COMMISSION','Individual Commission');
define('CATEGORY_PAYMENT_DETAILS','You get your money by:');

if (!defined('TYPE_BELOW')) {
  define('TYPE_BELOW', 'Type below');
}
define('PLEASE_SELECT', 'Select One');
define('AFFILIATE_PERCENT_NOT_SET', 'Not Set');

//affiliate cobrand
define('TITLE_AFFILIATE_COBRANDING','Affiliate Co Branding');
define('ENTRY_COBRANDING_COMPANY_LOGO','Co Brand Logo:');
define('DELETE_COBRANDING_COMPANY_LOGO','Delete Existing logo');
define('ENTRY_COBRANDING_COMPANY_NAME','Co Brand Name:');
define('ENTRY_COBRANDING_SLOGAN','Co Brand Slogan:');
define('ENTRY_COBRANDING_URL','Co Brand URL:');
define('ENTRY_COBRANDING_URL_HELP','http:// or https:// required.');
define('ENTRY_COBRANDING_SUPPORT_EMAIL','Customer Service Email:');
define('ENTRY_COBRANDING_SUPPORT_PHONE','Customer Service Phone:');

define('AFFILIATE_ERROR_DIRECTORY_DOES_NOT_EXIST', 'Directory : %s does not exists.');
define('AFFILITE_ERROR_DIRECTORY_NOT_WRITABLE','Directory : %s is not writable.');
define('AFFILIATE_SUCCESS_DELETEED_IMAGE','Deleted Affiliate Cobrand Image.');
define('AFFILIATE_SUCCESS_UPLOADED_IMAGE','Uploaded Affiliate Cobrand Image.');
define('AFFILIATE_ERROR_UPLOADING_IMAGE','Can not upload Affiliate Cobrand Image');
define('AFFILIATE_ERROR_IMAGE_MISSING','Affiliate Cobrand Logo file is missing.');

define('JS_COBRANDING_SALES_ERROR','* The \'Co Branding Sales E-Mail Address\' has problem.\n');
define('JS_COBRANDING_SUPPORT_ERROR','* The \'Co Branding Support E-Mail Address\' has problem.\n');
define('JS_COBRANDING_BILLING_ERROR','* The \'Co Branding Billing E-Mail Address\' has problem.\n');
?>
