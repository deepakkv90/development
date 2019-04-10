<?php
/*
  $Id: paypalxc_login_aboveloginbox.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
global $cart;

if (defined('MODULE_PAYMENT_PAYPAL_XC_STATUS') && MODULE_PAYMENT_PAYPAL_XC_STATUS == 'True') {  
  $ec_enabled = tep_paypal_xc_enabled();
  if ( $ec_enabled && $cart->count_contents() > 0 ) { 
    $info_box_contents = array(); 
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => TEXT_PAYPAL_SINGIN_TITLE );
    ?>
    <tr>
      <td width="100%" valign="top">
        <?php new contentBoxHeading($info_box_contents, false, false);
        $paypal_btn_str = '';
        $paypal_btn_str .= '<table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n";
        $paypal_btn_str .= '  <tr>' . "\n";
        $paypal_btn_str .= '    <td width="10">' . tep_draw_separator('pixel_trans.gif', '10', '1') . '</td>' . "\n";
        $paypal_btn_str .= '    <td class="main">' . TEXT_PAYPAL_EXPLAIN . '</td>' . "\n";
        $paypal_btn_str .= '    <td align="right"><a href="' . tep_href_link(FILENAME_EC_PROCESS, '', 'SSL') . '"><img src="' . MODULE_PAYMENT_PAYPAL_EC_BUTTON_IMG . '" border=0></a></td>' . "\n";
        $paypal_btn_str .= '    <td width="10">' . tep_draw_separator('pixel_trans.gif', '10', '1') . '</td>' . "\n";
        $paypal_btn_str .= '  </tr>' . "\n";
        $paypal_btn_str .= '</table>';
        
        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left',
                                     'text'  => $paypal_btn_str);
        new contentBox($info_box_contents, true, true);
  
        if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){
          $info_box_contents = array();
          $info_box_contents[] = array('align' => 'left',
                                       'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                      );
          new contentBoxFooter($info_box_contents);
        }
        ?>
      </td>
    </tr>
    <?php 
  } 
}
?>