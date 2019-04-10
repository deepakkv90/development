<?php
/*
  $Id: CDS_headertags_addswitch.php,v 1.0.0.0 2007/06/09 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/

global $languages_id, $tags_array;

// PAGES.PHP
if (strstr($_SERVER['SCRIPT_NAME'], 'pages.php')) {
  $current_categories_id  = isset($_GET['CDpath']) ? (int)end(explode('_',$_GET['CDpath'])) : 0;
  $current_pages_id = isset($_GET['pID']) ? (int)$_GET['pID'] : 0;
  $page = array();
    $page_category = array();
  if (($current_categories_id == 0) && ($current_pages_id == 0)) return;

  if ($current_categories_id != 0) {
    $page_category_query = tep_db_query("SELECT icd.categories_meta_title, icd.categories_meta_keywords, icd.categories_meta_description 
                                                                from " . TABLE_PAGES_CATEGORIES . " ic, 
                                                                       " . TABLE_PAGES_CATEGORIES_DESCRIPTION . " icd 
                                                              WHERE ic.categories_id = '" . (int)$current_categories_id . "' 
                                                                and icd.categories_id = '" . (int)$current_categories_id . "' 
                                                                and icd.language_id = '" . (int)$languages_id . "'");
    $page_category = tep_db_fetch_array($page_category_query);
  }

  if ($current_pages_id != 0) { 
    $page_query= tep_db_query("SELECT ipd.pages_meta_title, ipd.pages_meta_keywords, ipd.pages_meta_description 
                                                 from " . TABLE_PAGES . " ip,
                                                      " . TABLE_PAGES_DESCRIPTION . " ipd  
                                                              WHERE ip.pages_id = '" . (int)$current_pages_id . "' 
                                                                and ipd.pages_id = '" . (int)$current_pages_id . "' 
                                                                and ipd.language_id = '" . (int)$languages_id . "'");
    $page = tep_db_fetch_array($page_query);
  }

  if (isset($page_category['categories_meta_description']) && tep_not_null($page_category['categories_meta_description'])) {
    $tags_array['desc'] .= ' - ' . $page_category['categories_meta_description'];
  } else {
    if (isset($page['pages_meta_description']) && tep_not_null($page['pages_meta_description'])) {
      $tags_array['desc'] .= ' - ' . $page['pages_meta_description'];
    }
  }
  // clean up the description string
  if (substr($tags_array['desc'], 0, 3) == " - ") {
    $tags_array['desc'] = substr($tags_array['desc'], 3, strlen($tags_array['desc']));
  }
  if (isset($page_category['categories_meta_keywords']) && tep_not_null($page_category['categories_meta_keywords'])) {
    $tags_array['keywords'] .= ', ' . $page_category['categories_meta_keywords'];
  } else {
    if (isset($page['pages_meta_keywords']) && tep_not_null($page['pages_meta_keywords'])) {
      $tags_array['keywords'] .= ', ' . $page['pages_meta_keywords'];
    }
  }
  // clean the beginning of the keyword string
  if (substr($tags_array['keywords'], 0, 1) == chr(32)) {
    $tags_array['keywords'] = substr($tags_array['keywords'], 1, strlen($tags_array['keywords']));
  }
  if (substr($tags_array['keywords'], 0, 2) == ", ") {
    $tags_array['keywords'] = substr($tags_array['keywords'], 2, strlen($tags_array['keywords']));
  }

  if (isset($page_category['categories_meta_title']) && tep_not_null($page_category['categories_meta_title'])) {
    $tags_array['title'] .= ' - ' . $page_category['categories_meta_title'];
  } else {
    if (isset($page['pages_meta_title']) && tep_not_null( $page['pages_meta_title'])) {
      //$tags_array['title'] .= ' - ' . $page['pages_meta_title']; //Modified July 27 2011
    }
  }
 // clean up the title string
  if (substr($tags_array['title'], 0, 3) == " - ") {
    $tags_array['title'] = substr($tags_array['title'], 3, strlen($tags_array['title']));
  }
}
?>