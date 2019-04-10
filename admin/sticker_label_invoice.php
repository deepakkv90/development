<?php
/*
  $Id: invoice.php,v 1.2 2004/03/13 15:09:11 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');

require_once('html2pdf/html2pdf.class.php');

require('includes/languages/english/invoice_pdf.php');

$order_id = (isset($_GET['order_id']) ? $_GET['order_id'] : '');

$customer_number_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($_GET['oID'])) . "'");

$customer_number = tep_db_fetch_array($customer_number_query);

$customer_number_query = tep_db_query("select c.customers_id, a.entry_company_tax_id from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_default_address_id = a.address_book_id where a.customers_id = c.customers_id and c.customers_id = '" . $customer_number['customers_id'] . "'");

$customers = tep_db_fetch_array($customer_number_query);

if (!is_array($customers)) {
	$customers = array();
}

$cInfo = new objectInfo($customers);
		
//Assign customer number 
if(!empty($cInfo->entry_company_tax_id)) {
	$customerNumber = $cInfo->entry_company_tax_id;
} else {
	$customerNumber = $cInfo->customers_id;
}

$payment_info_query = tep_db_query("select payment_info from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($order_id)) . "'");
$payment_info = tep_db_fetch_array($payment_info_query);
$payment_info = $payment_info['payment_info'];
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

$oID = tep_db_prepare_input($_GET['oID']);

$orders_query = tep_db_query("select * from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");

$orders_arr = tep_db_fetch_array($orders_query);
/*
//get eparcel details
$eparcel_qry = tep_db_query("SELECT * FROM eparcel_consignment ec LEFT JOIN eparcel_article ea ON (ec.consignment_id = ea.consignment_id) WHERE ec.orders_id = '".tep_db_input($oID)."'");
$eparcel_arr = tep_db_fetch_array($eparcel_qry);*/

include(DIR_WS_CLASSES . 'order.php');

$order = new order($oID);

ob_start();

?>

<page style="font-size:12px; font-family: Arial" backtop="5mm" backbottom="15mm" backleft="5mm" backright="5mm">

    	
    <page_footer>
	   <table style="width:100%;" border="0" cellspacing="0" cellpadding="0" class="main">
	       <tr>
	           <td style="width: 100%;">
	               <table style="width:100%;" border="0" cellspacing="0" cellpadding="0" class="main">
                      <tr>
                        <td style="width:100%; text-align:center; font-size:10px;"><b><font color="#FF6600">Name Badges International thanks you for your business</font></b></td>
                      </tr>
                      <tr>
                        <td style="width:100%;"><hr size="1"></td>
                      </tr>
                      <tr>
                        <td style="width:100%; text-align:left; font-size:10px;">Name Badges International Pty Ltd - The Professional Choice in Personalised Name Badges</td>
                      </tr>
                      <tr>
                        <td style="width:100%; text-align:left; font-size:10px;">Phone&#58; 02 8003 5046,&nbsp;Suite F Level 1 Octagon, 110 George Street, Parramatta, NSW 2150&nbsp;Australia</td>
                      </tr>
                      <tr>
                        <td style="width:100%; text-align:left; font-size:10px;">&#169; Copyright 2011 Name Badges International Pty Ltd.&nbsp;&nbsp;http://www.namebadgesinternational.com.au&nbsp;&nbsp;&#124;&nbsp;&nbsp;ABN&#58;&nbsp;60149490406</td>
                      </tr>
                                 
                    </table>
	           </td>	           		   
	       </tr>
		   <tr>
				<td style="width: 100%; height:30px;">&nbsp;</td>
		   </tr>
		 </table>
	</page_footer>

<!-- sticker Label area Starts -->
	
