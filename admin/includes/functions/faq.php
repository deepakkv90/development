<?php
/*
  FAQ system for OSC 2.2 MS2 v2.1  22.02.2005
  Originally Created by: http://adgrafics.com admin@adgrafics.net
  Updated by: http://www.webandpepper.ch osc@webandpepper.ch v2.0 (03.03.2004)
  Last Modified: http://shopandgo.caesium55.com timmhaas@web.de v2.1 (22.02.2005)
  Released under the GNU General Public License
  osCommerce, Open Source E-Commerce Solutions
  Copyright (c) 2004 osCommerce
*/

  function faq_toc ($language) {
  static $old_faq_id;
  
  if ($old_faq_id) {
    $exclude = explode("&", $old_faq_id);
    while (list($dummy,$old_id) = each($exclude)) {
    if ($old_id) {
      $query .= 'faq_id != ' . $old_id . ' AND ';
      unset($old_id);
    }
    }
  }
  $result = tep_db_fetch_array(tep_db_query("SELECT faq_id, question FROM " . TABLE_FAQ . " WHERE $query visible='1' AND language = '$language' ORDER BY v_order asc"));
  if ($result['faq_id']) {
    $old_faq_id .= $result['faq_id'] . '&';
    $result['toc'] = '<a href="' . tep_href_link(FILENAME_FAQ_VIEW_ALL,'#' . $result['faq_id']) . '"><b>' . $result['question'] . '</b></a>';
  }
  return $result;
  }

  function read_faq ($language) {
  static $old_faq_id;
  
  if ($old_faq_id) {
    $exclude = explode("&", $old_faq_id);
    while (list($dummy,$old_id) = each($exclude)) {
    if ($old_id) {
      $query .= 'faq_id != ' . $old_id . ' AND ';
      unset($old_id);
    }
    }
  }
  $result = tep_db_fetch_array(tep_db_query("SELECT faq_id, question, answer FROM " . TABLE_FAQ . " WHERE $query visible='1' AND language = '$language' ORDER BY v_order asc"));

  if ($result['faq_id']) {
    global $languages_id;

    $categories_query = tep_db_query("select icd.categories_id, icd.categories_name from " . TABLE_FAQ_TO_CATEGORIES . " ip2c left join " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " icd on icd.categories_id = ip2c.categories_id where ip2c.faq_id = '" . (int)$result['faq_id'] . "' and icd.language_id = '" . (int)$languages_id . "'");
    $category = tep_db_fetch_array($categories_query);
    if ($category === false) $category = array();

    $result = array_merge($result, $category);

    $old_faq_id .= $result['faq_id'] . '&';
    $result['faq'] = '<b><span id="' . $result['faq_id'] . '">' . $result['question'] . '</span></b><br>' . $result['answer'];
  }
    return $result;
  }
  
  function browse_faq ($language,$_GET) {
    global $languages_id;
    
    $faq_lang = (isset($_GET['faq_lang']) ? $_GET['faq_lang'] : '');
    $faq_action = (isset($_GET['faq_action']) ? $_GET['faq_action'] : '');
    $query_lang = '';
    
    if ($faq_lang != '') {
      $query_lang = "WHERE language = '(int)$faq_lang'";
    } elseif ($faq_action == 'Added') {
      $query_lang = "WHERE language = '(int)$language'";
    }
    $query = "SELECT *, DATE_FORMAT(date, '%d.%m.%y') AS d FROM " . TABLE_FAQ . " $query_lang ORDER BY v_order";
    $daftar = tep_db_query($query);
    $c=0;

    $result = array();
    while ($buffer = tep_db_fetch_array($daftar)) {
      $categories_query = tep_db_query("select icd.categories_id, icd.categories_name from " . TABLE_FAQ_TO_CATEGORIES . " ip2c left join " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " icd on icd.categories_id = ip2c.categories_id where ip2c.faq_id = '" . (int)$buffer['faq_id'] . "' and icd.language_id = '" . (int)$languages_id . "'");
      $category = tep_db_fetch_array($categories_query);
      if ($category === false) $category = array();

      $buffer = array_merge($buffer, $category);

      $result[$c] = $buffer;

      $c++;
    }

    return $result;
  }
  
  function read_data ($faq_id) {
    $result = tep_db_fetch_array(tep_db_query("SELECT * FROM " . TABLE_FAQ . " WHERE faq_id=$faq_id"));

    global $languages_id;
    $categories_query = tep_db_query("select icd.categories_id, icd.categories_name from " . TABLE_FAQ_TO_CATEGORIES . " ip2c left join " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " icd on icd.categories_id = ip2c.categories_id where ip2c.faq_id = '" . (int)$faq_id . "' and icd.language_id = '" . (int)$languages_id . "'");
    $category = tep_db_fetch_array($categories_query);
    if ($category === false) $category = array();

    $result = array_merge($result, $category);

    return $result;
  }
  
  function error_message($error) {
    switch ($error) {
    case "20":
      return '<tr class=messageStackError><td>' . tep_image(DIR_WS_IMAGES . 'icons/warning.gif', FAQ_WARNING) . ' ' . FAQ_ERROR_20 . '</td></tr>';
      break;
    case "80":
      return '<tr class=messageStackError><td>' . tep_image(DIR_WS_IMAGES . 'icons/warning.gif', FAQ_WARNING) . ' ' . FAQ_ERROR_80 . '</td></tr>';
      break;
    default:
      return $error;
    }
  }

  function tep_faq_get_category_name($category_id, $language_id) {
    $category_query = tep_db_query("select categories_name from " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "'");
    $category = tep_db_fetch_array($category_query);

    return $category['categories_name'];
  }

  function tep_faq_get_category_description($category_id, $language_id) {
    $category_query = tep_db_query("select categories_description from " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "'");
    $category = tep_db_fetch_array($category_query);

    return $category['categories_description'];
  }

  function tep_faq_remove_category($category_id) {
    $category_image_query = tep_db_query("select categories_image from " . TABLE_FAQ_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");
    $category_image = tep_db_fetch_array($category_image_query);

    // if same image is used for some other category, don't delete
    $duplicate_image_query = tep_db_query("select count(*) as total from " . TABLE_FAQ_CATEGORIES . " where categories_image = '" . tep_db_input($category_image['categories_image']) . "'");
    $duplicate_image = tep_db_fetch_array($duplicate_image_query);

    if ($duplicate_image['total'] < 2) {
      if (file_exists(DIR_FS_CATALOG_IMAGES . $category_image['categories_image'])) {
        @unlink(DIR_FS_CATALOG_IMAGES . $category_image['categories_image']);
      }
    }

    tep_db_query("delete from " . TABLE_FAQ_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");
    tep_db_query("delete from " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "'");
    tep_db_query("delete from " . TABLE_FAQ_TO_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");
  }

  function tep_faq_remove_faq($faq_id) {
    tep_db_query("delete from " . TABLE_FAQ . " where faq_id = '" . (int)$faq_id . "'");
    tep_db_query("delete from " . TABLE_FAQ_TO_CATEGORIES . " where faq_id = '" . (int)$faq_id . "'");
  }
?>
