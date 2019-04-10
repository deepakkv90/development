<?php
/*
  $Id: application_bottom.php,v 1.2 2008/05/30 23:40:36 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// close session (store variables)
tep_session_close();
if (STORE_PAGE_PARSE_TIME == 'true') {
  $time_start = explode(' ', PAGE_PARSE_START_TIME);
  $time_end = explode(' ', microtime());
  $parse_time = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);
  error_log(strftime(STORE_PARSE_DATE_TIME_FORMAT) . ' - ' . getenv('REQUEST_URI') . ' (' . $parse_time . 's)' . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
  if (DISPLAY_PAGE_PARSE_TIME == 'true') {
    echo '<span class="smallText">Parse Time: ' . $parse_time . 's</span>';
  }
}
//RCI applicationbottom
$cre_RCI->get('applicationbottom', 'bottom', false);
// CRE_SEO 
if (file_exists(DIR_FS_CATALOG . 'seo.php') && (CRE_SEO == 'true')) {
  ob_end_flush();
}
?>