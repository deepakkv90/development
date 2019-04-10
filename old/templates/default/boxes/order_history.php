<?php
/*
  $Id: order_history.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$history = new box_order_history();

if (count($history->rows) > 0) {
?>
    <!-- order_history //-->
    <tr>
      <td>
        <?php
        $info_box_contents = array();
        $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_CUSTOMER_ORDERS . '</font>');
        new $infobox_template_heading($info_box_contents, '', ((isset($column_location) && $column_location !='') ? $column_location : '') );
        foreach ($history->rows as $products) {
          // changes the cust_order into a buy_now action
          $customer_orders_string .= '  <tr>' .
                                     '    <td class="infoBoxContents"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id']) . '">' . $products['products_name'] . '</a></td>' .
                                     '    <td class="infoBoxContents" align="right" valign="top"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action','cPath','products_id')) . 'action=buy_now&products_id=' . $products['products_id'] . '&cPath=' . tep_get_product_path($products['products_id'])) . '">' . tep_image(DIR_WS_ICONS . 'cart.gif', ICON_CART) . '</a></td>' .
                                     '  </tr>';
        }
        $customer_orders_string .= '<br>';
        $info_box_contents = array();
        $info_box_contents[] = array('text' => $customer_orders_string);
        new $infobox_template($info_box_contents, true, true, ((isset($column_location) && $column_location !='') ? $column_location : '') );
        if (TEMPLATE_INCLUDE_FOOTER =='true'){
          $info_box_contents = array();
          $info_box_contents[] = array('align' => 'left',
                                       'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                      );
          new $infobox_template_footer($info_box_contents, ((isset($column_location) && $column_location !='') ? $column_location : '') );
        }
        ?>
      </td>
    </tr>
    <!-- order_history_eof //-->
<?php
}
?>