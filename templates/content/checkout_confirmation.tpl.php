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
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <?php
  if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
    $header_text = '&nbsp;'
    ?>
    <tr>
      <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_confirmation.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
        </tr>
      </table></td>
    </tr>

    <?php
  } else {
    $header_text = HEADING_TITLE;
  }
  if (MAIN_TABLE_BORDER == 'yes'){
    table_image_border_top(false, false, $header_text);
  }
  ?>
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
      <tr class="infoBoxContents">
        <?php
        if ($sendto != false) {
          ?>
          <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <?php
              // RCO start
              if ($cre_RCO->get('checkoutconfirmation', 'editdeliveryaddresslink') !== true) {              
                echo '<td class="main"><b>' . HEADING_DELIVERY_ADDRESS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a></td>' . "\n";
              }
              // RCO end
              ?>
            </tr>
            <tr>
              <td class="main"><?php echo tep_address_label($_SESSION['customer_id'], $_SESSION['billto'], true, ' ', '<br>');//tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>'); ?></td>
            </tr>
            <?php
            if ($order->info['shipping_method']) {
              ?>
              <tr>
                <?php
                // RCO start
                if ($cre_RCO->get('checkoutconfirmation', 'editshippingmethodlink') !== true) { 
                  echo '<td class="main"><b>' . HEADING_SHIPPING_METHOD . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a></td>' . "\n";
                }
                // RCO end
                ?>
              </tr>
              <tr>
                <td class="main"><?php echo $order->info['shipping_method']; ?></td>
              </tr>
              <?php
            }
            ?>
          </table></td>
          <?php
        }
        ?>
        <td width="<?php echo (($sendto != false) ? '70%' : '100%'); ?>" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <?php
              if (sizeof($order->info['tax_groups']) > 1) {
                ?>
                <tr>
                  <?php
                  // RCO start
                  if ($cre_RCO->get('checkoutconfirmation', 'editproductslink') !== true) {
                    echo '<td class="main" colspan="2"><b>' . HEADING_PRODUCTS . '</b> <a href="' . tep_href_link(FILENAME_SHOPPING_CART, "", "SSL") . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a></td>' . "\n";
                  }
                  // RCO end
                  ?>
                  <td class="smallText" align="right"><b><?php echo HEADING_TAX; ?></b></td>
                  <td class="smallText" align="right"><b><?php echo HEADING_TOTAL; ?></b></td>
                </tr>
                <?php
              } else {
                ?>
                <tr>
                  <?php
                  // RCO start
                  if ($cre_RCO->get('checkoutconfirmation', 'editproductslink') !== true) {
                    echo '<td class="main" colspan="2"><b>' . HEADING_PRODUCTS . '</b> <a href="' . tep_href_link(FILENAME_SHOPPING_CART, "", "SSL") . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a></td>' . "\n";
                  }
                  // RCO end
                  ?>                  
                </tr>
                <?php
              }
              for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
                echo '          <tr>' . "\n" .
                     '            <td class="main" align="right" valign="top" width="30">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
                     '            <td class="main" valign="top">' . $order->products[$i]['name'];
                if (STOCK_CHECK == 'true') {
                  echo tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
                } 
                if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
                  for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
                    echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . ' ' . $order->products[$i]['attributes'][$j]['prefix'] . ' ' . $currencies->display_price($order->products[$i]['attributes'][$j]['price'], tep_get_tax_rate($products[$i]['tax_class_id']), 1)  . '</i></small></nobr>';
                  }
                }
				
				//For badge products attributes - Jan 03 2012
				$bdopts = tep_get_badge_products_options($order->products[$i]['id']);
				if(!empty($bdopts)) {
					echo '<br><small><i>'.$bdopts.'</i></small>';
				}
				
                echo '            </td>' . "\n";
                if (sizeof($order->info['tax_groups']) > 1) echo '            <td class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";
				  echo '            <td class="main" align="right" valign="top">' . $currencies->display_price($order->products[$i]['final_price'], tep_get_tax_rate($order->products[$i]['tax_class_id']), $order->products[$i]['qty']) . '</td>' . "\n" .
                     '          </tr>' . "\n";
                /*echo '            <td class="main" align="right" valign="top">' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . '</td>' . "\n" .
                     '          </tr>' . "\n";*/
              }
              ?>
            </table></td>
          </tr>
        </table></td>
      </tr>
	  <tr><td>
	  
	  <!-- Modified Sep 08, 2010 to display Price Break Discount -->
		<?php
		if($order->info["price_break_amount"]>0) {
			?>
			<p style="font-weight:bold;">
				<?php echo STORE_NAME; ?> Price Break Discount : you save $<?php echo number_format($order->info["price_break_amount"],2); ?>		</p>
		<?php } ?>
	  
	  </td></tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td class="main"><b><?php echo HEADING_BILLING_INFORMATION; ?></b></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
      <tr class="infoBoxContents">
        <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <?php
            // RCO start
            if ($cre_RCO->get('checkoutconfirmation', 'editbillingaddresslink') !== true) {           
              echo '<td class="main"><b>' . HEADING_BILLING_ADDRESS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a></td>' . "\n";
            }
            // RCO end
            ?>
          </tr>
          <tr>
            <td class="main"><?php echo tep_address_label($_SESSION['customer_id'], $_SESSION['billto'], true, ' ', '<br>');//tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>'); ?></td>
          </tr>
          <tr>
            <?php
            // RCO start
            if ($cre_RCO->get('checkoutconfirmation', 'editpaymentmethodlink') !== true) {               
              echo '<td class="main"><b>' . HEADING_PAYMENT_METHOD . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a></td>' . "\n";
            }
            // RCO end
            ?>
          </tr>
          <tr>
            <td class="main"><?php echo $order->info['payment_method']; ?></td>
          </tr>
        </table></td>
        <td width="70%" valign="top" align="right"><table border="0" cellspacing="0" cellpadding="2">
          <?php
          if (MODULE_ORDER_TOTAL_INSTALLED) {
            $order_total_modules->process();			
            echo $order_total_modules->output();
          }
          // RCI code start
          echo $cre_RCI->get('checkoutconfirmation', 'display');
          // RCI code eof
		  
          ?>
        </table>	
		</td>
      </tr>
    </table></td>
  </tr>
  <?php
  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
      $payment_info = $confirmation['title'];
      if (!isset($_SESSION['payment_info'])) $_SESSION['payment_info'] = $payment_info;
      ?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo HEADING_PAYMENT_INFORMATION; ?></b></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" colspan="4"><?php echo $confirmation['title']; ?></td>
              </tr>
              <?php
              for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
                ?>
                <tr>
                  <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  <td class="main"><?php echo $confirmation['fields'][$i]['title']; ?></td>
                  <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  <td class="main"><?php echo $confirmation['fields'][$i]['field']; ?></td>
                </tr>
                <?php
              }
              ?>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <?php
    }
  }
  ?>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  
    <?php
  
  //display due date
  if (tep_not_null($order->info['due_date'])) {
    ?>
    <tr>
      <td class="main"><?php echo '<b>Due Date </b> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
    </tr>   
    <tr>
      <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td><?php echo tep_date_aus_format($order->info['due_date'],"short") . tep_draw_hidden_field('due_date', $order->info['due_date']); ?></td>
        </tr>
      </table></td>
    </tr>  
	 <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr> 
    <?php
 }
 
  if (tep_not_null($order->info['comments'])) {
    ?>
    <tr>
      <td class="main"><?php echo '<b>' . HEADING_ORDER_COMMENTS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
    </tr>
    <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
    <tr>
      <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td class="main"><?php echo nl2br(tep_output_string_protected($order->info['comments'])) . tep_draw_hidden_field('comments', $order->info['comments']); ?></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
    <?php
 }
  
    if (tep_not_null($order->info['purchase_number'])) {
    ?>
    <tr>
      <td class="main"><?php echo '<b>' . HEADING_PURCHASE_NUMBER . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
    </tr>
    <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
    <tr>
      <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td class="main"><?php echo nl2br(tep_output_string_protected($order->info['purchase_number'])) . tep_draw_hidden_field('purchase_number', $order->info['purchase_number']); ?></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
    <?php
  }
  if (MAIN_TABLE_BORDER == 'yes'){
    table_image_border_bottom();
  }
  ?>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
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
              <td class="main" align="right"><?php  echo tep_template_image_submit('button_confirm_order.gif', IMAGE_BUTTON_CONFIRM_ORDER) . "\n";?></td>       
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
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
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