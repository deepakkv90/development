<?php
/*
  $Id: paypalxc_checkoutsuccess_bottom.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/

if (defined('MODULE_PAYMENT_PAYPAL_XC_STATUS') && MODULE_PAYMENT_PAYPAL_XC_STATUS == 'True') {
  if (isset($_SESSION['token'])) unset($_SESSION['token']);
  if (isset($_SESSION['PayerID'])) unset($_SESSION['PayerID']);
  if (isset($_SESSION['skip_payment'])) unset($_SESSION['skip_payment']);
  if (isset($_SESSION['paypalxc_create_account'])) unset($_SESSION['paypalxc_create_account']);
  if (isset($_SESSION['temp_password'])) unset($_SESSION['temp_password']);
  if (isset($_SESSION['skip_shipping'])) unset($_SESSION['skip_shipping']);
  if (isset($_SESSION['nologin'])) unset($_SESSION['nologin']);
}
?>