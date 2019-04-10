<?php
/*
  $Id: coupon_admin.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('TOP_BAR_TITLE', 'Statistics');
define('HEADING_TITLE', 'Discount Coupons');
define('HEADING_TITLE_STATUS', 'Status : ');
define('TEXT_CUSTOMER', 'Customer:');
define('TEXT_COUPON', 'Coupon Name');
define('TEXT_COUPON_ALL', 'All Coupons');
define('TEXT_COUPON_ACTIVE', 'Active Coupons');
define('TEXT_COUPON_INACTIVE', 'Inactive Coupons');
define('TEXT_SUBJECT', 'Subject:');
define('TEXT_FROM', 'From:');
define('TEXT_FREE_SHIPPING', 'Free Shipping');
define('TEXT_MESSAGE', 'Message:');
define('TEXT_SELECT_CUSTOMER', 'Select Customer');
define('TEXT_ALL_CUSTOMERS', 'All Customers');
define('TEXT_NEWSLETTER_CUSTOMERS', 'To All Newsletter Subscribers');
define('TEXT_CONFIRM_DELETE', 'Are you sure you want to delete this Coupon?');

define('TEXT_TO_REDEEM1', 'You can also redeem this Discount coupon during checkout. Just enter the code in the box provided, and click on the redeem button. ');
define('TEXT_IN_CASE', ' in case you have any problems. ');
define('TEXT_VOUCHER_IS', 'The coupon code is ');
define('TEXT_REMEMBER', 'Please do not lose the coupon code, make sure to keep the code safe so you can benefit from this special offer');
define('TEXT_VISIT', 'when you visit <a style="color: #000000" href="' . HTTP_SERVER . DIR_WS_CATALOG . '">' . STORE_NAME . '</a>' ."\n" .'(' . HTTP_SERVER . DIR_WS_CATALOG . ')');
define('TEXT_ENTER_CODE', ' and enter the code. ');
define('TEXT_EMAIL_BUTTON_TEXT', '<p><HR><b><font color="red">The Back Button has been DISABLED while HTML WYSIWG Editor is turned ON.</b></font><HR>');
define('TEXT_EMAIL_BUTTON_HTML', '<p><HR><b><font color="red">HTML is currently Disabled!</b></font><br><br>If you want to send HTML email, Enable HTML Email option in : Admin-->Configuration-->E-Mail Options-->Use MIME HTML When Sending Emails<br>');

define('TEXT_OR_VISIT', 'or visit ');
define('TEXT_TO_REDEEM_TEXT', ' To redeem this Discount coupon, please click on the link below.' ."\n" . '%s' . "\n\n" . 'Please also write down the redemption code');
define('TEXT_WHICH_IS', ' which is ');

define('TEXT_COUPON_REDEEMED', 'Redeemed Coupons');
define('REDEEM_DATE_LAST', 'Last Redeemed');
define('COUPON_STATUS', 'Status');
define('COUPON_STATUS_HELP', 'Set to ' . IMAGE_ICON_STATUS_RED . ' to disable customers\' ability to use the coupon.');
define('COUPON_BUTTON_EMAIL_VOUCHER', 'Email Coupon');
define('COUPON_BUTTON_EDIT_VOUCHER', 'Edit Coupon');
define('COUPON_BUTTON_DELETE_VOUCHER', 'Delete Coupon');
define('COUPON_BUTTON_VOUCHER_REPORT', 'Coupon Report');

define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_HEADING_COUPON_REPORT', 'Coupon Report');

define('CUSTOMER_NAME', 'Customer Name');
define('REDEEM_DATE', 'Date Redeemed');
define('IP_ADDRESS', 'IP Address');

define('TEXT_REDEMPTIONS', 'Redemptions');
define('TEXT_REDEMPTIONS_TOTAL', 'In Total');
define('TEXT_REDEMPTIONS_CUSTOMER', 'For this Customer');
define('TEXT_NO_FREE_SHIPPING', 'No Free Shipping');

define('NOTICE_EMAIL_SENT_TO', 'Notice: Email sent to: %s');
define('ERROR_NO_CUSTOMER_SELECTED', 'Error: No customer has been selected.');
define('ERROR_NO_COUPON_AMOUNT', 'Error: No coupon amount has been entered. Either enter an amount or select free shipping.');
define('ERROR_COUPON_EXISTS', 'Error: A coupon with the same coupon code already exists.');

define('COUPON_NAME', 'Coupon Name');
define('COUPON_AMOUNT', 'Coupon Amount');
define('COUPON_CODE', 'Coupon Code');
define('COUPON_STARTDATE', 'Start Date');
define('COUPON_FINISHDATE', 'End Date');
define('COUPON_FREE_SHIP', 'Free Shipping');
define('COUPON_DESC', 'Coupon Description');
define('COUPON_MIN_ORDER', 'Minimum Order');
define('COUPON_USES_COUPON', 'Uses per Coupon');
define('COUPON_USES_USER', 'Uses per Customer');
define('COUPON_PRODUCTS', 'Valid Products');
define('COUPON_CATEGORIES', 'Valid Categories');
define('VOUCHER_NUMBER_USED', 'Number Used');
define('DATE_CREATED', 'Date Created');
define('DATE_MODIFIED', 'Date Modified');
define('TEXT_HEADING_NEW_COUPON', 'Create New Coupon');
define('IMAGE_NEW_COUPON', 'New Coupon');
define('TEXT_NEW_INTRO', 'Please fill out the following information for the new coupon.<br>');


define('COUPON_NAME_HELP', 'A short name for the coupon.');
define('COUPON_AMOUNT_HELP', 'The value of the discount for the coupon, either fixed or add a % on the end for a percentage discount.');
define('COUPON_CODE_HELP', 'You can enter your own code here, or leave blank for an auto generated one.');
define('COUPON_STARTDATE_HELP', 'The date the coupon will be valid from.');
define('COUPON_FINISHDATE_HELP', 'The date the coupon expires.');
define('COUPON_FREE_SHIP_HELP', 'The coupon gives free shipping on an order. Note: This overrides the coupon_amount figure but respects the minimum order value.');
define('COUPON_DESC_HELP', 'A description of the coupon for the customer.');
define('COUPON_MIN_ORDER_HELP', 'The minimum order value before the coupon is valid.');
define('COUPON_USES_COUPON_HELP', 'The maximum number of times the coupon can be used; leave blank if you want no limit.');
define('COUPON_USES_USER_HELP', 'Number of times a user can use the coupon, leave blank for no limit.');
define('COUPON_PRODUCTS_HELP', 'A comma separated list of product_ids that this coupon can be used with. Leave blank for no restrictions.');
define('COUPON_CATEGORIES_HELP', 'A comma separated list of cpaths that this coupon can be used with, leave blank for no restrictions.');


define('ALT_CONFIRM_DELETE_VOUCHER', 'Confirm Delete Voucher');
define('ALT_CANCEL', 'Cancel');
define('VIEW', 'View');
define('ALT_EMAIL_VOUCHER', 'Email Voucher');
define('ALT_EDIT_VOUCHER', 'Edit Voucher');
define('ALT_DELETE_VOUCHER', 'Delete Voucher');
define('ALT_VOUCHER_REPORT', 'Voucher Report');
define('COUPON_BUTTON_PREVIEW', 'Preview');
define('COUPON_BUTTON_BACK', 'Back');
define('COUPON_BUTTON_CONFIRM', 'Confirm');
/**********gsr************/
define('COUPON_SALE_EXCLUDE_HELP', 'This coupon does not apply to any item  that is marked as being on sale.');
define('COUPON_SALE_EXCLUSION', 'Coupon Sale Exclusion'); 
/*************************/
?>