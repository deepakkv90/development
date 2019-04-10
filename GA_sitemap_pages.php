<?php
/*
  $Id: GA_sitemap_pages.php,v 1.0.0.0 2007/12/04 13:41:11 datazen Exp $

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
  $cre_pages_result = tep_db_query("SELECT p.pages_id, p.pages_date_added, p.pages_date_modified, pd.language_id, l.code
                                      from " . TABLE_PAGES . " p, 
                                           " . TABLE_PAGES_DESCRIPTION . " pd, 
                                           " . TABLE_LANGUAGES . " l 
                                    WHERE p.pages_id = pd.pages_id 
                                      and pd.language_id = l.languages_id 
                                      and p.pages_status = '1' 
                                    ORDER BY p.pages_date_added ASC, p.pages_date_modified ASC");
  $cre_pages_array = array();
  if (tep_db_num_rows($cre_pages_result) > 0) {
    while($cre_pages_info = tep_db_fetch_array($cre_pages_result)) {
      $cre_pages_array[$cre_pages_info['pages_id']][$cre_pages_info['code']] = $cre_pages_info;
    }
  }
  reset($cre_pages_array);
  $container = array();
  if ( sizeof($cre_pages_array) > 0 ){
    foreach ($cre_pages_array as $lang){
      foreach($lang as $block){
        $loc_language = ($block['code'] != DEFAULT_LANGUAGE) ? '&language='.$block['code'] : '';
        $container = array('loc' => htmlspecialchars(utf8_encode(HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_PAGES . '?pID=' . $block['pages_id'] . $loc_language)), 
                           'date_added' => $block['pages_date_added'],
                           'last_modified' => $block['pages_date_modified'],
                          );
        echo generateNode($container);
      }//foreach($lang as $block)
    } //foreach ($cre_pages_array as $lang)
  } //sizeof($cre_pages_array)
  // RCI insert urlset
  echo $cre_RCI->get('sitemappages', 'urlsetbottom');
  echo '</urlset>';
}
include_once('includes/application_bottom.php');
?>