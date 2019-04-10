<?php
/*
  $Id: paypalxc_checkoutpayment_paymentmodule.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
		global $payment_modules;

		$ec_enabled = tep_paypal_xc_enabled();
		$modules_count = tep_count_payment_modules();

        $selection = $payment_modules->selection();
		
		echo TEXT_ENTER_PAYMENT_INFORMATION;

		echo '<div class="product-grid">';
		
        $radio_buttons = 0;
		
        for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
		
          ?>
            <div class="shipMethods">
				<div class="name"><?php echo $selection[$i]['module']; ?></div>
				<div class="cart">
				  <?php
				  if (sizeof($selection) > 1 || $ec_enabled ) {
					echo tep_draw_radio_field('payment', $selection[$i]['id']);
				  } else {
					echo tep_draw_hidden_field('payment', $selection[$i]['id']);
				  }
				  ?>
				</div>
				  <?php
				  if (isset($selection[$i]['error'])) {
				  ?>
					<div class="error"><?php echo $selection[$i]['error']; ?></div>
				  <?php
				  } elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) {
					?>
					
						<?php
						for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) { 
						  ?>
						  <table><tr>
							
							<td class="main"><?php echo $selection[$i]['fields'][$j]['title']; ?></td>
							
							<td class="main"><?php echo $selection[$i]['fields'][$j]['field']; ?></td>
							
						  </tr></table>
						  <?php
						}
						?>
					 
			  <?php } ?>
		    </div>
		  <?php
          $radio_buttons++;
		  
        }
		
		echo '</div>';
		
        if ( $ec_enabled ) {
          ?>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <?php
              echo '<tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";    
                ?>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" colspan="3"><b><?php echo MODULE_PAYMENT_PAYPAL_EC_TEXT_TITLE; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="<?php echo MODULE_PAYMENT_PAYPAL_EC_MARK_IMG; ?>"></td>
                <td class="main" align="right">
                  <?php 
                  if ($radio_buttons > 0 ) {
                    echo tep_draw_radio_field('payment', 'paypal_xc_ec'); 
                  } else {
                    echo tep_draw_hidden_field('payment', 'paypal_xc_ec');
                  }
                  ?>
                </td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" colspan="2"><?php echo MODULE_PAYMENT_PAYPAL_XC_TEXT_ACCEPTANCE_MARK; ?></td>
              </tr>
            </table></td>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
          </tr>
          <?php
        }
        ?>
      </table></td>
    </tr>
  </table></td>
</tr>