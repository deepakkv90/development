<?php
/*
  $Id: cresecure.php (hosted version),v 1.0 RC2 2009/04/1 00:36:41 datazen Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class cresecure {
    var $code, $title, $description, $enabled, $sort_order, $pci;
    var $allowed_types, $cc_card_type, $cc_card_number, $cc_expiry_month, $cc_expiry_year, $cc_card_code;

// class constructor
  function cresecure() {
    global $order;
    $this->code = 'cresecure';
    $this->title = (defined('MODULE_PAYMENT_CRESECURE_TEXT_TITLE')) ? MODULE_PAYMENT_CRESECURE_TEXT_TITLE : '';
    $this->description = (defined('MODULE_PAYMENT_CRESECURE_TEXT_DESCRIPTION')) ? MODULE_PAYMENT_CRESECURE_TEXT_DESCRIPTION : '';
    $this->sort_order = (defined('MODULE_PAYMENT_CRESECURE_SORT_ORDER')) ? MODULE_PAYMENT_CRESECURE_SORT_ORDER : 0;
    $this->enabled = (defined('MODULE_PAYMENT_CRESECURE_STATUS') && MODULE_PAYMENT_CRESECURE_STATUS == 'True') ? true : false;
    $this->accepted_cc = (defined('MODULE_PAYMENT_CRESECURE_ACCEPTED_CC')) ? MODULE_PAYMENT_CRESECURE_ACCEPTED_CC : '';
    $this->order_status = (defined('MODULE_PAYMENT_CRESECURE_ORDER_STATUS_ID') && (int)MODULE_PAYMENT_CRESECURE_ORDER_STATUS_ID > 0) ? MODULE_PAYMENT_CRESECURE_ORDER_STATUS_ID : 0;
    $this->version = (!file_exists(DIR_WS_CLASSES . 'rci.php')) ? 'OSC' : 'CRE'; 
    $this->pci = true;
    if (defined('MODULE_PAYMENT_CRESECURE_TEST_MODE') && MODULE_PAYMENT_CRESECURE_TEST_MODE == 'True') {
      //$this->form_action_url = 'https://cregateway.net/securepayments/a1/cc_collection.php';  // cre only internal test url
      $this->form_action_url = 'https://sandbox-cresecure.net/securepayments/a1/cc_collection.php';  // sandbox url
    } else {
      $this->form_action_url = 'https://cresecure.net/securepayments/a1/cc_collection.php';  // production url
    }
    if (is_object($order)) $this->update_status();
    $this->card_types = array('American Express' => 'American Express',
                              'DinersCart Blanche' => 'DinersCart Blanche',
                              'Discover' => 'Discover',
                              'JCB' => 'JCB',
                              'MasterCard' => 'MasterCard',
                              'Citibank Financial' => 'Citibank Financial',
                              'RevolutionCard' => 'RevolutionCard',
                              'Visa' => 'Visa');
    $this->allowed_types = array();
    // credit card pulldown list
    $cc_array = explode(', ', MODULE_PAYMENT_CRESECURE_ACCEPTED_CC);
    while (list($key, $value) = each($cc_array)) {
      $this->allowed_types[$value] = $this->card_types[$value];
    }
  }

  // class methods
  function update_status() {
    global $order;
    if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_CRESECURE_ZONE > 0) ) {
      $check_flag = false;
      $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_CRESECURE_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
      while ($check = tep_db_fetch_array($check_query)) {
        if ($check['zone_id'] < 1) {
          $check_flag = true;
          break;
        } elseif ($check['zone_id'] == $order->billing['zone_id']) {
          $check_flag = true;
          break;
        }
      }
      if ($check_flag == false) {
        $this->enabled = false;
      }
    }
  }

  //concatenate to get CC images
  function get_cc_images() {
    $cc_images = '';
    reset($this->allowed_types);
    while (list($key, $value) = each($this->allowed_types)) {
      if ($key == 'MasterCard') $key = 'Mastercard';
      if ($key == 'American Express') $key = 'Amex'; 
      if ($key == 'DinersCart Blanche') $key = 'DinersCart_Blanche';
      $cc_images .= tep_image(DIR_WS_ICONS . $key . '.gif', $value);
    }
    return $cc_images;
  }

  function javascript_validation() {
    return false;     
  }

  function selection() {
    global $cart_cresecure_ID;

    if (isset($_SESSION['cart_cresecure_ID'])) {
      $cart_cresecure_ID = $_SESSION['cart_cresecure_ID'];
      $order_id = substr($cart_cresecure_ID, strpos($cart_cresecure_ID, '-')+1);
      $check_query = tep_db_query('select orders_id from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '" limit 1');
      if (tep_db_num_rows($check_query) < 1) {
        tep_db_query('delete from ' . TABLE_ORDERS . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_TOTAL . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_DOWNLOAD . ' where orders_id = "' . (int)$order_id . '"');

        unset($_SESSION['cart_cresecure_ID']);
      }
    }        
    $selection = array('id' => $this->code,
                       'module' => $this->title . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $this->get_cc_images() . '<br>' . MODULE_PAYMENT_CRESECURE_BUTTON_DESCRIPTION);
    return $selection;
  }

  function pre_confirmation_check() {
    global $cartID, $cart;
    if (isset($cart->cartID) && $cart->cartID != '') {
      $cartID = $cart->cartID;     
    } else {
      $cartID = $cart->generate_cart_id();
    }
    if ((!isset($_SESSION['cartID'])) || (isset($_SESSION['cartID']) && $_SESSION['cartID'] == '')) {
      $_SESSION['cartID'] = $cartID;
    }
    return true;
  }

  function confirmation() {
    global $cartID, $cart_cresecure_ID, $customer_id, $languages_id, $currencies, $order, $order_total_modules;

    $insert_order = false;

    if (isset($_SESSION['cart_cresecure_ID'])) {
      $cart_cresecure_ID = $_SESSION['cart_cresecure_ID'];
      $order_id = substr($cart_cresecure_ID, strpos($cart_cresecure_ID, '-')+1);

      $curr_check = tep_db_query("select currency from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
      $curr = tep_db_fetch_array($curr_check);

      if ( ($curr['currency'] != $order->info['currency']) || ($cartID != substr($cart_cresecure_ID, 0, strlen($cartID))) ) {
        $check_query = tep_db_query('select orders_id from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '" limit 1');

        if (tep_db_num_rows($check_query) < 1) {
          tep_db_query('delete from ' . TABLE_ORDERS . ' where orders_id = "' . (int)$order_id . '"');
          tep_db_query('delete from ' . TABLE_ORDERS_TOTAL . ' where orders_id = "' . (int)$order_id . '"');
          tep_db_query('delete from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '"');
          tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS . ' where orders_id = "' . (int)$order_id . '"');
          tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . ' where orders_id = "' . (int)$order_id . '"');
          tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_DOWNLOAD . ' where orders_id = "' . (int)$order_id . '"');
        }
        $insert_order = true;
      }
    } else {
      $insert_order = true;
    }        
    if ($insert_order == true) {
      if (defined('MODULE_ADDONS_ONEPAGECHECKOUT_STATUS') && MODULE_ADDONS_ONEPAGECHECKOUT_STATUS == 'True') {
        $order_total_modules->collect_posts();
        $order_total_modules->pre_confirmation_check();
        $order_total_modules->process();
      }
      $order_totals = array();
      if (is_array($order_total_modules->modules)) {
        reset($order_total_modules->modules);
        while (list(, $value) = each($order_total_modules->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
            for ($i=0, $n=sizeof($GLOBALS[$class]->output); $i<$n; $i++) {
              if (tep_not_null($GLOBALS[$class]->output[$i]['title']) && tep_not_null($GLOBALS[$class]->output[$i]['text'])) {
              // temp fix for buysafe issue
              if ($class == 'ot_buysafe') $GLOBALS[$class]->output[$i]['title'] = 'buySAFE Bond Guarantee:';
                $order_totals[] = array('code' => $GLOBALS[$class]->code,
                                        'title' => $GLOBALS[$class]->output[$i]['title'],
                                        'text' => $GLOBALS[$class]->output[$i]['text'],
                                        'value' => $GLOBALS[$class]->output[$i]['value'],
                                        'sort_order' => $GLOBALS[$class]->sort_order);
              }
            }
          }
        }
      }
      $sql_data_array = array('customers_id' => $customer_id,
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
                              'payment_method' => $order->info['payment_method'],
                              'payment_info' => $cartID,
                              'cc_type' => $order->info['cc_type'],
                              'cc_owner' => $order->info['cc_owner'],
                              'cc_number' => $order->info['cc_number'],
                              'cc_expires' => $order->info['cc_expires'],
                              'date_purchased' => 'now()',
                              'orders_status' => MODULE_PAYMENT_CRESECURE_ORDER_STATUS_ID,
                              'currency' => $order->info['currency'],
                              'currency_value' => $order->info['currency_value']);
      tep_db_perform(TABLE_ORDERS, $sql_data_array);
      $insert_id = tep_db_insert_id();
      for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
        $sql_data_array = array('orders_id' => $insert_id,
                                'title' => $order_totals[$i]['title'],
                                'text' => $order_totals[$i]['text'],
                                'value' => $order_totals[$i]['value'],
                                'class' => $order_totals[$i]['code'],
                                'sort_order' => $order_totals[$i]['sort_order']);
        tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
      }
      $_SESSION['products_ordered'] = '';
      for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
        $sql_data_array = array('orders_id' => $insert_id,
                                'products_id' => tep_get_prid($order->products[$i]['id']),
                                'products_model' => $order->products[$i]['model'],
                                'products_name' => $order->products[$i]['name'],
                                'products_price' => $order->products[$i]['price'],
                                'final_price' => $order->products[$i]['final_price'],
                                'products_tax' => $order->products[$i]['tax'],
                                'products_quantity' => $order->products[$i]['qty']);
        tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
        $order_products_id = tep_db_insert_id();
        
        // product attributes
        $products_ordered_attributes = '';
        if (isset($order->products[$i]['attributes'])) {               
          for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
            if ($this->version == 'CRE') {  
              $sql_data_array = array('orders_id' => $insert_id,
                                      'orders_products_id' => $order_products_id,
                                      'products_options' => $order->products[$i]['attributes'][$j]['option'],
                                      'products_options_values' => $order->products[$i]['attributes'][$j]['value'],
                                      'options_values_price' => $order->products[$i]['attributes'][$j]['price'],
                                      'price_prefix' => $order->products[$i]['attributes'][$j]['prefix'],
                                      'products_options_id' => $order->products[$i]['attributes'][$j]['option_id'],
                                      'products_options_values_id' => $order->products[$i]['attributes'][$j]['value_id']);
            } else {
              $sql_data_array = array('orders_id' => $insert_id,
                                      'orders_products_id' => $order_products_id,
                                      'products_options' => $order->products[$i]['attributes'][$j]['option'],
                                      'products_options_values' => $order->products[$i]['attributes'][$j]['value'],
                                      'options_values_price' => $order->products[$i]['attributes'][$j]['price'],
                                      'price_prefix' => $order->products[$i]['attributes'][$j]['prefix']);                  
            }
            tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);
            if (DOWNLOAD_ENABLED == 'true') {     
              $attributes_query = "SELECT pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename
                                     from " . TABLE_PRODUCTS_ATTRIBUTES . " pa,
                                          " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                   WHERE pa.products_id = '" . $order->products[$i]['id'] . "'
                                     and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "'
                                     and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "'
                                     and pa.products_attributes_id = pad.products_attributes_id";
                                     
              $attributes = tep_db_query($attributes_query);
              $attributes_values = tep_db_fetch_array($attributes);
              if ( isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename']) ) {
                $sql_data_array = array('orders_id' => $insert_id,
                                        'orders_products_id' => $order_products_id,
                                        'orders_products_filename' => $attributes_values['products_attributes_filename'],
                                        'download_maxdays' => $attributes_values['products_attributes_maxdays'],
                                        'download_count' => $attributes_values['products_attributes_maxcount']);
                tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
              }
            }
            $products_ordered_attributes .= "\n\t" . $order->products[$i]['attributes'][$j]['option'] . ' ' . $order->products[$i]['attributes'][$j]['value'] . ' ' . $order->products[$i]['attributes'][$j]['prefix'] . ' ' . $currencies->display_price($order->products[$i]['attributes'][$j]['price'], tep_get_tax_rate($products[$i]['tax_class_id']), 1);
          }
        } 
        $_SESSION['products_ordered'] .= $order->products[$i]['qty'] . ' x ' . tep_db_decoder($order->products[$i]['name']) . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
      } // end for      
      $cart_cresecure_ID = $cartID . '-' . $insert_id;
      $_SESSION['cart_cresecure_ID'] = $cart_cresecure_ID;   
    }
    $confirmation = array('title' => $this->title . ': ' . $this->cc_card_type);  
   
    return $confirmation;
  }

  function process_button() {
    global  $order, $currency, $language, $cart_cresecure_ID;
   
    $order_id = substr($cart_cresecure_ID, strpos($cart_cresecure_ID, '-')+1);    
    $username = (defined('MODULE_PAYMENT_CRESECURE_LOGIN')) ? MODULE_PAYMENT_CRESECURE_LOGIN : '';
    $password = (defined('MODULE_PAYMENT_CRESECURE_PASS')) ? MODULE_PAYMENT_CRESECURE_PASS : '';  
    $test = (defined('MODULE_PAYMENT_CRESECURE_TEST_MODE') && MODULE_PAYMENT_CRESECURE_TEST_MODE == 'True') ? true : false;                             
    $branded_url = (file_exists('checkout_payment_template.php')) ? 'checkout_payment_template.php' : 'default';   
    $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';
    if ($request_type == 'SSL') {
      $content_template_url = 'https://' . substr(tep_href_link($branded_url, '', 'NONSSL', false, false), strpos(tep_href_link($branded_url, '', 'NONSSL', false, false), '://')+3);
    } else {
      $content_template_url = '';  // no SSL = force default template page
    }      
    // calculate total weight and formulate order description
    $total_weight = 0;
    $order_desc = '';
    for ($i=0; $i<sizeof($order->products); $i++) {
      $total_weight = $total_weight + ((int)$order->products[$i]['qty'] * (float)$order->products[$i]['weight']);
      $order_desc .= $order->products[$i]['qty'] . '-' . ($order->products[$i]['name']) . '**';
    }
    
    $process_button_string = tep_draw_hidden_field('CRESecureID', $username) .
                             tep_draw_hidden_field('CRESecureAPIToken', $password) .
                             
                             tep_draw_hidden_field('customer_company', (isset($order->billing['company'])) ? $order->billing['company'] : $order->customer['company']) .
                             tep_draw_hidden_field('customer_firstname', (isset($order->billing['firstname'])) ? $order->billing['firstname'] : $order->customer['firstname']) .
                             tep_draw_hidden_field('customer_lastname', (isset($order->billing['lastname'])) ? $order->billing['lastname'] : $order->customer['lastname']) .
                             tep_draw_hidden_field('customer_address', (isset($order->billing['street_address'])) ? $order->billing['street_address'] : $order->customer['street_address']) .
                             tep_draw_hidden_field('customer_email', (isset($order->billing['email_address'])) ? $order->billing['email_address'] : $order->customer['email_address']) .
                             tep_draw_hidden_field('customer_phone', (isset($order->billing['telephone'])) ? $order->billing['telephone'] : $order->customer['telephone']) .
                             tep_draw_hidden_field('customer_city', (isset($order->billing['city'])) ? $order->billing['city'] : $order->customer['city']) . 
                             tep_draw_hidden_field('customer_state', (isset($order->billing['state'])) ?  tep_get_zone_code($order->billing['country']['id'], $order->billing['zone_id'], '') :  tep_get_zone_code($order->customer['country']['id'], $order->customer['zone_id'], '')) . 
                             tep_draw_hidden_field('customer_postal_code', (isset($order->billing['postcode'])) ? $order->billing['postcode'] : $order->customer['postcode']) .
                             tep_draw_hidden_field('customer_country', (isset($order->billing['country']['iso_code_3'])) ? $order->billing['country']['iso_code_3'] : $order->customer['country']['iso_code_3']) .
                             
                             tep_draw_hidden_field('delivery_company', $order->delivery['company']) .
                             tep_draw_hidden_field('delivery_firstname', $order->delivery['firstname']) .
                             tep_draw_hidden_field('delivery_lastname', $order->delivery['lastname']) .
                             tep_draw_hidden_field('delivery_address', $order->delivery['street_address']) .
                             tep_draw_hidden_field('delivery_email', $order->delivery['email_address']) .
                             tep_draw_hidden_field('delivery_phone', $order->delivery['telephone']) .
                             tep_draw_hidden_field('delivery_city', $order->delivery['city']) . 
                             tep_draw_hidden_field('delivery_state',  tep_get_zone_code($order->delivery['country']['id'], $order->delivery['zone_id'], '')) .
                             tep_draw_hidden_field('delivery_postal_code', $order->delivery['postcode']) .
                             tep_draw_hidden_field('delivery_country', $order->delivery['country']['iso_code_3']) .

                             tep_draw_hidden_field('total_amt', number_format($order->info['total'], 2)) .
                             tep_draw_hidden_field('total_weight', $total_weight) .
                             tep_draw_hidden_field('order_desc', $order_desc) .
                             tep_draw_hidden_field('order_id', $order_id) .
                             tep_draw_hidden_field('customer_id', $_SESSION['customer_id']) .
                             tep_draw_hidden_field('currency_code', $currency) .
                             tep_draw_hidden_field('lang', $language) .       
                             tep_draw_hidden_field('allowed_types', implode('|', $this->allowed_types)) .                                                                         
                             tep_draw_hidden_field('sess_id', tep_session_id()) .
                             tep_draw_hidden_field('sess_name', tep_session_name()) .
                             tep_draw_hidden_field('ip_address', $_SERVER["REMOTE_ADDR"]) .
                             tep_draw_hidden_field('return_url', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', false, false)) .
                             tep_draw_hidden_field('content_template_url', $content_template_url);
                             
    return $process_button_string;
  }

  function before_process() {
    global $customer_id, $language, $order, $order_id, $order_totals, $sendto, $billto, $languages_id, $payment;
    global $currencies, $cart, $order_total_modules;
    global $$payment;
  
    $cancel = (isset($_GET['action']) && $_GET['action'] == 'cancel') ? true : false;
    $order_id = (isset($_GET['order_id'])) ? (int)$_GET['order_id'] : 0; 
    $response_code = (isset($_GET['code'])) ? $_GET['code'] : '';
    $response_msg = (isset($_GET['msg'])) ? $_GET['msg'] : '';
    $response_error = (isset($_GET['error'])) ? $_GET['error'] : '';
    $response_mPAN = (isset($_GET['mPAN'])) ? $_GET['mPAN'] : '';
    $response_name = (isset($_GET['name'])) ? $_GET['name'] : '';
    $response_type = (isset($_GET['type'])) ? $_GET['type'] : ''; 
    $response_exp = (isset($_GET['exp'])) ? $_GET['exp'] : ''; 
    $response_transID = (isset($_GET['TxnGUID'])) ? $_GET['TxnGUID'] : '';
    if ($response_code != '000') {
      if($response_error == true) {
        tep_db_query('delete from ' . TABLE_ORDERS . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_TOTAL . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_DOWNLOAD . ' where orders_id = "' . (int)$order_id . '"');            
        $error_msg = $response_code . ' : ' . $response_msg; 
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode($error_msg), 'SSL', true, false));
      } else if ($cancel == true) {           
        tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL'));   
      } else {
        tep_db_query('delete from ' . TABLE_ORDERS . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_TOTAL . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_DOWNLOAD . ' where orders_id = "' . (int)$order_id . '"');              
        $error_msg = ($error_msg = '') ? '799: Unknown payment module error' : $response_code . ' : ' . $response_msg; 
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode($error_msg), 'SSL', true, false));
      }
    }  
    // update transaction_log
    $sql_data_array = array('transaction_id' => $response_transID,
                            'order_id' => $order_id,
                            'created_date' => 'now()');
    tep_db_perform('transaction_log', $sql_data_array);
    // update orders table
    $sql_data_array = array('cc_owner' => $response_name,
                            'cc_type' => $response_type,
                            'cc_number' => $response_mPAN,
                            'cc_expires' => $response_exp,
                            );
    tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id = ' . (int)$order_id);
    // update orders status
    tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . (MODULE_PAYMENT_CRESECURE_ORDER_STATUS_COMPLETE_ID > 0 ? (int)MODULE_PAYMENT_CRESECURE_ORDER_STATUS_COMPLETE_ID : (int)DEFAULT_ORDERS_STATUS_ID) . "', last_modified = now() where orders_id = '" . (int)$order_id . "'");
    // update order status history
    $comments = (defined('MODULE_PAYMENT_CRESECURE_TEST_MODE') && MODULE_PAYMENT_CRESECURE_TEST_MODE == 'True') ? ':TESTMODE: ' . $order->info['comments'] : $order->info['comments']; 
    $sql_data_array = array('orders_id' => $order_id,
                            'orders_status_id' => (MODULE_PAYMENT_CRESECURE_ORDER_STATUS_COMPLETE_ID > 0 ? (int)MODULE_PAYMENT_CRESECURE_ORDER_STATUS_COMPLETE_ID : (int)DEFAULT_ORDERS_STATUS_ID),
                            'date_added' => 'now()',
                            'customer_notified' => (SEND_EMAILS == 'true') ? '1' : '0',
                            'comments' => $comments);
    tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
  
    // stock update 
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      if (STOCK_LIMITED == 'true') {
        if (DOWNLOAD_ENABLED == 'true') {
          $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename
                              FROM " . TABLE_PRODUCTS . " p
                              LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                              ON p.products_id=pa.products_id
                              LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                              ON pa.products_attributes_id=pad.products_attributes_id
                              WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
          // will work with only one option for downloadable products
          // otherwise, we have to build the query dynamically with a loop
          $products_attributes = $order->products[$i]['attributes'];
          if (is_array($products_attributes)) {
            $stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
          }
          $stock_query = tep_db_query($stock_query_raw);
        } else {
          $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
        }
        if (tep_db_num_rows($stock_query) > 0) {
          $stock_values = tep_db_fetch_array($stock_query);
          // do not decrement quantities if products_attributes_filename exists
          if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
            $stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];
          } else {
            $stock_left = $stock_values['products_quantity'];
          }
          tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
          if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
            tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
          }
        }
      }
      // update products_ordered (for bestsellers list)
      tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
    }
    // include affiliate checkout process
    if (isset($_SESSION['affiliate_ref'])) {
      require(DIR_WS_INCLUDES . 'affiliate_checkout_process.php');
    }    
    if ($this->version == 'CRE' && is_object($order_total_modules)) $order_total_modules->apply_credit();   
    // send emails
    $this->sendEmail();
    // reset the cart
    $cart->reset(true);
    // unregister session variables used during checkout
    unset($_SESSION['sendto']);
    unset($_SESSION['billto']);
    unset($_SESSION['shipping']);
    unset($_SESSION['payment']);
    unset($_SESSION['comments']);
    unset($_SESSION['products_ordered']);
    if (isset($_SESSION['cot_gv'])) unset($_SESSION['cot_gv']);
    if (isset($_SESSION['credit_covers'])) unset($_SESSION['credit_covers']);  
    if ($this->version == 'CRE' && is_object($order_total_modules)) $order_total_modules->clear_posts();
    $transID = (isset($response_transID) && $response_transID != '') ? '&trans_id=' . $response_transID : '';
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'order_id='. $order_id . '&customer_id=' . $_SESSION['customer_id'] . $transID, 'SSL'));
  } 

  function after_process() {
    return false;
  }
        
  function sendEmail() {
    global $order, $order_id, $order_totals, $products_ordered, $products_ordered_attributes;
    global $$payment;
    
    if ( ! isset($_SESSION['noaccount']) ) {
      $email_order = STORE_NAME . "\n" .
                     EMAIL_SEPARATOR . "\n" .
                     EMAIL_TEXT_ORDER_NUMBER . ' ' . $order_id . "\n" .
                     EMAIL_TEXT_INVOICE_URL . ' ' .
                     tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $order_id, 'SSL', false) . "\n" .
                     EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";
    } else {
      $email_order = STORE_NAME . "\n" .
                     EMAIL_SEPARATOR . "\n" .
                     EMAIL_TEXT_ORDER_NUMBER . ' ' . $order_id . "\n" .
                     EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";
    }
    if ($order->info['comments']) {
      $email_order .= tep_db_output($order->info['comments']) . "\n\n";
    }
    $email_order .= EMAIL_TEXT_PRODUCTS . "\n" .
                    EMAIL_SEPARATOR . "\n" .
                    $_SESSION['products_ordered'] .
                    EMAIL_SEPARATOR . "\n";
    for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
      $email_order .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "\n";
    }

    if ($order->content_type != 'virtual') {
      $email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" . EMAIL_SEPARATOR . "\n" .
      tep_address_label($_SESSION['customer_id'], $_SESSION['sendto'], 0, '', "\n") . "\n";
    }
    $email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" . EMAIL_SEPARATOR . "\n" .
    tep_address_label($_SESSION['customer_id'], $_SESSION['billto'], 0, '', "\n") . "\n\n";
  
    $payment = $_SESSION['payment'];
    if (is_object($$payment)) {
      $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" .
                      EMAIL_SEPARATOR . "\n";
      $payment_class = $$payment;
      $email_order .= $payment_class->title . "\n\n";
      if ($payment_class->email_footer) {
        $email_order .= $payment_class->email_footer . "\n\n";
      }
    }    
    tep_mail($order->customer['firstname'] . ' ' . $order->customer['lastname'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    // send emails to other people
    if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
      tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    }
    return true;
  }

  function get_error() {
    return false;
  }

  function check() {
    if (!isset($this->_check)) {
      $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_CRESECURE_STATUS'");
      $this->_check = tep_db_num_rows($check_query);
    }
    return $this->_check;
  }

  function install() {
    $check_query = tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'Preparing [CRE Secure]' limit 1");
    if (tep_db_num_rows($check_query) < 1) {
      $status_query = tep_db_query("select max(orders_status_id) as status_id from " . TABLE_ORDERS_STATUS);
      $status = tep_db_fetch_array($status_query);
      $status_id = $status['status_id']+1;
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $status_id . "', '" . $languages[$i]['id'] . "', 'Preparing [CRE Secure]')");
      }
      $flags_query = tep_db_query("describe " . TABLE_ORDERS_STATUS . " public_flag");
      if (tep_db_num_rows($flags_query) == 1) {
        tep_db_query("update " . TABLE_ORDERS_STATUS . " set public_flag = 0 and downloads_flag = 0 where orders_status_id = '" . $status_id . "'");
      }
    } else {
      $check = tep_db_fetch_array($check_query);
      $status_id = $check['orders_status_id'];
    }    
    // remove any old test mode value
    tep_db_query("delete from " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_PAYMENT_CRESECURE_TEST_MODE'");
    // insert new values
    tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable CRE Secure Payment Module', 'MODULE_PAYMENT_CRESECURE_STATUS', 'True', 'Do you want to accept payments through the CRE Secure Payment System?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('CRE Secure Account ID', 'MODULE_PAYMENT_CRESECURE_LOGIN', '', 'The Account ID used for the CRE Secure payment service', '6', '0', now())");
    tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('CRE Secure API Token', 'MODULE_PAYMENT_CRESECURE_PASS', '', 'The API Token used for the CRE Secure payment service', '6', '0', now())");
    tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Sandbox Mode', 'MODULE_PAYMENT_CRESECURE_TEST_MODE', 'False', 'Set to \'True\' for sandbox test environment or set to \'False\' for production environment.', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");      
    tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show Incomplete Orders', 'MODULE_PAYMENT_CRESECURE_SHOW_INCOMPLETE', 'False', 'Set to True to show incomplete orders on shopping cart page and customer order history page.', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");    
    if ($this->version == 'OSC') {
      tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Accepted Credit Cards', 'MODULE_PAYMENT_CRESECURE_ACCEPTED_CC', 'American Express, MasterCard, Visa', 'The credit cards you currently accept. Selections are American Express, Citibank Financial, DinersCart Blanche, Discover, JCB, MasterCard, RevolutionCard.', '6', '0', now())");  
    } else {
      tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Accepted Credit Cards', 'MODULE_PAYMENT_CRESECURE_ACCEPTED_CC', 'American Express, MasterCard, Visa', 'The credit cards you currently accept', '6', '0', '_selectCREOptions(array(\'American Express\',\'Citibank Financial\',\'DinersCart Blanche\',\'Discover\',\'JCB\',\'MasterCard\',\'RevolutionCard\',\'Visa\'), ', now())");
    }
    tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_CRESECURE_ZONE', '0', 'If a zone is selected, enable this payment method for that zone only.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
    tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Pending Order Status', 'MODULE_PAYMENT_CRESECURE_ORDER_STATUS_ID', '0', 'For Pending orders, set the status of orders made with this payment module to this value.  Default is \'Preparing [CRE Secure]\'', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Completed Order Status', 'MODULE_PAYMENT_CRESECURE_ORDER_STATUS_COMPLETE_ID', '0', 'For Completed orders, set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_PAYMENT_CRESECURE_SORT_ORDER', '10', 'Sort order of payment display. Lowest is displayed first.', '6', '5' , now())"); 
    
    // create transaction_log table
    tep_db_query("CREATE TABLE IF NOT EXISTS `transaction_log` (
                 `log_id` int(11) NOT NULL auto_increment,
                 `token` varchar(128) NOT NULL,
                 `transaction_id` varchar(64) NOT NULL,
                 `order_id` int(11) NOT NULL default '0',
                 `created_date` datetime NOT NULL default '0000-00-00 00:00:00',
                  PRIMARY KEY  (`log_id`,`order_id`)
                  ) ENGINE=MyISAM;"); 
                  
    if ($this->version == 'OSC') {                  
      tep_db_query("ALTER IGNORE TABLE `orders` ADD `payment_info` TEXT;");                     
    }
  }

  function remove() {
    tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->hidden_keys()) . "')");
    // remove order status 
    tep_db_query("delete from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'Preparing [CRE Secure]'");
    if ($this->version == 'OSC') {                  
      tep_db_query("ALTER IGNORE TABLE `orders` DROP `payment_info`;");                     
    }    
  }
  
  function hidden_keys() {
    if ($this->version == 'OSC') {
      return array('MODULE_PAYMENT_CRESECURE_SHOW_INCOMPLETE');
    } 
  }

  function keys() {
    if ($this->version == 'OSC') {       
      return array('MODULE_PAYMENT_CRESECURE_STATUS',
                   'MODULE_PAYMENT_CRESECURE_TEST_MODE',
                   'MODULE_PAYMENT_CRESECURE_LOGIN',
                   'MODULE_PAYMENT_CRESECURE_PASS', 
                   'MODULE_PAYMENT_CRESECURE_ACCEPTED_CC',
                   'MODULE_PAYMENT_CRESECURE_ORDER_STATUS_ID',
                   'MODULE_PAYMENT_CRESECURE_ORDER_STATUS_COMPLETE_ID', 
                   'MODULE_PAYMENT_CRESECURE_SORT_ORDER');
    } else {
      return array('MODULE_PAYMENT_CRESECURE_STATUS',
                   'MODULE_PAYMENT_CRESECURE_TEST_MODE',
                   'MODULE_PAYMENT_CRESECURE_LOGIN',
                   'MODULE_PAYMENT_CRESECURE_PASS', 
                   'MODULE_PAYMENT_CRESECURE_SHOW_INCOMPLETE',
                   'MODULE_PAYMENT_CRESECURE_ACCEPTED_CC',
                   'MODULE_PAYMENT_CRESECURE_ORDER_STATUS_ID',
                   'MODULE_PAYMENT_CRESECURE_ORDER_STATUS_COMPLETE_ID', 
                   'MODULE_PAYMENT_CRESECURE_SORT_ORDER');
    }                       
  }     
} // end class
if (!function_exists('_selectCREOptions')) {
  function _selectCREOptions($select_array, $key_value, $key = '') {
    for ($i=0; $i<(sizeof($select_array)); $i++) {
      $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
      $string .= '<br><input type="checkbox" name="' . $name . '" value="' . $select_array[$i] . '"';
      $key_values = explode(", ", $key_value);
      if (in_array($select_array[$i], $key_values)) $string .= ' checked="checked"';
      $string .= '> ' . $select_array[$i];
    }
    return $string;
  }    
}
if (!function_exists('tep_db_decoder')) {
  function tep_db_decoder($string) {
    $string = str_replace('&#39;', "'", $string);
    $string = str_replace('&#39', "'", $string); //backword compatabiliy
    return $string;
  }
}
?>