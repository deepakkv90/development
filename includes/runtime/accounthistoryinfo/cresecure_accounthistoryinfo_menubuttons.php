<?php
/*
  $Id: cresecure_accounthistoryinfo_menubuttons.php,v 1.0.0.0 2009/04/30 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
global $order, $language, $currency;
$rci = '';
$cartID = (isset($_SESSION['cart_cresecure_ID'])) ? substr($_SESSION['cart_cresecure_ID'], 0, strpos($_SESSION['cart_cresecure_ID'], '-')) : '';
$order_id = (isset($_GET['order_id'])) ? (int)$_GET['order_id'] : 0;
$sql_query = tep_db_query("SELECT payment_info from " . TABLE_ORDERS . " WHERE orders_id = '" . $order_id . "'");
$info = tep_db_fetch_array($sql_query);
$payment_info = $info['payment_info'];
if ($order->info['orders_status'] == 'Preparing [CRE Secure]' && $cartID != $payment_info) {
  $_SESSION['payment'] = 'cresecure';
  $_SESSION['sendto'] = 'cresecure';
  $customer_id = $_SESSION['customer_id'];
  $username = (defined('MODULE_PAYMENT_CRESECURE_LOGIN')) ? MODULE_PAYMENT_CRESECURE_LOGIN : '';
  $password = (defined('MODULE_PAYMENT_CRESECURE_PASS')) ? MODULE_PAYMENT_CRESECURE_PASS : '';                       
  $branded_url = (file_exists('checkout_payment_template.php')) ? 'checkout_payment_template.php' : 'default';   
  $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';
  if ($request_type == 'SSL') {
    $content_template_url = 'https://' . substr(tep_href_link($branded_url, '', 'NONSSL', false, false), strpos(tep_href_link($branded_url, '', 'NONSSL', false, false), '://')+3);
  } else {
    $content_template_url = '';  // no SSL = force default template page
  }   
  $allowed_types = (defined('MODULE_PAYMENT_CRESECURE_ACCEPTED_CC')) ? str_replace(', ', '|', MODULE_PAYMENT_CRESECURE_ACCEPTED_CC) : 'Mastercard|Visa';
  if (defined('MODULE_PAYMENT_CRESECURE_TEST_MODE') && MODULE_PAYMENT_CRESECURE_TEST_MODE == 'True') {
    $this->form_action_url = 'https://sandbox-cresecure.net/securepayments/a1/cc_collection.php';  // sandbox url
  } else {
    $this->form_action_url = 'https://cresecure.net/securepayments/a1/cc_collection.php';  // production url
  }   
  $currency = (isset($order->info['currency'])) ? $order->info['currency'] : 'USD';
  // calculate total weight and formulate order description
  $total_weight = 0;
  $order_desc = '';
  for ($i=0; $i<sizeof($order->products); $i++) {
    $total_weight = $total_weight + ((int)$order->products[$i]['qty'] * (float)$order->products[$i]['weight']);
    $order_desc .= $order->products[$i]['qty'] . '-' . ($order->products[$i]['name']) . '**';
  }
  $rci .= tep_draw_form('payment', $this->form_action_url, 'post') .
          tep_draw_hidden_field('CRESecureID', $username) .
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

          tep_draw_hidden_field('total_amt', number_format($order->info['total_value'], 2)) .
          tep_draw_hidden_field('total_weight', $total_weight) .
          tep_draw_hidden_field('order_desc', $order_desc) .
          tep_draw_hidden_field('order_id', $order_id) .
          tep_draw_hidden_field('customer_id', $_SESSION['customer_id']) .
          tep_draw_hidden_field('currency_code', $currency) .
          tep_draw_hidden_field('lang', $language) .       
          tep_draw_hidden_field('allowed_types', $allowed_types) .                                                                         
          tep_draw_hidden_field('sess_id', tep_session_id()) .
          tep_draw_hidden_field('sess_name', tep_session_name()) .
          tep_draw_hidden_field('ip_address', $_SERVER["REMOTE_ADDR"]) .
          tep_draw_hidden_field('return_url', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', false, false)) .
          tep_draw_hidden_field('content_template_url', $content_template_url);          
        
  $rci .= '<td align="center" class="main">' . tep_template_image_submit('button_complete_payment.gif', IMAGE_COMPLETE_PAYMENT) . '</td>' . "\n";
  $rci .= '</form>' . "\n";
}
return $rci; 
?> 