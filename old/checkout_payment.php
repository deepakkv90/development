<?php
/*
  $Id: checkout_payment.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');

if(isset($_GET['status']) && $_GET['status'] == 'cancelorder'){
  $restock = 'on';
  $orderID = (int)$_GET['order_id'];
  tep_remove_order($orderID, $restock);
}

// multi-vendor shipping  
// if a shipping method has not been selected for all vendors, redirect the customer to the shipping method selection page
if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
  if ((!is_array ($_SESSION['shipping']['vendor']) || count ($_SESSION['shipping']['vendor']) != count ($cart->vendor_shipping())) && SHIPPING_SKIP == 'No' ) { // No shipping selected or not all selected
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, 'error_message=' . ERROR_NO_SHIPPING_SELECTED_SELECTED, 'SSL'));
  }
}
// multi-vendor shipping eof//
// if the customer is not logged on, redirect them to the login page
if ( ! isset($_SESSION['customer_id']) ) {
  $navigation->set_snapshot();
  tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}
// if there is nothing in the customers cart, redirect them to the shopping cart page
if ($cart->count_contents() < 1) {
  tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
}
// if no shipping method has been selected, redirect the customer to the shipping method selection page
if (!isset($_SESSION['shipping']) && SHIPPING_SKIP == 'No') {
  tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
}
// avoid hack attempts during the checkout procedure by checking the internal cartID
if (isset($cart->cartID) && isset($_SESSION['cartID']) ) {
  if ($cart->cartID != $_SESSION['cartID']) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }
}
// if we have been here before and are coming back get rid of the credit covers variable
if(isset($_SESSION['credit_covers'])) unset($_SESSION['credit_covers']);  //ICW ADDED FOR CREDIT CLASS SYSTEM
// Stock Check
if ( (STOCK_CHECK == 'true') && (STOCK_ALLOW_CHECKOUT != 'true') ) {
  $products = $cart->get_products();
  for ($i=0, $n=sizeof($products); $i<$n; $i++) {
    if (tep_check_stock($products[$i]['id'], $products[$i]['quantity'])) {
      tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
      break;
    }
  }
}
// RCI checkoutpayment logic
echo $cre_RCI->get('checkoutpayment', 'logic', false);
// if no billing destination address was selected, use the customers own address as default
if (!isset($_SESSION['billto'])) {
  $_SESSION['billto'] = $_SESSION['customer_default_address_id'];
} else {
  // verify the selected billing address
  $check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and address_book_id = '" . (int)$_SESSION['billto'] . "'");
  $check_address = tep_db_fetch_array($check_address_query);
  if ($check_address['total'] != '1') {
    $_SESSION['billto'] = $_SESSION['customer_default_address_id'];
    if (isset($_SESSION['payment'])) unset($_SESSION['payment']);
  }
}
require(DIR_WS_CLASSES . 'order.php');
$order = new order;
require(DIR_WS_CLASSES . 'order_total.php');//ICW ADDED FOR CREDIT CLASS SYSTEM
$order_total_modules = new order_total;//ICW ADDED FOR CREDIT CLASS SYSTEM
$order_total_modules->clear_posts(); // ADDED FOR CREDIT CLASS SYSTEM by Rigadin in v5.13
//print_r($order_total_modules);
if (!isset($_SESSION['comments'])) $_SESSION['comments'] = '';
$total_weight = $cart->show_weight();
$total_count = $cart->count_contents();
$total_count = $cart->count_contents_virtual(); //ICW ADDED FOR CREDIT CLASS SYSTEM
// modification for checking what is order total Start
if (MODULE_ORDER_TOTAL_INSTALLED) {
 $order_total_modules->process();
 }
// load all enabled payment modules
require(DIR_WS_CLASSES . 'payment.php');
$payment_modules = new payment;
if (isset($payment->modules) && is_array($payment->modules) && sizeof($payment->modules) == 0) {
  $payment = '';
}
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT);
$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
$content = CONTENT_CHECKOUT_PAYMENT;
$javascript = $content . '.js.php';
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
<script type="text/javascript"><!--
function CVVPopUpWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,width=600,height=233,screenX=150,screenY=150,top=150,left=150')
}
//--></script>