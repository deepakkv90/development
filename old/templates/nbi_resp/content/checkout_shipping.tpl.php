<?php 

// RCI code start

echo $cre_RCI->get('global', 'top');

echo $cre_RCI->get('checkoutshipping', 'top');

// RCI code eof

?>

<?php

echo tep_draw_form('checkout_address', tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'),'post','id="frm_checkout_ship"') . tep_draw_hidden_field('action', 'process'); ?>

<h1><?php echo HEADING_TITLE; ?></h1>

<div class="content">
	<!--<h2><?php echo TABLE_HEADING_SHIPPING_ADDRESS; ?></h2>-->
	
	<div class="left">
		 
		 <h3><?php echo TITLE_SHIPPING_ADDRESS; ?></h3>
		<br/>
		<?php echo tep_address_label($_SESSION['customer_id'], $_SESSION['sendto'], true, ' ', '<br>'); ?>

	</div>
	
	<div class="right">
		<?php

		// RCO start

		if ($cre_RCO->get('checkoutshipping', 'changeaddressbutton') !== true) {

		  echo TEXT_CHOOSE_SHIPPING_DESTINATION . '<br/><br/> <a class="button" href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '">Change</a>';

		}

		// RCO eof

		?>
	</div>
	
</div>

<?php  if (tep_count_shipping_modules() > 0) {  ?>

<div class="content text-center">
	
	<h2><?php echo TABLE_HEADING_SHIPPING_METHOD; ?></h2>
	
	<?php 
	if (sizeof($quotes) > 1 && sizeof($quotes[0]) > 1) { 
		
		echo "<p>".TEXT_CHOOSE_SHIPPING_METHOD."</p>"; 
		
	} elseif ($free_shipping == false) { 
	
			echo "<p>".TEXT_ENTER_SHIPPING_INFORMATION."</p>"; 
		
	}
	
	if ($free_shipping == true) {
	
		echo "<h3>".FREE_SHIPPING_TITLE ."</h3>&nbsp;&nbsp;&nbsp;". $quotes[$i]['icon'];
		echo sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) . tep_draw_hidden_field('shipping', 'free_free');
		
	} else {

      $radio_buttons = 0; $shipset = 0;
	  
	  echo '<div class="product-grid">';
	  
      for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
	  
		echo '<div>';
		
		echo '<div class="name">'.$quotes[$i]['module'].'</div>';
		
		if (isset($quotes[$i]['icon']) && tep_not_null($quotes[$i]['icon'])) { echo '<div class="image">'.$quotes[$i]['icon'].'</div>'; }
		
		if (isset($quotes[$i]['error'])) {
		
			echo '<div class="error">'.$quotes[$i]['error'].'</div>';
			
		} else {
		
			for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {

			// set the radio button to be checked if it is the method chosen

            $checked = (($quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] == $shipping['id']) ? true : false);
		
			if(preg_match("/aus/",$quotes[$i]['id'])) {
				if($shipset==0) {					
					$checked="CHECKED";
					$shipset = 1;
				}
			} 		

?>
                    <div class="desc"><?php echo $quotes[$i]['methods'][$j]['title']; ?></div>

<?php

            if ( ($n > 1) || ($n2 > 1) ) {

?>

                    <div class="price"><?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0))); ?></div>

                    <div class="cart"><?php echo tep_draw_radio_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'], $checked); ?></div>

<?php

            } else {

?>

                    <div class="price"><?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])) . tep_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']); ?></div>

<?php

            }

            $radio_buttons++;

          }
		
		}
		
		echo '</div>';
	  
	  }
	  
	  echo '</div>';
	  
	}  
	?>
	
	<div class="product-grid">
	
	</div>
	
</div>

<?php } ?>


	 <div class="content text-center">
		<p><?php echo '<b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></p>
		<input type="submit" name="submit_shipping" value="Continue" class="button" />
	 </div>

      <div class="content">
		<table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">

              <tr>

                <td width="50%" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>

                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>

              </tr>

            </table></td>

            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>

            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>

            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">

              <tr>

                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>

                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>

              </tr>

            </table></td>

          </tr>

          <tr>

            <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_DELIVERY; ?></td>

            <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_PAYMENT; ?></td>

            <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>

            <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>

          </tr>

        </table>
		
		</div>

    </form>

<?php 

// RCI code start

echo $cre_RCI->get('checkoutshipping', 'bottom');

echo $cre_RCI->get('global', 'bottom');

// RCI code eof

?>