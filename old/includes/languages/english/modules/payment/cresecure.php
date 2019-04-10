<?php
/*
  $Id: cresecure.php,v 1.0 2009/01/27 00:36:41 datazen Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
define('MODULE_PAYMENT_CRESECURE_TEXT_TITLE', 'Credit Card via CRE Secure');
define('MODULE_PAYMENT_CRESECURE_TEXT_DESCRIPTION', '<div align="center"><img src="images/cre_secure.png"/></div><div style="padding:10px;"> <b>Universal Payment System</b><br/> See for yourself why CRE Secure is the best option for online retailers who want a PCI Compliant, designer-friendly way to accept credit cards.<br/><a href="http://cresecure.com/from_admin" target="_blank">Click Here to Learn More >></a><p>Version 1.4</p><p><a href="' . tep_href_link('cc_purge.php', '', 'SSL') . '">Credit Card Purge Utility >></a></p></div>');
define('MODULE_PAYMENT_CRESECURE_BUTTON_DESCRIPTION', '</b>Your payment is protected by CRE Secure. Cardholder data will not be stored or shared. Checkout with Confidence.<b>');
define('MODULE_PAYMENT_CRESECURE_TEXT_CREDIT_CARD_OWNER', 'Credit Card Owner:');
define('MODULE_PAYMENT_CRESECURE_TEXT_CREDIT_CARD_NUMBER', 'Credit Card Number:');
define('MODULE_PAYMENT_CRESECURE_TEXT_CREDIT_CARD_EXPIRES', 'Credit Card Expiry Date:');
define('MODULE_PAYMENT_CRESECURE_TEXT_CREDIT_CARD_TYPE', 'Credit Card Type:');
define('MODULE_PAYMENT_CRESECURE_TEXT_JS_CC_OWNER', '* The owner\'s name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.');
define('MODULE_PAYMENT_CRESECURE_TEXT_CVV_LINK', 'What is it?');
define('MODULE_PAYMENT_CRESECURE_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.');
define('MODULE_PAYMENT_CRESECURE_TEXT_ERROR', 'Credit Card Error!');
define('MODULE_PAYMENT_CRESECURE_TEXT_JS_CC_CVV', '* You must enter a CVC number to proceed.');
define('TEXT_CCVAL_ERROR_CARD_TYPE_MISMATCH', 'The credit card type you\'ve chosen does not match the credit card number entered. Please check the number and credit card type and try again.');
define('TEXT_CCVAL_ERROR_CVV_LENGTH', 'The CVC number entered is incorrect. Please try again.');
?>