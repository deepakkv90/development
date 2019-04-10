<?php
/*
  Categories Functions
*/

////
// Return a product's catagory
// TABLES: products_to_catagories
  function tep_get_products_catagory_id($products_id) {
    global $languages_id;

    $the_products_catagory_query = tep_db_query("select products_id, categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $products_id . "'" . " order by products_id,categories_id");
    $the_products_catagory = tep_db_fetch_array($the_products_catagory_query);

    return $the_products_catagory['categories_id'];
  }

////
// WebMakers.com Added: Find a Categories Name
// TABLES: categories_description
  function tep_get_categories_name($who_am_i) {
    global $languages_id;
    $the_categories_name_query= tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id= '" . $who_am_i . "' and language_id= '" . $languages_id . "'");

    $the_categories_name = tep_db_fetch_array($the_categories_name_query);
    return $the_categories_name['categories_name'];
  }


////
// WebMakers.com Added: Find a Categories image
// TABLES: categories_image
  function tep_get_categories_image($what_am_i) {
    $the_categories_image_query= tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id= '" . $what_am_i . "'");

    $the_categories_image = tep_db_fetch_array($the_categories_image_query);
    return $the_categories_image['categories_image'];
  }
?>
