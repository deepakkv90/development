<?php
/*
  $Id: links.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if(!isset($_SESSION['sppc_customer_group_id'])) {
  $customer_group_id = 'G';
} else {
 $customer_group_id = $_SESSION['sppc_customer_group_id'];
}
// check for link categoris to determine if there is anything to display
$link_categories_query = tep_db_query("select lc.link_categories_id, lcd.link_categories_name from " . TABLE_LINK_CATEGORIES . " lc, " . TABLE_LINK_CATEGORIES_DESCRIPTION . " lcd where lc.link_categories_id = lcd.link_categories_id and lc.link_categories_status = '1' and lcd.language_id = '" . (int)$languages_id . "' and lc.products_group_access like '%". $customer_group_id."%' order by lcd.link_categories_name");
$number_of_categories = tep_db_num_rows($link_categories_query);
if ($number_of_categories > 0) {
  ?>
  <!-- links --> 
  <tr>
    <td>
      <?php
      $info_box_contents = array();
      $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_LINKS . '</font>');
      new $infobox_template_heading($info_box_contents, tep_href_link(FILENAME_LINKS, '', 'NONSSL'), ((isset($column_location) && $column_location !='') ? $column_location : '') );
      $informationString = '';
      while($row = tep_db_fetch_array($link_categories_query)) {
        $lPath_new = 'lPath=' . $row['link_categories_id'];
        $informationString .= '<a href="' . tep_href_link(FILENAME_LINKS, $lPath_new) . '">' . $row['link_categories_name'] . '</a><br>';
      }
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left',
                                   'text'  => $informationString
                                  );
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
  <!-- links eof//-->
  <?php
}
?>