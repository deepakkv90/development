<?php
/*
  $Id: affiliate_payment.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Affiliate Program');
define('HEADING_TITLE', 'Affiliate Program: Payment');

define('TEXT_AFFILIATE_HEADER', 'Your Payments:');

define('TABLE_HEADING_DATE', 'Payment Date');
define('TABLE_HEADING_PAYMENT', 'Affiliate Earnings');
define('TABLE_HEADING_STATUS', 'Payment Status');
define('TABLE_HEADING_PAYMENT_ID','Payment-ID');
define('TEXT_DISPLAY_NUMBER_OF_PAYMENTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> payments)');
define('TEXT_INFORMATION_PAYMENT_TOTAL', 'Your current earnings amount to:');
define('TEXT_NO_PAYMENTS', 'No payments have been recorded yet.');
define('TEXT_PAYMENT_HELP', ' [?] ');
define('TEXT_PAYMENT', 'Click on [?] to see a description of each category.');
define('HEADING_PAYMENT_HELP', 'Affiliate Help');

define('IMAGE_AFFILIATE_BILLING', 'Generate Payments');
define('HEADING_TITLE_SEARCH', 'Search'); 
define('HEADING_TITLE_STATUS', 'Status'); 
define('TEXT_ALL_PAYMENTS', 'All payments');
define('TABLE_HEADING_AFILIATE_NAME', 'Affiliate');
define('TABLE_HEADING_NET_PAYMENT', 'Payment (excl.)');
define('TABLE_HEADING_DATE_BILLED', 'Date Billed');
define('TABLE_HEADING_ACTION', 'Action');

/*************************/

define('SUCCESS_BILLING','Your Affiliates have been sucessfully billed');
define('SUCCESS_PAYMENT_UPDATED','Your Affiliates Payment Requests have been sucessfully generated');
define('SUCCESS_BILLING', 'Success Billing');
define('TEXT_AFFILIATE','Affiliate:');
define('TEXT_AFFILIATE_PAYMENT','Affiliate Payment:');
define('TEXT_AFFILIATE_BILLED',  'Affiliate Billed:');
define('TEXT_AFFILIATE_PAYING_POSSIBILITIES','Paying Possibilities:');  
define('TEXT_AFFILIATE_PAYMENT_BANK_TRANSFER','Bank Transfer');
define('TEXT_AFFILIATE_PAYMENT_BANK_NAME','Bank Name:');
define('TEXT_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER','Branch Number:');
define('TEXT_AFFILIATE_PAYMENT_BANK_SWIFT_CODE','Swift Code:');
define('TEXT_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME','Account Name:');
define('TEXT_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER','Account Number:');
define('TEXT_AFFILIATE_PAYMENT_PAYPAL','Paypal');
define('TEXT_AFFILIATE_PAYMENT_PAYPAL_EMAIL','Paypal Email:');
define('TEXT_AFFILIATE_PAYMENT_CHECK','Payment Check');
define('TEXT_AFFILIATE_PAYMENT_CHECK_PAYEE','Check Payee:');
define('PAYMENT_STATUS','Status');
define('PAYMENT_NOTIFY_AFFILIATE','Notify Affiliate:'); 
define('TABLE_HEADING_NEW_VALUE','New Value');
define('TABLE_HEADING_OLD_VALUE','Old Value');
define('TABLE_HEADING_AFFILIATE_NOTIFIED','Affiliate Notified');
define('TEXT_NO_PAYMENT_HISTORY','No Payment History');
define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Payment Update');
define('EMAIL_TEXT_AFFILIATE_PAYMENT_NUMBER', 'Payment Number:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');
define('EMAIL_TEXT_PAYMENT_BILLED', 'Date billed');
define('EMAIL_TEXT_STATUS_UPDATE', 'Your payment has been updated to the following status.' . "\n\n" . 'New status: %s' . "\n\n" . 'Please reply to this email if you have any questions.' . "\n");
define('EMAIL_TEXT_NEW_PAYMENT', 'A new invoice arrived to your payments' . "\n");

/***********************************/
?>