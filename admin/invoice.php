<?php
/*
  $Id: invoice.php,v 1.2 2004/03/13 15:09:11 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$order_id = (isset($_GET['order_id']) ? $_GET['order_id'] : '');
//$customer_number_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($order_id)) . "'");
$customer_number_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($_GET['oID'])) . "'");
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
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
$oID = tep_db_prepare_input($_GET['oID']);
$orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");
include(DIR_WS_CLASSES . 'order.php');
$order = new order($oID);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE . ' - ' . TITLE_PRINT_ORDER . $oID; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<!--<link rel="stylesheet" type="text/css" href="includes/print.css">-->
<link rel="stylesheet" type="text/css" href="includes/new_stylesheet.css">
<style type="text/css">
<!--
.style1 {
	color: #D20000;
	font-weight: bold;
}
.style2 {color: #0069D2}
-->
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<div align="center">
<center>
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="650" id="AutoNumber1">
<tr>
  <td>
  <!-- body_text //-->
  <table border="0" width="100%" cellspacing="0" cellpadding="2">
  
  <tr>
  <TD ALIGN="left" VALIGN="left"><?php echo tep_image(DIR_WS_IMAGES . 'loaded_header_logo.png', STORE_NAME);  ?> </TD>
    <td align="right">
		<script language="JavaScript">
            if (window.print) {
              document.write('<a href="javascript:;" onClick="javascript:window.print()" onMouseOut=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" onMouseOver=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage_over.gif'); ?>"><img src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" width="25" height="15" align="absbottom" border="0" name="imprim">' + '<?php echo IMAGE_BUTTON_PRINT; ?></a></center>');
            }
            else document.write ('<h2><?php echo IMAGE_BUTTON_PRINT; ?></h2>')
          </script>
        &nbsp;&nbsp;&nbsp;<a href="javascript:window.close();"><img src='images/close_window.jpg' width="15" height="15" border=0></a>
	</td>
  </tr><tr>
    <td align="left">&nbsp;</td>
    <td align="right"><!-- sencot -->
      <FONT FACE="Arial" SIZE="2" COLOR="#000000"> <?php echo "<b>". ENTRY_CUSTOMER_NUMBER. "</b>&nbsp;" . $customerNumber.'<br>' ; ?> </strong></font>
      <!-- fin sencot -->
    </td>
  </tr>
  
   <?php if(!empty($order->info['order_assigned_to'])) { ?>
  <tr>
    <td align="left">&nbsp;</td>
    <td align="right">
      <font face="Verdana" size="2" color="#000000">
	  	<?php echo "<b>Sales Consultant: ". "</b>&nbsp;" .$order->info['order_assigned_to'].'<br>' ; ?>
	  </font>
    </td>
  </tr>
  <?php } ?>
  
   <?php if(!empty($order->customer['customers_term'])) { ?>
  <tr>
    <td align="left">&nbsp;</td>
    <td align="right">
      <font face="Verdana" size="2" color="#000000">
	  	<?php echo "<b>Term: ". "</b>&nbsp;" .$order->customer['customers_term'].'<br>' ; ?>
	  </font>
    </td>
  </tr>
  <?php } ?>
  
  <tr>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td width="10%"><hr size="1"></td>
                  <td align="center" class="pageHeading" width="37%">
				  	<?php  if($order->info['orders_status_number']!=100006) {  ?> 
						<em><b><?php echo PRINT_INVOICE_HEADING; ?></b></em>
					<?php } else { ?>
						<em><b><?php echo "Tax Invoice No. ".tep_db_input($oID); ?></b></em>
					<?php } ?>
				  </td>
                  <td width="100%"><hr size="1"></td>
                </tr>
              </table></td>
          </tr>
          <!--<tr>
            <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '100', '5'); ?></td>
          </tr>-->
          <tr>
          
          <!-- sencot fond ecran -->
          <td valign="top" width="49%">
          
		  <div class="container">
			 <b class="rtop"><b class="r1" style="border-bottom:2px solid #000;"></b> <b class="r2"></b> <b class="r3"></b> <b class="r4"></b></b>	 
			 <div class="inner_div">
	 		  
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="main">
                  <tr>
                    <td align="left" valign="top"><b><?php echo ENTRY_SOLD_TO; ?></b></td>
                  </tr>
                  <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
                  </tr>
                  <tr>
                    <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, '', '<br>&nbsp;&nbsp;&nbsp;&nbsp;'); ?></td>
                  </tr>
                  <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                  </tr>
                  <tr>
                    <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $order->customer['telephone']; ?></td>
                  </tr>
                  <tr>
                    <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $order->customer['email_address']; ?></td>
                  </tr>
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '7'); ?></td>
                  </tr>
                </table>
					  
				</div>
				<b class="rbottom"><b class="r4"></b> <b class="r3"></b> <b class="r2"></b> <b class="r1" style="border-top:2px solid #000;"></b></b>
			</div>

          </td>
          
          <td width="2%"></td>
          <td valign="top" width="49%">
            <!-- sencot fond ecran -->
             <div class="container">
				 <b class="rtop"><b class="r1" style="border-bottom:2px solid #000;"></b> <b class="r2"></b> <b class="r3"></b> <b class="r4"></b></b>	 
				 <div class="inner_div">
			
				  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="main">
                    <tr>
                      <td align="left" valign="top"><b><?php echo ENTRY_SHIP_TO; ?></b></td>
                    </tr>
                    <tr>
                      <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
                    </tr>
                    <tr>
                      <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>&nbsp;&nbsp;&nbsp;&nbsp;'); ?></td>
                    </tr>
                    <tr>
                      <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>
                    <tr>
                      <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                      <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '7'); ?></td>
                    </tr>
                  </table>
				  
				</div>
				<b class="rbottom"><b class="r4"></b> <b class="r3"></b> <b class="r2"></b> <b class="r1" style="border-top:2px solid #000;"></b></b>
			  </div>
			
            </td>
			
          </tr>
        </table>
        </TD>
      </tr>
	  <!--
      <tr>
        <TD COLSPAN="2"><?php echo tep_draw_separator('pixel_trans.gif', '100', '15'); ?></td>
      </tr>-->
      <tr>
        <TD COLSPAN="2">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>           
            <td>
            
			    <div class="container">
				 <b class="rtop"><b class="r1" style="border-bottom:2px solid #000;"></b> <b class="r2"></b> <b class="r3"></b> <b class="r4"></b></b>	 
				 <div class="inner_div">
				 
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="main">
                  <tr>
                   <?php  if($order->info['orders_status_number']!=100006) {  ?> 
					<td>&nbsp;<b><?php echo PRINT_INVOICE_ORDERNR; ?></b><br> <?php echo tep_db_input($oID); ?></td>
                    <td>&nbsp;<b><?php echo ENTRY_DATE_PURCHASED; ?></b><br><?php echo tep_date_aus_format($order->info['date_purchased'],"short",$languages_id); ?></td>
					<td>&nbsp;<b><?php echo "Due date"; ?></b><br><?php echo tep_date_aus_format($order->info['due_date'],"short",$languages_id); ?></td>
                    <?php } else { ?>					
                    <td>&nbsp;<b><?php echo "Invoice date"; ?></b><br><?php echo tep_date_aus_format($order->info['last_modified'],"short",$languages_id); ?></td>
					<?php } ?>
                    <td>&nbsp;<b><?php echo ENTRY_PURCHASE_NUMBER; ?></b><br><?php echo $order->info['purchase_number']; ?></td>
                    <td>&nbsp;<b><?php echo ENTRY_PAYMENT_METHOD; ?></b><br><?php echo $order->info['payment_method']; ?></td>                                    
                  </tr>                                    
                </table>
				
				</div>
				<b class="rbottom"><b class="r4"></b> <b class="r3"></b> <b class="r2"></b> <b class="r1" style="border-top:2px solid #000;"></b></b>
			  </div>
				
            </td>
          </tr>
        </table>
        </td>
      </tr>
	  <!--
      <tr>
        <TD COLSPAN="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
      </tr>
      <tr>
        <TD COLSPAN="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>-->
      <tr>
        <TD COLSPAN="2" style='padding-top:4px;'><table border="1" bordercolor="#CCCCCC" width="100%" cellspacing="0" cellpadding="5">
            <tr class="dataTableHeadingRow">
              <td class="main" style="font-weight:bold;" align="center"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
              <td class="dataTableHeadingContent" style="font-weight:normal;" align="center"><?php echo TABLE_HEADING_BASE_PRICE; ?></td>
              <td class="dataTableHeadingContent" style="font-weight:bold;" align="center"><?php echo TABLE_HEADING_QUANTITY; ?></td>
			   <td class="dataTableHeadingContent" style="font-weight:normal;" align="center"><?php echo TABLE_HEADING_PRICE_BREAK_EXCLUDING_TAX; ?></td>
              <td class="dataTableHeadingContent" style="font-weight:normal;" align="center"><?php echo TABLE_HEADING_TAX; ?></td>             
              <td class="dataTableHeadingContent" style="font-weight:normal;" align="center"><?php echo TABLE_HEADING_PRICE_BREAK_INCLUDING_TAX; ?></td>
              <td class="dataTableHeadingContent" style="font-weight:bold;" align="right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
              <td class="dataTableHeadingContent" style="font-weight:bold;" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
            </tr>
            <?php 
   for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
 /*echo '      <tr class="dataTableRow">' . "\n" .
      '        <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
 */     
 echo '      <tr class="dataTableRow">' . "\n" .
 	  '        <td class="dataTableContent" valign="top">' . $order->products[$i]['name'];

     if (isset($order->products[$i]['attributes']) && (($k = sizeof($order->products[$i]['attributes'])) > 0)) {
       for ($j = 0; $j < $k; $j++) {
         echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
         if ($order->products[$i]['attributes'][$j]['price'] != '0') echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
         echo '</i></small></nobr>';
       }
     }
	
	//Modified Sep, 09, 2010
	if($order->products[$i]['product_original_final_price'] > 0) {
		$product_original_price = $order->products[$i]['product_original_final_price'];
	} else {
		$product_original_price = $order->products[$i]['final_price'];
	}
	
     echo '          </td>' . "\n" .
           '        <td class="dataTableContent" valign="top" align="center">' . $currencies->format($product_original_price, true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n";
      echo '        <td class="dataTableContent" align="center" valign="top">' . tep_display_tax_value($order->products[$i]['qty']) . '</td>' . "\n" .
	  '        <td class="dataTableContent" align="center" valign="top">' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
	 	   ' 		<td class="dataTableContent" align="center" valign="top">' . tep_display_tax_value($order->products[$i]['tax_rate']) . '%</td>' . "\n" .           
           '        <td class="dataTableContent" align="center" valign="top">' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax_rate']), true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top">' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
		   
           '        <td class="dataTableContent" align="right" valign="top">' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax_rate']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n";
     echo '         </tr>' . "\n";
   }
