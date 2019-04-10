<?php
/*
  $Id: featured.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$featured = new box_featured();

if ($featured->row_count > 0) {
?>
  <!-- featured -->
  <tr>
    <td>
      <?php
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left',
                                   'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_FEATURED . '</font>'
                                  );
      new $infobox_template_heading($info_box_contents, tep_href_link(FILENAME_FEATURED_PRODUCTS, '', 'NONSSL'), ((isset($column_location) && $column_location !='') ? $column_location : '') );
      
      $featured_product21_id = $featured->rows[0]['products_id'];
      $featured_product21_image = $featured->rows[0]['products_image'];
      $featured_product21_name = tep_get_products_name($featured->rows[0]['products_id']);
      $pf->loadProduct($featured->rows[0]['products_id'],$languages_id);
      $featured_price1 = $pf->getPriceStringShort();
      
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'center',
                                    'text'  => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_product21_id) . '">' . tep_image(DIR_WS_IMAGES . $featured_product21_image, $featured_product21_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_product21_id, 'NONSSL') . '">' . $featured_product21_name . '</a><br>' . $featured_price1
                                  );
      new $infobox_template($info_box_contents, true, true, ((isset($column_location) && $column_location !='') ? $column_location : '') );
      
      if (TEMPLATE_INCLUDE_FOOTER == 'true') {
        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left',
                                     'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                    );
        new $infobox_template_footer($info_box_contents, ((isset($column_location) && $column_location !='') ? $column_location : '') );
      } 
      ?>
    </td>
  </tr>
  <?php
}
?>
<!-- featured eof//-->