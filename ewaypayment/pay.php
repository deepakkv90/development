<?php
	//include special required files from osCommerce and eWay module
	include "../includes/configure.php";
	include "../" . DIR_WS_INCLUDES . 'database_tables.php';
	include "functions.php";
	include "EwayConfig.php";
	
	
	//connect to mysql database
	databaseConnect();	
	
	//obtain eWay configuration from your osCommerce database
	$eWay_config = getEWayConfigFromDatabase();
	
	if($eWay_config["ssl_verifier"] && $_SERVER["HTTPS"] != "on"){
		?>TRANSACTION ERROR: INSECURE (solutions: make it https:// or change "SSL Verifier" from "eWay Payment" module)<?php	
		exit();
	}
	
	
	$success_oscommerce_page = "../checkout_process.php";
	$failure_oscommerce_page = "../checkout_payment.php";
	
	//in testing mode multiple by 100 will make transactions to not fail
	//$_POST['my_totalamount'] *= 100;	
	
	
	
	//live payment or hosted payment
	if($eWay_config["processing_method"] == REAL_TIME || $eWay_config["processing_method"] == REAL_TIME_CVN || $eWay_config["processing_method"] == GEO_IP_ANTI_FRAUD){
		//live payment	
		require_once('EwayPaymentLive.php');
		$eway = new EwayPaymentLive($eWay_config["customer_id"], $eWay_config["processing_method"], $eWay_config["live_gateway"]);
		
		$eway->setTransactionData("TotalAmount", $_POST['my_totalamount']); //mandatory field
		//$eway->setTransactionData("TotalAmount", 10.00); //mandatory field //For testing
		$eway->setTransactionData("CustomerFirstName", $_POST['my_firstname']);
		$eway->setTransactionData("CustomerLastName", $_POST['my_lastname']);
		$eway->setTransactionData("CustomerEmail", $_POST['my_email']);
		$eway->setTransactionData("CustomerAddress", $_POST['my_address']);
		$eway->setTransactionData("CustomerPostcode", $_POST['my_postcode']);
		$eway->setTransactionData("CustomerInvoiceDescription", $_POST['my_invoice_description']);
		$eway->setTransactionData("CustomerInvoiceRef", $_POST['my_invoice_ref']);
		$eway->setTransactionData("CardHoldersName", $_POST['my_card_name']); //mandatory field
		$eway->setTransactionData("CardNumber", $_POST['my_card_number']); //mandatory field
		$eway->setTransactionData("CardExpiryMonth", $_POST['my_card_exp_month']); //mandatory field
		$eway->setTransactionData("CardExpiryYear", $_POST['my_card_exp_year']); //mandatory field
		$eway->setTransactionData("URL", "https://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]?return=yes");
		$eway->setTransactionData("AutoRedirect", "1");
		$eway->setTransactionData("TrxnNumber", "");
		$eway->setTransactionData("Option1", $_POST['my_ewayOption1']);
		$eway->setTransactionData("Option2", "");
		$eway->setTransactionData("Option3", "");
		
		//for REAL_TIME_CVN
		$eway->setTransactionData("CVN", $_POST['my_eway_cvn']);
		
		if($eWay_config["processing_method"] == GEO_IP_ANTI_FRAUD){
			//for GEO_IP_ANTI_FRAUD 		
			$eway->setTransactionData("CustomerIPAddress", $eway->getVisitorIP()); //mandatory field when using Geo-IP Anti-Fraud
			$eway->setTransactionData("CustomerBillingCountry", $_POST['my_country_code']); //mandatory field when using Geo-IP Anti-Fraud
		}
		
		
		
		//$eway->setCurlPreferences(CURLOPT_CAINFO, "/usr/share/ssl/certs/my.cert.crt"); //Pass a filename of a file holding one or more certificates to verify the peer with. This only makes sense when used in combination with the CURLOPT_SSL_VERIFYPEER option. 
		//$eway->setCurlPreferences(CURLOPT_CAPATH, "/usr/share/ssl/certs/my.cert.path");
		if(!$eWay_config["ssl_verifier"])
			$eway->setCurlPreferences(CURLOPT_SSL_VERIFYPEER, 0);  //pass a long that is set to a zero value to stop curl from verifying the peer's certificate 
		
				
		if($eWay_config["curl_proxy"]!=""){
			$eway->setCurlPreferences(CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //use CURL proxy, for example godaddy.com hosting requires it
			$eway->setCurlPreferences(CURLOPT_PROXY, $eWay_config["curl_proxy"]); //use CURL proxy, for example godaddy.com hosting requires it
			//http://proxy.shr.secureserver.net:3128
		}
		
		//print_r($_POST);
			
		$ewayResponseFields = $eway->doPayment();
		
		//print_r($ewayResponseFields);
		//exit;
		
		if($ewayResponseFields["EWAYTRXNSTATUS"]=="False"){
			/*print "Transaction Error: " . $ewayResponseFields["EWAYTRXNERROR"] . "<br>\n";		
			foreach($ewayResponseFields as $key => $value)
				print "\n<br>\$ewayResponseFields[\"$key\"] = $value";*/
			header("Location: $failure_oscommerce_page?osCsid=$ewayResponseFields[EWAYTRXNOPTION1]&error_message=$ewayResponseFields[EWAYTRXNERROR] Please try again.");
			exit();		
		}else if($ewayResponseFields["EWAYTRXNSTATUS"]=="True"){
			/*print "Transaction Success: " . $ewayResponseFields["EWAYTRXNERROR"]  . "<br>\n";
			foreach($ewayResponseFields as $key => $value)
				print "\n<br>\$ewayResponseFields[\"$key\"] = $value";*/
			header("Location: $success_oscommerce_page?osCsid=$ewayResponseFields[EWAYTRXNOPTION1]&order_id=$ewayResponseFields[EWAYTRXNNUMBER]");
			exit();
		}
	}else{
		//hosted payment
		if(!isset($_GET["return"])) {
			require_once('EwayPaymentHosted.php');
			
			$eway = new EwayPaymentHosted($eWay_config["customer_id"], $eWay_config["processing_method"], $eWay_config["live_gateway"]); 		
			
			$eway->setTransactionData("TotalAmount", $_POST['my_totalamount']); //mandatory field
			//$eway->setTransactionData("TotalAmount", 10.00); //mandatory field //For testing
			$eway->setTransactionData("CustomerFirstName", $_POST['my_firstname']);
			$eway->setTransactionData("CustomerLastName", $_POST['my_lastname']);
			$eway->setTransactionData("CustomerEmail", $_POST['my_email']);			
			$eway->setTransactionData("CustomerAddress", $_POST['my_address']);
			$eway->setTransactionData("CustomerPostcode", $_POST['my_postcode']);
			$eway->setTransactionData("CustomerInvoiceDescription", $_POST['my_invoice_description']);
			$eway->setTransactionData("CustomerInvoiceRef", $_POST['my_invoice_ref']);
			$eway->setTransactionData("URL", "https://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]?return=yes"); //the script that will receive the results: http://www.mywebsite.com.au/testewayhosted.php?return=yes
			//$eway->setTransactionData("SiteTitle", "My Web Site");
			$eway->setTransactionData("AutoRedirect", "1");
			$eway->setTransactionData("TrxnNumber", "");
			$eway->setTransactionData("Option1", $_POST['my_ewayOption1']);
			$eway->setTransactionData("Option2", "");
			$eway->setTransactionData("Option3", "");
			
			$eway->doPayment();
		}else if($_GET["return"]=="yes") {
			//PROCESS RETURN RESULTS FROM EWAY		
			$ewayResponseFields = $_POST;
			if($ewayResponseFields["ewayTrxnStatus"]=="False"){
				/*print "Transaction Error: " . $ewayResponseFields["eWAYresponseText"] . "<br>\n";		
				foreach($ewayResponseFields as $key => $value)
					print "\n<br>\$ewayResponseFields[\"$key\"] = $value";*/
				header("Location: $failure_oscommerce_page?osCsid=$ewayResponseFields[ewayTrxnReference]&error_message=$ewayResponseFields[eWAYresponseText] Please try again.");
				exit();
			}else if($ewayResponseFields["ewayTrxnStatus"]=="True"){
				/*print "Transaction Success: " . $ewayResponseFields["eWAYresponseText"]  . "<br>\n";
				foreach($ewayResponseFields as $key => $value)
					print "\n<br>\$ewayResponseFields[\"$key\"] = $value";*/
				header("Location: $success_oscommerce_page?osCsid=$ewayResponseFields[eWAYoption1]&order_id=$ewayResponseFields[ewayTrxnReference]");
				exit();				
			}
		}
	}
	/*foreach($_POST as $key=>$value)
		print "$key= $value\n<br>";*/
?>