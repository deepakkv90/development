<?php

/*

  $Id: checkout_shipping.php,v 1.1.1.1 2004/03/04 23:37:57 ccwjr Exp $



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2003 osCommerce



  Released under the GNU General Public License

  Shoppe Enhancement Controller - Copyright (c) 2003 WebMakers.com

  Linda McGrath - osCommerce@WebMakers.com

*/

require('includes/application_top.php');

require('includes/classes/http_client.php');

require(DIR_WS_CLASSES . 'order.php');

// reset $shipping if free shipping is on and weight is not 0

if (tep_get_configuration_key_value('MODULE_SHIPPING_FREESHIPPER_STATUS') and $cart->show_weight()!=0) {

  unset($_SESSION['shipping']);

}

// if the customer is not logged on, redirect them to the login page

if ( ! isset($_SESSION['customer_id']) ) {

  $navigation->set_snapshot();

  tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));

}

// if there is nothing in the customers cart, redirect them to the shopping cart page

if ($cart->count_contents() < 1) {

  tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));

}

// if CDpath session exists - unset the session

if (isset($_SESSION['CDpath'])) {

  unset($_SESSION['CDpath']);                               

}

// validate Cart for checkout

$valid_to_checkout= true;

$cart->get_products(true);

if (!$valid_to_checkout) {

  $messageStack->add_session('header', ERROR_VALID_TO_CHECKOUT, 'error');

  tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));

}


//For check and update myfiles
if(is_array($cart->myfiles)) {
	$cart->update_product_files(); //Modified dec 03, 2010
}



if( !isset($_SESSION['customer_default_address_id']) ) {

  $str = tep_db_fetch_array(tep_db_query("select customers_default_address_id from customers where customers_id = ".$_SESSION['customer_id'].""));

  $_SESSION['customer_default_address_id'] = $str['customers_default_address_id'];

}



// if no shipping destination address was selected, use the customers own address as default

if ( ! isset($_SESSION['sendto']) ) {

  $_SESSION['sendto'] = $_SESSION['customer_default_address_id'];

} else {

  // verify the selected shipping address

  $check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and address_book_id = '" . (int)$_SESSION['sendto'] . "'");

  $check_address = tep_db_fetch_array($check_address_query);

  if ($check_address['total'] != '1') {

    $_SESSION['sendto'] = $_SESSION['customer_default_address_id'];

    if(isset($_SESSION['shipping']))   unset($_SESSION['shipping']);

  }

}

$order = new order;

// START: display min. order. qty. mod  - Mar 08 2011
	
if(PRODUCT_LIST_MIN_ORDER_QTY == 1) {  

	for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
	
	  if (tep_check_min_order_qty($order->products[$i]['id'], $order->products[$i]['qty'])) {
	  
		tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
		
	  }
	  
	}
	
}
 // END: display min. order. qty. mod

// register a random ID in the session to check throughout the checkout procedure

// against alterations in the shopping cart contents

$_SESSION['cartID'] = $cart->cartID;

// RCI checkout shipping logic

echo $cre_RCI->get('checkoutshipping', 'logic', false); 

$total_weight = $cart->show_weight();

$total_count = $cart->count_contents();



$order->content_type = 'mixed';

// if the order contains only virtual products, forward the customer to the billing page as a shipping address is not needed

if (($order->content_type == 'virtual') || ($order->content_type == 'virtual_weight') || SHIPPING_SKIP == 'Always' || (SHIPPING_SKIP == 'If Weight = 0' && $total_weight == 0)) {

  $_SESSION['shipping'] = false;

  $_SESSION['sendto'] = false;

  tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));

}

if (defined('MVS_STATUS') && MVS_STATUS == 'true') {

  include(DIR_WS_CLASSES . 'vendor_shipping.php');

} else {

  include(DIR_WS_CLASSES . 'shipping.php');

}

$shipping_modules = new shipping;

// load all enabled shipping modules

$free_shipping = false;

$free_shipping_over = '';

$data_array = explode(',', MODULE_SHIPPING_FREESHIPPER_OVER);

