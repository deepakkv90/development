<?php
/*
  $Id: links.php,v 1.1.1.1 2004/03/04 23:39:55 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  function tep_get_link_category_name($link_category_id, $language_id) {
    $link_category_query = tep_db_query("select link_categories_name from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where link_categories_id = '" . (int)$link_category_id . "' and language_id = '" . (int)$language_id . "'");
    $link_category = tep_db_fetch_array($link_category_query);

    return $link_category['link_categories_name'];
  }

  function tep_get_link_category_description($link_category_id, $language_id) {
    $link_category_query = tep_db_query("select link_categories_description from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where link_categories_id = '" . (int)$link_category_id . "' and language_id = '" . (int)$language_id . "'");
    $link_category = tep_db_fetch_array($link_category_query);

    return $link_category['link_categories_description'];
  }

  function tep_remove_link_category($link_category_id) {
    $link_category_image_query = tep_db_query("select link_categories_image from " . TABLE_LINK_CATEGORIES . " where link_categories_id = '" . (int)$link_category_id . "'");
    $link_category_image = tep_db_fetch_array($link_category_image_query);

    $duplicate_image_query = tep_db_query("select count(*) as total from " . TABLE_LINK_CATEGORIES . " where link_categories_image = '" . tep_db_input($link_category_image['link_categories_image']) . "'");
    $duplicate_image = tep_db_fetch_array($duplicate_image_query);

    if ($duplicate_image['total'] < 2) {
      if (file_exists(DIR_FS_CATALOG_IMAGES . $link_category_image['link_categories_image'])) {
        @unlink(DIR_FS_CATALOG_IMAGES . $link_category_image['link_categories_image']);
      }
    }

    tep_db_query("delete from " . TABLE_LINK_CATEGORIES . " where link_categories_id = '" . (int)$link_category_id . "'");
    tep_db_query("delete from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where link_categories_id = '" . (int)$link_category_id . "'");
    tep_db_query("delete from " . TABLE_LINKS_TO_LINK_CATEGORIES . " where link_categories_id = '" . (int)$link_category_id . "'");
  }

  function tep_remove_link($link_id) {
    tep_db_query("delete from " . TABLE_LINKS . " where links_id = '" . (int)$link_id . "'");
    tep_db_query("delete from " . TABLE_LINKS_TO_LINK_CATEGORIES . " where links_id = '" . (int)$link_id . "'");
    tep_db_query("delete from " . TABLE_LINKS_DESCRIPTION . " where links_id = '" . (int)$link_id . "'");
  }

// clone of tep_info_image() sans file_exists (which doesn't work on remote files)
  function tep_link_info_image($image, $alt, $width = '', $height = '') {
    if (tep_not_null($image)) {
      $image = tep_image($image, $alt, $width, $height);
    } else {
      $image = VISUAL_IMAGE_NONEXISTENT;
    }

    return $image;
  }
?>
