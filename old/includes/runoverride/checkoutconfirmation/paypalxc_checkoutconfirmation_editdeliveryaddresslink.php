<?php
/*
  $Id: paypalxc_checkoutconfirmation_editdeliveryaddresslink.php,v 1.1.0.0 2008/02/28 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
global $payment;

if (eregi('paypal', $payment)) {
  if (!isset($_SESSION['skip_payment']) && $_SESSION['skip_payment'] != '1') {
    echo '<td class="main"><b>' . HEADING_DELIVERY_ADDRESS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a></td>' . "\n";
  } else {
    echo '<td class="main"><b>' . HEADING_DELIVERY_ADDRESS . '</b></td>' . "\n";
  }
} else {
  echo '<td class="main"><b>' . HEADING_DELIVERY_ADDRESS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a></td>' . "\n";
}
?>