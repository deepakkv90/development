<?php
/*
  $Id: faq.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- faq //-->
          <tr>
            <td>
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_FAQ,
                   'link'  => tep_href_link(FILENAME_FAQ_MANAGER, 'selected_box=faq'));
if ($_SESSION['selected_box'] == 'faq' || MENU_DHTML == 'True') {
  //RCI to include links  
  $returned_rci_top = $cre_RCI->get('faq', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('faq', 'boxesbottom');
  $contents[] = array('text'  => $returned_rci_top .
                                 tep_admin_files_boxes(FILENAME_FAQ_MANAGER, BOX_FAQ_MANAGER, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_FAQ_CATEGORIES, BOX_FAQ_CATEGORIES, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_FAQ_VIEW, BOX_FAQ_VIEW, 'SSL','','2') .
                                 tep_admin_files_boxes(FILENAME_FAQ_VIEW_ALL,BOX_FAQ_VIEW_ALL, 'SSL','','2') .
                                 $returned_rci_bottom);
}
$box = new box;
echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- faq-eof //--->