<?php
/*
  $Id: paypalxc_checkoutpayment_logic.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/

global $payment, $billto, $sendto, $customer_default_address_id;

if (defined('MODULE_PAYMENT_PAYPAL_XC_STATUS') && MODULE_PAYMENT_PAYPAL_XC_STATUS == 'True' && $_SESSION['skip_payment'] == '1') {
  if (!isset($_SESSION['billto'])) {
    $_SESSION['billto'] = false;      
  }
  if ( $_SESSION['billto'] == false ) {
    if ( $_SESSION['sendto'] != false ) {
      $_SESSION['billto'] = $_SESSION['sendto'];
    } else {
      $_SESSION['billto'] = $_SESSION['customer_default_address_id'];
    }
  }  
  if (!isset($_SESSION['payment'])) {
    $_SESSION['payment'] = false;  
  }
  $_SESSION['payment'] = 'paypal_xc';
  tep_redirect(tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'));
}

if ( defined('MODULE_PAYMENT_PAYPAL_XC_STATUS') && MODULE_PAYMENT_PAYPAL_XC_STATUS == 'True' && $_SESSION['skip_shipping'] == '1' ) {
  tep_redirect(tep_href_link(FILENAME_EC_PROCESS, '', 'SSL'));
}
?>
