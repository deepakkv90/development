<?php
/*
  $Id: search.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

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
$faq_categories_query = tep_db_query("SELECT ic.categories_id, icd.categories_name 
                                        from " . TABLE_FAQ_CATEGORIES . " ic, 
                                             " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " icd 
                                      WHERE icd.categories_id = ic.categories_id 
                                        and icd.language_id = '" . (int)$languages_id . "' 
                                        and ic.categories_status = '1' 
                                        and ic.products_group_access like '%". $customer_group_id."%' 
                                      ORDER BY ic.categories_sort_order, icd.categories_name");
// faq outside categories
$faq_query = tep_db_query("SELECT ip.faq_id, ip.question 
                             from " . TABLE_FAQ . " ip 
                           LEFT JOIN " . TABLE_FAQ_TO_CATEGORIES . " ip2c 
                             on ip2c.faq_id = ip.faq_id 
                           WHERE ip2c.categories_id = '0' 
                             and ip.language = '" . (int)$languages_id . "' 
                             and ip.visible = '1' 
                           ORDER BY ip.v_order, ip.question");
if ((tep_db_num_rows($faq_categories_query) > 0) || (tep_db_num_rows($faq_query) > 0)) {
  ?>
  <!-- faq //-->
  <tr>
    <td>
      <?php
      $info_box_contents = array();
      $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_FAQ . '</font>');
      new $infobox_template_heading($info_box_contents, '', ((isset($column_location) && $column_location !='') ? $column_location : '') ); 
      $faq_string = '';
      while ($faq_categories = tep_db_fetch_array($faq_categories_query)) {
        $id_string = 'cID=' . $faq_categories['categories_id'];
        $faq_string .= '<a class="boxmenu" href="' . tep_href_link(FILENAME_FAQ, $id_string) . '">' . $faq_categories['categories_name'] . '</a>';
      }
      while ($faq = tep_db_fetch_array($faq_query)) {
        $id_string = 'fID=' . $faq['faq_id'];
        $faq_string .= '<a class="boxmenu" href="' . tep_href_link(FILENAME_FAQ, $id_string) . '">' . $faq['question'] . '</a>';
      }
      $info_box_contents = array();
      $info_box_contents[] = array('text'  => $faq_string);
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
  <!-- faq eof//--> 
  <?php
}
?>