<?php
/*
  $Id: vendors.php,v 1.1 2008/06/22 22:50:52 datazen Exp $

  Modified for MVS V1.0 2006/03/25 JCK/CWG
  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- vendors //-->
          <tr>
            <td>
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_VENDORS,
                   'link'  => tep_href_link(FILENAME_VENDORS, 'selected_box=vendors'));
if ($_SESSION['selected_box'] == 'vendors' || MENU_DHTML == 'True') {
  //RCI to include links 
  $returned_rci_top = $cre_RCI->get('data', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('data', 'boxesbottom');
  $contents[] = array('text'  => $returned_rci_top . 
                                 tep_admin_files_boxes(FILENAME_VENDORS, BOX_VENDORS, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_PRODS_VENDORS, BOX_VENDORS_REPORTS_PROD, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_ORDERS_VENDORS, BOX_VENDORS_ORDERS, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_MOVE_VENDORS, BOX_MOVE_VENDOR_PRODS, 'SSL','','2') .
                                 $returned_rci_bottom);
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- vendors_eof //-->