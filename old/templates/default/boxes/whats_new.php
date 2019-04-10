<?php
/*
  $Id: whats_new.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$whats = new box_whats_new();

if (count($whats->rows) > 0) {
?>
  <!-- whats_new //-->
  <tr>
    <td>
      <?php      
      $info_box_contents = array();
      $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_WHATS_NEW . '</font>');
      new $infobox_template_heading($info_box_contents, tep_href_link(FILENAME_PRODUCTS_NEW, '', 'NONSSL'), ((isset($column_location) && $column_location !='') ? $column_location : '') );
      $info_box_contents = array();
      foreach ($whats->rows as $whatsnew_product21) {
        $whatsnew_product21_id = $whatsnew_product21['products_id'];
        $whatsnew_product21_image = tep_get_products_image($whatsnew_product21['products_id']);
        $whatsnew_product21_name = tep_get_products_name($whatsnew_product21['products_id']);
        $pf->loadProduct($whatsnew_product21['products_id'],$languages_id);
        $whats_new_price = $pf->getPriceStringShort();
        $info_box_contents[] = array('align' => 'center', 
                                     'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $whatsnew_product21_id) . '">' . tep_image(DIR_WS_IMAGES . $whatsnew_product21_image, $whatsnew_product21_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $whatsnew_product21_id) . '">' . $whatsnew_product21_name . '</a><br>' . $whats_new_price .'<br>');
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
  <!-- whats_new eof//-->
  <?php
}
?>