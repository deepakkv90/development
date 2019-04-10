<?php
/*
  $Id: GA_sitemap_pages_categories.php,v 1.0.0.0 2007/12/04 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
include_once('includes/application_top.php');
// RCI sitemapproducts global top
echo $cre_RCI->get('global', 'sitemap',false); // include function generateNode()
echo $cre_RCI->get('global', 'sitemappagescategories',false);
// Google Xml header
if (defined('MODULE_ADDONS_GOOGLEANALYTICS_SITEMAP_STATUS') &&  MODULE_ADDONS_GOOGLEANALYTICS_SITEMAP_STATUS == 'True') { 
  header('Content-Type: text/xml');
  echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
  echo '<urlset 
 xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9"> ' . "\n"; 
  $pages_categories_result = tep_db_query("SELECT pc.categories_id, pc.categories_date_added, pc.categories_last_modified, pcd.language_id, l.code
                                             from " . TABLE_PAGES_CATEGORIES . " pc, 
                                                  " . TABLE_PAGES_CATEGORIES_DESCRIPTION . " pcd, 
                                                  " . TABLE_LANGUAGES . " l 
                                           WHERE pc.categories_id = pcd.categories_id 
                                             and pcd.language_id = l.languages_id 
                                             and pc.categories_status = '1' 
                                           GROUP BY pc.categories_id  
                                           ORDER BY pc.categories_date_added ASC, pc.categories_last_modified ASC");
  $pages_categories_array = array();
  if (tep_db_num_rows($pages_categories_result) > 0) {
    while($pages_categories_info = tep_db_fetch_array($pages_categories_result)) {
      $pages_categories_array[$pages_categories_info['categories_id']][$pages_categories_info['code']] = $pages_categories_info;
    }
  }
  reset($pages_categories_array);
  $container = array();
  if ( sizeof($pages_categories_array) > 0 ){
    foreach ($pages_categories_array as $lang){
      foreach($lang as $block){
        $loc_language = ($block['code'] != DEFAULT_LANGUAGE) ? '&language='.$block['code'] : '';
        $container = array('loc' => htmlspecialchars(utf8_encode(HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_PAGES . '?cID=' . $block['categories_id'] . $loc_language)), 
                           'date_added' => $block['categories_date_added'],
                           'last_modified' => $block['categories_last_modified'],
                          );
        echo generateNode($container);
      }//foreach($lang as $block)
    } //foreach ($pages_categories_array as $lang)
  } //sizeof($pages_categories_array)
  // RCI insert urlset
  echo $cre_RCI->get('sitemappagescategories', 'urlsetbottom');
  echo '</urlset>';
}
include_once('includes/application_bottom.php');
?>