<?php
/*
  $Id: specials_main.php,v 1.0 2005/05/12 23:55:58 hpdl Exp $

  http://www.template-faq.com

  Released under the GNU General Public License
*/

  $orders_query = tep_db_query("select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and p.products_id = s.products_id and pd.products_id = s.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by s.specials_date_added desc limit 2");

    $num_products_ordered = tep_db_num_rows($orders_query);
    if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {
?>
<!-- also_purchased_products //-->
<?php
    $info_box_contents = array();
    $info_box_contents[] = array('text' => BOX_HEADING_SPECIALS);

    new contentBoxHeading($info_box_contents);

      $row = 0;
      $col = 0;
      $info_box_contents = array();
  while ($new_products = tep_db_fetch_array($orders_query)) {
    $product_query = tep_db_query("select products_name, products_description from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$new_products['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
    $product = tep_db_fetch_array($product_query);
    #PR Build product information box
    $product_info_str = '<table border="0" cellspacing="0" cellpadding="0" class="productBoxHeading_tb"><tr>';
    $product_info_str .= '<td class="productBoxHeadingLcorner"></td>';
    $product_info_str .= '<td class="productBoxHeading"><table width="100%" class="expender_tb"><tr><td></td></tr></table></td></td>';
    $product_info_str .= '<td class="productBoxHeadingRcorner"></td>';
    $product_info_str .= '</tr></table>';
    $product_info_str .= '<table border="0" width="210" cellspacing="0" cellpadding="0" class="productBoxOuter"><tr>';
    $product_info_str .= '<td class="productBoxLSide"> </td>';
    $product_info_str .= '<td height=100% valign="top" class="productBoxMSide">';
    $product_info_str .= '<table border="0" width="100%" cellspacing="0" cellpadding="3" class="productBox" valign="top"><tr><td>';
    $product_info_str .= '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $new_products['products_image'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
    $product_info_str .= '</td><td valign="top">';
    $product_info_str .= '<div class="productBox_prod_name"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . $product['products_name'] . '</a></div>';
    $product_info_str .= '<div class="productBox_prod_discr">'.substr($product['products_description'], 0, 80).'</div>';
    $product_info_str .= '<table border="0" width="100%" cellspacing="0" cellpadding="0" class="productBox_prod_price"><tr><td>';
    $product_info_str .= '<s><font size="1">' . $currencies->display_price($new_products['products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])) . '</font></s><span class="productSpecialPrice">' . $currencies->display_price($new_products['specials_new_products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])) . '</span>';    
    $product_info_str .= '</td><td><IMG SRC="'. DIR_WS_TEMPLATE_IMAGES .'hl_1.gif">&nbsp;<IMG SRC="'. DIR_WS_TEMPLATE_IMAGES .'az_icon_sm_cart.gif">&nbsp;'; 
    $product_info_str .= '</td><td><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $new_products['products_id']) . '">' . IMAGE_BUTTON_BUY_NOW . '</a>';
    $product_info_str .= '</td></tr></table>';
    $product_info_str .= '</td></tr></table>';
    $product_info_str .= '</td><td class="productBoxRSide"></td></tr></table>';  
    $product_info_str .= '<table border="0" cellspacing="0" cellpadding="0" class="productBoxBottom_tb"><tr>';
    $product_info_str .= '<td class="productBoxLBottom">';
    $product_info_str .= '</td>';
    $product_info_str .= '<td class="productBoxMBottom"><table width="100%" class="expender_tb"><tr><td></td></tr></table></td>';
    $product_info_str .= '<td class="productBoxRBottom">';
    $product_info_str .= '</td></tr></table>';

    $info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" width="49%" valign="top"',
                                           'text' => $product_info_str);

    $col ++;
    if ($col > 1) {
    $col = 0;
    $row ++;
    }
      }

      new contentBox($info_box_contents, true, true);
      
       if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){
   $info_box_contents = array();
   $info_box_contents[] = array('align' => 'left',
                                 'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                               );
  new contentBoxFooter($info_box_contents);
}
?>
<!-- also_purchased_products_eof //-->
<?php
    }
?>
