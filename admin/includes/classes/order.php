<?php
/*
  $Id: order.php,v 1.1.1.1 2004/03/04 23:39:45 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class order {
    var  $products_options_name, $info, $totals, $products, $customer, $delivery, $content_type, $states, $country, $s_states, $s_country, $b_states, $b_country ;

    function order($order_id = '') {
      $this->info = array();
      $this->totals = array();
      $this->products = array();
      $this->customer = array();
      $this->delivery = array();
      $this->billing = array();


      if (tep_not_null($order_id)) {
        $this->query($order_id);
      } else {
        $this->cart();
      }
    }

    function query($order_id) {
      global $languages_id;
      
      $order_id = tep_db_prepare_input($order_id);
      $order_query = tep_db_query("select * from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
      $order = tep_db_fetch_array($order_query);
	  
	  //customer infor -April 04 2011
	  $customer_query = tep_db_query("SELECT c.customers_id, c.accountant_name, c.accountant_email, c.submit_accountant_email_to_xero, a.entry_company_tax_id, c.customers_term FROM customers c LEFT JOIN address_book a ON c.customers_default_address_id = a.address_book_id WHERE c.customers_id = a.customers_id and c.customers_id='".$order['customers_id']."'");
	  $customer_info = tep_db_fetch_array($customer_query);
	  
      $totals_query = tep_db_query("select title, text, class, value, sort_order from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' order by sort_order");
      while ($totals = tep_db_fetch_array($totals_query)) {
        $this->totals[] = array('title' => $totals['title'],
                                'class' => $totals['class'],
								'value' => $totals['value'],
								'sort_order' => $totals['sort_order'],
								'text' => $totals['text']);
      }

		// Modified on Aug 27, 2010 - selected class, value column for $order_total_query , $shipping_method_query, $totals_query
		
      $order_total_query = tep_db_query("select text, value, class from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' and class = 'ot_total'");
      $order_total = tep_db_fetch_array($order_total_query);

      $shipping_method_query = tep_db_query("select title, value, class from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' and class = 'ot_shipping'");
      $shipping_method = tep_db_fetch_array($shipping_method_query);

      $order_status_query = tep_db_query("select orders_status_name from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . $order['orders_status'] . "' and language_id = '" . (int)$languages_id . "'");
      $order_status = tep_db_fetch_array($order_status_query);
      $this->info = array('currency' => $order['currency'],
                          'currency_value' => $order['currency_value'],
                          'payment_method' => $order['payment_method'],
                          'account_name' => $order['account_name'],
                          'account_number' => $order['account_number'],
                          'po_number' => $order['po_number'],
                          'date_purchased' => $order['date_purchased'],
                          'payment_id' => $order['payment_id'],
                          'orders_status' => $order['orders_status'],
                          'shipping_cost' => $shipping_method['value'],
                          'total_value' => $order_total['value'],
                          'orders_status' => $order_status['orders_status_name'],
                          'orders_status_number' => $order['orders_status'],
                          'last_modified' => $order['last_modified'],
                          'total' => strip_tags($order_total['text']),
						  'purchase_number' => $order['purchase_number'],
						  'due_date' => $order['due_date'],
						  'order_assigned_to' => $order['order_assigned_to'],
						  'order_assigned_to_email' => $order['order_assigned_to_email'],
						  'xero' => $order['xero'],
                          'shipping_method' => ((substr($shipping_method['title'], -1) == ':') ? substr(strip_tags($shipping_method['title']), 0, -1) : strip_tags($shipping_method['title'])));

      $this->customer = array('name' => $order['customers_name'],
                              'id' => $order['customers_id'],
                              'company' => $order['customers_company'],
                              'street_address' => $order['customers_street_address'],
                              'suburb' => $order['customers_suburb'],
                              'city' => $order['customers_city'],
                              'postcode' => $order['customers_postcode'],
                              'state' => $order['customers_state'],
                              'country' => $order['customers_country'],
                              'format_id' => $order['customers_address_format_id'],
                              'telephone' => $order['customers_telephone'],
                              'email_address' => $order['customers_email_address'],
							  'purchase_number' => $order['purchase_number'],
                              'ipaddy' => $order['ipaddy'],
							  'customer_number' => $customer_info['customers_id'],
                              'macola_number' => $customer_info['entry_company_tax_id'],
							  'customers_term' => $customer_info['customers_term'],
							  'accountant_name' => $customer_info['accountant_name'],
							  'accountant_email' => $customer_info['accountant_email'],
							  'submit_accountant_email_to_xero' => $customer_info['submit_accountant_email_to_xero'],
                              'ipisp' => $order['ipisp']);

      $this->delivery = array('name' => $order['delivery_name'],
                              'company' => $order['delivery_company'],
                              'street_address' => $order['delivery_street_address'],
                              'suburb' => $order['delivery_suburb'],
                              'city' => $order['delivery_city'],
                              'postcode' => $order['delivery_postcode'],
                              'state' => $order['delivery_state'],
                              'country' => $order['delivery_country'],
                              'format_id' => $order['delivery_address_format_id'],
                              'telephone' => $order['delivery_telephone'],
                              'fax' => $order['delivery_fax'],
                              'email_address' => $order['delivery_email_address']);

      if (empty($this->delivery['name']) && empty($this->delivery['street_address'])) {
        $this->delivery = false;
      }

      $this->billing = array('name' => $order['billing_name'],
                             'company' => $order['billing_company'],
                             'street_address' => $order['billing_street_address'],
                             'suburb' => $order['billing_suburb'],
                             'city' => $order['billing_city'],
                             'postcode' => $order['billing_postcode'],
                             'state' => $order['billing_state'],
                             'country' => $order['billing_country'],
                             'format_id' => $order['billing_address_format_id'],
                             'telephone' => $order['billing_telephone'],
                             'fax' => $order['billing_fax'],
                             'email_address' => $order['billing_email_address']);

      // multi warehouse shipping 
      $orders_shipping_id = '';
      $check_new_vendor_data_query = tep_db_query("select orders_shipping_id, orders_id, vendors_id, vendors_name, shipping_module, shipping_method, shipping_cost, vendor_order_sent from " . TABLE_ORDERS_SHIPPING . " where orders_id = '" . (int)$order_id . "'");
      while ($checked_data = tep_db_fetch_array($check_new_vendor_data_query)) {
        $this->orders_shipping_id = $checked_data['orders_shipping_id'];
        //$orders_vendor_name = $checked_data['vendors_name'];
      }
      
      if (tep_not_null($this->orders_shipping_id) && defined('MVS_STATUS') && MVS_STATUS == 'true') {
        $index2 = 0;
        // get the Vendors
        $vendor_data_query = tep_db_query("select orders_shipping_id, orders_id, vendors_id, vendors_name, shipping_module, shipping_method, shipping_cost, shipping_tax, vendor_order_sent from " . TABLE_ORDERS_SHIPPING . " where orders_id = '" . (int)$order_id . "'");
        while ($vendor_order = tep_db_fetch_array($vendor_data_query)) {
          $this->products[$index2] = array('Vid' => $vendor_order['vendors_id'],
                                           'Vname' => $vendor_order['vendors_name'],
                                           'Vmodule' => $vendor_order['shipping_module'],
                                           'Vmethod' => $vendor_order['shipping_method'],
                                           'Vcost' => $vendor_order['shipping_cost'],
                                           'Vship_tax' => $vendor_order['shipping_tax'],
                                           'Vorder_sent' => $vendor_order['vendor_order_sent'], //a yes=sent a no=not sent
                                           'Vnoname' => 'Shipper',
                                           'spacer' => '-');
          $index = 0;
          $orders_products_query = tep_db_query("select orders_products_id, products_name, products_model, products_price, products_tax, products_quantity, final_price, products_id, products_returned, vendors_id from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "' and vendors_id = '" . (int)$vendor_order['vendors_id'] . "'");
          while ($orders_products = tep_db_fetch_array($orders_products_query)) {
            $this->products[$index2]['orders_products'][$index] = array('qty' => $orders_products['products_quantity'],
                                     'id' => $orders_products['products_id'],
                                     'orders_products_id' => $orders_products['orders_products_id'],
                                     'name' => $orders_products['products_name'],
                                     'return' => $orders_products['products_returned'],
                                     'tax' => $orders_products['products_tax'],
                                     'model' => $orders_products['products_model'],
                                     'price' => $orders_products['products_price'],
                                     'vendor_name' => $orders_products['vendors_name'],
                                     'vendor_ship' => $orders_products['shipping_module'],
                                     'shipping_method' => $orders_products['shipping_method'],
                                     'shipping_cost' => $orders_products['shipping_cost'],
                                     'final_price' => $orders_products['final_price'],
                                     'spacer' => '-');
            $subindex = 0;
            $attributes_query = tep_db_query("select products_options, products_options_values, options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$order_id . "' and orders_products_id = '" . (int)$orders_products['orders_products_id'] . "'");
            if (tep_db_num_rows($attributes_query)) {
              while ($attributes = tep_db_fetch_array($attributes_query)) {
                $this->products[$index2]['orders_products'][$index]['attributes'][$subindex] = array('option' => $attributes['products_options'],
                                                                                                     'value' => $attributes['products_options_values'],
                                                                                                     'prefix' => $attributes['price_prefix'],
                                                                                                     'price' => $attributes['options_values_price']);
                $subindex++;
              }
            }
            $index++;
          }
          $index2++;
        } // multi warehouse shipping //eof 
      } else { 
        $index = 0;
        /*$orders_products_query = tep_db_query("select orders_products_id, products_name, products_model, products_price, products_tax, products_quantity, final_price, products_id, products_returned, vendors_id from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "'");*/

		$orders_products_query = tep_db_query("select 
op.orders_products_id, op.products_name, op.products_model, op.products_price, op.products_tax,p.products_tax_class_id, op.products_quantity, op.final_price, op.products_id, op.products_returned, op.vendors_id, p.products_price as product_original_final_price, p.products_purchase_number, op.print_file_name  
from " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p
where op.orders_id = '" . (int)$order_id . "' and op.products_id=p.products_id order by op.orders_products_id");
        
		while ($orders_products = tep_db_fetch_array($orders_products_query)) {
		
		$orders_products_tax_query = tep_db_query("select op.products_tax,p.products_tax_class_id, tr.tax_rate, tr.tax_description 
		from " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p, " . TABLE_TAX_RATES . " tr   
		where 
		op.orders_id = '" . (int)$order_id . "' 
		and op.products_id=p.products_id 
		and p.products_tax_class_id=tr.tax_class_id
		order by op.orders_products_id");
		
		if (tep_db_num_rows($orders_products_tax_query)) {
			$orders_products_tax = tep_db_fetch_array($orders_products_tax_query); 
		}else{
			$orders_products_tax['tax_rate'] = '0.0000';
		}
          $this->products[$index] = array('qty' => $orders_products['products_quantity'],
                                          'id' => $orders_products['products_id'],
                                          'orders_products_id' => $orders_products['orders_products_id'],
                                          'name' => $orders_products['products_name'],
                                          'return' => $orders_products['products_returned'],
                                          'model' => $orders_products['products_model'],
                                          'tax' => $orders_products['products_tax'],
                                          'tax_rate' => $orders_products_tax['tax_rate'],
                                          'price' => $orders_products['products_price'],
                                          'final_price' => $orders_products['final_price'],
										  'product_original_final_price'=>$orders_products['product_original_final_price'],
                                          'Vid' => $orders_products['vendors_id'],
										  'print_file_name' => $orders_products['print_file_name'],
                                          'purchase_number' => $orders_products['products_purchase_number'] );
          if (!isset($_SESSION['sppc_customer_group_id'])) {
            $customer_group_id = '0';
          } else {
            $customer_group_id = $_SESSION['sppc_customer_group_id'];
          }
          if ($customer_group_id != '0') {
            $orders_customers_price = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where customers_group_id = '". $customer_group_id . "' and products_id = '" . $products[$i]['id'] . "'");
            if ($orders_customers = tep_db_fetch_array($orders_customers_price)) {
              $this->products[$index] = array('price' => $orders_customers['customers_group_price'], 'final_price' => $orders_customers['customers_group_price']);
            }
          }
          //check to see if product is a sub product
          $products_id_tmp = (int)$orders_products['orders_products_id'];
          if (tep_subproducts_parent($products_id_tmp)) {
            $products_id_query = tep_subproducts_parent($products_id_tmp);
          } else {
            $products_id_query = $products_id_tmp;
          }
          $subindex = 0;
          $attributes_query = tep_db_query("SELECT products_options_id, products_options, products_options_values_id, products_options_values, options_values_price, price_prefix
                                              from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . "
                                            WHERE orders_id = " . (int)$order_id . "
                                              and orders_products_id = '" . (int)$products_id_tmp . "'
                                            ORDER BY products_options, products_options_values");
          if (tep_db_num_rows($attributes_query)) {
            while ($attributes = tep_db_fetch_array($attributes_query)) {
              $option_name_query = tep_db_query("SELECT products_options_name
                                                   from  " . TABLE_PRODUCTS_OPTIONS_TEXT . "
                                                 WHERE products_options_text_id = " . $attributes['products_options_id']. "
                                                   and language_id = " . (int)$languages_id . " ");
              $products_options_name = '';
              while ($option_name_query_array = tep_db_fetch_array($option_name_query)) {
                $products_options_name = $option_name_query_array['products_options_name'];
              }
              $this->products[$index]['attributes'][$subindex] = array(
                                      'option_id' => $attributes['products_options_id'],
                                      'value_id' => $attributes['products_options_values_id'],
                                      'value' => $attributes['products_options_values'],
                                      'option' => (isset($attributes['products_options_name']) ? $attributes['products_options_name'] : ''),
                                      'option_name' => $products_options_name,
                                      'prefix' => $attributes['price_prefix'],
                                      'price' => $attributes['options_values_price']);
              $subindex++;
            }
          }
          $this->info['tax_groups']["{$this->products[$index]['tax']}"] = '1';
		  
		   // Added Sep 08, 2010 - Calculate price break discount
	$shown_price_break =($orders_products['product_original_final_price'] - $this->products[$index]['final_price']) * $this->products[$index]['qty'];
	$this->info['price_break_amount'] += $shown_price_break;
	
          $index++;
        }
      }
    }

    function cart() {
      global $billto, $cart, $languages_id, $currency, $currencies, $shipping, $statea, $country_title_s, $format_id_s; //$customer_id

      $this->content_type = $cart->get_content_type();
      $this->info = array('order_status' => DEFAULT_ORDERS_STATUS_ID,
                          'currency' => DEFAULT_CURRENCY,
                          'currency_value' => $currencies->currencies[DEFAULT_CURRENCY]['value'],
                          'payment_method' => $_SESSION['payment'],
                          'shipping_method' => $shipping['title'],
                          'shipping_cost' => $shipping['cost'],
                          'subtotal' => 0,
                          'tax' => 0,
                          'tax_groups' => array(),
                          'comments' => (isset($GLOBALS['comments']) ? $GLOBALS['comments'] : ''));

      if (isset($_SESSION['payment']) && is_object($_SESSION['payment'])) {
        $this->info['payment_method'] = $_SESSION['payment']->title;

        if ( isset($_SESSION['payment']->order_status) && is_numeric($_SESSION['payment']->order_status) && ($_SESSION['payment']->order_status > 0) ) {
          $this->info['order_status'] = $_SESSION['payment']->order_status;
        }
      }
      $customer_address_query = tep_db_query("select c.customers_firstname, c.customers_lastname, c.customers_email_address, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, ab.entry_state, ab.entry_country_id, ab.entry_telephone as customers_telephone from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " ab  where c.customers_id = '" . (int)$customer_id . "' and ab.customers_id = '" . (int)$customer_id . "' and c.customers_default_address_id = ab.address_book_id"); 
      while ( $customer_address = tep_db_fetch_array($customer_address_query) ) {
        $customer_country_query = tep_db_query("select co.countries_id, co.countries_name, co.countries_iso_code_2, co.countries_iso_code_3, co.address_format_id from " . TABLE_COUNTRIES . " co  where co.countries_id = '" . $customer_address['entry_country_id'] . "'");
        while ($customer_country = tep_db_fetch_array($customer_country_query) ) {
          $country_array = array('id' => $customer_country['countries_id'], 'title' => $customer_country['countries_name'], 'iso_code_2' => $customer_country['countries_iso_code_2'], 'iso_code_3' => $customer_country['countries_iso_code_3']);
          $customer_zone_query = tep_db_query("select z.zone_name from " . TABLE_ZONES . " z where z.zone_id ='" . $customer_address['entry_zone_id'] . "' ");
          if (tep_not_null($customer_address['entry_state'])) {
            $states = $customer_address['entry_state'];
          } else {
            while ($customer_zone1 = tep_db_fetch_array($customer_zone_query) ) {
              $states = $customer_zone1['zone_name'];
            }
          }
          $this->customer = array('firstname' => $customer_address['customers_firstname'],
                                  'lastname' => $customer_address['customers_lastname'],
                                  'company' => $customer_address['entry_company'],
                                  'street_address' => $customer_address['entry_street_address'],
                                  'suburb' => $customer_address['entry_suburb'],
                                  'city' => $customer_address['entry_city'],
                                  'postcode' => $customer_address['entry_postcode'],
                                  'state' => $states,
                                  'zone_id' => $customer_address['entry_zone_id'],
                                  'country' => $country_array,
                                  'country_id' => $customer_address['entry_country_id'],
                                  'format_id' => $customer_country['address_format_id'],
                                  'telephone' => $customer_address['customers_telephone'],
                                  'email_address' => $customer_address['customers_email_address'],
                                 );
        }
      }
      $shipping_address_query = tep_db_query("select ab.entry_firstname, ab.entry_lastname, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, ab.entry_country_id, ab.entry_state from " . TABLE_ADDRESS_BOOK . " ab where ab.customers_id = '" . (int)$customer_id . "' and ab.address_book_id = '" . (int)$_SESSION['sendto'] . "'");
      while ($shipping_address = tep_db_fetch_array($shipping_address_query) ) {
        $shipping_zone_query= tep_db_query("select co.countries_id, co.countries_name, co.countries_iso_code_2, co.countries_iso_code_3, co.address_format_id from "  . TABLE_COUNTRIES . " co  where co.countries_id = '" . $shipping_address['entry_country_id'] ."'");
        while ($shipping_zone = tep_db_fetch_array($shipping_zone_query) ) {
          $s_country = array('id' => $shipping_zone['countries_id'], 'title' => $shipping_zone['countries_name'], 'iso_code_2' => $shipping_zone['countries_iso_code_2'], 'iso_code_3' => $shipping_zone['countries_iso_code_3']);
          $shipping_zone_query1= tep_db_query("select  z.zone_name from " . TABLE_ZONES . " z where z.zone_id = '" . $shipping_address['entry_zone_id'] . "' ");
          if (tep_not_null($shipping_address['entry_state'])) {
            $s_states = $shipping_address['entry_state'];
          } else {
            while ($shipping_zone1 = tep_db_fetch_array($shipping_zone_query1) ) {
              $s_states = $shipping_zone1['zone_name'];
            }
          }
          $this->delivery = array('firstname' => $shipping_address['entry_firstname'],
                                  'lastname' => $shipping_address['entry_lastname'],
                                  'company' => $shipping_address['entry_company'],
                                  'street_address' => $shipping_address['entry_street_address'],
                                  'suburb' => $shipping_address['entry_suburb'],
                                  'city' => $shipping_address['entry_city'],
                                  'postcode' => $shipping_address['entry_postcode'],
                                  'state' => $s_states,
                                  'zone_id' => $shipping_address['entry_zone_id'],
                                  'country' =>  $s_country,
                                  'country_id' => $shipping_address['entry_country_id'],
                                  'format_id' => $shipping_zone['address_format_id']);
        }
      }
      $billing_address_query = tep_db_query("select ab.entry_firstname, ab.entry_lastname, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, ab.entry_country_id, ab.entry_state from " . TABLE_ADDRESS_BOOK . " ab where ab.customers_id = '" . (int)$customer_id . "' and ab.address_book_id = '" . (int)$billto . "'");
      while ($billing_address = tep_db_fetch_array($billing_address_query) ) {
        $billing_zone_query= tep_db_query("select co.countries_id, co.countries_name, co.countries_iso_code_2, co.countries_iso_code_3, co.address_format_id from " . TABLE_COUNTRIES . " co  where co.countries_id = '" . $billing_address['entry_country_id'] ."'");
        while ($billing_zone = tep_db_fetch_array($billing_zone_query) ) {
          $b_country = array('id' => $billing_zone['countries_id'], 'title' => $billing_zone['countries_name'], 'iso_code_2' => $billing_zone['countries_iso_code_2'], 'iso_code_3' => $billing_zone['countries_iso_code_3']);
          $billing_zone_query1= tep_db_query("select z.zone_name from " . TABLE_ZONES . " z where z.zone_id ='" . $billing_address['entry_zone_id'] . "' ");
          if (tep_not_null($billing_address['entry_state'])){
            $b_state = $billing_address['entry_state'];
          } else {
            while ($billing_zone1 = tep_db_fetch_array($billing_zone_query1) ) {
              $b_state = $billing_zone1['zone_name'];
            }
          }
          $this->billing = array('firstname' => $billing_address['entry_firstname'],
                                 'lastname' => $billing_address['entry_lastname'],
                                 'company' => $billing_address['entry_company'],
                                 'street_address' => $billing_address['entry_street_address'],
                                 'suburb' => $billing_address['entry_suburb'],
                                 'city' => $billing_address['entry_city'],
                                 'postcode' => $billing_address['entry_postcode'],
                                  'state' => $b_state,
                                  'zone_id' => $billing_address['entry_zone_id'],
                                  'country' => $b_country,
                                  'country_id' => $billing_address['entry_country_id'],
                                  'format_id' => $billing_zone['address_format_id']);
        }
      }
      $tax_address_query = tep_db_query("select ab.entry_country_id, ab.entry_zone_id from " . TABLE_ADDRESS_BOOK . " ab where ab.customers_id = '" . (int)$customer_id . "' and ab.address_book_id = '" . (int)($this->content_type == 'virtual' ? $billto : $_SEESIOM['sendto']) . "'");
      $tax_address = tep_db_fetch_array($tax_address_query);

      $index = 0;
      $products = $cart->get_products();
      for ($i=0, $n=sizeof($products); $i<$n; $i++) {
        $this->products[$index] = array('qty' => $products[$i]['quantity'],
                                        'name' => $products[$i]['name'],
                                        'model' => $products[$i]['model'],
                                        'tax' => tep_get_tax_rate($products[$i]['tax_class_id'], $tax_address['entry_country_id'], $tax_address['entry_zone_id']),
                                        'tax_description' => tep_get_tax_description($products[$i]['tax_class_id'], $tax_address['entry_country_id'], $tax_address['entry_zone_id']),
                                        'price' => $products[$i]['price'],
                                        'final_price' => $products[$i]['price'] + $cart->attributes_price($products[$i]['id']),
                                        'weight' => $products[$i]['weight'],
                                        'id' => $products[$i]['id']);
        $products_id_tmp = tep_get_prid($products[$i]['id']);
        if ($products[$i]['attributes']) {
          $subindex = 0;
          reset($products[$i]['attributes']);
          while (list($option, $value) = each($products[$i]['attributes'])) {
            if (tep_subproducts_parent($products_id_tmp)) {
              $products_id_query = tep_subproducts_parent($products_id_tmp);
            } else {
              $products_id_query = $products_id_tmp;
            }
            if ( !is_array($value) ) {
              $attributes_query = tep_db_query("select op.options_id, ot.products_options_name, o.options_type, ov.products_options_values_name, op.options_values_price as price, op.price_prefix
                                                from " . TABLE_PRODUCTS_ATTRIBUTES . " op,
                                                     " . TABLE_PRODUCTS_OPTIONS . " o,
                                                     " . TABLE_PRODUCTS_OPTIONS_TEXT . " ot,
                                                     " . TABLE_PRODUCTS_OPTIONS_VALUES . " ov
                                                where op.products_id = '" . $products_id_query . "'
                                                  and op.options_id = '" . $option . "'
                                                  and o.products_options_id = '" . $option . "'
                                                  and ot.products_options_text_id = '" . $option . "'
                                                  and op.options_values_id = '" . $value . "'
                                                  and ov.products_options_values_id = '" . $value . "'
                                                  and ov.language_id = '" . (int)$languages_id . "'
                                                  and ot.language_id = '" . (int)$languages_id . "'
                                               ");
              $attributes = tep_db_fetch_array($attributes_query);
              $this->products[$index]['attributes'][$subindex] = array('option' => $attributes['products_options_name'],
                                                                       'value' => $attributes['products_options_values_name'],
                                                                       'option_id' => $option,
                                                                       'value_id' => $value,
                                                                       'prefix' => $attributes['price_prefix'],
                                                                       'price' => $attributes['price']);
              $subindex++;
            } elseif ( isset($value['c'] ) ) {
              foreach ($value['c'] as $v) {
                $attributes_query = tep_db_query("select op.options_id, ot.products_options_name, o.options_type, ov.products_options_values_name, op.options_values_price as price, op.price_prefix
                                                  from " . TABLE_PRODUCTS_ATTRIBUTES . " op,
                                                     " . TABLE_PRODUCTS_OPTIONS . " o,
                                                     " . TABLE_PRODUCTS_OPTIONS_TEXT . " ot,
                                                     " . TABLE_PRODUCTS_OPTIONS_VALUES . " ov
                                                  where op.products_id = '" . $products_id_query . "'
                                                    and op.options_id = '" . $option . "'
                                                    and o.products_options_id = '" . $option . "'
                                                    and ot.products_options_text_id = '" . $option . "'
                                                    and op.options_values_id = '" . $v . "'
                                                    and ov.products_options_values_id = '" . $v . "'
                                                    and ov.language_id = '" . (int)$languages_id . "'
                                                    and ot.language_id = '" . (int)$languages_id . "'
                                                 ");
                $attributes = tep_db_fetch_array($attributes_query);
                $this->products[$index]['attributes'][$subindex] = array('option' => $attributes['products_options_name'],
                                                                       'value' => $attributes['products_options_values_name'],
                                                                       'option_id' => $option,
                                                                       'value_id' => $v,
                                                                       'prefix' => $attributes['price_prefix'],
                                                                       'price' => $attributes['price']);
                $subindex++;
              }
            } elseif ( isset($value['t'] ) ) {
              $attributes_query = tep_db_query("select op.options_id, ot.products_options_name, o.options_type, op.options_values_price as price, op.price_prefix
                                                from " . TABLE_PRODUCTS_ATTRIBUTES . " op,
                                                     " . TABLE_PRODUCTS_OPTIONS . " o,
                                                     " . TABLE_PRODUCTS_OPTIONS_TEXT . " ot
                                                where op.products_id = '" . $products_id_query . "'
                                                  and op.options_id = '" . $option . "'
                                                  and o.products_options_id = '" . $option . "'
                                                  and ot.products_options_text_id = '" . $option . "'
                                                  and ot.language_id = '" . (int)$languages_id . "'
                                               ");
              $attributes = tep_db_fetch_array($attributes_query);
              $this->products[$index]['attributes'][$subindex] = array('option' => $attributes['products_options_name'],
                                                                       'value' => $value['t'],
                                                                       'option_id' => $option,
                                                                       'value_id' => '0',
                                                                       'prefix' => $attributes['price_prefix'],
                                                                       'price' => $attributes['price']);
              $subindex++;
            }
          }
        }

        $shown_price = tep_add_tax($this->products[$index]['final_price'], $this->products[$index]['tax']) * $this->products[$index]['qty'];
        $this->info['subtotal'] += $shown_price;

        $products_tax = $this->products[$index]['tax'];
        $products_tax_description = $this->products[$index]['tax_description'];

        if (!isset($_SESSION['sppc_customer_group_show_tax'])) {
          $_SESSION['sppc_customer_group_show_tax'] = '1';
        } else {
          $customer_group_show_tax = $_SESSION['sppc_customer_group_show_tax'];
        }
        if (DISPLAY_PRICE_WITH_TAX == 'true' && $customer_group_show_tax == '1') {
          $this->info['tax'] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
          if (isset($this->info['tax_groups']["$products_tax_description"])) {
            $this->info['tax_groups']["$products_tax_description"] += $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
          } else {
            $this->info['tax_groups']["$products_tax_description"] = $shown_price - ($shown_price / (($products_tax < 10) ? "1.0" . str_replace('.', '', $products_tax) : "1." . str_replace('.', '', $products_tax)));
          }
        } else {
          $this->info['tax'] += ($products_tax / 100) * $shown_price;
          if (isset($this->info['tax_groups']["$products_tax_description"])) {
            $this->info['tax_groups']["$products_tax_description"] += ($products_tax / 100) * $shown_price;
          } else {
            $this->info['tax_groups']["$products_tax_description"] = ($products_tax / 100) * $shown_price;
          }
        }
        $index++;
      }
      if (!isset($_SESSION['sppc_customer_group_show_tax'])) {
        $customer_group_show_tax = '1';
      } else {
        $customer_group_show_tax = $_SESSION['sppc_customer_group_show_tax'];
      }
      if ((DISPLAY_PRICE_WITH_TAX == 'true') && ($customer_group_show_tax == '1')) {
        $this->info['total'] = $this->info['subtotal'] + $this->info['shipping_cost'];
      } else {
        $this->info['total'] = $this->info['subtotal'] + $this->info['tax'] + $this->info['shipping_cost'];
      }
    }

  }
?>