  <?php
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('accounthistoryinfo', 'top');
  // RCI code eof    
  ?>
  
  <h1><?php echo HEADING_TITLE; ?></h1>
  
  <div class="content">
  
	<h2><?php echo sprintf(HEADING_ORDER_NUMBER, $HTTP_GET_VARS['order_id']) . ' <small>(' . $order->info['orders_status'] . ')</small>'; ?></h2>
	<?php echo HEADING_ORDER_DATE . ' ' . tep_date_long($order->info['date_purchased']); ?><br/>
	<?php echo HEADING_ORDER_TOTAL . ' ' . $order->info['total']; ?>
	
  </div>
  
  <div class="content">
	<div class="left">
		<?php
		  if ($order->delivery != false) {
			
			echo "<h2>".HEADING_DELIVERY_ADDRESS."</h2>";
			echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>');
			
			if (tep_not_null($order->info['shipping_method'])) { 
				echo "<br/><br/><b>".HEADING_SHIPPING_METHOD."</b><br/>";
				echo $order->info['shipping_method'];
			}
			
		  } 
		?>
	</div>
	<div class="right">
		<h2><?php echo HEADING_BILLING_ADDRESS; ?></h2>
		<?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>'); ?>
	</div>
  </div>
  
  <div class="content">
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
		<?php
		  if (sizeof($order->info['tax_groups']) > 1) {
		?>
						  <tr>
							<td class="main" align="center"><b>QTY</b></td>
							<td class="main" align="center"><b><?php echo HEADING_PRODUCTS; ?></b></td>
							<td class="smallText" align="center"><b><?php echo HEADING_TAX; ?></b></td>
							<td class="smallText" align="center"><b><?php echo HEADING_TOTAL; ?></b></td>
						  </tr>
		<?php
		  } else {
		?>
						  <tr>
							<td class="main" align="center"><b>QTY</b></td>
							<td class="main" align="center"><b><?php echo HEADING_PRODUCTS; ?></b></td>
							<td class="main" align="center"><b><?php echo HEADING_PRODUCTS_BASE_PRICE; ?></b></td>
							<td class="main" align="center"><b><?php echo HEADING_PRODUCTS_FINAL_PRICE; ?></b></td>
						  </tr>
		<?php
		  }

		  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
			$sql_txt = 'select products.*, products_to_categories.categories_id as cat_id from products inner join products_to_categories on products_to_categories.products_id = products.products_id where products.products_id = '.(int)$order->products[$i]['id'];    
			$query = tep_db_query($sql_txt);
			$result_sql = mysql_fetch_array($query);    

			$dprid = $result_sql['default_product_id'];
			if ($dprid) {
			  require_once(dirname(dirname(__FILE__)).'/bd/badge_desc.php');
			  $badge = new Badge($result_sql['badge_data']); 
			  //For badge products attributes - Jan 03 2012
			  $bdopts = tep_get_badge_products_options($order->products[$i]['id']); // 
			  $badge_opt_desc = "";
			  if(!empty($bdopts)) {
				$badge_opt_desc = '<br><small><i>'.$bdopts.'</i></small>';
			  }
			  
			   echo '          <tr>' . "\n" .
					 '            <td class="main" align="right" valign="top" width="30">' . $order->products[$i]['qty'] . '&nbsp;</td>' . "\n" .
					 '            <td class="main" align="center" valign="top">' . $order->products[$i]['name'] . $badge_opt_desc . ' &nbsp; &nbsp; &nbsp; &nbsp;  <br />' . ((strlen(@$result_sql['products_image']) > 0)?'<img src="'. 'images/'. $result_sql['products_image'].'" />'.$badge->description():'') ;     
			
			} else {    
			   
			echo '          <tr>' . "\n" .
								 '            <td class="main" align="right" valign="top" width="30">' . $order->products[$i]['qty'] . '&nbsp;</td>' . "\n" .
								 '            <td class="main" align="center" valign="top">' . $order->products[$i]['name'] . ' &nbsp; &nbsp; &nbsp; &nbsp;  <br />' . ((strlen(@$result_sql['products_image']) > 0)?'<img src="'. 'images/'. $result_sql['products_image'].'" />'.'':'') ; 
			}

			$is_options = false;

		//attibute_query 
		//check for attibutes:

				  $attributes_check_query = tep_db_query("select *
					from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . "
				   where 
				   orders_id = '" .$order_id . "' and
				   orders_products_id = '" . $order->products[$i]['orders_products_id'] . "' ");
				if (tep_db_num_rows($attributes_check_query)) {
				  while ($attributes = tep_db_fetch_array($attributes_check_query)) {
					  if (!$is_options) {
						 echo '<br /><b>' . HEADING_OPTIONS  . '</b>';
						$is_options = true;
					  }
					  echo '<br><small><i> *' . $attributes['products_options'] . ' : ' . $attributes['products_options_values'] . '</i></small>';
					  echo '<br><small> ' . $attributes['price_prefix'] . ' ' . $currencies->display_price($attributes['options_values_price'], tep_get_tax_rate($result_sql['tax_class_id']), 1) . '</small>';

					}
				}

			
			echo '</td><td class="main" valign="top" align="center">';

			 if(isset($result_sql['tax_class_id'])) {
				echo $currencies->display_price($order->products[$i]['price'], tep_get_tax_rate($result_sql['tax_class_id']), 1);
			 }
			 
			echo '<a class="button" href="'.tep_href_link('index.php','cPath='.$result_sql['cat_id'].'&product_id='.$result_sql['products_id'].'&action=re-order&osCsid='.$_GET['osCsid']).'">Re-order</a>';
			
			echo '</td>' . "\n";
		 
			if (sizeof($order->info['tax_groups']) > 1) {
			  echo '            <td class="main" valign="top" align="center">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";
			}

			echo '            <td class="main" align="center" valign="top">' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
				 '          </tr>' . "\n";
		  }
		?>
     </table>
  </div>
  
  <div class="content text-right">
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
		<?php
		  for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {
			echo '              <tr>' . "\n" .
				 '                <td class="main" align="right" width="100%">' . $order->totals[$i]['title'] . '</td>' . "\n" .
				 '                <td class="main" align="right">' . $order->totals[$i]['text'] . '</td>' . "\n" .
				 '              </tr>' . "\n";
		  }
		?>
    </table>
  </div>
  
  <div class="content">
	<h2><?php echo HEADING_PAYMENT_METHOD; ?></h2>
	<?php echo $order->info['payment_method']; ?>
  </div>
  
  <div class="content">
	<h2>Due Date</h2>
	<?php echo tep_date_aus_format($order->info['due_date'],"short"); ?>
  </div>
  
  <div class="content">
	<h2><?php echo HEADING_ORDER_HISTORY; ?></h2>
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
		<?php
		  $statuses_query = tep_db_query("select os.orders_status_name, osh.date_added, osh.comments from " . TABLE_ORDERS_STATUS . " os, " . TABLE_ORDERS_STATUS_HISTORY . " osh where osh.orders_id = '" . (int)$HTTP_GET_VARS['order_id'] . "' and osh.customer_notified='1' and osh.orders_status_id = os.orders_status_id and os.language_id = '" . (int)$languages_id . "' order by osh.date_added");
		  while ($statuses = tep_db_fetch_array($statuses_query)) {
			echo '              <tr>' . "\n" .
				 '                <td class="main" valign="top" width="70">' . tep_date_short($statuses['date_added']) . '</td>' . "\n" .
				 '                <td class="main" valign="top" width="170">' . $statuses['orders_status_name'] . '</td>' . "\n" .
				 '                <td class="main" valign="top">' . (empty($statuses['comments']) ? '&nbsp;' : nl2br(tep_output_string_protected($statuses['comments']))) . '</td>' . "\n" .
				 '              </tr>' . "\n";
		  }
		?>
    </table>
  </div>
  	       
<?php
  // RCI code start
  echo $cre_RCI->get('accounthistoryinfo', 'menu');
  // RCI code eof    
?>
<?php
  if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . FILENAME_DOWNLOADS);
?>
		<div class="content"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td align="left" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, tep_get_all_get_params(array('order_id')), 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right" class="main"><?php echo '<a href="javascript:popupWindow(\'' .  tep_href_link(FILENAME_ORDERS_INVOICE, tep_get_all_get_params(array('order_id')) . 'oID=' . (int)$_GET['order_id'], 'NONSSL') . '\')">' . tep_template_image_button('button_printorder.gif', IMAGE_BUTTON_PRINT_ORDER) . '</a>'; ?></td>
                <td><?php require(DIR_WS_MODULES . 'payment/paypal/catalog/order_send_money.inc.php'); ?></td>
              </tr>
            </table>
		</div>
	
  <?php
  // RCI code start
  echo $cre_RCI->get('accounthistoryinfo', 'bottom');
  echo $cre_RCI->get('global', 'bottom');
  // RCI code eof    
  ?>