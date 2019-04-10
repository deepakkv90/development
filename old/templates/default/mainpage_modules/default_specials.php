<?php
/*
  $Id: default_specials.php,v 2.0 2003/06/13

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- D default_specials //-->
<?php
    $info_box_contents = array();
    $info_box_contents[] = array('text' => sprintf(TABLE_HEADING_DEFAULT_SPECIALS, strftime('%B')));

//Eversun mod for sppc and qty price breaks
  if(!isset($_SESSION['sppc_customer_group_id'])) {
  $customer_group_id = 'G';
  } else {
   $customer_group_id = $_SESSION['sppc_customer_group_id'];
  }
  
$new10 = tep_db_query("select distinct 
 p.products_id,
 pd.products_name, pd.products_blurb,
 if (isnull(pg.customers_group_price), p.products_price, pg.customers_group_price) as products_price,
 p.products_image 
  from (" . TABLE_PRODUCTS . " p 
      left join " . TABLE_PRODUCTS_GROUPS . " pg on p.products_id = pg.products_id and pg.customers_group_id = '" . $customer_group_id . "'),
  " . TABLE_SPECIALS . " s,
  " . TABLE_PRODUCTS_DESCRIPTION . " pd
 where 
   p.products_status = '1'
   and p.products_id = s.products_id
   and pd.products_id = p.products_id
   and pd.language_id = '" . $languages_id . "' 
   and s.status = '1' 
   and p.products_group_access like '%". $customer_group_id."%'
 order by rand(),  s.specials_date_added DESC limit " . MAX_DISPLAY_SPECIAL_PRODUCTS);
 
//Eversun mod end for sppc and qty price breaks

  $row = 0;
  $col = 0;
  $num = 0;
 while ($default_specials_1a = tep_db_fetch_array($new10)) {

    $num ++;
      if ($num == 1) {
    new contentBoxHeading($info_box_contents, tep_href_link(FILENAME_SPECIALS));
       }


  $pf->loadProduct($default_specials_1a['products_id'],$languages_id);
        $products_price_s = $pf->getPriceStringShort();
      
      $hide_add_to_cart = hide_add_to_cart();
      if ($hide_add_to_cart == 'false' && group_hide_show_prices() == 'true') {
      $buyitnow='<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action','cPath','products_id')) . 'action=buy_now&amp;products_id=' . $default_specials_1a['products_id'] . '&amp;cPath=' . tep_get_product_path($default_specials_1a['products_id'])) . '">' . tep_template_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a>&nbsp;';
      }
 
 $info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                           'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $default_specials_1a['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $default_specials_1a['products_image'], $default_specials_1a['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $default_specials_1a['products_id']) . '">' . $default_specials_1a['products_name'] . '</a><br>' . (tep_not_null($default_specials_1a['products_blurb'] && PRODUCT_BLURB == 'true') ? $default_specials_1a['products_blurb'] . '<br>' : '') . $products_price_s . '<br>'. $buyitnow);

    $col ++;
    if ($col > 2) {
      $col = 0;
      $row ++;
    }
  }
  
if($num) {
  new contentBox($info_box_contents, true, true);
if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){ 
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                                'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                              );
  new contentBoxFooter($info_box_contents);
  }
}
?>

<!-- D default_specials_eof //-->
