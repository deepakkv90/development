<?php
/*
  $Id: return_product.tpl.php,v 1.0 2008/05/99 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
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
          <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <?php
  } else {
    $header_text = HEADING_TITLE;
  }
  ?>
  <pre>
  
  <tr>
    <td valign = "top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <?php
      if (MAIN_TABLE_BORDER == 'yes'){
        table_image_border_top(false, false, $header_text);
      }

      if ($_GET['action'] == 'sent'){
        $text_query = tep_db_query("SELECT * FROM " . TABLE_RETURNS_TEXT . " where return_text_id = '1' and language_id = '" . $languages_id . "'");
        $text = tep_db_fetch_array($text_query);
        ?>
        <tr>
          <td valign = "top"><table border="0" width="100%" cellspacing="0" cellpadding="2" valign = "top">
              <tr>
                <td class="pageHeading" align="center"><?php echo '<strong>' . TEXT_YOUR_RMA_NUMBER . $_GET['rma_value'] . '</strong>'; ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '20', '20'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo nl2br($text['return_text_one']); ?></td>
              </tr>
              <?php
              if (MAIN_TABLE_BORDER == 'yes'){
                  table_image_border_bottom();
              }
              ?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '20', '20'); ?></td>
              </tr>
              <tr>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
              </tr>
            </table></td>
        </tr>
      </table>
      <?php
      } else {
        $account_query = tep_db_query("SELECT customers_firstname, customers_lastname, customers_email_address FROM " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
        $account = tep_db_fetch_array($account_query);
        // query the order table, to get all the product details
        $returned_products_query = tep_db_query("SELECT * FROM " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o where o.orders_id = op.orders_id and op.orders_id = '" . $_GET['order_id'] . "' and products_id = '" . $product_id . "'");
        $returned_products = tep_db_fetch_array($returned_products_query);

              $tmpquantity = 0;
              $query = tep_db_query("select products_quantity from returns_products_data where order_id = '" . $_GET['order_id']. "' and products_id = '" . $product_id ."' ");
              if (tep_db_num_rows($query)) {
              while($result = tep_db_fetch_array($query)) {
              $tmp_quantity += $result['products_quantity'];
              }
              }
              $returnvalue = $returned_products['products_quantity'];
              $tmp_quantity = $tmp_quantity + $tmpquantity;
              $return_products_qty = $returnvalue - $tmp_quantity;

$disabled = '';
if($return_products_qty == 0 ){
$disabled = 'disabled';
}

echo tep_draw_form('longsubmit', tep_href_link(FILENAME_RETURN, 'action=insert&order_id=' . $_GET['order_id'] . '&product_id=' . $product_id, 'SSL')); 
?>
  <tr>
    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td class="main" colspan="2"><b><?php echo (($disabled != 'disabled') ? TEXT_SUPPORT_RETURN_HEADING : TEXT_SUPPORT_RETURN_HEADING_DISABLED); ?></small></b></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo TEXT_SUPPORT_PRODUCT_RETURN; ?></b><br></td>
              </tr>
            </table></td>
          <td width="70%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <?php
                    if (sizeof($order->info['tax_groups']) > 1) {
                      ?>
                    <tr>
                      <td class="main" colspan="2"><b>Qty</b></td>
                      <td class="smallText" align="right"><b><?php echo HEADING_PRODUCTS; ?></b></td>
                      <td class="smallText" align="right"><b><?php echo HEADING_TOTAL; ?></b></td>
                    </tr>
                    <?php
                    } else {
                      ?>
                    <tr>
                      <td class="main">&nbsp;</td>
                      <td class="main" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo HEADING_PRODUCTS; ?></b></td>
                    </tr>
                    <?php
                    }
                    echo '<tr>' . "\n" .
                            '<td class="main" align="right" valign="top" width="30"><select name="returns_quantity">' . "\n";
                            $n=0;
                            while($n <= $return_products_qty){
                                echo '<option = "' . $n . '">' . $n . '</option>' . "\n";
                                $n++;
                            }
                    echo  '<td class="main" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;' . $returned_products['products_name'];
                    //attributes
                    $attributes_check_query = tep_db_query("select opa.*
                                                            from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " opa, 
                                                                 " . TABLE_ORDERS_PRODUCTS . " op
                                                            where 
                                                            opa.orders_id = '" . (int)$_GET['order_id'] . "' and
                                                            opa.orders_products_id = op.orders_products_id and 
                                                            op.orders_id = '" . (int)$_GET['order_id'] . "' and  
                                                            op.products_id = '" . (int)$product_id . "' ");
                    if (tep_db_num_rows($attributes_check_query)) {
                      while ($attributes = tep_db_fetch_array($attributes_check_query)) {
                        echo '<br><small><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*' . $attributes['products_options'] . ' : ' . $attributes['products_options_values'] . '&nbsp; &nbsp;' . $attributes['price_prefix'] . ' ' . $currencies->display_price($attributes['options_values_price'], tep_get_tax_rate($returned_products['products_tax'])) . '</small>';
                      }
                    }


                    echo '</td>' . "\n";
                    echo '<td class="main" align="right" valign="top">' . $currencies->format(($returned_products['products_price'] + (tep_calculate_tax(($returned_products['products_price']),($returned_products['products_tax'])))) * ($return_products_qty));
                    
                    echo '</td>' . "\n" .
                         '</tr>' . "\n";
                    ?>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td width="40%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo TEXT_SUPPORT_BILLING_ADDRESS; ?></b></td>
              </tr>
              <tr>
                <td class="main">&nbsp;</td>
              </tr>
            </table></td>
          <td width="60%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <?php
                echo '<tr>' . "\n" .
                     '<td class="main" align="left" width="5%">&nbsp;</td>' . "\n" .
                     '<td class="main" align="left" width=95%>' . tep_address_format((isset($order->billing['format_id']) ? $order->billing['format_id'] : 0), $order->billing, 1, ' ', '<br>') . '</td>' . "\n" .
                     '</tr>' . "\n";
                ?>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td width="40%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo TEXT_SUPPORT_DELIVERY_ADDRESS; ?></b></td>
              </tr>
              <tr>
                <td class="main">&nbsp;</td>
              </tr>
            </table></td>
          <td width="60%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <?php
                echo '<tr>' . "\n" .
                     '<td class="main" align="left" width="5%">&nbsp;</td>' . "\n" .
                     '<td class="main" align="left" width=95%>' . tep_address_format((isset($order->delivery['format_id']) ? $order->delivery['format_id'] : 0), $order->delivery, 1, ' ', '<br>') . '</td>' . "\n" .
                     '</tr>' . "\n";
                ?>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td width="40%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo TEXT_SUPPORT_USER_EMAIL; ?></b></td>
              </tr>
            </table></td>
          <td width="60%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <?php
                echo '<tr>' . "\n" .
                     '<td class="main" align="left" width="5%">&nbsp;</td>' . "\n" .
                     '<td class="main" align="left" width=95%>' . $account['customers_email_address'] . tep_draw_hidden_field('support_user_email', $account['customers_email_address']) . '</td>' . "\n" .
                     '</tr>' . "\n";
                ?>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <?php
if($disabled != 'disabled'){
?>
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td width="40%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo TEXT_WHY_RETURN; ?></b></td>
              </tr>
            </table></td>
          <td width="60%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class=main width=5%>&nbsp;</td>
                <td class="main" width=95%><?php
                    $priority_query = tep_db_query("select return_reason_id, return_reason_name from ". TABLE_RETURN_REASONS . " where language_id = '" . $languages_id . "' order by return_reason_id desc");
                    $select_box = '<select name="support_priority"  size="' . MAX_MANUFACTURERS_LIST . '">';
                    while ($priority_values = tep_db_fetch_array($priority_query)) {
                      $select_box .= '<option value="' . $priority_values['return_reason_id'] . '"';
                      if (DEFAULT_RETURN_REASON ==  $priority_values['return_reason_id']) $select_box .= ' selected="selected"';
                      $select_box .= '>' . substr($priority_values['return_reason_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '</option>';
                    }
                    $select_box .= "</select>";
                    $select_box .= tep_hide_session_id();
                    echo $select_box;
                    ?>
                </td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td class="main">&nbsp;</td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td width="40%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo TEXT_PREF_REFUND_METHOD; ?></b></td>
              </tr>
              <tr>
                <td class="main">&nbsp;</td>
              </tr>
            </table></td>
          <td width="60%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class=main width=5%>&nbsp;</td>
                <td class="main" width=95%><?php //echo tep_draw_input_field('link_url'); ?>
                  <?php
                    $refund_query = tep_db_query("select refund_method_id, refund_method_name from ". TABLE_REFUND_METHOD . " where language_id = '" . $languages_id . "' order by refund_method_id asc");
                    $select_box = '<select name="refund_method"  size="' . MAX_MANUFACTURERS_LIST . '">';
                    while ($refund_values = tep_db_fetch_array($refund_query)) {
                      $select_box .= '<option value="' . $refund_values['refund_method_name'] . '"';
                      if (DEFAULT_REFUND_METHOD ==  $refund_values['refund_method_id']) $select_box .= ' selected="selected"';
                      $select_box .= '>' . substr($refund_values['refund_method_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '</option>';
                    }
                   $select_box .= "</select>";
                   $select_box .= tep_hide_session_id();
                   echo $select_box;
                   echo '<br><br>';
                   $charge_query = tep_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_RESTOCK_VALUE'");
                   $charge = tep_db_fetch_array($charge_query);  
                   // don't show re-stocking info if it's set to zero in Admin > Configuration > Stock
                   if ($charge['configuration_value'] != 0) {
                     echo TEXT_SUPPORT_SURCHARGE . $charge['configuration_value'] .'%' . TEXT_SUPPORT_SURCHARGE_TWO;
                   } 
                   ?>
                </td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td width="40%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo TEXT_SUPPORT_TEXT; ?></b></td>
              </tr>
              <tr>
                <td class="main">&nbsp;</td>
              </tr>
            </table></td>
          <td width="60%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <?php
                echo '<tr>' . "\n" .
                     '<td class="main" align="left" width="5%">&nbsp;</td>' . "\n" .
                     '<td class="main" align="left" width=95%>' . tep_draw_textarea_field('support_text', 'soft', '40', '7') . '</td>' . "\n" . 
                     '</tr>' . "\n";
                     ?>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <?php
}//($disabled != 'disabled')
  if (MAIN_TABLE_BORDER == 'yes'){
    table_image_border_bottom();
  }
?>
  <tr>
    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
<?php 
    if($disabled != 'disabled'){
        echo  '<td align="right">' . tep_template_image_submit('button_confirm.gif', IMAGE_BUTTON_CONFIRM) . '</td>' . "\n";
    } else {
        echo '<td align="right"><a href="javascript:history.go(-1)">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a></td>' . "\n";
    } 
?>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
  </tr>
</table>
</form>
</td>
</tr>
<?php
}
?>
</table>
