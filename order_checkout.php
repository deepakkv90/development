<?php
/*
  $Id: order_checkout.php,v 1.0.0 2008/05/22 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');

// Reset $shipping if free shipping is on and weight is not 0
if (tep_get_configuration_key_value('MODULE_SHIPPING_FREESHIPPER_STATUS') and $cart->show_weight()!=0) {
  if (isset($_SESSION['shipping'])) unset($_SESSION['shipping']);
}
// if the customer is not logged on, redirect them to the login page
if (!isset($_SESSION['customer_id']) || $_SESSION['customer_id'] == '') {
  $navigation->set_snapshot();
  tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}
// if there is nothing in the customers cart, redirect them to the shopping cart page
if ($cart->count_contents() < 1) {
  tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
}
// Validate Cart for checkout
$valid_to_checkout= true;
$cart->get_products(true);
if (!$valid_to_checkout) {
  $messageStack->add_session('header', ERROR_VALID_TO_CHECKOUT, 'error');
  tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
}
// if no shipping destination address was selected, use the customers own address as default
if (isset($_SESSION['shipping'])) {
  $shipping = $_SESSION['shipping'];
}
if (!isset($_SESSION['sendto'])) {
  $_SESSION['sendto'] = $_SESSION['customer_default_address_id'];
} else {
  // verify the selected shipping address
  $check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and address_book_id = '" . (int)$_SESSION['sendto'] . "'");
  $check_address = tep_db_fetch_array($check_address_query);
  if ($check_address['total'] != '1') {
    $_SESSION['sendto'] = $customer_default_address_id;
    if (isset($_SESSION['shipping'])) unset($_SESSION['shipping']);
  }
}
if (isset($_SESSION['payment'])) {
  $payment = $_SESSION['payment'];
}
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
// register a random ID in the session to check throughout the checkout procedure
// against alterations in the shopping cart contents
$cartID = $cart->cartID;  
if (!isset($_SESSION['cartID'])) $_SESSION['cartID'] = $cartID;

$total_weight = $cart->show_weight();
$total_count = $cart->count_contents();
// load all enabled shipping modules
// MVS Start
if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
  include(DIR_WS_CLASSES . 'vendor_shipping.php');
} else {
  include(DIR_WS_CLASSES . 'shipping.php');
}
// MVS end
require_once(DIR_WS_CLASSES . 'http_client.php');
$shipping_modules = new shipping;

// MVS Start
if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
  $quotes = $shipping_modules->quote();
} else {
  $free_shipping = false;
  $free_shipping_over = '';
  $data_array = explode(',', MODULE_SHIPPING_FREESHIPPER_OVER);
  foreach ($data_array as $value) {
    $tmp = explode('-', $value);
    if(!isset($_SESSION['sppc_customer_group_id'])) {   
      if (is_numeric($tmp[1]) && $cart->show_total() >= $tmp[1]) {
          $free_shipping_over = $tmp[1];
      } else {
        if (defined('MODULE_SHIPPING_FREESHIPPER_OVER') && is_numeric(MODULE_SHIPPING_FREESHIPPER_OVER)) {
          $free_shipping_over = MODULE_SHIPPING_FREESHIPPER_OVER;
        }
      }
      break;
    } else {
       if ($tmp[0] == $_SESSION['sppc_customer_group_id']) {
        if (is_numeric($tmp[1]) && $cart->show_total() >= $tmp[1]) {
          $free_shipping_over = $tmp[1];
        }
        break;
      }
    }  
  }

  if (is_numeric($free_shipping_over) && $order->info['total'] >= $free_shipping_over && defined('MODULE_SHIPPING_FREESHIPPER_STATUS') && MODULE_SHIPPING_FREESHIPPER_STATUS == 'True' ) {
    $free_shipping = true;
    include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
    $freeshipping_over_amount = $free_shipping_over;
    // Check for free shipping zone
    $chk_val = chk_free_shipping_zone(MODULE_SHIPPING_FREESHIPPER_ZONE);
    if ($chk_val == 0) {
      $free_shipping = false;
    }
  }

  // if the order contains only virtual products, forward the customer to the billing page as
  // a shipping address is not needed
  if (($order->content_type == 'virtual') || ($order->content_type == 'virtual_weight') ) {
    $shipping = false;
    $_SESSION['sendto'] = false;
  } else {
    if ( (tep_count_shipping_modules() > 0) || ($free_shipping == true) ) {
      $shipping = true;
    } else {
      $shipping = false;
      $_SESSION['sendto'] = false;
    }
  }
  $_SESSION['shipping'] = $shipping;

  // get all available shipping quotes
  if ($shipping !== false) {
      $quotes = $shipping_modules->quote();
     // if no shipping method has been selected, automatically select the cheapest method.
    // if the modules status was changed when none were available, to save on implementing
    // a javascript force-selection method, also automatically select the cheapest shipping
    // method if more than one module is now enabled
      if (!isset($_SESSION['shipping']) || (isset($_SESSION['shipping']) && ($shipping == true) && (tep_count_shipping_modules() > 0))) $_SESSION['shipping'] = $shipping_modules->cheapest();
  }

  if($free_shipping == true) {
    $_SESSION['shipping'] = array('id' => 'free_free',
                                  'title' => FREE_SHIPPING_TITLE,
                                  'cost' => 0);
  } 
}// MVS end

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ORDER_CHECKOUT);
require(DIR_WS_CLASSES . 'order_total.php');
$order_total_modules = new order_total;
$order_total_modules->clear_posts();
if (isset($_POST['coupon_redeem']) && $_POST['coupon_redeem'] == '1') {
		if (tep_not_null($_POST['gv_redeem_code'])) {
		 	if (isset($_POST['shipping']) && tep_not_null($_POST['shipping'])) {
			  	$order->info['shipping_cost'] = $shipping['cost'];
  				$order->info['subtotal'] += $shipping['cost'];
		  		$order->info['total'] = $order->info['subtotal'];				
 			}
		} else {
		 	tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=ot_coupon&error=' . urlencode(ERROR_EMPTY_REDEEM_COUPON), 'SSL'));
		}
}
$order_total_modules->collect_posts();
$order_total_modules->pre_confirmation_check();
$order_total_modules->process();

require(DIR_WS_CLASSES . 'payment.php');
$payment_modules = new payment;

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ORDER_CHECKOUT, '', 'SSL'));

$content = CONTENT_ORDER_CHECKOUT;
$javascript = $content . '.js.php';
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_WS_INCLUDES . 'application_bottom.php');
?>      