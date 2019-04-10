<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  
  Featured Products Listing Module
*/
?>
<!--D featured_prodcts-->
<?php
 require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FEATURED_PRODUCTS);

//Eversun mod for sppc and qty price breaks
  if ( ! isset($_SESSION['sppc_customer_group_id'])) {
    $customer_group_id = 'G';
  } else {
    $customer_group_id = $_SESSION['sppc_customer_group_id'];
  }
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text' => TABLE_HEADING_FEATURED_PRODUCTS);
  
  $featured_products2_query = tep_db_query("select distinct
                          p.products_id,
                     p.products_price as final_price,
                          p.manufacturers_id,
                          pd.products_name,
                          p.products_tax_class_id, 
                          if (isnull(pg.customers_group_price), p.products_price, pg.customers_group_price) as products_price,
                          p.products_date_added, 
                          p.products_image 
                          from (" . TABLE_PRODUCTS . " p 
                         left join " . TABLE_PRODUCTS_GROUPS . " pg on p.products_id = pg.products_id and pg.customers_group_id = '" . $customer_group_id . "'),
                              " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                        " . TABLE_FEATURED . " f
                                 where
                                   p.products_status = '1'
                                   and f.status = '1'
                                   and p.products_id = f.products_id
                                   and pd.products_id = f.products_id
                                   and pd.language_id = '" . $languages_id . "'
                                   and p.products_group_access like '%". $customer_group_id."%'
                                   order by rand(), p.products_date_added DESC, pd.products_name limit " . MAX_DISPLAY_FEATURED_PRODUCTS);
   
  $row = 0;
  $col = 0;
  $num = 0;
  
  while ($featured_products4 = tep_db_fetch_array($featured_products2_query)) {
   $num ++;
   $featured_products4_array[] = array('id' => $featured_products4['products_id'],
                                  'name' => $featured_products4['products_name'],
                                  'image' => $featured_products4['products_image'],
                                  'price' => $featured_products4['products_price'],
                                  'tax_class_id' => $featured_products4['products_tax_class_id'],
                                  'date_added' => tep_date_long($featured_products4['products_date_added']),
                                  'manufacturer' => tep_get_manufacturers_name($featured_products4['manufacturers_id'])   );
  }
  
 // BOF: Lango Added for template MOD
 

  
    for($i=0; $i<sizeof($featured_products4_array); $i++) {
  $pf->loadProduct($featured_products4_array[$i]['id'],$languages_id);
        $products_price = $pf->getPriceStringShort();
  // }

$s_buy_now = '';
$hide_add_to_cart = hide_add_to_cart();
if ($hide_add_to_cart == 'false' && group_hide_show_prices() == 'true') {
  $s_buy_now = '<br>' . '    <a href="' . tep_href_link(FILENAME_FEATURED_PRODUCTS, tep_get_all_get_params(array('action','cPath','products_id')) . 'action=buy_now&amp;products_id=' . $featured_products4_array[$i]['id'] . '&cPath=' . tep_get_product_path($featured_products4_array[$i]['id']), 'NONSSL') . '">' . tep_template_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a>';
}

$featured_string1a .= '  <tr>' .
                   '    <td class= "boxText" width="100%" align="center" valign="top"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products4_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products4_array[$i]['image'], $featured_products4_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br>' .
                   '    <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products4_array[$i]['id'], 'NONSSL') . '"><b><u>' . $featured_products4_array[$i]['name'] . '</u></b></a><br>' . TEXT_DATE_ADDED . ' ' . $featured_products4_array[$i]['date_added'] . '<br>' . TEXT_MANUFACTURER . ' ' . $featured_products4_array[$i]['manufacturer'] . '<br>' . TEXT_PRICE . ' ' . $products_price . $s_buy_now . '</td>' .
                   '  </tr>' ;

    
  }
//build box  
 if($num) {
   $info_box_contents = array();
   $info_box_contents[] = array('align' => 'left', 'text' => TABLE_HEADING_FEATURED_PRODUCTS);
   new contentBoxHeading($info_box_contents, tep_href_link(FILENAME_FEATURED_PRODUCTS));
   
   $info_box_contents = array();
   $info_box_contents[] = array('align' => 'left',
                               'text'  => '<table border="0" cellspacing="0" cellpadding="0" width="100%">' . $featured_string1a . '</table>');
   new contentBox($info_box_contents, true, true);


 if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){  
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                              );
    new infoboxFooter($info_box_contents);
  }
 } 
?>
