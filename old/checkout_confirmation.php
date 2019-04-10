<?php

/*

  $Id: checkout_confirmation.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2003 osCommerce



  Released under the GNU General Public License

*/



 require('includes/application_top.php');

// if the customer is not logged on, redirect them to the login page

  if ( ! isset($_SESSION['customer_id']) ) {

    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));

    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));

  }



// if there is nothing in the customers cart, redirect them to the shopping cart page

  if ($cart->count_contents() < 1) {

    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));

  }



// avoid hack attempts during the checkout procedure by checking the internal cartID

  if (isset($cart->cartID) && isset($_SESSION['cartID'])) {

    if ($cart->cartID != $_SESSION['cartID']) {

      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));

    }

  }



// if no shipping method has been selected, redirect the customer to the shipping method selection page

  if (!isset($_SESSION['shipping']) && SHIPPING_SKIP == 'No') {

    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));

  }



  if (!isset($_SESSION['payment'])) $_SESSION['payment'] = ''; 

  if (isset($_POST['payment'])) $_SESSION['payment'] = $_POST['payment'];

  

  $payment = $_SESSION['payment'];



  $_SESSION['comments'] = isset($_POST['comments']) ? tep_db_prepare_input($_POST['comments']) : '';

  

    $_SESSION['purchase_number'] = isset($_POST['purchase_number']) ? tep_db_prepare_input($_POST['purchase_number']) : '';



// set shipping addresss to customer's default address if without shipping

  

  if ( !isset($_SESSION['sendto']) ) {    

    $_SESSION['sendto'] = false;    

  }

  if ($_SESSION['sendto'] == false) {

    $_SESSION['sendto'] = $_SESSION['customer_default_address_id'];

  }

  // load the selected shipping module

  require(DIR_WS_CLASSES . 'shipping.php');

  $shipping_modules = new shipping($_SESSION['shipping']);



  // load the selected payment module

  require(DIR_WS_CLASSES . 'payment.php');

  if (isset($_SESSION['credit_covers'])) $_SESSION['payment']=''; //ICW added for CREDIT CLASS

  $payment_modules = new payment($_SESSION['payment']);

  $payment_modules->update_status();



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
  
  // START: display min. order. qty. mod  - Mar 03 2011
  
    $any_under_min_order_qty = false;
	
	if(PRODUCT_LIST_MIN_ORDER_QTY == 1) {  
	
		for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
		
		  if (tep_check_min_order_qty($order->products[$i]['id'], $order->products[$i]['qty'])) {
		  
			$any_under_min_order_qty = true;
			
			tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
			
		  }
		  
		}
		
	}
	
  // END: display min. order. qty. mod



// Ok, the various checks have been applied.  Now if this is thesecond time thru

// we want to proceed.  The checks are to be applied before the confirmation screen is presented

// and once again after the confirm button is clicked to reduce possible errors in the order.

  if ( isset($_POST['action']) && $_POST['action'] == 'proceed' ) {

    if (isset($$payment->form_action_url)) {

      tep_redirect($$payment->form_action_url);

    } else {

      tep_redirect(tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL'));

    }

  }  



  // RCI code start

  echo $cre_RCI->get('checkoutconfirmation', 'logic', false);

  // RCI code eof



//ICW ADDED FOR CREDIT CLASS SYSTEM

  require(DIR_WS_CLASSES . 'order_total.php');

//ICW ADDED FOR CREDIT CLASS SYSTEM

  $order_total_modules = new order_total;

//ICW ADDED FOR CREDIT CLASS SYSTEM

  $order_total_modules->collect_posts();

//ICW ADDED FOR CREDIT CLASS SYSTEM

  $order_total_modules->pre_confirmation_check();



// ICW CREDIT CLASS Amended Line

//  if ( ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {

  if ( (is_array($payment_modules->modules)) && (sizeof($payment_modules->modules) > 1) && (!is_object($$payment)) && (!isset($_SESSION['credit_covers'])) ) {

    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));

  }



  if (is_array($payment_modules->modules)) {

    $payment_modules->pre_confirmation_check();

  }



//ICW Credit class amendment Lines below repositioned

//  require(DIR_WS_CLASSES . 'order_total.php');

//  $order_total_modules = new order_total;



  

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION);



  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));

  $breadcrumb->add(NAVBAR_TITLE_2);



  $content = CONTENT_CHECKOUT_CONFIRMATION;

  

  if (ACCOUNT_CONDITIONS_REQUIRED == 'true') $javascript = 'checkout_confirmation.js.php';



  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
  
  if ($_SESSION['cartID']!="") {
  	$cartIDuni=$_SESSION['cartID'];
  }else if($cart->cartID!=""){
  	$cartIDuni = $cart->cartID;
  }else{
  	$cartIDuni= time();
  }
  
	$all_1['prod_info']=$order->products;
	$all_1['user_det']=$order->billing;
	$all_1['prod_all']=$order->info;
	$all_1['cartid']= $cartIDuni;
	  //getHtmlTable("<table>".str_replace(array("</tbody>","<tbody>"), array("",""), $order_total_modules->output())."</table>");
	if(isset($_SESSION['all_pro_details']))
		unset($_SESSION['all_pro_details']);
		
	$_SESSION['all_pro_details'] = serialize($all_1);
	
	
    require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
