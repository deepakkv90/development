<?php
	//define('NBI_PAYPAL_IPN_SYNC_URL', "https://namebadgesinternational.com.au/curl_response.php");
	require('includes/configure.php');
	if(NBI_SYNC==true) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	$url = NBI_PAYPAL_IPN_SYNC_URL;
	$data = array(
    'username' => 'user1',
    'password' => 'passuser1',
    'gender'   => 1,
	);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);     
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);	
	curl_setopt($ch, CURLOPT_URL,"https://namebadgesinternational.com.au/manage_paypal_ipn.php");	
	var_dump(curl_exec($ch));
	var_dump(curl_getinfo($ch));
	var_dump(curl_error($ch)); 
	}
?>