<?php
/*
  $Id: checkout_processing.php,v 1.0.0 2008/05/22 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ORDER_CHECKOUT);
  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;
  // Stock Check
  $any_out_of_stock = false;
  if (STOCK_CHECK == 'true') {
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      if (tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty'])) {
        $any_out_of_stock = true;
      }
    }
    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
      tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
    }
  }
  if ((tep_count_shipping_modules() > 0)) {
    if ((isset($_POST['shipping'])) && (strpos($_POST['shipping'], '_')) ) {
      $shipping = $_POST['shipping'];			
      list($module, $method) = explode('_', $shipping);
      if ( is_object($$module) || ($shipping == 'free_free') ) {
        if ($shipping == 'free_free') {
          $quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
          $quote[0]['methods'][0]['cost'] = '0';
        } else {
          $quote = $shipping_modules->quote($method, $module);
        }
        if (isset($quote['error'])) {
          if (isset($_SESSION['shipping'])) unset($_SESSION['shipping']);
        } else {
          if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
            $shipping = array('id' => $shipping,
                                           'title' => (($free_shipping == true) ?  $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
                              'cost' => $quote[0]['methods'][0]['cost']);
          }
        }
      } else {
        if (isset($_SESSION['shipping'])) unset($_SESSION['shipping']);
      }
    }
  }
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping;
  // load the selected shipping module
  //  $shipping_modules = new shipping($shipping);
  if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
    if ((!is_array ($_SESSION['shipping']['vendor']) || count ($_SESSION['shipping']['vendor']) != count ($cart->vendor_shipping())) && SHIPPING_SKIP == 'No' ) {    // No shipping selected or not all selected
      tep_redirect(tep_href_link(FILENAME_ORDER_CHECKOUT, 'error_message=' . ERROR_NO_SHIPPING_SELECTED_SELECTED, 'SSL'));
    }
  } else {
    if ( tep_count_shipping_modules() > 0 && $_SESSION['shipping'] !== false && !isset($_POST['shipping']) && (!strpos($_POST['shipping'], '_')) ) {    
      tep_redirect(tep_href_link(FILENAME_ORDER_CHECKOUT, 'shipping_error=1', 'SSL'));   
    }
  }


  if (isset($_POST['payment'])) {
    $payment = $_POST['payment'];
    $_SESSION['payment'] = $payment;
  } elseif (isset($_SESSION['payment'])) {
    $payment = $_SESSION['payment'];
  }
  if (tep_not_null($_POST['comments'])) {
    $comments = tep_db_prepare_input($_POST['comments']);
    $_SESSION['comments'] = $comments; 
    $GLOBALS['comments'] = $comments;
  }
  if ($payment == 'paypal_wpp_ec') {
    $payment = 'paypal_wpp';
    $_SESSION['skip_shipping'] = '1';
    tep_redirect(tep_href_link(FILENAME_EC_PROCESS, '', 'SSL'));
  }
  // load the selected payment module
  require(DIR_WS_CLASSES . 'payment.php');
  if ($_SESSION['credit_covers']) $payment=''; //ICW added for CREDIT CLASS
  $payment_modules = new payment($payment);
  $payment_modules->update_status();
  $order = new order;
  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;
  $order_total_modules->collect_posts();
  $order_total_modules->pre_confirmation_check();
  if ( (is_array($payment_modules->modules)) && (sizeof($payment_modules->modules) > 1) && (!is_object($$payment)) && (!$_SESSION['credit_covers']) ) {
    tep_redirect(tep_href_link(FILENAME_ORDER_CHECKOUT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
  }
  if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_PROCESSING, '', 'SSL'));
  
  $content = CONTENT_CHECKOUT_PROCESSING;
  
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
  
?>
<script type="text/javascript"><!--
document.checkout_processing.submit();
--></script>