?>
            <tr>
				<td colspan="5" align="left" valign="middle">
						<?php
						if($order->info["price_break_amount"]>0) {
							?>
							<p style="font-weight:bold;" class="main">
								Name Badges International Price Break Discount : <span class="style2"><font style="color:#0069D2;">you save $<?php echo number_format($order->info["price_break_amount"],2); ?></font></span>	<br>
				  <br></p>
						<?php } ?>
			  </td>
              <td align="right" colspan="4"><table border="0" cellspacing="0" cellpadding="3">
                  <?php
 for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
   echo '         <tr>' . "\n" .
        '          <td align="right" class="smallText">' . $order->totals[$i]['title'] . '</td>' . "\n" .
        '          <td align="right" class="smallText">' . $order->totals[$i]['text'] . '</td>' . "\n" .
        '         </tr>' . "\n";
 }
          ?>
                </table></td>
            </tr>
          </table></td>
      </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
      <tr align="center">
        <td align="center"><b><font color="#FF6600">Name Badges International thanks you for your business</font></b></td>
      </tr>
	  <tr><td ><hr size="2"></td></tr>	
	  <tr>
	    <td align="center"  class="dataTableContent">
		For Payments over the Phone (Credit Card). Simply <span style="font-weight: bold;">Call 1300 267 074</span> and follow the prompts. <br> <span style="font-weight: bold;">Biller ID: 460287&nbsp; - Reference Number: Our Order/Invoice Number</span><br> -------------------------------------------------------------------------------------------------<br>
		For bank Transfer, please use the following details with THE ORDER NUMBER as reference to transfer your total order value:<BR>
	      <strong>Account Name: Name Badges International Pty Ltd Account Number: BSB 034081 - Account# 263974<br>
        Bank Name: Westpac Acacia Ridge, Brisbane</strong></td>
      </tr>
	  <tr>
	    <td align="center"  class="dataTableContent">----------------------------------------------------------------------------------</td>
	  </tr>
	  <tr><td align="center"  class="dataTableContent">Name Badges International Pty Ltd - The Professional Choice in Personalised Name Badges</td>
	  </tr>
	  <tr>
	    <td align="center" class="dataTableContent">Phone: 02 8003 5046&nbsp;| Suite F Level 1 Octagon, 110 George Street  Parramatta, NSW 2150 Australia</td>
	  </tr>
	  <tr><td align="center" class="dataTableContent">&copy; Copyright 2011 Name Badges International Pty Ltd.&nbsp;|&nbsp;ABN:&nbsp;60 149 490 406</td>
	  </tr>
	  <tr><td align="center" class="dataTableContent">http://www.namebadgesinternational.com.au</td></tr>	  
    </table>
    <!-- body_text_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
