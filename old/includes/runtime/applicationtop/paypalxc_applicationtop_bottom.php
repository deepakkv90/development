<?php
/*
  $Id: paypalxc_applicationtop_bottom.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
  define('MODULE_PAYMENT_PAYPAL_EC_BUTTON_IMG', 'https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif');
  define('MODULE_PAYMENT_PAYPAL_EC_MARK_IMG', 'https://www.paypal.com/en_US/i/logo/PayPal_mark_37x23.gif');
  define('FILENAME_EC_PROCESS', 'ec_process.php');
  define('FILENAME_PAYPAL_XC', 'paypal_xc.php');
  define('FILENAME_PAYPAL_XC_IPN', 'paypal_xc_ipn.php');
    
  global $ec_enabled, $customer_id, $customer_first_name, $customer_default_address_id, $customer_country_id, $customer_zone_id, $comments, $shipping, $free_shipping, $shipping_modules, $cart, $payment, $sendto, $billto, $credit_covers, $order;
  function tep_paypal_xc_enabled() {    
    global $customer_id;
    if(!isset($_SESSION['sppc_customer_group_id'])) {
      $customer_group_id = '0';
    } else {
      $customer_group_id = $_SESSION['sppc_customer_group_id'];
    }
    if ( defined('MODULE_PAYMENT_PAYPAL_XC_STATUS') && MODULE_PAYMENT_PAYPAL_XC_STATUS == 'True' ) {
      if (defined('INSTALLED_VERSION_TYPE') && stristr(INSTALLED_VERSION_TYPE, 'B2B')) {
        $customer_payment = tep_db_fetch_array(tep_db_query("select IF(c.customers_payment_allowed <> '', c.customers_payment_allowed, cg.group_payment_allowed) as payment_allowed from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_GROUPS . " cg where c.customers_id = '" . $customer_id . "' and cg.customers_group_id =  '" . $customer_group_id . "'"));
      } else {
        $customer_payment['payment_allowed'] = '';
      }
      if (tep_not_null($customer_payment['payment_allowed'])) {
        $payment_array = explode(';', $customer_payment['payment_allowed']);
        $flag = false;
        foreach ( $payment_array as $value ) {
          if ( trim($value) == 'paypal_xc.php' ) {
            $flag = true;
            break;
          }
        }
      } else {
        $flag = true;
      }
      return $flag;
    } else {
      return false;
    }
  }
  
  if ( ! function_exists('create_temp_customer') ) {
  function create_temp_customer($customer_info) {
    global $customer_id, $customer_first_name, $customer_default_address_id, $customer_country_id, $customer_zone_id, $billto, $sendto;
    $query = tep_db_query("SELECT c.customers_id as customer_id, c.customers_firstname, c.customers_default_address_id as customer_default_address_id, ab.entry_country_id as customer_country_id, ab.entry_zone_id as customer_zone_id FROM " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " ab WHERE c.customers_id = ab.customers_id AND c.customers_default_address_id = ab.address_book_id AND c.customers_email_address = '" . $customer_info['EMAIL'] . "'");
    if (tep_db_num_rows($query) > 0) {
      $data = tep_db_fetch_array($query);
      $customer_id = $data['customer_id'];
      $customer_first_name = $data['customer_first_name'];
      $customer_default_address_id = $data['customer_default_address_id'];
      $customer_country_id = $data['customer_country_id'];
      $customer_zone_id = $data['customer_zone_id'];
    } else {
      $_SESSION['temp_password'] = tep_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
      $sql_data_array = array('customers_firstname' => $customer_info['FIRSTNAME'],
                              'customers_lastname' => $customer_info['LASTNAME'],
                              'customers_email_address' => $customer_info['EMAIL'],
                              'customers_validation' => '1',
                              'customers_password' => tep_encrypt_password($_SESSION['temp_password']));
      tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);
      $customer_id = tep_db_insert_id();
      $sql_query = tep_db_query("SELECT countries_id FROM " . TABLE_COUNTRIES . " WHERE countries_iso_code_2 = '" . $customer_info['SHIPTOCOUNTRYCODE'] . "'");
      if (tep_db_num_rows($sql_query) == 0) {
        $sql_query = tep_db_query("SELECT countries_id FROM " . TABLE_COUNTRIES . " WHERE countries_iso_code_2 = '" . $customer_info['COUNTRYCODE'] . "'");
      }
      $country = tep_db_fetch_array($sql_query);
      $customer_country_id = $country['countries_id'];
      $zone = tep_db_fetch_array(tep_db_query("SELECT zone_id FROM " . TABLE_ZONES . " WHERE zone_country_id = '" . $country['countries_id'] . "' AND zone_code = '" . $customer_info['SHIPTOSTATE'] . "'"));
      if (tep_not_null($zone['zone_id'])) {
        $customer_zone_id = $zone['zone_id'];
        $state = '';
      } else {
        $customer_zone_id = '0';
        $state = $customer_info['SHIPTOSTATE'];
      }
      $customer_first_name = $customer_info['FIRSTNAME'];
      $customer_last_name = $customer_info['LASTNAME'];
      $sql_data_array = array('customers_id' => $customer_id,
                              'entry_firstname' => $customer_first_name,
                              'entry_lastname' => $customer_last_name,
                              'entry_telephone' => $customer_info['PHONENUM'],
                              'entry_street_address' => $customer_info['SHIPTOSTREET'],
                              'entry_postcode' => $customer_info['SHIPTOZIP'],
                              'entry_city' => $customer_info['SHIPTOCITY'],
                              'entry_country_id' => $customer_country_id,
                              'entry_zone_id' => $customer_zone_id,
                              'entry_state' => $state);
      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
      $customer_default_address_id = tep_db_insert_id();
      $billto = $customer_default_address_id;
      $sendto = $customer_default_address_id;
      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$customer_default_address_id . "' where customers_id = '" . (int)$customer_id . "'");
      tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");   
      $_SESSION['paypalxc_create_account'] = '1';
    } 
    $_SESSION['customer_id'] = $customer_id;
    $_SESSION['customer_first_name'] = $customer_first_name;
    $_SESSION['customer_default_address_id'] = $customer_default_address_id;
    $_SESSION['customer_country_id'] = $customer_country_id;
    $_SESSION['customer_zone_id'] = $customer_zone_id;
  }
  } // if exists
  
  if ( ! function_exists('update_temp_customer') ) {
  function update_temp_customer($customer_info) {
    global $customer_id, $customer_first_name, $customer_default_address_id, $customer_country_id, $customer_zone_id, $billto, $sendto;
    $query = tep_db_query("SELECT c.customers_id as customer_id, c.customers_firstname, c.customers_default_address_id as customer_default_address_id, ab.entry_country_id as customer_country_id, ab.entry_zone_id as customer_zone_id FROM " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " ab WHERE c.customers_id = ab.customers_id AND c.customers_default_address_id = ab.address_book_id AND c.customers_email_address = '" . $customer_info['EMAIL'] . "' AND c.customers_id <> '" . $customer_id . "'");
    if (tep_db_num_rows($query) > 0) {
      $data = tep_db_fetch_array($query);
      delete_temp_customer($customer_id);
      $customer_id = $data['customer_id'];
      $customer_first_name = $data['customer_first_name'];
      $customer_default_address_id = $data['customer_default_address_id'];
      $customer_country_id = $data['customer_country_id'];
      $customer_zone_id = $data['customer_zone_id'];    
      $billto = $customer_default_address_id;
      $sendto = $customer_default_address_id;
    } else {
      $sql_data_array = array('customers_firstname' => $customer_info['FIRSTNAME'],
                              'customers_lastname' => $customer_info['LASTNAME'],
                              'customers_email_address' => $customer_info['EMAIL']);
      tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . $customer_id . "'");
      $sql_query = tep_db_query("SELECT countries_id FROM " . TABLE_COUNTRIES . " WHERE countries_iso_code_2 = '" . $customer_info['SHIPTOCOUNTRYCODE'] . "'");
      if (tep_db_num_rows($sql_query) == 0) {
        $sql_query = tep_db_query("SELECT countries_id FROM " . TABLE_COUNTRIES . " WHERE countries_iso_code_2 = '" . $customer_info['COUNTRYCODE'] . "'");
      }
      $country = tep_db_fetch_array($sql_query);
      $customer_country_id = $country['countries_id'];
      $zone = tep_db_fetch_array(tep_db_query("SELECT zone_id FROM " . TABLE_ZONES . " WHERE zone_country_id = '" . $country['countries_id'] . "' AND zone_code = '" . $customer_info['SHIPTOSTATE'] . "'"));
      if (tep_not_null($zone['zone_id'])) {
        $customer_zone_id = $zone['zone_id'];
        $state = '';
      } else {
        $customer_zone_id = '0';
        $state = $customer_info['SHIPTOSTATE'];
      }
      $customer_first_name = $customer_info['FIRSTNAME'];
      $customer_last_name = $customer_info['LASTNAME'];
      $sql_data_array = array('entry_firstname' => $customer_first_name,
                              'entry_lastname' => $customer_last_name,
                              'entry_telephone' => $customer_info['PHONENUM'],
                              'entry_street_address' => $customer_info['SHIPTOSTREET'],
                              'entry_postcode' => $customer_info['SHIPTOZIP'],
                              'entry_city' => $customer_info['SHIPTOCITY'],
                              'entry_country_id' => $customer_country_id,
                              'entry_zone_id' => $customer_zone_id,
                              'entry_state' => $state);
      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "address_book_id = '" . $customer_default_address_id . "'");
    }
  }
  } // if exists
  
  if ( ! function_exists('delete_temp_customer') ) {
  function delete_temp_customer($customer_id) {
    tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . tep_db_input($customer_id) . "'");
    tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . tep_db_input($customer_id) . "'");
    tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . tep_db_input($customer_id) . "'");
    tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . tep_db_input($customer_id) . "'");
    tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . tep_db_input($customer_id) . "'");
    tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . tep_db_input($customer_id) . "'");
  }
  } // if exists

  
  if ( defined('MODULE_PAYMENT_PAYPAL_XC_STATUS') && MODULE_PAYMENT_PAYPAL_XC_STATUS == 'True' && strstr($_SERVER['SCRIPT_NAME'], FILENAME_CHECKOUT_SHIPPING) && tep_not_null($_GET['token']) && tep_not_null($_GET['PayerID']) ) {
    require_once(DIR_WS_MODULES . 'payment/paypal_xc/paypal_xc_base.php');
    $paypal_xc = new paypal_xc_base();
    $response = $paypal_xc->GetExpressCheckoutDetailsRequest($_GET['token'], $_GET['PayerID']);
    if (!isset($_SESSION['customer_id'])) {
      create_temp_customer($response);
      $_SESSION['nologin'] = '1';
    } elseif ( $_SESSION['paypalxc_create_account'] == '1' ) {
      update_temp_customer($response);
    }
    $_SESSION['token'] = $_GET['token'];
    $_SESSION['PayerID'] = $_GET['PayerID'];
    $_SESSION['skip_payment'] = '1';
    if (!isset($_SESSION['billto'])) {
      $_SESSION['billto'] = $billto;
    }
    if (!isset($_SESSION['sendto'])) {
      $_SESSION['sendto'] = $sendto;
    }
    if ( $billto == false ) {
      if ( $sendto != false ) {
        $billto = $sendto;
      } else {
        $billto = $customer_default_address_id;
      }
    }
    if ( $_SESSION['skip_shipping'] == '1' ) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'));
    }
  }
  
  if ( defined('MODULE_PAYMENT_PAYPAL_XC_STATUS') && MODULE_PAYMENT_PAYPAL_XC_STATUS == 'True' && strstr($_SERVER['SCRIPT_NAME'], FILENAME_CHECKOUT_SHIPPING) ) {
    if ( isset($_POST['ppaction']) && $_POST['ppaction'] == 'paypal' ) {
      $_SESSION['skip_shipping'] = '1';
    } else {
      $_SESSION['skip_shipping'] = '0';
    }
  }
    
  if ( defined('MODULE_PAYMENT_PAYPAL_XC_STATUS') && MODULE_PAYMENT_PAYPAL_XC_STATUS == 'True' && strstr($_SERVER['SCRIPT_NAME'], FILENAME_CHECKOUT_CONFIRMATION) && isset($_POST['payment']) && $_POST['payment'] == 'paypal_xc_ec' ) {
    require_once(DIR_WS_CLASSES . 'order.php');
    $order = new order;
    require_once(DIR_WS_CLASSES . 'order_total.php');
    $order_total_modules = new order_total;
    $order_total_modules->collect_posts();
    $order_total_modules->pre_confirmation_check();    
    if (!isset($_SESSION['payment'])) {
      $_SESSION['payment'] = $payment;
    }
    $payment = 'paypal_xc';
    if (!isset($_SESSION['comments'])) {
      $_SESSION['comments'] = $comments;
    }
    if ( isset($_POST['comments']) && tep_not_null($_POST['comments']) ) {
      $comments = tep_db_prepare_input($_POST['comments']);
    }
    $_SESSION['skip_shipping'] = '1';
    tep_redirect(tep_href_link(FILENAME_EC_PROCESS, '', 'SSL'));
  }
?>
