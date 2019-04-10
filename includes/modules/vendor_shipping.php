<?php
/*
  $Id: vendor_shipping.php,v 1.0 2005/03/29 jck Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License
*/

  if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
    $header_text = '&nbsp;'
  ?>
   <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td class="pageHeading"><?php echo TABLE_HEADING_SHIPPING_METHOD; ?></td>
      </tr>
    </table></td>
  </tr>
  <?php
  } else {
     $header_text = TABLE_HEADING_SHIPPING_METHOD;
  }
  if (MAIN_TABLE_BORDER == 'yes') {
    table_image_border_top(false, false, $header_text);
  }
  
  $vendor_shipping = $cart->vendor_shipping();
//Display a notice if we are shipping by multiple methods
    if (count ($vendor_shipping) > 1) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><font color="#FF0000"><b>NOTE:</b></font> <?php echo TEXT_MULTIPLE_SHIPPING_METHODS; ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
    }
$s_shipping = '';
//Draw a selection box for each shipping_method
    foreach ($vendor_shipping as $vendor_id => $vendor_data) {
      $total_weight = $vendor_data['weight'];
      $shipping_weight = $total_weight;
      $cost = $vendor_data['cost'];
      $ship_tax = $shipping_tax;   //for taxes
      $total_count = $vendor_data['qty'];

//  Much of the code from the top of the main page has been moved here, since
//    it has to be executed for each vendor
      if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
        $pass = false;

        switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
          case 'national':
            if ($order->delivery['country_id'] == STORE_COUNTRY) {
              $pass = true;
            }
            break;
          case 'international':
            if ($order->delivery['country_id'] != STORE_COUNTRY) {
              $pass = true;
            }
            break;
          case 'both':
            $pass = true;
            break;
          }

          $free_shipping = false;
          if ( ($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
            $free_shipping = true;

            include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
          }
        } else {
          $free_shipping = false;
        }
//print "<br>Vendor_id in Shipping: " . $vendor_id;
//Get the quotes array
      $quotes = $shipping_modules->quote('', '', $vendor_id);

// if no shipping method has been selected, automatically select the cheapest method.
// if the modules status was changed when none were available, to save on implementing
// a javascript force-selection method, also automatically select the cheapest shipping
// method if more than one module is now enabled
    //if ( !tep_session_is_registered('shipping') || ( tep_session_is_registered('shipping') && ($shipping == false) && (tep_count_shipping_modules() > 1) ) ) $shipping = $shipping_modules->cheapest($vendor_id);

    $_SESSION['shipping'] = $shipping_modules->cheapest($vendor_id);
    $products_ids = $vendor_data['products_id'];

    if ($s_shipping != '') {
      $s_shipping .= '&';
    }
    $s_shipping .= 'shipping_'.$vendor_id.'='.$_SESSION['shipping']['id'].'_'.$vendor_id.'&products_'.$vendor_id.'='.$products_ids[0];
  
    
?>
        <td><table border=0 width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border=0 width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="50%" valign="top"><b><?php echo TEXT_PRODUCTS; ?></b></td>
                <td class="main" width="50%" valign="top">&nbsp;</td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
      
      foreach ($products_ids as $product_id) {
        $products_query = tep_db_query("select products_name 
                                              from " . TABLE_PRODUCTS_DESCRIPTION . " 
                                              where products_id = '" . (int)$product_id . "' 
                                                and language_id = '" . (int)$languages_id . "'"
                                      );
        $products = tep_db_fetch_array($products_query);
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="50%" valign="top">
<?php 
        echo tep_draw_separator('pixel_trans.gif', '10', '1'); 
        echo $products['products_name']; 
?>
                </td>
                <td class="main" width="50%" valign="top" align="right"><?php ; ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
      }//foreach
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
<?php    
      if (count($quotes) > 1) {
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
                    <td class="main" width="100%"><?php echo sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) . tep_draw_hidden_field('shipping', 'free_free'); ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                </table></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td> 
              </tr>
<?php
    } else {
      $radio_buttons = 0;
      for ($i=0, $n=count($quotes); $i<$n; $i++) {
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
             if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
                 echo '                  <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect_vendor(this, ' . $radio_buttons . ','.$vendor_id.')">' . "\n";
              } else {
                echo '                  <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
              }
            } else {
              if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
                echo '                  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect_vendor(this, ' . $radio_buttons . ','.$vendor_id.')">' . "\n";
              } else {
                echo '                  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
              }
            }
?>
<?php $shipping_actual_tax = $quotes[$i]['tax'] / 100;
$shipping_tax = $shipping_actual_tax * $quotes[$i]['methods'][$j]['cost']; ?>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" width="75%"><?php echo $quotes[$i]['methods'][$j]['title']; ?></td>
<?php
            if ( ($n > 1) || ($n2 > 1) ) {
?>
                    <td class="main"><?php echo $currencies->format($quotes[$i]['methods'][$j]['cost']); ?></td>
                    <td class="main" align="right">
<?php 
              echo tep_draw_radio_field('shipping_' . $vendor_id, $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] . '_' . $shipping_tax, $checked);
              echo tep_draw_hidden_field('products_' . $vendor_id, implode("_", $products_ids)); 
?>
                    </td>
<?php
            } else {
?>
                    <td class="main" align="right" colspan="2"><?php echo $currencies->format($quotes[$i]['methods'][$j]['cost']) . tep_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] . '_' . $shipping_tax); ?></td>
                    <td class="main" align="right">
<?php 
             echo tep_draw_hidden_field('shipping_' . $vendor_id, $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] . '_' . $shipping_tax);
              echo tep_draw_hidden_field('products_' . $vendor_id, implode("_", $products_ids)); 
?>
                    </td>
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
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php            
    }
    if (MAIN_TABLE_BORDER == 'yes') {
      table_image_border_bottom();
    } 
?>