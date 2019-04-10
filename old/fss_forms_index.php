<?php
/*
  $Id: fss_forms_index.php,v 1.0.0.0 2008/06/19 10:20:48 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FSS_FORMS_INDEX);
$current_category_id = isset($_GET['fPath']) ? $_GET['fPath'] : '';
$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_FSS_FORMS_INDEX, 'fPath=' . $current_category_id));
$content = CONTENT_FSS_FORMS_INDEX;
$listing_sql = "select ff.forms_id, ffd.forms_name from " . TABLE_FSS_FORMS . " ff, " . TABLE_FSS_FORMS_DESCRIPTION . " ffd, " . TABLE_FSS_FORMS_TO_CATEGORIES . " ff2c where ff.forms_id = ffd.forms_id and ff.forms_status = '1' and ff2c.forms_id = ff.forms_id and ff2c.categories_id = '" . (int)$current_category_id . "' and ffd.language_id = '" . $languages_id . "' and ff.forms_type = '0'";
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>