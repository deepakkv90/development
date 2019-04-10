<?
	function databaseConnect(){
		$dbCON = @mysql_pconnect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD) or die ("Woah! It seems the database is being overloaded, please try to access this page later.");
		@mysql_select_db(DB_DATABASE, $dbCON);
	}

	function getEWayConfigFromDatabase(){
		$q = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='MODULE_PAYMENT_EWAYPAYMENT_CUSTOMER_ID'";
		$result = mysql_query($q);
		if($row=mysql_fetch_array($result))
			$eway_customer_id = trim($row["configuration_value"]);
			
		$q = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='MODULE_PAYMENT_EWAYPAYMENT_GATEWAY_MODE'";
		$result = mysql_query($q);
		if($row=mysql_fetch_array($result))
			$eway_live_gateway = $row["configuration_value"]=='Live gateway' ? true : false;
			
		$q = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='MODULE_PAYMENT_EWAYPAYMENT_PROCESSING_METHOD'";
		$result = mysql_query($q);
		if($row=mysql_fetch_array($result))
			$eway_processing_method = $row["configuration_value"];
		
		$q = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='MODULE_PAYMENT_EWAYPAYMENT_SSL_VERIFIER'";
		$result = mysql_query($q);
		if($row=mysql_fetch_array($result))
			$eway_ssl_verifier = $row["configuration_value"]=='On' ? true : false;
		
		$q = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key='MODULE_PAYMENT_EWAYPAYMENT_CURL_PROXY'";
		$result = mysql_query($q);
		if($row=mysql_fetch_array($result))
			$eway_curl_proxy = trim($row["configuration_value"]);
			
		return array("customer_id" => $eway_customer_id, "live_gateway" => $eway_live_gateway, "processing_method" => $eway_processing_method, "ssl_verifier" => $eway_ssl_verifier, "curl_proxy" => $eway_curl_proxy);
	}
	
?>