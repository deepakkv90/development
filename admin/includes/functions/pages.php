<?php
/*
  $Id: pages.php,v 1.1.1.1 2004/03/04 23:39:55 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  function tep_pages_get_category_name($category_id, $language_id) {
    $category_query = tep_db_query("select categories_name from " . TABLE_PAGES_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "'");
    $category = tep_db_fetch_array($category_query);

    return $category['categories_name'];
  }

  function tep_pages_get_category_description($category_id, $language_id) {
    $category_query = tep_db_query("select categories_description from " . TABLE_PAGES_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "'");
    $category = tep_db_fetch_array($category_query);

    return $category['categories_description'];
  }

  function tep_pages_get_page_title($page_id, $language_id) {
    $page_query = tep_db_query("select pages_title from " . TABLE_PAGES_DESCRIPTION . " where pages_id = '" . (int)$page_id . "' and language_id = '" . (int)$language_id . "'");
    $page = tep_db_fetch_array($page_query);

    return $page['pages_title'];
  }

  function tep_pages_get_page_blurb($page_id, $language_id) {
    $page_query = tep_db_query("select pages_blurb from " . TABLE_PAGES_DESCRIPTION . " where pages_id = '" . (int)$page_id . "' and language_id = '" . (int)$language_id . "'");
    $page = tep_db_fetch_array($page_query);

    return $page['pages_blurb'];
  }

  function tep_pages_get_page_body($page_id, $language_id) {
    $page_query = tep_db_query("select pages_body from " . TABLE_PAGES_DESCRIPTION . " where pages_id = '" . (int)$page_id . "' and language_id = '" . (int)$language_id . "'");
    $page = tep_db_fetch_array($page_query);

    return $page['pages_body'];
  }

  function tep_pages_get_page_meta_title($page_id, $language_id) {
    $page_query = tep_db_query("select pages_meta_title from " . TABLE_PAGES_DESCRIPTION . " where pages_id = '" . (int)$page_id . "' and language_id = '" . (int)$language_id . "'");
    $page = tep_db_fetch_array($page_query);

    return $page['pages_meta_title'];
  }

  function tep_pages_get_page_meta_keywords($page_id, $language_id) {
    $page_query = tep_db_query("select pages_meta_keywords from " . TABLE_PAGES_DESCRIPTION . " where pages_id = '" . (int)$page_id . "' and language_id = '" . (int)$language_id . "'");
    $page = tep_db_fetch_array($page_query);

    return $page['pages_meta_keywords'];
  }

  function tep_pages_get_page_meta_description($page_id, $language_id) {
    $page_query = tep_db_query("select pages_meta_description from " . TABLE_PAGES_DESCRIPTION . " where pages_id = '" . (int)$page_id . "' and language_id = '" . (int)$language_id . "'");
    $page = tep_db_fetch_array($page_query);

    return $page['pages_meta_description'];
  }

  function tep_pages_remove_category($category_id) {
    $category_image_query = tep_db_query("select categories_image from " . TABLE_PAGES_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");
    $category_image = tep_db_fetch_array($category_image_query);

    // if same image is used for some other category, don't delete
    $duplicate_image_query = tep_db_query("select count(*) as total from " . TABLE_PAGES_CATEGORIES . " where categories_image = '" . tep_db_input($category_image['categories_image']) . "'");
    $duplicate_image = tep_db_fetch_array($duplicate_image_query);

    if ($duplicate_image['total'] < 2) {
      if (file_exists(DIR_FS_CATALOG_IMAGES . $category_image['categories_image'])) {
        @unlink(DIR_FS_CATALOG_IMAGES . $category_image['categories_image']);
      }
    }

    tep_db_query("delete from " . TABLE_PAGES_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");
    tep_db_query("delete from " . TABLE_PAGES_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "'");
    tep_db_query("delete from " . TABLE_PAGES_TO_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");
  }

  function tep_pages_remove_page($page_id) {
    tep_db_query("delete from " . TABLE_PAGES . " where pages_id = '" . (int)$page_id . "'");
    tep_db_query("delete from " . TABLE_PAGES_DESCRIPTION . " where pages_id = '" . (int)$page_id . "'");
    tep_db_query("delete from " . TABLE_PAGES_TO_CATEGORIES . " where pages_id = '" . (int)$page_id . "'");
  }
?>
