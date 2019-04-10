<?php
/*
  $Id: administrator.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- administrator //-->
          <tr>
            <td>
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_ADMINISTRATOR,
                   'link'  => tep_href_link(FILENAME_ADMIN_MEMBERS, tep_get_all_get_params(array('selected_box')) . 'selected_box=administrator'));
if ($_SESSION['selected_box'] == 'administrator' || MENU_DHTML == 'True') {
  //RCI to include links 
  $returned_rci_top = $cre_RCI->get('administrator', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('administrator', 'boxesbottom');
  $contents[] = array('text'  => $returned_rci_top .
                                 tep_admin_files_boxes(FILENAME_ADMIN_MEMBERS, BOX_ADMINISTRATOR_MEMBERS, 'NONSSL','','2') .
                                 tep_admin_files_boxes(FILENAME_ADMIN_MEMBERS, BOX_ADMINISTRATOR_GROUPS,'NONSSL','gID=groups','2') .
                                 tep_admin_files_boxes(FILENAME_ADMIN_ACCOUNT, BOX_ADMINISTRATOR_ACCOUNT_UPDATE, 'NONSSL','','2') .
                                 tep_admin_files_boxes(FILENAME_ADMIN_FILES, BOX_ADMINISTRATOR_BOXES, 'NONSSL','','2') .
                                 $returned_rci_bottom);
  }
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- administrator_eof //-->