<?php
/*
  $Id: fss_surveys_info.php,v 1.0.0.0 2008/06/19 10:20:48 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
require(DIR_WS_FUNCTIONS . FILENAME_FSS_FUNCTIONS);
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FSS_SURVEYS_INFO);
$forms_id = $_GET['forms_id'];
$forms_query = tep_db_query("SELECT ff.forms_type, ff.forms_post_name, ff.send_email_to, ff.send_post_data, ff.enable_vvc, ffd.forms_name, ffd.forms_confirmation_content, ffd.forms_description 
                               from " . TABLE_FSS_FORMS . " ff, 
                                    " . TABLE_FSS_FORMS_DESCRIPTION . " ffd 
                             WHERE ff.forms_id = '" . $forms_id . "' 
                               and ff.forms_id = ffd.forms_id 
                               and ffd.language_id = '" . $_SESSION['languages_id'] . "'");
$forms = tep_db_fetch_array($forms_query);
$content = CONTENT_FSS_SURVEYS_INFO;
$breadcrumb->add($forms['forms_name'], tep_href_link(FILENAME_FSS_SURVEYS_INFO, tep_get_all_get_params(array())));
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>