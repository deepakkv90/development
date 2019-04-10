<?php
/*
  $Id: returns_track.php,v 1.2 2008/10/05 00:36:42 wa4u Exp $

  author Puddled Internet - http://www.puddled.co.uk
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<tr>
  <td valign="top"><?php
         $account_query = tep_db_query("SELECT customers_firstname, customers_lastname, customers_email_address FROM " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
         $account = tep_db_fetch_array($account_query);
         // query the order table, to get all the product details
?>
    <table width="100%" border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td class="main"><b><?php echo TEXT_SUPPORT_PRODUCT_RETURN; ?></b></td>
        <td class="main"><table border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td class="main" colspan="3"><b>&nbsp; &nbsp;<?php echo HEADING_PRODUCTS; ?></b></td>
            </tr>
            <?php
//  $ordered_product_query = tep_db_query("SELECT * FROM " . TABLE_ORDERS_PRODUCTS . " where order_id = '" . $_GET
    echo '          <tr>' . "\n" .
         '            <td class="main" align="right" valign="top" width="30">' . $returned_products['products_quantity'] . '&nbsp;x</td>' . "\n" .
         '            <td class="main" valign="top">' . $returned_products['products_name'];
    echo '</td>' . "\n";
    echo '            <td class="main" align="right" valign="top">' . $currencies->format(($returned_products['products_price'] + (tep_calculate_tax(($returned_products['products_price']),($returned_products['products_tax'])))) * ($returned_products['products_quantity'])) . '</td>' . "\n" .
         '          </tr>' . "\n";
    ?>
          </table></td>
      </tr>
      <tr>
        <td colspan="2" class="main"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo TEXT_SUPPORT_BILLING_ADDRESS; ?></b></td>
        <td class="main"><?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>');?></td>
      </tr>
      <tr>
        <td colspan="2" class="main"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo TEXT_SUPPORT_DELIVERY_ADDRESS; ?></b></td>
        <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>');?></td>
      </tr>
      <tr>
        <td colspan="2" class="main"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo TEXT_SUPPORT_USER_EMAIL; ?></b></td>
        <td class="main"><?php echo $account['customers_email_address'] . tep_draw_hidden_field('support_user_email', $account['customers_email_address']);?></td>
      </tr>
      <tr>
        <td colspan="2" class="main"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo TEXT_WHY_RETURN; ?></b></td>
        <td class="main"><?php
            $reason_query = tep_db_query("SELECT return_reason_name FROM " . TABLE_RETURN_REASONS . " where return_reason_id = '" . $returned_products['returns_reason'] . "' and language_id = '" . $languages_id . "'");
            $reason = tep_db_fetch_array($reason_query);
             echo $reason['return_reason_name'];
          ?></td>
      </tr>
      <tr>
        <td colspan="2" class="main"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo TEXT_SUPPORT_TEXT; ?></td>
        <td class="main"><?php echo nl2br($returned_products['comments']);?></td>
      </tr>
      <tr>
        <td colspan="2" class="main"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
    </table></td>
</tr>