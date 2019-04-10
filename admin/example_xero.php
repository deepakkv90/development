<?php
/**
 * An example script for the XeroOAuth class
 *
 * @author Ronan Quirke <network@xero.com>
 */
 session_start();
 ob_start();
require 'xero/XeroOAuth.php';
require_once('xero/_config.php');
$oauthObject = new OAuthSimple();
                     
$xro_settings = $xro_private_defaults;

echo $xro_settings['xero_url'].'/Invoices/';

// Example Xero API PUT:
    
	$oauthObject->reset();
    $result = $oauthObject->sign(array(
        'path'      => $xro_settings['xero_url'].'/Invoices/',
        'action'	=> 'PUT',
        'parameters'=> array(
			'oauth_signature_method' => $xro_settings['signature_method']),
        'signatures'=> $signatures));
        
    $xml = "<Invoices>  
      <Invoice>  
        <Type>ACCREC</Type>  
        <Contact>  
          <ContactNumber>8012</ContactNumber>  
          <Name>Ananth Last</Name>  
          <ContactStatus>ACTIVE</ContactStatus>  
          <EmailAddress>ananthan@indusnet.co.in</EmailAddress>                                
          <AccountsReceivableTaxType>OUTPUT</AccountsReceivableTaxType>  
          <AccountsPayableTaxType>INPUT</AccountsPayableTaxType>            
          <DefaultCurrency>AUD</DefaultCurrency>  
          <Addresses>  
            <Address>  
              <AddressType>POBOX</AddressType>  
              <AttentionTo>Ananth Last</AttentionTo>  
              <AddressLine1>170 north street</AddressLine1>  
              <AddressLine2></AddressLine2>                
              <City>Trichy</City>  
              <Region>Queensland</Region>  
              <PostalCode>621703</PostalCode>  
              <Country>Australia</Country>  
            </Address>              
          </Addresses>  
          <Phones>              
            <Phone>  
              <PhoneType>MOBILE</PhoneType>  
              <PhoneNumber>999876784</PhoneNumber>                
            </Phone>  
          </Phones>  
        </Contact>  
        <Date>2012-06-12T00:00:00</Date>  
        <DueDate>2012-06-16T00:00:00</DueDate>  
        <InvoiceNumber>5011</InvoiceNumber>            
        <CurrencyCode>AUD</CurrencyCode>  
        <Status>AUTHORISED</Status>  
        <LineAmountTypes>Exclusive</LineAmountTypes>  
         
        <LineItems><LineItem>  
							<ItemCode>127</ItemCode>
							<Description>iMedal 50mm Custom Medallion</Description>  
							<Quantity>1</Quantity>  
							<UnitAmount>19.05</UnitAmount>  
							<TaxType>OUTPUT</TaxType>  
							<TaxAmount>1.91</TaxAmount>  
							<LineAmount>19.05</LineAmount>  
							<AccountCode>200</AccountCode>  
							<Tracking />  
						 </LineItem>  <LineItem>  
					<ItemCode>Shipping</ItemCode>
					<Description>Australia Post (Excl. Tax)</Description>															
					<Quantity>1</Quantity>  
					<UnitAmount>9.08</UnitAmount>
					<LineAmount>9.08</LineAmount> 
					<TaxAmount>0.91</TaxAmount> 					
					<AccountCode>200</AccountCode>  					
				 </LineItem><LineItem>  
						<ItemCode>Discount</ItemCode>
						<Description>Discount Coupons:DiscountTestX:</Description>															
						<Quantity>1</Quantity>  
						<UnitAmount>-3.09</UnitAmount>
						<LineAmount>-3.09</LineAmount> 
						<TaxAmount>0.00</TaxAmount> 					
						<AccountCode>200</AccountCode>  					
					 </LineItem></LineItems> 
			<SubTotal>25.04</SubTotal>  
			<TotalTax>2.82</TotalTax>  
			<Total>27.85</Total> 
		  </Invoice>  
		</Invoices>";
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
	
	//echo "<br>";
	//print_r($r);
	//echo "<br>";
	
	$xm = simplexml_load_string($r);
	print_r($xm);
	echo "<br>";
	//print_r($xm->ErrorNumber);
	
	echo $xm->ErrorNumber;
	echo "<br>";
	echo $xm->Elements->DataContractBase->ValidationErrors->ValidationError->Message;
	
	echo $xm->Status;
	
	exit;
		
		//////////////////////////////////////////////////////////////////////
	
?>