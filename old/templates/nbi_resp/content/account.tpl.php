    <?php
    // RCI code start
    echo $cre_RCI->get('global', 'top');
    echo $cre_RCI->get('account', 'top');
    // RCI code eof
    ?>
	
	<h1><?php echo HEADING_TITLE; ?></h1>
	
	<?php if ($messageStack->size('account') > 0) { ?>
        <div class="content"><?php echo $messageStack->output('account'); ?></div>
    <?php } ?>
	
    
	<!-- New design start -->
	  
        <div class="content">
			
			
			<?php
				//print_r($cart);
				//echo "<br>";
				//print_r($cart->get_products());
			?>
			<table align="center" border="0" width="100%">
				<tr>
					<td valign="bottom">
						<?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_MYFILES, '', 'SSL').'">' . tep_image('images/myfiles.gif', IMAGE_MYFILES).'</a>'; ?>
					</td>
					<td valign="bottom">
						<?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_ARTWORKS, '', 'SSL').'">' . tep_image('images/artwork.gif', IMAGE_ARTWORK).'</a>'; ?>
					</td>
					<td valign="bottom">
						<?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL').'">' . tep_image('images/myaccount.gif', IMAGE_ACCOUNT).'</a>'; ?>
					</td>
				</tr>
			</table>
			
		</div>
      
	  <!-- Customer information -->
	  <?php $customer_info = tep_get_customer_info(); 
	  	if($customer_info['entry_company_tax_id']!="") {
			$cust_number = $customer_info['entry_company_tax_id'];
		} else {
			$cust_number = $customer_info['customers_id'];
		}
		
		//print_r($customer_info);
		//Last customer order
		$sel_last_ord = tep_db_query("select orders_id, date_purchased, due_date from orders where customers_id='".(int)$_SESSION['customer_id']."' ORDER BY date_purchased DESC
LIMIT 0 , 1");
		$last_ord_info = tep_db_fetch_array($sel_last_ord);
		//print_r($last_ord_info);
		
		
	  ?>

	  	<div class="content">
			<table align="center" width="100%" cellspacing="0" cellpadding="2">
				<tr class="tbhead">
					<td class="bottom-ln" align="center">Customer No.</td>
					<td class="bottom-ln left-ln" align="center">Company Name</td>
					<td class="bottom-ln left-ln" align="center">Customer Name</td>
					<td class="bottom-ln left-ln" align="center">Account Created</td>
					<td class="bottom-ln left-ln" align="left" style="padding:5px">Last Order</td>				
				</tr>
				<tr >
					<td align="center"><?php echo $cust_number; ?></td>
					<td class="left-ln" align="center"><?php echo $customer_info['entry_company']; ?></td>
					<td class="left-ln" align="center"><?php echo $customer_info['entry_firstname']." ".$customer_info['entry_lastname']; ?></td>
					<td class="left-ln" align="center"><?php echo tep_date_aus_format($customer_info['customers_info_date_account_created'],"short"); ?></td>
					<td class="left-ln" align="left" style="padding:5px"><?php echo "#".$last_ord_info['orders_id']." <br> ".tep_date_aus_format($last_ord_info['date_purchased']); ?></td>				
				</tr>
			</table>
		</div>

	  
	  
	  <?php if (tep_count_customer_orders() > 0) { ?>
	  
	  

        <div class="content">
			
			<h1>My Order History &nbsp;&nbsp;&nbsp;<?php echo '<a style="font-size:11px;" href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '"><u>( Show All ) </u></a>'; ?></h1>
			
			<table align="center" width="100%" cellspacing="0" cellpadding="2">
				<tr class="tbhead">				  
					<td width="28%">Products</td>
					<td width="8%">Order#</td>
					<td width="10%">Order Date</td>
					<td width="10%">Due Date</td>
					<td width="10%">Total</td>
					<td width="10%">Status</td>
					<td width="24%">&nbsp;</td>
				</tr>
				<?php
                  $orders_query = tep_db_query("select o.orders_id, o.date_purchased, o.due_date, o.delivery_name, o.delivery_country, o.billing_name, o.billing_country, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' order by orders_id desc limit 3");
                  while ($orders = tep_db_fetch_array($orders_query)) {
                    if (tep_not_null($orders['delivery_name'])) {
                      $order_name = $orders['delivery_name'];
                      $order_country = $orders['delivery_country'];
                    } else {
                      $order_name = $orders['billing_name'];
                      $order_country = $orders['billing_country'];
                    }
					
					//require(DIR_WS_CLASSES . 'order.php');
  					$order = new order($orders['orders_id']);
					
					for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    					$sql_txt = 'select products.*, products_to_categories.categories_id as cat_id from products inner join products_to_categories on products_to_categories.products_id = products.products_id where products.products_id = '.(int)$order->products[$i]['id'];    
						$query = tep_db_query($sql_txt);
						$result_sql = mysql_fetch_array($query); 
  
						?>
					   
						<tr>                      
						  <td class="box box-top-right" align="center">
							<?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders['orders_id'], 'SSL') . '"><img src="image_thumb.php?file='.DIR_WS_IMAGES . $result_sql['products_image'] .'&sizex=150&sizey=150" alt="'.$result_sql['products_name'].'"><br><br>'.$order->products[$i]['name'].'</a>'; ?>
						  </td>
						  <td align="center" class="box box-top-right">
						  	<?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders['orders_id'], 'SSL') . '">'.$orders['orders_id'].'</a>'; ?>
						  </td>
						  <td class="box box-top-right" align="center"> <?php echo tep_date_aus_format($orders['date_purchased'],"short"); ?> </td>
						  <td class="box box-top-right" align="center"> <?php echo tep_date_aus_format($orders['due_date'],"short"); ?> </td>
						  <td class="box box-top-right" align="center"> <?php echo $orders['order_total']; ?> </td>
						  <td class="box box-top-right" align="center"> <?php echo $orders['orders_status_name']; ?> </td>
						  <td class="box box-top" align="center">
							<?php 
							echo '<a class="button" href="'.tep_href_link('index.php','cPath='.$result_sql['cat_id'].'&product_id='.$result_sql['products_id'].'&action=re-order&osCsid='.$_GET['osCsid']).'">Re-order</a>';
							
							echo '&nbsp;&nbsp;<a class="button" href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $orders['orders_id'], 'SSL') . '"TARGET="_blank">Invoice</a>'; ?>
						  </td>
						</tr>
						<?php
					 }
				  
				  }
                  ?>				
			</table>
		</div>

	  
	  <?php } ?>
	  
	  <!-- New design end -->
	  

    <?php
    // RCI code start
    echo $cre_RCI->get('account', 'menu');
    // RCI code eof

    ?>
  
  <?php
  // RCI code start
  echo $cre_RCI->get('account', 'bottom');
  echo $cre_RCI->get('global', 'bottom');
  // RCI code eof
  ?>