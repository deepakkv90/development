<?php
/*
  $Id: customers.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Account');
define('HEADING_TITLE_SEARCH', 'Search:');
 
define('TABLE_HEADING_FIRSTNAME', 'First Name');
define('TABLE_HEADING_LASTNAME', 'Last Name');
define('TABLE_HEADING_ACCOUNT_CREATED', 'Account Created');
define('TABLE_HEADING_ACCOUNT_CREATED_SHORT', 'Acc. Date');
define('TABLE_HEADING_LAST_ORDER', 'Last Order');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_COMPANY','Account');

define('TEXT_DATE_ACCOUNT_CREATED', 'Account Created:');
define('TEXT_DATE_ACCOUNT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_INFO_DATE_LAST_LOGON', 'Last Logon:');
define('TEXT_INFO_NUMBER_OF_LOGONS', 'Number of Logons:');
define('TEXT_INFO_COUNTRY', 'Country:');
define('TEXT_INFO_NUMBER_OF_REVIEWS', 'Number of Reviews:');
define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this account?');
define('TEXT_DELETE_REVIEWS', 'Delete %s review(s)');
define('TEXT_INFO_HEADING_DELETE_CUSTOMER', 'Delete Account');
if (!defined('TYPE_BELOW')) {
  define('TYPE_BELOW', 'Type below');
}

define('PLEASE_SELECT', 'Select One');

// Eversun mod for sppc and qty price breaks
define('TABLE_HEADING_CUSTOMERS_GROUPS', 'Account&#160;Group');
define('TABLE_HEADING_CUSTOMERS_GROUPS_SHORT', 'Acc. Group');
define('TABLE_HEADING_CUSTOMERS_NUMBER', 'Acc. #');
define('TABLE_HEADING_CUSTOMERS_REFERENCE', 'Macola #');
define('TABLE_HEADING_CUSTOMERS_NAME', 'Name');
define('TABLE_HEADING_REQUEST_AUTHENTICATION', 'RA');
define('ENTRY_CUSTOMERS_PAYMENT_SET', 'Set payment modules for the account');
define('ENTRY_CUSTOMERS_PAYMENT_DEFAULT', 'Use settings from Group or Configuration');
define('ENTRY_CUSTOMERS_PAYMENT_SET_EXPLAIN', 'If you choose <b><i>Set payment modules for the account</i></b> but do not check any of the boxes, default settings (Group settings or Configuration) will still be used.');
define('ENTRY_CUSTOMERS_SHIPPING_SET', 'Set shipping modules for the account');
define('ENTRY_CUSTOMERS_SHIPPING_DEFAULT', 'Use settings from Group or Configuration');
define('ENTRY_CUSTOMERS_SHIPPING_SET_EXPLAIN', 'If you choose <b><i>Set shipping modules for the customer</i></b> but do not check any of the boxes, default settings (Group settings or Configuration) will still be used.');
define('ENTRY_CUSTOMERS_EMAIL_VALIDATED','Email Validated:');
define('ENTRY_EMAILVALIDATE_YES', 'Validated (active)');
define('ENTRY_EMAILVALIDATE_NO', 'Unvalidated (inactive)');
define('TEXT_EMAIL_VALIDATE_FEATURE','Require E-mail confirmation on account creation is set off.');

define('TEXT_ACCOUNT_VALIDATE_FEATURE','Require Account confirmation on account creation is set off.');
define('ENTRY_CUSTOMERS_ACCOUNT_VALIDATED','Account Validate');
define('ENTRY_ACCOUNTVALIDATE_P', 'Pending');
define('ENTRY_ACCOUNTVALIDATE_A', 'Approve');
define('ENTRY_ACCOUNTVALIDATE_D', 'Deny');
define('ALT_IC_UP',' --> A-B-C From Top ');
define('ALT_IC_DOWN',' --> Z-X-Y From Top ');
define('ALT_IC_UP_NUM',' --> 1-2-3 From Top ');
define('ALT_IC_DOWN_NUM',' --> 3-2-1 From Top ');
define('TABLE_HEADING_CUSTOMERS_NO','No.');
define('IMAGE_BUTTON_CREATE_ORDER','create order');
define('IMAGE_BUTTON_RESEND_VALIDATION', 'Resend Validation E-mail');
define('IMAGE_LOGIN_AS_CUSTOMER', 'Login as Customer');
define('ENTRY_CUSTOMERS_GROUP_NAME', 'Account Price Group:');
define('BOX_CUSTOMERS_GROUPS', 'Account Groups');

define('ENTRY_COMPANY_TAX_ID', 'Macola Customer Ref.'); //Mar 11 2011
define('ENTRY_COMPANY_TAX_ID_ERROR', '<span class="errorText"></span>');
define('ENTRY_CUSTOMER_NUMBER', 'Account number:'); //Mar 11 2011
define('ENTRY_CUSTOMER_NUMBER_ERROR', '<span class="errorText"></span>');

define('ENTRY_CUSTOMERS_GROUP_REQUEST_AUTHENTICATION', 'Switch off alert for authentication:');
define('ENTRY_CUSTOMERS_GROUP_RA_NO', 'Alert off');
define('ENTRY_CUSTOMERS_GROUP_RA_YES', 'Alert on');
define('ENTRY_CUSTOMERS_GROUP_RA_ERROR', '');
define('ENTRY_CUSTOMERS_ACCESS_GROUP', 'Account Access Group: ');
define('ENTRY_CUSTOMERS_GUEST_GROUP', 'Guest');
define('ENTRY_CUSTOMERS_ALL_GROUP', 'All');
// Eversun mod end for sppc and qty price breaks
define('ENTRY_CUSTOMERS_TEMPLATE_NAME','Selected Template');
define('ENTRY_CUSTOMERS_VOUCHER_AMOUNT','Voucher Amount');
define('SYSTEM_INFORMATION','System Information');
define('NOTICE_CUSTOMER_UPDATED', 'Account ID %s Updated.'); 
define('NOTICE_CUSTOMER_DELETE', 'Account ID %s Deleted.'); 
define('ERROR_PASSWORD_NO_MATCH', 'The passwords do not match.');
define('ERROR_PASSWORD_MIN_LENGTH', 'Password must be a minimum length of %s.');
define('ERROR_PASSWORD_NOT_HARDENED', 'The password must contain upper and lower case letters and at least 1 number.');
define('ENTRY_CUSTOMERS_VOUCER_AMOUNT_HELP', '&nbsp;&nbsp; The value of the voucher amount, either fixed or add a % on the end for a percentage discount. ');

define('TABLE_HEADING_REVENUE','Revenus');
define('TABLE_HEADING_NUMBER_OF_ORDERS','Orders');
?>