<table align="left" style="width:90%; padding-left:25px;"  cellspacing="0" cellpadding="0">
<tr>
<td>
	<br />
    <table style="width: 100%;" cellspacing="0" cellpadding="0">            
        <tr>
            <td style="width: 55%; vertical-align:top;">                
                    <div style="font-size:18px;"><b><?php echo "DELIVER TO";  ?></b></div>
                    <br>
                    <div style="padding:2px; font-size:16px; line-height:18px;">
                        <?php 
                            echo strtoupper($orders_arr["delivery_name"]."<br>" .
                                 $orders_arr["delivery_company"]."<br>" .
                                 $orders_arr["delivery_street_address"]."<br>" .
                                 $orders_arr["delivery_city"]." " .
                                 $orders_arr["delivery_state"]." ".$orders_arr["delivery_postcode"]); 
                        ?>                      
                    </div>                                                  
            </td>           
            <td style="width: 45%; vertical-align:center;">             
                <div style="padding:2px;font-size:18px; text-align:right;">
                        PHONE: <?php echo $orders_arr['customers_telephone']; ?>                        
                </div>                  
            </td>
        </tr>
    </table>
    <br/>
    <table style="width: 100%; padding:5%;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="width:70%; font-size:16px; padding:5px; border-top:1px solid #CCC;">
                <b>DELIVERY INSTRUCTIONS</b>
            </td>
            <td style="text-align:center; padding:5px; font-size:16px; border-top:1px solid #CCC;">&nbsp;</td>
        </tr>
        
        <tr>
            <td style="width:100%; padding:5px; border-top:1px solid #CCC;" colspan="2">
                &nbsp;
            </td>           
        </tr>
        <tr>
            <td style="width:70%; height:5px; font-size:14px; padding:5px; border-bottom: solid 1px #CCC;">&nbsp;<br/></td>
            <td style="text-align:center; height:5px; border-bottom: solid 1px #CCC; padding:5px;">&nbsp;<br/></td>
        </tr>
                
    </table>    
    <br />
    <table style='width: 100%;' cellspacing='0' cellpadding='0'>
        <tr>
            <td style="width:55%; font-size:14px; padding:5px; line-height:18px;"> 
				<b>SENDER</b><br>
                NAME BADGES INTERNATIONAL PTY LTD<BR />
                SUITE F LEVEL 1 OCTAGON<BR />
				110 GEORGE STREET<br /> 
				PARRAMATTA, NSW 2150                
			</td>
			<td style='width: 20%; font-size:12px; padding:2px;text-align:center;'> 
				<img src="images/loaded_header_logo.png" width="80" height="86"> 
			</td>
			<td style='width: 25%; font-size:12px; padding:2px;text-align:center;'>                 
                &nbsp;
            </td>
            
        </tr>       
    </table>    
    <br/>
    <table style="width: 100%;" cellspacing="0" cellpadding="0">
        <tr>            
            <td style="width:100%; font-size:10px; text-align:justify; padding:5px; border-top: solid 1px #CCC;">
                <br><b>Aviation Security and Dangerous Goods Declaration</b><br/>
                The sender acknowledges that this article may be carried by air and will be subject to aviation security and clearing procedures and the sender declares that the article does not contain any dangerous or prohibited goods, explosive or incendiary devices. A false declaration is a criminal offence.				                              
            </td>
        </tr>
    </table>    
    <br/>    

