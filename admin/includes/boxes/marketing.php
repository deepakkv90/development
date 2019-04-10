<?php
/*
  $Id: marketing.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- marketing //-->
          <tr>
            <td>
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_MARKETING,
                   'link'  => tep_href_link(FILENAME_EVENTS_MANAGER, 'selected_box=marketing'));
if ($_SESSION['selected_box'] == 'marketing' || MENU_DHTML == 'True') {
  //RCI to include links  
  $returned_rci_top = $cre_RCI->get('marketing', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('marketing', 'boxesbottom');
  $contents[] = array('text'  => $returned_rci_top .
                                 tep_admin_files_boxes(FILENAME_EVENTS_MANAGER, BOX_MARKETING_EVENTS_MANAGER, 'SSL', '', '2').
                                 tep_admin_files_boxes(FILENAME_BANNER_MANAGER, BOX_MARKETING_BANNER_MANAGER, 'SSL', '', '2') .
                                 tep_admin_files_boxes(FILENAME_SPECIALS, BOX_MARKETING_SPECIALS, 'SSL', '', '2') .
                                 tep_admin_files_boxes(FILENAME_SPECIALSBYCAT, BOX_MARKETING_SPECIALSBYCAT, 'SSL', '', '2') .
                                 tep_admin_files_boxes(FILENAME_NEWSLETTERS, BOX_TOOLS_NEWSLETTER_MANAGER, 'SSL', '', '2') .
                                 $returned_rci_bottom);
  }
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- marketing_eof //-->