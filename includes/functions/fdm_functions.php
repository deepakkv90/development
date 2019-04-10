<?php
/*
  $Id: fdm_functions.php,v 1.1.1.1 2006/10/12 10:20:48 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  function cre_resize_bytes($size) {
    $count = 0;
    $format = array("B","KB","MB","GB","TB","PB","EB","ZB","YB");
    while(($size/1024)>1 && $count<8) {
      $size=$size/1024;
      $count++;
    }
    $return = number_format($size,0,'','.')." ".$format[$count];
    return $return;
  }

  function tep_has_folder_subfolders($folder_id) {
    $child_folder_query = tep_db_query("select count(*) as count from " . TABLE_LIBRARY_FOLDERS . " where folders_parent_id = '" . (int)$folder_id . "'");
    $child_folder = tep_db_fetch_array($child_folder_query);
    if ($child_folder['count'] > 0) {
      return true;
    } else {
      return false;
    }
  }
  
  function tep_file_directory($cid, $cpath, &$file_directory, &$level, $COLLAPSABLE = 'true') {
    $selectedPath = array();
    $folders_query = tep_db_query("select lf.folders_id, lf.folders_parent_id, lfd.folders_name from " . TABLE_LIBRARY_FOLDERS . " lf, " . TABLE_LIBRARY_FOLDERS_DESCRIPTION . " lfd where lf.folders_parent_id = " . $cid . " and lf.folders_id = lfd.folders_id and lfd.language_id='" . $_SESSION['languages_id'] ."' order by lf.folders_sort_order, lfd.folders_name");

    while ($folders = tep_db_fetch_array($folders_query))  {
      if ($level[$folders['folders_parent_id']] == "") { 
          $level[$folders['folders_parent_id']] = 0; 
      }
      $level[$folders['folders_id']] = $level[$folders['folders_parent_id']] + 1;

      for ($a=1; $a<$level[$folders['folders_id']]; $a++) {
        $file_directory .= "&nbsp;&nbsp;";
      }

      $file_directory .= '<a href="';

      $fPath_new = $cpath;
      if ($level[$folders['folders_parent_id']] > 0) {
        $fPath_new .= "_";
      }
      $fPath_new .= $folders['folders_id'];
      $fPath_new_text = "fPath=" . $fPath_new;
      $file_directory .= tep_href_link(FILENAME_FOLDER_FILES, $fPath_new_text);
      $file_directory .= '">';

      $selectedPath = array();
      if ($_GET['fPath']) {
        $selectedPath = array_map('tep_string_to_int', explode('_', $_GET['fPath']));
      }

      if (in_array($folders['folders_id'], $selectedPath)) { $file_directory .= '<b>'; }

      if ($level[$folders['folders_id']] == 1) { $file_directory .= '<u>'; }

      $file_directory .= $folders['folders_name'];
      if ($COLLAPSABLE == 'true' && tep_has_folder_subfolders($folders['folders_id'])) { $file_directory .= ' ->'; }

      if ($level[$folders['folders_id']] == 1) { $file_directory .= '</u>'; }

      if (in_array($folders['folders_id'], $selectedPath)) { $file_directory .= '</b>'; }

      $file_directory .= '</a>';
      $file_directory .= '<br>';

      if (tep_has_folder_subfolders($folders['folders_id'])) {
        if ($COLLAPSABLE == 'true') {
          if (in_array($folders['folders_id'], $selectedPath)) {
            tep_file_directory($folders['folders_id'], $fPath_new, $file_directory, $level, $COLLAPSABLE);
          }
        } else { 
            tep_file_directory($folders['folders_id'], $fPath_new, $file_directory, $level, $COLLAPSABLE); 
        }
      }
    }
  }
?>