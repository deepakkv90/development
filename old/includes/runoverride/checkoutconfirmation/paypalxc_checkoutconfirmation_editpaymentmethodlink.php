<?php
/*
  $Id: paypalxc_checkoutconfirmation_editpaymentmethodlink.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
if (isset($_SESSION['skip_payment']) && $_SESSION['skip_payment'] == '1') {
  echo '<td class="main"><b>' . HEADING_PAYMENT_METHOD . '</b></td>' . "\n";
} else {
  echo '<div class="left"><h2>' . HEADING_PAYMENT_METHOD . '</h2></div> <div class="right text-right"><a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '">' . TEXT_EDIT . '</a></div>' . "\n";
}
?>