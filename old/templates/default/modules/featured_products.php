<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  
  Featured Products Listing Module
*/
  if (sizeof($featured_products_array) == '0') {
?>
  <tr>
    <td class="main"><?php echo TEXT_NO_FEATURED_PRODUCTS; ?></td>
  </tr>
<?php
  } else {
    for($i=0; $i<sizeof($featured_products_array); $i++) {

  $pf->loadProduct($featured_products_array[$i]['id'],$languages_id);
        $products_price = $pf->getPriceStringShort();

 ?>
  <tr>
    <td width="<?php echo SMALL_IMAGE_WIDTH + 10; ?>" valign="top" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>'; ?></td>
    <td valign="top" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><b><u>' . $featured_products_array[$i]['name'] . '</u></b></a><br>' . TEXT_DATE_ADDED . ' ' . $featured_products_array[$i]['date_added'] . '<br>' . TEXT_MANUFACTURER . ' ' . $featured_products_array[$i]['manufacturer'] . '<br><br>' . TEXT_PRICE . ' ' . $products_price; ?></td>
    <td align="right" valign="middle" class="main"><?php 
    $hide_add_to_cart = hide_add_to_cart();
    if ($hide_add_to_cart == 'false' && group_hide_show_prices() == 'true') {
      echo '<a href="' . tep_href_link(FILENAME_FEATURED_PRODUCTS, tep_get_all_get_params(array('action','cPath','products_id')) . 'action=buy_now&products_id=' . $featured_products_array[$i]['id'] . '&cPath=' . tep_get_product_path($featured_products_array[$i]['id']), 'NONSSL') . '">' . tep_template_image_button('button_in_cart.gif', IMAGE_BUTTON_IN_CART) . '</a>';
    }
   ?></td>
  </tr>
<?php
      if (($i+1) != sizeof($featured_products_array)) {
?>
  <tr>
    <td colspan="3" class="main">&nbsp;</td>
  </tr>
<?php
      }
    }
  }
?>
