<?php
/*
  $Id: ipn.lng.php,v 2.6a 2004/07/14 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2002 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

  define('UNKNOWN_TXN_TYPE', 'Unknown Transaction Type');
  define('UNKNOWN_TXN_TYPE_MSG', 'An unknown transaction (%s) occurred from ' . $_SERVER['REMOTE_ADDR'] . "\nAre you running any tests?\n\n");
  define('UNKNOWN_POST', 'Unknown Post');
  define('UNKNOWN_POST_MSG', "An unknown POST from %s was received.\nAre you running any tests?\n\n");
  define('EMAIL_SEPARATOR', "------------------------------------------------------");
  define('RESPONSE_VERIFIED', 'Verified');
  define('RESPONSE_MSG', "Connection Type\n".EMAIL_SEPARATOR."\ncurl= %s, socket= %s, domain= %s, port= %s \n\nPayPal Response\n".EMAIL_SEPARATOR."\n%s \n\n");
  define('RESPONSE_INVALID', 'Invalid PayPal Response');
  define('RESPONSE_UNKNOWN', 'Unknown Verfication');
  define('EMAIL_RECEIVER', 'Email and Business ID config');
  define('EMAIL_RECEIVER_MSG', "Store Configuration Settings\nPrimary PayPal Email Address: %s\nBusiness ID: %s\n".EMAIL_SEPARATOR."\nPayPal Configuration Settings\nPrimary PayPal Email Address: %s\nBusiness ID: %s\n\n");
  define('EMAIL_RECEIVER_ERROR_MSG', "Store Configuration Settings\nPrimary PayPal Email Address: %s\nBusiness ID: %s\n".EMAIL_SEPARATOR."\nPayPal Configuration Settings\nPrimary PayPal Email Address: %s\nBusiness ID: %s\n\nPayPal Transaction ID: %s\n\n");
  define('TXN_DUPLICATE', 'Duplicate Transaction');
  define('TXN_DUPLICATE_MSG', "A duplicate IPN transaction (%s) has been received.\nPlease check your PayPal Account\n\n");
  define('IPN_TXN_INSERT', "IPN INSERTED");
  define('IPN_TXN_INSERT_MSG', "IPN %s has been inserted\n\n");
  define('CHECK_CURRENCY', 'Validate Currency');
  define('CHECK_CURRENCY_MSG', "Incorrect Currency\nPayPal: %s\nosC: %s\n\n");
  define('CHECK_TXN_SIGNATURE', 'Validate PayPal_Shopping_Cart Transaction Signature');
  define('CHECK_TXN_SIGNATURE_MSG', "Incorrect Signature\nPayPal: %s\nosC: %s\n\n");
  define('CHECK_TOTAL', 'Validate Total Transaction Amount');
  define('CHECK_TOTAL_MSG', "Incorrect Total\nPayPal: %s\nSession: %s\n\n");
  define('DEBUG', 'Debug');
  define('DEBUG_MSG', "\nOriginal Post\n".EMAIL_SEPARATOR."\n%s\n\n\nReconstructed Post\n".EMAIL_SEPARATOR."\n%s\n\n");
  define('PAYMENT_SEND_MONEY_DESCRIPTION', 'Money Received');
  define('PAYMENT_SEND_MONEY_DESCRIPTION_MSG', "You have received a payment of %s %s \n".EMAIL_SEPARATOR."\nThis payment was sent by someone from the PayPal website, using the Send Money tab\n\n");
  define('TEST_INCOMPLETE', 'Invalid Test');
  define('TEST_INCOMPLETE_MSG', "An error has occured, mostly likely because the Custom field in the IPN Test Panel did not have a valid transaction id.\n\n\n");
  define('HTTP_ERROR', 'HTTP Error');
  define('HTTP_ERROR_MSG', "An HTTP Error occured during authentication\n".EMAIL_SEPARATOR."\ncurl= %s, socket= %s, domain= %s, port= %s\n\n");
?>
