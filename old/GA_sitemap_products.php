<?php
/*
  $Id: GA_sitemap_products.php,v 1.0.0.0 2007/11/30 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
include_once('includes/application_top.php');
// RCI sitemapproducts global top
echo $cre_RCI->get('global', 'sitemap',false); // include function generateNode()
echo $cre_RCI->get('global', 'sitemapproducts',false);
if (defined('MODULE_ADDONS_GOOGLEANALYTICS_SITEMAP_STATUS') &&  MODULE_ADDONS_GOOGLEANALYTICS_SITEMAP_STATUS == 'True') { 
  // Google Xml header
  header('Content-Type: text/xml');
  echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
 echo '<urlset 
 xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9"> ' . "\n"; 
  $products_result = tep_db_query("SELECT p.products_id, pd.language_id, p.products_date_added, p.products_last_modified, l.code
                                     from " . TABLE_PRODUCTS . " p, 
                                          " . TABLE_PRODUCTS_DESCRIPTION . " pd, 
                                          " . TABLE_LANGUAGES . " l
                                    WHERE p.products_status='1' 
                                      and p.products_id = pd.products_id 
                                      and pd.language_id = l.languages_id
                                    ORDER BY products_last_modified DESC, products_date_added DESC");
  $products_array = array();
  if (tep_db_num_rows($products_result) > 0) {
    while($products_info = tep_db_fetch_array($products_result)) {
      $products_array[$products_info['products_id']][$products_info['code']] = $products_info;
    }
  }
  reset($products_array);
  $container = array();
  if ( sizeof($products_array) > 0 ){
    foreach ($products_array as $lang){
      foreach($lang as $block){
        $loc_language = ($block['code'] != DEFAULT_LANGUAGE) ? '&language='.$block['code'] : '';
        $container = array('loc' => htmlspecialchars(utf8_encode(HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_PRODUCT_INFO . '?products_id=' . $block['products_id'] . $loc_language)), 
                           'date_added' => $block['products_date_added'],
                           'last_modified' => $block['products_last_modified'],
                          );
        echo generateNode($container);
      }//foreach($lang as $block)
    } //foreach ($products_array as $lang)
  } //sizeof($products_array)
  // RCI insert urlset
  echo $cre_RCI->get('sitemapproducts', 'urlsetbottom');
  echo '</urlset>';
}
include_once('includes/application_bottom.php');
?>