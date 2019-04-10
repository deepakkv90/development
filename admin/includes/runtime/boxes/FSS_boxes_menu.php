<?php
/*
  $Id: FSS_boxes_menu.php,v 2.0 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/                                      
global $selected_box, $menu_dhtml; 
if (defined('MODULE_ADDONS_FSS_STATUS') && MODULE_ADDONS_FSS_STATUS == 'True') {    
  $heading = array();
  $contents = array();
  $heading[] = array('text'  => BOX_HEADING_FSS,
                     'link'  => tep_href_link(FILENAME_FSS_FORMS_BUILDER, 'selected_box=fss_menu'));
  if (defined('PROJECT_VERSION') && ereg('6.2', PROJECT_VERSION)) {  
    if ($selected_box == 'fss_menu' || $menu_dhtml == true) {
      $contents[] = array('text'  => tep_admin_files_boxes(FILENAME_FSS_FORMS_BUILDER, BOX_FSS_FORMS_BUILDER, 'SSL', '', '0') . 
                                     tep_admin_files_boxes(FILENAME_FSS_POST_MANAGER, BOX_FSS_POST_MANAGER, 'SSL', '', '0') .
                                     tep_admin_files_boxes(FILENAME_FSS_CONFIG, BOX_FSS_CONFIG, 'SSL', 'gID=490', '0') .
                                     tep_admin_files_boxes(FILENAME_FSS_BACKUP_RESTORE, BOX_FSS_BACKUP_RESTORE));
    }
  } else {
    if ($_SESSION['selected_box'] == 'fss_menu' || MENU_DHTML == 'True') {
      $contents[] = array('text'  => tep_admin_files_boxes(FILENAME_FSS_FORMS_BUILDER, BOX_FSS_FORMS_BUILDER, 'SSL', '', '2') .  
                                     tep_admin_files_boxes(FILENAME_FSS_POST_MANAGER, BOX_FSS_POST_MANAGER, 'SSL', '', '2') . 
                                     tep_admin_files_boxes(FILENAME_FSS_CONFIG, BOX_FSS_CONFIG, 'SSL' , 'gID=490', '2') . 
                                     tep_admin_files_boxes(FILENAME_FSS_BACKUP_RESTORE, BOX_FSS_BACKUP_RESTORE, 'SSL' , '', '2'));
    }    
  }
  $box = new box;
  if (MENU_DHTML == 'True') {
    echo $box->menuBox($heading, $contents);
    return ;
  } else {
    $interm = $box->menuBox($heading, $contents);
    $rci .= '<!-- fss_menu //-->' . "\n";
    $rci .= '<tr>' . "\n";
    $rci .= '<td>' . "\n";
    $rci .= $interm;
    $rci .= '</td>' . "\n";
    $rci .= '</tr>' . "\n";
    $rci .= '<!-- fss_menu eof //-->' . "\n";
    return $rci;
  }
}
?>