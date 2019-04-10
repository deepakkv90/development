<?php
/*
  $Id: FDMS_headertags_addswitch.php,v 1.0.0.0 2007/07/26 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
global $languages_id, $tags_array;
if (defined('MODULE_ADDONS_FDM_STATUS') && MODULE_ADDONS_FDM_STATUS == 'True') { 
// FDM_FOLDER_FILES.PHP / FDM_FILE_DETAIL.PHP
if ( strstr($_SERVER['SCRIPT_NAME'],'fdm_folder_files.php') || strstr($_SERVER['SCRIPT_NAME'],'fdm_file_detail.php') ){
  $current_folder_id  = isset($_GET['fPath']) ? (int)end(explode('_',$_GET['fPath'])) : 0;
  $current_file_id = isset($_GET['file_id']) ? (int)$_GET['file_id'] : 0;

  if (($current_folders_id == 0) && ($current_file_id == 0)) return;

  if ($current_folder_id != 0) {
    $folder_query = tep_db_query("SELECT lfd.folders_head_title_tag, lfd.folders_head_desc_tag, lfd.folders_head_keywords_tag 
                                                   from " . TABLE_LIBRARY_FOLDERS . " lf, 
                                                          " . TABLE_LIBRARY_FOLDERS_DESCRIPTION . " lfd 
                                                 WHERE lf.folders_id = '" . (int)$current_folder_id . "' 
                                                   and lfd.folders_id = '" . (int)$current_folder_id . "' 
                                                   and ifd.language_id = '" . (int)$languages_id . "'");
    $folder = tep_db_fetch_array($folder_query);
  }

  if ($current_file_id != 0) { 
    $file_query= tep_db_query("SELECT lfd.files_head_title_tag, lfd.files_head_desc_tag, lfd.files_head_keywords_tag  
                                                 from " . TABLE_LIBRARY_FILES . " lf,
                                                      " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfd  
                                                              WHERE lf.files_id = '" . (int)$current_file_id . "' 
                                                                and lfd.files_id = '" . (int)$current_file_id . "' 
                                                                and lfd.language_id = '" . (int)$languages_id . "'");
    $file = tep_db_fetch_array($file_query);
  }

  if (tep_not_null($folder['folders_head_desc_tag'])) {
    $tags_array['desc'] .= ' - ' . $folder['folders_head_desc_tag'];
  } else {
    if (tep_not_null($file['files_head_desc_tag'])) {
      $tags_array['desc'] .= ' - ' . $file['files_head_desc_tag'];
    }
  }
  // clean up the description string
  if (substr($tags_array['desc'], 0, 3) == " - ") {
    $tags_array['desc'] = substr($tags_array['desc'], 3, strlen($tags_array['desc']));
  }

  if (tep_not_null($folder['folders_head_keywords_tag'])) {
    $tags_array['keywords'] .= ', ' . $folder['folders_head_keywords_tag'];
  } else {
    if (tep_not_null($file['files_head_keywords_tag'])) {
      $tags_array['keywords'] .= ', ' . $file['pages_head_keywords_tag'];
    }
  }
  // clean the the keyword string
  if (substr($tags_array['keywords'], 0, 1) == chr(32)) {
    $tags_array['keywords'] = substr($tags_array['keywords'], 1, strlen($tags_array['keywords']));
  }
  if (substr($tags_array['keywords'], 0, 2) == ", ") {
    $tags_array['keywords'] = substr($tags_array['keywords'], 2, strlen($tags_array['keywords']));
  }

  if (tep_not_null($folder['folders_head_title_tag'])) {
    $tags_array['title'] .= ' - ' . $folder['folders_head_title_tag'];
  } else {
    if (tep_not_null( $file['files_head_title_tag'])) {
      $tags_array['title'] .= ' - ' . $file['files_head_title_tag'];
    }
  }
 // clean up the title string
  if (substr($tags_array['title'], 0, 3) == " - ") {
    $tags_array['title'] = substr($tags_array['title'], 3, strlen($tags_array['title']));
  }
}
}
?>