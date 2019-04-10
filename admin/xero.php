<?php
///////////////////// XERO API TESTING START ////////////////////

require 'xero/XeroOAuth.php';

require_once('xero/_config.php');

require_once(DIR_WS_CLASSES . 'order.php');

$order = new order($oID);

//print_r($order);

$oauthObject = new OAuthSimple();
                     
$xro_settings = $xro_private_defaults;

// Example Xero API PUT:
    
	$oauthObject->reset();
    $result = $oauthObject->sign(array(
        'path'      => $xro_settings['xero_url'].'/Invoices/',
        'action'	=> 'PUT',
        'parameters'=> array(
			'oauth_signature_method' => $xro_settings['signature_method']),
        'signatures'=> $signatures));
        
    $cname = $order->customer['company'];
	if($cname=="") {
		$cname = $order->customer['name'];
	}
	
	$invoice_date = tep_get_invoice_date($oID);
	if(empty($invoice_date)) { $invoice_date = date("Y-m-d H:i:s"); }
	
	$xml = '<Invoices>  
      <Invoice>  
        <Type>ACCREC</Type>  
        <Contact>  
          <ContactNumber>'.$order->customer['id'].'</ContactNumber>  
          <ContactStatus>ACTIVE</ContactStatus>';
		  
		  $nm = htmlentities($cname);
		  $em = $order->customer['email_address'];
		  
		  if($order->customer['submit_accountant_email_to_xero']) {
			  if(!empty($order->customer['accountant_name'])) {
				$nm= htmlentities($order->customer['accountant_name']);
			  } 
			  
			  if(!empty($order->customer['accountant_email'])) {
				$em = $order->customer['accountant_email'];
			  } 
		  }	 
		  
		  $xml .= '<Name>'.$nm.'</Name>';
		  $xml .= '<EmailAddress>'.$em.'</EmailAddress>';
		  
          $xml .= '<AccountsReceivableTaxType>OUTPUT</AccountsReceivableTaxType>  
          <AccountsPayableTaxType>INPUT</AccountsPayableTaxType>            
          <DefaultCurrency>AUD</DefaultCurrency>  
          <Addresses>  
            <Address>  
              <AddressType>POBOX</AddressType>  
              <AttentionTo>'.htmlentities($order->customer['name']).'</AttentionTo>  
              <AddressLine1>'.htmlentities($order->customer['street_address']).'</AddressLine1>  
              <AddressLine2>'.htmlentities($order->customer['suburb']).'</AddressLine2>                
              <City>'.$order->customer['city'].'</City>  
              <Region>'.$order->customer['state'].'</Region>  
              <PostalCode>'.$order->customer['postcode'].'</PostalCode>  
              <Country>'.$order->customer['country'].'</Country>  
            </Address>              
          </Addresses>  
          <Phones>              
            <Phone>  
              <PhoneType>MOBILE</PhoneType>  
              <PhoneNumber>'.$order->customer['telephone'].'</PhoneNumber>                
            </Phone>  
          </Phones>  
        </Contact>  
        <Date>'.date("Y-m-d", strtotime($invoice_date)).'T00:00:00</Date>  
        <DueDate>'.date("Y-m-d", strtotime($order->info['due_date'])).'T00:00:00</DueDate>  
        <InvoiceNumber>'.$oID.'</InvoiceNumber>            
        <CurrencyCode>AUD</CurrencyCode>  
        <Status>AUTHORISED</Status>  
        <LineAmountTypes>Exclusive</LineAmountTypes>';
		
		if(!empty($order->info['purchase_number'])) {
			$xml .= '<Reference>'.$order->info['purchase_number'].'</Reference>';
		}
		
		$xml .='<LineItems>';
			$total_line_amount = 0.00; $total_line_amount_tax = 0.00;
			for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
				
				$line_amount_tax = tep_round((($order->products[$i]['qty'] * $order->products[$i]['final_price'])/100)*$order->products[$i]['tax'],4);
				$line_amount = ($order->products[$i]['final_price'] * $order->products[$i]['qty']);
				
				$model_arr = explode("-",$order->products[$i]['model']);
				if($model_arr[0]=="") { $model_arr[0] = "0000"; }
				
				$xml .= '<LineItem>  
							<ItemCode>'.$model_arr[0].'</ItemCode>
							<Description>'.htmlentities($order->products[$i]['name']).'</Description>  
							<Quantity>'.$order->products[$i]['qty'].'</Quantity>  
							<UnitAmount>'.$order->products[$i]['final_price'].'</UnitAmount>  
							<TaxType>OUTPUT</TaxType>  
							<TaxAmount>'.$line_amount_tax.'</TaxAmount>  
							<LineAmount>'.$line_amount.'</LineAmount>  
							<AccountCode>200</AccountCode>  
							<Tracking />  
						 </LineItem>  ';
				$total_line_amount += $line_amount;
				$total_line_amount_tax += $line_amount_tax;		 
			}
		$shipping_cost_xero_tax = tep_round((($order->info['shipping_cost'] / 100 ) * $order->products[0]['tax']),2);
		$shipping_cost_xero = $order->info['shipping_cost'];
		
		$xml .= '<LineItem>  
					<ItemCode>Shipping</ItemCode>
					<Description>'.htmlentities($order->info['shipping_method']).'</Description>															
					<Quantity>1</Quantity>  
					<UnitAmount>'.$order->info['shipping_cost'].'</UnitAmount>
					<LineAmount>'.$shipping_cost_xero.'</LineAmount> 
					<TaxAmount>'.$shipping_cost_xero_tax.'</TaxAmount> 					
					<AccountCode>200</AccountCode>  					
				 </LineItem>';
		//discount calculation
		
		$discount_xero = 0.00; $order_total_xero = 0.00; $dt = 0.00;
		
		for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {

		  if($order->totals[$i]['class']=="ot_coupon") {
			$dt = tep_round((($order->totals[$i]['value'] * $order->products[0]['tax'])/100),2);
 
			 $xml .= '<LineItem>  
						<ItemCode>Discount</ItemCode>
						<Description>'.htmlentities($order->totals[$i]['title']).'</Description>															
						<Quantity>1</Quantity>  
						<UnitAmount>-'.$order->totals[$i]['value'].'</UnitAmount>
						<LineAmount>-'.$order->totals[$i]['value'].'</LineAmount> 
						<TaxAmount>-'.$dt.'</TaxAmount> 					
						<AccountCode>200</AccountCode>  					
					 </LineItem>';
			  $discount_xero = $order->totals[$i]['value'];			  
		  } else if($order->totals[$i]['class']=="ot_gv") {
			  
			  $dt = tep_round((($order->totals[$i]['value'] * $order->products[0]['tax'])/100),2);
			  
	
			  $xml .= '<LineItem>  
						<ItemCode>Discount</ItemCode>
						<Description>'.htmlentities($order->totals[$i]['title']).'</Description>															
						<Quantity>1</Quantity>  
						<UnitAmount>-'.$order->totals[$i]['value'].'</UnitAmount>
						<LineAmount>-'.$order->totals[$i]['value'].'</LineAmount> 
						<TaxAmount>-'.$dt.'</TaxAmount> 					
						<AccountCode>200</AccountCode>  					
					 </LineItem>';
			  $discount_xero = $order->totals[$i]['value'];			  
		  	 
		  } else if($order->totals[$i]['class']=="ot_customer_discount") {
			  
			  $dt = tep_round((($order->totals[$i]['value'] * $order->products[0]['tax'])/100),2);
			  
	
			  $xml .= '<LineItem>  
						<ItemCode>Discount</ItemCode>
						<Description>'.htmlentities($order->totals[$i]['title']).'</Description>															
						<Quantity>1</Quantity>  
						<UnitAmount>-'.$order->totals[$i]['value'].'</UnitAmount>
						<LineAmount>-'.$order->totals[$i]['value'].'</LineAmount> 
						<TaxAmount>-'.$dt.'</TaxAmount> 					
						<AccountCode>200</AccountCode>  					
					 </LineItem>';
			  $discount_xero = $order->totals[$i]['value'];			  
		  	 
		  } elseif($order->totals[$i]['class']=="ot_total") {
			 $order_total_xero = $order->totals[$i]['value'];
		  }
		  	  
		}
		
        $xml .= '</LineItems> 
			<SubTotal>'.tep_round(($total_line_amount + $shipping_cost_xero - $discount_xero),2).'</SubTotal>  
			<TotalTax>'.tep_round(($total_line_amount_tax + $shipping_cost_xero_tax - $dt),2).'</TotalTax>  
			<Total>'.tep_round($order_total_xero,2).'</Total> 
		  </Invoice>  
		</Invoices>';
	
	//echo $xml;
	//exit;
	
	$fh  = fopen('php://memory', 'w+');
	fwrite($fh, $xml);
	rewind($fh);
	$ch = curl_init();
	curl_setopt_array($ch, $options);
	curl_setopt($ch, CURLOPT_PUT, true);
	curl_setopt($ch, CURLOPT_INFILE, $fh);
	curl_setopt($ch, CURLOPT_INFILESIZE, strlen($xml));
    curl_setopt($ch, CURLOPT_URL, $result['signed_url']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
	$r = curl_exec($ch);
	curl_close($ch);
	
	$xm = simplexml_load_string($r);
	
	
	
	
	$xero_msg = "";
	if($xm->Status=="OK") {
		
		tep_db_query("UPDATE " . TABLE_ORDERS . " set xero = '1' where orders_id = '" . (int)$oID . "'");
		$xero_msg = 'Submitted to XERO.';
		tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments, admin_users_id) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '0', '" . tep_db_input($xero_msg)  . "', '".(int)$_SESSION['login_id']."')");
		
		//For CRM
		manage_crm_order_status($oID,$status,$status_name,tep_db_input($xero_msg),0);
		
	} else {
		$xero_msg = 'Xero Error number: '. $xm->ErrorNumber."\n" . 'Xero Error message:' . $xm->Elements->DataContractBase->ValidationErrors->ValidationError->Message;
		tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments, admin_users_id) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '0', '" . tep_db_input($xero_msg)  . "', '".(int)$_SESSION['login_id']."')");	
		
		//For CRM
		manage_crm_order_status($oID,$status,$status_name,tep_db_input($xero_msg),0);
	}
	
	// update xero table to store xml request and responses
	tep_db_query("INSERT INTO xero SET orders_id='".(int)$oID."', xero_request='".$xml."', xero_response='".$r."', date_added=now()");
	
///////////////////// XERO API TESTING END //////////////////////

?>