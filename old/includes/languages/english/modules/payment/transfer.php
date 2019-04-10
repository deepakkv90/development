<?php
/*
  $Id: transfer.php,v 2.1 2008/08/20 00:36:41 wa4u Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  define('MODULE_PAYMENT_TRANSFER_TEXT_TITLE','Bank Transfer (Pay Offline)');
  define('MODULE_PAYMENT_TRANSFER_TEXT_DESCRIPTION', 'FOR BANK TRANSFER  PAYMENT PLEASE USE <b>THE ORDER NUMBER</b> AS REFERENCE located in your email confirmation. <br><br>Please use the following details to transfer your total order value:<br><br>Account Name: ' . (defined('MODULE_PAYMENT_TRANSFER_PAYTO')? MODULE_PAYMENT_TRANSFER_PAYTO : '') . '<br><b>Account Number: ' . (defined('MODULE_PAYMENT_TRANSFER_ACCOUNT')? MODULE_PAYMENT_TRANSFER_ACCOUNT : '') . '</b><br>Bank Name: ' . (defined('MODULE_PAYMENT_TRANSFER_BANK')? MODULE_PAYMENT_TRANSFER_BANK : '') . '<br><br>We will not ship your order until we receive payment in the above account unless you have an existing account with Name Badges International.');
  define('MODULE_PAYMENT_TRANSFER_TEXT_EMAIL_FOOTER', '<b>For bank Transfer, please use the following details with THE ORDER NUMBER as reference to transfer your total order value:</b>' . "\n\n" . 'Account Name: ' . (defined('MODULE_PAYMENT_TRANSFER_PAYTO')? MODULE_PAYMENT_TRANSFER_PAYTO : '') . "\n" . 'Account Number:  ' . (defined('MODULE_PAYMENT_TRANSFER_ACCOUNT')? MODULE_PAYMENT_TRANSFER_ACCOUNT : '') . "\n" . 'Bank Name: ' . (defined('MODULE_PAYMENT_TRANSFER_BANK')? MODULE_PAYMENT_TRANSFER_BANK : '') . "\n\n" . 'Your order will not ship until we receive payments in the above account unless you have an existing account with Name Badges International. Thank you for your business.');

?>