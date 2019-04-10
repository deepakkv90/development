<?php
/*
  $Id: shop_by_price.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('MODULE_SHOPBYPRICE_RANGES') && MODULE_SHOPBYPRICE_RANGES > 0) {
  ?>
  <!-- shop by price //-->  
  <tr>
    <td>
      <?php
      require_once(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOP_BY_PRICE);
      $info_box_contents = array();
      $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_SHOP_BY_PRICE . '</font>');
      new $infobox_template_heading($info_box_contents, '', ((isset($column_location) && $column_location !='') ? $column_location : '') ); 
      $info_box_contents = array();
      $sbp_array = unserialize(MODULE_SHOPBYPRICE_RANGE);
      $info_box_contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_SHOP_BY_PRICE, 'range=0', 'NONSSL') . '">' . TEXT_INFO_UNDER . $currencies->format($sbp_array[0]) . '</a><br>');
      for ($i=1, $ii=count($sbp_array); $i < $ii; $i++) {
        $info_box_contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_SHOP_BY_PRICE, 'range=' . $i, 'NONSSL') . '">' . TEXT_INFO_FROM . $currencies->format($sbp_array[$i-1]) . TEXT_INFO_TO . $currencies->format($sbp_array[$i]) . '</a><br>');
      }
      if (defined('MODULE_SHOPBYPRICE_OVER') && MODULE_SHOPBYPRICE_OVER == True) {
        $info_box_contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_SHOP_BY_PRICE, 'range=' . $i, 'NONSSL') . '">' . $currencies->format($sbp_array[$i-1]) . TEXT_INFO_ABOVE . '</a><br>');
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
  <?php
}
?>
<!-- shop_by_price eof//-->