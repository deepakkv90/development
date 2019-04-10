<?php
/*
  $Id: paypal.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

  define('FILENAME_PAYPAL_INFO','popup_paypal.php');
  define('FILENAME_IPN','ipn.php');

  class paypal {
    var $code, $title, $description, $enabled;

// class constructor
    function paypal() {
      global $order;
      $this->code = 'paypal';
      $this->codeTitle = 'PayPal';
      $this->title = MODULE_PAYMENT_PAYPAL_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_PAYPAL_TEXT_DESCRIPTION;
      if (defined('MODULE_PAYMENT_PAYPAL_SORT_ORDER')) {
        $this->sort_order = (int)MODULE_PAYMENT_PAYPAL_SORT_ORDER;
      } else {
        $this->sort_order = '';
      }

      if (defined('MODULE_PAYMENT_PAYPAL_STATUS')) {
        $this->enabled = ((MODULE_PAYMENT_PAYPAL_STATUS == 'True') ? true : false);
      } else {
        $this->enabled = false;
      }

      if (defined('MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID')) {
        if ((int)MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID > 0) {
          $this->order_status = MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID;
        }
      } else {
        $this->order_status = 0;
      }
      if (is_object($order)) $this->update_status();
      if (defined('MODULE_PAYMENT_PAYPAL_DOMAIN')) {
        $this->form_paypal_url = 'https://' . MODULE_PAYMENT_PAYPAL_DOMAIN . '/cgi-bin/webscr';
      } else {
        $this->form_paypal_url = '';
      }
      $this->cc_explain_url = tep_href_link(FILENAME_PAYPAL_INFO, '', 'SSL');
   }

// catalog payment module class methods
    function update_status() {
      global $order;
      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_PAYPAL_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_PAYPAL_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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

    function javascript_validation() {
      return false;
    }

    function selection() {
      $img_visa = DIR_WS_MODULES .'payment/paypal/images/visa.gif';
      $img_mc = DIR_WS_MODULES .'payment/paypal/images/mastercard.gif';
      $img_discover = DIR_WS_MODULES .'payment/paypal/images/discover.gif';
      $img_amex = DIR_WS_MODULES .'payment/paypal/images/amex.gif';
      $img_paypal = DIR_WS_MODULES .'payment/paypal/images/paypal_intl.gif';
      $jscript_url = '<a style="cursor: pointer; "' . " onclick=\"javascript:popup=window.open(\'" . $this->cc_explain_url . "\',\'popup\',\'scrollbars,resizable,width=625,height=600,left=50,top=50\'); popup.focus(); return false;\">";
      $cc_explain = '<div style="A.hover{cursor:hand}">' . MODULE_PAYMENT_PAYPAL_CC_DESCRIPTION .'&nbsp;' .
                    "<script>document.writeln('" . $jscript_url .
                    MODULE_PAYMENT_PAYPAL_CC_URL_TEXT . "</a>');</script>" .
                    '<noscript><a href="' . $this->cc_explain_url . '" target="_blank">' .
                    MODULE_PAYMENT_PAYPAL_CC_URL_TEXT . '</noscript>' ."\n".'</div>';
      $paypal_cc_txt = sprintf(MODULE_PAYMENT_PAYPAL_CC_TEXT,
                              tep_image($img_visa,' Visa ','','','align="absmiddle"'),
                              tep_image($img_mc,' MasterCard ','','','align="absmiddle"'),
                              tep_image($img_discover,' Discover ','','','align="absmiddle"'),
                              tep_image($img_amex,' American Express ','','','align="absmiddle"'),
                              tep_image($img_paypal,' PayPal ','','','align="absmiddle"')
                             );
      //Modified Sep 20, 2010
	  /*
	  $fields[] = array('title' => '', //MODULE_PAYMENT_PAYPAL_TEXT_TITLE,
                        'field' => '<div><b>' . $paypal_cc_txt . '</b></div>' . $cc_explain );*/
	
	  $fields[] = array('title' => '', //MODULE_PAYMENT_PAYPAL_TEXT_TITLE,
                        'field' => '<div><b>' . tep_image($img_paypal,' PayPal ','','','align="absmiddle"') . '</b></div>');
      return array('id' => $this->code,
                   'module' => $this->title,
                   'fields' => $fields);
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return false;
    }

    function currency() {
      global $currency;
      if(!isset($this->currency)) {
        if (MODULE_PAYMENT_PAYPAL_CURRENCY == 'Selected Currency') {
          $this->currency = $currency;
        } else {
          $this->currency = substr(MODULE_PAYMENT_PAYPAL_CURRENCY, 5);
        }
        if (!in_array($this->currency, array('CAD', 'EUR', 'GBP', 'JPY', 'USD', 'AUD'))) {
          $this->currency = MODULE_PAYMENT_PAYPAL_DEFAULT_CURRENCY;
        }
      }
      return $this->currency;
    }

    //Returns the gross total amount to compare with paypal.mc_gross
    function grossPaymentAmount($my_currency) {
      global $order, $currencies;
      return number_format(($order->info['total']) * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency));
    }

    function amount($my_currency) {
      global $order, $currencies;
      return number_format(($order->info['total'] - $order->info['shipping_cost']) * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency));
    }

    function process_button() {
      return false;
    }

    function before_process() {
      if(!class_exists('PayPal_osC')) include_once(DIR_WS_MODULES . 'payment/paypal/classes/osC/osC.class.php');
      if (PayPal_osC::check_order_status()) {
        tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL'));
      } else {
        include(DIR_WS_MODULES . 'payment/paypal/catalog/checkout_process.inc.php');
      }
      exit;
    }

    function after_process() {
      return false;
    }

    function output_error() {
      return false;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable PayPal Module', 'MODULE_PAYMENT_PAYPAL_STATUS', 'True', 'Do you want to accept PayPal payments?&nbsp;<a style=\"color: #0033cc;\" href=\"" . tep_href_link(FILENAME_PAYPAL, 'action=help', 'NONSSL') . "\" target=\"paypalHelp\">[Help]</a>', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('E-Mail Address', 'MODULE_PAYMENT_PAYPAL_ID','".STORE_OWNER_EMAIL_ADDRESS."', 'The e-mail address to use for the PayPal service', '6', '1', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Business ID', 'MODULE_PAYMENT_PAYPAL_BUSINESS_ID','".STORE_OWNER_EMAIL_ADDRESS."', 'Email address or account ID of the payment recipient', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Default Currency', 'MODULE_PAYMENT_PAYPAL_DEFAULT_CURRENCY', 'USD', 'The <b>default</b> currency to use for when the customer chooses to checkout via the store using a currency not supported by PayPal.<br />(This currency must exist in your store)', '6', '0', 'tep_cfg_select_option(array(\'USD\',\'CAD\',\'EUR\',\'GBP\',\'JPY\',\'AUD\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Currency', 'MODULE_PAYMENT_PAYPAL_CURRENCY', 'Selected Currency', 'The currency to use for credit card transactions', '6', '3', 'tep_cfg_select_option(array(\'Selected Currency\',\'Only USD\',\'Only CAD\',\'Only EUR\',\'Only GBP\',\'Only JPY\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_PAYPAL_ZONE', '0', 'If a zone is selected, enable this payment method for that zone only.', '6', '4', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Pending Notification Status', 'MODULE_PAYMENT_PAYPAL_PROCESSING_STATUS_ID', '" . DEFAULT_ORDERS_STATUS_ID .  "', 'Set the Pending Notification status of orders made with this payment module to this value (\'Pending\' recommended)', '6', '5', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID', '" . DEFAULT_ORDERS_STATUS_ID . "', 'Set the status of orders made with this payment module to this value<br>(\'Processing\' recommended)', '6', '6', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set On Hold Order Status', 'MODULE_PAYMENT_PAYPAL_ORDER_ONHOLD_STATUS_ID', '" . DEFAULT_ORDERS_STATUS_ID . "', 'Set the status of <b>On Hold</b> orders made with this payment module to this value', '6', '7', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Canceled Order Status', 'MODULE_PAYMENT_PAYPAL_ORDER_CANCELED_STATUS_ID', '" . DEFAULT_ORDERS_STATUS_ID . "', 'Set the status of <b>Canceled</b> orders made with this payment module to this value', '6', '8', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Synchronize Invoice', 'MODULE_PAYMENT_PAYPAL_INVOICE_REQUIRED', 'False', 'Do you want to specify the order number as the PayPal invoice number?', '6', '9', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_PAYPAL_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '10', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Refunded Order Status', 'MODULE_PAYMENT_PAYPAL_ORDER_REFUNDED_STATUS_ID', '" . DEFAULT_ORDERS_STATUS_ID . "', 'Set the status of <b>Refunded</b> orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Background Color', 'MODULE_PAYMENT_PAYPAL_CS', 'White', 'Select the background color of PayPal\'s payment pages.', '6', '11', 'tep_cfg_select_option(array(\'White\',\'Black\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Processing logo', 'MODULE_PAYMENT_PAYPAL_PROCESSING_LOGO', 'loaded_header_logo.gif', 'The image file name to display the store\'s checkout process', '6', '12', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Store logo', 'MODULE_PAYMENT_PAYPAL_STORE_LOGO', '', 'The image file name for PayPal to display (leave empty if your store does not have SSL)', '6', '13', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('PayPal Page Style Name', 'MODULE_PAYMENT_PAYPAL_PAGE_STYLE', 'default', 'The name of the page style you have configured in your PayPal Account', '6', '14', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Include a note with payment', 'MODULE_PAYMENT_PAYPAL_NO_NOTE', 'No', 'Choose whether your customer should be prompted to include a note or not?', '6', '15', 'tep_cfg_select_option(array(\'Yes\',\'No\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Shopping Cart Method', 'MODULE_PAYMENT_PAYPAL_METHOD', 'Aggregate', 'What type of shopping cart do you want to use?', '6', '16', 'tep_cfg_select_option(array(\'Aggregate\',\'Itemized\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable PayPal Shipping Address', 'MODULE_PAYMENT_PAYPAL_SHIPPING_ALLOWED', 'No', 'Allow the customer to choose their own PayPal shipping address?', '6', '17', 'tep_cfg_select_option(array(\'Yes\',\'No\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Debug Email Notifications', 'MODULE_PAYMENT_PAYPAL_IPN_DEBUG', 'Yes', 'Enable debug email notifications', '6', '18', 'tep_cfg_select_option(array(\'Yes\',\'No\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Digest Key', 'MODULE_PAYMENT_PAYPAL_IPN_DIGEST_KEY', 'PayPal_Shopping_Cart_IPN', 'Key to use for the digest functionality', '6', '19', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Test Mode', 'MODULE_PAYMENT_PAYPAL_IPN_TEST_MODE', 'Off', 'Set test mode <a style=\"color: #0033cc;\" href=\"" . tep_href_link(FILENAME_PAYPAL, 'action=itp') . "\" target=\"ipn\">[IPN Test Panel]</a>', '6', '20', 'tep_cfg_select_option(array(\'Off\',\'On\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Cart Test', 'MODULE_PAYMENT_PAYPAL_IPN_CART_TEST', 'On', 'Set cart test mode to verify the transaction amounts', '6', '21', 'tep_cfg_select_option(array(\'Off\',\'On\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Debug Email Notification Address', 'MODULE_PAYMENT_PAYPAL_IPN_DEBUG_EMAIL','".STORE_OWNER_EMAIL_ADDRESS."', 'The e-mail address to send <b>debug</b> notifications to', '6', '22', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('PayPal Domain', 'MODULE_PAYMENT_PAYPAL_DOMAIN', 'www.paypal.com', 'Select which PayPal domain to use<br>(for live production select www.paypal.com)', '6', '23', 'tep_cfg_select_option(array(\'www.paypal.com\',\'www.sandbox.paypal.com\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Return URL behavior', 'MODULE_PAYMENT_PAYPAL_RM', '1', 'How should the customer be sent back from PayPal to the specified URL?<br>0=No IPN, 1=GET, 2=POST', '6', '24', 'tep_cfg_select_option(array(\'0\',\'1\',\'2\'), ', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array(
          'MODULE_PAYMENT_PAYPAL_STATUS',
          'MODULE_PAYMENT_PAYPAL_ID',
          'MODULE_PAYMENT_PAYPAL_BUSINESS_ID',
          'MODULE_PAYMENT_PAYPAL_DEFAULT_CURRENCY',
          'MODULE_PAYMENT_PAYPAL_CURRENCY',
          'MODULE_PAYMENT_PAYPAL_ZONE',
          'MODULE_PAYMENT_PAYPAL_PROCESSING_STATUS_ID',
          'MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID',
          'MODULE_PAYMENT_PAYPAL_ORDER_ONHOLD_STATUS_ID',
          'MODULE_PAYMENT_PAYPAL_ORDER_REFUNDED_STATUS_ID',
          'MODULE_PAYMENT_PAYPAL_ORDER_CANCELED_STATUS_ID',
          'MODULE_PAYMENT_PAYPAL_INVOICE_REQUIRED',
          'MODULE_PAYMENT_PAYPAL_SORT_ORDER',
          'MODULE_PAYMENT_PAYPAL_CS',
          'MODULE_PAYMENT_PAYPAL_PROCESSING_LOGO',
          'MODULE_PAYMENT_PAYPAL_STORE_LOGO',
          'MODULE_PAYMENT_PAYPAL_PAGE_STYLE',
          'MODULE_PAYMENT_PAYPAL_NO_NOTE',
          'MODULE_PAYMENT_PAYPAL_METHOD',
          'MODULE_PAYMENT_PAYPAL_SHIPPING_ALLOWED',
          'MODULE_PAYMENT_PAYPAL_IPN_DIGEST_KEY',
          'MODULE_PAYMENT_PAYPAL_IPN_TEST_MODE',
          'MODULE_PAYMENT_PAYPAL_IPN_CART_TEST',
          'MODULE_PAYMENT_PAYPAL_IPN_DEBUG',
          'MODULE_PAYMENT_PAYPAL_IPN_DEBUG_EMAIL',
          'MODULE_PAYMENT_PAYPAL_DOMAIN',
          'MODULE_PAYMENT_PAYPAL_RM');
    }

    function setTransactionID() {
      global $order, $currencies;
      $my_currency = $this->currency();
      $trans_id = STORE_NAME . date('Ymdhis');
      $this->digest = md5($trans_id . number_format($order->info['total'] * $currencies->get_value($my_currency), $currencies->get_decimal_places($my_currency), '.', '') . MODULE_PAYMENT_PAYPAL_IPN_DIGEST_KEY);
      return $this->digest;
    }

    function formFields($txn_sign = '', $payment_amount = '', $payment_currency = '', $payment_currency_value = '', $orders_id = '', $return_url = '', $cancel_url = '' ) {
      global $order, $currencies;
      $my_currency = (tep_not_null($payment_currency)) ? $payment_currency : $this->currency();
      $my_currency_value = (tep_not_null($payment_currency_value)) ? $payment_currency_value : $currencies->get_value($my_currency);
      $paypal_fields = tep_draw_hidden_field('cmd', '_ext-enter') . //allows the customer addr details to be passed
      tep_draw_hidden_field('business', MODULE_PAYMENT_PAYPAL_BUSINESS_ID) ;
	  
	  //Modified Feb 16 2011
	  if ( isset($order->info['cot_gv']) ) {
			$paypal_fields .= tep_draw_hidden_field('discount_amount_cart', $order->info['cot_gv_amount']);
	  } else if(isset($order->info['cc_id'])) {
			$paypal_fields .= tep_draw_hidden_field('discount_amount_cart', $order->info['gv_redeem_amount']);
	  }
		
      $paypal_fields .= tep_draw_hidden_field('currency_code', $my_currency);
      if(tep_not_null(MODULE_PAYMENT_PAYPAL_STORE_LOGO)) $paypal_fields .= tep_draw_hidden_field('image_url', tep_href_link(DIR_WS_IMAGES.MODULE_PAYMENT_PAYPAL_STORE_LOGO, '', 'SSL'));
      $return_href_link = tep_not_null($return_url) ? $return_url : tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'action=success&order_id='. $this->orders_id . '&' . tep_session_name() . '=' . tep_session_id(), 'SSL', false);
      $cancel_href_link = tep_not_null($cancel_url) ? $cancel_url : tep_href_link(FILENAME_CHECKOUT_PAYMENT, tep_session_name() . '=' . tep_session_id(), 'SSL', false);
      $paypal_fields .= tep_draw_hidden_field('return', $return_href_link);
      $paypal_fields .= tep_draw_hidden_field('cancel_return', $cancel_href_link) .
      tep_draw_hidden_field('notify_url', tep_href_link(FILENAME_IPN, '', 'SSL',false));
     if(MODULE_PAYMENT_PAYPAL_SHIPPING_ALLOWED == 'No' ) $paypal_fields .= tep_draw_hidden_field('no_shipping', '1' );
      //$paypal_fields .= tep_draw_hidden_field('address_override', '1' );
	 //Modified Sep 20, 2010
	 $shipping_tax_modified = $order->info['shipping_cost'] + (($order->info['shipping_cost'] / 100 ) * $order->info['tax_class_id']);
     if(MODULE_PAYMENT_PAYPAL_METHOD == 'Itemized') {
        $paypal_fields .= tep_draw_hidden_field('upload', sizeof($order->products)) .
        tep_draw_hidden_field('redirect_cmd', '_cart') .
        tep_draw_hidden_field('handling_cart', number_format($shipping_tax_modified * $my_currency_value, $currencies->get_decimal_places($my_currency)));
        //Let PayPal Calculate the amount since we're essentially uploading a shopping cart
        //tep_draw_hidden_field('amount', number_format(($order->info['total'] - $order->info['shipping_cost']) * $my_currency_value, $currencies->get_decimal_places($my_currency)));
      } else {
        $paypal_fields .= tep_draw_hidden_field('item_name', STORE_NAME) .
        tep_draw_hidden_field('redirect_cmd', '_xclick');
        //$paypal_fields .= tep_draw_hidden_field('amount', $amount) .
		$paypal_fields .= tep_draw_hidden_field('amount', tep_round($order->info['total'],2));
        //$paypal_fields .= tep_draw_hidden_field('shipping', number_format($shipping_tax_modified * $my_currency_value, $currencies->get_decimal_places($my_currency)));
      }

      //See Manual: 0= No IPN, 1 = GET, 2 = POST
      $paypal_fields .= tep_draw_hidden_field('rm', MODULE_PAYMENT_PAYPAL_RM);
      $invoice_id = (tep_not_null($orders_id)) ? $orders_id : $this->orders_id;
      $signature = (tep_not_null($txn_sign)) ? $txn_sign : $this->digest;
      $paypal_fields .= tep_draw_hidden_field('custom', $signature);
      if(MODULE_PAYMENT_PAYPAL_INVOICE_REQUIRED == 'True') $paypal_fields .= tep_draw_hidden_field('invoice', $invoice_id);
      $paypal_fields .= $this->customerDetailsFields($order) .
      //Customer comment field
      $this->noteOptionFields(MODULE_PAYMENT_PAYPAL_NO_NOTE , MODULE_PAYMENT_PAYPAL_CUSTOMER_COMMENTS) .
      //PayPal Background Color
      tep_draw_hidden_field('cs',(MODULE_PAYMENT_PAYPAL_CS == 'White') ? '0' : '1') ;
      if(tep_not_null(MODULE_PAYMENT_PAYPAL_PAGE_STYLE)) $paypal_fields .= tep_draw_hidden_field('page_style',MODULE_PAYMENT_PAYPAL_PAGE_STYLE);
      if(MODULE_PAYMENT_PAYPAL_METHOD == 'Itemized') {
        //Itemized Order Details
		$totaltax = 0;
        for ($i=0; $i<sizeof($order->products); $i++) {
          $index = $i+1;
          $paypal_fields .= tep_draw_hidden_field('item_name_'.$index, $order->products[$i]['name']).
          tep_draw_hidden_field('item_number_'.$index, $order->products[$i]['model']).
          tep_draw_hidden_field('quantity_'.$index, $order->products[$i]['qty']).
          tep_draw_hidden_field('amount_'.$index, tep_round($order->products[$i]['final_price']* $my_currency_value,2));
          
		  //Modified by hiding taxwise item (because discount not working tax_x is enabled) - Feb 16 2011
		  
		  //$tax = ($order->products[$i]['final_price'] * ($order->products[$i]['tax'] / 100)) * $my_currency_value;		  
		  $tax[$i] = ($order->products[$i]['final_price'] * ($order->products[$i]['tax'] / 100)) * $my_currency_value;
          $totaltax = $totaltax + ($tax[$i] * $order->products[$i]['qty']);
		  //$paypal_fields .= tep_draw_hidden_field('tax_'.$index, number_format($tax, 2));
          
		  
		  //Customer Specified Product Options: PayPal Max = 2
          if ($order->products[$i]['attributes']) {
            for ($j=0, $n=sizeof($order->products[$i]['attributes']); $j<2; $j++) {
              if($order->products[$i]['attributes'][$j]['option']){
                $paypal_fields .= $this->optionSetFields($j,$index,$order->products[$i]['attributes'][$j]['option'],$order->products[$i]['attributes'][$j]['value']);
              } else {
                $paypal_fields .= $this->optionSetFields($j,$index);
              }
            }
          } else {
            for ($j=0; $j<2; $j++) {
              $paypal_fields .= $this->optionSetFields($j,$index);
            }
          }
        }
		
		//Modified by hiding taxwise item (because discount not working tax_x is enabled) - Feb 16 2011
		$paypal_fields .= tep_draw_hidden_field('tax_cart', number_format($totaltax,2,'.',''));
		
      } else { //method 1
        $item_number;
        for ($i=0; $i<sizeof($order->products); $i++) {
          $item_number .= ' '.$order->products[$i]['name'].' ,';
        }
        $item_number = substr_replace($item_number,'',-2);
        $paypal_fields .= tep_draw_hidden_field('item_number', $item_number);
      }
      return $paypal_fields;
    }

    function customerDetailsFields(&$order) {
      //Customer Details - for those who haven't signed up to PayPal
      $paypal_fields = tep_draw_hidden_field('email', $order->customer['email_address']) .
      tep_draw_hidden_field('first_name', $order->billing['firstname']) .
      tep_draw_hidden_field('last_name', $order->billing['lastname']) .
      tep_draw_hidden_field('address1', $order->billing['street_address']) .
      tep_draw_hidden_field('address2', $order->billing['suburb']) .
      tep_draw_hidden_field('city', $order->billing['city']) .
      //tep_draw_hidden_field('state', tep_get_zone_code($order->billing['country']['id'],$order->billing['zone_id'],$order->billing['zone_id'])) .
      tep_draw_hidden_field('state', $order->billing['state']) .
      tep_draw_hidden_field('zip', $order->billing['postcode']);

      //User Country Preference
      //Note: Anguilla[AI], Dominican Republic[DO], The Netherlands[NL] have different codes to the iso codes in the osC db
      $paypal_fields .= tep_draw_hidden_field('lc', $order->billing['country']['iso_code_2']);
      //Telephone is problematic.
      /*//OMITTED SINCE NOT SPECIFICALLY BILLING ADDRESS RELATED
        $telephone = preg_replace('/\D/', '', $order->customer['telephone']);
        $paypal_fields .= tep_draw_hidden_field('night_phone_a',substr($telephone,0,3));
        $paypal_fields .= tep_draw_hidden_field('night_phone_b',substr($telephone,3,3));
        $paypal_fields .= tep_draw_hidden_field('night_phone_c',substr($telephone,6,4));
        $paypal_fields .= tep_draw_hidden_field('day_phone_a',substr($telephone,0,3));
        $paypal_fields .= tep_draw_hidden_field('day_phone_b',substr($telephone,3,3));
        $paypal_fields .= tep_draw_hidden_field('day_phone_c',substr($telephone,6,4));
      */
      return $paypal_fields;
    }

    function optionSetFields($sub_index,$index,$option=' ',$value=' ') {
      return tep_draw_hidden_field('on'.$sub_index.'_'.$index,$option).
        tep_draw_hidden_field('os'.$sub_index.'_'.$index,$value);
    }

    function noteOptionFields($option='No',$msg='Add Comments About Your Order') {
      $option = ($option == 'Yes') ? '0': '1';
      $no_note = tep_draw_hidden_field('no_note',$option);
      if (!$option) return $no_note .= tep_draw_hidden_field('cn',$msg);
      else return $no_note;
    }

    function sendMoneyFields(&$order, $orders_id) {
      include_once(DIR_WS_MODULES . 'payment/paypal/database_tables.inc.php');
      $orders_session_query = tep_db_query("select firstname, lastname, payment_amount, payment_currency, payment_currency_val, txn_signature from " . TABLE_ORDERS_SESSION_INFO . " where orders_id ='" . (int)$orders_id . "'");
      $orders_session_info = tep_db_fetch_array($orders_session_query);
      $order->billing['firstname'] = $orders_session_info['firstname'];
      $order->billing['lastname'] = $orders_session_info['lastname'];
      $return_href_link = tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id='.$orders_id, 'SSL');
      $cancel_href_link = $return_href_link;
      return $this->formFields($orders_session_info['txn_signature'], $orders_session_info['payment_amount'], $orders_session_info['payment_currency'], $orders_session_info['payment_currency_val'], $orders_id, $return_href_link, $cancel_href_link);
    }
  }//end class
?>
