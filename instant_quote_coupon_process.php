<?php
header('Content-Type: application/json');
//$resp = array("code" => "Hello", "type" => "Good bye", "value" => 2.55,);
//echo json_encode($resp);
//exit;

include('includes/configure.php');
include('includes/filenames.php');
include('includes/database_tables.php');
include('includes/functions/database.php');
include('includes/functions/general.php');        
tep_db_connect();



$code = mysql_real_escape_string($_GET['code']); // get the requested page
$date = date('Y-m-d H:i:s');

if(!empty($code)) {
	$rst = mysql_query("SELECT coupon_id, coupon_type, coupon_code, coupon_amount FROM coupons WHERE coupon_code='".$code."' AND (NOW() >= coupon_start_date AND NOW() <= coupon_expire_date) AND coupon_active='Y'");
	if(mysql_num_rows($rst)>0) {
		$row = mysql_fetch_array($rst);
		
		$response["success"] = 1;
		$response["code"] = $code;
		if($row["coupon_type"]=="P") {
			$response["type"]="percentage";
			$response["value"] = $row["coupon_amount"]/100;
		} else { 
			$response["type"]="fixed"; 
			$response["value"] = $row["coupon_amount"];
		}		
		$response["message"] = "";
	} else {
		$response["success"] = 0;
		$response["message"] = "No Coupon Available/Expired";
	}

} else {
	$response["success"] = 0;
	$response["message"] = "Enter Valid Coupon Code";
}

//echo $data;
echo json_encode($response);
exit;
?>