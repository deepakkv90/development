<?php
/*
  $Id: products_new.php,v 1.1.1.1 2004/03/04 23:38:02 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

this file gets all the reaw data needed by product_listing and product_listing_column to build
the display

*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCTS_NEW);

  // Eversun mod for sppc and qty price breaks
  if(!isset($_SESSION['sppc_customer_group_id'])) {
  $customer_group_id = 'G';
  } else {
   $customer_group_id = $_SESSION['sppc_customer_group_id'];
  }

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

  // find out if sorting by price has been requested
  if ( (isset($_GET['sort'])) && (ereg('[1-8][ad]', $_GET['sort'])) && (substr($_GET['sort'], 0, 1) <= sizeof($column_list)) && $customer_group_id != '0' ){
    $_sort_col = substr($_GET['sort'], 0 , 1);
    if ($column_list[$_sort_col-1] == 'PRODUCT_LIST_PRICE') {
      $status_need_to_get_prices = true;
    }
  }

  $select_column_list = '';
  $need_manufacturer = false;
  $need_price = false;
  for ($i = 0, $n = sizeof($column_list); $i < $n; ++$i) {
    switch ($column_list[$i]) {
      case 'PRODUCT_LIST_MODEL':
        $select_column_list .= 'p.products_model, ';
        break;
      case 'PRODUCT_LIST_NAME':
        $select_column_list .= 'pd.products_name, ';
        break;
      case 'PRODUCT_LIST_MANUFACTURER':
        $select_column_list .= 'm.manufacturers_name, p.manufacturers_id, ';
        $need_manufacturer = true;
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
      case 'PRODUCT_LIST_PRICE':
        $need_price = true;
        break;
    }
  }

  // start building the sql
  $listing_sql = "SELECT DISTINCT " . $select_column_list . "
                          p.products_id,
                          p.products_image ";
  if ($need_price === true) {
    $listing_sql .= "    , p.products_price,
                          p.products_tax_class_id,
                          IF(s.status, IF(ISNULL(pg.customers_group_price), LEAST(s.specials_new_products_price, p.products_price), LEAST(pg.customers_group_price, s.specials_new_products_price)), IF(ISNULL(pg.customers_group_price), p.products_price, LEAST(pg.customers_group_price, p.products_price))) as final_price ";
  }
  // add the tables to be selected from
  if ($need_price === true && $need_manufacturer === true) {
    $listing_sql .= "  FROM ((" . TABLE_PRODUCTS . " p
                       LEFT JOIN " . TABLE_SPECIALS . " s using(products_id) )
                       LEFT JOIN " . TABLE_PRODUCTS_GROUPS . " pg on p.products_id = pg.products_id and pg.customers_group_id = '" . $customer_group_id . "')
                       LEFT JOIN " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id,
                         " . TABLE_PRODUCTS_DESCRIPTION . " pd ";
  } elseif ($need_price === true) {
    $listing_sql .= "  FROM (" . TABLE_PRODUCTS . " p
                       LEFT JOIN " . TABLE_SPECIALS . " s using(products_id) )
                       LEFT JOIN " . TABLE_PRODUCTS_GROUPS . " pg on p.products_id = pg.products_id and pg.customers_group_id = '" . $customer_group_id . "',
                        " . TABLE_PRODUCTS_DESCRIPTION . " pd ";
  } elseif ($need_manufacturer === true) {
    $listing_sql .= "  FROM (" . TABLE_PRODUCTS . " p
                       LEFT JOIN " . TABLE_MANUFACTURERS . " m using(manufacturers_id) ),
                         " . TABLE_PRODUCTS_DESCRIPTION . " pd ";
  } else {
    $listing_sql .= "  FROM " . TABLE_PRODUCTS . " p,
                         " . TABLE_PRODUCTS_DESCRIPTION . " pd ";
  }
  // now add the where conditions
  $listing_sql .= "  WHERE p.products_status = '1'
                       AND pd.products_id = p.products_id
                       AND pd.language_id = '" . (int)$languages_id . "'
                       AND p.products_group_access LIKE '%" . $customer_group_id . "%'
                       AND DATE_SUB(CURDATE(),INTERVAL " . NEW_PRODUCT_INTERVAL . " DAY) <= p.products_date_added";
  // and finially add the order by as needed
  if ( (!isset($_GET['sort'])) || (!ereg('[1-8][ad]', $_GET['sort'])) || (substr($_GET['sort'], 0, 1) > sizeof($column_list)) ) {
    // before the CATEGORIES_SORT_ORDER can be used, we must check to see if the column was selected
      $sort_column = 'PRODUCT_LIST_NAME';
      for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
        if ($column_list[$i] == CATEGORIES_SORT_ORDER) {
          $sort_column = CATEGORIES_SORT_ORDER;
          break;
        }
      }
      $sort_order = 'a';
  } else {
    $sort_col = substr($_GET['sort'], 0 , 1);
    $sort_column = $column_list[$sort_col-1];
    $sort_order = substr($_GET['sort'], 1);
  }
  // check to see if it is one of the columns being allowed for
  switch ($sort_column) {
    case 'PRODUCT_LIST_MODEL':
      $listing_sql .= " order by p.products_model " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
       break;
    case 'PRODUCT_LIST_NAME':
      $listing_sql .= " order by pd.products_name " . ($sort_order == 'd' ? 'desc' : '');
      break;
    case 'PRODUCT_LIST_MANUFACTURER':
      $listing_sql .= " order by m.manufacturers_name " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
      break;
    case 'PRODUCT_LIST_QUANTITY':
      $listing_sql .= " order by p.products_quantity " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
      break;
    case 'PRODUCT_LIST_IMAGE':
      // sorting by image name makes no sense, so just ignore it
      break;
    case 'PRODUCT_LIST_WEIGHT':
      $listing_sql .= " order by p.products_weight " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
      break;
    case 'PRODUCT_LIST_PRICE':
      $listing_sql .= " order by final_price " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
      break;
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCTS_NEW));

  $content = CONTENT_PRODUCTS_NEW;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
