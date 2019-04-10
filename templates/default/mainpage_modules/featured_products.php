<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  
  Featured Products Listing Module
*/
?>
<!--D featured_products-->
<?php
 require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FEATURED_PRODUCTS);

//Eversun mod for sppc and qty price breaks
  if(!isset($_SESSION['sppc_customer_group_id'])) {
  $customer_group_id = 'G';
  } else {
   $customer_group_id = $_SESSION['sppc_customer_group_id'];
  }
     $info_box_contents = array();
 $featured_products2_query = tep_db_query("select distinct
                           p.products_image, 
                           p.products_id,
                           pd.products_name, pd.products_blurb
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
                                   order by rand(),p.products_date_added DESC, pd.products_name limit " . MAX_DISPLAY_FEATURED_PRODUCTS);
   
  $row = 0;
  $col = 0;
  $num = 0;
   $featured_products2_check = tep_db_num_rows($featured_products2_query);
     if ($featured_products2_check > 0){
  while ($featured_products4 = tep_db_fetch_array($featured_products2_query)) {
   $num ++;
   $featured_products4_array[] = array('id' => $featured_products4['products_id'],
                                  'name' => $featured_products4['products_name'],
                                  'blurb' =>  $featured_products4['products_blurb'],
                                  'image' => $featured_products4['products_image']   );
  }
     for($i=0; $i<sizeof($featured_products4_array); $i++) {
        $num++;
        $pf->loadProduct($featured_products4_array[$i]['id'],$languages_id);
        $products_price = str_replace('&nbsp;',' ',$pf->getPriceStringShort());
        

$featured_str  = '';
$featured_str  .= '<table width="280" border="0" cellspacing="0" cellpadding="0">' . "\n";
$featured_str  .= '  <tr>' . "\n";
$featured_str  .= '    <td width="6">' . tep_image(DIR_WS_TEMPLATE_IMAGES . 'mainpage_left.png') . '</td>' . "\n";
$featured_str  .= '    <td width="110" align="center" style="background:url(' . DIR_WS_TEMPLATE_IMAGES . 'mainpage_mid.png)"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products4_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products4_array[$i]['image'], $featured_products4_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td>' . "\n";
$featured_str  .= '    <td valign="top" style="background:url(' . DIR_WS_TEMPLATE_IMAGES . 'mainpage_mid.png)"><table width="100%" border="0" cellspacing="0" cellpadding="0">' . "\n";
$featured_str  .= '        <tr>' . "\n";
$featured_str  .= '          <td height="60"><div  class="pageHeading"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products4_array[$i]['id'], 'NONSSL') . '">' . $featured_products4_array[$i]['name'] . '</a><br>'  . (tep_not_null($featured_products4_array[$i]['blurb'] && PRODUCT_BLURB == 'true') ? $featured_products4_array[$i]['blurb'] . '<br>' : '') . '</div></td>' . "\n";
$featured_str  .= '        </tr>' . "\n";
$featured_str  .= '        <tr>' . "\n";
$featured_str  .= '          <td height="70" valign="top">' . cre_product_short_description_template($featured_products4_array[$i]['id'], '100') . '</td>' . "\n";
$featured_str  .= '        </tr>' . "\n";
$featured_str  .= '        <tr>' . "\n";
$featured_str  .= '          <td>' . tep_image(DIR_WS_TEMPLATE_IMAGES . 'h-dots.png') . '</td>' . "\n";
$featured_str  .= '        </tr>' . "\n";
$featured_str  .= '        <tr>' . "\n";
$featured_str  .= '          <td><table width="158" border="0" cellspacing="0" cellpadding="0">' . "\n";
$featured_str  .= '              <tr>' . "\n";
$featured_str  .= '                <td width="69" align="center" class="price_mainpage">' . $products_price . '</td>' . "\n";
$featured_str  .= '                <td width="1" align="center">' . tep_image(DIR_WS_TEMPLATE_IMAGES . 'gray-div.png') . '</td>' . "\n";
$featured_str  .= '                <td width="88" align="center">';
if (hide_add_to_cart() == 'false' && group_hide_show_prices() == 'true') {
  $featured_str  .= '<a href="' . tep_href_link(FILENAME_SHOPPING_CART, tep_get_all_get_params(array('action','products_id')) . 'action=buy_now&products_id=' . $featured_products4_array[$i]['id']) . '">' . tep_image(DIR_WS_TEMPLATE_IMAGES . 'buy-now.png') . '</a>';
}
$featured_str  .= '</td>' . "\n";
$featured_str  .= '              </tr>' . "\n";
$featured_str  .= '            </table></td>' . "\n";
$featured_str  .= '        </tr>' . "\n";
$featured_str  .= '      </table></td>' . "\n";
$featured_str  .= '    <td width="6" align="right">' . tep_image(DIR_WS_TEMPLATE_IMAGES . 'mainpage_right.png') . '</td>' . "\n";
$featured_str  .= '  </tr>' . "\n";
$featured_str  .= '</table>' . "\n";

  $info_box_contents[$row][$col] = array('align' => 'center',
                                         'params' => 'width="50%" valign="top" style="padding-top:12px;"',
                                         'text' => $featured_str);
  $col ++;
  if ($col > 1) {
    $col = 0;
    $row ++;
  }
}
if($num) {
  new productListingBox($info_box_contents);
}
  }
?>
 <!-- featured_products eof -->