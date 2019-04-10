<?php
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('accounthistory', 'top');
  // RCI code eof    
  
  $orders_total = tep_count_customer_orders();
  
  ?>

<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_history.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
        </tr>
      </table></td>
  </tr>
    
  <tr>
      <td>
	  	
			<!-- New design -->
			 <?php if (tep_count_customer_orders() > 0) { ?>
			  <tr>
				<td>
					<table align="center" width="100%" cellspacing="0" cellpadding="2">
						<tr class="acc-header">				  
							<td width="28%" class="box box-bottom-right" style="padding:15px 0;" align="center">Products</td>
							<td width="8%" class="box box-bottom-right" style="padding:15px 0;" align="center">Order#</td>
							<td width="10%" class="box box-bottom-right" style="padding:15px 0;" align="center">Order Date</td>
							<td width="8%" class="box box-bottom-right" style="padding:15px 0;" align="center">Due Date</td>
							<td width="10%" class="box box-bottom-right" style="padding:15px 0;" align="center">Total</td>
							<td width="10%" class="box box-bottom-right" style="padding:15px 0;" align="center">Status</td>
							<td width="24%" class="box box-bottom" style="padding:15px 0;" align="center">&nbsp;</td>
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
									<?php echo '<a href="'.tep_href_link('index.php','cPath='.$result_sql['cat_id'].'&product_id='.$result_sql['products_id'].'&action=re-order&osCsid='.$_GET['osCsid']).'"><img src="images/button_reorder.gif" border="0"></a>' . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $orders['orders_id'], 'SSL') . '"TARGET="_blank">' . tep_template_image_button('button_invoice.gif', SMALL_IMAGE_BUTTON_INVOICE) . '</a>'; ?>
								  </td>
								</tr>
								<?php
							 }
						  
						  }
						  ?>				
					</table>
				</td>
			  </tr>
			  
			  <?php } else { echo TEXT_NO_PURCHASES;  }?>
		
	  </td>
  </tr> 
  
  <!--
  <tr>
  
  <td>
  <table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
      <td class="main" width="7%"><b>Order #</b></td>
      <td class="main" width="20%" align="center"><b>Order Date</b></td>
      <td class="main" width="20%" align="center"><b>Status</b></td>
      <td class="main"><b>Products</b></td>
      <td class="main" width="20%" align="center"><b>Order Cost</b></td>
      <td class="main" width="8%">&nbsp;</td>
      <td class="main" width="10%">&nbsp;</td>
	  <td class="main" width="10%">&nbsp;</td>
</tr>	  
  </table>
  </td>
  
  </tr>
  -->
  <?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>
  <?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
  <!--
  <tr>
    <td><?php
  

  if ($orders_total > 0) {
    $history_query_raw = "select o.orders_id, o.date_purchased, o.delivery_name, o.billing_name, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' order by orders_id DESC";
    $history_split = new splitPageResults($history_query_raw, MAX_DISPLAY_ORDER_HISTORY);
    $history_query = tep_db_query($history_split->sql_query);

    while ($history = tep_db_fetch_array($history_query)) {
      $products_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$history['orders_id'] . "'");
      $products = tep_db_fetch_array($products_query);

      if (tep_not_null($history['delivery_name'])) {
        $order_type = TEXT_ORDER_SHIPPED_TO;
        $order_name = $history['delivery_name'];
      } else {
        $order_type = TEXT_ORDER_BILLED_TO;
        $order_name = $history['billing_name'];
      }
?>
 		 <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td class="main" width="7%"><?php echo $history['orders_id']; ?></td>
          <td class="main" width="20%"><?php echo tep_date_long($history['date_purchased']); ?></td>
          <td class="main" width="20%" align="center"><?php echo $history['orders_status_name']; ?></td>
          <td class="main" align="center"><?php echo $products['count']; ?></td>
          <td class="main" width="20%" align="center"><?php echo strip_tags($history['order_total']); ?></td>
          <td class="main" width="8%"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&amp;' : '') . 'order_id=' . $history['orders_id'], 'SSL') . '">' . tep_template_image_button('small_view.gif', SMALL_IMAGE_BUTTON_VIEW) . '</a>'; ?></td>
          <td class="main" width="10%"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $history['orders_id'], 'SSL') . '"TARGET="_blank">' . tep_template_image_button('button_invoice.gif', SMALL_IMAGE_BUTTON_INVOICE) . '</a>'; ?></td>
		  <td class="main" width="10%"><?php echo '<a href="javascript:popupWindow(\'' .  tep_href_link(FILENAME_ORDERS_INVOICE, tep_get_all_get_params(array('order_id')) . 'oID=' . $history['orders_id'], 'NONSSL') . '\')">' . tep_template_image_button('button_printorder.gif', IMAGE_BUTTON_PRINT_ORDER) . '</a>'; ?></td
        ></tr>
      </table>
      
      <?php
    }
  } else {
?>
      <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td><table border="0" width="100%" cellspacing="2" cellpadding="4">
              <tr>
                <td class="main"><?php echo TEXT_NO_PURCHASES; ?></td>
              </tr>
            </table></td>
        </tr>
      </table>
      <?php
  }
?>
    </td>
  </tr>
  -->
  <?php
      // RCI code start
      echo $cre_RCI->get('accounthistory', 'menu');
      // RCI code eof    
      // BOF: Lango Added for template MOD
      if (MAIN_TABLE_BORDER == 'yes'){
        table_image_border_bottom();
      }
      // EOF: Lango Added for template MOD
      if ($orders_total > 0) {
        ?>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td class="smallText" valign="top"><?php echo $history_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
          <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $history_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
        </tr>
      </table></td>
  </tr>
  <?php
  }
?>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
  
  <td>
  <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
    <tr class="infoBoxContents">
      <td>
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
          <td><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
        </td>
        
        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
        </tr>
      </table>
    </td>
    
    </tr>
    
  </table>
  </td>
  
  </tr>
  
</table>
<?php
    // RCI code start
    echo $cre_RCI->get('accounthistory', 'bottom');
    echo $cre_RCI->get('global', 'bottom');
    // RCI code eof    
    ?>
