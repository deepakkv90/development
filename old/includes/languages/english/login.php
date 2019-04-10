<?php
/*
  $Id: login.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

//Email Validation
define("TEXT_REQUIRE_LOGIN_MESSAGE_HEADING","Require Login");
define("TEXT_REQUIRE_LOGIN_MESSAGE","This site requires you to have an account and to login to see the content.");

define('TEXT_LOGIN_ERROR_VALIDATION', 'Error: Your account is not validated.');
define('TEXT_YOU_HAVE_TO_VALIDATE', 'Please insert your Validation-key to confirm your registration');
define('ENTRY_VALIDATION_CODE', 'Validation-key:');
define('TEXT_NEW_VALIDATION_CODE', '<b>Request a new Validation-key <u>here</u></b>');

define('NAVBAR_TITLE', 'Login Page');

define('HEADING_TITLE', 'Welcome, Please Sign In');

define('HEADING_NEW_CUSTOMER', 'New Customer');
define('TEXT_NEW_CUSTOMER', 'I am a new customer.');
define('TEXT_NEW_CUSTOMER_INTRODUCTION', 'By creating an account at ' . STORE_NAME . ' you will be able to shop faster, be up to date on an orders status, and keep track of the orders you have previously made.');
define('HEADING_RETURNING_CUSTOMER', 'Returning Customer');
define('TEXT_RETURNING_CUSTOMER', 'I am a returning customer.');

define('TEXT_PASSWORD_FORGOTTEN', 'Password forgotten? Click here.');

define('TEXT_LOGIN_ERROR', ' Error: No match for E-Mail Address and/or Password.');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>Note:</b></font> Your &quot;Visitors Cart&quot; contents will be merged with your &quot;Members Cart&quot; contents once you have logged on. <a href="javascript:session_win();">[More Info]</a>');
// Begin Checkout Without Account v0.70 changes

define('PWA_FAIL_ACCOUNT_EXISTS', 'An account already exists for the email address {EMAIL_ADDRESS}.  You must login here with the password for that account before proceeding to checkout.');
// Begin Checkout Without Account v0.60 changes
define('HEADING_CHECKOUT', 'Proceed Directly to Checkout');
define('TEXT_CHECKOUT_INTRODUCTION', 'Proceed to Checkout without creating an account. By choosing this option none of your user information will be kept in our records, and you will not be able to review your order status, nor keep track of your previous orders.');
define('PROCEED_TO_CHECKOUT', 'Proceed to Checkout without Registering');
// End Checkout Without Account changes
// Eversun mod for sppc and qty price breaks
// define the email address that can change customer_group_id on login
define('SPPC_TOGGLE_LOGIN_PASSWORD', 'support@creloaded.com');
// Eversun mod for sppc and qty price breaks

define('LOGIN_TITLE1', 'Choose a Customer Group');
?>
