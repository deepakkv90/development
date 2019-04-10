<?php
/*

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- D mainpages_modules.new_products.php//-->
<?php

//Eversun mod for sppc and qty price breaks
  if(!isset($_SESSION['sppc_customer_group_id'])) {
  $customer_group_id = 'G';
  } else {
   $customer_group_id = $_SESSION['sppc_customer_group_id'];
  }
  $info_box_contents = array();
  $info_box_contents[] = array('text' => sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')));

  
      $new_products_query = tep_db_query("select distinct
                          p.products_id,
                          p.products_price, 
                          p.manufacturers_id,
                          pd.products_name, pd.products_blurb,
                          p.products_tax_class_id, 
                          if (isnull(pg.customers_group_price), p.products_price, pg.customers_group_price) as products_price,
                          p.products_date_added, 
                           p.products_image 
                          from ((" . TABLE_PRODUCTS . " p 
      left join " . TABLE_PRODUCTS_GROUPS . " pg on p.products_id = pg.products_id and pg.customers_group_id = '" . $customer_group_id . "') 
      left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id),
      " . TABLE_PRODUCTS_DESCRIPTION . " pd
      where 
       p.products_status = '1' 
       and pd.products_id = p.products_id
       and pd.language_id = '" . $languages_id . "' 
       and p.products_group_access like '%". $customer_group_id."%'
       and DATE_SUB(CURDATE(),INTERVAL " .NEW_PRODUCT_INTERVAL ." DAY) <= p.products_date_added  
                                   and p.products_group_access like '%". $customer_group_id."%'
       order by rand(), p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);

  


  $row = 0;
  $col = 0;
  $num = 0;
  while ($new_products = tep_db_fetch_array($new_products_query)) {
  
    $num ++;
      if ($num == 1) { 
  new contentBoxHeading($info_box_contents, tep_href_link(FILENAME_PRODUCTS_NEW));
      }

  $pf->loadProduct($new_products['products_id'],$languages_id);
        $products_price_s = $pf->getPriceStringShort();

  
    $info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                           'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $new_products['products_image'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . $new_products['products_name'] . '</a><br>' . (tep_not_null($new_products['products_blurb'] && PRODUCT_BLURB == 'true') ? $new_products['products_blurb']. '<br>' : '') . $products_price_s);


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
<!-- D new_products_eof //-->
