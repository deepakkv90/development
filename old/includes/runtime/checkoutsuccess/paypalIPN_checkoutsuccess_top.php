<?php
/*
  $Id: paypalIPN_checkoutsuccess_top.php,v 1.0.0.0 2009/12/01 13:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/

global $paypal_order;

require(DIR_WS_MODULES . 'payment/paypal/classes/paypal_order.class.php');

if ((defined('MODULE_PAYMENT_PAYPAL_STATUS') && MODULE_PAYMENT_PAYPAL_STATUS == 'True') && ($_SESSION['payment'] == 'paypal')) {

  // restore the order_id to the URL for PayPal Orders
  if (isset($_SESSION['PayPal_osC'])) {
    include_once(DIR_WS_CLASSES . 'object_info.php');
    $paypal_order = new objectInfo($_SESSION['PayPal_osC']);
    $processed = isset($_SESSION['PayPal_processed']) ? $_SESSION['PayPal_processed'] : false;
    if (($paypal_order->orders_id != '') && (!$_SESSION['PayPal_processed'])) {
      $url_string = 'action=success&order_id=' . (int)$paypal_order->orders_id;
      $_SESSION['PayPal_processed'] = true;
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, $url_string));
    }
  }    
    if ((isset($_GET['action']) && $_GET['action'] == 'success')) {
        paypal_order::reset_checkout_cart_session();
    }
}
?>