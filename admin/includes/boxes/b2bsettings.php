<?php
/*
  $Id: b2bsettings.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- b2bsettings //-->
          <tr>
            <td>
<?php
$cg_check_sql = "select configuration_group_id from " . TABLE_CONFIGURATION_GROUP . " where configuration_group_title = '" . tep_db_input('B2B Configuration') . "'";
$cg_check_query = tep_db_query($cg_check_sql);
if (tep_db_num_rows($cg_check_query) > 0 ) {
  $cg_check = tep_db_fetch_array($cg_check_query);
  $cg_id = $cg_check['configuration_group_id'];
  $heading = array();
  $contents = array();
  $heading[] = array('text'  => BOX_HEADING_B2BSETTINGS,
                     'link'  => tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $cg_id . '&selected_box=b2bsettings'));
  if ($_SESSION['selected_box'] == 'b2bsettings' || MENU_DHTML == 'True') {
    //RCI to include links 
    $returned_rci_top = $cre_RCI->get('b2bsettings', 'boxestop');
    $returned_rci_bottom = $cre_RCI->get('b2bsettings', 'boxesbottom');
    $contents[] = array('text'  => $returned_rci_top .
                                   tep_admin_files_boxes(FILENAME_CONFIGURATION, BOX_B2BSETTINGS_STORE_SETTINGS, 'SSL','gID=' . $cg_id,'2').
                                   tep_admin_files_boxes(FILENAME_CUSTOMERS_GROUPS, BOX_B2BSETTINGS_CUSTOMERS_GROUPS, 'SSL','','2') .
                                   $returned_rci_bottom);
   }
    $box = new box;
    echo $box->menuBox($heading, $contents);
}
?>
            </td>
          </tr>
<!-- b2bsettings_eof //-->