<?php
/*
  $Id: newsletters.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Newsletter Manager');

define('TABLE_HEADING_NEWSLETTERS', 'Newsletters');
define('TABLE_HEADING_SIZE', 'Size');
define('TABLE_HEADING_MODULE', 'Module');
define('TABLE_HEADING_GROUP', 'Customer Group');
define('TABLE_HEADING_SENT', 'Sent');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');
define('TEXT_ALL_GROUPS', 'All Groups');
define('TEXT_SELECT_GROUP', 'Select Group');
define('TEXT_SELECT_CUSTOMER', 'Select Customer');
define('TEXT_ALL_CUSTOMERS', 'All Customers');
define('TEXT_NEWSLETTER_CUSTOMERS', 'To All Newsletter Subscribers');

define('TEXT_NEWSLETTER_MODULE', 'Module:');
define('TEXT_GROUP_MODULE', 'Group:');
define('TEXT_NEWSLETTER_RESELLER', 'Employees');
define('TEXT_NEWSLETTER_CUSTOMER', 'Retail');
define('TEXT_NEWSLETTER_PRODUCER', 'Wholesale');
define('TEXT_NEWSLETTER_TITLE', 'Newsletter Title:');
define('TEXT_NEWSLETTER_CONTENT', 'Content:');

define('TEXT_NEWSLETTER_DATE_ADDED', 'Date Added:');
define('TEXT_NEWSLETTER_DATE_SENT', 'Date Sent:');

define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this newsletter?');

define('TEXT_PLEASE_WAIT', 'Please wait .. sending Emails ..<br><br>Please do not interrupt this process!');
define('TEXT_FINISHED_SENDING_EMAILS', 'Finished sending Emails!');

define('ERROR_NEWSLETTER_TITLE', 'Error: Newsletter title required');
define('ERROR_NEWSLETTER_MODULE', 'Error: Newsletter module required');
define('ERROR_REMOVE_UNLOCKED_NEWSLETTER', 'Error: Please lock the newsletter before deleting it.');
define('ERROR_EDIT_UNLOCKED_NEWSLETTER', 'Error: Please lock the newsletter before editing it.');
define('ERROR_SEND_UNLOCKED_NEWSLETTER', 'Error: Please lock the newsletter before sending it.');

//added for B2B Customer Groups
define('TEXT_GROUPS', 'Customer Groups');
define('TEXT_SELECTED_GROUPS', 'Selected Customer Groups');

if (!defined('JS_PLEASE_SELECT_PRODUCTS')) {
  define('JS_PLEASE_SELECT_PRODUCTS', 'Please select at least product!');
}
define('TEXT_PRODUCT_NOTIFICATIONS_NEWSLETTER_NAME', 'Product Notifications');
define('TEXT_CUSTOMER_NEWSLETTER_NAME', 'Customer Newsletter');
define('TEXT_AFFILIATE_NEWSLETTER_NAME', 'Affiliate Newsletter'); 
?>