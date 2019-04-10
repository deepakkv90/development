<?php
/*
  $Id: get_order_total.php,v 1.0.0 2008/05/22 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
require('includes/classes/http_client.php');
require(DIR_WS_CLASSES . 'order.php');
$order = new order;

$free_shipping = false;
if(isset($_SESSION['sppc_customer_group_id'])) {   
  $data_array = explode(',', MODULE_SHIPPING_FREESHIPPER_OVER);
  foreach ($data_array as $value) {
    $tmp = explode('-', $value);
    if ($tmp[0] == $_SESSION['sppc_customer_group_id']) {
      if (is_numeric($tmp[1]) && $order->info['total'] >= $tmp[1] && defined('MODULE_SHIPPING_FREESHIPPER_OVER') && MODULE_SHIPPING_FREESHIPPER_OVER && MODULE_SHIPPING_FREESHIPPER_STATUS == 'True') {
        $free_shipping = true;
        include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
        $freeshipping_over_amount = $tmp[1];
      }
      break;
    }
  }
} else { 
  if (defined('MODULE_SHIPPING_FREESHIPPER_OVER') && is_numeric(MODULE_SHIPPING_FREESHIPPER_OVER) && $order->info['total'] >= MODULE_SHIPPING_FREESHIPPER_OVER && MODULE_SHIPPING_FREESHIPPER_STATUS == 'True') {
    $free_shipping = true;
    include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
    $freeshipping_over_amount = MODULE_SHIPPING_FREESHIPPER_OVER;
  } 
}



if (isset($_SESSION['shipping'])) $shipping = $_SESSION['shipping'];
if ($shipping !== false) {
    if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
    include(DIR_WS_CLASSES . 'vendor_shipping.php');
    $shipping_modules = new shipping;
    $total_shipping_cost = 0;
    $shipping_title = MULTIPLE_SHIP_METHODS_TITLE;
    $vendor_shipping = $cart->vendor_shipping();




    $shipping = array();
    foreach ($vendor_shipping as $vendor_id => $vendor_data) {

      $products_shipped = $_GET['products_' . $vendor_id];
      $products_array = explode ("_", $products_shipped);
      $shipping_data = $_GET['shipping_' . $vendor_id];
      $shipping_array = explode ("_", $shipping_data);



      $module = $shipping_array[0];
      $method = $shipping_array[1];
      $ship_tax = $shipping_array[2];


      if ( is_object($$module) || ($module == 'free') ) {
        if ($module == 'free') {
          $quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
          $quote[0]['methods'][0]['cost'] = '0';
        } else {
          $total_weight = $vendor_shipping[$vendor_id]['weight'];
          $shipping_weight = $total_weight;
          $cost = $vendor_shipping[$vendor_id]['cost'];
          $total_count = $vendor_shipping[$vendor_id]['qty'];
          $quote = $shipping_modules->quote($method, $module, $vendor_id);
        }

   
        if (isset($quote['error'])) {
          unset($_SESSION['shipping']);
        } else {
          if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
            $output[$vendor_id] = array('id' => $module . '_' . $method,
                                        'title' => $quote[0]['methods'][0]['title'],
                                        'ship_tax' => $ship_tax,
                                        'products' => $products_array,
                                        'cost' => $quote[0]['methods'][0]['cost']
                                       );
            $total_ship_tax += $ship_tax;
            $total_shipping_cost += $quote[0]['methods'][0]['cost'];
          }
        }
      }
    }
    if ($free_shipping == true) {
      $shipping_title = $quote[0]['module'];
    } elseif (count($output) <2) {
      $shipping_title = $quote[0]['methods'][0]['title'];
    }



    $_SESSION['shipping'] = array('id' => $shipping,
                      'title' => $shipping_title,
                      'cost' => $total_shipping_cost,
                      'shipping_tax_total' => $total_ship_tax,
                      'vendor' => $output
                     );      
  

 
  } else {
    require(DIR_WS_CLASSES . 'shipping.php');
    $shipping_modules = new shipping;
    $total_weight = $cart->show_weight();
    $total_count = $cart->count_contents();
    if ((tep_count_shipping_modules() > 0)) {
      if ((isset($_GET['shipping'])) && (strpos($_GET['shipping'], '_')) ) {
        $shipping = $_GET['shipping'];							
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
            if ((isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost']))) {
              $_SESSION['shipping'] = array('id' => $shipping,
                                'title' => (($free_shipping == true) ?  $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
                                'cost' => $quote[0]['methods'][0]['cost']);
            }
         }				
        } else {
           if (isset($_SESSION['shipping'])) unset($_SESSION['shipping']); 
        }
      }
    }
  }
  
}
$order = new order;
require(DIR_WS_CLASSES . 'order_total.php');
$order_total_modules = new order_total;
$order_total_modules->collect_posts();
$order_total_modules->pre_confirmation_check();
$order_total_modules->process();
echo '<table><tr><td class="main">' . $order_total_modules->output() . '</td></tr></table>';
?>