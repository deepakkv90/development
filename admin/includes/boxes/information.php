<?php
/*
  $Id: information.php,v 2.0 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- information [modified for CDS]//-->
          <tr>
            <td>
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_CDS_HEADING,
                   'link'  => tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'selected_box=information'));
if (defined('PROJECT_VERSION') && ereg('6.2', PROJECT_VERSION)) {
  if ($selected_box == 'information' || $menu_dhtml == true) {
  
    //RCI start
    $returned_rci_top = $cre_RCI->get('information', 'boxestop');
    $returned_rci_bottom = $cre_RCI->get('information', 'boxesbottom');
    $contents[] = array('text'  => $returned_rci_top . 
                                   tep_admin_files_boxes(FILENAME_CDS_PAGE_MANAGER, BOX_CDS_PAGE_MANAGER, 'SSL', '', '0') . 
                                   tep_admin_files_boxes(FILENAME_CDS_CONFIGURATION, BOX_CDS_CONFIGUARION, 'SSL', 'gID=480', '0') .
                                   tep_admin_files_boxes(FILENAME_CDS_BACKUP_RESTORE, BOX_CDS_BACKUP_RESTORE, 'SSL' , '', '0') .
                                   tep_admin_files_boxes('', BOX_CDS_FAQ) .
                                   tep_admin_files_boxes(FILENAME_FAQ_MANAGER, BOX_FAQ_MANAGER, 'SSL' , '', '2') .
                                   tep_admin_files_boxes(FILENAME_FAQ_CATEGORIES, BOX_FAQ_CATEGORIES, 'SSL' , '', '2') .
                                   tep_admin_files_boxes(FILENAME_DEFINE_MAINPAGE, BOX_CATALOG_DEFINE_MAINPAGE, 'SSL', '', '0') .
                                   tep_admin_files_boxes(FILENAME_INFORMATION_MANAGER, BOX_INFORMATION_MANAGER, 'SSL', '', '0')  .
                                   $returned_rci_bottom);
    //RCI eof
  }    
} else {
  if ($_SESSION['selected_box'] == 'information' || MENU_DHTML == 'True') { 
    //RCI start
    $returned_rci_top = $cre_RCI->get('information', 'boxestop');
    $returned_rci_bottom = $cre_RCI->get('information', 'boxesbottom');
    $contents[] = array('text'  => $returned_rci_top . 
                                   tep_admin_files_boxes(FILENAME_CDS_PAGE_MANAGER, BOX_CDS_PAGE_MANAGER, 'SSL', '', '2') . 
                                   tep_admin_files_boxes(FILENAME_CDS_CONFIGURATION, BOX_CDS_CONFIGUARION, 'SSL', 'gID=480', '2') .
                                   tep_admin_files_boxes(FILENAME_CDS_BACKUP_RESTORE, BOX_CDS_BACKUP_RESTORE, 'SSL' , '', '2') .
                                   tep_admin_files_boxes('', '&nbsp;&nbsp;' . BOX_CDS_FAQ) .
                                   tep_admin_files_boxes(FILENAME_FAQ_MANAGER, BOX_FAQ_MANAGER, 'SSL' , '', '4') .
                                   tep_admin_files_boxes(FILENAME_FAQ_CATEGORIES, BOX_FAQ_CATEGORIES, 'SSL' , '', '4') .
                                   tep_admin_files_boxes(FILENAME_DEFINE_MAINPAGE, BOX_CATALOG_DEFINE_MAINPAGE, 'SSL', '', '2') .
                                   tep_admin_files_boxes(FILENAME_DEFINE_HOMEPAGE, BOX_CATALOG_DEFINE_HOMEPAGE, 'SSL', '', '2') .
                                   $returned_rci_bottom);
    //RCI eof
  }
}  
$box = new box;
echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- information_eof [modified for CDS]//-->