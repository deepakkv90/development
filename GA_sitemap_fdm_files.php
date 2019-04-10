<?php
/*
  $Id: GA_sitemap_fdm_files.php,v 1.0.0.0 2008/05/30 13:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
include_once('includes/application_top.php');
// RCI sitemap global top
echo $cre_RCI->get('global', 'sitemap',false); // include function generateNode()
echo $cre_RCI->get('global', 'sitemapcategories',false);
// Google XML header
if (defined('MODULE_ADDONS_GOOGLEANALYTICS_SITEMAP_STATUS') &&  MODULE_ADDONS_GOOGLEANALYTICS_SITEMAP_STATUS == 'True') { 
  header('Content-Type: text/xml');
  echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
 echo '<urlset 
 xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9"> ' . "\n"; 
  $files_result = tep_db_query("SELECT f.files_id, f.files_date_added, f.files_last_modified, fd.language_id, l.code
                                  from " . TABLE_LIBRARY_FILES . " f, 
                                       " . TABLE_LIBRARY_FILES_DESCRIPTION . " fd, 
                                       " . TABLE_LANGUAGES . " l 
                                WHERE f.files_id = fd.files_id AND fd.language_id = l.languages_id AND f.files_status = '1' 
                                ORDER BY f.files_date_added ASC, f.files_last_modified ASC");
  $files_array = array();
  if (tep_db_num_rows($files_result) > 0) {
    while($files_info = tep_db_fetch_array($files_result)) {
      $files_array[$files_info['files_id']][$files_info['code']] = $files_info;
    }
  }
  reset($files_array);
  $container = array();
  if ( sizeof($files_array) > 0 ){
    foreach ($files_array as $lang){
      foreach($lang as $block){
        $loc_language = ($block['code'] != DEFAULT_LANGUAGE) ? '&language='.$block['code'] : '';
        $container = array('loc' => htmlspecialchars(utf8_encode(HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_FILE_DETAIL . '?file_id=' . $block['files_id'] . $loc_language)), 
                           'date_added' => $block['files_date_added'],
                           'last_modified' => $block['files_last_modified'],
                          );
        echo generateNode($container);
      }//foreach($lang as $block)
    } //foreach ($files_array as $lang)
  } //sizeof($files_array)
  // RCI insert urlset
  echo $cre_RCI->get('sitemapfdmfiles', 'urlsetbottom');
  echo '</urlset>';
}
include_once('includes/application_bottom.php');
?>