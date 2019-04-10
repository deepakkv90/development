<?php
/*
  $Id: orders.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
define('TABLE_HEADING_EDIT_ORDERS', 'To modify the order');
define('HEADING_TITLE', 'Orders');
define('HEADING_IS_TITLE', 'IS Order');
define('HEADING_IS_RECEIPT', 'IS Receipt');
define('HEADING_TITLE_SEARCH', 'Order ID:');
define('HEADING_TITLE_STATUS', 'Status:');
define('ENTRY_UPDATE_TO_CC', '(Update to <b>Credit Card</b> to view CC fields.)');
define('TABLE_HEADING_COMMENTS', 'Comments');
//define('TABLE_HEADING_CUSTOMERS', 'Customers'); //sep 29 2011
define('TABLE_HEADING_CUSTOMERS', 'Name');
define('TABLE_HEADING_ORDERID', 'Order ID');
define('TABLE_HEADING_IS_ORDERNUM', 'IS Order');
define('TABLE_HEADING_ORDER_TOTAL', 'Order Total');
define('TABLE_HEADING_DATE_PURCHASED', 'Date Purchased');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_QUANTITY', 'QTY');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Products');
define('TABLE_HEADING_TAX', 'GST TAX');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_HEADING_UNIT_PRICE', 'Unit Price');
define('TABLE_HEADING_BASE_PRICE', 'Catalog <br>Base Price');
define('TABLE_HEADING_UNIT_PRICE_TAXED', 'Price (incl.)');
define('TABLE_HEADING_TOTAL_PRICE', 'Total Price');
define('TABLE_HEADING_TOTAL_PRICE_TAXED', 'Total (incl. Tax)');
define('TABLE_HEADING_TOTAL_MODULE', 'Total Price Component');
define('TABLE_HEADING_TOTAL_AMOUNT', 'Amount');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Price (ex)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Price (inc)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Total (ex)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total (inc)');
define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Customer Notified');
define('TABLE_HEADING_DATE_ADDED', 'Date Added');
define('TABLE_HEADING_PAYMENT_STATUS', 'Payment Status');

//define('TABLE_HEADING_COMPANY', 'Company');//Sep 29 2011
define('TABLE_HEADING_COMPANY', 'Account');
define('TABLE_HEADING_PAYMENT', 'Payment');


define('ENTRY_SUBURB', 'Suburb :');
define('ENTRY_CITY', 'City :');
//define('ENTRY_CUSTOMER', 'Customer:'); //Sep 29 2011
define('ENTRY_CUSTOMER', 'Account:');

define('ENTRY_STATE', 'State :');
define('ENTRY_SOLD_TO', 'SOLD TO:');
define('ENTRY_TELEPHONE', 'Enter Telephone :');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');
define('ENTRY_DELIVERY_TO', 'Delivery To:');
define('ENTRY_SHIP_TO', 'SHIP TO:');
define('ENTRY_SHIPPING_ADDRESS', 'Shipping Address:');
define('ENTRY_BILLING_ADDRESS', 'Billing Address:');
define('ENTRY_PAYMENT_METHOD', 'Payment Method:');
define('ENTRY_CREDIT_CARD_TYPE', 'Credit Card Type:');
define('ENTRY_CREDIT_CARD_OWNER', 'Credit Card Owner:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Credit Card Number:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Credit Card Expires:');
define('ENTRY_CREDIT_CARD_CCV', 'CCV Code:');
define('ENTRY_CREDIT_CARD_START_DATE', 'Start Date: ');
define('ENTRY_CREDIT_CARD_START','Go back to d& eacute; CB leaves');
define('ENTRY_CREDIT_CARD_ISSUE', 'Issue Number: ');
define('ENTRY_SUB_TOTAL', 'Sub-Total:');
define('ENTRY_TAX', 'Tax:');
define('ENTRY_SHIPPING', 'Shipping and Handling:');
define('ENTRY_TOTAL', 'Total:');
define('ENTRY_DATE_PURCHASED', 'Date Purchased:');
define('ENTRY_STATUS', 'Status:');
define('ENTRY_DATE_LAST_UPDATED', 'Date Last Updated:');
define('ENTRY_NOTIFY_CUSTOMER', 'Notify Customer:');
define('ENTRY_NOTIFY_COMMENTS', 'Append Comments:');
define('ENTRY_PRINTABLE', 'Print Invoice');
define('TEXT_INFO_HEADING_DELETE_ORDER', 'Delete Order');
define('TEXT_INFO_HEADING_SEND_INVOICE_ORDER', 'Send Invoice');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this order?');
define('TEXT_INFO_SEND_INVOICE_INTRO', 'Are you sure you want to send invoice to this order?');
define('TEXT_INFO_DELETE_DATA', 'Customers Name  ');
define('TEXT_INFO_DELETE_DATA_OID', 'Order Number  ');
define('TEXT_DATE_ORDER_CREATED', 'Date Created:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Last Modified:');
define('TEXT_INFO_PAYMENT_METHOD', 'Payment Method:');
define('TEXT_INFO_ABANDONDED', 'Abandoned');
define('TEXT_CARD_ENCRPYT', '<font color=green> </b> This CC number is stored Encrypted </b></font>');
define('TEXT_CARD_NOT_ENCRPYT', '<font color=red> <b>Warning !!!! This CC number is not stored Encrypted </b></font>');
define('TEXT_EXPIRES_ENCRPYT', '<font color=green> </b> This CC expire date is stored Encrypted </b></font>');
define('TEXT_EXPIRES_NOT_ENCRPYT', '<font color=red> <b>Warning !!!! This CC expire date is not stored Encrypted </b></font>');
define('TEXT_CCV_ENCRPYT', '<font color=green> </b> This CC CCV is stored Encrypted </b></font>');
define('TEXT_CCV_NOT_ENCRPYT', '<font color=red> <b>Warning !!!! This CC CCV is not stored Encrypted If blank ignore this message</b></font>');
define('TEXT_EXPIRES_REMOVED', '<font color=green> </b> This CC expire date has been removed from the store.</b></font>');
define('TEXT_CCV_REMOVED', '<font color=green> </b> CCV Code:  Not stored - due to processing regulations. Enable CCV email in module settings.</b></font>');
define('TEXT_CARD__REMOVED', '<font color=green> </b> This CC number is not store or has been removed from the store.</b></font>');
define('ENTRY_IPADDRESS', 'IP Address:');
define('ENTRY_IPISP', 'ISP:');
define('TEXT_ALL_ORDERS', 'All Orders');
define('TEXT_NO_ORDER_HISTORY', 'No Order History Available');
define('ERROR_ORDER_DOES_NOT_EXIST', 'Error : This order does not exists');
define('SUCCESS_ORDER_UPDATED', 'Success : This order has been updated');
define('WARNING_ORDER_NOT_UPDATED', 'Attention : No change was made to this order.');
define('ENTRY_PAYMENT_TRANS_ID', 'Transaction ID: ');
// Email Subject 
define('EMAIL_TEXT_SUBJECT_1', ' ' . STORE_NAME. ' Order Updated');
define('EMAIL_TEXT_SUBJECT_2', ':  ');
define('ORDER', 'Order #:');
define('ORDER_DATE_TIME', 'Order Date &amp; Time:');
// multi-vendor shipping 
define('TABLE_HEADING_PRODUCTS_VENDOR', 'Vendor');
define('TABLE_HEADING_QUANTITY', 'Qty');
define('TABLE_HEADING_VENDORS_SHIP', 'Shipper');
define('TABLE_HEADING_SHIPPING_METHOD', 'Method');
define('TABLE_HEADING_SHIPPING_COST', 'Ship Cost');
define('VENDOR_ORDER_SENT', 'Order Sent to ');
define('TABLE_HEADING_PRICE_BREAK_EXCLUDING_TAX', 'Qty Break <br>Unit Price (ex)');
define('TABLE_HEADING_PRICE_BREAK_INCLUDING_TAX', 'Qty Break <br>Unit Price (inc)');
define('TABLE_HEADING_USERS', 'User');

//Order update email content defined here
define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Update for Your Order Number: %s');
define('EMAIL_GREET_TEXT', 'Hi %s');
define('EMAIL_ORDER_UPDATE_TEXT', '<br>Thank you for waiting while we were working on your order. We are pleased to inform you that the status of your order has changed.'."\n".'
Your order has been updated to the following status : <b>%s</b>');
define('EMAIL_TEXT_ORDER_NUMBER', 'Order Number:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');
define('EMAIL_TEXT_DATE_ORDERED', 'Date Ordered:');
//define('EMAIL_TEXT_STATUS_UPDATE', 'Your order has been updated to the following status.' . "\n\n" . 'New status: %s' . "\n\n" . 'Please reply to this email if you have any questions.' . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'The comments for your order are' . "\n\n%s\n\n");
define('EMAIL_TEXT_FOOTER_UPDATED', 'Yours Sincerely,'."\n".'Name Badges International Customer Support Team'."\n\n" . EMAIL_SEPARATOR . "\n".'<B>Shipping time frame from the moment your oder will be dispatched.</B>'."\n\n".'<B>Australia Post - Standard</B>'."\n".'2 to 4 business days from the time your order has been dispatched.'."\n\n".'<B>Australia Post Express</B>'."\n".'1-2 business day the time your order has been dispatched.'."\n".'Always choose this option if you have a short timeframe.'."\n\n\n".'<B>Name Badges International Pty Ltd </B>'."\n".STORE_NAME_ADDRESS."\n\n".STORE_OWNER_EMAIL_ADDRESS.' | '.HTTP_SERVER."\n".'Name Badges International Pty Ltd | ABN 61 127 091 016'."\n\n");
 define('MODULE_PAYMENT_TRANSFER_TEXT_EMAIL_FOOTER', '<b>For bank Transfer, please use the following details with THE ORDER NUMBER as reference to transfer your total order value:</b>' . "\n\n" . 'Account Name: ' . (defined('MODULE_PAYMENT_TRANSFER_PAYTO')? MODULE_PAYMENT_TRANSFER_PAYTO : '') . "\n" . 'Account Number:  ' . (defined('MODULE_PAYMENT_TRANSFER_ACCOUNT')? MODULE_PAYMENT_TRANSFER_ACCOUNT : '') . "\n" . 'Bank Name: ' . (defined('MODULE_PAYMENT_TRANSFER_BANK')? MODULE_PAYMENT_TRANSFER_BANK : '') . "\n\n" . 'Your order will not ship until we receive payments in the above account unless you have an existing account with Name Badges International. Thank you for your business.');

/* Send Invoice Notes */
define('TEXT_INFO_SEND_INVOICE_FROM', 'From:');
define('TEXT_INFO_SEND_INVOICE_TO', 'To:');
define('TEXT_INFO_SEND_INVOICE_CC', 'Cc:');
define('TEXT_INFO_SEND_INVOICE_BCC', 'Bcc:');
define('TEXT_INFO_SEND_INVOICE_NOTE_TITLE', 'Note:');
define('TEXT_INFO_SEND_INVOICE_NOTE', 'The Cc and Bcc email is only for convenience of the client. For security reason, only the main email account can login to access the account.');

define('MY_FILES_TITLE', 'My Files');
define('ORDER_TITLE', 'Orders');
define('CUSTOMERNO_TITLE', 'Customer #');
define('ACCOUNTCREATED_TITLE', 'Account Created');
define('MY_ARTWORK_TITLE', 'Artworks');

define('ENTRY_NOTIFY_SALES_CONSULTANT', 'Notify Sales Consultant:');
define('EMAIL_NOTIFY_SALES_SUBJECT', 'Need your assistance for the Order#: %s');

?>