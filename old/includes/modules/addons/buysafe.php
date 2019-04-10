<?php
/*
  $Id: buysafe.php,v 1.1.0.0 2007/12/17 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class buysafe {
    var $title, $debug_info;

    function buysafe() {
      $this->code = 'buysafe';
      $this->title = (defined('MODULE_ADDONS_BUYSAFE_TITLE')) ? MODULE_ADDONS_BUYSAFE_TITLE : '';
      $this->description = (defined('MODULE_ADDONS_BUYSAFE_DESCRIPTION')) ? MODULE_ADDONS_BUYSAFE_DESCRIPTION : '';
      $this->enabled = (defined('MODULE_ADDONS_BUYSAFE_STATUS') && MODULE_ADDONS_BUYSAFE_STATUS == 'True') ? true : false;
      $this->sort_order = (defined('MODULE_ADDONS_BUYSAFE_SORT_ORDER')) ? (int)MODULE_ADDONS_BUYSAFE_SORT_ORDER : 0;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ADDONS_BUYSAFE_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function call_api($method, $params = array(), $order_id = '') {
      global $currency, $order;
      if (tep_not_null($currency) && $currency != 'USD') return false;
      $check_stop_api_calls = tep_db_fetch_array(tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ADDONS_BUYSAFE_STOP_API_CALLS_TIME' and now() <= (configuration_value + interval 1 second)"));
      if (tep_not_null($check_stop_api_calls['configuration_value'])) return false;

      switch ($method) {
      case 'AddUpdateShoppingCart':
      case 'SetShoppingCartCheckout':
        require_once(DIR_WS_CLASSES . 'nusoap.php');
        //$soapclient = new nusoap_client(MODULE_ADDONS_BUYSAFE_API_URL, false, false, false, false, false, 5, 5);
        $soapclient = new nusoap_client(MODULE_ADDONS_BUYSAFE_API_URL);
        if ($err = $soapclient->getError()) {
          return array('faultstring' => '<b>Constructor error</b> ' . $err);
        }
        if ($method == 'AddUpdateShoppingCart') $object_reference = 'ShoppingCartAddUpdateRQ';
        if ($method == 'SetShoppingCartCheckout') $object_reference = 'ShoppingCartCheckoutRQ';
        $body = '<' . $object_reference . ' xmlns="http://ws.buysafe.com">
        <ShoppingCartId>' . $this->str_encode(isset($params['buysafe_cart_id']) ? $params['buysafe_cart_id'] : $_SESSION['cre_buySafe_unique_CartId']) . '</ShoppingCartId>
        <PreviouslyCanceledCartID>' . $this->str_encode(isset($params['buysafe_previous_cart_id']) ? $params['buysafe_previous_cart_id'] : '') . '</PreviouslyCanceledCartID>
        <OrderNumber>' . $this->str_encode(isset($params['orders_id']) ? $params['orders_id'] : 'Not_yet_known') . '</OrderNumber>
        <ClientIP>' . $this->str_encode(isset($params['buysafe_client_ip']) ? $params['buysafe_client_ip'] : getenv('REMOTE_ADDR')) . '</ClientIP>
        <SessionId>' . $this->str_encode(isset($params['buysafe_session_id']) ? $params['buysafe_session_id'] : tep_session_id()) . '</SessionId>'
        . $this->get_buyer_info($order) . '
        <WantsBond>
          <HasBoolean>true</HasBoolean>
          <Value>' . $this->str_encode($params['WantsBond']) . '</Value>
        </WantsBond>'
        . $this->get_cart_items($order) . '
        </' . $object_reference . '>';
        $result = $soapclient->call($method, $body, 'http://ws.buysafe.com', 'http://ws.buysafe.com/' . $method, $this->get_headers(), null, 'rpc', 'literal');
        $this->make_debug_info($soapclient, $method, $result);       
        if ($soapclient->fault) {
          return $result;
        } else {
          if ($err = $soapclient->getError()) {
            return array('faultstring' => '<b>Error</b> ' . $err);
          } else {
            return $result;
          }
        }
        break;
      case 'SetShoppingCartCancelOrder':
        require_once(DIR_FS_CATALOG . DIR_WS_CLASSES . 'nusoap.php');
        $soapclient = new nusoap_client(MODULE_ADDONS_BUYSAFE_API_URL, false);
        if ($err = $soapclient->getError()) {
          return array('faultstring' => '<b>Constructor error</b> ' . $err);
        }
        $body = '<SetShoppingCartCancelOrder xmlns="http://ws.buysafe.com">
                   <SetShoppingCartCancelOrderRQ> 
                     <IPAddress>' . $this->str_encode($params['buysafe_client_ip']) . '</IPAddress>
                     <DateProcessed>
                       <DateTimeValue>' . $this->str_encode($params['date_purchased'] ? str_replace(' ', 'T', $params['date_purchased']) : '0001-01-01T00:00:00') . '</DateTimeValue>
                       <HasDateTime>' . ($params['date_purchased'] ? 'true' : 'false') . '</HasDateTime>
                     </DateProcessed>
                     <PaymentProcessed>false</PaymentProcessed>
                     <ShoppingCartId>' . $this->str_encode($params['buysafe_cart_id'] ? $params['buysafe_cart_id'] : $_SESSION['cre_buySafe_unique_CartId']) . '</ShoppingCartId>
                   </SetShoppingCartCancelOrderRQ> 
                 </SetShoppingCartCancelOrder>';
        $result = $soapclient->call($method, $body, 'http://ws.buysafe.com', 'http://ws.buysafe.com/' . $method, $this->get_headers(), null, 'rpc', 'literal');
      
        $this->make_debug_info($soapclient, $method, $result);
        if ($soapclient->fault) {
          return $result;
        } else {
          if ($err = $soapclient->getError()) {
            return array('faultstring' => '<b>Error</b> ' . $err);
          } else {
            return $result;
          }
        }
        break;
      case 'GetbuySAFEDateTime':
        require_once(DIR_WS_CLASSES . 'nusoap.php');
        $soapclient = new nusoap_client(MODULE_ADDONS_BUYSAFE_API_URL, false);
        if ($err = $soapclient->getError()){
          return array('faultstring' => '<b>Constructor error</b> ' . $err);
        }
        $body = '';
        $result = $soapclient->call($method, $body, 'http://ws.buysafe.com', 'http://ws.buysafe.com/' . $method, $this->get_headers($params['private_token']), null, 'rpc', 'literal');
        $this->make_debug_info($soapclient, $method, $result);
        if ($soapclient->fault) {
          return $result;
        } else {
          if ($err = $soapclient->getError()) {
            return array('faultstring' => '<b>Error</b> ' . $err);
          } else {
            return $result;
          }
        }
        break;
      default:
        return array('faultstring' => 'Wrong API Method!');
      }
    }

    function keys() {
      return array('MODULE_ADDONS_BUYSAFE_STATUS', 
                   'MODULE_ADDONS_BUYSAFE_SEAL_TYPE', 
                   'MODULE_ADDONS_BUYSAFE_SEAL_AUTHENTICATION_DATA', 
                   'MODULE_ADDONS_BUYSAFE_STORE_AUTHENTICATION_DATA', 
                   'MODULE_ADDONS_BUYSAFE_DEBUG', 
                   );
    }

    function hidden_keys() {
      return array('MODULE_ADDONS_BUYSAFE_CART_PREFIX', 
                   'MODULE_ADDONS_BUYSAFE_PLATFORM', 
                   'MODULE_ADDONS_BUYSAFE_TOK', 
                   'MODULE_ADDONS_BUYSAFE_API_URL', 
                   'MODULE_ADDONS_BUYSAFE_ROLLOVER_URL', 
                   'MODULE_ADDONS_BUYSAFE_STOP_API_CALLS_TIME'
                   );
    }

    function install() {
      global $languages_id;
      
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable buySAFE Module', 'MODULE_ADDONS_BUYSAFE_STATUS', 'True', 'Select True to enable buySAFE.', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('buySAFE Seal Size', 'MODULE_ADDONS_BUYSAFE_SEAL_TYPE', 'Large', 'Select buySAFE Seal Size here.', '6', '2', 'tep_cfg_select_option(array(\'Large\', \'Medium\', \'Small\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Seal Authentication Data', 'MODULE_ADDONS_BUYSAFE_SEAL_AUTHENTICATION_DATA', '-- none --', 'Authenticate store: <a href=\"http://techsupport.buysafe.com/index.php?_m=knowledgebase&_a=viewarticle&kbarticleid=90\" target=\"_blank\" style=\"text-decoration:underline\">click here</a> for instructions.', '6', '4', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Store Authentication Data', 'MODULE_ADDONS_BUYSAFE_STORE_AUTHENTICATION_DATA', '-- none --', 'The Store Authentication Data as provided by buySAFE.', '6', '5', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Debug Mode', 'MODULE_ADDONS_BUYSAFE_DEBUG', 'False', 'Select True to enable buySAFE debug mode.', '6', '5', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      
      // get template id
      $template_name = defined('DEFAULT_TEMPLATE') ? DEFAULT_TEMPLATE : '';
      $template = tep_db_fetch_array(tep_db_query("SELECT template_id from " . TABLE_TEMPLATE . " WHERE template_name = '" . $template_name . "'"));
      $template_id = $template['template_id'];
      // insert infobox configuration
      tep_db_query("INSERT IGNORE INTO infobox_configuration VALUES ('" . $template_id . "', '', 'buysafe.php', 'BOX_HEADING_BUYSAFE', 'yes', 'left', 1, now(), now(), 'buySAFE', 'infobox', '#FFFFFF')"); 
      // get infobox_id
      $infobox = tep_db_fetch_array(tep_db_query("SELECT infobox_id from " . TABLE_INFOBOX_CONFIGURATION . " WHERE template_id = '" . $template_id . "' and infobox_file_name = 'buysafe.php'"));
      $infobox_id = $infobox['infobox_id'];    
      // insert infobox heading         
      tep_db_query("INSERT IGNORE INTO infobox_heading VALUES ('" . $infobox_id . "', '" . $languages_id . "', 'buySAFE')");

      // create buysafe tables 
      tep_db_query("CREATE TABLE IF NOT EXISTS `buysafe` (
                  `buysafe_id` int(11) NOT NULL  auto_increment,
                  `orders_id` int(11) NOT NULL default 0,
                  `buysafe_cart_id` VARCHAR( 255 ) NOT NULL,
                  `buysafe_client_ip` VARCHAR( 32 ) NOT NULL,
                  `buysafe_session_id` VARCHAR( 64 ) NOT NULL,
                   PRIMARY KEY  (buysafe_id, orders_id)) TYPE=MyISAM;");
                   
      // inject database variable into configuration table
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " VALUES ('', '','TABLE_BUYSAFE', 'buysafe', '', '', '', now(), now(), NULL, NULL)");
      
      // for sandbox testing, comment out the next 5 lines
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " values ('','Checkout API URL', 'MODULE_ADDONS_BUYSAFE_API_URL', 'https://api.buysafe.com/buysafews/checkoutapi.dll', 'The post URL for the Checkout API.', '', '', now(),now(),NULL,NULL)");
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " values ('','Rollover JS URL', 'MODULE_ADDONS_BUYSAFE_ROLLOVER_URL', 'https://seal.buysafe.com/private/rollover/rollover.js', 'The code to process the rollover is provided by buySAFE.', '', '', now(),now(),NULL,NULL)");
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " values ('','buySafe Cart Prefix', 'MODULE_ADDONS_BUYSAFE_CART_PREFIX', 'CRELoade', 'buySafe Cart Prefix.', '', '', now(),now(),NULL,NULL)");
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " values ('','buySafe Token', 'MODULE_ADDONS_BUYSAFE_TOK', 'C00ACE2A-3097-44A1-933A-B81AEDC40ACB', 'buySafe Token.', '', '', now(),now(),NULL,NULL)");
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " values ('','buySAFE Platform', 'MODULE_ADDONS_BUYSAFE_PLATFORM', 'CRELoaded', 'The platform as provided by buySAFE.', '', '', now(),now(),NULL,NULL)");
    
      // for sandbox testing, uncomment the next 5 lines 
      // tep_db_query("insert into " . TABLE_CONFIGURATION . " values ('','buySAFE Platform', 'MODULE_ADDONS_BUYSAFE_PLATFORM', 'CRELoaded_Sandbox', 'The platform as provided by buySAFE.', '', '', now(),now(),NULL,NULL)");  
      // tep_db_query("insert into " . TABLE_CONFIGURATION . " values ('','Checkout API URL', 'MODULE_ADDONS_BUYSAFE_API_URL', 'https://sbws.buysafe.com/buysafews/checkoutapi.dll', 'The post URL for the Checkout API.', '', '', now(),now(),NULL,NULL)");
      // tep_db_query("insert into " . TABLE_CONFIGURATION . " values ('','Rollover JS URL', 'MODULE_ADDONS_BUYSAFE_ROLLOVER_URL', 'https://sb.buysafe.com/private/rollover/rollover.js', 'The code to process the rollover is provided by buySAFE.', '', '', now(),now(),NULL,NULL)");
      // tep_db_query("insert into " . TABLE_CONFIGURATION . " values ('','buySafe Cart Prefix', 'MODULE_ADDONS_BUYSAFE_CART_PREFIX', 'CRELoaded_Sandbox', 'buySafe Cart Prefix.', '', '', now(),now(),NULL,NULL)");
      // tep_db_query("insert into " . TABLE_CONFIGURATION . " values ('','buySafe Token', 'MODULE_ADDONS_BUYSAFE_TOK', '4605598A-7E8A-46E8-B6B6-8A9877F69BE6', 'buySafe Token.', '', '', now(),now(),NULL,NULL)");
    
      // install ot_buysafe module
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_BUYSAFE_SORT_ORDER', '90', 'Sort order of display.', '6', '2', now())");
      //tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . (int)(MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER - 1 > 0 ? (int)MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER - 1 : 60) . "' where configuration_key = 'MODULE_ORDER_TOTAL_BUYSAFE_SORT_ORDER'");
      //define('MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER', (int)(MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER - 1 > 0 ? (int)MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER - 1 : 60)); 
      $this->update_installed_ot_modules();
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ADDONS_BUYSAFE_API_URL'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ADDONS_BUYSAFE_ROLLOVER_URL'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ADDONS_BUYSAFE_CART_PREFIX'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ADDONS_BUYSAFE_TOK'");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'TABLE_BUYSAFE '");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ADDONS_BUYSAFE_PLATFORM'");
      tep_db_query("delete from " . TABLE_INFOBOX_CONFIGURATION . " where infobox_define = 'BOX_HEADING_BUYSAFE'");        
      tep_db_query("delete from " . TABLE_INFOBOX_HEADING . " where box_heading = 'buySAFE'"); 
          
      // Remove ot_buysafe module
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_BUYSAFE_SORT_ORDER'");
      $this->update_installed_ot_modules();
    }

    function update_installed_ot_modules() {
      global $PHP_SELF;
      $module_directory = DIR_FS_CATALOG_MODULES . 'order_total/';
      $module_key = 'MODULE_ORDER_TOTAL_INSTALLED';
      $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
      $directory_array = array();
      if ($dir = @dir($module_directory)) {
        while ($file = $dir->read()) {
          if (!is_dir($module_directory . $file)) {
            if (substr($file, strrpos($file, '.')) == $file_extension) {
              $directory_array[] = $file;
            }
          }
        }
        sort($directory_array);
        $dir->close();
      }
      $installed_modules = array();
      for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
        $file = $directory_array[$i];
        include($module_directory . $file);
        $class = substr($file, 0, strrpos($file, '.'));
        if (tep_class_exists($class)) {
          $module = new $class;
          if ($module->check() > 0) {
            if ($module->sort_order > 0) {
              $installed_modules[$module->sort_order] = $file;
            } else {
              $installed_modules[] = $file;
            }
          }
        }
      }
      ksort($installed_modules);
      $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = '" . $module_key . "'");
      if (tep_db_num_rows($check_query)) {
        $check = tep_db_fetch_array($check_query);
        if ($check['configuration_value'] != implode(';', $installed_modules)) {
          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . implode(';', $installed_modules) . "', last_modified = now() where configuration_key = '" . $module_key . "'");
        }
      } else {
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Installed Modules', '" . $module_key . "', '" . implode(';', $installed_modules) . "', 'This is automatically updated. No need to edit.', '6', '0', now())");
      }
    }

    function get_headers($private_token = '') {
      return '<MerchantServiceProviderCredentials xmlns="http://ws.buysafe.com">
                <Us'.'erN'.'ame>' . $this->str_encode(MODULE_ADDONS_BUYSAFE_PLATFORM) . '</Us'.'erN'.'ame>
                <Pa'.'ssw'.'ord>' . $this->str_encode(MODULE_ADDONS_BUYSAFE_TOK) . '</Pa'.'ssw'.'ord>
              </MerchantServiceProviderCredentials>
              <BuySafeUserCredentials xmlns="http://ws.buysafe.com">
                <AuthenticationTokens>
                  <string>' . $this->str_encode($private_token ? $private_token : MODULE_ADDONS_BUYSAFE_STORE_AUTHENTICATION_DATA) . '</string>
                </AuthenticationTokens>
              </BuySafeUserCredentials>
              <BuySafeWSHeader xmlns="http://ws.buysafe.com">
                <Version>610</Version>
              </BuySafeWSHeader>';
    }

    function get_buyer_info($order_id = '') {
      global $customer_id, $sendto, $billto, $order;

      if ($order) {    
        $arr = explode(' ', $order->customer['name']);
        $firstname = $arr[0]; $lastname = '';
        for ($i = 1; $i < count($arr); $i++) {
          $lastname .= $arr[$i] . ' ';
        }      
        $billing = tep_db_fetch_array(tep_db_query("select countries_iso_code_2 from " . TABLE_COUNTRIES . " where countries_name = '" . tep_db_input($order->customer['country']) . "'"));
        $delivery = tep_db_fetch_array(tep_db_query("select countries_iso_code_2 from " . TABLE_COUNTRIES . " where countries_name = '" . tep_db_input($order->customer['country']) . "'"));
  
        return '<BuyerInfo>                          
                  <FirstName>' . $this->str_encode($firstname) . '</FirstName>
                  <LastName>' . $this->str_encode(trim($lastname)) . '</LastName>
                  <Email>' . $this->str_encode($order->customer['email_address']) . '</Email>
                  <BuyerType>Repeat</BuyerType>
                  <BillingAddress>
                    <PostalCode>' . $this->str_encode($order->customer['postcode']) . '</PostalCode>
                    <CountryCode>' . $this->str_encode($this->validate_country_code($billing['countries_iso_code_2'])) . '</CountryCode>
                  </BillingAddress>
                  <ShippingAddress>
                    <PostalCode>' . $this->str_encode($order->delivery['postcode']) . '</PostalCode>
                    <CountryCode>' . $this->str_encode($this->validate_country_code($delivery['countries_iso_code_2'])) . '</CountryCode>
                  </ShippingAddress>
                </BuyerInfo>';   
      }

      if (isset($_SESSION['customer_id'])) {
        $customer_address = tep_db_fetch_array(tep_db_query("select c.customers_firstname, c.customers_lastname, c.customers_email_address, ab.entry_company, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, z.zone_name, co.countries_id, co.countries_name, co.countries_iso_code_2, co.countries_iso_code_3, co.address_format_id, ab.entry_state from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " co on (ab.entry_country_id = co.countries_id) where c.customers_id = '" . (int)$customer_id . "' and ab.customers_id = '" . (int)$customer_id . "' and c.customers_default_address_id = ab.address_book_id"));
        if (isset($_SESSION['billto']) && $billto != '') {
          $billing_address = tep_db_fetch_array(tep_db_query("select ab.entry_postcode, ab.entry_state, ab.entry_zone_id, ab.entry_state, z.zone_name, co.countries_id, co.countries_name, co.countries_iso_code_2, co.countries_iso_code_3, co.address_format_id from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " co on (ab.entry_country_id = co.countries_id) where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$billto . "'"));
        }
        if (isset($_SESSION['sendto']) && $sendto != '') {
          $shipping_address = tep_db_fetch_array(tep_db_query("select ab.entry_postcode, ab.entry_state, ab.entry_zone_id, ab.entry_state, z.zone_name, co.countries_id, co.countries_name, co.countries_iso_code_2, co.countries_iso_code_3, co.address_format_id from " . TABLE_ADDRESS_BOOK . " ab left join " . TABLE_ZONES . " z on (ab.entry_zone_id = z.zone_id) left join " . TABLE_COUNTRIES . " co on (ab.entry_country_id = co.countries_id) where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$sendto . "'"));
        }
        return '<BuyerInfo>
                  <FirstName>' . $this->str_encode($customer_address['customers_firstname']) . '</FirstName>
                  <LastName>' . $this->str_encode($customer_address['customers_lastname']) . '</LastName>
                  <Email>' . $this->str_encode($customer_address['customers_email_address']) . '</Email>
                  <BuyerType>Registered</BuyerType>
                  <BillingAddress>
                    <PostalCode>' . $this->str_encode(isset($billing_address['entry_postcode']) ? $billing_address['entry_postcode'] : $customer_address['entry_postcode']) . '</PostalCode>
                    <CountryCode>' . $this->str_encode($this->validate_country_code(isset($billing_address['countries_iso_code_2']) ? $billing_address['countries_iso_code_2'] : $customer_address['countries_iso_code_2'])) . '</CountryCode>
                  </BillingAddress>
                  <ShippingAddress>
                    <PostalCode>' . $this->str_encode(isset($shipping_address['entry_postcode']) ? $shipping_address['entry_postcode'] : $customer_address['entry_postcode']) . '</PostalCode>
                    <CountryCode>' . $this->str_encode($this->validate_country_code(isset($shipping_address['countries_iso_code_2']) ? $shipping_address['countries_iso_code_2'] : $customer_address['countries_iso_code_2'])) . '</CountryCode>
                  </ShippingAddress>
                </BuyerInfo>';
      } else return '<BuyerInfo><BuyerType>Unknown</BuyerType></BuyerInfo>';
    }

    function get_cart_items($order_id = '') {
      global $cart, $order, $currencies, $currency, $languages_id;

      if ($order) {
        $items_string = ''; 
        $MIC_modifier = '';
        $i = 0;
        foreach ($order->products as $product) {
          $attributes_string = ''; $i++;
          if ($product['attributes']) {
            reset($product['attributes']);
            foreach ($product['attributes'] as $attribute) {
              $MIC_modifier .= $attribute['value'];
              $attributes_string .= $attribute['option'] . ': ' . $attribute['value'] . ' (' . $attribute['prefix'] . $currencies->format($attribute['price']) . ');';
            }
          }
          $check_unique = tep_db_fetch_array(tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_model = '" . tep_db_input($product['model']) . "' group by products_model"));
          $items_string .= '<ShoppingCartItem>
                              <UserToken>' . $this->str_encode(MODULE_ADDONS_BUYSAFE_STORE_AUTHENTICATION_DATA) . '</UserToken>
                              <MarketplaceItemCode>' . $this->str_encode((tep_not_null($product['model']) && $check_unique['total'] == 1) ? $product['model'] . $MIC_modifier : $product['name'] . $MIC_modifier) . '</MarketplaceItemCode>
                              <StockKeepingUnit>' . $this->str_encode($product['model']) . '</StockKeepingUnit>
                              <Title>' . $this->str_encode($product['name']) . '</Title>
                              <Attributes>' . $this->str_encode($attributes_string) . '</Attributes>
                              <isRestricted>false</isRestricted>
                              <QuantityPurchased>' . $this->str_encode($product['qty']) . '</QuantityPurchased>
                              <PriceInfo>
                                <FinalPrice>
                                  <CurrencyCode>' . $this->str_encode($order->info['currency']) . '</CurrencyCode>
                                  <Value>' . number_format(tep_round($product['final_price'], 2), 2, '.', '') . '</Value>
                                </FinalPrice>
                              </PriceInfo>
                              <URLInfo>
                                <ViewItem>' . tep_href_link('product_info.php', 'products_id=' . $product['id'], 'NONSSL') . '</ViewItem>
                              </URLInfo>
                            </ShoppingCartItem>';
        }       
        return '<Items>' . $items_string . '</Items>';
      }

      if ($cart->count_contents() > 0) {
        $items_string = '';
        $products = $cart->get_products();
        for ($i=0, $n=sizeof($products); $i<$n; $i++) {
          $MIC_modifier = '';
          $attributes_string = '';
          if ($products[$i]['attributes']) {
            reset($products[$i]['attributes']);
            while (list($option, $value) = each($products[$i]['attributes'])) {
              if ( !is_array($value) ) {
                $attributes_query = tep_db_query("select op.options_id, ot.products_options_name, o.options_type, ov.products_options_values_name, op.options_values_price, op.price_prefix
                                                  from " . TABLE_PRODUCTS_ATTRIBUTES . " op,
                                                       " . TABLE_PRODUCTS_OPTIONS . " o,
                                                       " . TABLE_PRODUCTS_OPTIONS_TEXT . " ot,
                                                       " . TABLE_PRODUCTS_OPTIONS_VALUES . " ov
                                                  where op.products_id = '" . tep_get_prid($products[$i]['id']) . "'
                                                    and op.options_id = '" . $option . "'
                                                    and o.products_options_id = '" . $option . "'
                                                    and ot.products_options_text_id = '" . $option . "'
                                                    and op.options_values_id = '" . $value . "'
                                                    and ov.products_options_values_id = '" . $value . "'
                                                    and ov.language_id = '" . (int)$languages_id . "'
                                                    and ot.language_id = '" . (int)$languages_id . "'
                                                 ");
                $attributes = tep_db_fetch_array($attributes_query);
                $attributes_string .= $attributes['products_options_name'] . ': ' . $attributes['products_options_values_name'] . ' (' . $attributes['price_prefix'] . $currencies->format($attributes['options_values_price']) . ');';
                $MIC_modifier .= $attributes['products_options_values_name'];
              } elseif ( isset($value['c'] ) ) {
                foreach ($value['c'] as $v) {
                  $attributes_query = tep_db_query("select op.options_id, ot.products_options_name, o.options_type, ov.products_options_values_name, op.options_values_price, op.price_prefix
                                                    from " . TABLE_PRODUCTS_ATTRIBUTES . " op,
                                                       " . TABLE_PRODUCTS_OPTIONS . " o,
                                                       " . TABLE_PRODUCTS_OPTIONS_TEXT . " ot,
                                                       " . TABLE_PRODUCTS_OPTIONS_VALUES . " ov
                                                    where op.products_id = '" . tep_get_prid($products[$i]['id']) . "'
                                                      and op.options_id = '" . $option . "'
                                                      and o.products_options_id = '" . $option . "'
                                                      and ot.products_options_text_id = '" . $option . "'
                                                      and op.options_values_id = '" . $v . "'
                                                      and ov.products_options_values_id = '" . $v . "'
                                                      and ov.language_id = '" . (int)$languages_id . "'
                                                      and ot.language_id = '" . (int)$languages_id . "'
                                                   ");
                  $attributes = tep_db_fetch_array($attributes_query);
                  $attributes_string .= $attributes['products_options_name'] . ': ' . $attributes['products_options_values_name'] . ' (' . $attributes['price_prefix'] . $currencies->format($attributes['options_values_price']) . ');';
                  $MIC_modifier .= $attributes['products_options_values_name'];
                }
              } elseif ( isset($value['t'] ) ) {
                $attributes_query = tep_db_query("select op.options_id, ot.products_options_name, o.options_type, op.options_values_price, op.price_prefix
                                                  from " . TABLE_PRODUCTS_ATTRIBUTES . " op,
                                                       " . TABLE_PRODUCTS_OPTIONS . " o,
                                                       " . TABLE_PRODUCTS_OPTIONS_TEXT . " ot
                                                  where op.products_id = '" . tep_get_prid($products[$i]['id']) . "'
                                                    and op.options_id = '" . $option . "'
                                                    and o.products_options_id = '" . $option . "'
                                                    and ot.products_options_text_id = '" . $option . "'
                                                    and ot.language_id = '" . (int)$languages_id . "'
                                                 ");
                $attributes = tep_db_fetch_array($attributes_query);
                $attributes_string .= $attributes['products_options_name'] . ': ' . $value['t'] . ' (' . $attributes['price_prefix'] . $currencies->format($attributes['options_values_price']) . ');';
                $MIC_modifier .= $value['t'];
              }
            }
          }
          $check_unique = tep_db_fetch_array(tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where products_model = '" . tep_db_input($products[$i]['model']) . "' group by products_model"));
          $items_string .= '<ShoppingCartItem>
                              <UserToken>' . $this->str_encode(MODULE_ADDONS_BUYSAFE_STORE_AUTHENTICATION_DATA) . '</UserToken>
                              <MarketplaceItemCode>' . $this->str_encode((tep_not_null($products[$i]['model']) && $check_unique['total'] == 1) ? $products[$i]['model'] . $MIC_modifier : $products[$i]['name'] . $MIC_modifier) . '</MarketplaceItemCode>
                              <StockKeepingUnit>' . $this->str_encode($products[$i]['model']) . '</StockKeepingUnit>
                              <Title>' . $this->str_encode($products[$i]['name']) . '</Title>
                              <Attributes>' . $this->str_encode($attributes_string) . '</Attributes>
                              <isRestricted>false</isRestricted>
                              <QuantityPurchased>' . $this->str_encode($products[$i]['quantity']) . '</QuantityPurchased>
                              <PriceInfo>
                                <FinalPrice>
                                  <CurrencyCode>' . $this->str_encode($currency) . '</CurrencyCode>
                                  <Value>' . number_format(tep_round($products[$i]['price'] + $cart->attributes_price($products[$i]['id']), 2), 2, '.', '') . '</Value>
                                </FinalPrice>
                              </PriceInfo>
                              <URLInfo>
                                <ViewItem>' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id'], 'NONSSL', false) . '</ViewItem>
                              </URLInfo>
                            </ShoppingCartItem>';
        }

        return '<Items>' . $items_string . '</Items>';
      } else return '';
    }

    function str_encode($value) {
      return utf8_encode(htmlspecialchars($value)); // if charset ISO-8859-1 only, use utf8_encode() function
      //return iconv((CHARSET, 'UTF-8', htmlspecialchars($value)); // if not, use iconv() function
    }

    function validate_country_code($country_code) {
      if (in_array($country_code, array('US', 'AX', 'CA', 'DZ', 'AS', 'AD', 'AO', 'AI', 'AQ', 'AG', 'AR', 'AM', 'AW', 'AU', 'AT', 'AZ', 'BS', 'BH', 'BD', 'BB', 'BY', 'BE', 'BZ', 'BJ', 'BM', 'BT', 'BO', 'BA', 'BW', 'BV', 'BR', 'IO', 'BN', 'BG', 'BF', 'BI', 'KH', 'CM', 'AL', 'CV', 'KY', 'CF', 'TD', 'CL', 'CN', 'CX', 'CC', 'CO', 'KM', 'CG', 'CD', 'CK', 'CR', 'CI', 'HR', 'CU', 'CY', 'CZ', 'DK', 'DJ', 'DM', 'DO', 'EC', 'EG', 'SV', 'GQ', 'ER', 'EE', 'ET', 'FK', 'FO', 'FJ', 'FI', 'FR', 'GF', 'PF', 'TF', 'GA', 'GM', 'GE', 'DE', 'GH', 'GI', 'GR', 'GL', 'GD', 'GP', 'GT', 'GG', 'GN', 'GW', 'GY', 'HT', 'HM', 'VA', 'HN', 'HK', 'HU', 'IS', 'IN', 'ID', 'IR', 'IQ', 'IE', 'IL', 'IT', 'JM', 'JP', 'JO', 'KZ', 'KE', 'KI', 'KP', 'KR', 'KW', 'KG', 'LA', 'LV', 'LB', 'LS', 'LR', 'LY', 'LI', 'LT', 'LU', 'MO', 'MK', 'MG', 'MW', 'MY', 'MV', 'ML', 'MT', 'MH', 'MQ', 'MR', 'MU', 'YT', 'MX', 'FM', 'MD', 'MC', 'MN', 'MS', 'MA', 'MZ', 'MM', 'NA', 'NR', 'NP', 'NL', 'AN', 'NC', 'NZ', 'NI', 'NE', 'NG', 'NU', 'NF', 'MP', 'NO', 'OM', 'PK', 'PW', 'PS', 'PA', 'PG', 'PY', 'PE', 'PH', 'PN', 'PL', 'PT', 'PR', 'QA', 'RE', 'RO', 'RU', 'RW', 'SH', 'KN', 'LC', 'PM', 'VC', 'WS', 'SM', 'ST', 'SA', 'SN', 'RS', 'SC', 'SL', 'SG', 'SK', 'SI', 'SB', 'SO', 'ZA', 'GS', 'ES', 'LK', 'SD', 'SR', 'SJ', 'SZ', 'SE', 'CH', 'SY', 'TW', 'TJ', 'TZ', 'TH', 'TL', 'TG', 'TK', 'TO', 'TT', 'TN', 'TR', 'TM', 'TC', 'TV', 'UG', 'UA', 'AE', 'GB', 'AF', 'UM', 'UY', 'UZ', 'VU', 'VE', 'VN', 'VG', 'VI', 'WF', 'EH', 'YE', 'ZM', 'OT')))
      {
        return $country_code;
      } else {
        return 'OT';
      }
    }

    function make_debug_info($soapclient, $method, $result) {
      $headers = $soapclient->getHeaders();
      $headers_array = $this->decodeXML($headers);
      $TransactionId = isset($headers_array['TransactionId']) ? $headers_array['TransactionId'] : $result['detail']['TransactionStatus']['TransactionId'];
      $isSuccessful = isset($headers_array['isSuccessful']) ? $headers_array['isSuccessful'] : $result['detail']['TransactionStatus']['isSuccessful'];
      $isFatal = isset($result['isFatal']) ? $result['isFatal'] : '';
      $IsBuySafeEnabled = isset($result['IsBuySafeEnabled']) ? $result['IsBuySafeEnabled'] : ''; 
      $TotalBondCost = isset($result['TotalBondCost']) ? $result['TotalBondCost'] : ''; 
      $this->debug_info = <<<TEXT_DEBUG_LABEL
<!-- buySAFE Debug Info
    Request
        Action: $method
    Result
        TransactionId: $TransactionId
        isSuccessful: $isSuccessful
        isFatal: $isFatal
        IsBuySafeEnabled: $IsBuySafeEnabled
        TotalBondCost: $TotalBondCost
-->
TEXT_DEBUG_LABEL;
if (defined('MODULE_ADDONS_BUYSAFE_DEBUG') && MODULE_ADDONS_BUYSAFE_DEBUG == 'True') { 
  echo '<h2>Request</h2><pre>' . htmlspecialchars($soapclient->request, ENT_QUOTES) . '</pre>';
  echo '<h2>Response</h2><pre>' . htmlspecialchars($soapclient->response, ENT_QUOTES) . '</pre>';
}
    }

    function get_debug_info() {
      return $this->debug_info;
    }

    function decodeXML($xmlstg) {
      preg_match_all ("(<([a-z0-9]+)>([^<>]*?)</[a-z0-9]+>)i", $xmlstg, $out, PREG_SET_ORDER);
      $n = 0;
      $retarr = '';
      while (isset($out[$n]))
      {
        $retarr[$out[$n][1]] = strip_tags($out[$n][2]);
        $n++; 
      }
      return $retarr;
    }
  }
?>