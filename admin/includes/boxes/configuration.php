<?php
/*
  $Id: configuration.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- configuration //-->
          <tr>
            <td>
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_CONFIGURATION,
                   'link'  => tep_href_link(FILENAME_CONFIGURATION, 'gID=1&selected_box=configuration'));
if ($_SESSION['selected_box'] == 'configuration' || MENU_DHTML == 'True') {
  $cfg_groups = '';
  $configuration_groups_query = tep_db_query("select configuration_group_id as cgID, configuration_group_title as cgTitle from " . TABLE_CONFIGURATION_GROUP . " where visible = '1' order by sort_order");
  while ($configuration_groups = tep_db_fetch_array($configuration_groups_query)) {
    $cfg_groups .=  tep_admin_files_boxes(FILENAME_CONFIGURATION,  $configuration_groups['cgTitle'], 'NONSSL', 'gID=' . $configuration_groups['cgID'],'2');
  }
  //RCI to include links  
  $returned_rci_top = $cre_RCI->get('configuration', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('configuration', 'boxesbottom');
  $contents[] = array('text'  => $returned_rci_top .
                                 $cfg_groups .
                                 $returned_rci_bottom);
  }
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- configuration_eof //-->