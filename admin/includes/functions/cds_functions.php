<?php
/*
  $Id: cds_functions.php,v 1.0.0.0 2007/02/16 11:21:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

function tep_pages_parse_categories_path($cPath) {
  // make sure the category ids are integers
  $cPath_array = array_map('tep_string_to_int', explode('_', $cPath));
  // make sure no duplicate category ids exist which could lock the server in a loop
  $tmp_array = array();
  $n = sizeof($cPath_array);
  for ($i=0; $i < $n; $i++) {
    if (!in_array($cPath_array[$i], $tmp_array)) {
      $tmp_array[] = $cPath_array[$i];
    }
  }
  return $tmp_array;
}

function tep_pages_get_categories_path($current_categories_id = '') { 
    
  global $cPath_array;
  if (tep_not_null($current_categories_id)) {
    $cp_size = sizeof($cPath_array);
    if ($cp_size == 0) {
      $cPath_new = $current_categories_id;
    } else {
      $cPath_new = '';
      $last_categories_query = tep_db_query("select categories_parent_id from " . TABLE_CDS_CATEGORIES . " where categories_id = '" . (int)$cPath_array[($cp_size-1)] . "'");
      $last_categories = tep_db_fetch_array($last_categories_query);

      $current_categories_query = tep_db_query("select categories_parent_id from " . TABLE_CDS_CATEGORIES . " where categories_id = '" . (int)$current_categories_id . "'");
      $current_categories = tep_db_fetch_array($current_categories_query);

      if ($last_categories['categories_parent_id'] == $current_categories['categories_parent_id']) {
        for ($i=0; $i<($cp_size-1); $i++) {
          $cPath_new .= '_' . $cPath_array[$i];
        }
      } else {
        for ($i=0; $i<$cp_size; $i++) {
          $cPath_new .= '_' . $cPath_array[$i];
        }
      }
      $cPath_new .= '_' . $current_categories_id;

      if (substr($cPath_new, 0, 1) == '_') {
        $cPath_new = substr($cPath_new, 1);
      }
    }
  } else {
    $cPath_new = implode('_', $cPath_array);
  }

  return 'cPath=' . $cPath_new;
}

function tep_pages_get_categories_tree($parent_id = '0', $spacing = '', $exclude = '', $pages_tree_array = '', $include_itself = false) {
  global $languages_id;

  if (!is_array($pages_tree_array)) $pages_tree_array = array();
  if ( (sizeof($pages_tree_array) < 1) && ($exclude != '0') ) $pages_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);
  if ($include_itself) {
    $pages_self_query = tep_db_query("select pcd.categories_name from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " pcd where pcd.language_id = '" . (int)$languages_id . "' and pcd.categories_id = '" . (int)$parent_id . "'");
    if ($pages_self = tep_db_fetch_array($pages_self_query)) {
      $pages_tree_array[] = array('id' => $parent_id, 'text' => $pages_self['categories_name']);
    }
  }
  $pages_query = tep_db_query("select pc.categories_id, pc.categories_parent_id, pcd.categories_name from " . TABLE_CDS_CATEGORIES . " pc, " . TABLE_CDS_CATEGORIES_DESCRIPTION . " pcd where pc.categories_id = pcd.categories_id and pcd.language_id = '" . (int)$languages_id . "' and pc.categories_parent_id = '" . (int)$parent_id . "' order by pc.categories_sort_order, pcd.categories_name");
  while ($pages = tep_db_fetch_array($pages_query)) {
    if ($exclude != $pages['categories_id']) $pages_tree_array[] = array('id' => $pages['categories_id'], 'text' => $spacing . $pages['categories_name']);
    $pages_tree_array = tep_pages_get_categories_tree($pages['categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $pages_tree_array);
  }
  return $pages_tree_array;
}

function tep_pages_get_categories_count($categories_id, $recursive = true) {
  $categories_count = 0;
  $sql_categories = ("select categories_id from " . TABLE_CDS_CATEGORIES . " where categories_parent_id = '" . (int)$categories_id . "'");
  $categories_query = tep_db_query($sql_categories);
  while ($categories = tep_db_fetch_array($categories_query)) {
    $categories_count++;
  }
  return $categories_count;
}

function tep_pages_get_pages_count($categories_id, $include_inactive = false, $recursive = true) {
  $pages_count = 0;
  if ($include_inactive) {
    $sql_pages = ("select count(*) as total from " . TABLE_CDS_PAGES . " p, " . TABLE_CDS_PAGES_TO_CATEGORIES . " p2c where p.pages_id = p2c.pages_id and p2c.categories_id = '" . (int)$categories_id . "'");
  } else {
    $sql_pages = ("select count(*) as total from " . TABLE_CDS_PAGES . " p, " . TABLE_CDS_PAGES_TO_CATEGORIES . " p2c where p.pages_id = p2c.pages_id and p.pages_status = '1' and p2c.categories_id = '" . (int)$categories_id . "'");
  }
  $pages_query=tep_db_query($sql_pages);
  if ($pages = tep_db_fetch_array($pages_query)) {
    $pages_count += $pages['total'];
  }
  return $pages_count;
}

function cre_cds_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
  // the wrap is removed because it is not W3C standard and creates problem in IE
  if ($width == '') $width = '100%';
  $param = '';
   $rows = '';
    $cols = '';
  if (eregi('%', $width)) {
    $param .= 'width:' . $width . '; ';
    $rows = substr($width, -1);
  } else {
    $rows = $width;
    }
  if (eregi('%', $height)) {
    $param .= 'height:' . $height . '; ';
      $cols = substr($height, -1);           
  } else {
      $cols = $height;
    }
  $field = '<textarea style="' . $param . '" name="' . tep_output_string($name) . '" id="' . tep_output_string($name) . '" cols="' . tep_output_string($rows) . '" rows="' . tep_output_string($cols) . '"';
  if (tep_not_null($parameters)) $field .= ' ' . $parameters;
  $field .= '>';
  if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
    $field .= stripslashes($GLOBALS[$name]);
  } elseif (tep_not_null($text)) {
    $field .= $text;
  }
  $field .= '</textarea>';

  return $field;
}

function cre_get_lowest_sort_value($category_id) {
  $category_sort_query = tep_db_query("select categories_sort_order from " . TABLE_CDS_CATEGORIES . " where categories_parent_id = '" . (int)$category_id . "' ORDER BY categories_sort_order ASC");
  $category_sort = tep_db_fetch_array($category_sort_query);
  $cat_sort_value = (int)$category_sort['categories_sort_order'];

  $page_sort_query = tep_db_query("select page_sort_order from " . TABLE_CDS_PAGES_TO_CATEGORIES . " where categories_id = '" . (int)$category_id . "' ORDER BY page_sort_order ASC");
  $page_sort = tep_db_fetch_array($page_sort_query);
  $page_sort_value = (int)$page_sort['page_sort_order'];if (tep_db_num_rows($page_query) > 0) {
    return $page['pages_file'];
  } elseif (isset($_POST['pages_file'][$language_id])) {
    return $_POST['pages_file'][$language_id];
  } else {
    return '';
  }

  $lowest_sort_value = ($page_sort_value <= $cat_sort_value) ? $page_sort_value : $cat_sort_value;

  return (int)$lowest_sort_value;
}

function cre_get_next_sort_value() {
  global $cPath;

  $cat_id = isset($cPath) ? explode('_', $cPath) : array();
  $cat_id = end($cat_id);

  $category_sort_query = tep_db_query("select categories_sort_order from " . TABLE_CDS_CATEGORIES . " where categories_parent_id = '" . (int)$cat_id . "' ORDER BY categories_sort_order DESC");
  $category_sort = tep_db_fetch_array($category_sort_query);
  $cat_sort_value = (int)$category_sort['categories_sort_order'];

  $page_sort_query = tep_db_query("select page_sort_order from " . TABLE_CDS_PAGES_TO_CATEGORIES . " where categories_id = '" . (int)$cat_id . "' ORDER BY page_sort_order DESC");
  $page_sort = tep_db_fetch_array($page_sort_query);
  $page_sort_value = (int)$page_sort['page_sort_order'];

  $next_sort_value = ($cat_sort_value >= $page_sort_value) ? $cat_sort_value + 10 : $page_sort_value + 10;

  return (int)$next_sort_value;
}

function tep_pages_get_category_name($category_id, $language_id) {
  $category_query = tep_db_query("select categories_name from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "'");
  $category = tep_db_fetch_array($category_query);
  if (tep_db_num_rows($category_query) > 0) {
    return $category['categories_name'];
  } elseif (isset($_POST['categories_name'][$language_id])) {
    return $_POST['categories_name'][$language_id];
  } else {
    return '';
  }
}

function tep_pages_get_category_heading($category_id ,$language_id){
  $category_query = tep_db_query("select categories_heading from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "'");
  $category = tep_db_fetch_array($category_query);
  if (tep_db_num_rows($category_query) > 0) {
    return $category['categories_heading'];
  } elseif (isset($_POST['categories_heading'][$language_id])) {
    return $_POST['categories_heading'][$language_id];
  } else {
    return '';
  }
}

function tep_pages_get_category_description($category_id, $language_id) {
  $category_query = tep_db_query("select categories_description from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "'");
  $category = tep_db_fetch_array($category_query);
  if (tep_db_num_rows($category_query) > 0) {
    return $category['categories_description'];
  } elseif (isset($_POST['categories_description'][$language_id])) {
    return $_POST['categories_description'][$language_id];
  } else {
    return '';
  }
}

function tep_pages_get_category_blurb($category_id, $language_id) {
  $category_query = tep_db_query("select categories_blurb from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "'");
  $category = tep_db_fetch_array($category_query);
  if (tep_db_num_rows($category_query) > 0) {
    return $category['categories_blurb'];
  } elseif (isset($_POST['categories_blurb'][$language_id])) {
    return $_POST['categories_blurb'][$language_id];
  } else {
    return '';
  }
}

function tep_pages_get_meta_keyword_category($category_id ,$language_id){
  $category_query = tep_db_query("select categories_meta_keywords from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "'");
  $category = tep_db_fetch_array($category_query);
  if (tep_db_num_rows($category_query) > 0) {
    return $category['categories_meta_keywords'];
  } elseif (isset($_POST['categories_meta_keywords'][$language_id])) {
    return $_POST['categories_meta_keywords'][$language_id];
  } else {
    return '';
  }
}

function tep_pages_get_category_meta_title($category_id, $language_id) {
  $category_query = tep_db_query("select categories_meta_title from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "'");
  $category = tep_db_fetch_array($category_query);
  if (tep_db_num_rows($category_query) > 0) {
    return $category['categories_meta_title'];
  } elseif (isset($_POST['categories_meta_title'][$language_id])) {
    return $_POST['categories_meta_title'][$language_id];
  } else {
    return '';
  }
}

function tep_pages_get_category_meta_keywords($category_id, $language_id) {
  $category_query = tep_db_query("select categories_meta_keywords from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "'");
  $category = tep_db_fetch_array($category_query);
  if (tep_db_num_rows($category_query) > 0) {
    return $category['categories_meta_keywords'];
  } elseif (isset($_POST['categories_meta_keywords'][$language_id])) {
    return $_POST['categories_meta_keywords'][$language_id];
  } else {
    return '';
  }
}

function tep_pages_get_category_meta_description($category_id, $language_id) {
  $category_query = tep_db_query("select categories_meta_description from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "'");
  $category = tep_db_fetch_array($category_query);
  if (tep_db_num_rows($category_query) > 0) {
    return $category['categories_meta_description'];
  } elseif (isset($_POST['categories_meta_description'][$language_id])) {
    return $_POST['categories_meta_description'][$language_id];
  } else {
    return '';
  }
}

function tep_pages_get_page_title($page_id, $language_id) {
  $page_query = tep_db_query("select pages_title from " . TABLE_CDS_PAGES_DESCRIPTION . " where pages_id = '" . (int)$page_id . "' and language_id = '" . (int)$language_id . "'");
  $page = tep_db_fetch_array($page_query);
  
  if (tep_db_num_rows($page_query) > 0) {
    return $page['pages_title'];
  } elseif (isset($_POST['pages_title'][$language_id])) {
    return $_POST['pages_title'][$language_id];
  } else {
    return '';
  }
}

function tep_pages_get_menu_name($page_id, $language_id) {
  $page_query = tep_db_query("select pages_menu_name from " . TABLE_CDS_PAGES_DESCRIPTION . " where pages_id = '" . (int)$page_id . "' and language_id = '" . (int)$language_id . "'");
  $page = tep_db_fetch_array($page_query);
  if (tep_db_num_rows($page_query) > 0) {
    return $page['pages_menu_name'];
  } elseif (isset($_POST['pages_menu_name'][$language_id])) {
    return $_POST['pages_menu_name'][$language_id];
  } else {
    return '';
  }
}

function tep_pages_get_page_blurb($page_id, $language_id) {
  $page_query = tep_db_query("select pages_blurb from " . TABLE_CDS_PAGES_DESCRIPTION . " where pages_id = '" . (int)$page_id . "' and language_id = '" . (int)$language_id . "'");
  $page = tep_db_fetch_array($page_query);
  if (tep_db_num_rows($page_query) > 0) {
    return $page['pages_blurb'];
  } elseif (isset($_POST['pages_blurb'][$language_id])) {
    return $_POST['pages_blurb'][$language_id];
  } else {
    return '';
  }
}

function tep_pages_get_page_body($page_id, $language_id) {
  $page_query = tep_db_query("select pages_body from " . TABLE_CDS_PAGES_DESCRIPTION . " where pages_id = '" . (int)$page_id . "' and language_id = '" . (int)$language_id . "'");
  $page = tep_db_fetch_array($page_query);
  if (tep_db_num_rows($page_query) > 0) {
    return $page['pages_body'];
  } elseif (isset($_POST['pages_body'][$language_id])) {
    return $_POST['pages_body'][$language_id];
  } else {
    return '';
  }
}

function tep_pages_get_page_meta_title($page_id, $language_id) {
  $page_query = tep_db_query("select pages_meta_title from " . TABLE_CDS_PAGES_DESCRIPTION . " where pages_id = '" . (int)$page_id . "' and language_id = '" . (int)$language_id . "'");
  $page = tep_db_fetch_array($page_query);
  if (tep_db_num_rows($page_query) > 0) {
    return $page['pages_meta_title'];
  } elseif (isset($_POST['pages_meta_title'][$language_id])) {
    return $_POST['pages_meta_title'][$language_id];
  } else {
    return '';
  }
}

function tep_pages_get_page_meta_keywords($page_id, $language_id) {
  $page_query = tep_db_query("select pages_meta_keywords from " . TABLE_CDS_PAGES_DESCRIPTION . " where pages_id = '" . (int)$page_id . "' and language_id = '" . (int)$language_id . "'");
  $page = tep_db_fetch_array($page_query);
  if (tep_db_num_rows($page_query) > 0) {
    return $page['pages_meta_keywords'];
  } elseif (isset($_POST['pages_meta_keywords'][$language_id])) {
    return $_POST['pages_meta_keywords'][$language_id];
  } else {
    return '';
  }
}

function tep_pages_get_page_meta_description($page_id, $language_id) {
  $page_query = tep_db_query("select pages_meta_description from " . TABLE_CDS_PAGES_DESCRIPTION . " where pages_id = '" . (int)$page_id . "' and language_id = '" . (int)$language_id . "'");
  $page = tep_db_fetch_array($page_query);
  if (tep_db_num_rows($page_query) > 0) {
    return $page['pages_meta_description'];
  } elseif (isset($_POST['pages_meta_description'][$language_id])) {
    return $_POST['pages_meta_description'][$language_id];
  } else {
    return '';
  }
}

function tep_pages_get_auxillary_file($page_id,$language_id){
  $page_query = tep_db_query("select pages_file from " . TABLE_CDS_PAGES_DESCRIPTION . " where pages_id = '" . (int)$page_id . "' and language_id = '" . (int)$language_id . "'");
  $page = tep_db_fetch_array($page_query);
  if (tep_db_num_rows($page_query) > 0) {
    return $page['pages_file'];
  } elseif (isset($_POST['pages_file'][$language_id])) {
    return $_POST['pages_file'][$language_id];
  } else {
    return '';
  }
}

function tep_pages_remove_category($category_id) {
  $category_image_query = tep_db_query("select categories_image from " . TABLE_CDS_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");
  $category_image = tep_db_fetch_array($category_image_query);
  // if same image is used for some other category, don't delete
  $duplicate_image_query = tep_db_query("select count(*) as total from " . TABLE_CDS_CATEGORIES . " where categories_image = '" . tep_db_input($category_image['categories_image']) . "'");
  $duplicate_image = tep_db_fetch_array($duplicate_image_query);
  if ($duplicate_image['total'] < 2) {
    if (file_exists(DIR_FS_CATALOG_IMAGES . $category_image['categories_image'])) {
      @unlink(DIR_FS_CATALOG_IMAGES . $category_image['categories_image']);
    }
  }
  tep_db_query("delete from " . TABLE_CDS_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");
  tep_db_query("delete from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "'");
  tep_db_query("delete from " . TABLE_CDS_PAGES_TO_CATEGORIES . " where categories_id = '" . (int)$category_id . "'");
}

function tep_pages_remove_page($page_id) {
  tep_db_query("delete from " . TABLE_CDS_PAGES . " where pages_id = '" . (int)$page_id . "'");
  tep_db_query("delete from " . TABLE_CDS_PAGES_DESCRIPTION . " where pages_id = '" . (int)$page_id . "'");
  tep_db_query("delete from " . TABLE_CDS_PAGES_TO_CATEGORIES . " where pages_id = '" . (int)$page_id . "'");
}

//added for cre pages move function
function tep_generate_pages_category_path($id, $from = 'category', $pages_categories_array = '', $index = 0) {
  global $languages_id;

  if (!is_array($pages_categories_array)) $pages_categories_array = array();
  $pages_category_query = tep_db_query("select cd.categories_name, c.categories_parent_id from " . TABLE_CDS_CATEGORIES . " c, " . TABLE_CDS_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");
  $pages_category = tep_db_fetch_array($pages_category_query);
  $pages_categories_array[$index][] = array('id' => $id, 'text' => $pages_category['categories_name']);
  if ( (tep_not_null($pages_category['categories_parent_id'])) && ($pages_category['categories_parent_id'] != '0') ) $pages_categories_array = tep_generate_pages_category_path($pages_category['categories_parent_id'], 'category', $pages_categories_array, $index);
  return $pages_categories_array;
}

function tep_output_generated_pages_category_path($id, $from = 'category') {
  $calculated_pages_category_path_string = '';
  $calculated_pages_category_path = tep_generate_pages_category_path($id, $from);
  for ($i=0, $n=sizeof($calculated_pages_category_path); $i<$n; $i++) {
    for ($j=0, $k=sizeof($calculated_pages_category_path[$i]); $j<$k; $j++) {
      $calculated_pages_category_path_string .= $calculated_pages_category_path[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
    }
    $calculated_pages_category_path_string = substr($calculated_pages_category_path_string, 0, -16) . '<br>';
  }
  $calculated_pages_category_path_string = substr($calculated_pages_category_path_string, 0, -4);
  if (strlen($calculated_pages_category_path_string) < 1) $calculated_pages_category_path_string = TEXT_TOP;
  return $calculated_pages_category_path_string;
}

function tep_get_generated_pages_category_path_ids($id, $from = 'category') {
  $calculated_pages_category_path_string = '';
  $calculated_pages_category_path = tep_generate_pages_category_path($id, $from);
  for ($i=0, $n=sizeof($calculated_pages_category_path); $i<$n; $i++) {
    for ($j=0, $k=sizeof($calculated_pages_category_path[$i]); $j<$k; $j++) {
      $calculated_pages_category_path_string .= $calculated_pages_category_path[$i][$j]['id'] . '_';
    }
    $calculated_pages_category_path_string = substr($calculated_pages_category_path_string, 0, -1) . '<br>';
  }
  $calculated_pages_category_path_string = substr($calculated_pages_category_path_string, 0, -4);
  if (strlen($calculated_pages_category_path_string) < 1) $calculated_pages_category_path_string = TEXT_TOP;
  return $calculated_pages_category_path_string;
}

function tep_get_pages_category_tree($parent_id = '0', $spacing = '', $exclude = '', $pages_category_tree_array = '', $include_itself = false) {
  global $languages_id;

  if (!is_array($pages_category_tree_array)) $pages_category_tree_array = array();
  if ( (sizeof($pages_category_tree_array) < 1) && ($exclude != '0') ) $pages_category_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);
  if ($include_itself) {
    $pages_category_query = tep_db_query("select cd.categories_name from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " cd where cd.language_id = '" . (int)$languages_id . "' and cd.categories_id = '" . (int)$parent_id . "'");
    $pages_category = tep_db_fetch_array($pages_category_query);
    $pages_category_tree_array[] = array('id' => $parent_id, 'text' => $pages_category['categories_name']);
  }
  $pages_categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_parent_id from " . TABLE_CDS_CATEGORIES . " c, " . TABLE_CDS_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.categories_parent_id = '" . (int)$parent_id . "' order by c.categories_sort_order, cd.categories_name");
  while ($pages_categories = tep_db_fetch_array($pages_categories_query)) {
    if ($exclude != $pages_categories['categories_id']) $pages_category_tree_array[] = array('id' => $pages_categories['categories_id'], 'text' => $spacing . $pages_categories['categories_name']);
    $pages_category_tree_array = tep_get_pages_category_tree($pages_categories['categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $pages_category_tree_array);
  }
  return $pages_category_tree_array;
}

function cre_get_cat_name($cPath) {
  global $languages_id;

  $findme  = '_';
  $pos = strpos($cPath, $findme);
  if (!$pos) {
    $sql_category_name = tep_db_query("SELECT categories_name 
                                                             from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " 
                                                          WHERE categories_id = " . $cPath . " 
                                                            and language_id = " . $languages_id);

    $category_name = tep_db_fetch_array($sql_category_name);
    $separated = $category_name['categories_name'];             
  } else {
    $var = explode('_', $cPath);
    $var1 = array();
    for ($i = 0; $i < sizeof($var); $i++) {
      $sql_category_name = tep_db_query("SELECT categories_name 
                                                               from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " 
                                                             WHERE categories_id = " . $var[$i] . " 
                                                               and language_id = " . $languages_id);

      $category_name = tep_db_fetch_array($sql_category_name);
      $cat_name = $category_name['categories_name'];
      $var1[] = $cat_name; 
        $separated = implode('_',  $var1 ); 
    }
  }
  $separated_cat_name = strtolower(implode('_', explode( ' ', $separated))); 

  return $separated_cat_name;
}
//clean filenames and replace spaces with _
function cre_clean_filename($fname){
    $fname = preg_replace('/[^a-z0-9-]/', ' ', strtolower($fname));
    $fname = preg_replace('/\s\s+/', '_', $fname);
    return str_replace(' ', '_', $fname);
}
?>