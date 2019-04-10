<?php
/*
  $Id: categories.php,v 1.3 2007/12/30 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
global $groupcnt;

require('includes/application_top.php');
require('includes/functions/categories_description.php');
require(DIR_WS_CLASSES . 'file_select.php');
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

// RCI code start
echo $cre_RCI->get('global', 'top', false);
echo $cre_RCI->get('categories', 'top', false); 
// RCI code eof 
    
//intilize varibles
$groupcnt = 0; 
$categories_id = '';
$Push = '';
$categories_image = '';
$category_template_id = '';
$categories_previous_image = '';
$extra_field = '';
$parent_name = '';

// array used by the DirSelect class
$ImageLocations['base_dir'] = DIR_FS_CATALOG_IMAGES;
$ImageLocations['base_url'] = DIR_WS_CATALOG_IMAGES; 

// POST GET compatibility
if (isset($_GET['cID'])) {
  $cID = $_GET['cID'] ;
} else if (isset($_POST['cID'])) {
  $cID = $_POST['cID'] ;
} else {
  $cID = '' ;
}
if (isset($_GET['pID'])) {
  $pID = $_GET['pID'] ;
} else if (isset($_POST['pID'])) {
  $pID = $_POST['pID'] ;
} else {
  $pID = '' ;
}
if (isset($_GET['cPath'])) {
  $cPath = $_GET['cPath'] ;
} else if (isset($_POST['cPath'])) {
  $cPath = $_POST['cPath'] ;
} else {
  $cPath = '' ;
}
if (isset($_GET['action'])) {
  $action = $_GET['action'] ;
} else if (isset($_POST['action'])) {
  $action = $_POST['action'] ;
  } else {
  $action = '' ;
}
if (tep_not_null($action)) {
  switch ($action) {
    case 'sort':
      if (isset($_POST['c_sort_order']) && is_array($_POST['c_sort_order'])) {
        foreach ($_POST['c_sort_order'] as $key => $value) {
          tep_db_query("UPDATE " . TABLE_CATEGORIES . " SET sort_order = '" . $value . "' WHERE categories_id = '" . $key . "'");
        }
      }
      if (isset($_POST['sort_order']) && is_array($_POST['sort_order'])) {
        foreach ($_POST['sort_order'] as $key => $value) {
          tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET sort_order = '" . $value . "' WHERE products_id = '" . $key . "'");
        }
      }
      tep_redirect(tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params(array('action'))));
      break;
      
    case 'setflag':
      if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
        if (isset($pID)) {
          tep_set_product_status($pID, $_GET['flag']);
        }
        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }
      }
      tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pID));
      break;
      
    case 'insert_category':
    case 'update_category':
      if (isset($_POST[IMAGE_BACK]) || !isset($_POST['sort_order'])) {
        $action = 'edit_category';
      } else {
        if (isset($_POST['categories_id'])) $categories_id = tep_db_prepare_input($_POST['categories_id']);
        if ($categories_id == '') {
          $categories_id = tep_db_prepare_input($_GET['cID']);
        }
        $sort_order = tep_db_prepare_input($_POST['sort_order']);
        $categories_image = tep_db_prepare_input($_POST['categories_image']);
        $sql_data_array = array('sort_order' => $sort_order);
        $customergroupid = "";
        if (isset($_POST['products_group_access'])) {
          foreach ($_POST['products_group_access'] as $value) {
            $customergroupid .= $value . ',';
          }
        }
        $customergroupid = substr($customergroupid,0,(strlen($customergroupid)-1));
        $sql_data_array['products_group_access'] = $customergroupid;
        $customergroupid_nav_access = "";
        if (isset($_POST['products_nav_access'])) {
          foreach ($_POST['products_nav_access'] as $value) {
            $customergroupid_nav_access .= $value . ',';
          }
        }
        $customergroupid_nav_access = substr($customergroupid_nav_access,0,(strlen($customergroupid_nav_access)-1));
        $sql_data_array['products_nav_access'] = $customergroupid_nav_access;
        
        if ($action == 'insert_category') {
          $insert_sql_data = array('parent_id' => $current_category_id,
                                   'date_added' => 'now()');
          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
          tep_db_perform(TABLE_CATEGORIES, $sql_data_array);
          $categories_id = tep_db_insert_id();
        } elseif ($action == 'update_category') {
          $update_sql_data = array('last_modified' => 'now()');
          $sql_data_array = array_merge($sql_data_array, $update_sql_data);
          tep_db_perform(TABLE_CATEGORIES, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "'");
          if (isset($_POST['Push'])) {
            if($_POST['Push'] == 2) {
              readChild($categories_id,$customergroupid,"C");
            }
            if ($_POST['Push'] == 3) {
              readChild($categories_id,$customergroupid,"CP");
            }
          }
        }
        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $language_id = $languages[$i]['id'];
          $sql_data_array = array('categories_name' => tep_db_prepare_input(tep_db_encoder($_POST['categories_name'][$language_id])),
                                  'categories_heading_title' => tep_db_prepare_input(tep_db_encoder($_POST['categories_heading_title'][$language_id])),
                                  'categories_description' => tep_db_prepare_input(tep_db_encoder($_POST['categories_description'][$language_id])),
                                  'categories_head_title_tag' => tep_db_prepare_input(tep_db_encoder($_POST['categories_head_title_tag'][$language_id])),
                                  'categories_head_desc_tag' => tep_db_prepare_input(tep_db_encoder($_POST['categories_head_desc_tag'][$language_id])),
                                  'categories_head_keywords_tag' => tep_db_prepare_input(tep_db_encoder($_POST['categories_head_keywords_tag'][$language_id]))
                                  );
          
          if ($action == 'insert_category') {
            $insert_sql_data = array('categories_id' => $categories_id,
                                     'language_id' => $languages[$i]['id']);
            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
            tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array);
          } elseif ($action == 'update_category') {
            tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
          }
        }
        if ( ( (isset($_POST['unlink_cat_image'])) && ($_POST['unlink_cat_image'] == 'yes') ) ||
           ( (isset($_POST['delete_cat_image'])) && ($_POST['delete_cat_image'] == 'yes')) ) {
           tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '' where categories_id = '" . (int)$categories_id . "'");
        } else {
          if (isset($_POST['categories_image_name'])) {
            tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . tep_db_input($_POST['categories_image_name']) . "' where categories_id = '" . (int)$categories_id . "'");
          }
        }
        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }
       
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
      }
      break;
      
    case 'delete_category_confirm':
      if (isset($_POST['categories_id'])) {
        $categories_id = tep_db_prepare_input($_POST['categories_id']);
        $categories = tep_get_category_tree($categories_id, '', '0', '', true);
        $products = array();
        $products_delete = array();
        for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
          $product_ids_query = tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$categories[$i]['id'] . "'");
          while ($product_ids = tep_db_fetch_array($product_ids_query)) {
            $products[$product_ids['products_id']]['categories'][] = $categories[$i]['id'];
          }
        }
        reset($products);
        while (list($key, $value) = each($products)) {
          $category_ids = '';
          for ($i=0, $n=sizeof($value['categories']); $i<$n; $i++) {
            $category_ids .= "'" . (int)$value['categories'][$i] . "', ";
          }
          $category_ids = substr($category_ids, 0, -2);
          $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$key . "' and categories_id not in (" . $category_ids . ")");
          $check = tep_db_fetch_array($check_query);
          if ($check['total'] < '1') {
            $products_delete[$key] = $key;
          }
        }
        tep_set_time_limit(0);
        for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
          tep_remove_category($categories[$i]['id']);
        }
        reset($products_delete);
        while (list($key) = each($products_delete)) {
          tep_remove_product($key);
        }
      }
      if (USE_CACHE == 'true') {
        tep_reset_cache_block('categories');
        tep_reset_cache_block('also_purchased');
      }


      tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
      break;
      
    case 'delete_product_confirm':
      if (isset($_POST['products_id']) && isset($_POST['product_categories']) && is_array($_POST['product_categories'])) {
        $product_id = tep_db_prepare_input($_POST['products_id']);
        $product_categories = $_POST['product_categories'];
        // sub products
        $sub_products_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_parent_id = '" . tep_db_input($product_id) . "'");
        while ($sub_products = tep_db_fetch_array($sub_products_query)) {
          for ($i=0, $n=sizeof($product_categories); $i<$n; $i++) {
            tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . tep_db_input($sub_products['products_id']) . "' and categories_id = '" . (int)$product_categories[$i] . "'");
          }
          $product_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$sub_products['products_id'] . "'");
          $product_categories1 = tep_db_fetch_array($product_categories_query);
          if ($product_categories1['total'] == '0') {
            tep_db_query("delete from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . tep_db_input($sub_products['products_id']) . "'");
            tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . tep_db_input($sub_products['products_id']) . "'");
            tep_db_query("delete from " . TABLE_PRODUCTS_XSELL . " where products_id = '" . tep_db_input($sub_products['products_id']) . "'");
            tep_db_query("delete from " . TABLE_SPECIALS . " where products_id = '" . tep_db_input($sub_products['products_id']) . "'");
            tep_db_query("delete from " . TABLE_PRODUCTS . " where products_id = '" . tep_db_input($sub_products['products_id']) . "'");
          }
        }
        // sub products eof
        for ($i=0, $n=sizeof($product_categories); $i<$n; $i++) {
          tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "' and categories_id = '" . (int)$product_categories[$i] . "'");
        }
        $product_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");
        $product_categories = tep_db_fetch_array($product_categories_query);
        if ($product_categories['total'] == '0') {
          tep_remove_product($product_id);
        }
      }
      if (USE_CACHE == 'true') {
        tep_reset_cache_block('categories');
        tep_reset_cache_block('also_purchased');
      }
      tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
      break;
      
    case 'move_category_confirm':
      if (isset($_POST['categories_id']) && ($_POST['categories_id'] != $_POST['move_to_category_id'])) {
        $categories_id = tep_db_prepare_input($_POST['categories_id']);
        $new_parent_id = tep_db_prepare_input($_POST['move_to_category_id']);
        $path = explode('_', tep_get_generated_category_path_ids($new_parent_id));
        if (in_array($categories_id, $path)) {
          $messageStack->add_session('search', ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');
          tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
        } else {
          tep_db_query("update " . TABLE_CATEGORIES . " set parent_id = '" . (int)$new_parent_id . "', last_modified = now() where categories_id = '" . (int)$categories_id . "'");
          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
          }
          tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&cID=' . $categories_id));
        }
      }
      break;
      
    case 'move_product_confirm':
      $products_id = tep_db_prepare_input($_POST['products_id']);
      $new_parent_id = tep_db_prepare_input($_POST['move_to_category_id']);
      $duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$new_parent_id . "'");
      $duplicate_check = tep_db_fetch_array($duplicate_check_query);
      if ($duplicate_check['total'] < 1) tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . (int)$new_parent_id . "' where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$current_category_id . "'");
      // sub products
      $sub_products_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_parent_id = '" . (int)$products_id . "'");
      while ($sub_products = tep_db_fetch_array($sub_products_query)) {
        $duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $sub_products['products_id'] . "' and categories_id = '" . $new_parent_id . "'");
        $duplicate_check = tep_db_fetch_array($duplicate_check_query);
        if ($duplicate_check['total'] < 1) tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . (int)$new_parent_id . "' where products_id = '" . (int)$sub_products['products_id'] . "' and categories_id = '" . (int)$current_category_id . "'");
      }
      // sub products eof
      if (USE_CACHE == 'true') {
        tep_reset_cache_block('categories');
        tep_reset_cache_block('also_purchased');
      }
      tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&pID=' . $products_id));
      break;
      
    case 'create_copy_product_attributes':
      $copy_to_products_id = (int)$_POST['copy_to_products_id'];
      tep_copy_products_attributes($pID,$copy_to_products_id);
      break;
      
    case 'create_copy_product_attributes_categories':
      $categories_products_copying_query= tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id='" . $cID . "'");
      while ( $categories_products_copying=tep_db_fetch_array($categories_products_copying_query) ) {
        // process all products in category
        tep_copy_products_attributes($make_copy_from_products_id,$categories_products_copying['products_id']);
      }
      break;
      
    case 'update_product':
      $languages = tep_get_languages();
      $products_id = (int)$_GET['pID'];
      // get the current product data so we can compare it later to see what has changed
      $products_old_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS . " WHERE products_id = " . $products_id);
      $products_old = tep_db_fetch_array($products_old_query);
      $isSubProduct = false;
      if ($products_old['products_parent_id'] > 0) $isSubProduct = true;
      $products_description_old_query = tep_db_query("SELECT language_id, products_name, products_blurb, products_description, products_url, products_viewed, 
                                                             products_head_title_tag, products_head_desc_tag, products_head_keywords_tag
                                                      FROM " . TABLE_PRODUCTS_DESCRIPTION . "
                                                      WHERE products_id = " . $products_id);
      $products_description_old = array();
      while ($description_old = tep_db_fetch_array($products_description_old_query)) {
        $products_description_old[$description_old['language_id']] = array('products_name' => $description_old['products_name'],
                                                                           'products_blurb' => $description_old['products_blurb'], 
                                                                           'products_description' => $description_old['products_description'],
                                                                           'products_url' => $description_old['products_url'],
                                                                           'products_viewed' => $description_old['products_viewed'], 
                                                                           'products_head_title_tag' => $description_old['products_head_title_tag'],
                                                                           'products_head_desc_tag' => $description_old['products_head_desc_tag'],
                                                                           'products_head_keywords_tag' => $description_old['products_head_keywords_tag']
                                                                          );
      }
      unset($products_old_query);
      unset($products_description_old_query);
      unset($description_old);
      
      $sql_data_array = array(); //declare the array and add to it anything changed
      $products_date_available = tep_db_prepare_input($_POST['products_date_available']);
      if ($products_date_available != $products_old['products_date_available']) $sql_data_array['products_date_available'] = $products_date_available;
      $products_quantity = tep_db_prepare_input($_POST['products_quantity']);
      if ($products_quantity != $products_old['products_quantity']) $sql_data_array['products_quantity'] = $products_quantity;      
	  //Mar 03 2011 - added products_min_order_qty entry
	  $products_min_order_qty = tep_db_prepare_input($_POST['products_min_order_qty']);
      if ($products_min_order_qty != $products_old['products_min_order_qty']) $sql_data_array['products_min_order_qty'] = $products_min_order_qty;
	  
	  //products_type entry
      $products_type = tep_db_prepare_input($_POST['products_type']);
      if ($products_type != $products_old['products_type']) {
          $sql_data_array['products_type'] = $products_type;
      }
	  
	  //Aug 02 2012
	  $overall_quantity = tep_db_prepare_input($_POST['overall_quantity']);
      if ($overall_quantity != $products_old['overall_quantity']) $sql_data_array['overall_quantity'] = $overall_quantity;
	  
	  $progressed_quantity = tep_db_prepare_input($_POST['progressed_quantity']);
      if ($progressed_quantity != $products_old['progressed_quantity']) $sql_data_array['progressed_quantity'] = $progressed_quantity;
	  
	   //Product costs
	  $labour_cost = tep_db_prepare_input($_POST['labour_cost']);
      if ($labour_cost != $products_old['labour_cost']) $sql_data_array['labour_cost'] = $labour_cost;
	  $overhead_cost = tep_db_prepare_input($_POST['overhead_cost']);
      if ($overhead_cost != $products_old['overhead_cost']) $sql_data_array['overhead_cost'] = $overhead_cost;
	  $material_cost = tep_db_prepare_input($_POST['material_cost']);
      if ($material_cost != $products_old['material_cost']) $sql_data_array['material_cost'] = $material_cost;
	  
	  $products_model = tep_db_prepare_input(tep_db_encoder($_POST['products_model']));
      if ($products_model != $products_old['products_model']) $sql_data_array['products_model'] = $products_model;
      $max_lines_count = tep_db_prepare_input($_POST['max_lines_count']);
      if ($max_lines_count != $products_old['max_lines_count']) $sql_data_array['max_lines_count'] = $max_lines_count;
      $max_images_count = tep_db_prepare_input($_POST['max_images_count']);
      if ($max_images_count != $products_old['max_images_count']) $sql_data_array['max_images_count'] = $max_images_count;
      $default_texts = tep_db_prepare_input($_POST['default_texts']);
      if ($default_texts != $products_old['default_texts']) $sql_data_array['default_texts'] = $default_texts;
      $products_price = tep_db_prepare_input($_POST['products_price']);
      if ($products_price != $products_old['products_price']) $sql_data_array['products_price'] = $products_price;
      $products_weight = isset($_POST['products_weight']) ? tep_db_prepare_input($_POST['products_weight']) : 0;
      if ($products_weight != $products_old['products_weight']) $sql_data_array['products_weight'] = $products_weight;
      $products_status = isset($_POST['products_status']) ? tep_db_prepare_input($_POST['products_status']) : 0;
      if ($products_status != $products_old['products_status']) $sql_data_array['products_status'] = $products_status;
      $products_tax_class_id = isset($_POST['products_tax_class_id']) ? tep_db_prepare_input($_POST['products_tax_class_id']) : 0;
      if ($products_tax_class_id != $products_old['products_tax_class_id']) $sql_data_array['products_tax_class_id'] = $products_tax_class_id;
      $manufacturers_id = isset($_POST['manufacturers_id']) ? tep_db_prepare_input($_POST['manufacturers_id']) : 0;
      if ($manufacturers_id != $products_old['manufacturers_id']) $sql_data_array['manufacturers_id'] = $manufacturers_id;
      $sort_order = isset($_POST['sort_order']) ? tep_db_prepare_input($_POST['sort_order']) : 0;
      if ($sort_order != $products_old['sort_order']) $sql_data_array['sort_order'] = $sort_order;
      // multi-vendor shipping
      $vendors_id = isset($_POST['vendors_id']) ? tep_db_prepare_input($_POST['vendors_id']) : 0;
      if ($vendors_id != $products_old['vendors_id']) $sql_data_array['vendors_id'] = $vendors_id;
      $vendors_prod_id = isset($_POST['vendors_prod_id']) ? tep_db_prepare_input($_POST['vendors_prod_id']) : 0;
      if ($vendors_prod_id != $products_old['vendors_prod_id']) $sql_data_array['vendors_prod_id'] = $vendors_prod_id;
      $vendors_prod_comments = isset($_POST['vendors_prod_comments']) ? tep_db_prepare_input($_POST['vendors_prod_comments']) : '';
      if ($vendors_prod_comments != $products_old['vendors_prod_comments']) $sql_data_array['vendors_prod_comments'] = $vendors_prod_comments;
      $vendors_product_price = isset($_POST['vendors_product_price']) ? tep_db_prepare_input($_POST['vendors_product_price']) : '';
      if ($vendors_product_price != $products_old['vendors_product_price']) $sql_data_array['vendors_product_price'] = $vendors_product_price;
      // multi-vendor shipping //eof
      $images = array(array('table' => 'products_image',      'delete' => 'delete_image',      'unlink' => 'unlink_image'),
                      array('table' => 'products_image_med',  'delete' => 'delete_image_med',  'unlink' => 'unlink_image_med'),
                      array('table' => 'products_image_lrg',  'delete' => 'delete_image_lrg',  'unlink' => 'unlink_image_lrg'),
                      array('table' => 'products_image_rls2', 'delete' => 'delete_image_rls2', 'unlink' => 'unlink_image_rls2'),
                      array('field' => 'products_image',      'path'   => 'file_dir_exist_dest_small', 'file' => 'file_exist_dest_small'),
                      array('field' => 'products_image_med',  'path'   => 'file_dir_exist_dest_med', 'file' => 'file_exist_dest_med'),
                      array('field' => 'products_image_lrg',  'path'   => 'file_dir_exist_dest_lrg', 'file' => 'file_exist_dest_lrg'),
                      array('field' => 'products_image_rls2',  'path'   => 'file_dir_exist_dest_rls2', 'file' => 'file_exist_dest_rls2'),
                      array('table' => 'products_image_sm_1', 'delete' => 'delete_image_sm_1', 'unlink' => 'unlink_image_sm_1'),
                      array('table' => 'products_image_xl_1', 'delete' => 'delete_image_xl_1', 'unlink' => 'unlink_image_xl_1'),
                      array('table' => 'products_image_sm_2', 'delete' => 'delete_image_sm_2', 'unlink' => 'unlink_image_sm_2'),
                      array('table' => 'products_image_xl_2', 'delete' => 'delete_image_xl_2', 'unlink' => 'unlink_image_xl_2'),
                      array('table' => 'products_image_sm_3', 'delete' => 'delete_image_sm_3', 'unlink' => 'unlink_image_sm_3'),
                      array('table' => 'products_image_xl_3', 'delete' => 'delete_image_xl_3', 'unlink' => 'unlink_image_xl_3'),
                      array('table' => 'products_image_sm_4', 'delete' => 'delete_image_sm_4', 'unlink' => 'unlink_image_sm_4'),
                      array('table' => 'products_image_xl_4', 'delete' => 'delete_image_xl_4', 'unlink' => 'unlink_image_xl_4'),
                      array('table' => 'products_image_sm_5', 'delete' => 'delete_image_sm_5', 'unlink' => 'unlink_image_sm_5'),
                      array('table' => 'products_image_xl_5', 'delete' => 'delete_image_xl_5', 'unlink' => 'unlink_image_xl_5'),
                      array('table' => 'products_image_sm_6', 'delete' => 'delete_image_sm_6', 'unlink' => 'unlink_image_sm_6'),
                      array('table' => 'products_image_xl_6', 'delete' => 'delete_image_xl_6', 'unlink' => 'unlink_image_xl_6')
                     );
      foreach ($images as $image) {
        if (isset($_POST[$image['delete']]) && $_POST[$image['delete']] == 'yes' && $products_old[$image['table']] != '') {
          unlink(DIR_FS_CATALOG_IMAGES . $products_old[$image['table']]);
          $sql_data_array[$image['table']] = '';
        } elseif (isset($_POST[$image['unlink']]) && $_POST[$image['unlink']] == 'yes' && $products_old[$image['table']] != '') { 
          $sql_data_array[$image['table']] = '';
        } elseif (isset($_FILES[$image['table']]) && tep_not_null($_FILES[$image['table']]['name'])) {
          if (strtolower($_FILES[$image['table']]['name']) != 'none') {
            $uploadFile = DIR_FS_CATALOG_IMAGES . urldecode($_POST[$image['dir']]) . $_FILES[$image['table']]['name'];
            @move_uploaded_file($_FILES[$image['table']]['tmp_name'], $uploadFile);
            if ($_FILES[$image['table']]['name'] != $products_old[$image['table']]) $sql_data_array[$image['table']] = tep_db_prepare_input(urldecode($_POST[$image['dir']]) . $_FILES[$image['table']]['name']);
          } elseif ($products_old[$image['table']] != '') {
            $sql_data_array[$image['table']] = '';
          }
        } elseif(isset($_POST[$image['file']]) && tep_not_null($_POST[$image['file']])) {
          $sql_data_array[$image['field']] = tep_db_prepare_input(rawurldecode($_POST[$image['path']]) . rawurldecode($_POST[$image['file']]));
        } elseif (isset($_POST[$image['table']]) && tep_not_null($_POST[$image['table']])) {
          if (strtolower($_POST[$image['table']]) != 'none') {
            if ($_POST[$image['table']] != $products_old[$image['table']]) $sql_data_array[$image['table']] = tep_db_prepare_input($_POST[$image['table']]);
          } elseif ($products_old[$image['table']] != '') {
            $sql_data_array[$image['table']] = '';
          }
        }
      }
      
      $customergroupid = "";
      if (isset($_POST['products_group_access'])) {
        foreach ($_POST['products_group_access'] as $value) {
          $customergroupid .= $value . ',';
        }      
        $customergroupid = substr($customergroupid,0,(strlen($customergroupid)-1));
      }
      if ($products_old['products_group_access'] != $customergroupid) $sql_data_array['products_group_access'] = $customergroupid;
      $customergroupid = "";
      if (isset($_POST['products_nav_access'])) {
        foreach ($_POST['products_nav_access'] as $value) {
          $customergroupid .= $value . ',';
        }
        $customergroupid = substr($customergroupid,0,(strlen($customergroupid)-1));
      }
      if ($products_old['products_nav_access'] != $customergroupid) $sql_data_array['products_nav_access'] = $customergroupid;
      
      $pricing_array = tep_get_pricing();

      for ($i = 1; $i <= 11; ++$i) {
        if ($products_old['products_price'.$i] != $pricing_array[$i]['price']) $sql_data_array['products_price'.$i] = $pricing_array[$i]['price'];
        if ($products_old['products_price'.$i.'_qty'] != $pricing_array[$i]['qty']) $sql_data_array['products_price'.$i.'_qty'] = $pricing_array[$i]['qty'];
      }
      
      if (isset($_POST['products_history']) && $_POST['products_history'] > 0) {
        if ($_POST['products_history_qty'] == 'days') {
          if ($products_old['products_qty_days'] != (int)$_POST['products_history']) $sql_data_array['products_qty_days'] = (int)$_POST['products_history'];
          if ($products_old['products_qty_years'] != 0) $sql_data_array['products_qty_years'] = 0;
        } else {
          if ($products_old['products_qty_days'] != 0) $sql_data_array['products_qty_days'] = 0;
          if ($products_old['products_qty_years'] != (int)$_POST['products_history'])$sql_data_array['products_qty_years'] = (int)$_POST['products_history'];
        }
      }
      
      // check to see if there is anything to actually update in the products table
      if (count($sql_data_array) > 0 ) {
        $sql_data_array['products_last_modified'] = 'now()';
        tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', 'products_id = ' . (int)$products_id);
      }
      
      tep_update_group_pricing($products_id);
      
      // at this point, the processing is different if it is a parent product or a sub product
      // sub products do not have extra fields, do not have sub sub products, and the name handling is different
      // parent products do allow for extra fileds, can have sub products, and the name is simply what is supplied
      if ($isSubProduct) {
        // process the sub products description table data
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $language_id = $languages[$i]['id'];
          $products_name_input = tep_db_prepare_input(tep_db_encoder($_POST['products_name'][$language_id]));
          $parent_name_query = tep_db_query("SELECT products_name
                                             FROM " . TABLE_PRODUCTS_DESCRIPTION . "
                                             WHERE products_id = " . (int)$products_old['products_parent_id'] . " and language_id = " . (int)$language_id);
          $parent_name = tep_db_fetch_array($parent_name_query);
          $products_name = $parent_name['products_name'] . ' - ' . $products_name_input;
          if (!isset($products_description_old[$language_id])) {
            $_POST['products_url'][$language_id] = urldecode($_POST['products_url'][$language_id]);
            if (substr($_POST['products_url'][$language_id], 0, 7) == 'http://') $_POST['products_url'][$language_id] = substr($_POST['products_url'][$language_id], 7);
            $sql_data_array = array('products_name' => $products_name,
                                    'products_blurb' => tep_db_prepare_input(tep_db_encoder($_POST['products_blurb'][$language_id])),
                                    'products_description' => tep_db_prepare_input(tep_db_encoder($_POST['products_description'][$language_id])),
                                    'products_url' => tep_db_prepare_input($_POST['products_url'][$language_id]),
                                    'products_head_title_tag' => tep_db_prepare_input(tep_db_encoder($_POST['products_head_title_tag'][$language_id])),
                                    'products_head_desc_tag' => tep_db_prepare_input(tep_db_encoder($_POST['products_head_desc_tag'][$language_id])),
                                    'products_head_keywords_tag' => tep_db_prepare_input(tep_db_encoder($_POST['products_head_keywords_tag'][$language_id]))
                                   );
          } else {
            $sql_data_array = array(); //declare the array and add to it anything changed
            $products_name_input = tep_db_prepare_input(tep_db_encoder($_POST['products_name'][$language_id]));
            $parent_name_query = tep_db_query("SELECT products_name
                                               FROM " . TABLE_PRODUCTS_DESCRIPTION . "
                                               WHERE products_id = " . (int)$products_old['products_parent_id'] . " and language_id = " . (int)$language_id);
            $parent_name = tep_db_fetch_array($parent_name_query);
            $products_name = $parent_name['products_name'] . ' - ' . $products_name_input;
            if ($products_description_old[$language_id]['products_name'] != $products_name) {
              $sql_data_array['products_name'] = tep_db_encoder($products_name);
            }
            $products_blurb = tep_db_prepare_input(tep_db_encoder($_POST['products_blurb'][$language_id]));
            if ($products_description_old[$language_id]['products_blurb'] != $products_blurb) $sql_data_array['products_blurb'] = tep_db_encoder($products_blurb);
            $products_description = tep_db_prepare_input(tep_db_encoder($_POST['products_description'][$language_id]));
            if ($products_description_old[$language_id]['products_description'] != $products_description) $sql_data_array['products_description'] = tep_db_encoder($products_description);
            $_POST['products_url'][$language_id] = urldecode($_POST['products_url'][$language_id]);
            if (substr($_POST['products_url'][$language_id], 0, 7) == 'http://') $_POST['products_url'][$language_id] = substr($_POST['products_url'][$language_id], 7);
            $products_url = tep_db_prepare_input($_POST['products_url'][$language_id]);
            if ($products_description_old[$language_id]['products_url'] != $products_url) $sql_data_array['products_url'] = $products_url;
            $products_head_title_tag = tep_db_prepare_input(tep_db_encoder($_POST['products_head_title_tag'][$language_id]));
            if ($products_description_old[$language_id]['products_head_title_tag'] != $products_head_title_tag) $sql_data_array['products_head_title_tag'] = $products_head_title_tag;
            $products_head_desc_tag = tep_db_prepare_input(tep_db_encoder($_POST['products_head_desc_tag'][$language_id]));
            if ($products_description_old[$language_id]['products_head_desc_tag'] != $products_head_desc_tag) $sql_data_array['products_head_desc_tag'] = $products_head_desc_tag;
            $products_head_keywords_tag = tep_db_prepare_input(tep_db_encoder($_POST['products_head_keywords_tag'][$language_id]));
            if ($products_description_old[$language_id]['products_head_keywords_tag'] != $products_head_keywords_tag) $sql_data_array['products_head_keywords_tag'] = $products_head_keywords_tag;          
          }
        
          // check to see if there is anything to actually update in the products table
          if (count($sql_data_array) > 0 ) {
            tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', 'products_id = ' . (int)$products_id . ' and language_id = ' . (int)$language_id);
          }
        }
        
      } else {
        // process the products description table data
        $products_description_parent = array(); // save the name and description for later use in processing sub products
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $language_id = $languages[$i]['id'];
          $products_description_parent[$language_id] = array('products_name' => tep_db_prepare_input(tep_db_encoder($_POST['products_name'][$language_id])),
                                                             'products_blurb' => tep_db_prepare_input(tep_db_encoder($_POST['products_blurb'][$language_id])), 
                                                             'products_description' => tep_db_prepare_input(tep_db_encoder($_POST['products_description'][$language_id]))
                                                             );
          if (!isset($products_description_old[$language_id])) {
            $_POST['products_url'][$language_id] = urldecode($_POST['products_url'][$language_id]);
            if (substr($_POST['products_url'][$language_id], 0, 7) == 'http://') $_POST['products_url'][$language_id] = substr($_POST['products_url'][$language_id], 7);
            $sql_data_array = array('products_name' => tep_db_prepare_input(tep_db_encoder($_POST['products_name'][$language_id])),
                                    'products_blurb' => tep_db_prepare_input(tep_db_encoder($_POST['products_blurb'][$language_id])),
                                    'products_description' => tep_db_prepare_input(tep_db_encoder($_POST['products_description'][$language_id])),
                                    'products_url' => tep_db_prepare_input($_POST['products_url'][$language_id]),
                                    'products_head_title_tag' => tep_db_prepare_input(tep_db_encoder($_POST['products_head_title_tag'][$language_id])),
                                    'products_head_desc_tag' => tep_db_prepare_input(tep_db_encoder($_POST['products_head_desc_tag'][$language_id])),
                                    'products_head_keywords_tag' => tep_db_prepare_input(tep_db_encoder($_POST['products_head_keywords_tag'][$language_id]))
                                   );
          } else {
            $sql_data_array = array(); //declare the array and add to it anything changed
            $products_name = tep_db_prepare_input(tep_db_encoder($_POST['products_name'][$language_id]));
            if ($products_description_old[$language_id]['products_name'] != $products_name) {
              $sql_data_array['products_name'] = $products_name;
              // if this is a parent product, any subs may need updating due to the name change
              tep_update_sub_name($products_id, $products_description_old[$language_id]['products_name'], $products_name, $language_id);
            }
            $products_blurb = tep_db_prepare_input(tep_db_encoder($_POST['products_blurb'][$language_id]));
            if ($products_description_old[$language_id]['products_blurb'] != $products_blurb) $sql_data_array['products_blurb'] = tep_db_encoder($products_blurb);
            $products_description = tep_db_prepare_input(tep_db_encoder($_POST['products_description'][$language_id]));
            if ($products_description_old[$language_id]['products_description'] != $products_description) $sql_data_array['products_description'] = tep_db_encoder($products_description);
            $_POST['products_url'][$language_id] = urldecode($_POST['products_url'][$language_id]);
            if (substr($_POST['products_url'][$language_id], 0, 7) == 'http://') $_POST['products_url'][$language_id] = substr($_POST['products_url'][$language_id], 7);
            $products_url = tep_db_prepare_input($_POST['products_url'][$language_id]);
            if ($products_description_old[$language_id]['products_url'] != $products_url) $sql_data_array['products_url'] = $products_url;
            $products_head_title_tag = tep_db_prepare_input(tep_db_encoder($_POST['products_head_title_tag'][$language_id]));
            if ($products_description_old[$language_id]['products_head_title_tag'] != $products_head_title_tag) $sql_data_array['products_head_title_tag'] = $products_head_title_tag;
            $products_head_desc_tag = tep_db_prepare_input(tep_db_encoder($_POST['products_head_desc_tag'][$language_id]));
            if ($products_description_old[$language_id]['products_head_desc_tag'] != $products_head_desc_tag) $sql_data_array['products_head_desc_tag'] = $products_head_desc_tag;
            $products_head_keywords_tag = tep_db_prepare_input(tep_db_encoder($_POST['products_head_keywords_tag'][$language_id]));
            if ($products_description_old[$language_id]['products_head_keywords_tag'] != $products_head_keywords_tag) $sql_data_array['products_head_keywords_tag'] = $products_head_keywords_tag;          
          }
        
          // check to see if there is anything to actually update in the products table
          if (count($sql_data_array) > 0 ) {
            tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', 'products_id = ' . (int)$products_id . ' and language_id = ' . (int)$language_id);
          }
        }
      
		//insert badge settings if badge products
        if (isset($_POST['products_type'])) {
                
            if(tep_is_badge_settings_exists($products_id)) {
                tep_db_query("UPDATE products_badge_settings SET bgcolor='".$_POST['bgcolor']."', bgbrush='".$_POST['bgbrush']."', text_color='".$_POST['text_color']."', fonts='".$_POST['fonts']."' WHERE products_id = '".(int)$products_id."'"); 
            }   else {             
                tep_db_query("INSERT INTO products_badge_settings SET bgcolor='".$_POST['bgcolor']."', bgbrush='".$_POST['bgbrush']."', text_color='".$_POST['text_color']."', fonts='".$_POST['fonts']."', products_id = '".(int)$products_id."'");
            }            
        } 
        /***********************/
		
        // Extra Fields
        if (isset($_POST['extra_field'])) { // Check to see if there are any need to update extra fields.
          $extra_fields_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " WHERE products_id = " . (int)$products_id);
          $extra_product_entry = array();
          while ($products_extra_fields = tep_db_fetch_array($extra_fields_query)) {
            $extra_product_entry[$products_extra_fields['products_extra_fields_id']] = $products_extra_fields['products_extra_fields_value'];
          }
          unset($extra_fields_query);
          foreach ($_POST['extra_field'] as $key => $val) {
            if (isset($extra_product_entry[$key])) { // an entry exists
              if ($val == '') {
                tep_db_query("DELETE FROM " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " where products_id = " . (int)$products_id . " AND  products_extra_fields_id = " . $key);
              } elseif ($val != $extra_product_entry[$key]) { 
                tep_db_query("UPDATE " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " SET products_extra_fields_value = '" . tep_db_input($val) . "' WHERE products_id = " . (int)$products_id . " AND  products_extra_fields_id = " . $key);
              }
            } else { // an entry does not exist
              if ($val != '') tep_db_query("INSERT INTO " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " (products_id, products_extra_fields_id, products_extra_fields_value) VALUES ('" . (int)$products_id . "', '" . $key . "', '" . tep_db_input($val) . "')");
            }
          }
        } // Check to see if there are any need to update extra fields.
       
        // Sub Products
        $prodidarray = isset($_POST['subproductid']) ? $_POST['subproductid'] : array();
        $namearray = isset($_POST['subprodname']) ? tep_db_prepare_input(tep_db_encoder($_POST['subprodname'])) : array();
        $modelarray = isset($_POST['subprodmodel']) ? tep_db_prepare_input(tep_db_encoder($_POST['subprodmodel'])) : array();
        $pricearray = isset($_POST['subprodprice']) ? $_POST['subprodprice'] : array();
        $weightarray = isset($_POST['subprodweight']) ? $_POST['subprodweight'] : array();
        $linesarray = isset($_POST['subprodmax_lines_count']) ? $_POST['subprodmax_lines_count'] : array();
        $imgsarray = isset($_POST['subprodmax_images_count']) ? $_POST['subprodmax_images_count'] : array();        
        $quantityarray = isset($_POST['subprodqty']) ? $_POST['subprodqty'] : array();
        $picturearray = isset($_FILES['subprodimage']) ? $_FILES['subprodimage'] : array();
        $prevarray = isset($_POST['subprodprevimage']) ? $_POST['subprodprevimage'] : array();
        $deletearray = isset($_POST['subproddelete']) ? $_POST['subproddelete'] : array();
        
        $parent_product_qty = 0;
        for ($i=0; $i < count($namearray); $i++) {
          // Go through each row that has a product name
          if ($namearray[$i] != '' && !isset($deletearray[$i])) {
            $sub_products_id = isset($prodidarray[$i]) ? (int)$prodidarray[$i] : 0;
            if ($sub_products_id > 0) { // this is an existing sub product
              $products_sub_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS . " WHERE products_id = " . $sub_products_id);
              $products_sub = tep_db_fetch_array($products_sub_query);
              
              $sql_data_array = array(); //declare the array and add to it anything changed
              $products_price = tep_db_prepare_input($pricearray[$i]);
              if ($products_price != $products_sub['products_price']) $sql_data_array['products_price'] = $products_price;
              $products_model = tep_db_prepare_input($modelarray[$i]);
              if ($products_model != $products_sub['products_model']) $sql_data_array['products_model'] = $products_model;
              $max_lines_count = tep_db_prepare_input($linesarray[$i]);
              if ($max_lines_count != $products_sub['max_lines_count']) $sql_data_array['max_lines_count'] = $max_lines_count;
              $max_images_count = tep_db_prepare_input($imgsarray[$i]);
              if ($max_images_count != $products_sub['max_images_count']) $sql_data_array['max_images_count'] = $max_images_count;              
              $products_quantity = isset($quantityarray[$i]) ? tep_db_prepare_input($quantityarray[$i]) : 0;
              $parent_product_qty += $products_quantity;
              if ($products_quantity != $products_sub['products_quantity']) $sql_data_array['products_quantity'] = $products_quantity;
              $products_weight = isset($weightarray[$i]) ? tep_db_prepare_input($weightarray[$i]) : 0;
              if ($products_weight != $products_sub['products_weight']) $sql_data_array['products_weight'] = $products_weight;
              
              if ($picturearray['name'][$i] != '' && $picturearray['name'][$i] != $products_sub['products_image']) {
                $sql_data_array['products_image'] = $picturearray['name'][$i];
                $uploadFile = DIR_FS_CATALOG_IMAGES . $picturearray['name'][$i];
                @move_uploaded_file($picturearray['tmp_name'][$i], $uploadFile);
              } elseif ($picturearray['name'][$i] == '' && $prevarray[$i] != '' && $prevarray[$i] != $products_sub['products_image']) {
                $sql_data_array['products_image'] = $prevarray[$i]; // the image could have been upload as part of the preview
              }
              
              // there are several fields that are needed from the parent product
              if ($products_date_available != $products_sub['products_date_available']) $sql_data_array['products_date_available'] = $products_date_available;
              if ($products_tax_class_id != $products_sub['products_tax_class_id']) $sql_data_array['products_tax_class_id'] = $products_tax_class_id;
              if ($manufacturers_id != $products_sub['manufacturers_id']) $sql_data_array['manufacturers_id'] = $manufacturers_id;
              
              if (count($sql_data_array) > 0) {
                $sql_data_array['products_last_modified'] = 'now()';
                tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', 'products_id = ' . (int)$sub_products_id);
              }
              unset($products_sub_query);
              unset($products_sub);
              
              $products_description_sub_query = tep_db_query("SELECT language_id, products_name
                                                              FROM " . TABLE_PRODUCTS_DESCRIPTION . "
                                                              WHERE products_id = " . $sub_products_id);
              $products_description_sub = array();
              while ($description_sub = tep_db_fetch_array($products_description_sub_query)) {
                $products_description_sub[$description_sub['language_id']] = array('products_name' => $description_sub['products_name']);
              }
              for ($l=0, $lc=sizeof($languages); $l<$lc; $l++) {
                $language_id = $languages[$l]['id'];
                $parent_name = isset($products_description_parent[$language_id]['products_name']) ? $products_description_parent[$language_id]['products_name'] : '';
                $new_sub_name = strstr($namearray[$i],$parent_name . ' - ') ? $namearray[$i] : $parent_name . ' - ' . $namearray[$i];
                if (isset($products_description_sub[$language_id])) {
                  if ($new_sub_name != $products_description_sub[$language_id]['product_name']) {
                    $sql_data_array = array('products_name' => tep_db_encoder($new_sub_name));
                    tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', 'products_id = ' . (int)$prodidarray[$i] . ' and language_id = ' . (int)$language_id);
                  }
                } else {
                  $sql_data_array = array('products_name' => tep_db_prepare_input(tep_db_encoder($new_sub_name)),
                                          'products_id' => (int)$prodidarray[$i],
                                          'language_id' => (int)$language_id
                                         );
                  tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
                }
              }
              unset($products_description_sub_query);
              unset($products_description_sub);
              
            } else { // this most be a new sub product
              $sql_data_array = array('products_price' => tep_db_prepare_input($pricearray[$i]),
                                      'products_model' => tep_db_prepare_input($modelarray[$i]),
                                      'max_lines_count' => tep_db_prepare_input($linesarray[$i]),
                                      'max_images_count' => tep_db_prepare_input($imgsarray[$i]),
                                      'products_quantity' => tep_db_prepare_input($quantityarray[$i]),
                                      'products_weight' => tep_db_prepare_input($weightarray[$i]),
                                      'products_date_available' => $products_date_available,
                                      'products_tax_class_id' => tep_db_prepare_input($_POST['products_tax_class_id']),
                                      'manufacturers_id' => (int)tep_db_prepare_input($_POST['manufacturers_id']),
                                      'products_parent_id' => $products_id,
                                      'products_date_added' => 'now()',
                                      'products_status' => 0);
              if ($picturearray['name'][$i] != '') {
                $sql_data_array['products_image'] = $picturearray['name'][$i];
                $uploadFile = DIR_FS_CATALOG_IMAGES . $picturearray['name'][$i];
                @move_uploaded_file($picturearray['tmp_name'][$i], $uploadFile);
              }
              
              tep_db_perform(TABLE_PRODUCTS, $sql_data_array);
              $sub_products_id = tep_db_insert_id();
              
               //-----------------Commented on 1st April 2010 --------------------
			  /*tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) (select " . $sub_products_id . ", categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = " . $products_id . ")");*/
			  //-----------------Comment ends  --------------------/ 
              
              for ($l=0, $lc=sizeof($languages); $l<$lc; $l++) {
                $language_id = $languages[$l]['id'];
                if (isset($products_description_parent[$language_id])) {
                  $new_sub_name = strstr($namearray[$i],$products_description_parent[$language_id]['products_name'] . ' - ') ? $namearray[$i] : $products_description_parent[$language_id]['products_name'] . ' - ' . $namearray[$i];
                  $sql_data_array = array('products_name' => $new_sub_name,
                                          'products_description' => $namearray[$i],
                                          'language_id' => (int)$language_id,
                                          'products_id' => $sub_products_id);
                  tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
                }
              }
            }
            
          } // End on if the sub prod name is set
        } // end of loop on the sub products name inoput field
        
        // Check for sub products to be deleted
        for ($i=0; $i < count($deletearray); $i++) {
          tep_db_query("DELETE FROM " . TABLE_PRODUCTS . " WHERE products_id = " . (int)$deletearray[$i]);
          tep_db_query("DELETE FROM " . TABLE_PRODUCTS_DESCRIPTION . " WHERE products_id = " . (int)$deletearray[$i]);
          tep_db_query("DELETE FROM " . TABLE_PRODUCTS_TO_CATEGORIES . " WHERE products_id = " . (int)$deletearray[$i]);
        }
        // End of Sub Products
      }
      
      // if has subproduct, update parent product's qty
      if ($parent_product_qty > 0) {
        tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET products_quantity = '" . $parent_product_qty . "' WHERE products_id = '" . $products_old['products_id'] . "'");
      }
        
      if (USE_CACHE == 'true') {
        tep_reset_cache_block('categories');
        tep_reset_cache_block('also_purchased');
      }
      
      
      if ($isSubProduct) {
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_old['products_parent_id']));
      } else {
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_old['products_id']));
      }
      break;
      
    case 'insert_product':
      if (isset($_POST[IMAGE_BACK]) || !isset($_POST['sort_order'])) {
        $action = 'new_product';
      } else {
        $languages = tep_get_languages();
        
        $products_date_available = tep_db_prepare_input($_POST['products_date_available']);
        $products_quantity = tep_db_prepare_input($_POST['products_quantity']);
		$products_min_order_qty = tep_db_prepare_input($_POST['products_min_order_qty']); //Mar 03 2011
		$products_type = tep_db_prepare_input($_POST['products_type']); //Nov 03 2012
		
		//Aug 01 2012
		$overall_quantity = tep_db_prepare_input($_POST['overall_quantity']); 
		$progressed_quantity = tep_db_prepare_input($_POST['progressed_quantity']);
		
		//Product costs
		$labour_cost = tep_db_prepare_input($_POST['labour_cost']); 
		$overhead_cost = tep_db_prepare_input($_POST['overhead_cost']); 
		$material_cost = tep_db_prepare_input($_POST['material_cost']);
		
        $products_model = tep_db_prepare_input(tep_db_encoder($_POST['products_model']));
        $products_price = tep_db_prepare_input($_POST['products_price']);
        $products_weight = isset($_POST['products_weight']) ? tep_db_prepare_input($_POST['products_weight']) : 0;
        $products_status = isset($_POST['products_status']) ? tep_db_prepare_input($_POST['products_status']) : 0;
        $products_tax_class_id = isset($_POST['products_tax_class_id']) ? tep_db_prepare_input($_POST['products_tax_class_id']) : 0;
        $manufacturers_id = isset($_POST['manufacturers_id']) ? tep_db_prepare_input($_POST['manufacturers_id']) : 0;
        $sort_order = isset($_POST['sort_order']) ? tep_db_prepare_input($_POST['sort_order']) : 0;
        $sql_data_array = array('products_date_available' => $products_date_available,
                                'products_quantity' => $products_quantity,
								'products_min_order_qty' => $products_min_order_qty, //Mar 03 2011
								'overall_quantity' => $overall_quantity, //Aug 01 2012
								'progressed_quantity' => $progressed_quantity, //Aug 01 2012
								'labour_cost' => $labour_cost,
								'overhead_cost' => $overhead_cost,
								'material_cost' => $material_cost,
                                'products_model' => $products_model,
                                // multi-vendor shipping
                                'vendors_prod_id' => tep_db_prepare_input($_POST['vendors_prod_id']),
                                'vendors_product_price' => tep_db_prepare_input($_POST['vendors_product_price']),
                                'vendors_id' => tep_db_prepare_input($_POST['vendors_id']),
                                'vendors_prod_comments' => tep_db_prepare_input($_POST['vendors_prod_comments']),
                                // multi-vendor shipping //eof
                                'products_price' => $products_price,
                                'products_weight' => $products_weight,
                                'products_status' => $products_status,
                                'products_tax_class_id' => $products_tax_class_id,
                                'manufacturers_id' => $manufacturers_id,
                                'sort_order' => $sort_order);
        


        $images = array(array('table' => 'products_image', 'delete' => 'delete_image', 'unlink' => 'unlink_image', 'dir' => 'products_image_destination'),
                        array('table' => 'products_image_med', 'delete' => 'delete_image_med', 'unlink' => 'unlink_image_med', 'dir' => 'products_image_med_destination'),
                        array('table' => 'products_image_lrg', 'delete' => 'delete_image_lrg', 'unlink' => 'unlink_image_lrg'),
                        array('table' => 'products_image_rls2', 'delete' => 'delete_image_rls2', 'unlink' => 'unlink_image_rls2'),
                        array('field' => 'products_image', 'path' => 'file_dir_exist_dest_small', 'file' => 'file_exist_dest_small'),
                        array('field' => 'products_image_med', 'path' => 'file_dir_exist_dest_med', 'file' => 'file_exist_dest_med'),
                        array('field' => 'products_image_lrg', 'path' => 'file_dir_exist_dest_lrg', 'file' => 'file_exist_dest_lrg'),
                        array('field' => 'products_image_rls2', 'path' => 'file_dir_exist_dest_rls2', 'file' => 'file_exist_dest_rls'),
                        array('table' => 'products_image_sm_1', 'delete' => 'delete_image_sm_1', 'unlink' => 'unlink_image_sm_1', 'dir' => 'products_image_sm_1_destination'),
                        array('table' => 'products_image_xl_1', 'delete' => 'delete_image_xl_1', 'unlink' => 'unlink_image_xl_1', 'dir' => 'products_image_xl_1_destination'),
                        array('table' => 'products_image_sm_2', 'delete' => 'delete_image_sm_2', 'unlink' => 'unlink_image_sm_2', 'dir' => 'products_image_sm_2_destination'),
                        array('table' => 'products_image_xl_2', 'delete' => 'delete_image_xl_2', 'unlink' => 'unlink_image_xl_2', 'dir' => 'products_image_xl_2_destination'),
                        array('table' => 'products_image_sm_3', 'delete' => 'delete_image_sm_3', 'unlink' => 'unlink_image_sm_3', 'dir' => 'products_image_sm_3_destination'),
                        array('table' => 'products_image_xl_3', 'delete' => 'delete_image_xl_3', 'unlink' => 'unlink_image_xl_3', 'dir' => 'products_image_xl_3_destination'),
                        array('table' => 'products_image_sm_4', 'delete' => 'delete_image_sm_4', 'unlink' => 'unlink_image_sm_4', 'dir' => 'products_image_sm_4_destination'),
                        array('table' => 'products_image_xl_4', 'delete' => 'delete_image_xl_4', 'unlink' => 'unlink_image_xl_4', 'dir' => 'products_image_xl_4_destination'),
                        array('table' => 'products_image_sm_5', 'delete' => 'delete_image_sm_5', 'unlink' => 'unlink_image_sm_5', 'dir' => 'products_image_sm_5_destination'),
                        array('table' => 'products_image_xl_5', 'delete' => 'delete_image_xl_5', 'unlink' => 'unlink_image_xl_5', 'dir' => 'products_image_xl_5_destination'),
                        array('table' => 'products_image_sm_6', 'delete' => 'delete_image_sm_6', 'unlink' => 'unlink_image_sm_6', 'dir' => 'products_image_sm_6_destination'),
                        array('table' => 'products_image_xl_6', 'delete' => 'delete_image_xl_6', 'unlink' => 'unlink_image_xl_6', 'dir' => 'products_image_xl_6_destination')
                       );
        foreach ($images as $image) {
          if (isset($_POST[$image['delete']]) && $_POST[$image['delete']] == 'yes' && $products_old[$image['table']] != '') {
            unlink(DIR_FS_CATALOG_IMAGES . $products_old[$image['table']]);
            $sql_data_array[$image['table']] = '';
          } elseif (isset($_POST[$image['unlink']]) && $_POST[$image['unlink']] == 'yes' && $products_old[$image['table']] != '') { 
            $sql_data_array[$image['table']] = '';
          } elseif (isset($_FILES[$image['table']]) && tep_not_null($_FILES[$image['table']]['name']) && strtolower($_FILES[$image['table']]['name']) != 'none') {
            $uploadFile = DIR_FS_CATALOG_IMAGES . urldecode($_POST[$image['dir']]) . $_FILES[$image['table']]['name'];
            @move_uploaded_file($_FILES[$image['table']]['tmp_name'], $uploadFile);
            $sql_data_array[$image['table']] = tep_db_prepare_input(urldecode($_POST[$image['dir']]) . $_FILES[$image['table']]['name']);
          } elseif(isset($_POST[$image['file']]) && tep_not_null($_POST[$image['file']])) {
            $sql_data_array[$image['field']] = tep_db_prepare_input(rawurldecode($_POST[$image['path']]) . rawurldecode($_POST[$image['file']]));
          } elseif (isset($_POST[$image['table']]) && tep_not_null($_POST[$image['table']]) && strtolower($_POST[$image['table']]) != 'none') {
            $sql_data_array[$image['table']] = tep_db_prepare_input($_POST[$image['table']]);
          }
        }
        
        $customergroupid = "";
        if (isset($_POST['products_group_access'])) {
          foreach ($_POST['products_group_access'] as $value) {
            $customergroupid .= $value . ',';
          }      
        }
        $customergroupid = substr($customergroupid,0,(strlen($customergroupid)-1));
        if ($products_old['products_group_access'] != $customergroupid) $sql_data_array['products_group_access'] = $customergroupid;
        $customergroupid = "";
        if (isset($_POST['products_nav_access'])) {
          foreach ($_POST['products_nav_access'] as $value) {
            $customergroupid .= $value . ',';
          }
        }
        $customergroupid = substr($customergroupid,0,(strlen($customergroupid)-1));
        if ($products_old['products_nav_access'] != $customergroupid) $sql_data_array['products_nav_access'] = $customergroupid;
        
        $pricing_array = tep_get_pricing();
        for ($i = 1; $i <= 11; ++$i) {
          if ($products_old['products_price'.$i] != $pricing_array[$i]['price']) $sql_data_array['products_price'.$i] = $pricing_array[$i]['price'];
          if ($products_old['products_price'.$i.'_qty'] != $pricing_array[$i]['qty']) $sql_data_array['products_price'.$i.'_qty'] = $pricing_array[$i]['qty'];
        }
        
        if (isset($_POST['products_history']) && $_POST['products_history'] > 0) {
          if ($_POST['products_history_qty'] == 'days') {
            $sql_data_array['products_qty_days'] = $_POST['products_history'];
            $sql_data_array['products_qty_years'] = 0;
          } else {
            $sql_data_array['products_qty_days'] = 0;
            $sql_data_array['products_qty_years'] = $_POST['products_history'];
          }
        }
      
        $sql_data_array['products_date_added'] = 'now()';
        tep_db_perform(TABLE_PRODUCTS, $sql_data_array);
        $products_id = tep_db_insert_id();
        
        tep_update_group_pricing($products_id);
        
        // process the products description table data
        $products_description_parent = array(); // save the name and description for later use in processing sub products
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $language_id = $languages[$i]['id'];
          //if ($_POST['products_name'][$language_id] != '') {
            $products_description_parent[$language_id] = array('products_name' => tep_db_prepare_input(tep_db_encoder($_POST['products_name'][$language_id])),
                                                               'products_description' => tep_db_prepare_input(tep_db_encoder($_POST['products_description'][$language_id]))
                                                              );
            $_POST['products_url'][$language_id] = urldecode($_POST['products_url'][$language_id]);
            if (substr($_POST['products_url'][$language_id], 0, 7) == 'http://') $_POST['products_url'][$language_id] = substr($_POST['products_url'][$language_id], 7);
            $sql_data_array = array('products_name' => tep_db_prepare_input(tep_db_encoder($_POST['products_name'][$language_id])),
                                    'products_blurb' => tep_db_prepare_input(tep_db_encoder($_POST['products_blurb'][$language_id])), 
                                    'products_description' => tep_db_prepare_input(tep_db_encoder($_POST['products_description'][$language_id])),
                                    'products_url' => tep_db_prepare_input($_POST['products_url'][$language_id]),
                                    'products_head_title_tag' => tep_db_prepare_input(tep_db_encoder($_POST['products_head_title_tag'][$language_id])),
                                    'products_head_desc_tag' => tep_db_prepare_input(tep_db_encoder($_POST['products_head_desc_tag'][$language_id])),
                                    'products_head_keywords_tag' => tep_db_prepare_input(tep_db_encoder($_POST['products_head_keywords_tag'][$language_id])),
                                    'products_id' => $products_id,
                                    'language_id' => $language_id
                                   );
            tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
         // }
        }
        
        // add it to the cirrent category
        tep_db_query("INSERT INTO " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) VALUES (" . (int)$products_id . ", " . (int)$current_category_id . ")");
        
		//insert badge settings if badge products
        if (isset($_POST['products_type']) && $_POST['products_type']==1) {            
            tep_db_query("INSERT INTO products_badge_settings SET products_id = '".(int)$products_id."', bgcolor='".$_POST['bgcolor']."', bgbrush='".$_POST['bgbrush']."', text_color='".$_POST['text_color']."', fonts='".$_POST['fonts']."'");            
        }        
        /***********************/
		
        // Extra Fields
        if (isset($_POST['extra_field'])) { // Check to see if there are any need to update extra fields.
          $extra_fields_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " WHERE products_id = " . (int)$products_id);
          $extra_product_entry = array();
          while ($products_extra_fields = tep_db_fetch_array($extra_fields_query)) {
            $extra_product_entry[$products_extra_fields['products_extra_fields_id']] = $products_extra_fields['products_extra_fields_value'];
          }
          unset($extra_fields_query);
          foreach (tep_db_prepare_input(tep_db_encoder($_POST['extra_field'])) as $key => $val) {
            if (isset($extra_product_entry[$key])) { // an entry exists
              if ($val == '') {
                tep_db_query("DELETE FROM " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " where products_id = " . (int)$products_id . " AND  products_extra_fields_id = " . $key);
              } elseif ($val != $extra_product_entry[$key]) { 
                tep_db_query("UPDATE " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " SET products_extra_fields_value = '" . tep_db_input($val) . "' WHERE products_id = " . (int)$products_id . " AND  products_extra_fields_id = " . $key);
              }
            } else { // an entry does not exist
              if ($val != '') tep_db_query("INSERT INTO " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " (products_id, products_extra_fields_id, products_extra_fields_value) VALUES ('" . (int)$products_id . "', '" . $key . "', '" . tep_db_input($val) . "')");
            }
          }
        } // Check to see if there are any need to update extra fields.
        
        // Sub Products
        $prodidarray = isset($_POST['subproductid']) ? $_POST['subproductid'] : array();
        $namearray = isset($_POST['subprodname']) ? tep_db_prepare_input(tep_db_encoder($_POST['subprodname'])) : array();
        $modelarray = isset($_POST['subprodmodel']) ? tep_db_prepare_input(tep_db_encoder($_POST['subprodmodel'])) : array();
        $pricearray = isset($_POST['subprodprice']) ? $_POST['subprodprice'] : array();
        $weightarray = isset($_POST['subprodweight']) ? $_POST['subprodweight'] : array();
        $quantityarray = isset($_POST['subprodqty']) ? $_POST['subprodqty'] : array();
        $picturearray = isset($_FILES['subprodimage']) ? $_FILES['subprodimage'] : array();
        $prevarray = isset($_POST['subprodprevimage']) ? $_POST['subprodprevimage'] : array();
        $deletearray = isset($_POST['subproddelete']) ? $_POST['subproddelete'] : array();
        
        $parent_product_qty = 0;
        for ($i=0; $i < count($namearray); $i++) {
          // Go through each row that has a product name
          if ($namearray[$i] != '') {
            $parent_product_qty += $quantityarray[$i];
            $sql_data_array = array('products_price' => tep_db_prepare_input($pricearray[$i]),
                                    'products_model' => tep_db_prepare_input($modelarray[$i]),
                                    'products_quantity' => tep_db_prepare_input($quantityarray[$i]),
                                    'products_weight' => tep_db_prepare_input($weightarray[$i]),
                                    'products_date_available' => $products_date_available,
                                    'products_tax_class_id' => tep_db_prepare_input($_POST['products_tax_class_id']),
                                    'manufacturers_id' => (int)tep_db_prepare_input($_POST['manufacturers_id']),
                                    'products_parent_id' => $products_id,
                                    'products_date_added' => 'now()',
                                    'products_status' => 0);
            if ($picturearray['name'][$i] != '') {
              $sql_data_array['products_image'] = $picturearray['name'][$i];
              $uploadFile = DIR_FS_CATALOG_IMAGES . $picturearray['name'][$i];
              @move_uploaded_file($picturearray['tmp_name'][$i], $uploadFile);
            } elseif ($picturearray['name'][$i] == '' && $prevarray[$i] != '') {
              $sql_data_array['products_image'] = $prevarray[$i]; // the image could have been upload as part of the preview
            }
            
            tep_db_perform(TABLE_PRODUCTS, $sql_data_array);
            $sub_products_id = tep_db_insert_id();
            
            for ($l=0, $lc=sizeof($languages); $l<$lc; $l++) {
              $language_id = $languages[$l]['id'];
              $parent_name = isset($products_description_parent[$language_id]['products_name']) ? $products_description_parent[$language_id]['products_name'] : '';
              $new_sub_name = strstr($namearray[$i],$parent_name . ' - ') ? $namearray[$i] : $parent_name . ' - ' . $namearray[$i];
              $sql_data_array = array('products_name' => tep_db_prepare_input(tep_db_encoder($new_sub_name)),
                                      'products_description' => tep_db_prepare_input(tep_db_encoder($namearray[$i])),
                                      'language_id' => (int)$language_id,
                                      'products_id' => (int)$sub_products_id);
              tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
            }
            
            //-----------------Commented on 1st April 2010 --------------------/ 
			// add it to the current category
            /*tep_db_query("INSERT INTO " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) VALUES (" . (int)$sub_products_id . ", " . (int)$current_category_id . ")");*/
			//-----------------Comment ends  --------------------/ 
            
          } // End on if the sub prod name is set
        } // end of loop on the sub products name inoput field
        // End of Sub Products
        
        // if has subproduct, update parent product's qty
        if ($parent_product_qty > 0) {
          tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET products_quantity = '" . $parent_product_qty . "' WHERE products_id = '" . $products_id . "'");
        }
        
        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }
       

        // RCI code start
        echo $cre_RCI->get('categories', 'insertproduct', false); 
        // RCI code eof
        
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id));
      }
      break;
      
    case 'copy_to_confirm':
      if (isset($_POST['products_id']) && isset($_POST['categories_id'])) {
        $products_id = tep_db_prepare_input($_POST['products_id']);
        $categories_id = tep_db_prepare_input($_POST['categories_id']);
        if ($_POST['copy_as'] == 'link') {
          if ($categories_id != $current_category_id) {
            $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$categories_id . "'");
            $check = tep_db_fetch_array($check_query);
            if ($check['total'] < '1') {
              tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$categories_id . "')");
            }
            $sub_products_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_parent_id = '" . (int)$products_id . "'");
            while ($sub_products = tep_db_fetch_array($sub_products_query)) {
              $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$sub_products['products_id'] . "' and categories_id = '" . (int)$categories_id . "'");
              $check = tep_db_fetch_array($check_query);
              if ($check['total'] < '1') {
                tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$sub_products['products_id'] . "', '" . (int)$categories_id . "')");
              }
            }
          } else {
            $messageStack->add_session('search', ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
          }
        } elseif ($_POST['copy_as'] == 'duplicate') {
		  //Mar 03 2011 - modified to add products_min_order_qty entry
          $product_query = tep_db_query("SELECT products_quantity, products_min_order_qty, progressed_quantity, overall_quantity, labour_cost, overhead_cost, material_cost, products_model, vendors_prod_id, products_image, products_image_med, products_image_lrg, products_image_rls2, products_image_sm_1, products_image_xl_1, products_image_sm_2, products_image_xl_2, products_image_sm_3, products_image_xl_3, products_image_sm_4, products_image_xl_4, products_image_sm_5, products_image_xl_5, products_image_sm_6, products_image_xl_6, products_price, vendors_product_price, vendors_prod_comments, products_price1, products_price2, products_price3, products_price4, products_price5, products_price6, products_price7, products_price8, products_price9, products_price10, products_price11, products_price1_qty, products_price2_qty, products_price3_qty, products_price4_qty, products_price5_qty, products_price6_qty, products_price7_qty, products_price8_qty, products_price9_qty, products_price10_qty, products_price11_qty, products_qty_blocks, products_date_available, products_weight, products_tax_class_id, manufacturers_id, products_group_access, products_nav_access, sort_order, products_qty_days, products_qty_years  
                                           from " . TABLE_PRODUCTS . " 
                                         WHERE products_id = '" . (int)$products_id . "'");
          $product = tep_db_fetch_array($product_query);
		  
		  //Mar 03 2011 - modified to add products_min_order_qty entry
          tep_db_query("insert into " . TABLE_PRODUCTS . " (products_quantity, products_min_order_qty, progressed_quantity, overall_quantity, labour_cost, overhead_cost, material_cost, products_model, vendors_prod_id, vendors_product_price, vendors_prod_comments,vendors_id, products_image, products_image_med, products_image_lrg, products_image_rls2, products_image_sm_1, products_image_xl_1, products_image_sm_2, products_image_xl_2, products_image_sm_3, products_image_xl_3, products_image_sm_4, products_image_xl_4, products_image_sm_5, products_image_xl_5, products_image_sm_6, products_image_xl_6, products_price, products_price1, products_price2, products_price3, products_price4, products_price5, products_price6, products_price7, products_price8, products_price9, products_price10, products_price11, products_price1_qty, products_price2_qty, products_price3_qty, products_price4_qty, products_price5_qty, products_price6_qty, products_price7_qty, products_price8_qty, products_price9_qty, products_price10_qty, products_price11_qty, products_qty_blocks, products_date_added, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_group_access, products_nav_access, sort_order, products_qty_days, products_qty_years) values
                      ('" . tep_db_input($product['products_quantity']) . "', '" . tep_db_input($product['products_min_order_qty']) . "', '" . tep_db_input($product['progressed_quantity']) . "', '" . tep_db_input($product['overall_quantity']) . "', '" . tep_db_input($product['labour_cost']) . "','" . tep_db_input($product['overhead_cost']) . "','" . tep_db_input($product['material_cost']) . "', '" . tep_db_input($product['products_model']) . "', '".tep_db_input($product['vendors_prod_id']) . "', '" . tep_db_input($product['vendors_product_price']) . "', '" . tep_db_input($product['vendors_prod_comments']) . "', '" . (int)$product['vendors_id'] . "', '" . tep_db_input($product['products_image']) . "', '" . tep_db_input($product['products_image_med']) . "', '" . tep_db_input($product['products_image_lrg']) . "', '" . tep_db_input($product['products_image_rls2']) . "', '" . tep_db_input($product['products_image_sm_1']) . "', '" . tep_db_input($product['products_image_xl_1']) . "', '" . tep_db_input($product['products_image_sm_2']) . "', '" . tep_db_input($product['products_image_xl_2']) . "', '" . tep_db_input($product['products_image_sm_3']) . "', '" . tep_db_input($product['products_image_xl_3']) . "', '" . tep_db_input($product['products_image_sm_4']) . "', '" . tep_db_input($product['products_image_xl_4']) . "', '" . tep_db_input($product['products_image_sm_5']) . "', '" . tep_db_input($product['products_image_xl_5']) . "', '" . tep_db_input($product['products_image_sm_6']) . "', '" . tep_db_input($product['products_image_xl_6']) . "', '" . tep_db_input($product['products_price']) . "',
                      '" . tep_db_input($product['products_price1']) . "', '" . tep_db_input($product['products_price2']) . "', '" . tep_db_input($product['products_price3']) . "', '" . tep_db_input($product['products_price4']) . "', '" . tep_db_input($product['products_price5']) . "', '" . tep_db_input($product['products_price6']) . "', '" . tep_db_input($product['products_price7']) . "', '" . tep_db_input($product['products_price8']) . "', '" . tep_db_input($product['products_price9']) . "', '" . tep_db_input($product['products_price10']) . "', '" . tep_db_input($product['products_price11']) . "', '" . tep_db_input($product['products_price1_qty']) . "', '" . tep_db_input($product['products_price2_qty']) . "', '" . tep_db_input($product['products_price3_qty']) . "', '" . tep_db_input($product['products_price4_qty']) . "', '" . tep_db_input($product['products_price5_qty']) . "', '" . tep_db_input($product['products_price6_qty']) . "', '" . tep_db_input($product['products_price7_qty']) . "', '" . tep_db_input($product['products_price8_qty']) . "', '" . tep_db_input($product['products_price9_qty']) . "', '" . tep_db_input($product['products_price10_qty']) . "', '" . tep_db_input($product['products_price11_qty']) . "', '" . tep_db_input($product['products_qty_blocks']) . "',
                      now(), '" . tep_db_input($product['products_date_available']) . "', '" . tep_db_input($product['products_weight']) . "', '0', '" . (int)$product['products_tax_class_id'] . "', '" . (int)$product['manufacturers_id'] . "', '" . tep_db_input($product['products_group_access']) . "', '" . tep_db_input($product['products_nav_access']) . "', '" . tep_db_input($product['sort_order']) . "', '" . tep_db_input($product['products_qty_days']) . "', '" . tep_db_input($product['products_qty_years']) . "')");
          $dup_products_id = tep_db_insert_id();
          $description_query = tep_db_query("select language_id, products_name, products_blurb, products_description, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag, products_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "'");
          while ($description = tep_db_fetch_array($description_query)) {
            tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_blurb, products_description, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag, products_url, products_viewed) values ('" . (int)$dup_products_id . "', '" . (int)$description['language_id'] . "', '" . tep_db_input($description['products_name']) . "', '" . tep_db_input($description['products_blurb']) . "', '" . tep_db_input($description['products_description']) . "', '" . tep_db_input($description['products_head_title_tag']) . "', '" . tep_db_input($description['products_head_desc_tag']) . "', '" . tep_db_input($description['products_head_keywords_tag']) . "', '" . tep_db_input($description['products_url']) . "', '0')");
          }
          tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$dup_products_id . "', '" . (int)$categories_id . "')");
          $products_id_from=tep_db_input($products_id);
          $products_id_to= $dup_products_id;
          $products_id = $dup_products_id;
          if ( $_POST['copy_attributes']=='copy_attributes_yes' and $_POST['copy_as'] == 'duplicate' ) {
            $copy_attributes_delete_first='1';
            $copy_attributes_duplicates_skipped='1';
            $copy_attributes_duplicates_overwrite='0';
            if (DOWNLOAD_ENABLED == 'true') {
              $copy_attributes_include_downloads='1';
              $copy_attributes_include_filename='1';
            } else {
              $copy_attributes_include_downloads='0';
              $copy_attributes_include_filename='0';
            }
            tep_copy_products_attributes($products_id_from,$products_id_to);
          }
          $products_id1 = tep_db_prepare_input($_POST['products_id']);
          $sub_products_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_parent_id = '" . (int)$products_id1 . "'");
          while ($sub_products = tep_db_fetch_array($sub_products_query)) {
            $product_query = tep_db_query("select products_quantity, products_min_order_qty, progressed_quantity, overall_quantity, labour_cost, overhead_cost, material_cost, products_model, products_image, products_image_med, products_image_lrg, products_image_rls2, products_image_sm_1, products_image_xl_1, products_image_sm_2, products_image_xl_2, products_image_sm_3, products_image_xl_3, products_image_sm_4, products_image_xl_4, products_image_sm_5, products_image_xl_5, products_image_sm_6, products_image_xl_6, products_price, products_price1, products_price2, products_price3, products_price4, products_price5, products_price6, products_price7, products_price8, products_price9, products_price10, products_price11, products_price1_qty, products_price2_qty, products_price3_qty, products_price4_qty, products_price5_qty, products_price6_qty, products_price7_qty, products_price8_qty, products_price9_qty, products_price10_qty, products_price11_qty, products_qty_blocks, products_date_available, products_weight, products_tax_class_id, manufacturers_id, sort_order, products_qty_days, products_qty_years from " . TABLE_PRODUCTS . " where products_id = '" . (int)$sub_products['products_id'] . "'");
            $product = tep_db_fetch_array($product_query);
            tep_db_query("insert into " . TABLE_PRODUCTS . " (products_quantity, products_min_order_qty, progressed_quantity, overall_quantity, labour_cost, overhead_cost, material_cost, products_model, products_image, products_image_med, products_image_lrg, products_image_rls2, products_image_sm_1, products_image_xl_1, products_image_sm_2, products_image_xl_2, products_image_sm_3, products_image_xl_3, products_image_sm_4, products_image_xl_4, products_image_sm_5, products_image_xl_5, products_image_sm_6, products_image_xl_6, products_price, products_price1, products_price2, products_price3, products_price4, products_price5, products_price6, products_price7, products_price8, products_price9, products_price10, products_price11, products_price1_qty, products_price2_qty, products_price3_qty, products_price4_qty, products_price5_qty, products_price6_qty, products_price7_qty, products_price8_qty, products_price9_qty, products_price10_qty, products_price11_qty, products_qty_blocks, products_date_added, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_parent_id, sort_order, products_qty_days, products_qty_years) values
                        ('" . tep_db_input($product['products_quantity']) . "', '" . tep_db_input($product['products_min_order_qty']) . "', '" . tep_db_input($product['progressed_quantity']) . "', '" . tep_db_input($product['overall_quantity']) . "', '" . tep_db_input($product['labour_cost']) . "', '" . tep_db_input($product['overhead_cost']) . "', '" . tep_db_input($product['material_cost']) . "', '" . tep_db_input($product['products_model']) . "', '" . tep_db_input($product['products_image']) . "', '" . tep_db_input($product['products_image_med']) . "', '" . tep_db_input($product['products_image_lrg']) . "', '" . tep_db_input($product['products_image_rls2']) . "', '" . tep_db_input($product['products_image_sm_1']) . "', '" . tep_db_input($product['products_image_xl_1']) . "', '" . tep_db_input($product['products_image_sm_2']) . "', '" . tep_db_input($product['products_image_xl_2']) . "', '" . tep_db_input($product['products_image_sm_3']) . "', '" . tep_db_input($product['products_image_xl_3']) . "', '" . tep_db_input($product['products_image_sm_4']) . "', '" . tep_db_input($product['products_image_xl_4']) . "', '" . tep_db_input($product['products_image_sm_5']) . "', '" . tep_db_input($product['products_image_xl_5']) . "', '" . tep_db_input($product['products_image_sm_6']) . "', '" . tep_db_input($product['products_image_xl_6']) . "', '" . tep_db_input($product['products_price']) . "',
                        '" . tep_db_input($product['products_price1']) . "', '" . tep_db_input($product['products_price2']) . "', '" . tep_db_input($product['products_price3']) . "', '" . tep_db_input($product['products_price4']) . "', '" . tep_db_input($product['products_price5']) . "', '" . tep_db_input($product['products_price6']) . "', '" . tep_db_input($product['products_price7']) . "', '" . tep_db_input($product['products_price8']) . "', '" . tep_db_input($product['products_price9']) . "', '" . tep_db_input($product['products_price10']) . "', '" . tep_db_input($product['products_price11']) . "', '" . tep_db_input($product['products_price1_qty']) . "', '" . tep_db_input($product['products_price2_qty']) . "', '" . tep_db_input($product['products_price3_qty']) . "', '" . tep_db_input($product['products_price4_qty']) . "', '" . tep_db_input($product['products_price5_qty']) . "', '" . tep_db_input($product['products_price6_qty']) . "', '" . tep_db_input($product['products_price7_qty']) . "', '" . tep_db_input($product['products_price8_qty']) . "', '" . tep_db_input($product['products_price9_qty']) . "', '" . tep_db_input($product['products_price10_qty']) . "', '" . tep_db_input($product['products_price11_qty']) . "', '" . tep_db_input($product['products_qty_blocks']) . "',
                         now(), '" . tep_db_input($product['products_date_available']) . "', '" . tep_db_input($product['products_weight']) . "', '0', '" . (int)$product['products_tax_class_id'] . "', '" . (int)$product['manufacturers_id'] . "', '" . $dup_products_id . "', '" . (int)$product['sort_order'] . "', '" . tep_db_input($product['products_qty_days']) . "', '" . tep_db_input($product['products_qty_years']) . "')");                           
            $dup_products_id1 = tep_db_insert_id();
            $description_query = tep_db_query("select language_id, products_name, products_blurb, products_description, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag, products_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$sub_products['products_id'] . "'");
            while ($description = tep_db_fetch_array($description_query)) {
              tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_blurb, products_description, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag, products_url, products_viewed) values ('" . (int)$dup_products_id1 . "', '" . (int)$description['language_id'] . "', '" . tep_db_input($description['products_name']) . "', '" . tep_db_input($description['products_blurb']) . "', '" . tep_db_input($description['products_description']) . "', '" . tep_db_input($description['products_head_title_tag']) . "', '" . tep_db_input($description['products_head_desc_tag']) . "', '" . tep_db_input($description['products_head_keywords_tag']) . "', '" . tep_db_input($description['products_url']) . "', '0')");
            }
            tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$dup_products_id1 . "', '" . (int)$categories_id . "')");
            $products_id_from=tep_db_input($sub_products['products_id']);
            $products_id_to= $dup_products_id1;
            if ( $_POST['copy_attributes']=='copy_attributes_yes' and $_POST['copy_as'] == 'duplicate' ) {
              $copy_attributes_delete_first='1';
              $copy_attributes_duplicates_skipped='1';
              $copy_attributes_duplicates_overwrite='0';
              if (DOWNLOAD_ENABLED == 'true') {
                $copy_attributes_include_downloads='1';
                $copy_attributes_include_filename='1';
              } else {
                $copy_attributes_include_downloads='0';
                $copy_attributes_include_filename='0';
              }
              tep_copy_products_attributes($products_id_from,$products_id_to);
            }
          }
        }
        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }
       tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $categories_id . '&pID=' . $products_id));
     }
      break;
      
    case 'new_product_preview':
      $images = array(array('table' => 'products_image',      'delete' => 'delete_image',      'unlink' => 'unlink_image'),
                      array('table' => 'products_image_med',  'delete' => 'delete_image_med',  'unlink' => 'unlink_image_med'),
                      array('table' => 'products_image_lrg',  'delete' => 'delete_image_lrg',  'unlink' => 'unlink_image_lrg'),
                      array('table' => 'products_image_rls2', 'delete' => 'delete_image_rls2', 'unlink' => 'unlink_image_rls2'),
                      array('table' => 'products_image_sm_1', 'delete' => 'delete_image_sm_1', 'unlink' => 'unlink_image_sm_1'),
                      array('table' => 'products_image_xl_1', 'delete' => 'delete_image_xl_1', 'unlink' => 'unlink_image_xl_1'),
                      array('table' => 'products_image_sm_2', 'delete' => 'delete_image_sm_2', 'unlink' => 'unlink_image_sm_2'),
                      array('table' => 'products_image_xl_2', 'delete' => 'delete_image_xl_2', 'unlink' => 'unlink_image_xl_2'),
                      array('table' => 'products_image_sm_3', 'delete' => 'delete_image_sm_3', 'unlink' => 'unlink_image_sm_3'),
                      array('table' => 'products_image_xl_3', 'delete' => 'delete_image_xl_3', 'unlink' => 'unlink_image_xl_3'),
                      array('table' => 'products_image_sm_4', 'delete' => 'delete_image_sm_4', 'unlink' => 'unlink_image_sm_4'),
                      array('table' => 'products_image_xl_4', 'delete' => 'delete_image_xl_4', 'unlink' => 'unlink_image_xl_4'),
                      array('table' => 'products_image_sm_5', 'delete' => 'delete_image_sm_5', 'unlink' => 'unlink_image_sm_5'),
                      array('table' => 'products_image_xl_5', 'delete' => 'delete_image_xl_5', 'unlink' => 'unlink_image_xl_5'),
                      array('table' => 'products_image_sm_6', 'delete' => 'delete_image_sm_6', 'unlink' => 'unlink_image_sm_6'),
                      array('table' => 'products_image_xl_6', 'delete' => 'delete_image_xl_6', 'unlink' => 'unlink_image_xl_6')
                     );
      foreach ($images as $image) {
        $dir_dest = $_POST[$image['table'].'_destination'];
        $dir_dest = str_ireplace( "%2F", "/", $dir_dest);
        ${$image['table'].'_name'} = '';
        if (isset($_FILES[$image['table']]) && tep_not_null($_FILES[$image['table']]['name']) && strtolower($_FILES[$image['table']]['name']) != 'none') {
          $image_upload = new upload($image['table']);
          $image_upload->set_destination(DIR_FS_CATALOG_IMAGES . $dir_dest);
          if ($image_upload->parse() && $image_upload->save()) {
            ${$image['table'].'_name'} = $image_upload->filename;
          } else {
            ${$image['table'].'_name'} = (isset($_POST[$image['table'].'_previous']) ? $_POST[$image['table'].'_previous'] : '');
          }
        }
      }
      
      $picturearray = (isset($_FILES['subprodimage']) ? $_FILES['subprodimage'] : array());
      $namearray = (isset($_POST['subprodname']) ? $_POST['subprodname'] : array());
      $new_sub_prod_image = array();
      $i = 0;
      for ($i=0; $i < count($namearray); $i++) {
        if ($namearray[$i] != '') {

          foreach($picturearray['name'] as $xkey=>$xval) {
            if ($xval != '') {
              if ($picturearray['name'][$xkey] != '') {
                $new_sub_prod_image[$xkey] = $picturearray['name'][$xkey];
                $_POST['subprodprevimage'][$xkey] = $picturearray['name'][$xkey];
                $uploadFile = DIR_FS_CATALOG_IMAGES . $picturearray['name'][$xkey];
                if (move_uploaded_file($picturearray['tmp_name'][$xkey], $uploadFile) && $xval != "") {
                  $messageStack->add('search', SUCCESS_FILE_SAVED_SUCCESSFULLY, 'success');
                } else if($xval != "") {
                  $messageStack->add('search', ERROR_FILE_NOT_SAVED, 'error');
                }
              }
            }
          }
          break;
        }
      }
      break;
  } // end switch($action)
} // end tep_not_null($action)
// check if the catalog image directory exists
if (is_dir(DIR_FS_CATALOG_IMAGES)) {
  if (!is_writeable(DIR_FS_CATALOG_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
} else {
  $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
}
switch (true) {
  case (CATEGORIES_SORT_ORDER=="products_name"):
    $order_it_by = "pd.products_name";
    break;
  case (CATEGORIES_SORT_ORDER=="products_name-desc"):
    $order_it_by = "pd.products_name DESC";
    break;
  case (CATEGORIES_SORT_ORDER=="model"):
    $order_it_by = "p.products_model";
    break;
  case (CATEGORIES_SORT_ORDER=="model-desc"):
    $order_it_by = "p.products_model DESC";
    break;
  default:
    $order_it_by = "pd.products_name";
    break;
}
$go_back_to=$_SERVER["REQUEST_URI"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script type="text/javascript" src="includes/general.js"></script>
<!-- Tabs code -->
<script type="text/javascript" src="includes/javascript/tabpane/local/webfxlayout.js"></script>
<link type="text/css" rel="stylesheet" href="includes/javascript/tabpane/tab.webfx.css">
<style type="text/css">
.dynamic-tab-pane-control h2 {
  text-align: center;
  width:    auto;
}

.dynamic-tab-pane-control h2 a {
  display:  inline;
  width:    auto;
}

.dynamic-tab-pane-control a:hover {
  background: transparent;
}
</style>
<script type="text/javascript" src="includes/javascript/tabpane/tabpane.js"></script>
<!-- End Tabs -->
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script type="text/javascript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="javascript"><!--
function checkUncheckAll(theElement, obj) {
  var theForm = theElement.form, z = 1;
  for (z=1; z < theForm.length; z++) {
    if (theForm[z].type == 'checkbox' && theForm[z].name == obj) {
      if (theElement.checked) {
        theForm[z].checked = true;
      } else{
        theForm[z].checked = false;
      }
    }
  }
}
function retailCheck(theElement, obj) {
  var theForm = theElement.form, z = 1;
  for (z=1; z < theForm.length; z++) {
    if (theForm[z].type == 'checkbox' && theForm[z].name == obj && theForm[z].value == 'G') {
      if (theElement.checked) {
        theForm[z].disabled = false;
      } else{
        theForm[z].checked = false;
        theForm[z].disabled = true;
      }
      return;
    }
  }
}

//--></script>
<?php
include_once(DIR_WS_INCLUDES . 'javascript/' . 'webmakers_added_js.php')
?>

<script type="text/javascript" src="includes/javascript/jquery.js"></script>

<script type="text/javascript">
<!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
function trim(str) {
  return str.replace(/^\s+|\s+$/g,"");
}

function getHTTPObject() {
  var xmlhttp;
  /*@cc_on
  @if (@_jscript_version >= 5)
    try {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (E) {
        xmlhttp = false;
      }
    }
  @else
  xmlhttp = false;
  @end @*/
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
    try {
      xmlhttp = new XMLHttpRequest();
    } catch (e) {
      xmlhttp = false;
    }
  }
  return xmlhttp;
}

var img_file;

function handleHttpResponse() {
  if (http.readyState == 4) {
    results = http.responseText;
    obj = document.getElementById(img_file);
    obj.innerHTML = results;
  }
}
<?php
if ($request_type == 'SSL') {
?>
var url = "<?php echo HTTPS_SERVER; ?>";
<?php
} else {
?>
var url = "<?php echo HTTP_SERVER; ?>";
<?php
}
?>
function previewFile(obj1, obj2, obj3, obj) {
  pathobj = document.getElementById(obj);
  path = unescape(pathobj.value);
  if (path == '/') {
    path = '';
  }
  imgurl = url + '<?php echo DIR_WS_CATALOG; ?>' + 'images/' + path + obj1.value;
  img = document.getElementById(obj2);
  img.src = imgurl;
  fileobj = document.getElementById(obj3);
  fileobj.value = obj1.value;
}

function listFile(obj, obj2, obj3, obj4) {
  img_file = obj3;
  listurl = url + '<?php echo DIR_WS_ADMIN; ?>' + 'get_file_list.php?';
<?php
if (isset($_GET[tep_session_name()])) {
?>
  listurl += '<?php echo tep_session_name() . '=' . $_GET[tep_session_name()] . '&'; ?>'
<?php
}
?>
  listurl += 'dir=' + escape(obj.value) + '&field=' + obj2 + '&field2=' + obj4 + '&field3=' + obj.name;
  http.open("GET", listurl, true);
  http.onreadystatechange = handleHttpResponse;
  http.send(null);
  document.getElementById(obj2).src = '';
}

var http = getHTTPObject();
//-->
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
    <?php
    if(defined('BOX_WIDTH')){
      $boxwidth = BOX_WIDTH ;
    } else {
      $boxwidth = 150 ;
    }
    ?>
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td class="page-container" valign="top">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <?php
        $manage_image = new DirSelect($ImageLocations);
        $image_dir = $manage_image->getDirs();
        $image_file = $manage_image->getFiles('');
        $file_dir = '<option value="">/</option>';
        foreach($image_dir as $relative => $fullpath) {
          if (substr($relative, -1) == '/'){
            $relative = substr($relative, 1);
            $file_dir .= '<option value="' . rawurlencode($relative) . '">' . $relative . '</option>';
          }
        }
        $file_list = '<option value=""> -- None -- </option>';
        foreach ($image_file[1] as $relative => $fullpath) {
          $file_list .= '<option value="' . rawurlencode($relative) . '">' . $relative . '</option>';
        }
        if ( isset($action) && ($action == 'new_category' || $action == 'edit_category') ) {
          if ( ($cID) && (!$_POST) ) {
            $categories_query = tep_db_query("select c.categories_id, cd.categories_name, cd.categories_heading_title, cd.categories_description, cd.categories_head_title_tag, cd.categories_head_desc_tag, cd.categories_head_keywords_tag, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified,products_group_access, c.products_nav_access from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $cID . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by c.sort_order, cd.categories_name");
            $category = tep_db_fetch_array($categories_query);
            $cInfo = new objectInfo($category);
          } elseif ($_POST) {
            $cInfo = new objectInfo($_POST);
            $categories_name = (isset($_POST['categories_name']) ? $_POST['categories_name'] : '' );
            $categories_heading_title = (isset($_POST['categories_heading_title']) ? $_POST['categories_heading_title'] : '' );
            $categories_description = (isset($_POST['categories_description']) ? $_POST['categories_description'] : '' );
            $categories_head_title_tag = (isset($_POST['categories_head_title_tag']) ? $_POST['categories_head_title_tag'] : '' );
            $categories_head_desc_tag = (isset($_POST['categories_head_desc_tag']) ? $_POST['categories_head_desc_tag'] : '' );
            $categories_head_keywords_tag = (isset($_POST['categories_head_keywords_tag']) ? $_POST['categories_head_keywords_tag'] : '' );
            $categories_url = (isset($_POST['categories_url']) ? $_POST['categories_url'] : '' );
            $categories_image = (isset($_POST['categories_image']) ? $_POST['categories_image'] : '' );
          } else {
            $cInfo = new objectInfo(array());
            $cInfo ->categories_name = (isset($cInfo ->categories_name) ? $cInfo ->categories_name : '' );
            $cInfo ->categories_heading_title = (isset($_POST['categories_heading_title']) ? $_POST['categories_heading_title'] : '' );
            $cInfo ->categories_description = (isset($_POST['categories_description']) ? $_POST['categories_description'] : '' );
            $cInfo ->categories_head_title_tag = (isset($_POST['categories_head_title_tag']) ? $_POST['categories_head_title_tag'] : '' );
            $cInfo ->categories_head_desc_tag = (isset($_POST['categories_head_desc_tag']) ? $_POST['categories_head_desc_tag'] : '' );
            $cInfo ->categories_head_keywords_tag = (isset($_POST['categories_head_keywords_tag']) ? $_POST['categories_head_keywords_tag'] : '' );
            $cInfo ->categories_url = (isset($_POST['categories_url']) ? $_POST['categories_url'] : '' );
            $cInfo ->categories_image = (isset($cInfo ->categories_image) ? $cInfo ->categories_image : '' );
            $cInfo ->sort_order = (isset($cInfo ->sort_order) ? $cInfo ->sort_order : '' );
            $cInfo ->products_group_access = (isset($cInfo ->products_group_access) ? $cInfo ->products_group_access : '' );
            $cInfo->products_nav_access = (isset($cInfo ->products_nav_access) ? $cInfo ->products_nav_access : '' );
          }
          $languages = tep_get_languages();
          $text_new_or_edit = ($action=='new_category') ? TEXT_INFO_HEADING_NEW_CATEGORY : TEXT_INFO_HEADING_EDIT_CATEGORY;
          // editor functions
          echo tep_load_html_editor();
          $category_elements = '';
          for ($i=0; $i<sizeof($languages); $i++) {
            $category_elements .= 'categories_description[' . $languages[$i]['id'] . '],';
          }
          echo tep_insert_html_editor($category_elements);
          // editor functions eof
          // RCI start
          echo $cre_RCI->get('categories', 'cedittop');
          // RCI eof
          ?>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>
          <tr>
            <td class="pageHeading">
              <?php echo sprintf($text_new_or_edit, tep_output_generated_category_path($current_category_id)); ?> 
            </td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>          
          <?php
          // RCO start fieldsetcinfo
          if ($cre_RCO->get('categories', 'fieldsetcinfo') !== true) {  
            ?>          
            <tr>
              <?php echo tep_draw_form('new_category', FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cID . '&action=new_category_preview', 'post', 'enctype="multipart/form-data"'); ?>
              <td><fieldset style="display:none;"></fieldset><fieldset><legend><?php echo TEXT_GENERAL_OPTIONS; ?></legend>
                <table width="100%" border="0" cellspacing="3" cellpadding="3" align="center" summary="category image and sort order">
                  <tr valign="top">
                    <?php echo tep_draw_hidden_field('categories_previous_image', (isset($cInfo->categories_image) ? $cInfo->categories_image :'')); ?>
                    <td width="33%" class="main">
                      <div class="tab-pane" id="imgTabPane1">
                        <script type="text/javascript">
                          imgTp1 = new WebFXTabPane( document.getElementById( "imgTabPane1" ) );
                        </script>
                        <div class="tab-page" id="new_file">
                          <h2 class="tab"><?php echo TEXT_TAB_PANE_NEW_FILE; ?></h2>
                          <script type="text/javascript">imgTp1.addTabPage( document.getElementById( "new_file" ) );</script>
                            <strong><?php echo TEXT_EDIT_CATEGORIES_IMAGE; ?></strong><br><?php echo tep_draw_file_field('categories_image'); ?>
                            <br><br>
                            <strong><?php echo TEXT_SELECT_CATEGORIES_IMAGE_DIR; ?></strong><br><select name="file_destination" class="dirWidth" id="dirPath" ><?php echo $file_dir; ?></select>
                        </div>
                        <div class="tab-page" id="existing_file">
                          <input type="hidden" name="file_exist_dest" id="file_exist_dest" value="">
                          <h2 class="tab"><?php echo TEXT_TAB_PANE_EXISTING_FILE; ?></h2>
                          <script type="text/javascript">imgTp1.addTabPage( document.getElementById( "existing_file" ) );</script>
                          <strong><?php echo TEXT_CATEGORIES_IMAGE_DIR; ?></strong><select name="file_dir_exist_dest" class="dirWidth" id="file_dir_exist_dest" onChange="listFile(this, 'img_file_preview', 'img_file', 'file_exist_dest');"><?php echo $file_dir; ?></select>
                          <div id="img_file"><b><?php echo TEXT_CATEGORIES_IMAGE_FILE; ?></b><select name="file_exist_dest_select" class="dirWidth" id="file_exist_dest_select" onChange="previewFile(this, 'img_file_preview', 'file_exist_dest', 'file_dir_exist_dest');"><?php echo $file_list; ?></select></div>
                          <div align="right"><img id="img_file_preview" src="" /></div>
                        </div>
                      </div>
                    </td>
                    <td width="33%" class="main" align="center">
                      <?php 
                      if ($cInfo->categories_image == '') { } else {?>
                        <strong><?php echo TEXT_EXISTING_CATEGORIES_IMAGE; ?></strong><br>
                        <?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $cInfo->categories_image, $cInfo->categories_image, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="0" vspace="5"') . '<br>' . $cInfo->categories_image . '<br>' . tep_draw_hidden_field('categories_previous_image', $cInfo->categories_image);
                      }
                      if (!($cInfo->categories_image =='')) {
                        echo '<br> <input type="checkbox" name="unlink_cat_image" value="yes">' .  TEXT_CATEGORIES_IMAGE_REMOVE_SHORT;
                        echo '<br> <input type="checkbox" name="delete_cat_image" value="yes">' . TEXT_CATEGORIES_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '10'). '</td>';
                      }
                      ?>
                    </td>
                    <td width="33%" class="main" align="center"><strong><?php echo TEXT_EDIT_SORT_ORDER; ?></strong>&nbsp;<?php echo tep_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'); ?></td>
                  </tr>
                </table>
              </fieldset></td>
                  </tr>
            <?php
          } // RCO eof fieldsetcinfo
          ?>            
          <tr>
            <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>          
          <?php
          // RCO start fieldsetcdescr                                                                                                        
          if ($cre_RCO->get('categories', 'fieldsetcdescr') !== true) {  
            ?>     
            <tr>
              <td colspan="3">
                <table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
                  <tr>
                    <td class="main" valign="top" width="100%"><div class="tab-pane" id="tabPane1">
                      <script type="text/javascript">
                        tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );
                      </script>
                      <?php
                      for ($i=0; $i<sizeof($languages); $i++) {
                        ?>
                        <div class="tab-page" id="<?php echo $languages[$i]['name'];?>">
                        <h2 class="tab"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], '', '', 'align="middle" style="height:16px; width:30px;"') . '&nbsp;' .$languages[$i]['name'];?></h2>
                        <script type="text/javascript">tp1.addTabPage( document.getElementById( "<?php echo $languages[$i]['name'];?>" ) );</script>
                        <table width="98%" border="0" cellspacing="0" cellpadding="0" summary="tab table">
                          <tr>
                            <td valign="top">
                              <table width="100%" border="0" cellspacing="4" cellpadding="0" summary="Title table">
                                <tr valign="top">
                                  <td class="main" width="20%"><strong><?php echo TEXT_EDIT_CATEGORIES_NAME; ?></strong></td>
                                  <td class="main" width="80%">
                                    <?php
                                    if (isset($cInfo->categories_id)) {
                                      echo tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', (isset($categories_name[$languages[$i]['id']]) ? stripslashes($categories_name[$languages[$i]['id']]) : tep_get_category_name($cInfo->categories_id, $languages[$i]['id'])), 'size="64" maxlength="64"'); 
                                    } else{
                                      echo tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', (isset($categories_name[$languages[$i]['id']]) ? stripslashes($categories_name[$languages[$i]['id']]) : ''), 'size="64" maxlength="64"'); 
                                    }
                                    ?>
                                  </td>
                                </tr>
                                <tr valign="top">
                                  <td class="main" width="20%"><strong><?php echo TEXT_EDIT_CATEGORIES_HEADING_TITLE; ?></strong></td>
                                  <td class="main">
                                    <?php
                                    if (isset($cInfo->categories_id)) {
                                      echo tep_draw_input_field('categories_heading_title[' . $languages[$i]['id'] . ']', (isset($categories_heading_title[$languages[$i]['id']]) ? stripslashes($categories_heading_title[$languages[$i]['id']]) : tep_get_category_heading_title($cInfo->categories_id, $languages[$i]['id'])), 'size="64" maxlength="64"'); 
                                    } else {
                                      echo tep_draw_input_field('categories_heading_title[' . $languages[$i]['id'] . ']', (isset($categories_heading_title[$languages[$i]['id']]) ? stripslashes($categories_heading_title[$languages[$i]['id']]) : ''), 'size="64" maxlength="64"'); 
                                    }
                                    ?>
                                  </td>
                                </tr>
                                <tr>
                                  <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
                                </tr>
                              </table>
                              <table width="100%"  border="0" cellspacing="4" cellpadding="0" summary="description tabe">
                                <tr valign="top">
                                  <td class="main"><strong><?php echo TEXT_EDIT_CATEGORIES_DESCRIPTION; ?></strong></td>
                                </tr>
                                <tr>
                                  <td>
                                    <?php
                                    if (isset($cInfo->categories_id)) {
                                      echo tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($categories_description[$languages[$i]['id']]) ? stripslashes($categories_description[$languages[$i]['id']]) : tep_get_category_description($cInfo->categories_id, $languages[$i]['id'])));
                                    } else {
                                      echo tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($categories_description[$languages[$i]['id']]) ? stripslashes($categories_description[$languages[$i]['id']]) : ''));
                                    }
                                    ?>
                                  </td>
                                </tr>
                              </table>
                              <table width="100%"  border="0" cellspacing="3" cellpadding="0" summary="meta content holder table">
                                <tr>
                                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
                                </tr>                               
                                <?php
                                // RCO start fieldsetcmeta                                                                                                      
                                if ($cre_RCO->get('categories', 'fieldsetcmeta') !== true) {  
                                  ?>                                   
                                  <tr>
                                    <td valign="top"><fieldset><legend><?php echo TEXT_PRODUCT_METTA_INFO; ?></legend>
                                      <table width="100%"  border="0" cellspacing="3" cellpadding="3">
                                        <tr>
                                          <td class="main"><strong><?php echo TEXT_EDIT_CATEGORIES_TITLE_TAG; ?></strong></td>
                                        </tr>
                                        <tr>
                                          <td class="main">
                                            <?php
                                            if (isset($cInfo->categories_id)) {
                                              echo tep_draw_textarea_field('categories_head_title_tag[' . $languages[$i]['id'] . ']', 'soft', '15', '1', (isset($categories_head_title_tag[$languages[$i]['id']]) ? stripslashes($categories_head_title_tag[$languages[$i]['id']]) : tep_get_category_head_title_tag($cInfo->categories_id, $languages[$i]['id'])),'style="width: 100%"');
                                            } else {
                                              echo tep_draw_textarea_field('categories_head_title_tag[' . $languages[$i]['id'] . ']', 'soft', '15', '1', (isset($categories_head_title_tag[$languages[$i]['id']]) ? stripslashes($categories_head_title_tag[$languages[$i]['id']]) : ''),'style="width: 100%"');
                                            }
                                            ?>
                                          </td>
                                        </tr>
                                      </table>
                                      <table width="100%"  border="0" cellspacing="3" cellpadding="3">
                                        <tr>
                                          <td width="50%" class="main"><strong><?php echo TEXT_EDIT_CATEGORIES_DESC_TAG;?></strong></td>
                                          <td width="50%" class="main"><strong><?php echo TEXT_EDIT_CATEGORIES_KEYWORDS_TAG; ?></strong></td>
                                        </tr>
                                        <tr>
                                          <td class="main">
                                            <?php
                                            if (isset($cInfo->categories_id)) {
                                              echo tep_draw_textarea_field('categories_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '25', '5', (isset($categories_head_desc_tag[$languages[$i]['id']]) ? stripslashes($categories_head_desc_tag[$languages[$i]['id']]) : tep_get_category_head_desc_tag($cInfo->categories_id, $languages[$i]['id'])),'style="width: 100%"');
                                            } else {
                                              echo tep_draw_textarea_field('categories_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '25', '5', (isset($categories_head_desc_tag[$languages[$i]['id']]) ? stripslashes($categories_head_desc_tag[$languages[$i]['id']]) : ''),'style="width: 100%"');
                                            }
                                            ?>
                                          </td>
                                          <td class="main">
                                            <?php
                                            if (isset($cInfo->categories_id)) {
                                              echo tep_draw_textarea_field('categories_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '25', '5', (isset($categories_head_keywords_tag[$languages[$i]['id']]) ? stripslashes($categories_head_keywords_tag[$languages[$i]['id']]) : tep_get_category_head_keywords_tag($cInfo->categories_id, $languages[$i]['id'])),'style="width: 100%"');
                                            } else {
                                              echo tep_draw_textarea_field('categories_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '25', '5', (isset($categories_head_keywords_tag[$languages[$i]['id']]) ? stripslashes($categories_head_keywords_tag[$languages[$i]['id']]) : ''),'style="width: 100%"');
                                            }
                                            ?>
                                          </td>
                                        </tr>
                                      </table>
                                      <br>
                                    </fieldset></td>
                                  </tr>
                                  <?php
                                } // RCO eof fieldsetcmeta
                                ?>
                              </table>
                            </td>
                          </tr>
                        </table>
                        </div>
                        <?php
                      }
                      ?>
                    </div>
                    <script type="text/javascript">
                      //<![CDATA[
                      setupAllTabs();
                      //]]>
                    </script>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                  </tr>
                </table>
              </td>
            </tr>
            <?php
          } // RCO eof fieldsetcdescr
          ?>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>          
          <?php
          // RCO start fieldsetcgroups
          if ($cre_RCO->get('categories', 'fieldsetcgroups') !== true) {  
            ?>          
            <tr>
              <td><fieldset><legend><?php echo TEXT_PRODUCTS_GROUP_ACCESS; ?></legend>
                <table width="80%" border="0" cellspacing="3" cellpadding="3" align="center" summary="category Image and sort order">
                  <tr valign="top">
                    <td width="50%" class="main">&nbsp;
                      <table width="100%" border="0" cellspacing="2" cellpadding="2">
                        <tr>
                          <td class="main">
                          <?php
                          $customergroup_query = tep_db_query("select customers_group_name,customers_group_id from ".TABLE_CUSTOMERS_GROUPS . " where group_status = '1' and group_access = '1'");
                          while ($customergroup = tep_db_fetch_array($customergroup_query)) {
                            $products_groups[] = $customergroup;
                            if ($customergroup['customers_group_id'] == '0') {
                              $products_groups[] = array('customers_group_id' => 'G', 'customers_group_name' => 'Guest');
                            }
                          }
                          echo tep_draw_checkbox_field('access', '', false, '', 'onclick="checkUncheckAll(this, \'products_group_access[]\');"') . ' ' . TEXT_ACCESS . '<br>';
                          $products_group_access = array();
                          $products_group_array = explode(',',$cInfo->products_group_access);
                          if (!tep_not_null($cInfo->categories_id)) {
                            $cid_array = explode('_', $_GET['cPath']);
                            $cid = array_pop($cid_array);
                            $customergroup = tep_db_fetch_array(tep_db_query("select products_group_access from ".TABLE_CATEGORIES . " where categories_id = '" . $cid . "'"));
                            $products_groups1 = explode(',', $customergroup['products_group_access']);
                            foreach ($products_groups1 as $value) {
                              $products_group_access[$value['customers_group_id']] = true;
                            }
                          } else {
                            foreach ($products_group_array as $value) {
                              $products_group_access[$value] = true;
                            }
                          }
                          foreach ($products_groups as $value) {
                            if ($value['customers_group_id'] == 'G') {
                              echo '&nbsp;&nbsp;';
                            }
                            echo '&nbsp;&nbsp;' . tep_draw_checkbox_field('products_group_access[]', $value['customers_group_id'], isset($products_group_access[$value['customers_group_id']]), '', ($value['customers_group_id'] == '0' ? 'onclick="retailCheck(this, \'products_group_access[]\');"' : '')) . ' ' . $value['customers_group_name'] . '<br>';
                          }
                          ?>
                          </td>
                          <td class="main" width="50%">
                          <?php /* commented out - this is for future use
                          echo tep_draw_checkbox_field('show_in_nav', '', false, '', 'onclick="checkUncheckAll(this, \'products_nav_access[]\');"') . ' ' . TEXT_SHOW_IN_NAV . '<br>';
                          $products_group_access = array();
                          $products_group_array = explode(',',$cInfo->products_nav_access);
                          foreach ($products_group_array as $value) {
                            $products_group_access[$value] = true;
                          }
                          foreach ($products_groups as $value) {
                            echo '&nbsp;&nbsp;' . tep_draw_checkbox_field('products_nav_access[]', $value['customers_group_id'], isset($products_group_access[$value['customers_group_id']])) . ' ' . $value['customers_group_name'] . '<br>';
                          }
                          */ ?>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td><fieldset><legend><?php echo TEXT_PUSH_SAVE_OPTION;?></legend>
                      <?php
                      echo tep_draw_radio_field('Push', '1').TEXT_DO_NOT_PUSH."<br>";
                      echo tep_draw_radio_field('Push', '2').TEXT_PUSH_SUB_CATEGORIES."<br>";
                      echo tep_draw_radio_field('Push', '3').TEXT_PUSH_SUB_CATEGORIES_PRODUCTS."<br><br>";
                      echo  tep_image('images/icons/warning.gif',TEXT_PUSH_WARNING) . '&nbsp;<font size="1">'.TEXT_PUSH_WARNING.'</font>'."<br>";
                      ?>
                    </fieldset></td>
                  </tr>
                </table>
              </fieldset></td>
            </tr>
            <?php
          } // RCO eof fieldsetcgroups          
          ?>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>          
          <tr>
            <td class="main" align="right">
            <?php
            echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($cID) ? '&cID=' . $cID : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>&nbsp;' . tep_draw_hidden_field('categories_date_added', (isset($cInfo->date_added) ? $cInfo->date_added : date('Y-m-d'))) . tep_draw_hidden_field('parent_id', $cInfo->parent_id) . tep_image_submit('button_preview_upload.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;'; 
            ?>
            </td>
          </tr>
          </form>
          <?php
          // new_category_preview (active when ALLOW_CATEGORY_DESCRIPTIONS is 'true')
        } else if (isset($action) && $action == 'new_category_preview') {
            if (isset($_POST)) {
              $cInfo = new objectInfo($_POST);
              $categories_name = $_POST['categories_name'];
              $categories_heading_title = $_POST['categories_heading_title'];
              $categories_description = $_POST['categories_description'];
              $categories_head_title_tag = $_POST['categories_head_title_tag'];
              $categories_head_desc_tag = $_POST['categories_head_desc_tag'];
              $categories_head_keywords_tag = $_POST['categories_head_keywords_tag'];
              $categories_image = (isset($_POST['categories_image']) ? $_POST['categories_image'] : '');
              $categories_previous_image = (isset($_POST['categories_previous_image']) ? $_POST['categories_previous_image'] : '');
              $categories_type_image = (isset($_POST['categories_type_image']) ? $_POST['categories_type_image'] : '');
              $categories_file_destination  = (isset($_POST['file_destination']) ? $_POST['file_destination'] : '');
              $categories_file_destination = str_ireplace( "%2F", "/", $categories_file_destination);
              $unlink_cat_image = (isset($_POST['unlink_cat_image']) ? $_POST['unlink_cat_image'] : '');
              $delete_cat_image = (isset($_POST['delete_cat_image']) ? $_POST['delete_cat_image'] : '');
              // copy image only if modified
              if ( (isset($_POST['delete_cat_image'])) && ($_POST['delete_cat_image'] == 'yes') ) {
                unlink(DIR_FS_CATALOG_IMAGES . $categories_previous_image);
              }
              if (((isset($_POST['unlink_cat_image'])) && ($_POST['unlink_cat_image'] == 'yes')) || ((isset($_POST['delete_cat_image'])) && ($_POST['delete_cat_image'] == 'yes'))) {
                $categories_image = '';
                $categories_image_name = '';
              } else {
                $categories_image_tmp = '';
                $categories_image_tmp = new upload('categories_image');
                $categories_image_tmp->set_destination(DIR_FS_CATALOG_IMAGES . $categories_file_destination);
                if ($categories_image_tmp->parse() && $categories_image_tmp->save()) {
                  $categories_image_name =  $categories_file_destination . $categories_image_tmp->filename;
                } elseif (isset($_POST['file_exist_dest']) && tep_not_null($_POST['file_exist_dest'])) {
                  $categories_image_name = rawurldecode($_POST['file_dir_exist_dest']) . $_POST['file_exist_dest'];
                } else if (is_file(DIR_FS_CATALOG_IMAGES . $categories_file_destination . $categories_image)){
                  $categories_image_name = $categories_file_destination . $categories_image;
                } else {
                  $categories_image_name = $categories_previous_image;
                }
              }
            } else {
              $category_query = tep_db_query("select c.categories_id, cd.language_id, cd.categories_name, cd.categories_heading_title, cd.categories_description, cd.categories_head_title_tag, cd.categories_head_desc_tag, cd.categories_head_keywords_tag, c.categories_image, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and c.categories_id = '" . $cID . "'");
              $category = tep_db_fetch_array($category_query);
              $cInfo = new objectInfo($category);
              $categories_image_name = $cInfo->categories_image;
            }
            $form_action = ((isset($cID)) && tep_not_null($cID) ) ? 'update_category' : 'insert_category';
            echo tep_draw_form($form_action, FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cID . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');
            $read = '';
            if (isset($_GET['read'])){
              $read = $_GET['read'];
            }
            $languages = tep_get_languages();
            for ($i=0; $i<sizeof($languages); $i++) {
              if ($read == 'only') {
                $cInfo->categories_name = tep_get_category_name($cInfo->categories_id, $languages[$i]['id']);
                $cInfo->categories_heading_title = tep_get_category_heading_title($cInfo->categories_id, $languages[$i]['id']);
                $cInfo->categories_description = tep_get_category_description($cInfo->categories_id, $languages[$i]['id']);
                $cInfo->category_template_id = tep_get_category_template_id($cInfo->categories_id, $languages[$i]['id']);
                $cInfo->categories_head_title_tag = tep_get_category_head_title_tag($cInfo->categories_id, $languages[$i]['id']);
                $cInfo->categories_head_desc_tag = tep_get_category_head_desc_tag($cInfo->categories_id, $languages[$i]['id']);
                $cInfo->categories_head_keywords_tag = tep_get_category_head_keywords_tag($cInfo->categories_id, $languages[$i]['id']);
              } else {
                $cInfo->categories_name = tep_db_prepare_input($categories_name[$languages[$i]['id']]);
                $cInfo->categories_heading_title = tep_db_prepare_input($categories_heading_title[$languages[$i]['id']]);
                $cInfo->categories_description = tep_db_prepare_input($categories_description[$languages[$i]['id']]);
                $cInfo->category_template_id = (isset($category_template_id[$languages[$i]['id']]) ? tep_db_prepare_input($category_template_id[$languages[$i]['id']]) : 0) ;
                $cInfo->categories_head_title_tag = tep_db_prepare_input($categories_head_title_tag[$languages[$i]['id']]);
                $cInfo->categories_head_desc_tag = tep_db_prepare_input($categories_head_desc_tag[$languages[$i]['id']]);
                $cInfo->categories_head_keywords_tag = tep_db_prepare_input($categories_head_keywords_tag[$languages[$i]['id']]);
              }
              ?>
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="pageHeading"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $cInfo->categories_heading_title; ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php //echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $categories_image_name, $cInfo->categories_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . $cInfo->categories_description; ?>
                
                <table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="main" ><?php echo $cInfo->categories_description; ?></td>
                    <td class="main" width="10"></td>
                    <?php 
                    if ($categories_image_name) {
                      echo '<td class="main" valign="top" >' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $categories_image_name, $categories_image_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . '</td>'; 
                    }
                    ?>                    
                  </tr>
                </table>


                </td>
              </tr>
              <?php
            }
            if ($read == 'only') {
              if (isset($_GET['origin'])) {
                $pos_params = strpos($_GET['origin'], '?', 0);
                if ($pos_params != false) {
                  $back_url = substr($_GET['origin'], 0, $pos_params);
                  $back_url_params = substr($_GET['origin'], $pos_params + 1);
                } else {
                  $back_url = $_GET['origin'];
                  $back_url_params = '';
                }
              } else {
                $back_url = FILENAME_CATEGORIES;
                $back_url_params = 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id;
              }
              ?>
              <tr>
                <td align="right"><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
              </tr>
              <?php
            } else {
              ?>
              <tr>
                <td align="right" class="smallText">
                  <?php
                  reset($_POST);
                  while (list($key, $value) = each($_POST)) {
                    if (is_array($value)) {
                      while (list($k, $v) = each($value)) {
                        echo tep_draw_hidden_field($key . '[' . $k . ']', htmlspecialchars(stripslashes($v))) . "\n";
                      }
                    } else {
                      echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value))) . "\n";
                    }
                  }
                  echo tep_draw_hidden_field('X_categories_image', stripslashes($categories_image_name)) . "\n";
                  echo tep_draw_hidden_field('categories_image_name', stripslashes($categories_image_name)) . "\n";
                  echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . "\n\n"; 
                  echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cID) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>&nbsp;';
                  if ($cID) {
                    echo tep_image_submit('button_update.gif', IMAGE_UPDATE) . "\n";
                  } else {
                    echo tep_image_submit('button_insert.gif', IMAGE_INSERT) . "\n";
                  }                               
                  ?>&nbsp;
                </td>
                </form>
              </tr>
              <?php
            }
        } elseif ($action == 'new_product') {
          $parameters = array('products_name' => '',
                              'products_blurb' => '',
                              'products_description' => '',
                              'products_url' => '',
                              'products_id' => '',
                              'products_quantity' => '',
                              'products_model' => '',
                              'max_lines_count' => '-1',
                              'max_images_count' => '-1',                             
                              'default_texts' => '',
                              'products_image' => '',
                              'products_image_med' => '',
                              'products_image_lrg' => '',
                              'products_image_rls2' => '',
                              'products_image_sm_1' => '',
                              'products_image_xl_1' => '',
                              'products_image_sm_2' => '',
                              'products_image_xl_2' => '',
                              'products_image_sm_3' => '',
                              'products_image_xl_3' => '',
                              'products_image_sm_4' => '',
                              'products_image_xl_4' => '',
                              'products_image_sm_5' => '',
                              'products_image_xl_5' => '',
                              'products_image_sm_6' => '',
                              'products_image_xl_6' => '',
                              'products_price' => '',
                              'products_price1' => '',
                              'products_price2' => '',
                              'products_price3' => '',
                              'products_price4' => '',
                              'products_price5' => '',
                              'products_price6' => '',
                              'products_price7' => '',
                              'products_price8' => '',
                              'products_price9' => '',
                              'products_price10' => '',
                              'products_price11' => '',
                              'products_price1_qty' => '',
                              'products_price2_qty' => '',
                              'products_price3_qty' => '',
                              'products_price4_qty' => '',
                              'products_price5_qty' => '',
                              'products_price6_qty' => '',
                              'products_price7_qty' => '',
                              'products_price8_qty' => '',
                              'products_price9_qty' => '',
                              'products_price10_qty' => '',
                              'products_price11_qty' => '',
                              'products_qty_blocks' => '',
                              'products_group_access' => 'G,0',
                              'products_nav_access' => 'G,0',
                              'products_weight' => '',
                              'products_date_added' => '',
                              'products_last_modified' => '',
                              'products_date_available' => date('Y-m-d'),
                              'products_status' => '',
							  'products_type' => '',
                              'products_tax_class_id' => '',
                              'manufacturers_id' => '',
                              'sort_order' => '',
							  'show_in_search' => '',
                              'products_qty_days' => '',
                              'products_qty_years' => '');
       
          $pInfo = new objectInfo($parameters);
          if (isset($pID) && empty($_POST)) {
            $products_extra_fields_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " WHERE products_id=" . (int)$pID);
            while ($products_extra_fields = tep_db_fetch_array($products_extra_fields_query)) {
              $extra_field[$products_extra_fields['products_extra_fields_id']] = $products_extra_fields['products_extra_fields_value'];
              $extra_field_array=array('extra_field'=>$extra_field);
              $pInfo->objectInfo($extra_field_array);
            }
			//Mar 03 2011 - Modified to add p.products_min_order_qty,
            $product_query = tep_db_query("select pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_url, p.products_id, p.vendors_id, p.vendors_prod_id, p.vendors_prod_comments, p.vendors_product_price, p.products_quantity, p.products_min_order_qty, p.overall_quantity, p.progressed_quantity, p.labour_cost, p.overhead_cost, p.material_cost, p.products_model, p.max_images_count, p.max_lines_count, p.default_texts, p.products_image, p.products_image_med, p.products_image_lrg, p.products_image_rls2, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, p.products_price, p.products_weight, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.products_tax_class_id, p.manufacturers_id,products_group_access, p.products_price, p.products_price1, p.products_price2, p.products_price3, p.products_price4, p.products_price5, p.products_price6, p.products_price7, p.products_price8, p.products_price9, p.products_price10, p.products_price11, p.products_price1_qty, p.products_price2_qty, p.products_price3_qty, p.products_price4_qty, p.products_price5_qty, p.products_price6_qty, p.products_price7_qty, p.products_price8_qty, p.products_price9_qty, p.products_price10_qty, p.products_price11_qty, p.products_group_access, p.products_nav_access, p.sort_order, p.products_qty_days, p.products_qty_years, p.products_type, p.show_in_search from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$pID . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
            $product = tep_db_fetch_array($product_query);
            if (!empty($product)){
              $pInfo->objectInfo($product);
            }
          } elseif ((isset($_POST)) && (tep_not_null($_POST)) ){
            $pInfo->objectInfo($_POST);
            $products_name = $_POST['products_name'];
            $products_blurb = $_POST['products_blurb']; 
            $products_description = $_POST['products_description'];
            $products_url = $_POST['products_url'];
          }
          $manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
          $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
          while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
            $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                           'text' => $manufacturers['manufacturers_name']);
          }
          // multi-vendor shipping
          $vendors_array = array(array('id' => '1', 'text' => 'NONE'));
          $vendors_query = tep_db_query("select vendors_id, vendors_name from " . TABLE_VENDORS . " order by vendors_name");
          while ($vendors = tep_db_fetch_array($vendors_query)) {
            $vendors_array[] = array('id' => $vendors['vendors_id'],
                                     'text' => $vendors['vendors_name']);
          }
          // multi-vendor shipping //eof        
          $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
          $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
          while ($tax_class = tep_db_fetch_array($tax_class_query)) {
            $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                                       'text' => $tax_class['tax_class_title']);
          }
          $languages = tep_get_languages();
          if (!isset($pInfo->products_status)) $pInfo->products_status = '1';
          switch ($pInfo->products_status) {
            case '0': $in_status = false; $out_status = true; break;
            case '1':
            default: $in_status = true; $out_status = false;
          }
		  
		  //Select Badge settings
		  
		  //echo $pInfo->products_type;
		  
          if (!isset($pInfo->products_type)) $pInfo->products_type = '1';
          switch ($pInfo->products_type) {
            case '0': $pr_badge = false; $pr_nonbadge = true; break;
            case '1':
            default: $pr_badge = true; $pr_nonbadge = false;
          }
                                     
          $badge_settings_arr = tep_get_badge_settings((int)$pID);          
          switch ($badge_settings_arr['bgcolor']) {
              case '0': $bgcolor_on = false; $bgcolor_off = true; break;
              case '1': 
              default: $bgcolor_on = true; $bgcolor_off = false; break;
          }		  
		  switch ($badge_settings_arr['bgbrush']) {
              case '0': $bgbrush_on = false; $bgbrush_off = true; break;
              case '1': 
              default: $bgbrush_on = true; $bgbrush_off = false; break;
          }		  
          switch ($badge_settings_arr['text_color']) {
              case '0': $text_color_on = false; $text_color_off = true; break;
              case '1': 
              default: $text_color_on = true; $text_color_off = false; break;
          }
          switch ($badge_settings_arr['fonts']) {
              case '0': $fonts_on = false; $fonts_off = true; break;
              case '1': 
              default: $fonts_on = true; $fonts_off = false; break;
          }
		  
          ?>
          <script type="text/javascript">
            <!--
            var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "products_date_available","btnDate1","<?php echo $pInfo->products_date_available; ?>",scBTNMODE_CUSTOMBLUE);
            //-->
          </script>
          <script type="text/javascript"><!--
          var tax_rates = new Array();
          <?php
          for ($i=0, $n=sizeof($tax_class_array); $i<$n; $i++) {
            if ($tax_class_array[$i]['id'] > 0) {
              echo 'tax_rates["' . $tax_class_array[$i]['id'] . '"] = ' . tep_get_tax_rate($tax_class_array[$i]['id']) . ';' . "\n";
            }
          }
          ?>
          function doRound(x, places) {
            return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
          }
          function getTaxRate() {
            var selected_value = document.forms["new_product"].products_tax_class_id.selectedIndex;
            var parameterVal = document.forms["new_product"].products_tax_class_id[selected_value].value;
            if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
              return tax_rates[parameterVal];
            } else {
              return 0;
            }
          }
          function updateGross() {
            var taxRate = getTaxRate();
            var grossValue = document.forms["new_product"].products_price.value;
            if (taxRate > 0) {
              grossValue = grossValue * ((taxRate / 100) + 1);
            }
            document.forms["new_product"].products_price_gross.value = doRound(grossValue, 4);
            document.forms["new_product"].products_price0.value = document.forms["new_product"].products_price.value;
          }
          function updateNet() {
            var taxRate = getTaxRate();
            var netValue = document.forms["new_product"].products_price_gross.value;
            if (taxRate > 0) {
              netValue = netValue / ((taxRate / 100) + 1);
            }
            document.forms["new_product"].products_price.value = doRound(netValue, 4);
            document.forms["new_product"].products_price0.value = document.forms["new_product"].products_price.value;
          }
          function setRetailPrice() {
            var newValue = document.forms["new_product"].products_price0.value;
            document.forms["new_product"].products_price.value = newValue;
            updateGross();
            updateNet();
          }
          function checkdelete() {
            var thereturnvalue = true;
            for(i=0; i < document.new_product.elements.length; i++) {
              if (document.new_product.elements[i].name == 'subproddelete[]') {
                if (document.new_product.elements[i].checked) {
                  var agree=confirm("Are you sure you want to delete these sub-products?");
                  if (agree) {
                    thereturnvalue = true;
                    break;
                  } else {
                    thereturnvalue = false;
                    break;
                  } 
                }
              }
            }
            return thereturnvalue;
          }
		  
		  $(document).ready(function() {
             
             $('input[name=products_type]').click(function() {  
                  
                 if($('input[name=products_type]:checked').val()==1) {                       
                     $("#badge_settings").show();               
                 } else {                     
                     $("#badge_settings").hide();
                 }
                 
             });
             
          });
          //--></script>
          <?php
        // editor functions
        echo tep_load_html_editor();
        $products_elements = '';
        for ($i=0; $i<sizeof($languages); $i++) {
          $products_elements .= 'products_description[' . $languages[$i]['id'] . '],';
        }
        echo tep_insert_html_editor($products_elements);
        // editor functions eof
        // RCI start
        echo $cre_RCI->get('categories', 'pedittop');
        // RCI eof        
        if ( !empty($pID) ) {
          $form_action_text = 'Update';
          $form_action_action = 'update_product';
          $form_action_button = tep_image_submit('button_quick_save.gif',IMAGE_UPDATE,'name="Operation" onClick="document.pressed=this.value" VALUE="'.$form_action_action.'"');
        } else {
          $form_action_text = 'Insert';
          $form_action_action = 'insert_product';
          $form_action_button = tep_image_submit('button_quick_save.gif',IMAGE_UPDATE,'name="Operation" onClick="document.pressed=this.value" VALUE="'.$form_action_action.'"');
        }
        ?>
        <script type="text/javascript">
        function OnSubmitForm() {
          if(trim(document.pressed) == '<?php echo $form_action_text;?>') {
            document.new_product.action ="<?php echo str_replace('&amp;', '&', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($pID) ? '&pID=' . $pID : '') . '&action=' . $form_action_action)); ?>";
          } else if(trim(document.pressed) == 'Preview') {
            document.new_product.action ="<?php echo str_replace('&amp;', '&', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($pID) ? '&pID=' . $pID : '') . '&action=new_product_preview')); ?>";
          }
          return true;
        }
        </script>
        <form name="new_product" method="post" enctype="multipart/form-data" onSubmit="return OnSubmitForm();return checkdelete();setRetailPrice()">
        <table border="0" width="98%" cellspacing="0" cellpadding="2" align="center"> 
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>
          <tr>
            <td class="pageHeading">
              <?php             
              $text_new_or_edit = (isset($_GET['pID'])) ? sprintf(TEXT_INFO_HEADING_EDIT_PRODUCT, tep_get_products_name($_GET['pID'], $language_id)) : sprintf(TEXT_NEW_PRODUCT, tep_output_generated_category_path($current_category_id)); 
              echo $text_new_or_edit;
              ?>
            </td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>          
          <?php
          // RCO start fieldsetpinfo
          if ($cre_RCO->get('categories', 'fieldsetpinfo') !== true) {  
            ?>
            <tr>
              <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
            </tr>
            <tr>
              <td colspan="2"><fieldset style="display:none;"></fieldset><fieldset><legend><?php echo TEXT_GENERAL_OPTIONS; ?></legend>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr valign="top">
                    <td width="50%"><table  border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td class="main"><?php echo TEXT_PRODUCTS_STATUS; ?></td>
                        <?php
                        $parentid_query = tep_db_query("select products_parent_id from products  where products_id =".(int)$pID);
                        $parentid = tep_db_fetch_array($parentid_query);
                        if ($parentid["products_parent_id"] == 0) {
                          ?>
                          <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_status', '1', $in_status) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE . '&nbsp;' . tep_draw_radio_field('products_status', '0', $out_status) . '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE; ?></td>
                          <?php
                        } else{
                         ?>
                         <td class="main"><?php echo TEXT_SUB_PRODUCT_SUBPRODUCT; ?></td>  <?php
                        }
                        ?>
                      </tr>
                      <tr>
                        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                      </tr>
                      <tr>
                        <td class="main"><?php echo TEXT_PRODUCTS_DATE_AVAILABLE; ?><br><small><?php DATE_FORMAT;?></small></td>
                        <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; ?>
                          <script script type="text/javascript">dateAvailable.writeControl(); dateAvailable.dateFormat="yyyy-MM-dd";</script>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                      </tr>
                      <tr>
                        <td class="main"><?php echo TEXT_PRODUCTS_MANUFACTURER; ?></td>
                        <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id); ?></td>
                      </tr>
					  
                      <!-- Badge settings Start -->
					  
					  <tr>
                           <td colspan="2"><b>Badge Settings</b></td>
                      </tr>
                          					  
					  <tr>
                        <td class="main"><?php echo TEXT_PRODUCTS_TYPE; ?></td>
                        <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_type', '1', $pr_badge,' id="show_bdg_settings" ') . '&nbsp;' . TEXT_BADGE_PRODUCT . '&nbsp;' . tep_draw_radio_field('products_type', '0', $pr_nonbadge,' id="hide_bdg_settings" ') . '&nbsp;' . TEXT_NONBADGE_PRODUCT; ?></td>
                      </tr> 
                      
                      <div id="badge_settings">                          
                          <tr>
                            <td class="main"><?php echo TEXT_BADGE_PRODUCTS_BGCOLOR; ?></td>
                            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('bgcolor', '1', $bgcolor_on) . '&nbsp;' . TEXT_BADGE_PRODUCT_BGCOLOR_ON . '&nbsp;' . tep_draw_radio_field('bgcolor', '0', $bgcolor_off) . '&nbsp;' . TEXT_BADGE_PRODUCT_BGCOLOR_OFF; ?></td>
                          </tr>
						  
						  <tr>
                            <td class="main"><?php echo TEXT_BADGE_PRODUCTS_BGBRUSH; ?></td>
                            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('bgbrush', '1', $bgbrush_on) . '&nbsp;' . TEXT_BADGE_PRODUCT_BGBRUSH_ON . '&nbsp;' . tep_draw_radio_field('bgbrush', '0', $bgbrush_off) . '&nbsp;' . TEXT_BADGE_PRODUCT_BGBRUSH_OFF; ?></td>
                          </tr> 
                                                                              
                          <tr>
                            <td class="main"><?php echo TEXT_BADGE_PRODUCTS_TEXTCOLOR; ?></td>
                            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('text_color', '1', $text_color_on) . '&nbsp;' . TEXT_BADGE_PRODUCT_TEXTCOLOR_ON . '&nbsp;' . tep_draw_radio_field('text_color', '0', $text_color_off) . '&nbsp;' . TEXT_BADGE_PRODUCT_TEXTCOLOR_OFF; ?></td>
                          </tr>
                                                                              
                          <tr>
                            <td class="main"><?php echo TEXT_BADGE_PRODUCTS_FONTS; ?></td>
                            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('fonts', '1', $fonts_on) . '&nbsp;' . TEXT_BADGE_PRODUCT_FONTS_ON . '&nbsp;' . tep_draw_radio_field('fonts', '0', $fonts_off) . '&nbsp;' . TEXT_BADGE_PRODUCT_FONTS_OFF; ?></td>
                          </tr>
                      
                      </div>
                       
					  <!-- Badge settings End -->
					  
					  <!-- Product costs - April 29 2011 -->
					  <tr>
                        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                      </tr>
                      <tr>
                        <td class="main" colspan="2"><b><?php echo TEXT_PRODUCTS_COSTS; ?></b></td>                       
                      </tr>
					  <tr>
                        <td class="main"><?php echo TEXT_PRODUCTS_LABOUR; ?></td>
                        <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('labour_cost', $pInfo->labour_cost); ?></td>
                      </tr>
					  <tr>
                        <td class="main"><?php echo TEXT_PRODUCTS_OVERHEAD; ?></td>
                        <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('overhead_cost', $pInfo->overhead_cost); ?></td>
                      </tr>
					  <tr>
                        <td class="main"><?php echo TEXT_PRODUCTS_MATERIAL; ?></td>
                        <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('material_cost', $pInfo->material_cost); ?></td>
                      </tr>
					  <!-- Product costs - April 29 2011 -->
					  
                    </table></td>
                    <td width="50%"><table border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td class="main"><?php echo TEXT_SORT_ORDER; ?></td>
                        <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('sort_order', $pInfo->sort_order); ?></td>
                      </tr>
                      <tr>
                        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                      </tr>                      
                      <tr>
                        <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY; ?></td>
                        <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_quantity', $pInfo->products_quantity); ?></td>
					  </tr>
					  
                      <tr>
                        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                      </tr>
					  
					  <!-- Aug 01 2012 - added entry for overall_quantity -->
					  <tr>
                        <td class="main"><?php echo TEXT_OVERALL_QUANTITY; ?></td>
                        <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('overall_quantity', $pInfo->overall_quantity); ?></td>
					  </tr>	
                      <tr>
                        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                      </tr>
					   <tr>
                        <td class="main"><?php echo TEXT_PROGRESSED_QUANTITY; ?></td>
                        <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('progressed_quantity', $pInfo->progressed_quantity); ?></td>
					  </tr>	 
                      <tr>
                        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                      </tr>
					  <!-- End -->
					  
					  <!-- Mar 03 2011 - added entry for products_min_order_qty -->
					  <tr>
                        <td class="main"><?php echo TEXT_PRODUCTS_MIN_ORDER_QTY; ?></td>
                        <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_min_order_qty', $pInfo->products_min_order_qty); ?></td>
                      <tr>
                        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                      </tr>
					  <!-- End -->
                      <tr>
                        <td class="main"><?php echo TEXT_PRODUCTS_MODEL; ?></td>
                        <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_model', $pInfo->products_model); ?></td>
                      </tr>
                      <tr>
                        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                      </tr>
                      <tr>
                        <td class="main"><?php echo TEXT_PRODUCTS_WEIGHT; ?></td>
                        <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_weight', $pInfo->products_weight); ?></td>
                      </tr>
                      <tr>
                        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                      </tr>
                      <tr>
                        <td class="main"><?php echo TEXT_MAX_LINES_COUNT; ?></td>
                        <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('max_lines_count', $pInfo->max_lines_count); ?></td>
                      </tr>
                      <tr>
                        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                      </tr>
                      <tr>
                        <td class="main"><?php echo TEXT_MAX_IMAGES_COUNT; ?></td>
                        <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('max_images_count', $pInfo->max_images_count); ?></td>
                      </tr>
                      <tr>
                        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                      </tr>
                    </table></td>
                  </tr>
                  <?php
                  if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
                    ?>
                    <!-- multi-vendor shipping -->
                    <tr>
                      <td colspan="2" valign="top" width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td  valign="top" width="50%"><table width="100%" border="0" cellspacing="2" cellpadding="2">
                            <tr>
                              <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                            </tr>
                            <tr>
                              <td class="main" width="150"><?php echo TEXT_PRODUCTS_VENDORS; ?></td>
                              <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . tep_draw_pull_down_menu('vendors_id', $vendors_array, $pInfo->vendors_id); ?></td>
                           </tr>
                            <tr>
                              <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                            </tr>
                            <tr>
                              <td class="main" width="150"><?php echo TEXT_VENDORS_PROD_ID; ?></td>
                              <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . tep_draw_input_field('vendors_prod_id', $pInfo->vendors_prod_id); ?></td>
                            </tr>                     
                          </table></td>
                          <td valign="top" width="50%"><table width="100%" border="0" cellspacing="2" cellpadding="2"> 
                            <tr>
                              <td class="main"><?php echo TEXT_VENDORS_PROD_COMMENTS; ?></td>
                            </tr>
                            <tr>
                              <td class="main"><?php echo tep_draw_textarea_field('vendors_prod_comments', 'soft', '43', '4', (isset($vendors_prod_comments) ? $vendors_prod_comments : tep_get_vendors_prod_comments($pInfo->products_id))); ?></td>
                            </tr>
                          </table></td>
                        </tr>
                      </table></td>
                    </tr>
                    <!-- multi-vendor shipping //eof -->
                    <?php
                  }
                  ?>
