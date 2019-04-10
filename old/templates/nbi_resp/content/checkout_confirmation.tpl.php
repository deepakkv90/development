<?php
/*
  $Id: checkout_confirmation.tpl.php,v 1.0.0.0 2008/01/16 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/ 
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('checkoutconfirmation', 'top');
// RCI code eof

?>    

<h1><?php echo HEADING_TITLE; ?></h1>


<div class="content">
	
	<?php if (sizeof($order->info['tax_groups']) > 1) {
		
		echo '<h2>' . HEADING_PRODUCTS . ' <a href="' . tep_href_link(FILENAME_SHOPPING_CART, "", "SSL") . '">(' . TEXT_EDIT . ')</a></h2>';
		 
	  } else {
		
		echo '<h2>' . HEADING_PRODUCTS . ' <a href="' . tep_href_link(FILENAME_SHOPPING_CART, "", "SSL") . '">(' . TEXT_EDIT . ')</a></h2>';
		 
	  }?>
	
	<div class="cart-info">
	
	<table>
	  <thead>
		<tr>
		  
		  <td class="name">Products name</td>
		  <td class="quantity">Quantity</td>
		  <td class="price">Unit Price</td>
		  <td class="total">Subtotal</td>
		</tr>
	  </thead>
	  <tbody>
	  
	  
	  <?php
	  
	  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
		  
			    
			$product_row = tep_db_query("select default_product_id, badge_data, default_texts from products where products_id = ".$order->products[$i]['id']);
			$product_arr = tep_db_fetch_array($product_row);
			$dprid = $product_arr['default_product_id'];
			
			$products_name = $order->products[$i]['name'];
			
			if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
			  for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
				$products_name .= '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . ' ' . $order->products[$i]['attributes'][$j]['prefix'] . ' ' . $currencies->display_price($order->products[$i]['attributes'][$j]['price'], tep_get_tax_rate($products[$i]['tax_class_id']), 1)  . '</i></small></nobr>';
			  }
			}
		
			
			if ($dprid) {
				
				//$badge_short_desc = tep_badge_short_desc($product_arr['default_texts']);				

				$products_name .= '<div class="badge-desc">'.$badge_short_desc . tep_get_badge_products_options($order->products[$i]['id']) .'</div>';
				
				//$badge_long_desc = tep_badge_long_desc($product_arr['badge_data']);
				
				//print_r($badge_long_desc);

			} 

		echo '<tr>' . "\n" .
			 '<td class="name">' . $products_name .'</td>
			  <td class="quantity">' . $order->products[$i]['qty'];
			
				if (STOCK_CHECK == 'true') {
				  echo tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
				} 
				
			 echo '</td><td class="price">'.$currencies->display_price($order->products[$i]['final_price']).'</td>';
		  
		     echo '<td class="total">' . $currencies->display_price($order->products[$i]['final_price']*$order->products[$i]['qty']) . '</td>';
		echo '</tr>';	 
		
	  }
	  ?>
	
	
	</tbody>
	</table>
    </div>	
	
	
	<?php if($order->info["price_break_amount"]>0) { ?>
		<p style="font-weight:bold;">
			<?php echo STORE_NAME; ?> Price Break Discount : you save $<?php echo number_format($order->info["price_break_amount"],2); ?>
		</p>
	<?php } ?>
		
			
</div>

<?php if ($sendto != false) { ?>
<div class="content">
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <?php
                         
                echo '<td class="main"><b>' . HEADING_DELIVERY_ADDRESS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a></td>' . "\n";
           
            
              ?>
            </tr>
            <tr>
              <td><?php echo tep_address_label($_SESSION['customer_id'], $_SESSION['billto'], true, ' ', '<br>'); ?></td>
            </tr>
            <?php
            if ($order->info['shipping_method']) {
              ?>
              <tr>
                <?php
               
                  echo '<td class="main"><b>' . HEADING_SHIPPING_METHOD . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a></td>' . "\n";
            
                ?>
              </tr>
              <tr>
                <td class="main"><?php echo $order->info['shipping_method']; ?></td>
              </tr>
              <?php
            }
            ?>
          </table>
</div>
<?php } ?>

<div class="content">
	<table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
      <tr class="infoBoxContents">
        <td width="30%" valign="top">
		
		
			<?php
                     
              echo '<h2>'.HEADING_BILLING_ADDRESS . '</h2> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '">(' . TEXT_EDIT . ')</a>';
            
            ?>
		
		
		<?php echo tep_address_label($_SESSION['customer_id'], $_SESSION['billto'], true, ' ', '<br>'); ?>
		
		</td>
        <td width="70%" valign="top" align="right"><table border="0" cellspacing="0" cellpadding="2">
          <?php
          if (MODULE_ORDER_TOTAL_INSTALLED) {
            $order_total_modules->process();			
            echo $order_total_modules->output();
          }
        
		  
          ?>
        </table>	
		</td>
      </tr>
    </table>
</div>

<div class="content highlight">

            <?php
            // RCO start
            if ($cre_RCO->get('checkoutconfirmation', 'editpaymentmethodlink') !== true) {               
             echo '<div class="left"><h2>' . HEADING_PAYMENT_METHOD . '</h2></div> <div class="right text-right"><a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '">' . TEXT_EDIT . '</a></div>' . "\n";
            }
            // RCO end
            ?>
			<div class="clear"></div>
           <?php echo "<p class='caps'>".$order->info['payment_method']."</p>"; ?>
			
			
			<?php
			  if (is_array($payment_modules->modules)) {
				
				if ($confirmation = $payment_modules->confirmation()) {
				  $payment_info = $confirmation['title'];
				  if (!isset($_SESSION['payment_info'])) $_SESSION['payment_info'] = $payment_info;
				  
				  echo $confirmation['title']."<br/>";
				  
				  for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
					
					  echo $confirmation['fields'][$i]['title'].": " . $confirmation['fields'][$i]['field']."<br/>";
					  
				  }
				  
				}
				
			  }
			
			?>
			

</div>

<?php if (tep_not_null($order->info['due_date'])) { ?>
<div class="content highlight">
	<?php echo '<div class="left"><h2>Due Date </h2></div> <div class="right text-right"><a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . TEXT_EDIT . '</a></div>'; ?>
	<div class="clear"></div>
	<?php echo "<p class='caps'>" . tep_date_aus_format($order->info['due_date'],"short") . tep_draw_hidden_field('due_date', $order->info['due_date'])."</p>"; ?>
</div>
<?php } ?>

<?php if (tep_not_null($order->info['comments'])) { ?>
<div class="content highlight">
	
	<?php echo '<div class="left"><h2>' . HEADING_ORDER_COMMENTS . ' </h2></div> <div class="right text-right"><a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '">' . TEXT_EDIT . '</a></div>'; ?>
	<div class="clear"></div>
	
	<?php echo nl2br(tep_output_string_protected($order->info['comments'])) . tep_draw_hidden_field('comments', $order->info['comments']); ?>
</div>
<?php } ?>

<?php if (tep_not_null($order->info['purchase_number'])) { ?>
<div class="content highlight">
	
	<?php echo '<div class="left"><h2>' . HEADING_PURCHASE_NUMBER . ' </h2></div> <div class="right text-right"><a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '">' . TEXT_EDIT . '</a></div>'; ?>
	<div class="clear"></div>

	<?php echo nl2br(tep_output_string_protected($order->info['purchase_number'])) . tep_draw_hidden_field('purchase_number', $order->info['purchase_number']); ?>
</div>
<?php } ?>

<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">

  <?php
  //RCI start
  echo $cre_RCI->get('checkoutconfirmation', 'insideformabovebuttons');
  //RCI end
  ?>  
  
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
      <tr class="infoBoxContents">
        <td>
          <?php
          if (isset($$payment->form_action_url)) {
            $form_action_url = $$payment->form_action_url;
          } else {
            $form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
          }
          if (ACCOUNT_CONDITIONS_REQUIRED == 'false' ) {
            echo tep_draw_form('checkout_confirmation', $form_action_url, 'post','');
          } else {
            echo tep_draw_form('checkout_confirmation', $form_action_url, 'post','onsubmit="return checkCheckBox(this)"');
          }
          if (is_array($payment_modules->modules)) {
            echo $payment_modules->process_button();
          }
          ?>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <?php
            if (ACCOUNT_CONDITIONS_REQUIRED == 'true') {
              ?>
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" colspan="2"><?php echo CONDITION_AGREEMENT; ?> <input type="checkbox" value="0" name="agree"></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
              <?php
            }
            ?>
            <tr>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              <td class="main"><b><?php echo TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></td>
              <td class="main" align="right">
				<input type="submit" value="Confirm Order" class="button">

			  </td>       
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            </tr>       
            <tr>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              <td class="main">
                <?php 
                echo HEADING_IPRECORDED_1;
                $ip_iprecorded = YOUR_IP_IPRECORDED;
                $isp_iprecorded = YOUR_ISP_IPRECORDED;
                $ip = $_SERVER["REMOTE_ADDR"];
                $client = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
                $str = preg_split("/\./", $client);
                $i = count($str);
                $x = $i - 1;
                $n = $i - 2;
                $isp = $str[$n] . "." . $str[$x]; 
                echo "<div align=\"justify\"><font size=\".1\">$ip_iprecorded: $ip<br>$isp_iprecorded: $isp</div>"; 
                ?>
              </td>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            </tr>
            <?php
            //RCI start
            echo $cre_RCI->get('checkoutconfirmation', 'insideformbelowbuttons');
            //RCI end
            ?>
          </table></form>
        </td>
      </tr>
    </table></td>
  </tr>

  <?php
  //RCI start
  echo $cre_RCI->get('checkoutconfirmation', 'menu');
  //RCI end
  // RCO start
  if ($cre_RCO->get('checkoutconfirmation', 'checkoutbar') !== true) {               
    ?>
    <tr>
      <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td width="50%" align="right"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
              <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            </tr>
          </table></td>
          <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
          <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              <td><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
              <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            </tr>
          </table></td>
          <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_DELIVERY . '</a>'; ?></td>
          <td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_PAYMENT . '</a>'; ?></td>
          <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
          <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
        </tr>
      </table></td>
    </tr>
    <?php
  }
  // RCO end
  ?>
</table>
<?php 
// RCI code start
echo $cre_RCI->get('checkoutconfirmation', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>