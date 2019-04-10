<?php
/*
  $Id: gv_admin.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- gv_admin //-->
          <tr>
            <td>
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_GV_ADMIN,
                   'link'  => tep_href_link(FILENAME_COUPON_ADMIN, 'selected_box=gv_admin'));
if ($_SESSION['selected_box'] == 'gv_admin' || MENU_DHTML == 'True') {
  //RCI to include links  
  $returned_rci_top = $cre_RCI->get('gvadmin', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('gvadmin', 'boxesbottom');
  $contents[] = array('text'  => $returned_rci_top .
                                 tep_admin_files_boxes(FILENAME_COUPON_ADMIN , BOX_COUPON_ADMIN, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_GV_REPORT , BOX_GV_REPORT, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_GV_QUEUE , BOX_GV_ADMIN_QUEUE, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_GV_MAIL , BOX_GV_ADMIN_MAIL, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_GV_SENT , BOX_GV_ADMIN_SENT, 'SSL','','2') .
                                 $returned_rci_bottom);
 }
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- gv_admin_eof //-->