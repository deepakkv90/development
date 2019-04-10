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

$start = date("Y-m-d",time())." 00:00:00";
$end = date("Y-m-d",time())." 23:59:59";

$orders_query = tep_db_query("SELECT o.orders_id, o.customers_id, o.orders_status, o.customers_name, o.customers_company, o.due_date, o.xero, os.orders_status_name from " . TABLE_ORDERS . " o LEFT JOIN orders_status os ON (o.orders_status=os.orders_status_id) WHERE o.orders_status='100006' AND  o.last_modified >= '".$start."' AND o.last_modified < '".$end."' ORDER BY o.orders_id ASC");

$body = '<table align="center" border="0" width="100%" cellspacing="0" cellpadding="2">
	<tr>
		<td width="5%">&nbsp;</td>
		<td width="90%">
			<div style="padding:10px;"> 
				
				Hello,<br>
				This is the report for NBi.COM.AU. <br><br>The following orders has been synchronized with XERO .<br>
				Please Ensure that the correct information has been updated in XERO online.<br>
				If there is any order marked as FAILED, please check it manually online.</br></br>
				<p>&nbsp;</p>
				
				<table align="center" border="1" width="100%" cellspacing="0" cellpadding="4">
					<tr>
						<td style="font-weight:bold; text-align:center;">Account name</td>
						<td style="font-weight:bold; text-align:center;">Cust#</td>
						<td style="font-weight:bold; text-align:center;">Maco#</td>
						<td style="font-weight:bold; text-align:center;">Quote/Order Number</td>
						<td style="font-weight:bold; text-align:center;">Due Date</td>
						<td style="font-weight:bold; text-align:center;">Status</td>
						<td style="font-weight:bold; text-align:center;">XERO</td>
					</tr>';

	if($num_orders = tep_db_num_rows($orders_query)>0) {
  
				    while($orders_list = tep_db_fetch_array($orders_query)) {
					
					$customers_arr = tep_get_customer($orders_list["customers_id"]);
					
					$duedate = "";
					if(($orders_list["due_date"]!="0000-00-00 00:00:00") || ($orders_list["due_date"]!="NULL") || !empty($orders_list["due_date"])) {
						$duedate = tep_date_aus_format($orders_list["due_date"],"short");
					}
					
					$body .= '<tr>
						<td style="text-align:center;"><a href="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG .  'admin/orders.php?oID=' . $orders_list["orders_id"] . '&action=edit' . '">'.$orders_list["customers_company"].'</a></td>
						<td style="text-align:center;">'.$orders_list["customers_id"].'</td>
						<td style="text-align:center;">'.$customers_arr["entry_company_tax_id"].'</td>
						<td style="text-align:center;"><a href="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . 'admin/orders.php?oID=' . $orders_list["orders_id"] . '&action=edit' . '">' . $orders_list["orders_id"] . '</a></td>
						<td style="text-align:center;">'.$duedate.'</td>
						<td style="text-align:center;">'.$orders_list["orders_status_name"].'</td>
						<td style="text-align:center;">'.(($orders_list["xero"]==1)?"Success":"Failed or n/a") .'</td>
					</tr>';
					}
				$body .= '</table>				
					
					<p>&nbsp;</p>
					<p>Thanks </p>
					<p>Namebadgesinternational</p>
					</div>
				</td>
				<td width="5%">&nbsp;</td>		
			</tr>
		</table>';
		
					
		$mail->SetFrom("sales@namebadgesinternational.com.au", "Admin");
		
		$mail->AddAddress("xero@namebadgesinternational.com.au", "Derek");
				
		$mail->AddAddress("ajparkesdev@gmail.com", 'Developer');
		
		$mail->Subject    = "Daily Report for XERO Sync - ".tep_date_aus_format($start,"short");
				
		$mail->MsgHTML($body);
				
		$mail->Send();
		
	} 
	
	//echo $body;
?>