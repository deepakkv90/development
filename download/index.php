<?php
/*
  $Id: index.php,v 2.0 2008/05/05 00:36:41 datazen Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');

// the following cPath references come from application_top.php
$category_depth = 'top';
if (isset($cPath) && tep_not_null($cPath)) {
  $categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
  $cateqories_products = tep_db_fetch_array($categories_products_query);
  if ($cateqories_products['total'] > 0) {
    $category_depth = 'products'; // display products
  } else {
    $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "'");
    $category_parent = tep_db_fetch_array($category_parent_query);
    if ($category_parent['total'] > 0) {
      $category_depth = 'nested'; // navigate through the categories
    } else {
      $category_depth = 'products'; // category has no products, but display the 'no products' message
    }
  }
}
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
if ($category_depth == 'nested') {
  $category_query = tep_db_query("select cd.categories_name, cd.categories_heading_title, cd.categories_description, c.categories_image from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
  $category = tep_db_fetch_array($category_query);
  $categories_name = $category['categories_name'];
  $categories_heading_title = $category['categories_heading_title'];
  $categories_description = $category['categories_description'];
  $categories_image = $category['categories_image'];

    $content = CONTENT_INDEX_NESTED;

  } elseif ($category_depth == 'products' || isset($_GET['manufacturers_id'])) {
  // create column list
  $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                       'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                       'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                       'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                       'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                       'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                       'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                       'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);

  asort($define_list);
  $column_list = array();
  reset($define_list);
  while (list($key, $value) = each($define_list)) {
    if ($value > 0) $column_list[] = $key;
  }
  $status_product_prices_table = false;
  $status_need_to_get_prices = false;
  // find out if sorting by price has been requested
  if ( (isset($_GET['sort'])) && (ereg('[1-8][ad]', $_GET['sort'])) && (substr($_GET['sort'], 0, 1) <= sizeof($column_list)) && (int)$customer_group_id != '0' ){
    $_sort_col = substr($_GET['sort'], 0 , 1);
    if ($column_list[$_sort_col-1] == 'PRODUCT_LIST_PRICE') {
      $status_need_to_get_prices = true;
    }
  }

  if ($status_need_to_get_prices == true && (int)$customer_group_id != '0') {
    $product_prices_table = TABLE_PRODUCTS_GROUP_PRICES.(int)$customer_group_id;
   
    tep_db_check_age_products_group_prices_cg_table((int)$customer_group_id);
    $status_product_prices_table = true;
  } 
  $select_column_list = '';

  for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
    switch ($column_list[$i]) {
      case 'PRODUCT_LIST_MODEL':
        $select_column_list .= 'p.products_model, ';
        break;
      case 'PRODUCT_LIST_NAME':
        $select_column_list .= 'pd.products_name, ';
        break;
      case 'PRODUCT_LIST_MANUFACTURER':
        $select_column_list .= 'm.manufacturers_name, ';
        break;
      case 'PRODUCT_LIST_QUANTITY':
        $select_column_list .= 'p.products_quantity, ';
        break;
      case 'PRODUCT_LIST_IMAGE':
        $select_column_list .= 'p.products_image, ';
        break;
      case 'PRODUCT_LIST_WEIGHT':
        $select_column_list .= 'p.products_weight, ';
        break;
    }
  }
  // Get the category name and description
  $category_query = tep_db_query("select cd.categories_name, cd.categories_heading_title, cd.categories_description, c.categories_image from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
  $category = tep_db_fetch_array($category_query);
  // show the products of a specified manufacturer
  if (isset($_GET['manufacturers_id'])) {
    if (isset($_GET['filter_id']) && tep_not_null($_GET['filter_id'])) {
      // we are asked to show only a specific category
      if ($status_product_prices_table) {
        $listing_sql = "SELECT " . $select_column_list . " p.products_id, p.manufacturers_id, tmp_pp.products_price, p.products_tax_class_id,
                               IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price,
                               IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price
                        FROM (" . TABLE_PRODUCTS . " p
                        LEFT JOIN " . $product_prices_table . " as tmp_pp using(products_id)),
                                  " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                  " . TABLE_MANUFACTURERS . " m,
                                  " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c
                        WHERE p.products_status = 1
                          and p.manufacturers_id = " . (int)$_GET['manufacturers_id'] . "
                          and m.manufacturers_id = " . (int)$_GET['manufacturers_id'] . "
                          and p.products_id = p2c.products_id
                          and pd.products_id = p2c.products_id
                          and pd.language_id = " . (int)$languages_id . "
                          and p2c.categories_id = " . (int)$_GET['filter_id'];
    
      } else { // either retail or no need to get correct special prices
        $listing_sql = "SELECT " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id,
                               IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price,
                               IF(s.status, s.specials_new_products_price, p.products_price) as final_price
                        FROM (" . TABLE_PRODUCTS . " p
                        LEFT JOIN " . TABLE_SPECIALS . " s on(s.products_id = p.products_id and s.status = 1 and s.customers_group_id = " . (int)$customer_group_id . ") ),
                                  " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                  " . TABLE_MANUFACTURERS . " m,
                                  " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c
                        WHERE p.products_status = 1
                          and p.manufacturers_id = " . (int)$_GET['manufacturers_id'] . "
                          and m.manufacturers_id = " . (int)$_GET['manufacturers_id'] . "
                          and p.products_id = p2c.products_id
                          and pd.products_id = p2c.products_id
                          and pd.language_id = " . (int)$languages_id . "
                          and p2c.categories_id = " . (int)$_GET['filter_id'];
      } // end else { // either retail...
    } else {
      // show them all
      if ($status_product_prices_table) {
        $listing_sql = "SELECT " . $select_column_list . " p.products_id, p.manufacturers_id, tmp_pp.products_price, p.products_tax_class_id,
                               IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price,
                               IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price
                        FROM (" . TABLE_PRODUCTS . " p
                        LEFT JOIN " . $product_prices_table . " as tmp_pp using(products_id)),
                                  " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                  " . TABLE_MANUFACTURERS . " m
                        WHERE p.products_status = 1
                          and pd.products_id = p.products_id
                          and pd.language_id = " . (int)$languages_id . "
                          and p.manufacturers_id = " . (int)$_GET['manufacturers_id'] . "
                          and m.manufacturers_id = " . (int)$_GET['manufacturers_id'];
    
      } else { // either retail or no need to get correct special prices
        $listing_sql = "SELECT " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id,
                               IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price,
                               IF(s.status, s.specials_new_products_price, p.products_price) as final_price
                        FROM (" . TABLE_PRODUCTS . " p
                        LEFT JOIN " . TABLE_SPECIALS . " s on(s.products_id = p.products_id and s.status = 1 and s.customers_group_id = " . (int)$customer_group_id . ") ),
                                  " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                  " . TABLE_MANUFACTURERS . " m
                        WHERE p.products_status = 1
                          and pd.products_id = p.products_id
                          and pd.language_id = " . (int)$languages_id . "
                          and p.manufacturers_id = " . (int)$_GET['manufacturers_id'] . "
                          and m.manufacturers_id = " . (int)$_GET['manufacturers_id'];
   
      } // end else { // either retail...
    }
  } else {
    // show the products in a given categorie
    if (isset($_GET['filter_id']) && tep_not_null($_GET['filter_id'])) {
      // we are asked to show only specific catgeory;
      if ($status_product_prices_table) {
        $listing_sql = "SELECT " . $select_column_list . " p.products_id, p.manufacturers_id, tmp_pp.products_price, p.products_tax_class_id,
                               IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price,
                               IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price
                        FROM (" . TABLE_PRODUCTS . " p
                        LEFT JOIN " . $product_prices_table . " as tmp_pp using(products_id)),
                                  " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                  " . TABLE_MANUFACTURERS . " m,
                                  " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c
                        WHERE p.products_status = 1
                          and p.manufacturers_id = " . (int)$_GET['filter_id'] . "
                          and m.manufacturers_id = " . (int)$_GET['filter_id'] . "
                          and p.products_id = p2c.products_id
                          and pd.products_id = p2c.products_id
                          and pd.language_id = " . (int)$languages_id . "
                          and p2c.categories_id = " . (int)$current_category_id;
        
      } else { // either retail or no need to get correct special prices
        $listing_sql = "SELECT " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id,
                               IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price,
                               IF(s.status, s.specials_new_products_price, p.products_price) as final_price
                        FROM (" . TABLE_PRODUCTS . " p
                        LEFT JOIN " . TABLE_SPECIALS . " s on(s.products_id = p.products_id and s.status = 1 and s.customers_group_id = " . (int)$customer_group_id . ") ),
                                  " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                  " . TABLE_MANUFACTURERS . " m,
                                  " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c
                        WHERE p.products_status = 1
                          and p.manufacturers_id = " . (int)$_GET['filter_id'] . "
                          and m.manufacturers_id = " . (int)$_GET['filter_id'] . "
                          and p.products_id = p2c.products_id
                          and pd.products_id = p2c.products_id
                          and pd.language_id = " . (int)$languages_id . "
                          and p2c.categories_id = " . (int)$current_category_id;
        
      } // end else { // either retail...
    } else {
      // we show them all
      if ($status_product_prices_table) {
        $listing_sql = "SELECT " . $select_column_list . " p.products_id, p.manufacturers_id, tmp_pp.products_price, p.products_tax_class_id,
                               IF(tmp_pp.status, tmp_pp.specials_new_products_price, NULL) as specials_new_products_price,
                               IF(tmp_pp.status, tmp_pp.specials_new_products_price, tmp_pp.products_price) as final_price
                        FROM (" . TABLE_PRODUCTS . " p
                        LEFT JOIN " . $product_prices_table . " as tmp_pp using(products_id) )
                        LEFT JOIN " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id,
                                  " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                  " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c
                        WHERE p.products_status = 1
                          and p.products_id = p2c.products_id  
                          and pd.products_id = p2c.products_id
                          and pd.language_id = " . (int)$languages_id . "
                          and p2c.categories_id = " . (int)$current_category_id;
      } else { // either retail or no need to get correct special prices
        $listing_sql = "SELECT " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id,
                               IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price,
                               IF(s.status, s.specials_new_products_price, p.products_price) as final_price
                        FROM (" . TABLE_PRODUCTS . " p
                        LEFT JOIN " . TABLE_SPECIALS . " s on(s.products_id = p.products_id and s.status = 1 and s.customers_group_id = " . (int)$customer_group_id . ") )
                        LEFT JOIN " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id,
                                  " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                  " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c
                        WHERE p.products_status = 1
                          and p.products_id = p2c.products_id
                          and pd.products_id = p2c.products_id
                          and pd.language_id = " . (int)$languages_id . "
                          and p2c.categories_id = " . (int)$current_category_id;
      } // end else { // either retail...
    }
  }
  // criteria to show only Allowed Group Products
  $customer_group_id1 = array();
  if (isset($_SESSION['customer_id']) ){
    if($_SESSION['customer_id'] == "") {
      $customer_group_id1[] = "G";
    } else {
      $customer_group_id1 = tep_get_customers_access_group($_SESSION['customer_id']);
    }
  } else {
    $customer_group_id1[] = "G";
  }
  $listing_sql .= tep_get_access_sql('p.products_group_access', $customer_group_id1);

  if ( (!isset($_GET['sort'])) || (!ereg('[1-8][ad]', $_GET['sort'])) || (substr($_GET['sort'], 0, 1) > sizeof($column_list)) ) {
    $sort_column = (defined('PRODUCTS_SORT_ORDER')) ? strtoupper(PRODUCTS_SORT_ORDER) : 'NAME';
    $sort_order = 'a';
  } else {
    $sort_col = substr($_GET['sort'], 0 , 1);
    $sort_column = $column_list[$sort_col-1];
    $sort_order = substr($_GET['sort'], 1);
  }
  $listing_sql .= ' ORDER BY ';

  switch ($sort_column) {
    case 'MODEL':
    case 'PRODUCT_LIST_MODEL':
      $listing_sql .= "p.products_model " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";       
      break;
    case 'MANUFACTURER':
    case 'PRODUCT_LIST_MANUFACTURER':
      $listing_sql .= "m.manufacturers_name " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
      break;
    case 'QUANTITY':
    case 'PRODUCT_LIST_QUANTITY':
      $listing_sql .= "p.products_quantity " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";       
      break;
    case 'LAST ADDED':
      $listing_sql .= "p.products_date_added desc";
      break;
    case 'WEIGHT':
    case 'PRODUCT_LIST_WEIGHT':
      $listing_sql .= "p.products_weight " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
      break;
    case 'PRICE':
    case 'PRODUCT_LIST_PRICE':
      $listing_sql .= "p.products_price " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
      break;
    case 'SORT ORDER':
      $listing_sql .= "p.sort_order " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
      break;
    default:
      $listing_sql .= "pd.products_name " . ($sort_order == 'd' ? 'desc' : '');     
      break;        
  }
  $content = CONTENT_INDEX_PRODUCTS;
} else { // default page
  $content = CONTENT_INDEX_DEFAULT;
}
if((int)$current_category_id!=0) {
  if (isset($_SESSION['customer_id']) ){
    if($_SESSION['customer_id'] == "") {
      $customer_group_id1[] = "G";
    } else {
      $customer_group_id1 = tep_get_customers_access_group($_SESSION['customer_id']);
    }
  } else {
    $customer_group_id1[] = "G";
  }
  $category_query_raw = "select count(categories_id) as catcount from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'";
  $category_query_raw .= tep_get_access_sql('products_group_access', $customer_group_id1);
  $category_query = tep_db_query($category_query_raw);
  $category = tep_db_fetch_array($category_query);
  if ($category['catcount'] == 0) {
    $content = CONTENT_INDEX_RESTRICTED;
  } // Overriding the Content if the Category is not visible to the Group
}
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>