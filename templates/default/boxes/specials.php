<?php
/*
  $Id: specials.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$specials = new box_specials();

if (count($specials->rows) > 0) {
?>
  <!-- specials //-->
  <tr>
    <td>
      <?php
      $info_box_contents = array();
      $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_SPECIALS . '</font>');
      new $infobox_template_heading($info_box_contents, tep_href_link(FILENAME_SPECIALS, '', 'NONSSL'), ((isset($column_location) && $column_location !='') ? $column_location : '') );
      $info_box_contents = array();
      foreach ($specials->rows as $product_specials22) {
        $product_specials22_id = $product_specials22['products_id'];
        $product_specials22_image = tep_get_products_image($product_specials22['products_id']);
        $product_specials22_name = tep_get_products_name($product_specials22['products_id']);
        $pf->loadProduct($product_specials22['products_id'],$languages_id);
        $special_random_price = $pf->getPriceStringShort();
        $info_box_contents[] = array('align' => 'center',
                                     'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_specials22_id) . '">' . tep_image(DIR_WS_IMAGES . $product_specials22_image, $product_specials22_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_specials22_id) . '">' . $product_specials22_name . '</a><br>' . $special_random_price);
      }
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
  <!-- specials eof//-->
  <?php
}
?>