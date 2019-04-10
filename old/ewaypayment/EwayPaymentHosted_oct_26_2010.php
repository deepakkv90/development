<?
class EwayPaymentHosted {
	var $myGatewayURL;
    var $myCustomerID;
    var $myTransactionData = array();
    
	//Class Constructor
	function EwayPaymentHosted($customerID, $method ,$liveGateway) {
	    $this->myCustomerID = $customerID;
	    switch($method){
		    case REAL_TIME_HOSTED:
		    		if($liveGateway)
		    			$this->myGatewayURL = EWAY_PAYMENT_HOSTED_REAL_TIME;
		    		else
	    				$this->myGatewayURL = EWAY_PAYMENT_HOSTED_REAL_TIME_TESTING_MODE;
	    		break;
	    	 case REAL_TIME_CVN_HOSTED:
		    		if($liveGateway)
		    			$this->myGatewayURL = EWAY_PAYMENT_HOSTED_REAL_TIME_CVN;
		    		else
	    				$this->myGatewayURL = EWAY_PAYMENT_HOSTED_REAL_TIME_CVN_TESTING_MODE;
	    		break;	    	
    	}
	}
	
	//Payment Function
	function doPayment() {
?>
<html>
<head></head>
<body>
	<form method="post" name="ewaySubmitForm" action="<?=$this->myGatewayURL;?>">
	<input type="hidden" name="ewayCustomerID" value="<?=$this->myCustomerID;?>" />
<?    
	foreach($this->myTransactionData as $key=>$value){
?>	<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
<?	
	}
?>	</form>
<script type="text/javascript">document.ewaySubmitForm.submit();</script>
</body>
</html>
<?
	}
	
	//Set Transaction Data
	//Possible fields: "TotalAmount", "CustomerFirstName", "CustomerLastName", "CustomerEmail", "CustomerAddress", "CustomerPostcode", 
	//"CustomerInvoiceDescription", "CustomerInvoiceRef", "URL", "SiteTitle", "TrxnNumber", "Option1", "Option2", "Option3", "CVN"
	function setTransactionData($field, $value) {
		if($field=="TotalAmount")
			$value = round($value*100);
		$this->myTransactionData["eway" . $field] = htmlentities(trim($value));
	}
}
?>