<?php
/*
  $Id: data.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- data //-->
          <tr>
            <td>
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_DATA,
                   'link'  => tep_href_link(FILENAME_EASYPOPULATE_EXPORT, 'selected_box=data'));
if ($_SESSION['selected_box'] == 'data' || MENU_DHTML == 'True') {
  //RCI to include links  
  $returned_rci_top = $cre_RCI->get('data', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('data', 'boxesbottom');
  $returned_rci_top2 = $cre_RCI->get('data', 'boxestop2');
  $returned_rci_bottom2 = $cre_RCI->get('data', 'boxesbottom2');
  $returned_rci_top3 = $cre_RCI->get('data', 'boxestop3');
  $returned_rci_bottom3 = $cre_RCI->get('data', 'boxesbottom3');
  $returned_rci_top4 = $cre_RCI->get('data', 'boxestop4');
  $contents[] = array('text'  => $returned_rci_top .
                                 tep_admin_files_boxes('', BOX_DATA_EASYPOPULATE) .
                                 $returned_rci_top2 .
                                 tep_admin_files_boxes(FILENAME_EASYPOPULATE_EXPORT, BOX_DATA_EASYPOPULATE_EXPORT, 'NONSSL' , '', '2') .
                                 tep_admin_files_boxes(FILENAME_EASYPOPULATE_IMPORT, BOX_DATA_EASYPOPULATE_IMPORT, 'NONSSL' , '', '2') .
                                 tep_admin_files_boxes(FILENAME_EASYPOPULATE_OPTIONS_EXPORT, BOX_DATA_EASYPOPULATE_OPTIONS_EXPORT, 'NONSSL' , '', '2') .
                                 tep_admin_files_boxes(FILENAME_EASYPOPULATE_OPTIONS_IMPORT, BOX_DATA_EASYPOPULATE_OPTIONS_IMPORT, 'NONSSL' , '', '2') .
                                 $returned_rci_bottom2 .
                                 tep_admin_files_boxes('', BOX_DATA_EASYPOPULATE_BASIC) .
                                 $returned_rci_top3 .
                                 tep_admin_files_boxes(FILENAME_EASYPOPULATE_BASIC_EXPORT, BOX_DATA_EASYPOPULATE_BASIC_EXPORT, 'NONSSL' , '', '2') .
                                 tep_admin_files_boxes(FILENAME_EASYPOPULATE_BASIC_IMPORT, BOX_DATA_EASYPOPULATE_BASIC_IMPORT, 'NONSSL' , '', '2') .
                                 $returned_rci_bottom3 .
                                 tep_admin_files_boxes('', BOX_DATA) .
                                 $returned_rci_top4 .
                                 //tep_admin_files_boxes(FILENAME_GOOGLE_ADMIN, BOX_FEEDERS_GOOGLE, 'NONSSL','','2') .
                                 tep_admin_files_boxes(FILENAME_DATA, BOX_DATA_HELP, 'NONSSL','','2') .
                                 $returned_rci_bottom);
  }
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- data_eof //-->