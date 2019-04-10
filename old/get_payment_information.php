<?php
/*
  $Id: get_order_total.php,v 1.0.0 2008/05/22 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
require('includes/classes/http_client.php');
require(DIR_WS_CLASSES . 'payment.php');
$payment_modules = new payment($_GET['payment']);

//Payment Information
if(isset($_GET['payment'])){
  $_SESSION['payment'] = $_GET['payment'];
} else {
  $_SESSION['payment'] = $order->info['payment_method'];
}
//Payment Information


  if (is_array($payment_modules->modules)) {

   if ($confirmation = $payment_modules->confirmation()) {
      
      $payment_info = $confirmation['title'];
       if (!isset($_SESSION['payment_info'])) $_SESSION['payment_info'] = $payment_info;
       ?>
     <table border = "0" width = "90%" align = "center">
      <tr>
        <td class="main"><b><?php echo 'Payment Information'; ?></b></td>
      </tr>
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
     </table>
       <?php
   }
  }  
 
?>