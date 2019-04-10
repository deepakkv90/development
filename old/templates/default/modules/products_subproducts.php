<?php
//sub products module
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
  table_image_border_bottom(false, false, $header_text);
}
// EOF: Lango Added for template MOD

if(STOCK_ALLOW_CHECKOUT =='false')
{$allowcriteria=" and p.products_quantity >0";}else{$allowcriteria="";}

$sub_products_sql = tep_db_query("select p.products_id, p.products_price, p.products_tax_class_id, p.products_image, pd.products_name, p.products_model from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_parent_id = " . (int)$_GET['products_id'] . " ".$allowcriteria." and p.products_id = pd.products_id and pd.language_id = " . (int)$languages_id);

if (tep_db_num_rows($sub_products_sql) > 0) {
  // BOF: Lango Added for template MOD
  if (MAIN_TABLE_BORDER == 'yes'){
    table_image_border_top(false, false, $header_text);
  }
  // EOF: Lango Added for template MOD
  ?>
      <tr>
        <td align="right">
          <table>
          <?php
          while ($sub_products = tep_db_fetch_array($sub_products_sql)) {
            $subname = substr( $sub_products['products_name'], strlen( $product_info['products_name'] . ' - ' ));
          
            $pf->loadProduct($sub_products['products_id'],$languages_id);
          $sub_products_price = $pf->getPriceStringShort();

          ?>
            <tr align="right">
            <?php if (tep_not_null($sub_products['products_image'])) { ?>
              <td class="productListing-data"><?php echo tep_image(DIR_WS_IMAGES . $sub_products['products_image'], $subname, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT,'vspace="2" hspace="2"'); ?></td>
              <?php } else { ?>
              <td><!-- No image --></td>
              <?php } ?>
              <td class="productListing-data"><b><?php echo $subname; ?></b>&nbsp;[<?php echo $sub_products['products_model']; ?>]</td>
              <td class="productListing-data"><?php echo $sub_products_price; ?></td>
              <td class="productListing-data"><?php echo TEXT_PRODUCT_SUBPROD_QUANTITY;?> : <?php echo tep_draw_input_field('sub_products_qty[]', '0', 'size="5"') . tep_draw_hidden_field('sub_products_id[]', $sub_products['products_id']);;?></td>
            </tr>
          <?php
          }
          ?>
          </table>
        </td>
      </tr>
<?php  
  // BOF: Lango Added for template MOD
  if (MAIN_TABLE_BORDER == 'yes'){
    table_image_border_bottom();
  }
  // EOF: Lango Added for template MOD
}
// MOD end of sub product
?>