<?php
/*
  $Id: invoice_pdf.php,v 1.2 2004/03/13 15:09:11 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
require(DIR_FS_CLASSES . 'object_info.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ORDERS_INVOICE);

require('html2pdf/html2pdf.class.php');

$order_id = (isset($_GET['order_id']) ? $_GET['order_id'] : '');

//$customer_number_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($order_id)) . "'");
$customer_number_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($_GET['order_id'])) . "'");
$customer_number = tep_db_fetch_array($customer_number_query);
$customer_number_query = tep_db_query("select c.customers_id, a.entry_company_tax_id from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_default_address_id = a.address_book_id where a.customers_id = c.customers_id and c.customers_id = '" . $customer_number['customers_id'] . "'");
 $customers = tep_db_fetch_array($customer_number_query);
	if (!is_array($customers)) {
	  $customers = array();
	}
	$cInfo = new objectInfo($customers);
		
	//Assign customer number 
	
	$customerNumber = $cInfo->customers_id;
	
		
		
$payment_info_query = tep_db_query("select payment_info from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($order_id)) . "'");
$payment_info = tep_db_fetch_array($payment_info_query);
$payment_info = $payment_info['payment_info'];

$oID = tep_db_prepare_input($_GET['order_id']);
$orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");
include(DIR_WS_CLASSES . 'order.php');
$order = new order($oID);

ob_start();

?>

<style type="text/css">
	.pdf-prod-head { font-weight:bold; font-size:12px; padding:5px; border-bottom:1px solid #EFEFEF; }
	.pdf-prod { padding:5px; border-bottom:1px solid #EFEFEF;font-size:10px; vertical-align:top; }
	.pdf-footer { width:100%; text-align:center; font-size:11px; }
	.pdf-addr-box { vertical-align: top; font-size:13px; height:200px;border: 1px solid #000; border-radius: 2mm; background: #EFEFEF; border-collapse: collapse; padding-top:2mm; padding-left:5px; }
	.pdf-text { text-align:center; font-weight:bold; font-size:12px; }
	.left-line { border-left:1px solid #EFEFEF; }
	.right-line { border-right:1px solid #EFEFEF; }
</style>

<page style="font-size:12px;" backtop="58mm" backbottom="45mm" backleft="8mm" backright="8mm">

	<page_header>
				<table style="width: 100%;" cellspacing="1mm" cellpadding="0" >
					<tr>
						<td style="width:100%; text-align:center; height:110px; vertical-align:top">
							<table border="0" width="100%" cellspacing="0" cellpadding="2">
								<tr>
									<td style="width:50%; text-align:left; vertical-align:middle;">
										<img src="images/loaded_header_logo.png">
									</td>
									<td style="width:50%; text-align:right; vertical-align:bottom;">&nbsp;
										
									</td>
								</tr>
							  
								<tr>
									<td style="width:50%;">&nbsp;</td>
									<td style="width:50%; text-align:right;">
									  <font face="Verdana"><br /><?php echo ENTRY_COMPANY_TAX_ID_NEW . $customerNumber; ?></font>     
									</td>
								</tr>
								
								<?php if(!empty($order->info['order_assigned_to'])) { ?>
								<tr>
									<td style="width:50%;">&nbsp;</td>
									<td style="width:50%; text-align:right;">
									  <font face="Verdana"> <?php echo "Sales Consultant:&nbsp;" . $order->info['order_assigned_to']; ?></font>     
									</td>
								</tr>
								<?php } ?>
								
							</table>
							<br>
							<table style="width:100%;" border="0" cellspacing="0" cellpadding="2">
								<tr>
								  <td style="width:10%;"><hr></td>
								  <td style="width:20%;">
								   <?php  if($order->info['orders_status_number']!=100006) {  ?> 
								  	<em><b><?php echo PRINT_INVOICE_HEADING; ?></b></em>
								   <?php } else { ?>
										<em><b><?php echo "Tax Invoice No. ".tep_db_input($oID); ?></b></em>
								   <?php } ?>
								  </td>
								  <td style="width:70%;"><hr></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
	</page_header>		
	<page_footer>
		<table style="width:100%;" border="0" cellspacing="0" cellpadding="0" class="main">
		  <tr>
			<td class="pdf-footer"><b><font color="#FF6600">Name Badges International thanks you for your business</font></b></td>
		  </tr>
		  <tr>
			<td style="width:100%;"><hr size="1"></td>
		  </tr>
		  <tr>
			<td align="center"  class="pdf-footer">
				For Payments over the Phone (Credit Card). Simply <span style="font-weight: bold;">Call 1300 267 074</span> and follow the prompts. <br>
				<span style="font-weight: bold;">Biller ID: 460287&nbsp; - Reference Number: your Order Number</span><br>
				<hr style="dotted">
				For bank Transfer, please use the following details with THE ORDER NUMBER as reference to transfer your total order value:<br /><br />
			  <b>Account Name: Name Badges International Pty Ltd Account Number: BSB 034081 - Account# 263974<br>
			Bank Name: Westpac Acacia Ridge, Brisbane</b></td>
		  </tr>
		  <tr>
			<td align="center"  class="pdf-footer">----------------------------------------------------------------------------------</td>
		  </tr>		
		  <tr>
			<td class="pdf-footer">
				Name Badges International Pty Ltd - The Professional Choice in Personalised Name Badges</td>
		  </tr>		  
		  <tr>
			<td class="pdf-footer">Phone: 02 8003 5046&nbsp;| Suite F Level 1 Octagon, 110 George Street  Parramatta, NSW 2150 Australia</td>
		  </tr>
		  <tr>
			<td class="pdf-footer"> &copy; Copyright 2011 Name Badges International Pty Ltd.&nbsp;|&nbsp;ABN:&nbsp;60 149 490 406</td>
		  </tr>		 
		  <tr>
			<td class="pdf-footer">http://www.namebadgesinternational.com.au</td>
		  </tr>				  
	    </table>
	</page_footer>
	
	<table align="center" style="width: 100%;" cellspacing="1mm" cellpadding="0">			
		<tr>
			<td style="width: 48%; vertical-align:top;">
				<div class="pdf-addr-box">						
					<div style="font-size:13px; font-weight:bold; padding:4px;"><?php echo ENTRY_SOLD_TO;  ?></div><br>
					<div style="padding-left:60px;font-size:12px; font-weight:bold; line-height:18px;">
						<?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, '', '<br>'); ?>
						<br>
						<?php echo $order->customer['telephone']; ?>
						<br>
						<?php echo $order->customer['email_address']; ?>
					</div>									
				</div>
			</td>
			<td style="width: 3%;">&nbsp;</td>
			<td style="width: 48%; vertical-align:top;">
				<div class="pdf-addr-box">
					<div style="font-size:13px; font-weight:bold;padding:4px;"><?php echo ENTRY_SHIP_TO;  ?></div><br>
					<div style="padding-left:60px;font-size:12px;font-weight:bold;line-height:18px;">
							<?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'); ?>
							
					</div>										
				</div>
			</td>
		</tr>
	</table>
	<br>
	<table align="center" style="width: 100%;" cellspacing="0" cellpadding="0">
		<tr>
			<td style="width:100%;">
				<div style="vertical-align: middle;text-align: center; border: 1px solid #000; border-radius: 1mm; background: #EFEFEF; border-collapse: collapse; padding:1mm;">
				<table style="width: 100%;border: none;" cellspacing="0" cellpadding="0">
					<tr>
						<?php  if($order->info['orders_status_number']!=100006) {  ?> 
							<td style="width:15%;" class="pdf-text">
								<?php echo PRINT_INVOICE_ORDERNR . " " . tep_db_input($oID); ?>
							</td>
							<td style="width:20%;" class="pdf-text">
								<?php echo ENTRY_DATE_PURCHASED . " " . tep_date_aus_format($order->info['date_purchased'],"short"); ?>
							</td>
							<td style="width:15%;" class="pdf-text">
								<?php echo "Due Date: " . " " . tep_date_aus_format($order->info['due_date'],"short"); ?>
							</td>
						<?php } else { ?>							
							<td style="width:50%;" class="pdf-text">
								<?php echo "Invoice Date: " . " " . tep_date_aus_format($order->info['last_modified'],"short"); ?>
							</td>
						<?php } ?>
						<td style="width:25%;" class="pdf-text">
							<?php echo ENTRY_PURCHASE_NUMBER . " " . $order->info['purchase_number']; ?>
						</td>
						<td style="width:25%;" class="pdf-text">
							<?php echo ENTRY_PAYMENT_METHOD . " " . $order->info['payment_method']; ?>
						</td>
					</tr>
				</table>	
				</div>
			</td>
		</tr>
	</table>
	<br>
	
	<table align="center" style='width: 100%; border: solid 1px #EFEFEF;' cellspacing='0' cellpadding='0'>";
		<tr style='background: #EFEFEF;'>
			<td style='width: 6%; text-align:center;' class="pdf-prod-head right-line"> <?php echo TABLE_HEADING_QUANTITY; ?> </td>
			<td style='width: 8%; text-align:center;' class="pdf-prod-head right-line"> <?php echo TABLE_HEADING_PRODUCTS_MODEL; ?> </td>
			<td style='width: 30%; text-align:left;' class="pdf-prod-head right-line"> <?php echo TABLE_HEADING_PRODUCTS; ?> </td>			
			<td style='width: 12%; text-align:right;' class="pdf-prod-head right-line"> <?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?> </td>
			<td style='width: 14%; text-align:right;' class="pdf-prod-head right-line"> <?php echo TABLE_HEADING_PRICE_INCLUDING_TAX; ?> </td>
			<td style='width: 15%; text-align:right;' class="pdf-prod-head right-line"> <?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?> </td>
			<td style='width: 15%; text-align:right;' class="pdf-prod-head"> <?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?> </td>
		</tr>
		<?php 
		for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
			
			echo '<tr>' . "\n";			
			
			echo '<td style="width: 6%; text-align:center;" class="pdf-prod right-line">' . tep_display_tax_value($order->products[$i]['qty']) . '</td>' . "\n";			
			
			echo '<td style="width: 8%; text-align:center;" class="pdf-prod right-line">' . $order->products[$i]['model'] . '</td>' . "\n";
			
			echo '<td style="width: 30%;" class="pdf-prod right-line">' . $order->products[$i]['name'];
							
				//Modified Sep, 09, 2010
				if($order->products[$i]['product_original_final_price'] > 0) {
					$product_original_price = $order->products[$i]['product_original_final_price'];
				} else {
					$product_original_price = $order->products[$i]['final_price'];
				}
				if(!empty($order->products[$i]['desc'])) {					
					echo '<br />';					
					echo $order->products[$i]['desc'];
				}
			
			echo '</td>' . "\n";
			
			echo '<td style="width: 12%; text-align:right;" class="pdf-prod right-line">' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n";
						
			echo '        <td style="width: 14%; text-align:right;" class="pdf-prod right-line">' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax_rate']), true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
			'        <td style="width: 15%; text-align:right;" class="pdf-prod right-line">' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
			
			'        <td style="width: 15%; text-align:right;" class="pdf-prod">' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax_rate']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n";
			echo '</tr>' . "\n";
		}
		?>
		<tr>
			  <td style="width:56%; text-align:center;" colspan="4">
						<?php
						if($order->info["price_break_amount"]>0) {
							?>
							<p style="font-weight:bold; font-size:10px;" class="main">
								<?php echo STORE_NAME; ?> Price Break Discount : <font style="color:#0099CC;">you save $<?php echo number_format($order->info["price_break_amount"],2); ?></font>	<br><br></p>
						<?php } ?>
			  </td>
              <td style="width:44%; text-align:right;" colspan="3">
			  	<table cellspacing="0" cellpadding="2" align="right" style="width:100%;">
                 	<?php
					 for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
					   echo '         <tr>' . "\n" .
							'          <td align="right" style="padding:5px; font-size:10px;">' . $order->totals[$i]['title'] . '</td>' . "\n" .
							'          <td align="right" style="padding:5px; font-size:10px;">' . $order->totals[$i]['text'] . '</td>' . "\n" .
							'         </tr>' . "\n";
					 }
          			?>
              	</table>
			</td>
		</tr>
	</table>
</page> 



<?php 

//exit;

	$content = ob_get_clean();	
	
	//$file_name = safe_filename(STORE_NAME);
    $file_name = "tax_invoice_" . $_GET['order_id'] . ".pdf";
	$dir = DIR_FS_CATALOG;
	$dir .= '/pdf/';
	$files = $dir .''. $file_name;
	
	try {
		$html2pdf = new HTML2PDF('P','A4', 'en', false, 'ISO-8859-15');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		 $mode = (FORCE_PDF_INVOICE_DOWNLOAD == 'true') ? 'D' : 'F';
    	 // what do we do? display inline or force download  	
		//$pdfdoc = $html2pdf->Output("pdf/".$file_name, $mode);		
		$pdfdoc = $html2pdf->Output($file_name,'I');
		//exit;
		
	}
	catch(HTML2PDF_exception $e) { echo $e; }
		
?>