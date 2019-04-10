<?php
/*
  $Id: fdm_downloads_index.php,v 1.1.1.1 2006/10/04 23:38:02 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DOWNLOADS_INDEX);
  require(DIR_WS_LANGUAGES . $language .'/modules/' . FILENAME_DOWNLOADS_FILES_LISTING);

  if (!isset($_SESSION['customer_id'])) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_DOWNLOADS, tep_href_link(FILENAME_MY_DOWNLOADS, 'customer_id=' . $_SESSION['customer_id']));
  $content = CONTENT_DOWNLOADS_INDEX;
  $javascript = 'popup_window.js';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>