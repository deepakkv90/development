<?php 
/*
  $Id: order_checkout.tpl.php,v 1.0.0 2008/05/22 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('ordercheckout', 'top');
// RCI code eof
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
  <?php  
  if (isset($_GET['payment_error']) && is_object(${$_GET['payment_error']}) && ($error = ${$_GET['payment_error']}->get_error())) {
    ?>
    <tr>
      <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td class="main"><b><?php echo tep_output_string_protected($error['title']); ?></b></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBoxNotice">
        <tr class="infoBoxNoticeContents">
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              <td class="main" width="100%" valign="top"><?php echo tep_output_string_protected($error['error']); ?></td>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
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

  if ($order_total_modules->credit_selection()!='' ) {
    if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
        $header_text = '&nbsp;';
      ?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo TABLE_HEADING_CREDIT; ?></td>
          </tr>
        </table></td>
      </tr>
      <?php
    } else {
        $header_text =   TABLE_HEADING_CREDIT;
    }
    if (MAIN_TABLE_BORDER == 'yes') {
      table_image_border_top(false, false, $header_text);
    }

  echo tep_draw_form('checkout_payment_redeem', tep_href_link(FILENAME_ORDER_CHECKOUT, 'flag_coupon=1', 'SSL'), 'post', 'onsubmit="return check_coupon(this);"') . "\n";
  echo tep_draw_hidden_field('coupon_redeem', '1') . "\n";
  echo tep_draw_hidden_field('shipping', '') . "\n";
    echo $order_total_modules->credit_selection();//ICW ADDED FOR CREDIT CLASS SYSTEM 
    echo "\n" . '</form>' . "\n";
    if (MAIN_TABLE_BORDER == 'yes') {
      table_image_border_bottom();
    }
  }

  echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_CHECKOUT_PROCESSING, '', 'SSL'), 'post', 'onsubmit="return checkCheckBox(this);"') . tep_draw_hidden_field('cot_gv', '0') . "\n";

  if ($_SESSION['shipping'] !== false) {
    if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
      $header_text = '&nbsp;'
      ?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>
      <?php
    } else {
      $header_text = HEADING_TITLE;
    }
    if (MAIN_TABLE_BORDER == 'yes') {
      table_image_border_top(false, false, $header_text);
    }
    ?>
    <tr>
      <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td> 
              <td class="main" width="50%" valign="top"><?php echo TEXT_CHOOSE_SHIPPING_DESTINATION . '<br><br><a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '">' . tep_template_image_button('button_change_address.gif', IMAGE_BUTTON_CHANGE_ADDRESS) . '</a>'; ?></td>
              <td align="right" width="50%" valign="top"><table border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td class="main" align="center" valign="top"><?php echo '<b>' . TITLE_SHIPPING_ADDRESS . '</b><br>' . tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif'); ?></td>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td> 
                  <td class="main" valign="top"><?php echo tep_address_label($_SESSION['customer_id'], $_SESSION['sendto'], true, ' ', '<br>'); ?></td>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td> 
                </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
    <?php
    if (MAIN_TABLE_BORDER == 'yes') {
      table_image_border_bottom();
    }  
    // MVS Start
    if (tep_count_shipping_modules() > 0 || MVS_STATUS == 'true') {
      if (MVS_STATUS == 'true') {    
        require(DIR_WS_MODULES . 'vendor_shipping.php');
      } else {
    
      if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
       ?>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?php echo TABLE_HEADING_SHIPPING_METHOD; ?></td>
            </tr>
          </table></td>
        </tr>
        <?php
      } 
       if (MAIN_TABLE_BORDER == 'yes'){
          table_image_border_top(false, false, TABLE_HEADING_SHIPPING_METHOD);
        } 
        if (($order->content_type == 'virtual') || ($order->content_type == 'virtual_weight') || SHIPPING_SKIP == 'Always' || (SHIPPING_SKIP == 'If Weight = 0' && $cart->weight == 0)) {
          $_SESSION['shipping'] = false;
          $_SESSION['sendto'] = false; 
          $free_shipping = true;
        }
        ?>
        <tr>
          <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
            <?php
            if (isset($_GET['shipping_error'])) {        
              $error['error'] = TEXT_CHOOSE_SHIPPING_METHOD ;
            ?>
            <tr>
              <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBoxNotice">
                <tr class="infoBoxNoticeContents">
                  <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      <td class="main" width="100%" valign="top"><?php echo tep_output_string_protected($error['error']); ?></td>
                      <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
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
            ?>
            <tr class="infoBoxContents">
              <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                <?php
                if (sizeof($quotes) > 1 && sizeof($quotes[0]) > 1) {
                  ?>
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" width="50%" valign="top"><?php echo TEXT_CHOOSE_SHIPPING_METHOD; ?></td>
                    <td class="main" width="50%" valign="top" align="right"><?php echo '<b>' . TITLE_PLEASE_SELECT . '</b><br>' . tep_image(DIR_WS_IMAGES . 'arrow_east_south.gif'); ?></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                  <?php
                } elseif ($free_shipping == false) {
                  ?>
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" width="100%" colspan="2"><?php echo TEXT_ENTER_SHIPPING_INFORMATION; ?></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                  <?php         
                }
                if ($free_shipping == true) {
                  ?>
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td colspan="2" width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main" colspan="3"><b><?php echo FREE_SHIPPING_TITLE; ?></b>&nbsp;<?php echo $quotes[$i]['icon']; ?></td>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      </tr>
                      <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, 0)">
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main" width="100%"><?php echo sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format($freeshipping_over_amount)) . tep_draw_hidden_field('shipping', 'free_free'); ?></td>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      </tr>
                    </table></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td> 
                  </tr>
                  <?php
                } else {
                  $radio_buttons = 0;
                  for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
                    ?>
                    <tr>
                      <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                        <tr>
                          <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                          <td class="main" colspan="3"><b><?php echo $quotes[$i]['module']; ?></b>&nbsp;<?php if (isset($quotes[$i]['icon']) && tep_not_null($quotes[$i]['icon'])) { echo $quotes[$i]['icon']; } ?></td>
                          <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        </tr>
                        <?php
                        if (isset($quotes[$i]['error'])) {
                          ?>
                          <tr>
                            <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                            <td class="main" colspan="3"><?php echo $quotes[$i]['error']; ?></td>
                            <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                          </tr>
                          <?php
                        } else {
                          for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
                            // set the radio button to be checked if it is the method chosen
                            $checked = (($quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] == $_SESSION['shipping']['id']) ? true : false);
                            if ( ($checked == true) || ($n == 1 && $n2 == 1) ) {                            
                              echo '<tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                            } else {
                              echo '<tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                            }
                            ?>
                            <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                            <td class="main" width="75%"><?php echo $quotes[$i]['methods'][$j]['title']; ?></td>
                            <?php
                            if ( ($n > 1) || ($n2 > 1) ) {
                              ?>
                              <td class="main"><?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0))); ?></td>
                              <td class="main" align="right"><?php echo tep_draw_radio_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'], $checked); ?></td>
                              <?php
                            } else {
                              ?>
                              <body onload = "selectRowEffect('[object HTMLTableRowElement]',' <?php echo $radio_buttons;?>')">
                              <td class="main" align="right" colspan="2"><?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])) . tep_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']); ?></td>
                              <td class="main" align="right"><?php echo tep_draw_radio_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'], ' checked'); ?></td>
                              <?php
                            }
                            ?>
                            <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                            </tr>
                            <?php
                            $radio_buttons++;
                          }
                        }
                        ?>
                      </table></td>
                      <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td> 
                    </tr>
                    <?php
                  } 
                }
                ?>
              </table></td>
            </tr>
          </table></td>
        </tr>
        <?php
      
        if (MAIN_TABLE_BORDER == 'yes'){
          table_image_border_bottom();
        }
      }
    }// MVS Start
  }
  if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
    $header_text = '&nbsp;'
  ?>
        <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td class="pageHeading"><?php echo HEADING_TITLE_PAYMENT; ?></td>
        <td class="pageHeading" align="right"></td>
      </tr>
    </table></td>
  </tr>
  <?php
  } else {
    $header_text = HEADING_TITLE_PAYMENT;
  }
  if (MAIN_TABLE_BORDER == 'yes'){
    table_image_border_top(false, false, $header_text);
  }
  ?>
        <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
      <tr class="infoBoxContents">
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td> 
            <td class="main" width="50%" valign="top"><?php echo TEXT_SELECTED_BILLING_DESTINATION; ?><br><br><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '">' . tep_template_image_button('button_change_address.gif', IMAGE_BUTTON_CHANGE_ADDRESS) . '</a>'; ?></td>
            <td align="right" width="50%" valign="top"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" align="center" valign="top"><b><?php echo TITLE_BILLING_ADDRESS; ?></b><br><?php echo tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif'); ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td> 
                <td class="main" valign="top"><?php echo tep_address_label($_SESSION['customer_id'], $_SESSION['billto'], true, ' ', '<br>'); ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td> 
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <?php
  if (MAIN_TABLE_BORDER == 'yes'){
    table_image_border_bottom();
  }
  if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
    $header_text = '&nbsp;';
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="pageHeading"><?php echo TABLE_HEADING_PAYMENT_METHOD; ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  } else {
    $header_text = TABLE_HEADING_PAYMENT_METHOD;
  }
  if (MAIN_TABLE_BORDER == 'yes'){
    table_image_border_top(false, false, $header_text);
  }
  if( $order->info['total'] != 0 ){ 
  if (!defined('MODULE_PAYMENT_PAYPAL_WPP_STATUS') || MODULE_PAYMENT_PAYPAL_WPP_STATUS != 'True' || $_SESSION['skip_payment'] != '1') {
    // RCO start
    if ($cre_RCO->get('ordercheckout', 'paymentmodule') !== true) {     
      ?>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <?php
              $selection = $payment_modules->selection();
                 if (sizeof($selection) > 1) {
                ?>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  <td class="main" width="50%" valign="top"><?php echo TEXT_SELECT_PAYMENT_METHOD; ?></td>
                  <td class="main" width="50%" valign="top" align="right"><b><?php echo TITLE_PLEASE_SELECT; ?></b><br><?php echo tep_image(DIR_WS_IMAGES . 'arrow_east_south.gif'); ?></td>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                </tr>
                <?php
              } else {
                ?>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  <td class="main" width="100%" colspan="2"><?php echo TEXT_ENTER_PAYMENT_INFORMATION; ?></td>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                </tr>
                <?php
              }
              $radio_buttons = 0;
              for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
                ?>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <?php
                    if ( ($selection[$i]['id'] == $payment) || ($n == 1) ) {
                      echo '<tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffectPayment(this, ' . $radio_buttons . ')">' . "\n";
                    } else {
                      echo '<tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffectPayment(this, ' . $radio_buttons . ')">' . "\n";
                    }
                    ?>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="3"><b><?php echo $selection[$i]['module']; ?></b></td>
                    <td class="main" align="right">
                      <?php
                      if (sizeof($selection) > 1) {
                        echo tep_draw_radio_field('payment', $selection[$i]['id']);
                      } else {
                        echo tep_draw_hidden_field('payment', $selection[$i]['id']);
                      }        
                      ?>
                    </td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    </tr>
                    <?php
                    if (isset($selection[$i]['error'])) {
                      ?>
                      <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main" colspan="4"><?php echo $selection[$i]['error']; ?></td>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      </tr>
                      <?php
                    } elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) {
                      ?>
                      <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td colspan="4"><table border="0" cellspacing="0" cellpadding="2">
                          <?php
                          for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {
                            ?>
                            <tr>
                              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                              <td class="main"><?php echo $selection[$i]['fields'][$j]['title']; ?></td>
                              <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                              <td class="main"><?php echo $selection[$i]['fields'][$j]['field']; ?></td>
                              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                            </tr>
                            <?php
                          }
                          ?>
                        </table></td>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      </tr>
                      <?php
                    }
                    ?>
                  </table></td>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                </tr>
                <?php
                $radio_buttons++;
              }
              ?>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <?php
    }
    // RCO end
  } else {
    echo tep_draw_hidden_field('payment', 'paypal_wpp');
  }
  
  } else {
?>
      <tr>
        <td><?php echo TEXT_ORDER_TOTAL_ZERO;?></td>
      </tr>
<?php
echo tep_draw_hidden_field('payment', 'freecharger');
}
?>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td>
      <div id="div_order_total_payment_informayion">
      </div>
    </td>
  </tr>
<?php  

  if (MAIN_TABLE_BORDER == 'yes') {
    table_image_border_bottom();
  }
  if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
    $header_text = '&nbsp;'
  ?>
     <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td class="pageHeading"><?php echo HEADING_TITLE_COMMENTS; ?></td>
      </tr>
    </table></td>
  </tr>
  <?php
  } else {
     $header_text = HEADING_TITLE_COMMENTS;
  }
  if (MAIN_TABLE_BORDER == 'yes') {
    table_image_border_top(false, false, $header_text);
  }
  ?>
  <tr>                
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
      <tr class="infoBoxContents">
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><?php echo tep_draw_textarea_field('comments', 'soft', '60', '5', isset($_SESSION['comments']) ? $_SESSION['comments'] : ''); ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
<?php
  if (MAIN_TABLE_BORDER == 'yes'){
    table_image_border_bottom();
  }

  if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
    $header_text = '&nbsp;'
  ?>
     <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td class="pageHeading"><?php echo HEADING_TITLE_PRODUCTS; ?></td>
      </tr>
    </table></td>
  </tr>
  <?php
  } else {
    $header_text = TABLE_HEADING_PRODUCTS;
  }
  if (MAIN_TABLE_BORDER == 'yes') {
    table_image_border_top(false, false, $header_text);
  }
  ?>
     <tr>
    <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
      <tr class="infoBoxContents">
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <?php
          for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
            echo '<tr>' . "\n" .
                 '<td class="main" align="right" valign="top" width="30">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
                 '<td class="main" valign="top">' . $order->products[$i]['name'];
            if (STOCK_CHECK == 'true') {
              echo tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
            }
            if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
              for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
                echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . ' ' . $order->products[$i]['attributes'][$j]['prefix'] . ' ' . $currencies->display_price($order->products[$i]['attributes'][$j]['price'], tep_get_tax_rate($products[$i]['tax_class_id']), 1)  . '</i></small></nobr>';
              }
            }
            echo '</td>' . "\n";
            if (sizeof($order->info['tax_groups']) > 1) {
              echo '<td class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";
            }
            echo '<td class="main" align="right" valign="top">' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . '&nbsp;</td>' . "\n" .
                   '</tr>' . "\n";
          }
          if (MODULE_ORDER_TOTAL_INSTALLED) {
            ?>
            <tr>
              <td colspan="4" align="right">
                <div id="div_order_total">
                  <?php
                  // echo $order_total_modules->output();
                  ?>
                </div>
              </td>
            </tr>
            <?php
          }
          ?>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <?php
  if (MAIN_TABLE_BORDER == 'yes'){
    table_image_border_bottom();
  }
?>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
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
      </table></td>
        </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="50%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
            <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
          </tr>
        </table></td>
        <td width="50%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="center" width="50%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_CHECKOUT; ?></td>
        <td align="center" width="50%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
      </tr>
    </table></td>
  </tr></table></form>
<?php

if (MODULE_ORDER_TOTAL_INSTALLED) {  
  if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
  ?>
  <script type="text/javascript"><!--
  updateOrderTotal("<?php echo $s_shipping; ?>");
  //--></script>
  <?php
  } else {
  ?>
  <script type="text/javascript"><!--
  updateOrderTotal("<?php echo $_SESSION['shipping']['id']; ?>");
  //--></script>
  <?php
    }
}
// RCI code start
echo $cre_RCI->get('ordercheckout', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>