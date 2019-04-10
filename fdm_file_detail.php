<?php
/*
  $Id: fdm_file_detail.php,v 1.0.0.0 2006/10/12 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FILE_DETAIL);
  require_once(DIR_WS_FUNCTIONS . FILENAME_FDM_FUNCTIONS);

  if (isset($_POST['action']) && $_POST['action'] == 'login') {
    unset($_POST['action']);
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  $current_file_id = isset($_GET['file_id']) ? (int)$_GET['file_id'] : '';
  $file_check = tep_db_fetch_array(tep_db_query("select count(*) as total  from " . TABLE_LIBRARY_FILES_DESCRIPTION . " where  files_id = '" . $current_file_id . "'"));
  
  $breadcrumb->add(TEXT_FDM, FILENAME_FOLDER_FILES);
  if($file_check['total'] < 1) {
      $breadcrumb->add(TEXT_FILE_UNAVAILABLE);
  } else {
      $file_array = tep_db_fetch_array(tep_db_query("select  files_descriptive_name from " . TABLE_LIBRARY_FILES_DESCRIPTION . " where  files_id = '" . $current_file_id . "'"));
      $breadcrumb->add($file_array['files_descriptive_name'], tep_href_link(FILENAME_FILE_DETAIL, 'file_id=' . $current_file_id));
  }

  $content = CONTENT_FILE_DETAIL;
  $javascript = 'popup_window.js';
 
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
  
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?> 