<?php
/*
  $Id: fdm_folder_files.php,v 1.0.0.0 2006/10/12 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require_once(DIR_WS_FUNCTIONS . FILENAME_FDM_FUNCTIONS);

  if (isset($_POST['action']) && $_POST['action'] == 'login') {
    unset($_POST['action']);
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  $fPath = isset($_GET['fPath']) ? $_GET['fPath'] : '';

  if (eregi('_', $fPath)) {
    $current_folder_array = array_map('tep_string_to_int', explode('_', $fPath));
  } else {
    $current_folder_array[0] = (int)$fPath;
  }
  
  $breadcrumb->add(TEXT_FDM, FILENAME_FOLDER_FILES); 
  foreach ($current_folder_array as $folder_id)  {
    $folder_name = tep_db_fetch_array(tep_db_query("select folders_name from " . TABLE_LIBRARY_FOLDERS_DESCRIPTION . " where folders_id = '" . $folder_id . "'"));
    $breadcrumb->add($folder_name['folders_name'], FILENAME_FOLDER_FILES . '?fPath=' . $folder_id);
  }

  $current_folder_id = (int)array_pop($current_folder_array);

  $content = CONTENT_FOLDER_FILES;
  $javascript = 'popup_window.js';

  require (DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>