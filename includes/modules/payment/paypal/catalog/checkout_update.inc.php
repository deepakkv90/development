<?php
/*
  $Id: checkout_update.inc.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

  $PayPal_osC_Order->setAccountHistoryInfoURL(tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $PayPal_osC_Order->orderID, 'SSL', false));
  $PayPal_osC_Order->setCheckoutProcessLanguageFile(DIR_WS_LANGUAGES . $PayPal_osC_Order->language . '/' . FILENAME_CHECKOUT_PROCESS);
  $PayPal_osC_Order->updateProducts($order);
  $PayPal_osC_Order->notifyCustomer($order);
  $_SESSION['affiliate_ref'] = $PayPal_osC_Order->affiliate_id;
  $_SESSION['affiliate_clickthroughs_id'] = $PayPal_osC_Order->affiliate_clickthroughs_id;
  $affiliate_clientdate = $PayPal_osC_Order->affiliate_date;
  $affiliate_clientbrowser = $PayPal_osC_Order->affiliate_browser;
  $affiliate_clientip = $PayPal_osC_Order->affiliate_ipaddress;
  if (tep_not_null($_SESSION['affiliate_ref']) && $_SESSION['affiliate_ref'] != '0') {
    define('MODULE_PAYMENT_PAYPAL_SHOPPING_IPN_AFFILIATE','True');
    $insert_id = $PayPal_osC_Order->orderID;
    include(DIR_WS_INCLUDES . 'affiliate_checkout_process.php');
  }
  $PayPal_osC_Order->updateOrderStatus(MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID);
  $PayPal_osC_Order->removeOrdersSession();
?>
