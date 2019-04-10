<?php
/*
  $Id: fdm_featured_files.php,v 1.0.0.0 2006/10/12 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require_once(DIR_WS_FUNCTIONS . FILENAME_FDM_FUNCTIONS);

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FEATURED_FILES);
  
  if (isset($_POST['action']) && $_POST['action'] == 'login') {
    unset($_POST['action']);
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
  
  $breadcrumb->add(TABLE_HEADING_FEATURED_FILES, FILENAME_FEATURED_FILES); 
  
  $content = CONTENT_FEATURED_FILES;
  $javascript = 'popup_window.js';

  require (DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>