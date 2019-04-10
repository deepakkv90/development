<?php
/*
  $Id: GA_sitemap_fdm_folders.php,v 1.0.0.0 2008/05/30 13:41:11 wa4u Exp $    

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
  $folders_result = tep_db_query("SELECT f.folders_id, f.folders_date_added, f.folders_last_modified, fd.language_id, l.code
                                    from " . TABLE_LIBRARY_FOLDERS . " f, 
                                         " . TABLE_LIBRARY_FOLDERS_DESCRIPTION . " fd, 
                                         " . TABLE_LANGUAGES . " l 
                                  WHERE f.folders_id = fd.folders_id 
                                    and fd.language_id = l.languages_id 
                                  ORDER BY f.folders_date_added ASC, f.folders_last_modified ASC");
  $folders_array = array();
  if (tep_db_num_rows($folders_result) > 0) {
    while($folders_info = tep_db_fetch_array($folders_result)) {
      $folders_array[$folders_info['folders_id']][$folders_info['code']] = $folders_info;
    }
  }
  reset($folders_array);
  $container = array();
  if ( sizeof($folders_array) > 0 ){
    foreach ($folders_array as $lang){
      foreach($lang as $block){
        $loc_language = ($block['code'] != DEFAULT_LANGUAGE) ? '&language='.$block['code'] : '';
        $container = array('loc' => htmlspecialchars(utf8_encode(HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_FOLDER_FILES . '?fPath=' . $block['folders_id'] . $loc_language)), 
                           'date_added' => $block['folders_date_added'],
                           'last_modified' => $block['folders_last_modified'],
                          );
        echo generateNode($container);
      }//foreach($lang as $block)
    } //foreach ($folders_array as $lang)
  } //sizeof($folders_array)
  // RCI insert urlset
  echo $cre_RCI->get('sitemapfdmfolders', 'urlsetbottom');
  echo '</urlset>';
}
include_once('includes/application_bottom.php');
?>