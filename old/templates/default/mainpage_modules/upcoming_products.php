<?php
/*
  $Id: upcoming_products.php,v 1.1.1.3 2004/04/07 23:42:23 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- upcoming_products mainpage_modules //-->
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => sprintf(TABLE_HEADING_UPCOMING_PRODUCTS, strftime('%B')));

  if (!isset($_SESSION['sppc_customer_group_id'])) {
    $customer_group_id = 'G';
  } else {
    $customer_group_id = $_SESSION['sppc_customer_group_id'];
  }
  
  $expected_query_raw= tep_db_query("select p.products_id, pd.products_name, p.products_image, pd.products_blurb, products_date_available as date_expected,
     if (isnull(pg.customers_group_price), p.products_price, pg.customers_group_price) as products_price
     from (" . TABLE_PRODUCTS . " p 
     left join " . TABLE_PRODUCTS_GROUPS . " pg on p.products_id = pg.products_id and pg.customers_group_id = '" . $customer_group_id . "'),
           " . TABLE_PRODUCTS_DESCRIPTION . " pd 
     where to_days(products_date_available) >= to_days(now()) 
       and p.products_id = pd.products_id 
       and pd.language_id = '" . (int)$languages_id . "' 
       and p.products_group_access like '%". $customer_group_id."%'
     order by rand()
     limit " . MAX_DISPLAY_UPCOMING_PRODUCTS);

  $row = 0;
  $col = 0;
  $num = 0;
  while ($expected_query = tep_db_fetch_array($expected_query_raw)) {
    $num ++;
    if ($num == 1) { 
      new contentBoxHeading($info_box_contents, tep_href_link(FILENAME_UPCOMING_PRODUCTS));
    }
    $pf->loadProduct($expected_query['products_id'],$languages_id);
    $products_price_s = $pf->getPriceStringShort();
    
    $duedate= str_replace("00:00:00", "" , $expected_query['date_expected']);  
   
    $info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                           'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $expected_query['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $expected_query['products_image'], $expected_query['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $expected_query['products_id']) . '">' . $expected_query['products_name'] . '</a><br>' . (tep_not_null($expected_query['products_blurb'] && PRODUCT_BLURB == 'true') ? $expected_query['products_blurb'] . '<br>' : '') . $products_price_s . '<br>' . TABLE_HEADING_DATE_EXPECTED . '&nbsp;' . $duedate);


    $col ++;
    if ($col > 2) {
      $col = 0;
      $row ++;
    }
  }

  if ($num) {
    new contentBox($info_box_contents, true, true);
  
    if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true') {
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left',
                                   'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                   );
      new contentBoxFooter($info_box_contents);
    }
  }
?>
<!--D upcoming_products_eof //-->