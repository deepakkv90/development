<?php
/*
  $Id: paypalxc_shoppingcart_logic.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
if ( defined('MODULE_PAYMENT_PAYPAL_XC_STATUS') && MODULE_PAYMENT_PAYPAL_XC_STATUS == 'True') {
$ec_enabled = tep_paypal_xc_enabled();
if ( $ec_enabled ) { 
  ?>
  <tr>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
      <tr class="infoBoxContents">
        <td><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><?php echo TEXT_PAYPAL_EXPLAIN; ?></td>
                <td align="right"><a href="<?php echo tep_href_link(FILENAME_EC_PROCESS, '', 'SSL'); ?>"><img src="<?php echo MODULE_PAYMENT_PAYPAL_EC_BUTTON_IMG; ?>" border="0" /></a></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <?php 
} 
}
?>