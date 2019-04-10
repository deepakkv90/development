<?php
/*
  $Id: paypalxc_checkoutconfirmation_insideformbelowbuttons.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/

if ( defined('MODULE_PAYMENT_PAYPAL_XC_STATUS') && MODULE_PAYMENT_PAYPAL_XC_STATUS == 'True' && $_SESSION['skip_payment'] == '1' && $_SESSION['paypalxc_create_account'] == '1') {
  ?>
  <tr>
    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
    <td class="main">
      <?php 
      if (defined('PWA_ON') && PWA_ON == 'true' ) {
        echo tep_draw_checkbox_field('create_account', '1', true) . '&nbsp;' . TEXT_CREATE_ACCOUNT ; 
      } else {
        echo tep_draw_hidden_field('create_account', '1');
      }
      ?>
    </td>
    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
  </tr>
  <?php
}
?>