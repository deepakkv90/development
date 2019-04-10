<?php
/*
  $Id: checkout_process.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('EMAIL_TEXT_SUBJECT', 'Your Order Number: ');
define('EMAIL_TEXT_ORDER_NUMBER', 'Order Number:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');
define('EMAIL_TEXT_DATE_ORDERED', 'Date Ordered:');
define('EMAIL_TEXT_PRODUCTS', 'Products');
define('EMAIL_TEXT_SUBTOTAL', 'Sub-Total:');
define('EMAIL_TEXT_TAX', 'GST - Tax:        ');
define('EMAIL_TEXT_SHIPPING', 'Shipping and Handling: ');
define('EMAIL_TEXT_TOTAL', 'Total:    ');
define('EMAIL_TEXT_DELIVERY_ADDRESS', 'Delivery Address');
define('EMAIL_TEXT_BILLING_ADDRESS', 'Billing Address');
define('EMAIL_TEXT_PAYMENT_METHOD', 'Payment Method');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('TEXT_EMAIL_VIA', 'via');
define('TEXT_SHIPWIRE_TRANSACTION_ID', 'Shipwire Fullfillment Transaction Successful. Transaction ID: ');
define('EMAIL_TEXT_ORDER_PROCESS','Thank you for your order from Name Badges International. Once your package ships we will send an email with your order information.'."\n".'You can check the status of your order by logging into your account.'."\n".'If you have any questions about your order please contact us by simply replying to this email.'."\n\n".'Your order confirmation is below. Thank you again for your business.'."\n\n");
?>