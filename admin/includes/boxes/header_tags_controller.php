<?php
/*
  $Id: header_tags_controller.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- header_tags_controller //-->
          <tr>
            <td>
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_HEADER_TAGS_CONTROLLER,
                   'link'  => tep_href_link(FILENAME_HEADER_TAGS_CONTROLLER, 'selected_box=header tags'));
if ($_SESSION['selected_box'] == 'header tags' || MENU_DHTML == 'True') {
  //RCI to include links  
  $returned_rci_top = $cre_RCI->get('headertagscontroller', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('headertagscontroller', 'boxesbottom');
  $contents[] = array('text'  => $returned_rci_top .
                                 tep_admin_files_boxes(FILENAME_HEADER_TAGS_CONTROLLER, BOX_HEADER_TAGS_ADD_A_PAGE, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_HEADER_TAGS_ENGLISH, BOX_HEADER_TAGS_ENGLISH, 'SSL','','2').
                                 tep_admin_files_boxes(FILENAME_HEADER_TAGS_FILL_TAGS,  BOX_HEADER_TAGS_FILL_TAGS, 'SSL','','2') .
                                 $returned_rci_bottom);
  }
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- header_tags_controller_eof //-->