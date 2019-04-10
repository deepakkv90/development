<?php
/*
  $Id: taxes.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- taxes //-->
          <tr>
            <td>
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_LOCATION_AND_TAXES,
                   'link'  => tep_href_link(FILENAME_COUNTRIES, 'selected_box=taxes'));
if ($_SESSION['selected_box'] == 'taxes' || MENU_DHTML == 'True') {
  //RCI to include links  
  $returned_rci_top = $cre_RCI->get('taxes', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('taxes', 'boxesbottom');
  $contents[] = array('text'  => $returned_rci_top .
                                 tep_admin_files_boxes(FILENAME_COUNTRIES, BOX_TAXES_COUNTRIES, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_ZONES, BOX_TAXES_ZONES, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_GEO_ZONES, BOX_TAXES_GEO_ZONES, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_TAX_CLASSES, BOX_TAXES_TAX_CLASSES, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_TAX_RATES, BOX_TAXES_TAX_RATES, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_WAREHOUSE_ZONES, BOX_WAREHOUSE_ZONES, 'SSL','','2') . 
                                 $returned_rci_bottom);
  }
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- taxes_eof //-->