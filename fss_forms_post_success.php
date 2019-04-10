<?php
/*
  $Id: fss_forms_post_success.php,v 1.0.0.0 2008/06/19 10:20:48 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FSS_FORMS_POST_SUCCESS);
$forms_id = $_GET['forms_id'];
$confirmation = tep_db_fetch_array(tep_db_query("select forms_confirmation_content from " . TABLE_FSS_FORMS_DESCRIPTION . " where forms_id = '" . $forms_id . "' and language_id = '" . $languages_id . "'"));
if ( tep_not_null($confirmation['forms_confirmation_content']) ) {
  $confirmation_content = $confirmation['forms_confirmation_content'];
} else {
  $confirmation_content = TEXT_SUCCESS;
}
$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_FSS_FORMS_POST_SUCCESS));
$content = CONTENT_FSS_FORMS_POST_SUCCESS;
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>