foreach ($data_array as $value) {

  $tmp = explode('-', $value);

  if(!isset($_SESSION['sppc_customer_group_id'])) {   

    if (is_numeric($tmp[1]) && $cart->show_total() >= $tmp[1]) {

        $free_shipping_over = $tmp[1];

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

if(defined('MODULE_SHIPPING_FREESHIPPER_STATUS') && MODULE_SHIPPING_FREESHIPPER_STATUS == 'True') {

  if (is_numeric($free_shipping_over) && $order->info['total'] >= $free_shipping_over) {

    $free_shipping = true;

    include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');

    $freeshipping_over_amount = $free_shipping_over;

    // Check for free shipping zone

    $chk_val = chk_free_shipping_zone(MODULE_SHIPPING_FREESHIPPER_ZONE);

    if ($chk_val == 0) {

      $free_shipping = false;

    }

  }

}

// process the selected shipping method

if ( isset($_POST['action']) && ($_POST['action'] == 'process') ) {

  if (!isset($_SESSION['comments'])) $_SESSION['comments'] = '';

  if (tep_not_null($_POST['comments'])) {

    $_SESSION['comments'] = tep_db_prepare_input($_POST['comments']);

  }
  
  //update due date 
  
  if (!isset($_SESSION['due_date'])) $_SESSION['due_date'] = '';
  
  if (tep_not_null($_POST['due_date'])) {
  
    $due_date = date("Y-m-d", strtotime(tep_db_prepare_input($_POST['due_date'])));
	
    $_SESSION['due_date'] = $due_date;
	
  }


  // determine if free shipping or skip shipping page if enabled

  if (defined('MVS_STATUS') && MVS_STATUS == 'true') {

    $total_shipping_cost = 0;

    $shipping_title = MULTIPLE_SHIP_METHODS_TITLE;

    $vendor_shipping = $cart->vendor_shipping();

    $shipping = array();

    foreach ($vendor_shipping as $vendor_id => $vendor_data) {

      $products_shipped = $_POST['products_' . $vendor_id];

      $products_array = explode ("_", $products_shipped);

      $shipping_data = $_POST['shipping_' . $vendor_id];

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

    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));

  } else {

    if ( (tep_count_shipping_modules() > 0) || ($free_shipping == true) ) {

      if ( (isset($_POST['shipping'])) && (strpos($_POST['shipping'], '_')) ) {

        $_SESSION['shipping'] = $_POST['shipping'];

        list($module, $method) = explode('_', $_SESSION['shipping']);

        if ( is_object($$module) || ($_SESSION['shipping'] == 'free_free') ) {

          if ($_SESSION['shipping'] == 'free_free') {

            $quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;

            $quote[0]['methods'][0]['cost'] = get_free_shipping_handling_cost(MODULE_SHIPPING_FREESHIPPER_COST);;

          } else {

            $quote = $shipping_modules->quote($method, $module);

          }

          if (isset($quote['error'])) {

            unset($_SESSION['shipping']);

          } else {
			/*
            if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {

              $_SESSION['shipping'] = array('id' => $_SESSION['shipping'],

                                            'title' => (($free_shipping == true) ?  $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),

                                            'cost' => $quote[0]['methods'][0]['cost']);

              tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));

            }
			*/
			if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
			
              $_SESSION['shipping'] = array('id' => $_SESSION['shipping'],
			  
                                            'title' => (($free_shipping == true) ?  $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . (($quote[0]['methods'][0]['title']=="") ? "Excl. Tax" : $quote[0]['methods'][0]['title']) . ')'),
											
                                            'cost' => $quote[0]['methods'][0]['cost']);
				
				//update due date
				if (tep_not_null($quote[0]['duedate'])) {
					$_SESSION['due_date'] = $quote[0]['duedate'];
				}
				
              tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
			  
            }

          }

        } else {

          unset($_SESSION['shipping']);

        }

      } else {

        $_GET['shipping_error'] = 1;

      }

    } else {

      $_SESSION['shipping'] = false;

      tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));

    }    

  }

}

// RCI checkout_shipping logic

echo $cre_RCI->get('checkoutshipping', 'logic', false);

// get all available shipping quotes

$quotes = $shipping_modules->quote();

// if no shipping method has been selected, automatically select the cheapest method.

// if the modules status was changed when none were available, to save on implementing

// a javascript force-selection method, also automatically select the cheapest shipping

// method if more than one module is now enabled

if (( !isset($_SESSION['shipping']) || ( isset($_SESSION['shipping']) && ($_SESSION['shipping'] == false) && (tep_count_shipping_modules() > 1) ) ) && MVS_STATUS != 'true') $_SESSION['shipping'] = $shipping_modules->cheapest();

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SHIPPING);

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));

$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));

$content = CONTENT_CHECKOUT_SHIPPING;

$javascript = $content . '.js';

require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_WS_INCLUDES . 'application_bottom.php');

?>