<?php
// RCI code start
echo $cre_RCI->get('categories', 'peditoptions', false); 
// RCI code eof
?>
                </table>
                <table width="100%"  border="0" cellspacing="2" cellpadding="2">
                  <tr bgcolor="#ebebff">
                    <td class="main"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></td>
                    <td class="main"><?php echo '&nbsp;' . tep_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id, 'onchange="updateGross()"'); ?></td>
                  </tr>
                  <tr bgcolor="#ebebff">
                    <td class="main"><?php echo TEXT_PRODUCTS_PRICE_NET; ?></td>
                    <td class="main"><?php echo '&nbsp;' . tep_draw_input_field('products_price', $pInfo->products_price, 'onKeyUp="updateGross()"'); ?></td>
                  </tr>
                  <tr bgcolor="#ebebff">
                    <td class="main"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
                    <td class="main"><?php echo '&nbsp;' . tep_draw_input_field('products_price_gross', $pInfo->products_price, 'OnKeyUp="updateNet()"'); ?></td>
                  </tr>
                  <?php
                  if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
                    ?>
                    <!-- multi-vendor shipping -->
                    <tr bgcolor="#ebebff">
                      <td class="main"><?php echo TEXT_VENDORS_PRODUCT_PRICE_BASE; ?></td>
                      <td class="main"><?php echo '&nbsp;' . tep_draw_input_field('vendors_product_price', $pInfo->vendors_product_price, 'onKeyUp="updateNet()"'); ?></td>
                    </tr>
                    <!-- multi-vendor shipping //eof -->
                    <?php
                  }
                  ?>
                  <script type="text/javascript">
                    <!--
                    updateGross();
                    //-->
                  </script>
                  <tr bgcolor="#ebebff">
                    <td class="main" valign='top'><?php echo TEXT_PRODUCTS_GROUP_ACCESS; ?></td>
                    <td class="main">
                      <table width="100%" border="0" cellspacing="2" cellpadding="2">
                        <tr>
                          <td class="main">
                          <?php
                          $customergroup_query = tep_db_query("select customers_group_name,customers_group_id from ".TABLE_CUSTOMERS_GROUPS . " where group_status = '1' and group_access = '1'");
                          while ($customergroup = tep_db_fetch_array($customergroup_query)) {
                            $products_groups[] = $customergroup;
                            if ($customergroup['customers_group_id'] == '0') {
                              $products_groups[] = array('customers_group_id' => 'G', 'customers_group_name' => 'Guest');
                            }
                          }
                          echo tep_draw_checkbox_field('access', '', false, '', 'onclick="checkUncheckAll(this, \'products_group_access[]\');"') . ' ' . TEXT_ACCESS . '<br>';
                          $products_group_access = array();
                          $products_group_array = explode(',',$pInfo->products_group_access);
                          if (!tep_not_null($pInfo->products_id)) {
                            $cid_array = explode('_', $_GET['cPath']);
                            $cid = array_pop($cid_array);
                            $customergroup = tep_db_fetch_array(tep_db_query("select products_group_access from ".TABLE_CATEGORIES . " where categories_id = '" . $cid . "'"));
                            $products_groups1 = explode(',', $customergroup['products_group_access']);
                            foreach ($products_groups1 as $value) {
                              $products_group_access[$value['customers_group_id']] = true;
                            }
                          } else {
                            foreach ($products_group_array as $value) {
                              $products_group_access[$value] = true;
                            }
                          }
                          foreach ($products_groups as $value) {
                            if ($value['customers_group_id'] == 'G') {
                              echo '&nbsp;&nbsp;';
                            }
                            echo '&nbsp;&nbsp;' . tep_draw_checkbox_field('products_group_access[]', $value['customers_group_id'], isset($products_group_access[$value['customers_group_id']]), '', ($value['customers_group_id'] == '0' ? 'onclick="retailCheck(this, \'products_group_access[]\');"' : '')) . ' ' . $value['customers_group_name'] . '<br>';
                          }
                          ?>
                          </td>
                          <td class="main" width="50%">&nbsp;
                          <?php /* this is reserved for future use
                          echo tep_draw_checkbox_field('show_in_nav', '', false, '', 'onclick="checkUncheckAll(this, \'products_nav_access[]\');"') . ' ' . TEXT_SHOW_IN_NAV . '<br>';
                          $products_group_access = array();
                          $products_group_array = explode(',',$pInfo->products_nav_access);
                          foreach ($products_group_array as $value) {
                            $products_group_access[$value] = true;
                          }
                          foreach ($products_groups as $value) {
                            echo '&nbsp;&nbsp;' . tep_draw_checkbox_field('products_nav_access[]', $value['customers_group_id'], isset($products_group_access[$value['customers_group_id']])) . ' ' . $value['customers_group_name'] . '<br>';
                          }
                          */ ?>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </fieldset></td>
            </tr>
              <?php
          } // RCO eof fieldsetpinfo
          ?>    
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>
          <?php
          $products_price_qty[1] = $pInfo->products_price1_qty;
          if ($pInfo->products_price1_qty == 0) {
            $products_price_qty[1] = TEXT_PRODUCTS_QTY_DISCOUNT;
          }
          $products_price_qty[2] = $pInfo->products_price2_qty;
          if ($pInfo->products_price2_qty == 0) {
            $products_price_qty[2] = 2 * TEXT_PRODUCTS_QTY_DISCOUNT;
          }
          $products_price_qty[3] = $pInfo->products_price3_qty;
          if ($pInfo->products_price3_qty == 0) {
            $products_price_qty[3] = 3 * TEXT_PRODUCTS_QTY_DISCOUNT;
          }
          $products_price_qty[4] = $pInfo->products_price4_qty;
          if ($pInfo->products_price4_qty == 0) {
            $products_price_qty[4] = 4 * TEXT_PRODUCTS_QTY_DISCOUNT;
          }
          $products_price_qty[5] = $pInfo->products_price5_qty;
          if ($pInfo->products_price5_qty == 0) {
            $products_price_qty[5] = 5 * TEXT_PRODUCTS_QTY_DISCOUNT;
          }
          $products_price_qty[6] = $pInfo->products_price6_qty;
          if ($pInfo->products_price6_qty == 0) {
            $products_price_qty[6] = 6 * TEXT_PRODUCTS_QTY_DISCOUNT;
          }
          $products_price_qty[7] = $pInfo->products_price7_qty;
          if ($pInfo->products_price7_qty == 0) {
            $products_price_qty[7] = 7 * TEXT_PRODUCTS_QTY_DISCOUNT;
          }
          $products_price_qty[8] = $pInfo->products_price8_qty;
          if ($pInfo->products_price8_qty == 0) {
          $products_price_qty[8] = 8 * TEXT_PRODUCTS_QTY_DISCOUNT;
          }
          $products_price_qty[9] = $pInfo->products_price9_qty;
          if ($pInfo->products_price9_qty == 0) {
            $products_price_qty[9] = 9 * TEXT_PRODUCTS_QTY_DISCOUNT;
          }
          $products_price_qty[10] = $pInfo->products_price10_qty;
          if ($pInfo->products_price10_qty == 0) {
            $products_price_qty[10] = 10 * TEXT_PRODUCTS_QTY_DISCOUNT;
          }
          $products_price_qty[11] = $pInfo->products_price11_qty;
          if ($pInfo->products_price11_qty == 0) {
            $products_price_qty[11] = 11 * TEXT_PRODUCTS_QTY_DISCOUNT;
          }
          $products_price[1]= $pInfo->products_price1;
          $products_price[2]= $pInfo->products_price2;
          $products_price[3]= $pInfo->products_price3;
          $products_price[4]= $pInfo->products_price4;
          $products_price[5]= $pInfo->products_price5;
          $products_price[6]= $pInfo->products_price6;
          $products_price[7]= $pInfo->products_price7;
          $products_price[8]= $pInfo->products_price8;
          $products_price[9]= $pInfo->products_price9;
          $products_price[10]= $pInfo->products_price10;
          $products_price[11]= $pInfo->products_price11;
          
          // RCO start fieldsetqpb
          if ($cre_RCO->get('categories', 'fieldsetqpb') !== true) { 
            ?>   
            <tr>
              <td colspan="2">
                <fieldset><legend><?php echo TEXT_PRODUCTS_PRICE_GRP; ?></legend>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr valign="top">
                      <td width="50%">
                        <table  border="0" cellspacing="2" cellpadding="2">
<?php
            if (PWA_ON == 'false') {
              if ($pInfo->products_qty_days > 0) {
                $products_history = (int)$pInfo->products_qty_days;
                $products_history_flag = true;
              } else {
                $products_history = (int)$pInfo->products_qty_years;
                $products_history_flag = false;
              }
?>
                          <tr>
                            <td class="main" colspan="12"><?php echo '<b>' . TEXT_QTY_BASED_HISTORY . '</b> ' . tep_draw_input_field('products_history', $products_history, 'size="4"') . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . tep_draw_radio_field('products_history_qty', 'days', $products_history_flag) . ' ' . TEXT_PRODUCTS_QTY_DAYS . '&nbsp;&nbsp;&nbsp;' . tep_draw_radio_field('products_history_qty', 'years', !$products_history_flag) . ' ' . TEXT_PRODUCTS_QTY_YEARS; ?></td>
                          </tr>
<?php
            }
?>
                          <tr>
                            <td class="main"><b><?php echo TEXT_PRODUCTS_GROUPS; ?></b></td>
                            <td class="main" align="center"><b><?php echo TEXT_PRODUCTS_BASE; ?></b></td>
                            <td class="main"><?php echo tep_draw_input_field('products_price1_qty', $products_price_qty[1], 'size="4"'); ?></td>
                            <?php
                            echo '<td class="main">&gt;</td>';
                            for ($i = 2; $i < PRODUCT_QTY_PRICE_LEVEL; $i++) {
                              echo '<td class="main" align="center">' . tep_draw_input_field('products_price' . $i . '_qty', $products_price_qty[$i], 'size="4"') . '</td>';
                              echo '<td class="main">&gt;</td>';
                            }
//                            if (PRODUCT_QTY_PRICE_LEVEL == 11) {
//                              echo '<td class="main" align="center"><b>' . TEXT_PRODUCTS_ABOVE . '</b></td>';
//                            } else {
                              echo '<td class="main">' . tep_draw_input_field('products_price' . PRODUCT_QTY_PRICE_LEVEL . '_qty', $products_price_qty[PRODUCT_QTY_PRICE_LEVEL], 'size="4"') . '</td>';
//                            }
                            ?>
                          </tr>
                          <?php
                          $group_query = tep_db_query("SELECT customers_group_id FROM " . TABLE_CUSTOMERS_GROUPS . " WHERE customers_group_id = 0 AND group_status = '1' AND group_price = '1'");
                          if (tep_db_num_rows($group_query) > 0) {
                            ?>
                            <tr>
                              <td class="main"><?php echo TEXT_PRODUCTS_PRICE; ?></td>
                              <td class="main"><?php echo tep_draw_input_field('products_price0', $pInfo->products_price, 'onKeyUp="setRetailPrice()" size="4"') . '<span style="color:#FF0000;">*</span>'; ?></td>
                              <?php
                              for ($i = 1; $i <= PRODUCT_QTY_PRICE_LEVEL; $i++) {
                                echo '<td class="main">' . tep_draw_input_field('products_price' . $i, $products_price[$i], 'size="4"') . '</td>';
                                echo '<td class="main">&nbsp;</td>';
                              }
                              ?>
                            </tr>
                            <?php
                          }
                          $customers_group_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id != '0' AND group_status = '1' AND group_price = '1' order by customers_group_id");
                          $header = false;
                          while ($customers_group = tep_db_fetch_array($customers_group_query)) {
                            if (tep_db_num_rows($customers_group_query) > 0) {
                              $attributes_query = tep_db_query("select customers_group_id, customers_group_price, customers_group_price1, customers_group_price2, customers_group_price3, customers_group_price4, customers_group_price5, customers_group_price6, customers_group_price7, customers_group_price8, customers_group_price9, customers_group_price10, customers_group_price11 from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . $pInfo->products_id . "' and customers_group_id = '" . $customers_group['customers_group_id'] . "' order by customers_group_id");
                            } else {
                              $attributes = array('customers_group_id' => 'new');
                            }
                            ?>
                            <tr>
                              <td class="main">
                                <?php // only change in version 4.1.1
                                if ($attributes = tep_db_fetch_array($attributes_query)) {
                                  echo tep_draw_checkbox_field('sppcoption[' . $customers_group['customers_group_id'] . ']', 'sppcoption[' . $customers_group['customers_group_id'] . ']', true) . '&nbsp;' . $customers_group['customers_group_name'];
                                } else{
                                  echo tep_draw_checkbox_field('sppcoption[' . $customers_group['customers_group_id'] . ']', 'sppcoption[' . $customers_group['customers_group_id'] . ']', false) . '&nbsp;' . $customers_group['customers_group_name'];
                                }
                                ?>&nbsp;
                              </td>
                              <td class="main">
                                <?php
                                if ($attributes) {
                                  echo tep_draw_input_field('sppcprice[' . $customers_group['customers_group_id'] . ']', $attributes['customers_group_price'], 'size="4"');
                                } else {
                                  if (isset($pInfo->sppcprice[$customers_group['customers_group_id']])) { // when a preview was done and the back button used
                                    $sppc_cg_price = $pInfo->sppcprice[$customers_group['customers_group_id']];
                                  } else { // nothing in the db, nothing in the post variables
                                    $sppc_cg_price = '';
                                  }
                                  echo tep_draw_input_field('sppcprice[' . $customers_group['customers_group_id'] . ']', $sppc_cg_price , 'size="4"');
                                }  
                                ?>
                              </td>
                              <?php
                              for ($i = 1; $i <= PRODUCT_QTY_PRICE_LEVEL; $i++) {
                                echo '<td class="main">';
                                if ($attributes) {
                                  echo tep_draw_input_field('sppcprice' . $i . '[' . $customers_group['customers_group_id'] . ']', $attributes['customers_group_price' . $i], 'size="4"');
                                }  else {
                                  eval('$ret = isset($pInfo->sppcprice' . $i . '[$customers_group["customers_group_id"]]);');
                                  if ($ret) { // when a preview was done and the back button used
                                    eval('$sppc_cg_price = $pInfo->sppcprice' . $i . '[$customers_group["customers_group_id"]];');
                                  } else { // nothing in the db, nothing in the post variables
                                    $sppc_cg_price = '';
                                  }
                                  echo tep_draw_input_field('sppcprice' . $i . '[' . $customers_group['customers_group_id'] . ']', $sppc_cg_price , 'size="4"');
                                }
                                echo '</td>';
                                echo '<td class="main">&nbsp;</td>';
                              }
                              ?>
                            </tr>
                            <?php
                          } // end while ($customers_group = tep_db_fetch_array($customers_group_query))
                          ?>
                        </table>
                      </td>
                    </tr>
                  </table>
                </fieldset>
              </td>
            </tr>
            <?php
          } // RCO eof fieldsetqpb
          ?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>  
          <?php
          // RCO start fieldsetdescr
          if ($cre_RCO->get('categories', 'fieldsetdescr') !== true) { 
            ?>   
            <tr>
              <td colspan="2"><!-- tabs -->
                <table border="0" cellspacing="0" cellpadding="2" width="100%" align="center">
                  <tr>
                    <td class="main" valign="top" width="100%"><div class="tab-pane" id="tabPane1">
                      <script type="text/javascript">tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );</script>
                      <?php
                      for ($i=0; $i<sizeof($languages); $i++) {
                        ?>
                        <div class="tab-page" id="<?php echo $languages[$i]['name'];?>">
                          <h2 class="tab"><nobr><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'],'align="absmiddle" style="height:16px; width:30px;"') . '&nbsp;' .$languages[$i]['name'];?></nobr></h2>
                          <script type="text/javascript">tp1.addTabPage( document.getElementById( "<?php echo $languages[$i]['name'];?>" ) );</script>
                          <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="tab table">
                            <tr>
                              <td valign="top"><table border="0" cellspacing="4" cellpadding="0" summary="Title table">
                                <tr valign="top">
                                  <td class="main"><strong><?php echo TEXT_PRODUCTS_NAME; ?></strong></td>
                                  <?php
                                  if ($parentid["products_parent_id"] == 0) {
                                    $products_name_field = isset($products_name[$languages[$i]['id']]) ? $products_name[$languages[$i]['id']] : tep_get_products_name($pInfo->products_id, $languages[$i]['id']);
                                  } else {
                                    $products_name_field = isset($products_name[$languages[$i]['id']]) ? $products_name[$languages[$i]['id']] : tep_get_products_name($pInfo->products_id, $languages[$i]['id']);
                                    $products_parent_name_field = tep_get_products_name($parentid["products_parent_id"], $languages[$i]['id']);
                                    $products_name_field = substr( $products_name_field, strlen( $products_parent_name_field . ' - ' ));
                                  }
                                  ?>
                                  <td class="main"><?php echo tep_draw_input_field('products_name[' . $languages[$i]['id'] . ']', $products_name_field, 'size="64" maxlength="64"'); ?></td>
                                </tr>
                                <tr valign="top">
                                  <td class="main"><?php echo '<strong>' . TEXT_PRODUCTS_URL . '</strong><br><small>' . TEXT_PRODUCTS_URL_WITHOUT_HTTP . '</small>'; ?></td>
                                  <td class="main"><?php echo tep_draw_input_field('products_url[' . $languages[$i]['id'] . ']', (isset($products_url[$languages[$i]['id']]) ? $products_url[$languages[$i]['id']] : tep_get_products_url($pInfo->products_id, $languages[$i]['id'])), 'size="64" maxlength="255"'); ?></td>
                                </tr>
                                <tr>
                                  <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
                                </tr>
                              </table>
                              <table width="100%"  border="0" cellspacing="4" cellpadding="0" summary="description tabe">
                                <tr valign="top">
                                  <td class="main"><strong><?php echo TEXT_PRODUCTS_BLURB; ?></strong></td>
                                </tr>
                                <tr>
                                  <td><?php echo tep_draw_textarea_field('products_blurb[' . $languages[$i]['id'] . ']', 'soft', '70', '3', (isset($products_blurb[$languages[$i]['id']]) ? $products_blurb[$languages[$i]['id']] : tep_get_products_blurb($pInfo->products_id, $languages[$i]['id'])), 'style="width: 100%;" mce_editable="true"'); ?> </td>
                                </tr>
                              </table>  
                              <?php //if ($parentid["products_parent_id"] == 0) {
                              ?>
                              <table width="100%"  border="0" cellspacing="4" cellpadding="0" summary="description tabe">
                                <tr valign="top">
                                  <td class="main"><strong><?php echo TEXT_PRODUCTS_DESCRIPTION; ?></strong></td>
                                </tr>
                                <tr>
                                  <td><?php echo tep_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_description[$languages[$i]['id']]) ? $products_description[$languages[$i]['id']] : tep_get_products_description($pInfo->products_id, $languages[$i]['id']))); ?></td>
                                </tr>
                              </table>
                              <?php
                                  //}
                              // RCO start fieldsetmeta
                              if ($cre_RCO->get('categories', 'fieldsetmeta') !== true) { 
                                if ($parentid["products_parent_id"] == 0) {
                                ?>                        
                                <table width="100%"  border="0" cellspacing="3" cellpadding="0" summary="meta content holder table">
                                  <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                                  </tr>  
                                  <tr>
                                    <td class="main"><fieldset><legend><?php echo TEXT_PRODUCT_METTA_INFO; ?></legend>
                                      <table width="100%"  border="0" cellspacing="3" cellpadding="3">
                                        <tr>
                                          <td class="main"><strong><?php echo TEXT_PRODUCTS_PAGE_TITLE;?></strong></td>
                                        </tr>
                                        <tr>
                                          <td class="main"><?php echo tep_draw_textarea_field('products_head_title_tag[' . $languages[$i]['id'] . ']', 'soft', '15', '2', (isset($products_head_title_tag[$languages[$i]['id']]) ? $products_head_title_tag[$languages[$i]['id']] : tep_get_products_head_title_tag($pInfo->products_id, $languages[$i]['id'])),'style="width:100%"'); ?></td>
                                        </tr>
                                      </table>
                                      <table width="100%"  border="0" cellspacing="3" cellpadding="3">
                                        <tr class="main">
                                          <td width="50%"><strong><?php echo TEXT_PRODUCTS_HEADER_DESCRIPTION;?></strong></td>
                                          <td width="50%"><strong><?php echo TEXT_PRODUCTS_KEYWORDS; ?></strong></td>
                                        </tr>
                                        <tr class="main">
                                          <td><?php echo tep_draw_textarea_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '35', '5', (isset($products_head_desc_tag[$languages[$i]['id']]) ? $products_head_desc_tag[$languages[$i]['id']] : tep_get_products_head_desc_tag($pInfo->products_id, $languages[$i]['id'])),'style="width:100%"'); ?></td>
                                          <td><?php echo tep_draw_textarea_field('products_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '35', '5', (isset($products_head_keywords_tag[$languages[$i]['id']]) ? $products_head_keywords_tag[$languages[$i]['id']] : tep_get_products_head_keywords_tag($pInfo->products_id, $languages[$i]['id'])),'style="width:100%"'); ?></td>
                                        </tr>
                                      </table>
                                    </fieldset></td>
                                  </tr>
                                </table>
                                <?php
                              }
                             }
                              ?>
                            </tr>
                          </table>
                        </div>
                        <?php
                      }
                      ?>
                    </div>
                    <script type="text/javascript">
                      //<![CDATA[
                      setupAllTabs();
                      //]]>
                    </script>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <?php
          } // RCO eof fieldsetdescr
          ?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>  
          <?php
          // RCO start fieldsetimages
          if ($cre_RCO->get('categories', 'fieldsetimages') !== true) { 
            ?>  
            <tr>    
              <td colspan="2">    
                <fieldset><legend><!-- Product Images --><?php echo TEXT_PRODUCT_IMAGES;?></legend>
                  <table border="0" cellpadding="2" cellspacing="2" width="100%">
                    <!-- // BOF: MaxiDVD Added for Ulimited Images Pack! -->
                    <tr>
                      <td class="dataTableRow" valign="top">
                      <?php if ($parentid["products_parent_id"] == 0) { ?>
                        <span class="main"><?php echo TEXT_PRODUCTS_IMAGE_NOTE; ?></span>
                      <?php } else { ?>
                        <span class="main"><?php echo TEXT_PRODUCTS_IMAGE_NOTE_BORDER; ?></span>
                      <?php } ?>
                        <br><br>
                        <div class="tab-pane" id="imgTabPane1">
                          <script type="text/javascript">
                            imgTp1 = new WebFXTabPane( document.getElementById( "imgTabPane1" ) );
                          </script>
                          <div class="tab-page" id="new_file">
                            <h2 class="tab"><?php echo TEXT_TAB_PANE_NEW_FILE; ?></h2>
                            <script type="text/javascript">imgTp1.addTabPage( document.getElementById( "new_file" ) );</script>
                            <span class= "main"><strong><?php echo TEXT_UPLOAD_PRODUCTS_IMAGE; ?></strong></span>
                            <br><?php echo tep_draw_file_field('products_image') . tep_draw_hidden_field('products_image_previous', $pInfo->products_image); ?>
                            <br>
                            <select name="products_image_destination" class="dirWidth" id="dirPath" ><?php echo $file_dir; ?></select>
                          </div>
                          <div class="tab-page" id="existing_file">
                            <input type="hidden" name="file_exist_dest_small" id="file_exist_dest_small" />
                            <h2 class="tab"><?php echo TEXT_TAB_PANE_EXISTING_FILE; ?></h2>
                            <script type="text/javascript">imgTp1.addTabPage( document.getElementById( "existing_file" ) );</script>
                            <span class= "main"><strong><?php echo TEXT_CATEGORIES_IMAGE_DIR; ?></strong><select name="file_dir_exist_dest_small" class="dirWidth" id="file_dir_exist_dest_small" onChange="listFile(this, 'img_file_preview_small', 'img_file_small', 'file_exist_dest_small');"><?php echo $file_dir; ?></select></span>
                            <div id="img_file_small" class="main"><b><?php echo TEXT_CATEGORIES_IMAGE_FILE; ?></b><select name="file_exist_dest_select" class="dirWidth" id="file_exist_dest_select" onChange="previewFile(this, 'img_file_preview_small', 'file_exist_dest_small', 'file_dir_exist_dest_small');"><?php echo $file_list; ?></select></div>
                            <div align="right"><img id="img_file_preview_small" src="" /></div>
                          </div>
                        </div>
                      </td>
                      <td class="dataTableRow" valign="top">
                        <span class="smallText">
                          <?php if (($pID) && ($pInfo->products_image) != '')
                          echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . $pInfo->products_image . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $pInfo->products_image, $pInfo->products_image, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?>
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <td class="dataTableRow" valign="top">
                      <?php if ($parentid["products_parent_id"] == 0) { ?>
                        <span class="main"><?php echo TEXT_PRODUCTS_IMAGE_MEDIUM; ?></span>
                      <?php } else { ?>
                        <span class="main"><?php echo TEXT_PRODUCTS_IMAGE_MEDIUM_BORDER; ?></span>
                      <?php } ?>
                        <br><br>
                        <div class="tab-pane" id="imgTabPane1">
                          <script type="text/javascript">
                            imgTp2 = new WebFXTabPane( document.getElementById( "imgTabPane1" ) );
                          </script>
                          <div class="tab-page" id="new_file_med">
                            <h2 class="tab"><?php echo TEXT_TAB_PANE_NEW_FILE; ?></h2>
                            <script type="text/javascript">imgTp2.addTabPage( document.getElementById( "new_file_med" ) );</script>
                            <span class= "main"><strong><?php echo TEXT_UPLOAD_PRODUCTS_IMAGE; ?></strong></span>
                            <br>
                        <?php echo tep_draw_file_field('products_image_med') . tep_draw_hidden_field('products_image_med_previous', $pInfo->products_image_med); ?>
                            <br>
                            <select name="products_image_med_destination" class="dirWidth" id="dirPath" ><?php echo $file_dir; ?></select>
                          </div>
                          <div class="tab-page" id="existing_file_med">
                            <input type="hidden" name="file_exist_dest_med" id="file_exist_dest_med" />
                            <h2 class="tab"><?php echo TEXT_TAB_PANE_EXISTING_FILE; ?></h2>
                            <script type="text/javascript">imgTp2.addTabPage( document.getElementById( "existing_file_med" ) );</script>
                            <span class= "main"><strong><?php echo TEXT_CATEGORIES_IMAGE_DIR; ?></strong><select name="file_dir_exist_dest_med" class="dirWidth" id="file_dir_exist_dest_med" onChange="listFile(this, 'img_file_preview_med', 'img_file_med', 'file_exist_dest_med');"><?php echo $file_dir; ?></select></span>
                            <div id="img_file_med" class="main"><b><?php echo TEXT_CATEGORIES_IMAGE_FILE; ?></b><select name="img_file_preview_med_select" class="dirWidth" id="img_file_preview_med_select" onChange="previewFile(this, 'img_file_preview_med', 'file_exist_dest_med', 'file_dir_exist_dest_med');"><?php echo $file_list; ?></select></div>
                            <div align="right"><img id="img_file_preview_med" src="" /></div>
                          </div>
                        </div>
                      </td>
                      <td class="dataTableRow" valign="top">
                        <span class="smallText">
                          <?php if (($pID) && ($pInfo->products_image_med) != '')
                          echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . $pInfo->products_image_med . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $pInfo->products_image_med, $pInfo->products_image_med, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_med" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_med" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?>
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <td class="dataTableRow" valign="top">
                      <?php if ($parentid["products_parent_id"] == 0) { ?>
                        <span class="main"><?php echo TEXT_PRODUCTS_IMAGE_LARGE; ?></span>
                      <?php } else { ?>
                        <span class="main"><?php echo TEXT_PRODUCTS_IMAGE_LARGE_BORDER; ?></span>
                      <?php } ?>
                        <br><br>
                        <div class="tab-pane" id="imgTabPane1">
                          <script type="text/javascript">
                            imgTp3 = new WebFXTabPane( document.getElementById( "imgTabPane1" ) );
                          </script>
                          <div class="tab-page" id="new_file_lrg">
                            <h2 class="tab"><?php echo TEXT_TAB_PANE_NEW_FILE; ?></h2>
                            <script type="text/javascript">imgTp3.addTabPage( document.getElementById( "new_file_lrg" ) );</script>
                            <span class= "main"><strong><?php echo TEXT_UPLOAD_PRODUCTS_IMAGE; ?></strong></span>
                            <br>
                            <?php echo tep_draw_file_field('products_image_lrg') . tep_draw_hidden_field('products_image_lrg_previous', $pInfo->products_image_lrg); ?>
                            <br>
                            <select name="products_image_lrg_destination" class="dirWidth" id="dirPath" ><?php echo $file_dir; ?></select>
                          </div>
                          <div class="tab-page" id="existing_file_lrg">
                            <input type="hidden" name="file_exist_dest_lrg" id="file_exist_dest_lrg" />
                            <h2 class="tab"><?php echo TEXT_TAB_PANE_EXISTING_FILE; ?></h2>
                            <script type="text/javascript">imgTp3.addTabPage( document.getElementById( "existing_file_lrg" ) );</script>
                            <span class= "main"><strong><?php echo TEXT_CATEGORIES_IMAGE_DIR; ?></strong><select name="file_dir_exist_dest_lrg" class="dirWidth" id="file_dir_exist_dest_lrg" onChange="listFile(this, 'img_file_preview_lrg', 'img_file_lrg', 'file_exist_dest_lrg');"><?php echo $file_dir; ?></select></span>
                            <div id="img_file_lrg" class="main"><b><?php echo TEXT_CATEGORIES_IMAGE_FILE; ?></b><select name="img_file_preview_lrg_select" class="dirWidth" id="img_file_preview_lrg_select" onChange="previewFile(this, 'img_file_preview_lrg', 'file_exist_dest_lrg', 'file_dir_exist_dest_lrg');"><?php echo $file_list; ?></select></div>
                            <div align="right"><img id="img_file_preview_lrg" src="" /></div>
                          </div>
                        </div>
                      </td>
                      <td class="dataTableRow" valign="top">
                        <span class="smallText">
                          <?php if (($pID) && ($pInfo->products_image_lrg) != '')
                          echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . $pInfo->products_image_lrg . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $pInfo->products_image_lrg, $pInfo->products_image_lrg, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_lrg" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_lrg" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?>
                        </span>
                      </td>
                    </tr>
                    <?php if ($parentid["products_parent_id"]) { ?>
                    <tr>
                      <td class="dataTableRow" valign="top">
                        <span class="main"><?php echo TEXT_PRODUCTS_IMAGE_REAL_IMAGE2; ?></span>
                        <br><br>
                        <div class="tab-pane" id="imgTabPane1">
                          <script type="text/javascript">
                            imgTp3 = new WebFXTabPane( document.getElementById( "imgTabPane1" ) );
                          </script>
                          <div class="tab-page" id="new_file_rls2">
                            <h2 class="tab"><?php echo TEXT_TAB_PANE_NEW_FILE; ?></h2>
                            <script type="text/javascript">imgTp3.addTabPage( document.getElementById( "new_file_rls2" ) );</script>
                            <span class= "main"><strong><?php echo TEXT_UPLOAD_PRODUCTS_IMAGE; ?></strong></span>
                            <br>
                            <?php echo tep_draw_file_field('products_image_rls2') . tep_draw_hidden_field('products_image_rls2_previous', $pInfo->products_image_rls2); ?>
                            <br>
                            <select name="products_image_rls2_destination" class="dirWidth" id="dirPath" ><?php echo $file_dir; ?></select>
                          </div>
                          <div class="tab-page" id="existing_file_rls2">
                            <input type="hidden" name="file_exist_dest_rls2" id="file_exist_dest_rls2" />
                            <h2 class="tab"><?php echo TEXT_TAB_PANE_EXISTING_FILE; ?></h2>
                            <script type="text/javascript">imgTp3.addTabPage( document.getElementById( "existing_file_rls2" ) );</script>
                            <span class= "main"><strong><?php echo TEXT_CATEGORIES_IMAGE_DIR; ?></strong><select name="file_dir_exist_dest_rls2" class="dirWidth" id="file_dir_exist_dest_rls2" onChange="listFile(this, 'img_file_preview_rls2', 'img_file_rls2', 'file_exist_dest_rls2');"><?php echo $file_dir; ?></select></span>
                            <div id="img_file_rls2" class="main"><b><?php echo TEXT_CATEGORIES_IMAGE_FILE; ?></b><select name="img_file_preview_rls2_select" class="dirWidth" id="img_file_preview_rls2_select" onChange="previewFile(this, 'img_file_preview_rls2', 'file_exist_dest_rls2', 'file_dir_exist_dest_rls2');"><?php echo $file_list; ?></select></div>
                            <div align="right"><img id="img_file_preview_rls2" src="" /></div>
                          </div>
                        </div>
                      </td>
                      <td class="dataTableRow" valign="top">
                        <span class="smallText">
                          <?php if (($pID) && ($pInfo->products_image_rls2) != '')
                          echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . $pInfo->products_image_rls2 . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $pInfo->products_image_rls2, $pInfo->products_image_rls2, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_rls2" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_rls2" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?>
                        </span>
                      </td>
                    </tr>
                    <?php } ?>
                  </table>
                </fieldset>
              </td>
            </tr>
            <?php
          } // RCO eof fieldsetimages
          ?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>  
          <?php
          // RCO start fieldsetaddimages
          if ($cre_RCO->get('categories', 'fieldsetaddimages') !== true) { 
            if ($parentid["products_parent_id"] == 0) {
            if (ULTIMATE_ADDITIONAL_IMAGES == 'enable') {
              ?>
              <tr>       
                <td class="main" colspan="2"><fieldset><legend><?php echo TEXT_PRODUCTS_IMAGE_ADDITIONAL;?></legend>
                  <table border="0" cellpadding="2" cellspacing="0" width="100%">
                    <tr>
                      <td class="smalltext"><table border="0" cellpadding="2" cellspacing="0" width="100%">
                        <tr>
                          <td class="smalltext" colspan="2" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_TH_NOTICE; ?></td>
                          <td class="smalltext" colspan="2" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_XL_NOTICE; ?></td>
                        </tr>
                      </table>
                      <table border="0" cellpadding="2" cellspacing="0" width="100%">
                        <tr>
                          <td class="dataTableRow" valign="top"><span class="smallText"><?php echo TEXT_PRODUCTS_IMAGE_SM_1; ?></span></td>
                          <td class="dataTableRow" valign="top"><span class="smallText">
                            <?php echo tep_draw_file_field('products_image_sm_1') . tep_draw_hidden_field('products_image_sm_1_previous', $pInfo->products_image_sm_1); ?>
                            <br>
                            <select name="products_image_sm_1_destination" class="dirWidth" id="dirPath" ><?php echo $file_dir; ?></select>
                            </span></td>
                          <td class="dataTableRow" valign="top"><span class="smallText"><?php echo TEXT_PRODUCTS_IMAGE_XL_1; ?></span></td>
                          <td class="dataTableRow" valign="top"><span class="smallText">
                            <?php echo tep_draw_file_field('products_image_xl_1') . tep_draw_hidden_field('products_image_xl_1_previous', $pInfo->products_image_xl_1); ?>
                            <br>
                            <select name="products_image_xl_1_destination" class="dirWidth" id="dirPath" ><?php echo $file_dir; ?></select>
                            </span></td>
                        </tr>
                        <?php
                        if (($pID) && ($pInfo->products_image_sm_1) != '' or ($pInfo->products_image_xl_1) != '') {
                          ?>
                          <tr>
                            <td class="dataTableRow" colspan="2" valign="top"><?php if (tep_not_null($pInfo->products_image_sm_1)) { ?>
                              <span class="smallText"><?php echo $pInfo->products_image_sm_1 . '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $pInfo->products_image_sm_1, $pInfo->products_image_sm_1, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_sm_1" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_sm_1" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span>
                              <?php } ?>
                            </td>
                            <td class="dataTableRow" colspan="2" valign="top"><?php if (tep_not_null($pInfo->products_image_xl_1)) { ?>
                              <span class="smallText"><?php echo $pInfo->products_image_xl_1 . '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_1, $pInfo->products_image_xl_1, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_xl_1" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_xl_1" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span>
                              <?php } ?>
                            </td>
                          </tr>
                          <?php
                        }
                        ?>
                        <tr>
                          <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_SM_2; ?></td>
                          <td class="smallText" valign="top">
                            <?php echo tep_draw_file_field('products_image_sm_2') . tep_draw_hidden_field('products_image_sm_2_previous', $pInfo->products_image_sm_2); ?>
                            <br>
                            <select name="products_image_sm_2_destination" class="dirWidth" id="dirPath" ><?php echo $file_dir; ?></select>
                            </td>
                          <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_XL_2; ?></td>
                          <td class="smallText" valign="top">
                            <?php echo tep_draw_file_field('products_image_xl_2') . tep_draw_hidden_field('products_image_xl_2_previous', $pInfo->products_image_xl_2); ?>
                            <br>
                            <select name="products_image_xl_2_destination" class="dirWidth" id="dirPath" ><?php echo $file_dir; ?></select>
                            </td>
                        </tr>
                        <?php
                        if (($pID) && ($pInfo->products_image_sm_2) != '' or ($pInfo->products_image_xl_2) != '') {
                          ?>
                          <tr>
                            <td class="smallText" valign="top" colspan="2"><?php if (tep_not_null($pInfo->products_image_sm_2)) { ?>
                              <?php echo $pInfo->products_image_sm_2 . '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $pInfo->products_image_sm_2, '', SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_sm_2" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_sm_2" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?>
                              <?php } ?>
                            </td>
                            <td class="smallText" valign="top" colspan="2"><?php if (tep_not_null($pInfo->products_image_xl_2)) { ?>
                              <?php echo $pInfo->products_image_xl_2 . '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_2, '', SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_xl_2" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_xl_2" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?>
                              <?php } ?>
                            </td>
                          </tr>
                          <?php
                        }
                        ?>
                        <tr>
                          <td class="dataTableRow" valign="top"><span class="smallText"><?php echo TEXT_PRODUCTS_IMAGE_SM_3; ?></span></td>
                          <td class="dataTableRow" valign="top"><span class="smallText">
                            <?php echo tep_draw_file_field('products_image_sm_3') . tep_draw_hidden_field('products_image_sm_3_previous', $pInfo->products_image_sm_3); ?>
                            <br>
                            <select name="products_image_sm_3_destination" class="dirWidth" id="dirPath" ><?php echo $file_dir; ?></select>
                            </span></td>
                          <td class="dataTableRow" valign="top"><span class="smallText"><?php echo TEXT_PRODUCTS_IMAGE_XL_3; ?></span></td>
                          <td class="dataTableRow" valign="top"><span class="smallText">
                            <?php echo tep_draw_file_field('products_image_xl_3') . tep_draw_hidden_field('products_image_xl_3_previous', $pInfo->products_image_xl_3); ?>
                            <br>
                            <select name="products_image_xl_3_destination" class="dirWidth" id="dirPath" ><?php echo $file_dir; ?></select>
                            </span></td>
                        </tr>
                        <?php
                        if (($pID) && ($pInfo->products_image_sm_3) != '' or ($pInfo->products_image_xl_3) != '') {
                          ?>
                          <tr>
                            <td class="dataTableRow" colspan="2" valign="top"><?php if (tep_not_null($pInfo->products_image_sm_3)) { ?>
                              <span class="smallText"><?php echo $pInfo->products_image_sm_3 . '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $pInfo->products_image_sm_3, '', SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_sm_3" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_sm_3" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span>
                              <?php } ?>
                            </td>
                            <td class="dataTableRow" colspan="2" valign="top"><?php if (tep_not_null($pInfo->products_image_xl_3)) { ?>
                              <span class="smallText"><?php echo $pInfo->products_image_xl_3 . '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_3, '', SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_xl_3" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_xl_3" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span>
                              <?php } ?>
                            </td>
                          </tr>
                          <?php
                        }
                        ?>
                        <tr>
                          <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_SM_4; ?></td>
                          <td class="smallText" valign="top">
                            <?php echo tep_draw_file_field('products_image_sm_4') . tep_draw_hidden_field('products_image_sm_4_previous', $pInfo->products_image_sm_4); ?>
                            <br>
                            <select name="products_image_sm_4_destination" class="dirWidth" id="dirPath" ><?php echo $file_dir; ?></select>
                            </td>
                          <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_XL_4; ?></td>
                          <td class="smallText" valign="top">
                            <?php echo tep_draw_file_field('products_image_xl_4') . tep_draw_hidden_field('products_image_xl_4_previous', $pInfo->products_image_xl_4); ?>
                            <br>
                            <select name="products_image_xl_4_destination" class="dirWidth" id="dirPath" ><?php echo $file_dir; ?></select>
                            </td>
                        </tr>
                        <?php
                        if (($pID) && ($pInfo->products_image_sm_4) != '' or ($pInfo->products_image_xl_4) != '') {
                          ?>
                          <tr>
                            <td class="smallText" valign="top" colspan="2"><?php if (tep_not_null($pInfo->products_image_sm_4)) { ?>
                              <?php echo $pInfo->products_image_sm_4 . '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $pInfo->products_image_sm_4, '', SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_sm_4" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_sm_4" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?>
                              <?php } ?>
                            </td>
                            <td class="smallText" valign="top" colspan="2"><?php if (tep_not_null($pInfo->products_image_xl_4)) { ?>
                              <?php echo $pInfo->products_image_xl_4 . '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_4, '', SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_xl_4" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_xl_4" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?>
                              <?php } ?>
                            </td>
                          </tr>
                          <?php
                        }
                        ?>
                        <tr>
                          <td class="dataTableRow" valign="top"><span class="smallText"><?php echo TEXT_PRODUCTS_IMAGE_SM_5; ?></span></td>
                          <td class="dataTableRow" valign="top"><span class="smallText">
                            <?php echo tep_draw_file_field('products_image_sm_5') . tep_draw_hidden_field('products_image_sm_5_previous', $pInfo->products_image_sm_5); ?>
                            <br>
                            <select name="products_image_sm_5_destination" class="dirWidth" id="dirPath" ><?php echo $file_dir; ?></select>
                            </span></td>
                          <td class="dataTableRow" valign="top"><span class="smallText"><?php echo TEXT_PRODUCTS_IMAGE_XL_5; ?></span></td>
                          <td class="dataTableRow" valign="top"><span class="smallText">
                            <?php echo tep_draw_file_field('products_image_xl_5') . tep_draw_hidden_field('products_image_xl_5_previous', $pInfo->products_image_xl_5); ?>
                            <br>
                            <select name="products_image_xl_5_destination" class="dirWidth" id="dirPath" ><?php echo $file_dir; ?></select>
                            </span></td>
                        </tr>
                        <?php
                        if (($pID) && ($pInfo->products_image_sm_5) != '' or ($pInfo->products_image_xl_5) != '') {
                          ?>
                          <tr>
                            <td class="dataTableRow" colspan="2" valign="top"><?php if (tep_not_null($pInfo->products_image_sm_5)) { ?>
                              <span class="smallText"><?php echo $pInfo->products_image_sm_5 . '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $pInfo->products_image_sm_5, '', SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_sm_5" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_sm_5" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span>
                              <?php } ?>
                            </td>
                            <td class="dataTableRow" colspan="2" valign="top"><?php if (tep_not_null($pInfo->products_image_xl_5)) { ?>
                              <span class="smallText"><?php echo $pInfo->products_image_xl_5 . '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_5, '', SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_xl_5" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_xl_5" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span>
                              <?php } ?>
                            </td>
                          </tr>
                          <?php
                        }
                        ?>
                        <tr>
                          <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_SM_6; ?></td>
                          <td class="smalltext" valign="top">
                            <?php echo tep_draw_file_field('products_image_sm_6') . tep_draw_hidden_field('products_image_sm_6_previous', $pInfo->products_image_sm_6); ?>
                            <br>
                            <select name="products_image_sm_6_destination" class="dirWidth" id="dirPath" ><?php echo $file_dir; ?></select>
                            </td>
                          <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_XL_6; ?></td>
                          <td class="smalltext" valign="top">
                            <?php echo tep_draw_file_field('products_image_xl_6') . tep_draw_hidden_field('products_image_xl_6_previous', $pInfo->products_image_xl_6); ?>
                            <br>
                            <select name="products_image_xl_6_destination" class="dirWidth" id="dirPath" ><?php echo $file_dir; ?></select>
                            </td>
                        </tr>
                        <?php
                        if (($pID) && ($pInfo->products_image_sm_6) != '' or ($pInfo->products_image_xl_6) != '') {
                          ?>
                          <tr>
                            <td class="smallText" valign="top" colspan="2"><?php if (tep_not_null($pInfo->products_image_sm_6)) { ?>
                              <?php echo $pInfo->products_image_sm_6 . '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $pInfo->products_image_sm_6, '', SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_sm_6" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_sm_6" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?>
                              <?php } ?>
                            </td>
                            <td class="smallText" valign="top" colspan="2"><?php if (tep_not_null($pInfo->products_image_xl_6)) { ?>
                              <?php echo $pInfo->products_image_xl_6 . '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $pInfo->products_image_xl_6, '', SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_xl_6" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_xl_6" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?>
                              <?php } ?>
                            </td>
                          </tr>
                          <?php
                        }
                        ?>
                      </table></td>
                    </tr>
                  </table>
                </fieldset></td>
              </tr>
              <?php
            } else {
              echo tep_draw_hidden_field('products_image_sm_1_previous', $pInfo->products_image_sm_1) .
              tep_draw_hidden_field('products_image_xl_1_previous', $pInfo->products_image_xl_1) .
              tep_draw_hidden_field('products_image_sm_2_previous', $pInfo->products_image_sm_2) .
              tep_draw_hidden_field('products_image_xl_2_previous', $pInfo->products_image_xl_2) .
              tep_draw_hidden_field('products_image_sm_3_previous', $pInfo->products_image_sm_3) .
              tep_draw_hidden_field('products_image_xl_3_previous', $pInfo->products_image_xl_3) .
              tep_draw_hidden_field('products_image_sm_4_previous', $pInfo->products_image_sm_4) .
              tep_draw_hidden_field('products_image_xl_4_previous', $pInfo->products_image_xl_4) .
              tep_draw_hidden_field('products_image_sm_5_previous', $pInfo->products_image_sm_5) .
              tep_draw_hidden_field('products_image_xl_5_previous', $pInfo->products_image_xl_5) .
              tep_draw_hidden_field('products_image_sm_6_previous', $pInfo->products_image_sm_6) .
              tep_draw_hidden_field('products_image_xl_6_previous', $pInfo->products_image_xl_6);
            }
           }
          } // RCO eof fieldsetaddimages
          ?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>
          <?php
          // RCO start fieldsetsubprods
          if ($cre_RCO->get('categories', 'fieldsetsubprods') !== true) { 
            ?>    
            <!-- Beginning of sub products div as well as looking its products up -->
            <tr>     
              <td colspan="2" class="main">
                <?php
                $parentid_query = tep_db_query("select products_parent_id from products  where products_id =".(int)$pID);
                $parentid = tep_db_fetch_array($parentid_query);
                if ($parentid['products_parent_id'] == 0) {
                  ?>
                  <fieldset><legend><?php echo TEXT_SUB_PRODUCT; ?></legend>
                    <table cellpadding="2" width="100%">
                      <tr>
                        <td><table>
                          <tr>
                            <td class="main"><?php echo TEXT_SUB_PRODUCT_DELETE; ?></td>
                            <td class="main"><?php echo TEXT_SUB_PRODUCT_NAME; ?></td>
                            <!--<td class="main"><?php echo TEXT_SUB_PRODUCT_PRICE; ?></td>
                            <td class="main"><?php echo TEXT_SUB_PRODUCT_MODEL; ?></td>
                            <td class="main"><?php echo TEXT_SUB_PRODUCT_QTY; ?></td>
                            <td class="main"><?php echo TEXT_SUB_PRODUCT_WEIGHT; ?></td>-->
                            <td class="main"><?php echo TEXT_SUB_PRODUCT_IMAGE; ?></td>
                          </tr>
                          <?php
                          $parent_name = tep_get_products_name($pInfo->products_id, $languages_id);
                          $additionalcounter = 0;
                          if ( (isset($pID)) && ($pID >= '1') ) {
                            $tempcheckproductid = $pID;
                            $subscount_query = tep_db_query("select count(p.products_id) as total from products p , products_description pd where pd.products_id = p.products_id and pd.language_id = " . (int)$languages_id . " and p.products_parent_id =".$tempcheckproductid);
                            $subscount = tep_db_fetch_array($subscount_query);
                            $totalsubs = 0;
                            if ($subscount["total"] > 0) {
                              $totalsubs = (int)$subscount["total"];
                            }
                            if ($totalsubs > 0) {
                              $currentsubs_query = tep_db_query("select p.products_image, p.products_id, pd.products_name, pd.products_description, p.products_price, p.products_model, p.products_quantity, p.products_weight from products p, products_description pd where pd.products_id = p.products_id and pd.language_id = " . (int)$languages_id . " and p.products_parent_id =".$tempcheckproductid);
                              while ($currentsubs = tep_db_fetch_array($currentsubs_query)) {
                                $subname = substr( $currentsubs["products_name"], strlen( $parent_name . ' - ' ));
                                echo "<tr>";
                                echo '<td class="main">'.tep_draw_checkbox_field('subproddelete[]', $currentsubs["products_id"]).'</td>';
                                echo '<td class="main">'.tep_draw_input_field('subprodname[]',$subname).'</td>';
                                /*echo '<td class="main">'.tep_draw_input_field('subprodprice[]',$currentsubs["products_price"],'size="5"').'</td>';
                                echo '<td class="main">'.tep_draw_input_field('subprodmodel[]',$currentsubs["products_model"]).'</td>';
                                echo '<td class="main">'.tep_draw_input_field('subprodqty[]',$currentsubs["products_quantity"],'size="4"').'</td>';
                                echo '<td class="main">'.tep_draw_input_field('subprodweight[]',$currentsubs["products_weight"],'size="5"').'</td>';*/
                                echo '<td class="main">'.tep_draw_file_field('subprodimage[]').'<br>'.$currentsubs["products_image"].tep_draw_hidden_field('subprodprevimage[]', $currentsubs["products_image"]).'</td>';
                                echo '<td class="main">'.tep_draw_hidden_field('subproductid[]',$currentsubs["products_id"]).'</td>';
                                echo '<td class="main" align="left">'.'<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($pID) ? '&action=new_product&pID=' . $currentsubs["products_id"] : '')) . '">' . tep_image_button('button_edit_sm.gif', IMAGE_EDIT) . '</a>';
                                //RCI start
                                echo $cre_RCI->get('categories', 'subproducts');
                                //RCI eof
                                echo '</td>';
                                echo "</tr>";
                                $additionalcounter = $additionalcounter + 1;
                              }
                            }
                          }
                          $temptotalboxes = (int)MAX_PRODUCT_SUB_ROWS + (int)$additionalcounter;
                          while ($additionalcounter < $temptotalboxes) {
                            // This is where we display the number of sub products that they want to add by default to the main product
                            echo "<tr>";
                            echo '<td class="main">'.tep_draw_checkbox_field('subproddelete','').'</td>';
                            echo '<td class="main">'.tep_draw_input_field('subprodname[]',(isset($_POST['subprodname'][$additionalcounter])? $_POST['subprodname'][$additionalcounter] : '')).'</td>';
                            /*echo '<td class="main">'.tep_draw_input_field('subprodprice[]',(isset($_POST['subprodprice'][$additionalcounter])? $_POST['subprodprice'][$additionalcounter] : ''),'size="5"').'</td>';
                            echo '<td class="main">'.tep_draw_input_field('subprodmodel[]',(isset($_POST['subprodmodel'][$additionalcounter])? $_POST['subprodmodel'][$additionalcounter] : '')).'</td>';
                            echo '<td class="main">'.tep_draw_input_field('subprodqty[]',(isset($_POST['subprodqty'][$additionalcounter])? $_POST['subprodqty'][$additionalcounter] : ''),'size="4"').'</td>';
                            echo '<td class="main">'.tep_draw_input_field('subprodweight[]',(isset($_POST['subprodweight'][$additionalcounter])? $_POST['subprodweight'][$additionalcounter] : ''),'size="5"').'</td>';*/
                            echo '<td class="main">'.tep_draw_file_field('subprodimage[]').tep_draw_hidden_field('subprodprevimage[]', '').'</td>';
                            echo '<td class="main">'.tep_draw_hidden_field('subproductid[]','0').'</td>';
                            echo '<td class="main" align="left" valign="top"></td>';
                            echo "</tr>";
                            $additionalcounter = $additionalcounter + 1;
                          }
                          ?>
                        </table></td>
                      </tr>
                      <tr>
                        <td colspan="7"><blockquote><?php echo TEXT_SUB_PRODUCT_NOTE_1 . '<br>' , TEXT_SUB_PRODUCT_NOTE_2; ?></blockquote></td>
                      </tr>
                    </table>
                  </fieldset>
                  <?php
                } else {
                  ?>
                  <fieldset><legend><?php echo TEXT_SUB_PRODUCT; ?></legend>
                    <table cellpadding="0" cellpadding="2" width="100%">
                      <tr>
                        <td>
                          <?php echo  TEXT_PARENT_PRODUCT_WARNING.'<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($pID) ? '&action=new_product&pID=' . $parentid["products_parent_id"] : '')) . '">' . TEXT_PARENT_PRODUCT_WARNING2 . '</a>'.TEXT_PARENT_PRODUCT_WARNING3;?>
                        </td>
                      </tr>
                    </table>
                  </fieldset>
                  <?php 
                }
                ?>
              </td>
            </tr>
            <?php
          } // RCO eof fieldsetsubprods
          ?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>
          <?php
          // RCO start fieldsetextra
          if ($cre_RCO->get('categories', 'fieldsetextra') !== true) { 
            if ($parentid['products_parent_id'] == 0) {
            ?>     
            <tr>     
              <td colspan="2">    
                <fieldset><legend>Extra Fields</legend>
                  <table cellpadding="0" cellpadding="2" width="100%">
                    <tr>
                      <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>
                    <tr>
                      <td class="main"><table>
                        <!-- End of sub products div as well as looking its products up -->
                        <?php
                        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                          $languages_array[$languages[$i]['id']]=$languages[$i];
                        }
                        $extra_fields_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_EXTRA_FIELDS . " ORDER BY products_extra_fields_order");
                        while ($extra_fields = tep_db_fetch_array($extra_fields_query)) {
                          // display language icon or blank space
                          if ($extra_fields['languages_id'] == 0) {
                            $m=tep_draw_separator('pixel_trans.gif', '24', '15');
                          } else {
                            $m= tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages_array[$extra_fields['languages_id']]['directory'] . '/images/' . $languages_array[$extra_fields['languages_id']]['image'], $languages_array[$extra_fields['languages_id']]['name']);
                          }
                          ?>
                          <tr bgcolor="#ebebff">
                            <td class="main"><?php echo $extra_fields['products_extra_fields_name']; ?>:</td>
                            <td class="main">
                              <?php
                              if (isset($pInfo->extra_field)) {
                                echo $m . '&nbsp;' . tep_draw_input_field("extra_field[".$extra_fields['products_extra_fields_id']."]", (isset($pInfo->extra_field[$extra_fields['products_extra_fields_id']]) ? $pInfo->extra_field[$extra_fields['products_extra_fields_id']] : ''));
                              } else {
                                echo $m . '&nbsp;' . tep_draw_input_field("extra_field[".$extra_fields['products_extra_fields_id']."]", '');
                              }
                              ?>
                            </td>
                          </tr>
                          <?php
                        }
                        ?>
                      </table></td>
                    </tr>
                    <tr>
                      <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>
                  </table>
                </fieldset>
              </td>
            </tr>
            <?php
            }
          } // RCO eof fieldsetextra
          ?>
            <tr>     
              <td colspan="2">    
                <fieldset><legend>Default texts</legend>
                  <table cellpadding="0" cellpadding="2" width="100%">
                    <tr>
                      <td class="main"><?php echo tep_draw_textarea_field('default_texts', 'soft', '140', '15', $pInfo->default_texts); ?></td>
                    </tr>
                  </table>
                </fieldset>
              </td>
            </tr>
        </table>  
        <table cellpadding="0" cellpadding="2" width="100%">
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td valign="top" align="right"> <table align="right" border="0">
              <tr>
                <td class="main" align="right">
                  <?php
                  echo tep_draw_hidden_field('products_date_added', (tep_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d')));
                  echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($pID) ? '&pID=' . $pID : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
                  if ($parentid['products_parent_id'] == 0) {
                    // RCO start
                    if ($cre_RCO->get('categories', 'buttonattributes') !== true) { 
                      echo '<a href="' . tep_href_link(FILENAME_ATTRIBUTES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_edit_attributes.gif', IMAGE_EDIT_ATTRIBUTES) . '</a>';
                    }
                    // RCO eof
                  }
                  echo tep_image_submit('button_preview_upload.gif', 'Preview','name="Operation" onClick="document.pressed=this.value"');
                  echo tep_image_submit('button_'.$form_action_text.'.gif',$form_action_text,'name="Operation" onClick="document.pressed=this.value"');
                  ?>
                </td>
              </tr>
            </table></td>
          </tr>
        </table>
        </form>
        <?php
        // new product preview begin
      } elseif ($action == 'new_product_preview') {
        if ( (isset($_POST)) && (tep_not_null($_POST))) {
          $pInfo = new objectInfo($_POST);
          $products_name = $_POST['products_name'];
          $products_blurb = $_POST['products_blurb']; 
          $products_description = $_POST['products_description'];
          $products_head_title_tag = $_POST['products_head_title_tag'];
          $products_head_desc_tag = $_POST['products_head_desc_tag'];
          $products_head_keywords_tag = $_POST['products_head_keywords_tag'];
          $products_url = $_POST['products_url'];
          $products_image_destination = str_ireplace( "%2F", "/", $_POST['products_image_destination']);
          $products_image_med_destination = str_ireplace( "%2F", "/", $_POST['products_image_med_destination']);
          $products_image_lrg_destination = str_ireplace( "%2F", "/", $_POST['products_image_lrg_destination']);
          $products_image_rls2_destination = str_ireplace( "%2F", "/", $_POST['products_image_rls2_destination']);
          $products_image_sm_1_destination = str_ireplace( "%2F", "/", $_POST['products_image_sm_1_destination']);
          $products_image_sm_2_destination = str_ireplace( "%2F", "/", $_POST['products_image_sm_2_destination']);
          $products_image_sm_3_destination = str_ireplace( "%2F", "/", $_POST['products_image_sm_3_destination']);
          $products_image_sm_4_destination = str_ireplace( "%2F", "/", $_POST['products_image_sm_4_destination']);
          $products_image_sm_5_destination = str_ireplace( "%2F", "/", $_POST['products_image_sm_5_destination']);
          $products_image_sm_6_destination = str_ireplace( "%2F", "/", $_POST['products_image_sm_6_destination']);
          $products_image_xl_1_destination = str_ireplace( "%2F", "/", $_POST['products_image_xl_1_destination']);
          $products_image_xl_2_destination = str_ireplace( "%2F", "/", $_POST['products_image_xl_2_destination']);
          $products_image_xl_3_destination = str_ireplace( "%2F", "/", $_POST['products_image_xl_3_destination']);
          $products_image_xl_4_destination = str_ireplace( "%2F", "/", $_POST['products_image_xl_4_destination']);
          $products_image_xl_5_destination = str_ireplace( "%2F", "/", $_POST['products_image_xl_5_destination']);
          $products_image_xl_6_destination = str_ireplace( "%2F", "/", $_POST['products_image_xl_6_destination']);
       } else {
	   		//Mar 03 2011 - modified to add p.products_min_order_qty,
          $product_query = tep_db_query("select p.products_id, pd.language_id, pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_url, p.products_quantity, p.products_min_order_qty, p.overall_quantity, p.progressed_quantity, p.labour_cost, p.overhead_cost, p.material_cost, p.products_model, p.products_image, p.products_image_med, p.products_image_lrg, p.products_image_rls2, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, p.products_price, p.products_price1, p.products_price2, p.products_price3, p.products_price4, p.products_price5, p.products_price6, p.products_price7, p.products_price8, p.products_price9, p.products_price10, p.products_price11, p.products_price1_qty, p.products_price2_qty, p.products_price3_qty, p.products_price4_qty, p.products_price5_qty, p.products_price1_qty, p.products_price7_qty, p.products_price8_qty, p.products_price9_qty, p.products_price10_qty, products_price11_qty, p.products_qty_blocks, p.products_weight, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.manufacturers_id  from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and p.products_id = '" . (int)$pID . "'");
          $product = tep_db_fetch_array($product_query);
          $pInfo = new objectInfo($product);
          $products_image_destination ='';
          $products_image_med_destination = '';
          $products_image_lrg_destination = '';
          $products_image_rls2_destination = '';
          $products_image_sm_1_destination = '';
          $products_image_sm_2_destination = '';
          $products_image_sm_3_destination = '';
          $products_image_sm_4_destination ='';
          $products_image_sm_5_destination = '';
          $products_image_sm_6_destination = '';
          $products_image_xl_1_destination = '';
          $products_image_xl_2_destination = '';
          $products_image_xl_3_destination = '';
          $products_image_xl_4_destination = '';
          $products_image_xl_5_destination = '';
          $products_image_xl_6_destination = '';
          $products_image_name = $pInfo->products_image;
          $products_image_med_name =$pInfo->products_image_med;
          $products_image_lrg_name = $pInfo->products_image_lrg;
          $products_image_rls2_name = $pInfo->products_image_rls2;
          $products_image_sm_1_name = $pInfo->products_image_sm_1;
          $products_image_sm_2_name = $pInfo->products_image_sm_2;
          $products_image_sm_3_name = $pInfo->products_image_sm_3;
          $products_image_sm_4_name = $pInfo->products_image_sm_4;
          $products_image_sm_5_name = $pInfo->products_image_sm_5;
          $products_image_sm_6_name = $pInfo->products_image_sm_6;
          $products_image_xl_1_name = $pInfo->products_image_xl_1;
          $products_image_xl_2_name = $pInfo->products_image_xl_2;
          $products_image_xl_3_name = $pInfo->products_image_xl_3;
          $products_image_xl_4_name = $pInfo->products_image_xl_4;
          $products_image_xl_5_name = $pInfo->products_image_xl_5;
          $products_image_xl_6_name = $pInfo->products_image_xl_6;
        }
        $form_action = (!empty($pID)) ? 'update_product' : 'insert_product';
        echo tep_draw_form($form_action, FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($pID) ? '&pID=' . $pID : '') . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');
        $read = '';
        if (isset($_GET['read'])){
          $read = $_GET['read'];
        }
        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          if ($read == 'only') {
            $pInfo->products_name = tep_get_products_name($pInfo->products_id, $languages[$i]['id']);
            $pInfo->products_description = tep_get_products_description($pInfo->products_id, $languages[$i]['id']);
            $pInfo->products_head_title_tag = (isset($products_head_title_tag[$languages[$i]['id']]) ? tep_db_prepare_input($products_head_title_tag[$languages[$i]['id']]) : '');
            $pInfo->products_head_desc_tag = (isset($products_head_desc_tag[$languages[$i]['id']]) ? tep_db_prepare_input($products_head_desc_tag[$languages[$i]['id']]) : '');
            $pInfo->products_head_keywords_tag = (isset($products_head_keywords_tag[$languages[$i]['id']]) ? tep_db_prepare_input($products_head_keywords_tag[$languages[$i]['id']]) : '');
            $pInfo->products_url = tep_get_products_url($pInfo->products_id, $languages[$i]['id']);
          } else {
            $pInfo->products_name = tep_db_prepare_input($products_name[$languages[$i]['id']]);
            $pInfo->products_description = tep_db_prepare_input($products_description[$languages[$i]['id']]);
            $pInfo->products_head_title_tag = tep_db_prepare_input($products_head_title_tag[$languages[$i]['id']]);
            $pInfo->products_head_desc_tag = tep_db_prepare_input($products_head_desc_tag[$languages[$i]['id']]);
            $pInfo->products_head_keywords_tag = tep_db_prepare_input($products_head_keywords_tag[$languages[$i]['id']]);
            $pInfo->products_url = tep_db_prepare_input($products_url[$languages[$i]['id']]);
          }
          ?>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="pageHeading"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $pInfo->products_name; ?></td>
                    <td class="pageHeading" align="right"><?php echo $currencies->format($pInfo->products_price); ?></td>
                  </tr>
                  <?php
                  if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
                    ?>
                    <!-- multi-vendor shipping -->
                    <tr>
                      <td class="pageHeading" align="right"><?php echo TEXT_VENDORS_PRODUCT_PRICE_INFO . '<br>' . $currencies->format($pInfo->vendors_product_price); ?></td>
                    </tr>
                    <!-- multi-vendor shipping //eof -->
                    <?php
                  }
                  ?>
              </table></td>
            </tr>
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
              <td class="main"><?php 
              if ($products_image_med_name) {
                  echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_med_destination . $products_image_med_name, $products_image_med_destination . $products_image_med_name, MEDIUM_IMAGE_WIDTH, MEDIUM_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"'); 
              } elseif ($products_image_lrg_name) {
              ?>
                <script script type="text/javascript">
                  <!--
                  document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_lrg_destination . $products_image_lrg_name) . '\\\')">' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_med_destination . $products_image_name, $products_image_med_destination . $products_image_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . '</a>'; ?>');
                  //-->  
                </script>
                <?php 
              } elseif ($products_image_name) { 
                    echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_name, $products_image_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"');
              }
                echo $pInfo->products_description . '<br><br>';
                
                if ($read == 'only') {
                  $products_extra_fields_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " WHERE products_id=" . (int)$pID);
                  while ($products_extra_fields = tep_db_fetch_array($products_extra_fields_query)) {
                    $extra_fields_array[$products_extra_fields['products_extra_fields_id']] = $products_extra_fields['products_extra_fields_value'];
                  }
                } else {
                  $extra_fields_array = $_POST['extra_field'];
                }
                ?> 
                <br><br><b>Extra Fields</b><br>
                <table border="0" cellspacing="2" cellpadding="2">
                  <?php
                  $extra_fields_names_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_EXTRA_FIELDS. " WHERE languages_id='0' or languages_id='".(int)$languages[$i]['id']."' ORDER BY products_extra_fields_order");
                  while ($extra_fields_names = tep_db_fetch_array($extra_fields_names_query)) {
                    $extra_field_name[$extra_fields_names['products_extra_fields_id']] = $extra_fields_names['products_extra_fields_name'];
                    ?>
                    <tr bgcolor="#F0F9FF">
                      <td class="main" align="right"><b><?php echo (isset($extra_fields_names['products_extra_fields_name']) ? $extra_fields_names['products_extra_fields_name'] : '');?> : </b></td>
                      <td class="main" align="left"><b><?php echo (isset($extra_fields_array[$extra_fields_names['products_extra_fields_id']]) ? $extra_fields_array[$extra_fields_names['products_extra_fields_id']] : '');?></b></td>
                    </tr>
                    <?php
                  }
                  ?>
                </table>
                <br>
              </td>
            </tr>
            <?php
            if ($pInfo->products_url) {
              ?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo sprintf(TEXT_PRODUCT_MORE_INFORMATION, $pInfo->products_url); ?></td>
              </tr>
              <?php
            }
            ?>
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <?php
            if ($pInfo->products_date_available > date('Y-m-d')) {
              ?>
              <tr>
                <td align="center" class="smallText"><?php echo sprintf(TEXT_PRODUCT_DATE_AVAILABLE, tep_date_long($pInfo->products_date_available)); ?></td>
              </tr>
              <?php
            } else {
              ?>
              <tr>
                <td align="center" class="smallText"><?php echo sprintf(TEXT_PRODUCT_DATE_ADDED, tep_date_long($pInfo->products_date_added)); ?></td>
              </tr>
              <?php
            }
            ?>
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
          <?php
        } // end for
          ?>
            <tr>
              <td><fieldset>
                <legend><b><!-- Extra Images --><?php echo TEXT_EXTRA_IMAGES;?></b></legend>
                <?php if ($products_image_xl_1_name) { ?>
                <script script type="text/javascript"><!--
                  document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_sm_1_destination . $products_image_xl_1_name) . '\\\')">' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_1_destination . $products_image_sm_1_name, $products_image_sm_1_destination . $products_image_sm_1_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</a>'; ?>');
                  //--></script>
                <?php } elseif ($products_image_sm_1_name) { ?>
                <?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_1_destination . $products_image_sm_1_name, $products_image_sm_1_destination . $products_image_sm_1_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); }; ?>
                <?php if ($products_image_xl_2_name) { ?>
                <script script type="text/javascript"><!--
                  document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_xl_2_destination . $products_image_xl_2_name) . '\\\')">' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_2_destination . $products_image_sm_2_name, $products_image_sm_2_destination . $products_image_sm_2_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</a>'; ?>');
                  //-->
                </script>
                <?php } elseif ($products_image_sm_2_name) { ?>
                <?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_2_destination . $products_image_sm_2_name, $products_image_sm_2_destination . $products_image_sm_2_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); }; ?>
                <?php if ($products_image_xl_3_name) { ?>
                <script script type="text/javascript"><!--
                  document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_xl_3_destination . $products_image_xl_3_name) . '\\\')">' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_3_destination . $products_image_sm_3_name, $products_image_sm_3_destination . $products_image_sm_3_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</a>'; ?>');
                 //-->
                </script>
                <?php } elseif ($products_image_sm_3_name) { ?>
                <?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_3_destination . $products_image_sm_3_name, $products_image_sm_3_destination . $products_image_sm_3_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); }; ?>
                <?php if ($products_image_xl_4_name) { ?>
                <script script type="text/javascript"><!--
                  document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_xl_4_destination . $products_image_xl_4_name) . '\\\')">' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_4_destination . $products_image_sm_4_name, $products_image_sm_4_destination . $products_image_sm_4_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</a>'; ?>');
                  //-->
                </script>
                <?php } elseif ($products_image_sm_4_name) { ?>
                <?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_4_destination . $products_image_sm_4_name, $products_image_sm_4_destination . $products_image_sm_4_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); }; ?>
                <?php if ($products_image_xl_5_name) { ?>
                <script script type="text/javascript"><!--
                  document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_xl_5_destination . $products_image_xl_5_name) . '\\\')">' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_5_destination . $products_image_sm_5_name, $products_image_sm_5_destination . $products_image_sm_5_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</a>'; ?>');
                  //-->
                </script>
                <?php } elseif ($products_image_sm_5_name) { ?>
                <?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_5_destination . $products_image_sm_5_name, $products_image_sm_5_destination . $products_image_sm_5_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); }; ?>
                <?php if ($products_image_xl_6_name) { ?>
                <script script type="text/javascript"><!--
                  document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_xl_6_destination . $products_image_xl_6_name) . '\\\')">' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_6_destination . $products_image_sm_6_name, $products_image_sm_6_destination . $products_image_sm_6_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="6" vspace="6"') . '</a>'; ?>');
                  //-->
                </script>
                <?php } elseif ($products_image_sm_6_name) { ?>
                <?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_6_destination . $products_image_sm_6_name, $products_image_sm_6_destination . $products_image_sm_6_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="6" vspace="6"'); };?>
              </fieldset></td>
            </tr>
            <?php
            if (isset($read) && ($read == 'only')) {
              if (isset($_GET['origin'])) {
                $pos_params = strpos($_GET['origin'], '?', 0);
                if ($pos_params != false) {
                  $back_url = substr($_GET['origin'], 0, $pos_params);
                  $back_url_params = substr($_GET['origin'], $pos_params + 1);
                } else {
                  $back_url = $_GET['origin'];
                  $back_url_params = '';
                }
              } else {
                $back_url = FILENAME_CATEGORIES;
                $back_url_params = 'cPath=' . $cPath . '&pID=' . $pInfo->products_id;
              }     
              ?>
              <tr>
                <td align="right"><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
              </tr>
              <?php
            } else {
              ?>
              <tr>
                <td align="right" class="smallText">
                  <?php
                  reset($_POST);
                  while (list($key, $value) = each($_POST)) {
                    if (!is_array($_POST[$key])) {
                      echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value))) . "\n";
                    } else {
                      while (list($k, $v) = each($value)) {
                       echo tep_draw_hidden_field($key . '[' . $k . ']', htmlspecialchars(stripslashes($v))) . "\n";
                      }
                    }
                  }
                  if ($_POST['extra_field']) { // Check to see if there are any need to update extra fields.
                    foreach ($_POST['extra_field'] as $key=>$val) {
                      echo tep_draw_hidden_field('extra_field['.$key.']', stripslashes($val)) . "\n";
                    }
                  } // Check to see if there are any need to update extra fields.
                  echo tep_draw_hidden_field('products_image', stripslashes($products_image_destination . $products_image_name)) . "\n";
                  echo tep_draw_hidden_field('products_image_med', stripslashes($products_image_med_destination . $products_image_med_name)) . "\n";
                  echo tep_draw_hidden_field('products_image_lrg', stripslashes($products_image_lrg_destination . $products_image_lrg_name)) . "\n";
                  echo tep_draw_hidden_field('products_image_rls2', stripslashes($products_image_rls2_destination . $products_image_rls2_name)) . "\n";
                  echo tep_draw_hidden_field('products_image_sm_1', stripslashes($products_image_sm_1_destination . $products_image_sm_1_name)) . "\n";
                  echo tep_draw_hidden_field('products_image_xl_1', stripslashes($products_image_xl_1_destination . $products_image_xl_1_name)) . "\n";
                  echo tep_draw_hidden_field('products_image_sm_2', stripslashes($products_image_sm_2_destination . $products_image_sm_2_name)) . "\n";
                  echo tep_draw_hidden_field('products_image_xl_2', stripslashes($products_image_xl_2_destination . $products_image_xl_2_name)) . "\n";
                  echo tep_draw_hidden_field('products_image_sm_3', stripslashes($products_image_sm_3_destination . $products_image_sm_3_name)) . "\n";
                  echo tep_draw_hidden_field('products_image_xl_3', stripslashes($products_image_xl_3_destination . $products_image_xl_3_name)) . "\n";
                  echo tep_draw_hidden_field('products_image_sm_4', stripslashes($products_image_sm_4_destination . $products_image_sm_4_name)) . "\n";
                  echo tep_draw_hidden_field('products_image_xl_4', stripslashes($products_image_xl_4_destination . $products_image_xl_4_name)) . "\n";
                  echo tep_draw_hidden_field('products_image_sm_5', stripslashes($products_image_sm_5_destination . $products_image_sm_5_name)) . "\n";
                  echo tep_draw_hidden_field('products_image_xl_5', stripslashes($products_image_xl_5_destination . $products_image_xl_5_name)) . "\n";
                  echo tep_draw_hidden_field('products_image_sm_6', stripslashes($products_image_sm_6_destination . $products_image_sm_6_name)) . "\n";
                  echo tep_draw_hidden_field('products_image_xl_6', stripslashes($products_image_xl_6_destination . $products_image_xl_6_name)) . "\n";

                  echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;' . "\n";
                  if (!empty($pID)) {
                    echo tep_image_submit('button_update.gif', IMAGE_UPDATE) . "\n";;
                  } else {
                    echo tep_image_submit('button_insert.gif', IMAGE_INSERT) . "\n";;
                  }
                  echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($pID) ? '&pID=' . $pID : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
                  ?>
                </td>
              </tr>
            </table>
            </form>
            <?php
          }
        } else {
          ?>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
                  <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                      <td class="smallText" align="right">
                        <?php
                        echo tep_draw_form('search', FILENAME_CATEGORIES);
                        echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search');
                        echo '</form>';
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td class="smallText" align="right">
                        <?php
                        echo tep_draw_form('goto', FILENAME_CATEGORIES, '', 'get');
                        echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
                        if (isset($_GET[tep_session_name()])) {
                          echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
                        }
                        echo '</form>';
                        ?>
                      </td>
                    </tr>
                  </table></td>
                </tr>
              </table></td>
            </tr>
            <?php
            // RCI start
            echo $cre_RCI->get('categories', 'listingtop');
            // RCI eof
            ?>
            <tr>
              <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
                      <?php
                    echo tep_draw_form('sort', FILENAME_CATEGORIES, tep_get_all_get_params(array('action')) . 'action=sort', 'post', '', 'SSL');
                      ?>
                      <tr class="dataTableHeadingRow">
                        <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></td>
                        <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                        <td class="dataTableHeadingContent" align="center" width="50"><?php echo TABLE_HEADING_SORT_ORDER; ?></td>
                        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                      </tr>
                      <?php
                      $categories_count = 0;
                      $rows = 0;
                      if (isset($_POST['search'])) {
                        $search = str_replace("'", "&#39", tep_db_prepare_input($_POST['search'])); 
                        $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and cd.categories_name like '%" . tep_db_input($search) . "%' order by c.sort_order, cd.categories_name");
                      } else {
                        $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
                      }
                      $sort_order = 0;
                      while ($categories = tep_db_fetch_array($categories_query)) {
                        if (empty($cID)){
                          $cID = $categories['categories_id'];
                        }
                        $categories_count++;
                        $sort_order += 10;
                        $rows++;
                        // Get parent_id for subcategories if search
                        if (isset($_POST['search'])) $cPath= $categories['parent_id'];
                        if ((!isset($_GET['cID']) && !isset($_GET['pID']) || (isset($_GET['cID']) && ($_GET['cID'] == $categories['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
                          $category_childs = array('childs_count' => tep_childs_in_category_count($categories['categories_id']));
                          $category_products = array('products_count' => tep_products_in_category_count($categories['categories_id']));
                          $cInfo_array = array_merge($categories, $category_childs, $category_products);
                          $cInfo = new objectInfo($cInfo_array);
                        }
                        if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) {
                          echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
                          $onclick = 'document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '\'';
                        } else {
                          echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
                          $onclick = 'document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '\'';
                        }
                        ?>
                        <td class="dataTableContent" onclick="<?php echo $onclick; ?>"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '">' . tep_image(DIR_WS_ICONS . 'folder.png', ICON_FOLDER) . '</a>&nbsp;<b>' . $categories['categories_name'] . '</b>'; ?></td>
                        <td class="dataTableContent" align="center" onclick="<?php echo $onclick; ?>">&nbsp;</td>
                        <td class="dataTableContent" align="center"><?php echo tep_draw_input_field('c_sort_order[' . $categories['categories_id'] . ']', $sort_order, 'size="4"');?></td>
                        <td class="dataTableContent" align="right" onclick="<?php echo $onclick; ?>"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id'] . '&action=edit_category') . '">' . tep_image(DIR_WS_ICONS . 'page_edit.png', ICON_EDIT) . '</a>'; ?>&nbsp;&nbsp;
                          <?php
                          if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) {
                            echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', '');
                          } else {
                            echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>';
                          }
                          ?>&nbsp;
                        </td>
                      </tr>
                      
                      
                      
                      
                      <?php
                      }
                      $products_count = 0;
                      if (isset($_POST['search'])) {
                        $search = str_replace("'", "&#39", tep_db_prepare_input($_POST['search'])); 
                        $products_query = tep_db_query("SELECT p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.vendors_product_price, p.vendors_prod_comments, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_model, p2c.categories_id
                                                        FROM " . TABLE_PRODUCTS . " p,
                                                             " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                                             " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c
                                                        WHERE p.products_id = pd.products_id
                                                          and pd.language_id = " . (int)$languages_id . "
                                                          and p.products_id = p2c.products_id
                                                          and (pd.products_name like '%" . tep_db_input($search) . "%' or
                                                               p.products_model like '%" . tep_db_input($search) . "%')
                                                          and (p.user_id is NULL or p.user_id = '' )
                                                        ORDER BY p.sort_order, pd.products_name");
                      } else {
                        $products_query = tep_db_query("SELECT p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.vendors_product_price, p.vendors_prod_comments, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_model, p2c.categories_id
                                                          from " . TABLE_PRODUCTS . " p,
                                                               " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                                               " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c
                                                        WHERE p.products_id = pd.products_id
                                                          and pd.language_id = " . (int)$languages_id . "
                                                          and p.products_id = p2c.products_id
                                                          and p2c.categories_id = " . (int)$current_category_id . "
                                                          and p.products_parent_id = 0
                                                          and (p.user_id is NULL or p.user_id = '' )
                                                        ORDER BY sort_order, pd.products_name");
                      }
                      $sort_order = 0;
                      while ($products = tep_db_fetch_array($products_query)) {
                        if (empty($pID)){
                          $pID = $products['products_id'];
                        }
                        $products_count++;
                        $sort_order += 10;
                        $rows++;
                        // Get categories_id for product if search
                        if (isset($_POST['search'])) {
                          $product_category_query = tep_db_query("SELECT categories_id
                                                        FROM " . TABLE_PRODUCTS_TO_CATEGORIES . "
                                                        WHERE products_id = " . $products['products_id'] . "
                                                        ORDER BY categories_id");
                          $prodcats = tep_db_fetch_array($product_category_query);
                          $cPath = $prodcats['categories_id'];
                        }
                        if ( (!isset($_GET['pID']) && !isset($_GET['cID']) || (isset($_GET['pID']) && ($_GET['pID'] == $products['products_id']))) && !isset($pInfo) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
                          // find out the rating average from customer reviews
                          $reviews_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . (int)$products['products_id'] . "'");
                          $reviews = tep_db_fetch_array($reviews_query);
                          $pInfo_array = array_merge($products, $reviews);
                          $pInfo = new objectInfo($pInfo_array);
                        }
                        // RCO start plistrows
                        if ($cre_RCO->get('categories', 'plistrows') !== true) {
                          if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) ) {
                            echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
                            $onclick = 'document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '\'';
                          } else {
                            echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
                            $onclick = 'document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '\'';
                          }
                          ?>
                          <td class="dataTableContent" onclick="<?php echo $onclick; ?>"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '">' . tep_image(DIR_WS_ICONS . 'magnifier.png', ICON_PREVIEW) . '</a>&nbsp;' . $products['products_name']; ?></td>
                          <td class="dataTableContent" align="center" onclick="<?php echo $onclick; ?>">
                            <?php
                            if ($products['products_status'] == '1') {
                              echo tep_image(DIR_WS_IMAGES . 'accept.png', IMAGE_ICON_STATUS_GREEN, 16, 16) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'cancel-off.png', IMAGE_ICON_STATUS_RED_LIGHT, 16, 16) . '</a>';
                            } else {
                              echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'accept-off.png', IMAGE_ICON_STATUS_GREEN_LIGHT, 16, 16) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'cancel.png', IMAGE_ICON_STATUS_RED, 16, 16);
                            }
                            ?>
                          </td>
                          <td class="dataTableContent" align="center"><?php echo tep_draw_input_field('sort_order[' . $products['products_id'] . ']', $sort_order, 'size="4"');?></td>
                          <td class="dataTableContent" align="right" onclick="<?php echo $onclick; ?>"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product') . '">' . tep_image(DIR_WS_ICONS . 'page_edit.png', ICON_EDIT) . '</a>'; ?>&nbsp;&nbsp;
                            <?php
                            if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id)) {
                              echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', '');
                            } else {
                              echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>';
                            }
                            ?>&nbsp;
                          </td>
                          </tr>
                        <?php
                        // RCO end plistrows
                        }
                      }
                      $cPath_back = '';
                      if (sizeof($cPath_array) > 0) {
                        for ($i=0, $n=sizeof($cPath_array)-1; $i<$n; $i++) {
                          if (empty($cPath_back)) {
                            $cPath_back .= $cPath_array[$i];
                          } else {
                            $cPath_back .= '_' . $cPath_array[$i];
                          }
                        }
                      }
                      $cPath_back = (tep_not_null($cPath_back)) ? 'cPath=' . $cPath_back . '&' : '';
                      ?>
                      </table>
                     <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
                      <tr>
                        <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                          <tr>
                            <td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br>' . TEXT_PRODUCTS . '&nbsp;' . $products_count; ?></td>
                            <td align="right" class="smallText">
                              <?php
                              if (sizeof($cPath_array) > 0) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, $cPath_back . 'cID=' . $current_category_id) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; 
                              echo tep_image_submit('button_update_sort_order.gif', IMAGE_UPDATE_SORT_ORDER);
                              // RCO start listing buttons
                              if ($cre_RCO->get('categories', 'listingbuttons') !== true) { 
                                if (!isset($_POST['search'])) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_category') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_product') . '">' . tep_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>'; 
                              }
                              // RCO eof
                              ?>&nbsp;
                            </td>
                          </tr>
                          <tr>
                            <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                              <tr>              
                                <?php
                                // RCI code start
                                echo $cre_RCI->get('categories', 'listingbottom');
                                // RCI code eof
                                ?>
                              </tr>
                            </table></td>
                          </tr>                      
                        </table></td>
                      </tr>
                      </form>
                    </table></td>
              <?php
              $heading = array();
              $contents = array();
              switch ($action) {
                case 'new_category':
                case 'new_category':
                  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_CATEGORY . '</b>');
                  $contents = array('form' => tep_draw_form('newcategory', FILENAME_CATEGORIES, 'action=insert_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"'));
                  $contents[] = array('text' => TEXT_NEW_CATEGORY_INTRO);
                  $category_inputs_string = '';
                  $languages = tep_get_languages();
                  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                    $category_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']');
                  }
                  $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_NAME . $category_inputs_string);
                  $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
                  $contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', '', 'size="2"'));
                  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                  break;
                
                case 'delete_category':
                  $heading[] = array('text' => TEXT_INFO_HEADING_DELETE_CATEGORY);
                  $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
                  $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
                  $contents[] = array('text' => '<br><b>' . $cInfo->categories_name . '</b>');
                  if ($cInfo->childs_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
                  if ($cInfo->products_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
                  $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_delete.gif', IMAGE_DELETE));
                  break;
                
                case 'move_category':
                  $heading[] = array('text' => TEXT_INFO_HEADING_MOVE_CATEGORY);
                  $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=move_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
                  $contents[] = array('text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name));
                  $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
                  $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_move.gif', IMAGE_MOVE));
                  break;
                
                case 'delete_product':
                  $heading[] = array('text' => TEXT_INFO_HEADING_DELETE_PRODUCT);
                  $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=delete_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
                  $contents[] = array('text' => TEXT_DELETE_PRODUCT_INTRO);
                  $contents[] = array('text' => '<br><b>' . $pInfo->products_name . '</b>');
                  $product_categories_string = '';
                  $product_categories = tep_generate_category_path($pInfo->products_id, 'product');
                  for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
                    $category_path = '';
                    for ($j = 0, $k = sizeof($product_categories[$i]); $j < $k; $j++) {
                      $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
                    }
                    $category_path = substr($category_path, 0, -16);
                    $product_categories_string .= tep_draw_checkbox_field('product_categories[]', $product_categories[$i][sizeof($product_categories[$i])-1]['id'], true) . '&nbsp;' . $category_path . '<br>';
                  }
                  $product_categories_string = substr($product_categories_string, 0, -4);
                  $contents[] = array('text' => '<br>' . $product_categories_string);
                  $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_delete.gif', IMAGE_DELETE));
                  break;
                 
                case 'move_product':
                  $heading[] = array('text' => TEXT_INFO_HEADING_MOVE_PRODUCT);
                  $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=move_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
                  $contents[] = array('text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name));
                  $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
                  $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $pInfo->products_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
                  $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_move.gif', IMAGE_MOVE));
                  break;
                  
                case 'copy_to':
                  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>');
                  $contents = array('form' => tep_draw_form('copy_to', FILENAME_CATEGORIES, 'action=copy_to_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
                  $contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
                  $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
                  $contents[] = array('text' => '<br>' . TEXT_CATEGORIES . '<br>' . tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id));
                  $contents[] = array('text' => '<br>' . TEXT_HOW_TO_COPY . '<br>' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br>' . tep_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE);
                  $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
                  // only ask about attributes if they exist
                  if (tep_has_product_attributes($pInfo->products_id)) {
                    $contents[] = array('text' => '<br>' . TEXT_COPY_ATTRIBUTES_ONLY);
                    $contents[] = array('text' => '<br>' . TEXT_COPY_ATTRIBUTES . '<br>' . tep_draw_radio_field('copy_attributes', 'copy_attributes_yes', true) . ' ' . TEXT_COPY_ATTRIBUTES_YES . '<br>' . tep_draw_radio_field('copy_attributes', 'copy_attributes_no') . ' ' . TEXT_COPY_ATTRIBUTES_NO);
                    $contents[] = array('align' => 'center', 'text' => '<br>' . ATTRIBUTES_NAMES_HELPER . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '10'));
                    $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
                  }
                  $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_copy.gif', IMAGE_COPY));
                  break;
                  
                case 'copy_product_attributes':
                  $copy_attributes_delete_first='1';
                  $copy_attributes_duplicates_skipped='1';
                  $copy_attributes_duplicates_overwrite='0';
                  if (DOWNLOAD_ENABLED == 'true') {
                    $copy_attributes_include_downloads='1';
                    $copy_attributes_include_filename='1';
                  } else {
                    $copy_attributes_include_downloads='0';
                    $copy_attributes_include_filename='0';
                  }
                  $heading[] = array('text' => TEXT_COPY_ATTRIBUTES_TO_ANOTHER_PRODUCT);
                  $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=create_copy_product_attributes&cPath=' . $cPath . '&pID=' . $pInfo->products_id) . tep_draw_hidden_field('products_id', $pInfo->products_id) . tep_draw_hidden_field('products_name', $pInfo->products_name));
                  $contents[] = array('text' => '<br>'.TEXT_COPYING_ATTRIBUTES_FROM.' #' . $pInfo->products_id . '<br><b>' . $pInfo->products_name . '</b>');
                  $contents[] = array('text' => TEXT_COPYING_ATTRIBUTES_TO.' #&nbsp;' . tep_draw_input_field('copy_to_products_id', $copy_to_products_id, 'size="3"'));
                  $contents[] = array('text' => '<br>'.TEXT_DELETE_ALL_ATTRIBUTE.'&nbsp;' . tep_draw_checkbox_field('copy_attributes_delete_first',$copy_attributes_delete_first, 'size="2"'));
                  $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
                  $contents[] = array('text' => '<br>' . TEXT_OTHERWISE);
                  $contents[] = array('text' => TEXT_DUPLICATE_ATTRIBUTES_SKIPPED.'&nbsp;' . tep_draw_checkbox_field('copy_attributes_duplicates_skipped',$copy_attributes_duplicates_skipped, 'size="2"'));
                  $contents[] = array('text' => '&nbsp;&nbsp;&nbsp;'.TEXT_DUPLICATE_ATTRIBUTES_OVERWRITTEN.'&nbsp;' . tep_draw_checkbox_field('copy_attributes_duplicates_overwrite',$copy_attributes_duplicates_overwrite, 'size="2"'));
                  if (DOWNLOAD_ENABLED == 'true') {
                    $contents[] = array('text' => '<br>'.TEXT_COPY_ATTRIBUTES_WITH_DOWNLOADS.'&nbsp;' . tep_draw_checkbox_field('copy_attributes_include_downloads',$copy_attributes_include_downloads, 'size="2"'));
                  }
                  $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
                  $contents[] = array('align' => 'center', 'text' => '<br>' . PRODUCT_NAMES_HELPER);
                  if ($pID) {
                    $contents[] = array('align' => 'center', 'text' => '<br>' . ATTRIBUTES_NAMES_HELPER);
                  } else {
                    $contents[] = array('align' => 'center', 'text' => '<br>'.TEXT_SELECT_PRODUCT_FOR_DISPLAY);
                  }
                  $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_copy.gif', TEXT_BUTTON_COPY_ATTRIBUTES));
                  break;
                
                case 'copy_product_attributes_categories':
                  $copy_attributes_delete_first='1';
                  $copy_attributes_duplicates_skipped='1';
                  $copy_attributes_duplicates_overwrite='0';
                  if (DOWNLOAD_ENABLED == 'true') {
                    $copy_attributes_include_downloads='1';
                    $copy_attributes_include_filename='1';
                  } else {
                    $copy_attributes_include_downloads='0';
                    $copy_attributes_include_filename='0';
                  }
                  $heading[] = array('text' => TEXT_COPY_PRODUCT_ATTRIBUTES_TO_CATEGORY);
                  $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=create_copy_product_attributes_categories&cPath=' . $cPath . '&cID=' . $cID . '&make_copy_from_products_id=' . $copy_from_products_id));
                  $contents[] = array('text' => TEXT_COPY_PRODUCT_ATTRIBUTES_FROM_PRODUCT_ID.'#&nbsp;' . tep_draw_input_field('make_copy_from_products_id', $make_copy_from_products_id, 'size="3"'));
                  $contents[] = array('text' => '<br>'.TEXT_COPYING_TO_ALL_PRODUCTS_IN_CATEGORY_ID.'#&nbsp;' . $cID . '<br>'.TEXT_CATEGORY_NAME.' <b>' . tep_get_category_name($cID, $languages_id) . '</b>');
                  $contents[] = array('text' => '<br>'.TEXT_DELETE_ALL_ATTRIBUTE.'&nbsp;' . tep_draw_checkbox_field('copy_attributes_delete_first',$copy_attributes_delete_first, 'size="2"'));
                  $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
                  $contents[] = array('text' => '<br>' . TEXT_OTHERWISE);
                  $contents[] = array('text' => TEXT_DUPLICATE_ATTRIBUTES_SKIPPED.'&nbsp;' . tep_draw_checkbox_field('copy_attributes_duplicates_skipped',$copy_attributes_duplicates_skipped, 'size="2"'));
                  $contents[] = array('text' => '&nbsp;&nbsp;&nbsp;'.TEXT_DUPLICATE_ATTRIBUTES_OVERWRITTEN.'&nbsp;' . tep_draw_checkbox_field('copy_attributes_duplicates_overwrite',$copy_attributes_duplicates_overwrite, 'size="2"'));
                  if (DOWNLOAD_ENABLED == 'true') {
                    $contents[] = array('text' => '<br>'.TEXT_COPY_ATTRIBUTES_WITH_DOWNLOADS.'&nbsp;' . tep_draw_checkbox_field('copy_attributes_include_downloads',$copy_attributes_include_downloads, 'size="2"'));
                  }
                  $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
                  $contents[] = array('align' => 'center', 'text' => '<br>' . TEXT_ATTRIBUTES_PRODUCT_LOOKUP);
                  $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cID) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_copy.gif', 'Copy Attribtues'));
                  break;
                  
                default:
                  if ($rows > 0) {
                    if (isset($cInfo) && is_object($cInfo)) { // category info box contents
                      $heading[] = array('text' => $cInfo->categories_name);
                      // RCO start
                      if ($cre_RCO->get('categories', 'csidebarbuttons') !== true) {  
                        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=delete_category') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a><br><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=move_category') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a>');
                      }
                      // RCO eof
                      $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' <b>' . tep_date_short($cInfo->date_added) . '</b>');
                      if (tep_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' <b>' . tep_date_short($cInfo->last_modified) . '</b>');
                      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_info_image($cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br>' . $cInfo->categories_image);
                      $contents[] = array('text' => '<br>' . TEXT_SUBCATEGORIES . ' <b>' . $cInfo->childs_count . '</b><br>' . TEXT_PRODUCTS . ' <b>' . $cInfo->products_count . '</b>');
                      // RCO start
                      if ($cre_RCO->get('categories', 'csidebarattributes') !== true) { 
                        if ($cInfo->childs_count == 0 and $cInfo->products_count >= 1) {
                          $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
                          if ($cID) {
                            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cID . '&action=copy_product_attributes_categories') . '"><br>' . tep_image_button('button_copy_to.gif', 'Copy Attributes') . '</a>');
                          } else {
                            $contents[] = array('align' => 'center', 'text' => '<br>' . TEXT_ATTRIBUTES_COPY_TO);
                          }
                        }
                      }
                      // RCO eof
                      // RCI include category sidebar bottom text
                      $returned_rci = $cre_RCI->get('categories', 'csidebarbottom');
                      $contents[] = array('text' => $returned_rci);
                    } elseif (isset($pInfo) && is_object($pInfo)) { // product info box contents
                      // multi-vendor shipping 
                      $vendors_query_2 = tep_db_query("SELECT v.vendors_id, v.vendors_name 
                                                         from vendors v, products p 
                                                       WHERE v.vendors_id=p.vendors_id 
                                                         and p.products_id='" . $pInfo->products_id . "'");
                      while ($vendors_2 = tep_db_fetch_array($vendors_query_2)) {
                        $current_vendor_name = $vendors_2['vendors_name'];
                      }
                      // multi-vendor shipping //eof                    
                      $heading[] = array('text' => tep_get_products_name($pInfo->products_id, $languages_id));
                      // RCO start
                      if ($cre_RCO->get('categories', 'psidebarbuttons') !== true) { 
                        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $pInfo->categories_id . '&pID=' . $pInfo->products_id . '&action=new_product') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $pInfo->categories_id . '&pID=' . $pInfo->products_id . '&action=delete_product') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a><br><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $pInfo->categories_id . '&pID=' . $pInfo->products_id . '&action=move_product') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $pInfo->categories_id . '&pID=' . $pInfo->products_id . '&action=copy_to') . '">' . tep_image_button('button_copy_to.gif', IMAGE_COPY_TO) . '</a>');
                        $contents[] = array('align' => 'center', 'text' => tep_draw_separator('pixel_trans.gif', '1', '16') . '<a href="' . tep_href_link(FILENAME_ATTRIBUTES, 'cPath=' . $pInfo->categories_id . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_edit_attributes.gif', IMAGE_EDIT_ATTRIBUTES) . '</a>');
                      }
                      // RCO eof
                      //RCI include product sidebar buttons
                      $returned_rci = $cre_RCI->get('categories', 'psidebarbuttons');
                      $contents[] = array('align' => 'center', 'text' => $returned_rci);
                      $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' <b>' . tep_date_short($pInfo->products_date_added) . '</b>');
                      if (tep_not_null($pInfo->products_last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' <b>' . tep_date_short($pInfo->products_last_modified) . '</b>');
                      if (date('Y-m-d') < $pInfo->products_date_available) $contents[] = array('text' => TEXT_DATE_AVAILABLE . ' <b>' . tep_date_short($pInfo->products_date_available) . '</b>');
                      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_info_image($pInfo->products_image, tep_get_products_name($pInfo->products_id, $languages_id), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<br>' . $pInfo->products_image);
                      $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_PRICE_INFO . ' <b>' . $currencies->format($pInfo->products_price) . '</b><br>' . TEXT_PRODUCTS_QUANTITY_INFO . ' <b>' . $pInfo->products_quantity . '</b>');
                      // multi-vendor shipping
                      if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
                        $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_PRICE_INFO . ' <b>' . $currencies->format($pInfo->products_price) . '</b><br>' . TEXT_VENDOR . ' <b>' . $current_vendor_name . '</b><br>' . TEXT_VENDORS_PRODUCT_PRICE_INFO . '<b>' . $currencies->format($pInfo->vendors_product_price) . '</b><br>' . TEXT_PRODUCTS_QUANTITY_INFO . ' <b>' . $pInfo->products_quantity . '</b>');
                      }
                      // multi-vendor shipping //eof                      
                      $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_AVERAGE_RATING . ' <b>' . number_format($pInfo->average_rating, 2) . '%</b>');
                      //RCI include product sidebar product text
                      $returned_rci = $cre_RCI->get('categories', 'psidebarproducttext');
                      $contents[] = array('text' => $returned_rci);
                      // RCO start
                      if ($cre_RCO->get('categories', 'psidebarattributes') !== true) {                       
                        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
                        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $pInfo->categories_id . '&pID=' . $pInfo->products_id . '&action=copy_product_attributes') . '"><br>' . tep_image_button('button_copy_to.gif', TEXT_BUTTON_COPY_ATTRIBUTES) . '</a>');
                        if ($pID) {
                          $contents[] = array('align' => 'center', 'text' => '<br>' . sprintf(TEXT_ATTRIBUTES_NAMES_HELPER, $pID) . '<font color=#FF0000><b>' . $pID . '</b></font>');
                        } else {
                          $contents[] = array('align' => 'center', 'text' => '<br>'. TEXT_SELECT_PRODUCT_TO_DISPLAY_ATTRIBUTES);
                        }
                      }
                      // RCO eof
                      //RCI include product sidebar bottom product text
                      $returned_rci = $cre_RCI->get('categories', 'psidebarbottom');
                      $contents[] = array('text' => $returned_rci);
                    }
                  } else { 
                    $heading[] = array('text' => EMPTY_CATEGORY);
                    $contents[] = array('text' => TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS);
                  }
                  break;
                } 
                if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
                  echo '<td width="25%" valign="top">' . "\n";
                  $box = new box;
                  echo $box->infoBox($heading, $contents);
                  echo '</td>' . "\n";
                }
                ?>
                </tr>
              </table>
            </td>
          </tr>
          <?php
        }
        // RCI code start
        echo $cre_RCI->get('categories', 'bottom'); 
        echo $cre_RCI->get('global', 'bottom');                                        
        // RCI code eof
        ?>
      </table>
    </td>
    <!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>