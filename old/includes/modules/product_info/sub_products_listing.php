<?php
/*
  $Id: sub_product_multi_purchase.php,v 1.2.0.0 2008/06/20 13:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (MAIN_TABLE_BORDER == 'yes'){
  $header_text= '';
  table_image_border_top(false, false, $header_text);
}
?>
<tr>
  <td align="right"  width = "100%"><table cellpadding="0" cellspacing="1" border="0" width = "100%">
    <?php
    $cnt = 1;
    while ($sub_products = tep_db_fetch_array($sub_products_query)) {
      $subname = substr( $sub_products['products_name'], strlen( $product_info['products_name'] . ' - ' ));
      $pf->loadProduct($sub_products['products_id'],$languages_id);
      $sub_products_price = $pf->getPriceStringShort();
      if ($cnt == 2) {
        $class = 'productListing-even';
        $cnt = 1;
      } else {
        $class = 'productListing-odd';
        $cnt++;
      }
      ?>
      <tr class="<?php echo $class; ?>" align="right">
      <?php if(tep_not_null($sub_products['products_image']) ){?>
        <td class="productListing-data" valign="top" width = "<?php echo SMALL_IMAGE_WIDTH;?>"><?php echo tep_image(DIR_WS_IMAGES . $sub_products['products_image'], $subname, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT,'vspace="2" hspace="2"'); ?></td>
        <?php } else { ?>
        <td><!-- no image --></td>
        <?php } ?>
        <td class="productListing-data" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="productListing-data main" valign="top"><?php echo '<strong>' .  $subname . '</strong>' . ((defined('PRODUCT_INFO_SUB_PRODUCT_MODEL') && PRODUCT_INFO_SUB_PRODUCT_MODEL == 'True') ? ' | ' . $sub_products['products_model'] : '');
              if (tep_not_null($sub_products['products_blurb'] && PRODUCT_BLURB == 'true')){
                echo '<br>' . $sub_products['products_blurb'];
              }
              ?>
            </td>
          </tr>
          <?php 
          if (defined('PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES') && PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES == 'True' ){
            $load_attributes_for = $sub_products['products_id'];
            include(DIR_WS_MODULES . 'product_info/product_attributes.php');
          }
          ?>
        </table></td>
        <td class="productListing-data main" valign="top">
          <?php 
          echo  '<b>' . $sub_products_price . '</b><br>'; 
          if ($sub_products['products_quantity'] <= 0) {
            echo TEXT_OUT_OF_STOCK;
          }
          if ($sub_products['products_quantity'] > 0 || ($sub_products['products_quantity'] <= 0 && STOCK_ALLOW_CHECKOUT =='true')) {
            if(defined('PRODUCT_INFO_SUB_PRODUCT_PURCHASE') && PRODUCT_INFO_SUB_PRODUCT_PURCHASE == 'Multi'){
              if (PRODUCT_INFO_SUB_PRODUCT_ADDCART_TYPE == 'Checkbox') {
                echo '<p><strong>Add To Cart:</strong> <input type="checkbox" id="sub_add_checksub_products_qty_' . $sub_products['products_id'] . '" onclick="(this.checked) ? sub_products_qty_' . $sub_products['products_id'] . '.value = \'1\' : sub_products_qty_' . $sub_products['products_id'] . '.value =\'0\';">';
                echo '<input type="hidden" name="sub_products_qty[]" value="0" id="sub_products_qty_' . $sub_products['products_id'] . '"></p>';
              } else {
                echo '<p><strong>Qty:</strong> <input type="text" name="sub_products_qty[]" value="0" size="3" id="sub_products_qty_' . $sub_products['products_id'] . '"></p>';
              }
              echo tep_draw_hidden_field('sub_products_id[]', $sub_products['products_id']);
            } else {
              //echo  '<br>' . tep_template_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART,'onclick="sub_products_qty_' . $sub_products['products_id'] . '.value = \'1\';"');
              $hide_add_to_cart = hide_add_to_cart();
              if ($hide_add_to_cart == 'false' && group_hide_show_prices() == 'true') {
                echo  '<br><input type="submit" name="button" id="button" value="Add to Cart" onclick="sub_products_qty_' . $sub_products['products_id'] . '.value = \'1\';">';
                echo tep_draw_hidden_field('sub_products_qty[]', '0', 'id="sub_products_qty_' . $sub_products['products_id'] . '"') . tep_draw_hidden_field('sub_products_id[]', $sub_products['products_id']);
              }
            }
          }   // end if ($sub_products['products_quantity'] > 0){
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="3" bgcolor="#cccccc"><?php echo tep_draw_separator('pixel_trans.gif', '3', '1'); ?></td>
      </tr>
      <?php
    } // end while
    ?>
  </table></td>
</tr>
<?php
if (MAIN_TABLE_BORDER == 'yes'){
  table_image_border_bottom();
}
?>