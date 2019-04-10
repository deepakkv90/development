
<?php
/*
$Id: xsell_products.php, v1  2002/09/11

osCommerce, Open Source E-Commerce Solutions
<http://www.oscommerce.com>

Copyright (c) 2002 osCommerce

Released under the GNU General Public License
*/


if ($_GET['products_id']) {

$xsell_query = tep_db_query("select distinct 
   p.products_id,
   p.products_image, 
   p.products_price, 
   p.manufacturers_id,
   pd.products_name,
   p.products_tax_class_id,
   p.products_date_added, 
   p.products_image ,
   xp.xsell_id
   products_price 
   from " . TABLE_PRODUCTS_XSELL . " xp,
     " . TABLE_PRODUCTS . " p, 
   " . TABLE_PRODUCTS_DESCRIPTION . " pd 
where 
xp.products_id = '" . $_GET['products_id'] . "' 
and p.products_id = xp.xsell_id
and pd.products_id = xp.xsell_id
and pd.language_id = '" . $languages_id . "' 
and p.products_status = '1' 
order by rand(), xp.products_id asc limit " . MAX_DISPLAY_XSELL);


$num_products_xsell = tep_db_num_rows($xsell_query);

if ($num_products_xsell >= MIN_DISPLAY_XSELL) {
  require(DIR_WS_LANGUAGES . $language . '/modules/xsell_products.php');
?>
<!-- xsell_products //-->
   <tr>
            <td>

<?php
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left', 'text' => TEXT_XSELL_PRODUCTS);
       new  contentBoxHeading($info_box_contents,'');

      $row = 0;
      $col = 0;
      $info_box_contents = array();
      while ($xsell = tep_db_fetch_array($xsell_query)) {

  $pf->loadProduct($xsell['products_id'],$languages_id);
        $xsell_price = $pf->getPriceStringShort();
  
  $s_buy_now = '';
  $hide_add_to_cart = hide_add_to_cart();
  if ($hide_add_to_cart == 'false' && group_hide_show_prices() == 'true') {
    $s_buy_now = '<br><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action','cPath','products_id')) . 'action=buy_now&products_id=' . $xsell['products_id'] . '&cPath=' . tep_get_product_path($xsell['products_id']), 'NONSSL') . '">' . tep_template_image_button('button_buy_now.gif', TEXT_BUY . $xsell['products_name'] . TEXT_NOW) .'</a>';
  }

        $info_box_contents[$row][$col] = array('align' => 'center',
                                               'params' => 'class="smallText" width="33%" valign="top"',
                                               'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $xsell['products_image'], $xsell['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">' . $xsell['products_name'] .'</a><br>' . $xsell_price. $s_buy_now);
        $col ++;
        if ($col > 2) {
          $col = 0;
          $row ++;
        }
      }
      
if (($num_products_xsell >= MIN_DISPLAY_XSELL) && ($num_products_xsell > 0)) { 
      new contentBox($info_box_contents);
if (TEMPLATE_INCLUDE_FOOTER =='true'){
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                                'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                              );
 new contentBoxFooter($info_box_contents);

}
}
?>
           </td>
          </tr>
<!-- xsell_products_eof //-->




<?php
    }
  }
?>
