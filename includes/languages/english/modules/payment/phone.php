<?php
/*
  $Id: transfer.php,v 2.1 2008/08/20 00:36:41 wa4u Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  define('MODULE_PAYMENT_OVER_PHONE_TEXT_TITLE','Payments over the Phone (Credit Card - IVR)');
  define('MODULE_PAYMENT_OVER_PHONE_TEXT_DESCRIPTION', 'For Payments over the Phone (Credit Card - IVR). Simply <b>Call ' . (defined('MODULE_PAYMENT_OVER_PHONE_NUMBER')? MODULE_PAYMENT_OVER_PHONE_NUMBER : ' - ') . '</b> and follow the prompts.<br><br>Contact Name: ' . (defined('MODULE_PAYMENT_OVER_PHONE_CONTACT')? MODULE_PAYMENT_OVER_PHONE_CONTACT : ' - ') . '<br>Biller ID:<b>' . (defined('MODULE_PAYMENT_OVER_PHONE_BILLER_ID')? MODULE_PAYMENT_OVER_PHONE_BILLER_ID : ' - ') . '</b><br>Reference Number: </b>your Order Number</b><br><br>We will not ship your order until we receive payment in the above account.');
  
  define('MODULE_PAYMENT_OVER_PHONE_TEXT_EMAIL_FOOTER', 'For Payments over the Phone (Credit Card). Simply <b>Call '. (defined('MODULE_PAYMENT_OVER_PHONE_NUMBER')? MODULE_PAYMENT_OVER_PHONE_NUMBER : '') .'</b> and follow the prompts' . "\n\n" . '<b>Contact Name: ' . (defined('MODULE_PAYMENT_OVER_PHONE_CONTACT')? MODULE_PAYMENT_OVER_PHONE_CONTACT : '') . "\n"  . 'Biller ID: ' . (defined('MODULE_PAYMENT_OVER_PHONE_BILLER_ID')? MODULE_PAYMENT_OVER_PHONE_BILLER_ID : '') . "\n" . 'Reference Number: your Order Number</b>'."\n\n".'----------------------------------------------------------------------------------------------'."\n\n".'Your order will not ship until we receive payments in the above account unless you have an existing account with Name Badges International.');

?>