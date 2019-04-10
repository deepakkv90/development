<?php
/*
  $Id: ipn.lng.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2002 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

  define('EMAIL_SEPARATOR', "------------------------------------------------------");

  define('UNKNOWN_TXN_TYPE', 'Unknown Transaction Type');
  define('UNKNOWN_TXN_TYPE_MSG', 'An unknown transaction (%s) occurred from ' . $_SERVER['REMOTE_ADDR'] . "\nAre you running any tests?");

  define('UNKNOWN_POST', 'Unknown Post');
  define('UNKNOWN_POST_MSG', "An unknown POST from %s was received.\nAre you running any tests?");

  define('CONNECTION_TYPE', 'Connection Type');
  define('CONNECTION_TYPE_MSG', "curl: %s transport: %s domain: %s port: %s ");


  define('PAYPAL_RESPONSE', 'PayPal Response');
  define('PAYPAL_RESPONSE_MSG', "%s");

  define('EMAIL_RECEIVER', 'Email and Business ID config');
  define('EMAIL_RECEIVER_MSG', "Store Configuration Settings\nPrimary PayPal Email Address: %s\nBusiness ID: %s\n".EMAIL_SEPARATOR."\nPayPal Configuration Settings\nPrimary PayPal Email Address: %s\nBusiness ID: %s");
  define('EMAIL_RECEIVER_ERROR_MSG', "Store Configuration Settings\nPrimary PayPal Email Address: %s\nBusiness ID: %s\n".EMAIL_SEPARATOR."\nPayPal Configuration Settings\nPrimary PayPal Email Address: %s\nBusiness ID: %s\n\nPayPal Transaction ID: %s");

  define('TXN_DUPLICATE', 'Duplicate Transaction');
  define('TXN_DUPLICATE_MSG', "A duplicate IPN transaction (%s) has been received.\nPlease check your PayPal Account");

  define('IPN_TXN_INSERT', 'IPN INSERTED');
  define('IPN_TXN_INSERT_MSG', "IPN %s has been inserted");

  define('CART_TEST', 'Cart Test');
  define('CART_TEST_MSG', "Store (converted) order total: %s %s\nPayPal MC Total: %s %s");
  define('CART_TEST_ERR_MSG', "Invalid Cart Test\n".CART_TEST_MSG);

  define('CHECK_TXN_SIGNATURE', 'Validate '.IPN_PAYMENT_MODULE_NAME.' Transaction Signature');
  define('CHECK_TXN_SIGNATURE_MSG', "Incorrect Signature\nPayPal: %s\nosC: %s");

  define('CHECK_TOTAL', 'Validate Total Transaction Amount');
  define('CHECK_TOTAL_MSG', "Incorrect Total\nPayPal: %s\nSession: %s");

  define('DEBUG', IPN_PAYMENT_MODULE_NAME.' Debug Email Notification');
  define('DEBUG_MSG', "%s");

  define('PAYMENT_SEND_MONEY_DESCRIPTION', 'Money Received');
  define('PAYMENT_SEND_MONEY_DESCRIPTION_MSG', "You have received a payment of %s %s \n".EMAIL_SEPARATOR."\nThis payment was sent by someone from the PayPal website, using the Send Money tab");

  define('PAYPAL_AUCTION','Ebay Auction');
  define('PAYPAL_AUCTION_MSG','You have received an Ebay/PayPal Auction Instant Payment Notification, please login to your osCommerce Administration for further details.');

  define('TEST_COMPLETE', 'Test Complete');
  define('TEST_INCOMPLETE', 'Invalid Test');
  define('TEST_INCOMPLETE_MSG', "An error has occured, mostly likely because the Custom field in the IPN Test Panel did not have a valid transaction id.\n");

  define('HTTP_ERROR', 'HTTP Error');
  define('HTTP_ERROR_MSG', "An HTTP Error occured during authentication\n".EMAIL_SEPARATOR."\ncurl: %s transport: %s domain: %s port: %s");

  define('IPN_EMAIL', 'Attention!');
  define('IPN_EMAIL_MSG', "This is email has NOT been sent by PayPal.\n\nYou have received this email via the osCommerce ".IPN_PAYMENT_MODULE_NAME." Contribution\n\nTo discontinue receiving this notice disable 'Debug Email Notifications' in your osCommerce PayPal configuration panel.");
?>
