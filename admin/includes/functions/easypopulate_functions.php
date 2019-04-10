<?php
/*
  $Id: easypopulate_functions.php,v 3.01 2005/09/06  $

  Released under the GNU General Public License
*/

function ep_get_languages() {
  $languages_query = tep_db_query("select languages_id, code from " . TABLE_LANGUAGES . " order by sort_order");
  // start array at one, the rest of the code expects it that way
  $ll =1;
  while ($ep_languages = tep_db_fetch_array($languages_query)) {
    //will be used to return language_id en language code to report in product_name_code instead of product_name_id
    $ep_languages_array[$ll++] = array(
          'id' => $ep_languages['languages_id'],
          'code' => $ep_languages['code']
          );
  }
  return $ep_languages_array;
};

function tep_get_tax_class_rate($tax_class_id) {
  $tax_multiplier = 0;
  $tax_query = tep_db_query("select SUM(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " WHERE  tax_class_id = '" . $tax_class_id . "' GROUP BY tax_priority");
  if (tep_db_num_rows($tax_query)) {
    while ($tax = tep_db_fetch_array($tax_query)) {
      $tax_multiplier += $tax['tax_rate'];
    }
  }
  return $tax_multiplier;
};

function tep_get_tax_title_class_id($tax_class_title) {
  $classes_query = tep_db_query("select tax_class_id from " . TABLE_TAX_CLASS . " WHERE tax_class_title = '" . $tax_class_title . "'" );
  $tax_class_array = tep_db_fetch_array($classes_query);
  $tax_class_id = $tax_class_array['tax_class_id'];
  return $tax_class_id ;
}

function print_el( $item5 ) {
global $msg_output;
  $msg_output .= " | " . substr(strip_tags($item5), 0, 10);
  //echo " | " . substr(strip_tags($item5), 0, 10);
};

function print_el1( $item6 ) {
global $msg_output;
  $msg_output .= sprintf("| %'.4s ", substr(strip_tags($item6), 0, 80));
};

function tep_get_category_treea($parent_id , $spacing = '', $exclude = '', $category_id_array = '', $include_itself = true) {
    global $languages_id;

    if (!is_array($category_id_array)) $category_tree_array = array();
    if ( (sizeof($category_id_array) < 1) && ($exclude != '0') ) $category_id_array[] = array('id' => '0', 'text' => TEXT_TOP);

    if ($include_itself) {
      $category_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd where cd.language_id = '" . (int)$languages_id . "' and cd.categories_id = '" . (int)$parent_id . "'");
      $category = tep_db_fetch_array($category_query);
      $category_tree_arraya[] = array('id' => $parent_id, 'text' => $category['categories_name']);
    }

    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.parent_id = '" . (int)$parent_id . "' order by c.sort_order, cd.categories_name");
    while ($categories = tep_db_fetch_array($categories_query)) {
      if ($exclude != $categories['categories_id']) $category_id_array[] = array('id' => $categories['categories_id']);
      $category_tree_array = tep_get_category_treea($categories['categories_id'],  $exclude, $category_id_array);
    }

    return $category_id_array;
  }

function tep_get_uploaded_file($filename) {
  if (isset($_FILES[$filename])) {
    $uploaded_file = array('name' => $_FILES[$filename]['name'],
    'type' => $_FILES[$filename]['type'],
    'size' => $_FILES[$filename]['size'],
    'tmp_name' => $_FILES[$filename]['tmp_name']);
  } else {
    $uploaded_file = array('name' => $GLOBALS[$filename . '_name'],
    'type' => $GLOBALS[$filename . '_type'],
    'size' => $GLOBALS[$filename . '_size'],
    'tmp_name' => $GLOBALS[$filename]);
  }

return $uploaded_file;
}

// the $filename parameter is an array with the following elements:
// name, type, size, tmp_name
function tep_copy_uploaded_file($filename, $target) {
    if (substr($target, -1) != '/') $target .= '';
  $target .= $filename['name'];
  move_uploaded_file($filename['tmp_name'], $target);
}
?>