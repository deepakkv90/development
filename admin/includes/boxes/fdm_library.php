<?php
/*
  $Id: FDMS_boxes_menu.php, v 1.2 2008/12/11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
global $selected_box, $menu_dhtml; 

if (defined('MODULE_ADDONS_FDM_STATUS') && MODULE_ADDONS_FDM_STATUS == 'True') {
  $heading = array();
  $contents = array();
  $heading[] = array('text'  => BOX_HEADING_LIBRARY,
                     'link'  => tep_href_link(FILENAME_LIBRARY_FILES, 'selected_box=fdm_library'));
    if (defined('PROJECT_VERSION') && ereg('6.2', PROJECT_VERSION)) {
      if ($selected_box == 'fdm_library' || $menu_dhtml == true) {
        $contents[] = array('text'  => tep_admin_files_boxes(FILENAME_LIBRARY_FILES, BOX_LIBRARY_FILES, 'SSL' , '', '0') .
                                       tep_admin_files_boxes(FILENAME_LIBRARY_SCAN,BOX_LIBRARY_SCAN, 'SSL' , '', '0') .
                                       tep_admin_files_boxes(FILENAME_FILE_ICONS, BOX_FILE_ICONS, 'SSL' , '', '0') . 
                                       tep_admin_files_boxes(FILENAME_FEATURED_FILES, BOX_FEATURED_FILES, 'SSL' , '', '0') . 
                                       tep_admin_files_boxes(FILENAME_LIBRARY_BACKUP, BOX_LIBRARY_FILES_BACKUP, 'SSL' , '', '0') .
                                       tep_admin_files_boxes(FILENAME_FDM_CONFIG, BOX_LIBRARY_FILES_LISTING, 'SSL','gID=450', '0') .
                                       tep_admin_files_boxes('',BOX_HEADING_LIBRARY_REPORTS , 'SSL' , '', '0') .
                                       tep_admin_files_boxes(FILENAME_CUSTOMER_TOP_DOWNLOADS, BOX_HEADER_CUSTOMER_TOP_DOWNLOADS, 'SSL' , '', '2') .
                                       tep_admin_files_boxes(FILENAME_TOP_DOWNLOADS, BOX_HEADER_TOP_DOWNLOADS, 'SSL' , '', '2') .
                                       tep_admin_files_boxes(FILENAME_ATTACHED_FILES, BOX_HEADER_ATTACHED_FILES, 'SSL' , '', '2') .        
                                       tep_admin_files_boxes(FILENAME_ATTACHED_PROD_FILES, BOX_HEADER_PROD_ATTACHED_FILES, 'SSL' , '', '2') .
                                       tep_admin_files_boxes(FILENAME_PRODUCTS_NO_FILES, BOX_HEADER_PRODUCTS_NO_FILES, 'SSL' , '', '2') .  
                                       tep_admin_files_boxes(FILENAME_DOWNLOAD_LOG, BOX_HEADER_DOWNLOAD_LOG, 'SSL' , '', '2') . 
                                       tep_admin_files_boxes(FILENAME_DAILY_DOWNLOADS, BOX_HEADER_DAILY_DOWNLOADS, 'SSL' , '', '2') 
                           );
      }
    } else {
      if ($_SESSION['selected_box'] == 'fdm_library' || MENU_DHTML == 'True') { 
        $contents[] = array('text'  => tep_admin_files_boxes(FILENAME_LIBRARY_FILES, BOX_LIBRARY_FILES, 'SSL' , '', '2') .
                                       tep_admin_files_boxes(FILENAME_LIBRARY_SCAN,BOX_LIBRARY_SCAN, 'SSL' , '', '2') .
                                       tep_admin_files_boxes(FILENAME_FILE_ICONS, BOX_FILE_ICONS, 'SSL' , '', '2') . 
                                       tep_admin_files_boxes(FILENAME_FEATURED_FILES, BOX_FEATURED_FILES, 'SSL' , '', '2') . 
                                       tep_admin_files_boxes(FILENAME_LIBRARY_BACKUP, BOX_LIBRARY_FILES_BACKUP, 'SSL' , '', '2') .
                                       tep_admin_files_boxes(FILENAME_FDM_CONFIG, BOX_LIBRARY_FILES_LISTING, 'SSL','gID=450', '2') .
                                       tep_admin_files_boxes('',BOX_HEADING_LIBRARY_REPORTS , 'SSL' , '', '0') .
                                       tep_admin_files_boxes(FILENAME_CUSTOMER_TOP_DOWNLOADS, BOX_HEADER_CUSTOMER_TOP_DOWNLOADS, 'SSL' , '', '4') .
                                       tep_admin_files_boxes(FILENAME_TOP_DOWNLOADS, BOX_HEADER_TOP_DOWNLOADS, 'SSL' , '', '4') .
                                       tep_admin_files_boxes(FILENAME_ATTACHED_FILES, BOX_HEADER_ATTACHED_FILES, 'SSL' , '', '4') .        
                                       tep_admin_files_boxes(FILENAME_ATTACHED_PROD_FILES, BOX_HEADER_PROD_ATTACHED_FILES, 'SSL' , '', '4') .
                                       tep_admin_files_boxes(FILENAME_PRODUCTS_NO_FILES, BOX_HEADER_PRODUCTS_NO_FILES, 'SSL' , '', '4') .  
                                       tep_admin_files_boxes(FILENAME_DOWNLOAD_LOG, BOX_HEADER_DOWNLOAD_LOG, 'SSL' , '', '4') . 
                                       tep_admin_files_boxes(FILENAME_DAILY_DOWNLOADS, BOX_HEADER_DAILY_DOWNLOADS, 'SSL' , '', '4')    
                           );
    }
  }
  $box = new box;
  echo $box->menuBox($heading, $contents);
}
?>