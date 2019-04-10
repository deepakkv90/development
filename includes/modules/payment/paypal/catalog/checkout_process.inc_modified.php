<?php
/*
  $Id: checkout_process.inc.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

  global $payment_modules, $shipping_modules, $order, $currencies, $cart,  
            $language, $currency, $languages_id, $order_total_modules, $order_totals;

  require_once(DIR_WS_INCLUDES . 'modules/payment/paypal/database_tables.inc.php');



  if(!class_exists('order_total')) {
    include_once(DIR_WS_CLASSES . 'order_total.php');
  }

  if ( !is_object($order_total_modules) )
    $order_total_modules = new order_total;

  if ( !is_array($order_totals) )
    $order_totals = $order_total_modules->process();

  $sql_data_array = array('customers_id' => $_SESSION['customer_id'],
                          'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
                          'customers_company' => $order->customer['company'],
                          'customers_street_address' => $order->customer['street_address'],
                          'customers_suburb' => $order->customer['suburb'],
                          'customers_city' => $order->customer['city'],
                          'customers_postcode' => $order->customer['postcode'],
                          'customers_state' => $order->customer['state'],
                          'customers_country' => $order->customer['country']['title'],
                          'customers_telephone' => $order->customer['telephone'],
                          'customers_email_address' => $order->customer['email_address'],
                          'customers_address_format_id' => $order->customer['format_id'],
                          'delivery_name' => $order->delivery['firstname'] . ' ' . $order->delivery['lastname'],
                          'delivery_company' => $order->delivery['company'],
                          'delivery_street_address' => $order->delivery['street_address'],
                          'delivery_suburb' => $order->delivery['suburb'],
                          'delivery_city' => $order->delivery['city'],
                          'delivery_postcode' => $order->delivery['postcode'],
                          'delivery_state' => $order->delivery['state'],
                          'delivery_country' => $order->delivery['country']['title'],
                          'delivery_address_format_id' => $order->delivery['format_id'],
                          'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'],
                          'billing_company' => $order->billing['company'],
                          'billing_street_address' => $order->billing['street_address'],
                          'billing_suburb' => $order->billing['suburb'],
                          'billing_city' => $order->billing['city'],
                          'billing_postcode' => $order->billing['postcode'],
                          'billing_state' => $order->billing['state'],
                          'billing_country' => $order->billing['country']['title'],
                          'billing_address_format_id' => $order->billing['format_id'],
                          'payment_method' => $this->codeTitle,
                          'orders_status' => MODULE_PAYMENT_PAYPAL_PROCESSING_STATUS_ID,
                          'last_modified' => 'now()',
                          'currency' => $order->info['currency'],
                          'currency_value' => $order->info['currency_value']);
   $session_exists = false;
   if(isset($_SESSION['PayPal_osC'])) {
     $orders_session_query = tep_db_query("select osi.orders_id, o.payment_id from " . TABLE_ORDERS_SESSION_INFO . " osi left join " . TABLE_ORDERS . " o on osi.orders_id = o.orders_id where osi.txn_signature ='" . tep_db_input($PayPal_osC->txn_signature) . "'");
     $orders_check = tep_db_fetch_array($orders_session_query);
     //Now check to see whether order session info exists AND that this order
     //does not currently have an IPN.
     if ($orders_check['orders_id'] > 0 &&  $orders_check['payment_id'] == '0' ) {
       $session_exists = true;
       $this->orders_id = $orders_check['orders_id'];
     }
   }

   if($session_exists) {
    tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', "orders_id = '" . (int)$this->orders_id . "'");
   } else {
    $sql_data_array['date_purchased'] = 'now()';
    tep_db_perform(TABLE_ORDERS, $sql_data_array);
    $this->orders_id = tep_db_insert_id();
   }

 if($session_exists) {
    tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$this->orders_id . "'");
  }
  for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
    $sql_data_array = array('orders_id' => (int)$this->orders_id,
                            'title' => $order_totals[$i]['title'],
                            'text' => $order_totals[$i]['text'],
                            'value' => $order_totals[$i]['value'],
                            'class' => $order_totals[$i]['code'],
                            'sort_order' => $order_totals[$i]['sort_order']);
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
  }

  $sql_data_array = array('orders_status_id' => MODULE_PAYMENT_PAYPAL_PROCESSING_STATUS_ID,
                          'date_added' => 'now()',
                          'customer_notified' => 0,
                          'comments' => $order->info['comments']);

  if($session_exists) {
    tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array, 'update', "orders_id = '" . (int)$this->orders_id . "'");

    tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$this->orders_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$this->orders_id . "'");
  } else {
    $sql_data_array['orders_id'] = $this->orders_id;
    tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
  }

  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    $sql_data_array = array('orders_id' => (int)$this->orders_id,
                            'products_id' => tep_get_prid($order->products[$i]['id']),
                            'products_model' => $order->products[$i]['model'],
                            'products_name' => $order->products[$i]['name'],
                            'products_price' => $order->products[$i]['price'],
                            'final_price' => $order->products[$i]['final_price'],
                            'products_tax' => $order->products[$i]['tax'],
                            'products_quantity' => $order->products[$i]['qty']);

    tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);

    $order_products_id = tep_db_insert_id();

    if(is_callable(array($order_total_modules, 'update_credit_account'))) {

     global $insert_id;

     $insert_id = $this->orders_id;

     $order_total_modules->update_credit_account($i);//ICW ADDED FOR CREDIT CLASS SYSTEM
}
//------insert customer choosen option to order--------
    $attributes_exist = '0';
    if (isset($order->products[$i]['attributes'])) {
      $attributes_exist = '1';
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        if (DOWNLOAD_ENABLED == 'true') {
          $attributes_query = "select poptt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename
                               from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_TEXT . " poptt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                               left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                on pa.products_attributes_id=pad.products_attributes_id
                               where pa.products_id = '" . $order->products[$i]['id'] . "'
                                and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "'
                                and pa.options_id = popt.products_options_id
                                and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "'
                                and pa.options_values_id = poval.products_options_values_id
        and poptt.products_options_text_id = popt.products_options_id
                                and poptt.language_id = '" . $languages_id . "'
                                and poval.language_id = '" . $languages_id . "'";
          $attributes = tep_db_query($attributes_query);
        } else {
          $attributes = tep_db_query("select poptt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_TEXT . " poptt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and poptt.products_options_text_id = popt.products_options_id and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and poptt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
        }
        $attributes_values = tep_db_fetch_array($attributes);

        $sql_data_array = array('orders_id' => (int)$this->orders_id,
                                'orders_products_id' => $order_products_id,
                                'products_options_id' => $order->products[$i]['attributes'][$j]['option_id'],
                                'products_options' => $attributes_values['products_options_name'],
                                'products_options_values_id' => $order->products[$i]['attributes'][$j]['value_id'],
                                'products_options_values' => $attributes_values['products_options_values_name'],
                                'options_values_price' => $attributes_values['options_values_price'],
                                'price_prefix' => $attributes_values['price_prefix']);

        tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);
      }
    }
//------insert customer choosen option eof ----
    $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
    $total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
    $total_cost += $total_products_price;
  }
$order_total_modules->apply_credit();//ICW ADDED FOR CREDIT CLASS SYSTEM

// store the session info for notification update - gsb
  $sql_data_array = array('sendto' => $_SESSION['sendto'],
                          'billto' => $_SESSION['billto'],
                          'firstname' => $order->billing['firstname'],
                          'lastname' =>  $order->billing['lastname'],
                          'payment' => $_SESSION['payment'],
                          'payment_title' => $this->codeTitle,
                          'payment_amount' => $this->grossPaymentAmount($this->currency()),
                          'payment_currency' => $this->currency(),
                          'payment_currency_val' => $currencies->get_value($this->currency()),
                          'language' => $language,
                          'language_id' => $languages_id,
                          'currency' => $currency,
                          'currency_value' => $currencies->get_value($currency),
                          'content_type' => $order->content_type,
                          'txn_signature' => $this->setTransactionID());

  global $affiliate_clickthroughs_id, $affiliate_clientdate, $affiliate_clientbrowser, $affiliate_clientip;
  if (isset($_SESSION['affiliate_ref']) && tep_not_null($_SESSION['affiliate_ref'])) {
    $sql_data_array['affiliate_id'] = $_SESSION['affiliate_ref'];
    $sql_data_array['affiliate_clickthroughs_id'] = $affiliate_clickthroughs_id;
    $sql_data_array['affiliate_date'] = $affiliate_clientdate;
    $sql_data_array['affiliate_browser'] = $affiliate_clientbrowser;
    $sql_data_array['affiliate_ipaddress'] = $affiliate_clientip;
  }

  if($session_exists) {
    tep_db_perform(TABLE_ORDERS_SESSION_INFO, $sql_data_array, 'update', "orders_id = '" . (int)$this->orders_id . "'");
    $PayPal_osC->txn_signature = $this->digest;
  } else {
    $sql_data_array['orders_id'] = (int)$this->orders_id;
    tep_db_perform(TABLE_ORDERS_SESSION_INFO, $sql_data_array);
    $_SESSION['PayPal_osC'] = new PayPal_osC($this->orders_id,$this->digest);
  }

  if(is_callable(array($order_total_modules, 'apply_credit')))
    $order_total_modules->apply_credit();

  require(DIR_WS_INCLUDES . 'modules/payment/paypal/catalog/checkout_splash.inc.php');
?>
