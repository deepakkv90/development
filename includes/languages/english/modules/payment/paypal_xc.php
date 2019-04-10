<?php
/*
  $Id: paypal_xc.php,v 2.1 2008/06/12 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
define('MODULE_PAYMENT_PAYPAL_XC_TEXT_TITLE', 'PayPal Express Checkout');
define('MODULE_PAYMENT_PAYPAL_XC_MODULE_TITLE', 'Certified PayPal Express Checkout');
define('MODULE_PAYMENT_PAYPAL_EC_TEXT_TITLE', 'PayPal Express Checkout');
define('MODULE_PAYMENT_PAYPAL_XC_TEXT_DESCRIPTION', 'Certified PayPal Express Checkout');
define('MODULE_PAYMENT_PAYPAL_XC_ERROR_HEADING', 'We\'re sorry, but we were unable to process your credit card.');
define('MODULE_PAYMENT_PAYPAL_XC_TEXT_CARD_ERROR', 'The credit card information you entered contains an error.  Please check it and try again.');
define('MODULE_PAYMENT_PAYPAL_XC_TEXT_CREDIT_CARD_CHECKNUMBER_LOCATION', '(located at the back of the credit card)');
define('MODULE_PAYMENT_PAYPAL_XC_TEXT_DECLINED', 'Your credit card was declined. Please try another card or contact your bank for more info.<br><br>');
define('MODULE_PAYMENT_PAYPAL_XC_INVALID_RESPONSE', 'PayPal returned invalid or incomplete data to complete your order.  Please try again or select an alternate payment method.<br><br>');
define('MODULE_PAYMENT_PAYPAL_XC_TEXT_GEN_ERROR', 'An error occured when we tried to contact PayPal\'s servers.<br><br>');
define('MODULE_PAYMENT_PAYPAL_XC_TEXT_ERROR', 'An error occured when we tried to process your credit card.<br><br>');
define('MODULE_PAYMENT_PAYPAL_XC_TEXT_BAD_CARD', 'We apologize for the inconvenience, but PayPal only accepts Visa, Master Card, Discover, and American Express.  Please use a different credit card.');
define('MODULE_PAYMENT_PAYPAL_XC_TEXT_BAD_LOGIN', 'There was a problem validating your account.  Please try again.');
define('MODULE_PAYMENT_PAYPAL_XC_TEXT_JS_CC_OWNER', '* The owner\'s name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n');
define('MODULE_PAYMENT_PAYPAL_XC_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');
define('MODULE_PAYMENT_PAYPAL_XC_TEXT_EC_HEADER', 'Fast, Secure Checkout with PayPal:');
define('MODULE_PAYMENT_PAYPAL_XC_TEXT_BUTTON_TEXT', 'Save time. Checkout securely.<br>Pay without sharing your financial information.');
define('MODULE_PAYMENT_PAYPAL_XC_TEXT_STATE_ERROR', 'The state assigned to your account is not valid.  Please go into your account settings and change it.');
define('MODULE_PAYMENT_PAYPAL_XC_DIRECTPAY_ERROR', 'Credit Card payment through PayPal was not enabled, please use Express Checkout');
define('MODULE_PAYMENT_PAYPAL_XC_TEXT_JS_CC_CVC', '* You must enter a CCV number to proceed.\n');
define('MODULE_PAYMENT_PAYPAL_XC_TEXT_CVV_LINK', 'What is it?');
define('MODULE_PAYMENT_PAYPAL_XC_TEXT_EC_EXPLAIN', 'Allows you to pay for your order using your PayPal account.');
define('MODULE_PAYMENT_PAYPAL_XC_TEXT_ACCEPTANCE_MARK', 'Save time. Check out securely. Pay without sharing your financial information.');
?>