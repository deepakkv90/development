<?php 

// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('checkoutpayment', 'top');
// RCI code eof

echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'), 'post', 'onsubmit="return check_form();"'); 

?>

<h1><?php echo HEADING_TITLE; ?></h1>

<?php

  if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message'])) {

    $sql_data_array = array('orders_id' =>  $order_id1,
                           'orders_status_id' => '0',
                           'date_added' => 'now()',
                           'customer_notified' => '0',
                           'comments' => $HTTP_GET_VARS['error_message']);
   tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
  
  }
  
  if (isset($HTTP_GET_VARS['payment_error']) && is_object(${$HTTP_GET_VARS['payment_error']}) && ($error = ${$HTTP_GET_VARS['payment_error']}->get_error())) {
?>
      <div class="content">
		<?php echo tep_output_string_protected($error['title']); ?>
		<span class="error"><?php echo tep_output_string_protected($error['error']); ?></span>
	  
	  </div>
<?php
  }
?>

	<!--<h2><?php echo TABLE_HEADING_BILLING_ADDRESS; ?></h2>-->
	<div class="content">
		<div class="left">
			<h3><?php echo TITLE_BILLING_ADDRESS; ?></h3>
			<?php echo tep_address_label($_SESSION['customer_id'], $_SESSION['billto'], true, ' ', '<br>'); ?>
		</div>
		<div class="right">
			<?php echo TEXT_SELECTED_BILLING_DESTINATION; ?><br><br><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '">Change</a>'; ?>
		</div>
	</div>
	
<?php

// beginning of the coupon redemption code
  if ($order_total_modules->credit_selection()!='' ) {
  
  ?>
     <div class="content">
		<h3><?php echo TABLE_HEADING_CREDIT; ?></h3>
		 <?php echo $order_total_modules->credit_selection(); ?>
	 </div>
<?php
  }
// End of the coupon redemption code

?>

<div class="content text-center">
	
	<h2><?php echo TABLE_HEADING_PAYMENT_METHOD; ?></h2>
	
	<?php 
		// RCO start 
		if ($cre_RCO->get('checkoutpayment', 'paymentmodule') !== true) { 
			
			$selection = $payment_modules->selection();
			if (sizeof($selection) > 1) {
				echo TEXT_SELECT_PAYMENT_METHOD;
			} else {
				echo TEXT_ENTER_PAYMENT_INFORMATION;
			}
			
			echo '<div class="product-grid">';
			$radio_buttons = 0;
			for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
				echo '<div>';
					echo '<div class="name">'.$selection[$i]['module'].'</div>';
					
					if (isset($selection[$i]['error'])) {
					
						echo '<div class="error">'.$selection[$i]['error'].'</div>';
						
					} elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) {
						
						for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {
							
							echo '<table><tr><td class="main">'.$selection[$i]['fields'][$j]['title'].'</td>                        
							<td class="main">'.$selection[$i]['fields'][$j]['field'].'</td></tr></table>';
							
						}
						
					}
					
					echo '<div class="cart">';
					if (sizeof($selection) > 1) {
						  if (tep_session_is_registered('payment') && $payment == $selection[$i]['id']) {
							echo tep_draw_radio_field('payment', $selection[$i]['id'], true);
						  } elseif (!tep_session_is_registered('payment') && $i == 0) {
							echo tep_draw_radio_field('payment', $selection[$i]['id'], true);
						  } else {
							echo tep_draw_radio_field('payment', $selection[$i]['id']);
						  }
					} else {
					  echo tep_draw_hidden_field('payment', $selection[$i]['id']);
					}
					echo '</div>';
					
				echo '</div>';
				
				$radio_buttons++;
				
			}
			echo '</div>';
		} 

	// RCI code start
	echo $cre_RCI->get('checkoutpayment', 'billingtableright');
	// RCI code eof 		
	?>
	
	


</div>	


<div class="content text-center">
	<h2><?php echo TABLE_HEADING_COMMENTS; ?></h2>
	<textarea name="comments"></textarea>
</div>

<div class="content text-center">
	<h2><?php echo TABLE_HEADING_PURCHASE_NUMBER; ?></h2>
	<?php echo tep_draw_input_field('purchase_number'); ?>
</div>

<div class="content text-center">
	<h2><?php echo TITLE_CONTINUE_CHECKOUT_PROCEDURE; ?></h2>
	<p><?php echo TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?> </p>
	<input type="submit" class="button" value="Continue"/>
</div>      

		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%" align="right"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table></td>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table></td>
            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_DELIVERY . '</a>'; ?></td>
            <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_PAYMENT; ?></td>
            <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
            <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
          </tr>
        </table>
		
	</form>
<?php
// RCI code start
echo $cre_RCI->get('checkoutpayment', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>