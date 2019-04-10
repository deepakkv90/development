<?php
/*
  $Id: fss_completed_surveys.php,v 1.0.0.0 2008/06/19 10:20:48 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
// if the customer is not logged on, redirect them to the login page
if (!isset($_SESSION['customer_id'])) {
  $navigation->set_snapshot();
  tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}
require(DIR_WS_FUNCTIONS . FILENAME_FSS_FUNCTIONS);
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FSS_COMPLETED_SURVEYS);
$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_FSS_COMPLETED_SURVEYS));
$content = CONTENT_FSS_COMPLETED_SURVEYS;
$listing_sql = "SELECT ff.forms_id, ffd.forms_name 
                  from " . TABLE_FSS_FORMS . " ff, 
                       " . TABLE_FSS_FORMS_POSTS . " ffp, 
                       " . TABLE_FSS_FORMS_DESCRIPTION . " ffd where ff.forms_id = ffd.forms_id and ff.forms_status = '1' and ffd.language_id = '" . $languages_id . "' and ff.forms_type = '1' and ffp.forms_id = ff.forms_id and ffp.customers_id = '" . $_SESSION['customer_id'] . "'";
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
