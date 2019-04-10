<?php
/*
  $Id: GA_sitemap_categories.php,v 1.0.0.0 2008/05/30 13:41:11 datazen Exp $

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
  $categories_result = tep_db_query("SELECT c.categories_id, cd.language_id, c.date_added, c.last_modified, l.code
                                       from " . TABLE_CATEGORIES . " c, 
                                            " . TABLE_CATEGORIES_DESCRIPTION . " cd, 
                                            " . TABLE_LANGUAGES . " l
                                     WHERE c.categories_id = cd.categories_id 
                                       and cd.language_id = l.languages_id
                                     ORDER BY c.date_added ASC, c.last_modified ASC");
  $categories_array = array();
  if (tep_db_num_rows($categories_result) > 0) {
    while($categories_info = tep_db_fetch_array($categories_result)) {
      $categories_array[$categories_info['categories_id']][$categories_info['code']] = $categories_info;
    }
  }
  reset($categories_array);
  $container = array();
  if ( sizeof($categories_array) > 0 ){
    foreach ($categories_array as $lang){
      foreach($lang as $block){
        $loc_language = ($block['code'] != DEFAULT_LANGUAGE) ? '&language='.$block['code'] : '';
        $container = array('loc' => htmlspecialchars(utf8_encode(HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_DEFAULT . '?cPath=' . $block['categories_id'] . $loc_language)), 
                           'date_added' => $block['date_added'],
                           'last_modified' => $block['last_modified'],
                          );
        echo generateNode($container);
      } //foreach($lang as $block)
    } //foreach ($categories_array as $lang)
  } //sizeof($categories_array)
  // RCI insert url set
  echo $cre_RCI->get('sitemapcategories', 'urlsetbottom');
  echo '</urlset>';
}
include_once('includes/application_bottom.php');
?>