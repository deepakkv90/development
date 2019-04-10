<?php
/*
  $Id: create_order_process.php,v 1.5 2008/06/06 00:36:41 wa4u Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
  function sbs_get_zone_name($country_id, $zone_id) {
    $zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . $country_id . "' and zone_id = '" . $zone_id . "'");
    if (tep_db_num_rows($zone_query)) {
      $zone = tep_db_fetch_array($zone_query);
      return $zone['zone_name'];
    } else {
      return (isset($default_zone) ? $default_zone : '');
    }
  }  

  // include currencies class and create an instance
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  if(isset($_GET['Customer'])) {
    $account_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $_GET['Customer'] . "'");
    $account = tep_db_fetch_array($account_query);
    $customer_id = $account['customers_id'];

    $address_query = tep_db_query("select * from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $_GET['Customer'] . "'");
    $address = tep_db_fetch_array($address_query);
    $gender = tep_db_prepare_input($_POST['gender']);

    $firstname = $account['customers_firstname'];
    $lastname = $account['customers_lastname'];
    $dob = $account['customers_dob'];
    $email_address = $account['customers_email_address'];
    $telephone = $address['entry_telephone'];
    $fax = $account['customers_fax'];
    $newsletter = $account['customers_newsletter'];
    $password = $account['customers_password'];
    $confirmation = $account['customers_password'];
    $street_address = $address['entry_street_address'];
    $company = (tep_not_null($address['entry_company']) ? $address['entry_company'] : tep_db_prepare_input($_POST['company']));
    $suburb = (tep_not_null($address['entry_suburb']) ? $address['entry_suburb'] : tep_db_prepare_input($_POST['suburb']) );
    $postcode = $address['entry_postcode'];
    $city = $address['entry_city'];
    $zone_id = $address['entry_zone_id'];
    if ($address['entry_state'] == '') $address['entry_state'] = sbs_get_zone_name($address['entry_country_id'], $address['entry_zone_id']); 
    $state = (tep_not_null($address['entry_state']) ? $address['entry_state'] : tep_db_prepare_input($_POST['state']));
    $country_id = $address['entry_country_id'];
  } else if(isset($_POST['customers_id'])) {
    $customer_id = tep_db_prepare_input($_POST['customers_id']);
    $gender = tep_db_prepare_input($_POST['gender']);
    $firstname = tep_db_prepare_input($_POST['firstname']);
    $lastname = tep_db_prepare_input($_POST['lastname']);
    $dob = tep_db_prepare_input($_POST['dob']);
    $email_address = strtolower(tep_db_prepare_input($_POST['email_address']));
    $telephone = tep_db_prepare_input($_POST['telephone']);
    $fax = tep_db_prepare_input($_POST['fax']);
    $newsletter = tep_db_prepare_input($_POST['newsletter']);
    $password = tep_db_prepare_input($_POST['password']);
    $confirmation = tep_db_prepare_input($_POST['confirmation']);
    $street_address = tep_db_prepare_input($_POST['street_address']);
    $company = tep_db_prepare_input($_POST['company']);
    $suburb = tep_db_prepare_input($_POST['suburb']);
    $postcode = tep_db_prepare_input($_POST['postcode']);
    $city = tep_db_prepare_input($_POST['city']);
    $zone_id = tep_db_prepare_input($_POST['zone_id']);
    $state = tep_db_prepare_input($_POST['state']);
    $country_id = tep_db_prepare_input($_POST['country']);
  }
  $country = tep_get_country_name($country_id);
  $format_id = "1";
  $size = "1";
  //$payment_method = DEFAULT_PAYMENT_METHOD; //changed 1.5
  $payment_method = ''; //changed 1.5
  $new_value = "1";
  $error = false; // reset error flag
  $temp_amount = "0";
  $temp_amount = number_format($temp_amount, 2, '.', '');
// modified to the system defaults
  $currency_value = $currencies->currencies[DEFAULT_CURRENCY]['value'];
  
    $sql_data_array = array('customers_id' => $customer_id,
              'customers_name' => $firstname . ' ' . $lastname,
              'customers_company' => $company,
              'customers_street_address' => $street_address,
              'customers_suburb' => $suburb,
              'customers_city' => $city,
              'customers_postcode' => $postcode,
              'customers_state' => $state,
              'customers_country' => $country,
              'customers_telephone' => $telephone,
              'customers_email_address' => $email_address,
              'customers_address_format_id' => $format_id,
              'delivery_name' => $firstname . ' ' . $lastname,
              'delivery_company' => $company,
              'delivery_street_address' => $street_address,
              'delivery_suburb' => $suburb,
              'delivery_city' => $city,
              'delivery_postcode' => $postcode,
              'delivery_state' => $state,
              'delivery_country' => $country,
              'delivery_address_format_id' => $format_id,
              'billing_name' => $firstname . ' ' . $lastname,
              'billing_company' => $company,
              'billing_street_address' => $street_address,
              'billing_suburb' => $suburb,
              'billing_city' => $city,
              'billing_postcode' => $postcode,
              'billing_state' => $state,
              'billing_country' => $country,
              'billing_address_format_id' => $format_id,
              'date_purchased' => 'now()',
              'orders_status' => DEFAULT_ORDERS_STATUS_ID,
              'currency' => DEFAULT_CURRENCY,
              'currency_value' => $currency_value,
              'payment_method' => $payment_method,
              ); 

  //old
  tep_db_perform(TABLE_ORDERS, $sql_data_array);
  $insert_id = tep_db_insert_id();


    $sql_data_array = array('orders_id' => $insert_id,
          'orders_status_id' => $new_value,
          'date_added' => 'now()');
    tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

	
	/* //hided to display grand total and gst total instead of subtotal and tax - Aug 20 2011
    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => TEXT_SUB_TOTAL,
                            'text' => $temp_amount,
                            'value' => "0.00",
                            'class' => "ot_subtotal",
                            'sort_order' => "1");
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
   
    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => TEXT_TAX,
                            'text' => $temp_amount,
                            'value' => "0.00",
                            'class' => "ot_tax",
                            'sort_order' => "2");
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
	*/
	
	$sql_data_array = array('orders_id' => $insert_id,
                            'title' => TEXT_GRAND_SUBTOTAL,
                            'text' => $temp_amount,
                            'value' => "0.00",
                            'class' => "ot_grand_subtotal",
                            'sort_order' => "1");
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
	
    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => TEXT_SHIPPING,
                            'text' => $temp_amount,
                            'value' => "0.00",
                            'class' => "ot_shipping",
                            'sort_order' => "2");
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
	
	$sql_data_array = array('orders_id' => $insert_id,
                            'title' => TEXT_GST_TOTAL,
                            'text' => $temp_amount,
                            'value' => "0.00",
                            'class' => "ot_gst_total",
                            'sort_order' => "3");
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);

	$sql_data_array = array('orders_id' => $insert_id,
                            'title' => TEXT_CUSTOMER_DISCOUNT,
                            'text' => $temp_amount,
                            'value' => "0.00",
                            'class' => "ot_customer_discount",
                            'sort_order' => "4");
   tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
   
   $sql_data_array = array('orders_id' => $insert_id,
                            'title' => TEXT_TOTAL,
                            'text' => $temp_amount,
                            'value' => "0.00",
                            'class' => "ot_total",
                            'sort_order' => "5");
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);


  /*$customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
  $sql_data_array = array('orders_id' => $insert_id,
                          'new_value' => DEFAULT_ORDERS_STATUS_ID,
                          'date_added' => 'now()',
                          'customer_notified' => $customer_notification);
  tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);*/
    $_SESSION['create_order'] = true;
    tep_redirect(tep_href_link(FILENAME_EDIT_ORDERS, 'oID=' . $insert_id, 'SSL'));
  //tep_href_link(update_order. '.'.php, 'OrderID=' . $oInfo->orders_id) .
  //}

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>