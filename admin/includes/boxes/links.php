<?php
/*
  $Id: links.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- links //-->
          <tr>
            <td>
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_LINKS,
                   'link'  => tep_href_link(FILENAME_LINKS, 'selected_box=links'));
if ($_SESSION['selected_box'] == 'links' || MENU_DHTML == 'True') {
  //RCI to include links  
  $returned_rci_top = $cre_RCI->get('links', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('links', 'boxesbottom');
  $contents[] = array('text'  => $returned_rci_top .
                                 tep_admin_files_boxes(FILENAME_LINKS, BOX_LINKS_LINKS, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_LINK_CATEGORIES, BOX_LINKS_LINK_CATEGORIES, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_LINKS_CONTACT, BOX_LINKS_LINKS_CONTACT, 'SSL','','2') .
                                 $returned_rci_bottom);
  }
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- links_eof //-->