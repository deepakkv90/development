<?php
/*
  $Id: paypalxc_checkoutshipping_insideformbelowbuttons.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
if ( defined('MODULE_PAYMENT_PAYPAL_XC_STATUS') && MODULE_PAYMENT_PAYPAL_XC_STATUS == 'True') {
  
      $ec_enabled = tep_paypal_xc_enabled();
      if ( $ec_enabled && $_SESSION['skip_payment'] != '1' ) { 
?>
          <script type="text/javascript"><!--
            function change_action() {
              document.checkout_address.ppaction.value = "paypal";
              document.checkout_address.submit();
            }
          //--></script>
          <input type="hidden" value="" name="ppaction">
          <tr>
            <td width="100%" valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr>
                        <td class="main" style="padding-left:10px;"><?php echo TEXT_PAYPAL_MARK_EXPLAIN; ?></td>
                        <td align="right"><img src="<?php echo MODULE_PAYMENT_PAYPAL_EC_MARK_IMG; ?>" border="0" onclick="javascript:change_action();" style="cursor: pointer;"></td>
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