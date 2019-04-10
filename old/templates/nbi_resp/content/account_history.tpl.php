<?php
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('accounthistory', 'top');
  // RCI code eof    
  
  $orders_total = tep_count_customer_orders();
  
  ?>
  
  <h1><?php echo HEADING_TITLE; ?></h1>
  
  <div class="content">
	<?php if (tep_count_customer_orders() > 0) { ?>
	
	
	<table align="center" width="100%" cellspacing="0" cellpadding="2">
		<tr class="tbhead">				  
			<td width="28%">Products</td>
			<td width="8%">Order#</td>
			<td width="10%">Order Date</td>
			<td width="8%">Due Date</td>
			<td width="10%">Total</td>
			<td width="10%">Status</td>
			<td width="24%">&nbsp;</td>
		</tr>
		<?php
		  //$orders_query = tep_db_query("select o.orders_id, o.date_purchased, o.delivery_name, o.delivery_country, o.billing_name, o.billing_country, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' order by orders_id desc");
		  $history_query_raw = "select o.orders_id, o.date_purchased, o.due_date, o.delivery_name, o.billing_name, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' order by orders_id DESC";
		  $history_split = new splitPageResults($history_query_raw, MAX_DISPLAY_ORDER_HISTORY);
		  $history_query = tep_db_query($history_split->sql_query);

		  while ($orders = tep_db_fetch_array($history_query)) {
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
						echo '&nbsp;&nbsp;<a class="button" href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $orders['orders_id'], 'SSL') . '"TARGET="_blank">Invoice</a>'; 
					?>
				  </td>
				</tr>
				<?php
			 }
		  
		  }
		  ?>				
	</table>
	
	<?php } else { echo TEXT_NO_PURCHASES;  } ?>
	
  </div>

  <?php
      // RCI code start
      echo $cre_RCI->get('accounthistory', 'menu');
      // RCI code eof    

    if ($orders_total > 0) {
    ?>
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td class="smallText" valign="top"><?php echo $history_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
          <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $history_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
        </tr>
    </table>
    <?php
    }
    ?>

	<table border="0" width="100%" cellspacing="0" cellpadding="2">
		<tr>
		  <td><?php echo '<a class="button" href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">Back</a>'; ?></td>
		</tr>
	</table>
	  
<?php
	// RCI code start
	echo $cre_RCI->get('accounthistory', 'bottom');
	echo $cre_RCI->get('global', 'bottom');
	// RCI code eof    
?>
