<?php
/*
  $Id: also_purchased_products.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  if(!isset($_SESSION['sppc_customer_group_id'])) {
  $customer_group_id = 'G';
  } else {
   $customer_group_id = $_SESSION['sppc_customer_group_id'];
  }
  
  if (isset($_GET['products_id'])) {
    
    $orders_query = tep_db_query("select 
                              p.products_id,
                              p.products_image, 
                              p.products_price, 
                              p.manufacturers_id,
                              pd.products_name,
                              p.products_tax_class_id, 
                              if (isnull(pg.customers_group_price), p.products_price, pg.customers_group_price) as products_price,
                              p.products_date_added, 
                           p.products_image 
    from " . TABLE_ORDERS_PRODUCTS . " opa, 
    " . TABLE_ORDERS_PRODUCTS . " opb, 
    " . TABLE_ORDERS . " o, 
    ((" . TABLE_PRODUCTS . " p 
          left join " . TABLE_PRODUCTS_GROUPS . " pg on p.products_id = pg.products_id and pg.customers_group_id = '" . $customer_group_id . "') 
      left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id),
      " . TABLE_PRODUCTS_DESCRIPTION . " pd
    where 
    opa.products_id = '" . (int)$_GET['products_id'] . "' 
    and opa.orders_id = opb.orders_id 
    and opb.products_id != '" . (int)$_GET['products_id'] . "' 
    and opb.products_id = p.products_id 
    and opb.orders_id = o.orders_id 
    and pd.products_id = p.products_id
    and pd.language_id = '" . $languages_id . "' 
    and p.products_status = '1' group by p.products_id
    and p.products_group_access like '%". $customer_group_id."%'
    order by rand(), o.date_purchased desc limit " . MAX_DISPLAY_ALSO_PURCHASED);
    
    $num_products_ordered = tep_db_num_rows($orders_query);
    
    if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {
?>
<!-- default also_purchased_products //-->
          <tr>
            <td>

<?php
      $info_box_contents = array();
      $info_box_contents[] = array('text' => TEXT_ALSO_PURCHASED_PRODUCTS);

      new contentBoxHeading($info_box_contents, '');

      $row = 0;
      $col = 0;
      $info_box_contents = array();
      while ($orders = tep_db_fetch_array($orders_query)) {
        $orders['products_name'] = tep_get_products_name($orders['products_id']);
        $info_box_contents[$row][$col] = array('align' => 'center',
                                               'params' => 'class="navBbrown" width="33%" valign="top"',
                                               'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $orders['products_image'], $orders['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . $orders['products_name'] . '</a>');

        $col ++;
        if ($col > 2) {
          $col = 0;
          $row ++;
        }
      }

      new contentBox($info_box_contents);
if (TEMPLATE_INCLUDE_FOOTER =='true'){
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                                'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                              );
 new contentBoxFooter($info_box_contents);

}
?>
           </td>
          </tr>
<!-- also_purchased_products_eof //-->
<?php
    }
  }
?>
