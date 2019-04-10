<?php
/*
  $Id: moneyorder.php,v 2.1 2008/06/12 00:36:41 datazen Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
define('MODULE_PAYMENT_MONEYORDER_TEXT_TITLE', 'Check/Money Order');
define('MODULE_PAYMENT_MONEYORDER_TEXT_DESCRIPTION', 'Make Payable To:&nbsp;' . (defined('MODULE_PAYMENT_MONEYORDER_PAYTO')? MODULE_PAYMENT_MONEYORDER_PAYTO : '') . '<br><br>Send To:<br>' . nl2br(STORE_NAME_ADDRESS) . '<br><br>' . 'Your order will not ship until we receive payment.');
define('MODULE_PAYMENT_MONEYORDER_TEXT_EMAIL_FOOTER', "Make Payable To: ". (defined('MODULE_PAYMENT_MONEYORDER_PAYTO') ? MODULE_PAYMENT_MONEYORDER_PAYTO : '') . "\n\nSend To:\n" . STORE_NAME_ADDRESS . "\n\n" . 'Your order will not ship until we receive payment.');
?>