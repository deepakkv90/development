<?php
/*
  $Id: xero_daily_report.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

//require('includes/application_top.php');

include('includes/configure.php');
include('includes/filenames.php');
include('includes/database_tables.php');
include('includes/functions/database.php');
include('includes/functions/general.php');

require('includes/classes/class.phpmailer.php');
$mail = new PHPMailer();
  
tep_db_connect();
	

$start = date("Y-m-d")." 00:00:00";

$end = date("Y-m-d")." 23:59:59";

//get orders by customer
$fb_qry1 = tep_db_query("SELECT customers_id, orders_id, feedback_info_id FROM feedback_info WHERE status='0' AND (date_scheduled > '".$start."' AND  date_scheduled <= '".$end."')");
while($fb_arr1 = tep_db_fetch_array($fb_qry1)) {
	$cust_id = $fb_arr1["customers_id"]; 
	$cust_arr[$cust_id][] =  $fb_arr1["orders_id"]; 
}
if(tep_db_num_rows($fb_qry1) > 0) {
    foreach($cust_arr as $cId=>$oId) {	
    	$oId_str=implode($oId,",");
    	$orders_string[$cId] = $oId_str;		
    }
} else {
	echo "No list avail.";
}

$fb_qry = tep_db_query("SELECT DISTINCT customers_id, orders_id, feedback_info_id, COUNT(orders_id) as ord_count FROM feedback_info WHERE status='0' AND (date_scheduled > '".$start."' AND  date_scheduled <= '".$end."') GROUP BY customers_id");

while($fb_arr = tep_db_fetch_array($fb_qry)) {
	
	$body = '';
		
	$invoiced = "";
				
	$orders_query = tep_db_query("SELECT o.orders_id, o.customers_id, o.orders_status, o.customers_name, o.customers_email_address, o.customers_company, o.last_modified from " . TABLE_ORDERS . " o WHERE o.orders_status='100006' AND (o.customers_id = '".$fb_arr['customers_id']."' AND  o.orders_id = '".$fb_arr['orders_id']."')");
  
	$orders_list = tep_db_fetch_array($orders_query);
	
		//$customers_arr = tep_get_customer($orders_list["customers_id"]);	
		
		if(($orders_list["last_modified"]!="0000-00-00 00:00:00") || ($orders_list["last_modified"]!="NULL") || !empty($orders_list["last_modified"])) {
			$invoiced = tep_date_aus_format($orders_list["last_modified"],"short");
		}
		if($fb_arr["ord_count"]>1) {
			$odstring = " orders " . $orders_string[$fb_arr['customers_id']] . " have ";
		} else {
			$odstring = " order ".$orders_string[$fb_arr['customers_id']]." has ";
		}
		
		$body = '<div style="padding:10px;"> <table align="center" border="0" width="100%" cellspacing="0" cellpadding="4"><tr> <td><p>Dear '.$orders_list["customers_name"].',<br><br>Since your '.$odstring.' been invoiced on '.$invoiced.',<br>we would like to offer you the chance to comment on how you felt about your experience with us.<br><br>Please click on the below link (or paste it into a browser), <br>
you will see a short questionnaire that should take less than a minute to complete.<br><br>We value your feedback and your answers will help us continue to improve our services.<br><br>http://namebadgesinternational.com.au/pages.php?CDpath=0&pID=75<br><br>Thanks for your time,<br><br>Name Badges International Customer support team.<br></p></td></tr></table></div>';
		
        
		if(!empty($orders_list["customers_email_address"])) {
			
			$mail->SetFrom("sales@namebadgesinternational.com.au", "Admin");
			
			$mail->AddAddress($orders_list["customers_email_address"], $orders_list["customers_name"]);
					
			$mail->AddBCC("ajparkesdev@gmail.com", 'Developer');
			
			$mail->Subject    = "Feedback - Share your experiences about namebadgesinternational.com.au";
					
			$mail->MsgHTML($body);
					
			if($mail->Send()) {
				$fb_upd = tep_db_query("UPDATE feedback_info SET status='1' WHERE customers_id = '".$fb_arr['customers_id']."' AND (date_scheduled > '".$start."' AND  date_scheduled <= '".$end."')");
			}
			
			$mail->ClearAddresses();
		}
        
		
		//echo $body;
		//echo "<br>";			
}			

?>