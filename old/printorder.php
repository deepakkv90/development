<?php
/*
  $Id: invoice.php,v 1.2 2004/03/13 15:09:11 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
require(DIR_FS_CLASSES . 'object_info.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ORDERS_INVOICE);
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
		if(!empty($cInfo->entry_company_tax_id)) {
			$customerNumber = $cInfo->entry_company_tax_id;
		} else {
			$customerNumber = $cInfo->customers_id;
		}
		
$payment_info_query = tep_db_query("select payment_info from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($order_id)) . "'");
$payment_info = tep_db_fetch_array($payment_info_query);
$payment_info = $payment_info['payment_info'];

$oID = tep_db_prepare_input($_GET['order_id']);
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
  <tr>
    <td valign="top" align="left" class="main">&nbsp;</td>
    <td align="right" valign="bottom" class="main"><p align="right" class="main">
        <script language="JavaScript">
            if (window.print) {
              document.write('<a href="javascript:;" onClick="javascript:window.print()" onMouseOut=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" onMouseOver=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage_over.gif'); ?>"><img src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" width="43" height="28" align="absbottom" border="0" name="imprim">' + '<?php echo IMAGE_BUTTON_PRINT; ?></a></center>');
            }
            else document.write ('<h2><?php echo IMAGE_BUTTON_PRINT; ?></h2>')
          </script>
        &nbsp;&nbsp;&nbsp;<a href="javascript:window.close();"><img src='images/close_window.jpg' border=0></a></p></td>
  </tr>
  <TD ALIGN="left" VALIGN="left"><?php echo tep_image(DIR_WS_IMAGES . 'nbi_logo.png', STORE_NAME); //echo tep_image(DIR_WS_IMAGES . STORE_LOGO, STORE_NAME);  ?> </TD>
    <td align="right"><?php echo tep_image(DIR_WS_IMAGES . 'free-call.png', STORE_NAME);  ?></td>
  </tr><tr>
    <td align="left">&nbsp;</td>
    <td align="right"><!-- sencot -->
      <FONT FACE="Verdana" SIZE="2" COLOR="#000000"> <?php echo "<br><br><b>". ENTRY_COMPANY_TAX_ID_NEW. "</b>&nbsp;" . $customerNumber.'<br>' ; ?> </strong></font>
      <!-- fin sencot -->
    </td>
  </tr>
  <tr>
    <td colspan="2">
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <TD>
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td width="10%"><hr size="2"></td>
                  <td align="center" class="pageHeading" width="37%">
				  	<?php  if($order->info['orders_status_number']!=100006) {  ?> 
						<em><b><?php echo PRINT_INVOICE_HEADING; ?></b></em>
					<?php } else { ?>
						<em><b><?php echo "Tax Invoice No. ".tep_db_input($oID); ?></b></em>
					<?php } ?>
				  </td>
                  <td width="100%"><hr size="2"></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif', '100', '5'); ?></td>
          </tr>
          <tr>
          
          <td width="3"></td>
          <!-- sencot fond ecran -->
          <td valign="top">
          
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="11"><?php echo tep_image(DIR_WS_IMAGES . 'borders/maingrey_01.gif', 'maingrey_01,gif', 11, 16); ?></td>
              <?php echo '<td background="' . DIR_WS_IMAGES . 'borders/maingrey_02.gif">' . tep_image(DIR_WS_IMAGES . 'borders/maingrey_02.gif', 'maingrey_02.gif', 24, 16); ?>
            </td>
            
            <td width="19"><?php echo tep_image(DIR_WS_IMAGES . 'borders/maingrey_03.gif', 'maingrey_03.gif', 19, 16); ?></td>
            </tr>
            <tr> <?php echo '<td background="' . DIR_WS_IMAGES . 'borders/maingrey_04.gif">' . tep_image(DIR_WS_IMAGES . 'borders/maingrey_04.gif', 'maingrey_04.gif', 11, 21); ?>
            </td>
            
            <td align="center" bgcolor="#F2F2F2"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="main">
                  <tr>
                    <td align="left" valign="top"><b><?php echo ENTRY_SOLD_TO; ?></b></td>
                  </tr>
                  <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
                  </tr>
                  <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, '', '<br>&nbsp;&nbsp;&nbsp;&nbsp;'); ?></td>
                  </tr>
                  <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                  </tr>
                  <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $order->customer['telephone']; ?></td>
                  </tr>
                  <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $order->customer['email_address']; ?></td>
                  </tr>
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '7'); ?></td>
                  </tr>
                </table></td>
              <?php echo '<td background="' . DIR_WS_IMAGES . 'borders/maingrey_06.gif">' . tep_image(DIR_WS_IMAGES . 'borders/maingrey_06.gif', 'maingrey_06.gif', 19, 21); ?>
            </td>
            
            </tr>
            
            <tr>
              <td><?php echo tep_image(DIR_WS_IMAGES . 'borders/maingrey_07.gif', 'maingrey_07,gif', 11, 18); ?></td>
              <?php echo '<td background="' . DIR_WS_IMAGES . 'borders/maingrey_08.gif">' . tep_image(DIR_WS_IMAGES . 'borders/maingrey_08.gif', 'maingrey_08.gif', 24, 18); ?>
            </td>
            
            <td><?php echo tep_image(DIR_WS_IMAGES . 'borders/maingrey_09.gif', 'maingrey_09,gif', 19, 18); ?></td>
            </tr>
          </table>
          </td>
          
          <td width="45"></td>
            <td valign="top">
            <!-- sencot fond ecran -->
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="11"><?php echo tep_image(DIR_WS_IMAGES . 'borders/mainwhite_01.gif', 'mainwhite_01,gif', 11, 16); ?></td>
                <?php echo '<td background="' . DIR_WS_IMAGES . 'borders/mainwhite_02.gif">' . tep_image(DIR_WS_IMAGES . 'borders/mainwhite_02.gif', 'mainwhite_02.gif', 24, 16); ?>
              </td>
              
              <td width="19"><?php echo tep_image(DIR_WS_IMAGES . 'borders/mainwhite_03.gif', 'mainwhite_03,gif', 19, 16); ?></td>
              </tr>
              <tr> <?php echo '<td background="' . DIR_WS_IMAGES . 'borders/mainwhite_04.gif">' . tep_image(DIR_WS_IMAGES . 'borders/mainwhite_04.gif', 'mainwhite_04.gif', 11, 21); ?>
                </td>
                <td align="center" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="main">
                    <tr>
                      <td align="left" valign="top"><b><?php echo ENTRY_SHIP_TO; ?></b></td>
                    </tr>
                    <tr>
                      <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
                    </tr>
                    <tr>
                      <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>&nbsp;&nbsp;&nbsp;&nbsp;'); ?></td>
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
                  </table></td>
                <?php echo '<td background="' . DIR_WS_IMAGES . 'borders/mainwhite_06.gif">' . tep_image(DIR_WS_IMAGES . 'borders/mainwhite_06.gif', 'mainwhite_06.gif', 19, 21); ?>
                </td>
              </tr>
              <tr>
                <td><?php echo tep_image(DIR_WS_IMAGES . 'borders/mainwhite_07.gif', 'mainwhite_07,gif', 11, 18); ?></td>
                <?php echo '<td background="' . DIR_WS_IMAGES . 'borders/mainwhite_08.gif">' . tep_image(DIR_WS_IMAGES . 'borders/mainwhite_08.gif', 'mainwhite_08.gif', 24, 18); ?>
                </td>
                <td><?php echo tep_image(DIR_WS_IMAGES . 'borders/mainwhite_09.gif', 'mainwhite_09,gif', 19, 18); ?></td>
              </tr>
            </table>
            </td>
          </tr>
        </table>
        </TD>
      </tr>
      <tr>
        <TD COLSPAN="2"><?php echo tep_draw_separator('pixel_trans.gif', '100', '15'); ?></td>
      </tr>
      <tr>
        <TD COLSPAN="2">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="9"></td>
            <td>
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="11"><?php echo tep_image(DIR_WS_IMAGES . 'borders/maingrey_01.gif', 'maingrey_01,gif', 11, 16); ?></td>
                <?php echo '<td background="' . DIR_WS_IMAGES . 'borders/maingrey_02.gif">' . tep_image(DIR_WS_IMAGES . 'borders/maingrey_02.gif', 'maingrey_02.gif', 24, 16); ?>
              </td>
              
              <td width="19"><?php echo tep_image(DIR_WS_IMAGES . 'borders/maingrey_03.gif', 'maingrey_03,gif', 19, 16); ?></td>
              </tr>
              <tr> <?php echo '<td background="' . DIR_WS_IMAGES . 'borders/maingrey_04.gif">' . tep_image(DIR_WS_IMAGES . 'borders/maingrey_04.gif', 'maingrey_04.gif', 11, 21); ?>
              </td>
              
              <td align="center" bgcolor="#F2F2F2">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="main">
                  <tr>
                    <?php  if($order->info['orders_status_number']!=100006) {  ?> 
					<td width="12%">&nbsp;<b><?php echo PRINT_INVOICE_ORDERNR; ?></b> <?php echo tep_db_input($oID); ?></td>
                    <td width="30%">&nbsp;<b><?php echo ENTRY_DATE_PURCHASED; ?></b><?php echo tep_date_aus_format($order->info['date_purchased'],"short"); ?></td>
					<td width="15%">&nbsp;<b><?php echo "Due Date: "; ?></b><?php echo tep_date_short($order->info['due_date']); ?></td>
                    <?php } else { ?>					
                    <td width="30%">&nbsp;<b><?php echo "Invoice Date:"; ?></b><?php echo tep_date_aus_format($order->info['last_modified'],"short"); ?></td>
					<?php } ?>
                    <td>&nbsp;<b><?php echo ENTRY_PURCHASE_NUMBER; ?></b>&nbsp;
                    <td><?php echo $order->info['purchase_number']; ?></td>
                    <td>&nbsp;<b><?php echo ENTRY_PAYMENT_METHOD; ?></b>&nbsp;
                    <td><?php echo $order->info['payment_method']; ?></td>
                  </td>
                  
                  </tr>
                  
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '7'); ?></td>
                  </tr>
                </table>
                </td>
                <?php echo '<td background="' . DIR_WS_IMAGES . 'borders/maingrey_06.gif">' . tep_image(DIR_WS_IMAGES . 'borders/maingrey_06.gif', 'maingrey_06.gif', 19, 21); ?>
                </td>
              </tr>
              <tr>
                <td><?php echo tep_image(DIR_WS_IMAGES . 'borders/maingrey_07.gif', 'maingrey_07,gif', 11, 18); ?></td>
                <?php echo '<td background="' . DIR_WS_IMAGES . 'borders/maingrey_08.gif">' . tep_image(DIR_WS_IMAGES . 'borders/maingrey_08.gif', 'maingrey_08.gif', 24, 18); ?>
                </td>
                <td><?php echo tep_image(DIR_WS_IMAGES . 'borders/maingrey_09.gif', 'maingrey_09,gif', 19, 18); ?></td>
              </tr>
            </table>
            </td>
          </tr>
        </table>
        </td>
      </tr>
      <tr>
        <TD COLSPAN="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
      </tr>
      <tr>
        <TD COLSPAN="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <TD COLSPAN="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr class="dataTableHeadingRow">
              <td class="dataTableHeadingContent" style="font-weight:normal;" align="center"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
              <td class="dataTableHeadingContent" style="font-weight:normal;" align="center"><?php echo TABLE_HEADING_BASE_PRICE; ?></td>
              <td class="dataTableHeadingContent" style="font-weight:normal;" align="center"><?php echo TABLE_HEADING_QUANTITY; ?></td>
			   <td class="dataTableHeadingContent" style="font-weight:normal;" align="center"><?php echo TABLE_HEADING_PRICE_BREAK_EXCLUDING_TAX; ?></td>
             <!--<td class="dataTableHeadingContent" style="font-weight:normal;" align="center"><?php //echo TABLE_HEADING_TAX; ?></td> -->            
              <td class="dataTableHeadingContent" style="font-weight:normal;" align="center"><?php echo TABLE_HEADING_PRICE_BREAK_INCLUDING_TAX; ?></td>
              <td class="dataTableHeadingContent" style="font-weight:normal;" align="right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
              <td class="dataTableHeadingContent" style="font-weight:normal;" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
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
	 	   //Modified Sep 15, 2010
		   //' 		<td class="dataTableContent" align="center" valign="top">' . tep_display_tax_value($order->products[$i]['tax_rate']) . '%</td>' . "\n" .           
           '        <td class="dataTableContent" align="center" valign="top">' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax_rate']), true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
           '        <td class="dataTableContent" align="right" valign="top">' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
		   
           '        <td class="dataTableContent" align="right" valign="top">' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax_rate']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n";
     echo '         </tr>' . "\n";
   }
?>
            <tr>
				<td align="center" colspan="4">
						<?php
						if($order->info["price_break_amount"]>0) {
							?>
							<p style="font-weight:bold;" class="main">
								Name Badges International Price Break Discount : <font style="color:#0099CC;">you save $<?php echo number_format($order->info["price_break_amount"],2); ?></font>	<br><br></p>
						<?php } ?>
			  </td>
              <td align="right" colspan="3"><table border="0" cellspacing="0" cellpadding="2">
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
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="main">
      <tr align="center">
        <td colspan="4" align="center"><b><font color="#FF6600">Name Badges International thanks you for your business</font></b></td>
      </tr>
	  <tr><td colspan="4"><hr size="2"></td></tr>
	  <tr>
	    <td align="center"  class="dataTableContent">&nbsp;</td>
      </tr>
	  <tr>
	    <td align="center"  class="dataTableContent">
		 For Payments over the Phone (Credit Card). Simply <span style="font-weight: bold;">Call 1300 267 074</span> and follow the prompts. <br> <span style="font-weight: bold;">Biller ID: 564864&nbsp; - Reference Number: your Order Number</span><br> -------------------------------------------------------------------------------------------------<br>
		<p>For bank Transfer, please use the following details with THE ORDER NUMBER as reference to transfer your total order value:</p>
	      <p><strong>Account Name: Name Badges International Pty Ltd Account Number: BSB 034081 - Account# 263974<br>
        Bank Name: Westpac Acacia Ridge, Brisbane</strong></p></td>
      </tr>
	  <tr>
	    <td align="center"  class="dataTableContent">----------------------------------------------------------------------------------</td>
	  </tr>
	  <tr><td align="center"  class="dataTableContent">Name Badges International &#38;&nbsp;Co Pty Ltd &#45;&nbsp;Badges&#44;&nbsp;Medallions&#44;&nbsp;Trophies&#44;&nbsp;Awards and Plastic Injection Moulding</td></tr>
	  <tr><td align="center" class="dataTableContent">Phone&#58; 1800 777 125&nbsp;&nbsp;&#45;&nbsp;&#43;617 3277 2111&nbsp;&#124;&nbsp;555 Tarragindi Road &#45; Salisbury &#44;QLD 4107&nbsp;&#8211;&nbsp;Australia</td></tr>
	  <tr><td align="center" class="dataTableContent">&#169; Copyright 2009 Name Badges International & Co Pty Ltd&#46;&nbsp;&nbsp;&#124;&nbsp;&nbsp;ABN&#58;&nbsp;61127091016</td></tr>
	  <tr><td align="center" class="dataTableContent">http://www.namebadgesinternational.com.au</td></tr>
	  <tr><td align="center">&nbsp;</td></tr>
	  <tr><td align="center">&nbsp;</td></tr>
    </table>
    <!-- body_text_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
