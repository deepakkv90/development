<?php
/*
  $Id: shipwire_pro.php,v 1.1.0.0 2008/06/20 23:41:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class shipwire_pro { 
    var $code, $title, $descrption, $enabled, $sort_order;

    //  class constructor
    function shipwire_pro() {
      global $order, $cart;

      $this->code = 'shipwire_pro';
      $this->title = defined('MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_TITLE') ? MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_TITLE : '';
      $this->description = defined('MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_DESCRIPTION') ? MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_DESCRIPTION : '';
      $this->enabled = (defined('MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_STATUS') && MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_STATUS == 'True') ? true : false;
      $this->sort_order = defined('MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_SORT_ORDER') ? MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_SORT_ORDER : '';

      $this->output = array();
    }
    
    // class methods
    function process() {
      global $customer_id, $customer_email, $full_name, $items;

     // if (!$this->enabled) { return; }
      $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where customers_id = '" . (int)$customer_id . "' order by date_purchased desc limit 1");
      $orders = tep_db_fetch_array($orders_query);
      $shipw = $this->shipwire_process($orders['orders_id']);
      if ($items == '') { return; }    
      // update orders->comments
      $orders_status_query = tep_db_query("select comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . (int)$orders['orders_id'] . "'");
      $orders_status = tep_db_fetch_array($orders_status_query);
      if (tep_not_null($orders_status)){
        if($orders_status['comments'] == ''){       
          $new_comments = TEXT_SHIPWIRE_TRANSACTION_ID  . ' ' . $shipw['transaction_id'];
        }else{       
          $new_comments = trim($orders_status['comments']) . "\n" . TEXT_SHIPWIRE_TRANSACTION_ID  . ' ' . $shipw['transaction_id'];
        }
        tep_db_query("update " . TABLE_ORDERS_STATUS_HISTORY . " set comments = '" . trim($new_comments) . "' where orders_id = '" . (int)$orders['orders_id'] . "'");
      }
      // update orders->comments //eof
      $output_text = '';
      if (defined('MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_BANNER') && MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_BANNER == 'True') {
        $output_text .= '<DIV align="center"><table BORDER="0" CELLPADDING="0" CELLSPACING="0" WIDTH="100%"><tr><td align="center" class="main"><a target="_blank" href="'.  MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_AFFILIATE_URL .'">' . tep_image(DIR_WS_IMAGES . 'shipwire_banner.gif') . '</a></td></tr><tr><td align="center" class="main">' . TEXT_SHIPWIRE_PRO_TRANSACTION_ID. '<b>' . $shipw['transaction_id'] . '</b></td></tr></table></DIV>';
      }
      $this->output[] = array('text' => $output_text);

      $email_subject = "Your Shipwire Transaction ID for Order # " . $orders['orders_id'];
      $email_order =  "\n" . 'Your Shipwire Transaction ID for Order ' . $orders['orders_id'] . ' is ' . $shipw['transaction_id'] . "\n";     
      tep_mail($full_name, $customer_email, $email_subject, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);                  
    }

    function shipwire_process($order_id) {
      global $order, $customer_id, $customer_email, $full_name, $items;

      // check to see if we are running in debug mode
      if (defined('MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_DEBUG') && MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_DEBUG == 'True') {
        // open the file and write the starting information
        $filename = DIR_FS_CATALOG . 'debug/shipwire_debug.txt';
        $fp = fopen($filename, "a");
        $data = 'Shipwire module entered at ' . microtime() . "\n";
        $write = fputs($fp, $data);
      }
      
      // replace the "email" & "passwd" String values with your Shipwire email and password
      $email = defined('MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_EMAIL') ? MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_EMAIL : '';
      $passwd = defined('MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_PASSWORD') ? MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_PASSWORD : '';
      $server = defined('MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_MODE') ? MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_MODE : '';
      $warehouse = "00"; // Leave "00" if you want Shipwire to determine the warehouse
      $items = '';
      $send='0';

      $products_array = array();
      $products_query = tep_db_query("SELECT products_id, products_name, products_model, products_quantity, final_price, products_tax 
                                        from " . TABLE_ORDERS_PRODUCTS . " 
                                      WHERE orders_id = '" . (int)$order_id . "' 
                                      ORDER BY products_name");
      while ($products = tep_db_fetch_array($products_query)) {
        $products_array[] = array('id' => $products['products_id'],
                                  'name' => $products['products_name'],
                                  'model' => $products['products_model'],
                                  'qty' => $products['products_quantity'],
                                  'final_price' => $products['final_price'],
                                  'tax' => $products['products_tax'],
                                 );
      }
      if (sizeof($products_array) > 0) $send = '1';
      for ($i=0; $i < sizeof($products_array); $i++) {
        if (defined('MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_FILTER_PRODUCTS') && MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_FILTER_PRODUCTS == 'True') {
          $filter_query = tep_db_query("select products_quantity, manufacturers_id from " . TABLE_PRODUCTS . " where products_id = '" . $products_array[$i]['id'] . "'");
          $filter  = tep_db_fetch_array($filter_query);
          $mfg_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $filter['manufacturers_id'] . "'");
          $mfg  = tep_db_fetch_array($mfg_query);
          // manufacturer filter
          if( (!eregi('No Filter', MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_MFG_FILTER)) && ($mfg['manufacturers_name'] != '')){
            if(eregi($mfg['manufacturers_name'], MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_MFG_FILTER)){
              $send='1';
            }
          } else {   // no mfg filter set
            $send='0';
          }
          //model filter + qty filter
          if (MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_MODEL_FILTER != '') {
            if( ($products_array[$i]['model'] != '') && (eregi(MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_MODEL_FILTER, $products_array[$i]['model'])) ){
              if (MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_QTY_FILTER !='0'){
                if(MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_QTY_FILTER == '1') {
                  if($filter['products_quantity'] >= 1) { $send = 1; }
                }
                if(MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_QTY_FILTER == '2') {
                  if($filter['products_quantity'] <= 0) { $send = 1; }
                }
              }else{
                $send='1';
              }   
            } else {
              $send='0';
            }
          }
          if(MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_MODEL_FILTER == ''){
            // qty filter 
            if(MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_QTY_FILTER != '0'){
              if(MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_QTY_FILTER == '1') {
                ((int)$filter['products_quantity'] >= 1) ? $send = 1 : $send = '0';
              }
              if(MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_QTY_FILTER == '2') {
                ((int)$filter['products_quantity'] <= 0) ? $send = 1 : $send = '0';
              }
            }
          }
        }  // end filter products = true
        if ($send == '1'){
          $items .= '<Item num="' . $i . '">';
          $items .= '<Id>' . $products_array[$i]['id'] . '</Id>';
          $items .= '<Code>' . html_entity_decode($products_array[$i]['model'], ENT_QUOTES) . '</Code>';
          $items .= '<Quantity>' . $products_array[$i]['qty'] . '</Quantity>';
          $items .= '<Unit-Price>' . $products_array[$i]['final_price'] . '</Unit-Price>';
          $items .= '<Description>' . html_entity_decode($products_array[$i]['name'], ENT_QUOTES) . '</Description>';
          $items .= '<Taxable>' . ($products_array[$i]['tax'] != '' ? 'YES' : 'NO') . '</Taxable>';
          $items .= '<Url>' . HTTP_SERVER . '/product_info.php?products_id=' . $products_array[$i]['id'] . '</Url>';
          $items .= '</Item>';
        }
     // $send = '0';
      } // end products loop    
      if ($items == '') return false;
      $orders_query = tep_db_query("select * from " . TABLE_ORDERS . " WHERE customers_id = '" . (int)$customer_id . "' AND orders_id = '" . (int)$order_id . "'");
      $orders = tep_db_fetch_array($orders_query);

      $order_shipping_method_query = tep_db_query("select title from " . TABLE_ORDERS_TOTAL . " WHERE orders_id = '" . (int)$order_id . "' AND class = 'ot_shipping'");
      $order_shipping_method = tep_db_fetch_array($order_shipping_method_query);

      $order_totals_query = tep_db_query("select value from " . TABLE_ORDERS_TOTAL . " WHERE orders_id = '" . (int)$order_id . "' AND class = 'ot_total'");
      $order_totals = tep_db_fetch_array($order_totals_query);

      $country = ($orders['delivery_country'] == '') ? $orders['billing_country'] : $orders['delivery_country'];
      $countries_query = tep_db_query("select countries_iso_code_2 from " . TABLE_COUNTRIES . " WHERE countries_name = '" . $country . "'");
      $countries = tep_db_fetch_array($countries_query);
      $order_country = $countries['countries_iso_code_2'];

      $customer_email = $orders['customers_email_address'];
      $full_name = ($orders['delivery_name'] == '') ? $orders['billing_name'] : $orders['delivery_name'];

      $OrderList = '<OrderList StoreAccountName="CRELOADED">' .
        '<EmailAddress>' . $email . '</EmailAddress>' .
        '<Password>' . $passwd . '</Password>' .
        '<Server>'. $server .'</Server>' .
        '<Order id="' . $order_id . '">' .
        '<Time>' . date("D M j G:i:s Y") . ' GMT</Time>' .
        '<NumericTime>' . time() . '</NumericTime>' .
        '<Referer>CRELOADED</Referer>' .
        '<Warehouse>' . $warehouse . '</Warehouse>';
        if ($orders['delivery_street_address'] != '') {
          $OrderList .= '<AddressInfo type="ship">' .
            '<Name>' .
              '<Full>' . html_entity_decode($orders['delivery_name'], ENT_QUOTES) . '</Full>' .
            '</Name>' .
            '<Address1>' . html_entity_decode($orders['delivery_street_address'], ENT_QUOTES) . '</Address1>' .
            '<Address2>' . html_entity_decode($orders['delivery_suburb'], ENT_QUOTES) . '</Address2>' .
            '<City>' . $orders['delivery_city'] . '</City>' .
            '<State>' . $orders['delivery_state'] . '</State>' .
            '<Country>' . $order_country . '</Country>' .
            '<Zip>' . $orders['delivery_postcode'] . '</Zip>' .
            '<Phone>' . $orders['customers_telephone'] . '</Phone>' .
          '</AddressInfo>';
        } else {
          $OrderList .= '<AddressInfo type="ship">' .
            '<Name>' .
              '<Full>' . html_entity_decode($orders['billing_name'], ENT_QUOTES) . '</Full>' .
            '</Name>' .
            '<Address1>' . html_entity_decode($orders['billing_street_address'], ENT_QUOTES) . '</Address1>' .
            '<Address2>' . html_entity_decode($orders['billing_suburb'], ENT_QUOTES) . '</Address2>' .
            '<City>' . $orders['billing_city'] . '</City>' .
            '<State>' . $orders['billing_state'] . '</State>' .
            '<Country>' . $order_country . '</Country>' .
            '<Zip>' . $orders['billing_postcode'] . '</Zip>' .
            '<Phone>' . $orders['customers_telephone'] . '</Phone>' .
          '</AddressInfo>';
        }
        $OrderList .= '<AddressInfo type="bill">' .
          '<Name>' .
            '<Full>' . html_entity_decode($orders['billing_name'], ENT_QUOTES) . '</Full>' .
          '</Name>' .
          '<Address1>' . html_entity_decode($orders['billing_street_address'], ENT_QUOTES) . '</Address1>' .
          '<Address2>' . html_entity_decode($orders['billing_suburb'], ENT_QUOTES) . '</Address2>' .
          '<City>' . $orders['billing_city'] . '</City>' .
          '<State>' . $orders['billing_state'] . '</State>' .
          '<Country>' . $order_country . '</Country>' .
          '<Zip>' . $orders['billing_postcode'] . '</Zip>' .
          '<Phone>' . $orders['customers_telephone'] . '</Phone>' .
          '<Email>' . $orders['customers_email_address'] . '</Email>' .
        '</AddressInfo>' .
        '<Shipping>' . $order_shipping_method['title'] . '</Shipping>' .
        '<CreditCard type="' . $orders['cc_type'] . '" expiration="' . $orders['cc_expires'] . '"></CreditCard>' .
        $items . 
        '<Total>' .
          '<Line name="Total">' . $order_totals['value'] . '</Line>' .
        '</Total>' .
        '</Order>' .
      '</OrderList>';

      // check to see if we are running in debug mode
      if (MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_DEBUG == 'True') {
        $data = 'Before encoding : ' . "\n";
        $write = fputs($fp, $data);
        $data = $OrderList . "\n";
        $write = fputs($fp, $data);
      }
      
      //Convert characters to proper format for post
      $OrderList = urlencode($OrderList);

      // check to see if we are running in debug mode
      if (MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_DEBUG == 'True') {
        $data = 'The data to transmitted : ' . "\n";
        $write = fputs($fp, $data);
        $data = $OrderList . "\n";
        $write = fputs($fp, $data);
      }
      
      // open synchronous connection to Shipwire servlet    
      // NOTE:  you must have the cURL libraries installed with PHP on your server--
      // If you need them, see your System Administrator, who can get then at 
      // http://curl.haxx.se/download.html

      // in debug mode record the time
      if (MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_DEBUG == 'True') {
        $data = 'Setting up the curl connection at ' . microtime() . "\n";
        $write = fputs($fp, $data);
      }
      if (defined('MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_MODE') && MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_MODE == 'Test') {
        $api_url = 'https://www.shipwire.com/exec/FulfillmentServices.test.php';
      } else {
        $api_url = 'https://www.shipwire.com/exec/FulfillmentServices.php';
      }
      
      $urlConn = curl_init ($api_url);
      curl_setopt($urlConn, CURLOPT_POST, 1);
      curl_setopt($urlConn, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($urlConn, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($urlConn, CURLOPT_HTTPHEADER, array("Content-type", "application/x-www-form-urlencoded"));
      curl_setopt($urlConn, CURLOPT_POSTFIELDS, "OrderListXML=" . $OrderList);
      
      // in debug mode record the time
      if (MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_DEBUG == 'True') {
        $data = 'Starting transmission at ' . microtime() . "\n";
        $write = fputs($fp, $data);
      }
     // added support for curl proxy
      if (defined('CURL_PROXY_HOST') && defined('CURL_PROXY_PORT') && CURL_PROXY_HOST != '' && CURL_PROXY_PORT != '') {
        curl_setopt($urlConn, CURLOPT_HTTPPROXYTUNNEL, TRUE);
        curl_setopt($urlConn, CURLOPT_PROXY, CURL_PROXY_HOST . ":" . CURL_PROXY_PORT);
        curl_setopt($urlConn, CURLOPT_SSL_VERIFYPEER, FALSE);
      }
      if (defined('CURL_PROXY_USER') && defined('CURL_PROXY_PASSWORD') && CURL_PROXY_USER != '' && CURL_PROXY_PASSWORD != '') {
        curl_setopt($urlConn, CURLOPT_PROXYUSERPWD, CURL_PROXY_USER . ':' . CURL_PROXY_PASSWORD);
      }
      ob_start();
      curl_exec($urlConn);
      $orderSubmitted = ob_get_contents();
      ob_end_clean();
      
      // in debug mode record the time
      if (MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_DEBUG == 'True') {
        $data = 'Transmission completed at ' . microtime() . "\n";
        $write = fputs($fp, $data);
      }
  
      // Parse the response
      $parser= xml_parser_create(); 
      xml_parse_into_struct($parser,$orderSubmitted,$XMLvals,$XMLindex); 
      xml_parser_free($parser);

      $error_data_response = '';      
      $error_data_response = $this->get_error($XMLvals);
      $total_order_response = $this->get_total_order($XMLvals);
      $transaction_id_response = $this->get_transaction_id($XMLvals);
      
      $ret = array('error' => $error_data,
                   'total_order' => $total_order_response,
                   'transaction_id' => $transaction_id_response);     
      
      // if we are in debug mode, close it up
      if (MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_DEBUG == 'True') {
        $data = 'Error Response : ' . "\n" . $error_data_response . "\n";
        $write = fputs($fp, $data);
        $data = 'Total Order Response : ' . "\n" . $total_order_response . "\n";
        $write = fputs($fp, $data);
        $data = 'Transmission ID : ' . "\n" . $transaction_id_response . "\n";
        $write = fputs($fp, $data);
        $data = "\n\n\n\n";
        $write = fputs($fp, $data);
        fclose($fp);
      }
      return $ret;
    }

    // This function will return an array of the values of an element 
    // given the $vals and $index arrays, and the element name
    function getElementValue($XMLvals, $elName) { 
      $elValue = null;
      foreach ($XMLvals as $arrkey => $arrvalue) {
        foreach ($arrvalue as $key => $value) {
          if ($value==strtoupper($elName)){
            $elValue[] = $arrvalue['value'];
          }
        }
      }
      return $elValue;
    }
    
    function get_error($XMLvals) {
      $errorMessage = $this->getElementValue($XMLvals,"ErrorMessage");
      $ret_value = '';
      if (isset($errorMessage)) {
        foreach ($errorMessage as $key => $err) {
          $ret_value .= $err;
        }
      }
      return $ret_value;
    }
    
    function get_transaction_id($XMLvals) {
      $transactionId = $this->getElementValue($XMLvals,"TransactionId");
      $ret_value = '';
      if (isset($transactionId)) {
        foreach ($transactionId as $key => $tra) {
          $ret_value .= $tra;
        }
      }else{ 
          $ret_value .= 'error';
      }
      return $ret_value;
    }
    
    function get_total_order($XMLvals) {
      $totalOrders = $this->getElementValue($XMLvals,"TotalOrders");
      $ret_value = '';
      if (isset($totalOrders)) {
        foreach ($totalOrders as $key => $tot) {
          $ret_value .= $tot;
        }
      }else{
        $ret_value = 'error';
      }
      return $ret_value;
    }
    
    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      $mfg= " ''No Filter'',";
      $manufacturers_query_raw = "select manufacturers_id, manufacturers_name, manufacturers_image, date_added, last_modified from " . TABLE_MANUFACTURERS . " order by manufacturers_name";
      $manufacturers_query = tep_db_query($manufacturers_query_raw);
      while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
        $mfg .= "''" . $manufacturers['manufacturers_name'] . "'',";
      }
      $mfg = substr($mfg,0,-1);
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Shipwire Pro', 'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_STATUS', 'True', 'Do you want to enable Shipwire Pro?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Shipwire Banner', 'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_BANNER', 'True', 'Display Shipwire Banner and Shipwire Fullfillment ID on successful checkout?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Shipwire Mode', 'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_MODE', 'Test', 'Select which mode the Shipwire module will run.', '6', '1', 'tep_cfg_select_option(array(\'Test\', \'Production\'), ', now())");      
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Email Address', 'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_EMAIL', '', 'Email Address', '6', '5', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Shipwire Password', 'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_PASSWORD', '', 'Case-sensitive password.', '6', '6', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Products Filter', 'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_FILTER_PRODUCTS', 'False', 'Enable filtering of products sent to Shipwire API?  Filters are ADDITIVE.  Each condition you set must match for the product to get sent to Shipwire API.', '6', '7',  'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Model Filter', 'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_MODEL_FILTER', '_SHIPWIRE', 'Enter the text used in the products model field for filtering Shipwire products.  Leave blank to disable this filter.', '6', '8', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Manufacturer Filter', 'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_MFG_FILTER', 'No Filter', 'Select the Manufacturers used for filtering Shipwire products.<br><br>NOTE: All products for a selected Manufacturer will be sent to the Shipwire API regardless only if Products Filter is enabled AND will be filtered with other filters.', '6', '9',  'tep_cfg_select_multioption(array($mfg),', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Quantity Filter', 'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_QTY_FILTER', '0', 'This is advanced and should be carefully considered when using with Stock Decrement option enabled.<br><br>0=No Filter<br>1=(Qty >= 1)<br>2=(Qty <= 0)', '6', '10', now())");  
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Debug', 'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_DEBUG', 'False', 'Set to True to capture the information being transmitted.', '6', '11', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_SORT_ORDER', '0', 'Sort order of display.', '6', '12', now())");     
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (`configuration_key`, `configuration_value`) values ('MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_AFFILIATE_URL', 'https://www.shipwire.com/exec/creloaded.php?ref=6133361')");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (`configuration_key`, `configuration_value`) values ('MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_VERSION', '1.1')");
    }

    function remove() {
      $keys = '';
      $keys_array = $this->keys();
      for ($i=0; $i < sizeof($keys_array); $i++) {
        $keys .= "'" . $keys_array[$i] . "',";
      }
      $keys = substr($keys, 0, -1);
      $hidden_keys = '';
      $hidden_keys_array = $this->hidden_keys();
      for ($i=0; $i < sizeof($hidden_keys_array); $i++) {
        $hidden_keys .= "'" . $hidden_keys_array[$i] . "',";
      }
      $hidden_keys = substr($hidden_keys, 0, -1);
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in (" . $keys . ")");
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in (" . $hidden_keys . ")");
    }
    
    function hidden_keys() {
      return array('MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_AFFILIATE_URL',
                   'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_VERSION');
    }    

    function keys() {
		    return array('MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_STATUS',
                   'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_BANNER',
                   'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_MODE',
                   'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_EMAIL',
                   'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_PASSWORD',
                   'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_FILTER_PRODUCTS',
                   'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_MODEL_FILTER',
                   'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_MFG_FILTER',
                   'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_QTY_FILTER',
                   'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_DEBUG',
                   'MODULE_CHECKOUT_SUCCESS_SHIPWIRE_PRO_SORT_ORDER');
   }
 }
?>