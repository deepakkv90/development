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

$weeks = strtotime("-2 week");
//$weeks = strtotime("-1 Day");
	
$start = "2012-09-01 00:00:00";

$end = date("Y-m-d",$weeks)." 00:00:00";

//echo "SELECT o.orders_id, o.customers_id, o.orders_status, o.customers_name, o.customers_email_address, o.customers_company, o.due_date, o.xero, o.last_modified from " . TABLE_ORDERS . " o WHERE o.orders_status='100006' AND (o.last_modified >= '".$start."' AND  o.last_modified <= '".$end."') ORDER BY o.orders_id ASC";
//exit;

$orders_query = tep_db_query("SELECT o.orders_id, o.customers_id, o.orders_status, o.customers_name, o.customers_email_address, o.customers_company, o.due_date, o.xero, o.last_modified from " . TABLE_ORDERS . " o WHERE o.orders_status='100006' AND (o.last_modified >= '".$start."' AND  o.last_modified <= '".$end."') ORDER BY o.orders_id ASC");
  
	while($orders_list = tep_db_fetch_array($orders_query)) {
	
		//$customers_arr = tep_get_customer($orders_list["customers_id"]);
		
		$body = '';
		
		$invoiced = "";
		
		if(($orders_list["last_modified"]!="0000-00-00 00:00:00") || ($orders_list["last_modified"]!="NULL") || !empty($orders_list["last_modified"])) {
			$invoiced = tep_date_aus_format($orders_list["last_modified"],"short");
		}
		
		$body = '<div style="padding:10px;"> <table align="center" border="0" width="100%" cellspacing="0" cellpadding="4"><tr> <td><p>Dear '.$orders_list["customers_name"].',<br><br>Since your order '.$orders_list["orders_id"].' has been invoiced on '.$invoiced.',<br>we would like to offer you the chance to comment on how you felt about your experience with us.<br><br>Please click on the below link (or paste it into a browser), <br>
you will see a short questionnaire that should take less than a minute to complete.<br><br>We value your feedback and your answers will help us continue to improve our services.<br><br>http://ajparkes.com.au/pages.php?CDpath=0&pID=63<br><br>Thanks for your time,<br><br>AJ Parkes Customer support team.<br></p></td></tr></table></div>';
								
		if(!empty($orders_list["customers_email_address"])) {
			
			$mail->SetFrom("sales@ajparkes.com.au", "Admin");
			
			$mail->AddAddress($orders_list["customers_email_address"], $orders_list["customers_name"]);
					
			$mail->AddBCC("ananthan@indusnet.co.in", 'Ananthan');
			
			$mail->Subject    = "Feedback - Share your experiences about ajparkes.com.au";
					
			$mail->MsgHTML($body);
					
			$mail->Send();
			
			$mail->ClearAddresses();
		}
		
		//echo $body;
		//echo "<br>";
			
	}			

?>