</td>
</tr>
</table>
<br /><br/>
<br /><br/>
<hr style="border:1px solid #CCC;">
<br/>
<!-- sticker Label area Ends -->

	<table style="width: 100%;" cellspacing="1mm" cellpadding="0" >
		<tr>
			<td style="width:100%; text-align:center; height:70px; vertical-align:top">
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr>
						<td style="width:40%; text-align:left; vertical-align:top; font-size:10px;">
							<img src="images/loaded_header_logo.png" width="80" height="86">
							<br><br>
							<?php echo "Tax Invoice No. " . tep_db_input($oID); ?>
							<br>
							<?php echo "Invoice Date: " . tep_date_short($order->info['last_modified']); ?>
						</td>									
						<td style="width:30%; text-align:left; vertical-align:top; font-size:10px;">
						   <b> <?php echo "BILL TO:";  ?></b><br><br>
							<?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'); ?><br>							
							<br> <?php echo $order->customer['telephone']; ?>
							<br> <?php echo $order->customer['email_address']; ?>
						</td>
						<td style="width:30%; text-align:right; vertical-align:top; font-size:10px;">
							<font face="Verdana"> 
							   <?php echo "<br>".ENTRY_COMPANY_TAX_ID." ".$customerNumber; ?>
							</font><br>
							<?php if(!empty($orders_arr['order_assigned_to'])) { ?>
							<font face="Verdana"> 
								<?php echo "Sales Consultant: ".$orders_arr['order_assigned_to']; ?>
							</font><br>
							<?php } ?>
						</td>
					</tr>								
				</table>							
			</td>
		</tr>
	</table>
	<br/>
	<table style="width: 100%; border: solid 1px #EEEEFF;" cellspacing='0' cellpadding='0'>
		<tr style='background: #EFEFEF;'>
			<td style="width: 5%; font-weight:bold; font-size:10px; padding:5px;text-align:center;"> <?php echo TABLE_HEADING_QUANTITY; ?> </td>			
			<td style="width: 35%; font-weight:bold; font-size:10px; padding:5px;text-align:left;"> <?php echo TABLE_HEADING_PRODUCTS; ?> </td>			
			<td style="width: 15%; font-weight:bold; font-size:10px; padding:5px;text-align:right;"> <?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?> </td>
			<td style="width: 15%; font-weight:bold; font-size:10px; padding:5px;text-align:right;"> <?php echo TABLE_HEADING_PRICE_INCLUDING_TAX; ?> </td>
			<td style="width: 15%; font-weight:bold; font-size:10px; padding:5px;text-align:right;"> <?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?> </td>
			<td style="width: 15%; font-weight:bold; font-size:10px; padding:5px;text-align:right;"> <?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?> </td>
		</tr>
		<?php 
		for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
			
			echo '<tr>' . "\n";			
			
			echo '<td style="width: 5%;padding:5px; border-bottom:1px solid #CCC;font-size:10px; vertical-align:top; text-align:center;">' . tep_display_tax_value($order->products[$i]['qty']) . '</td>' . "\n";	
			
			echo '<td style="width: 35%;padding:5px; border-bottom:1px solid #CCC;font-size:10px; vertical-align:top;">' . $order->products[$i]['name'] . '</td>' . "\n";
			
			echo '<td style="width: 15%;padding:5px; text-align:right; border-bottom:1px solid #CCC;font-size:10px; vertical-align:top;">' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n";
						
			echo '        <td style="width: 15%;padding:5px; text-align:right; border-bottom:1px solid #CCC;font-size:10px; vertical-align:top;">' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax_rate']), true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
			'        <td style="width: 15%;padding:5px; text-align:right; border-bottom:1px solid #CCC;font-size:10px; vertical-align:top;">' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
			
			'        <td style="width: 15%; text-align:right; padding:5px; border-bottom:1px solid #CCC;font-size:10px; vertical-align:top;">' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax_rate']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n";
			echo '</tr>' . "\n";
		}
		?>
		
		<tr>
           <td colspan="3" style="width:55%; text-align:right;">&nbsp;</td>
           <td style="width:45%; text-align:right;" colspan="3">
                <table border="0" align="right" cellspacing='0' cellpadding='0'>
                    <?php
                     for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
                       echo '         <tr>' . "\n" .
                            '          <td style="padding:5px; font-size:10px; text-align:right;">' . $order->totals[$i]['title'] . '</td>' . "\n" .
                            '          <td style="padding:5px; font-size:10px; text-align:right;">' . $order->totals[$i]['text'] . '</td>' . "\n" .
                            '         </tr>' . "\n";
                     }
                    ?>
                    <tr><td colspan="2"></td></tr>
                </table>
            </td>
         </tr>   
         
	</table>
</page> 



<?php 

//exit;

	$content = ob_get_clean();	
	// encode data (puts attachment in proper format)
	try {
		$html2pdf = new HTML2PDF('P','A4', 'en', false, 'ISO-8859-15');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		//$pdfdoc = $html2pdf->Output($name, 'S');
		$pdfdoc = $html2pdf->Output($name);
	}
	catch(HTML2PDF_exception $e) { echo $e; }
	//require(DIR_WS_INCLUDES . 'application_bottom.php'); 

?>
