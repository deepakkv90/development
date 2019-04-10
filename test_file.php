<?php 
$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, 1); 
$url = "https://ajparkes.com.au/ajpcrm/test_crm.php";
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);	
	curl_setopt($ch, CURLOPT_URL,$url);	
	$result = curl_exec ($ch);
	echo "....".curl_error($ch);
	curl_close ($ch);
	
	var_dump($result);
exit; 