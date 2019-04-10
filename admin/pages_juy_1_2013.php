<?php
/*
  $Id: cds_page_manager.php,v 1.0.0.0 2007/02/27 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');
$is_62 = (INSTALLED_VERSION_MAJOR == 6 && INSTALLED_VERSION_MINOR == 2) ? true : false;
require(DIR_WS_FUNCTIONS . 'cds_functions.php');

if ($messageStack->size('header') > 0) {
  echo $messageStack->output('header');
}
$error_text = (isset($_SESSION['error_text'])) ? $_SESSION['error_text'] : '';

// determine cPath
$cPath = '0';
if ( isset($_POST['cPath'] ) && tep_not_null($_POST['cPath'] ) ) {
  $cPath = $_POST['cPath'];
} elseif ( isset($_GET['cPath'] ) && tep_not_null($_GET['cPath'])) {
  $cPath = $_GET['cPath'];
}
if ( tep_not_null($cPath) ) {
  $cPath_array = tep_pages_parse_categories_path($cPath);
  $cPath = implode('_', $cPath_array);
  $current_categories_id = $cPath_array[(sizeof($cPath_array)-1)];
} else {
  $current_categories_id = 0;
}

// clean variables
$cID = '';
if ( isset($_POST['cID'] ) && tep_not_null($_POST['cID'])) {
  $cID = (int)$_POST['cID'];
} elseif ( isset($_GET['cID'] ) && tep_not_null($_GET['cID'])) {
  $cID = (int)$_GET['cID'];
}
$pID = '';
if ( isset($_POST['pID'] ) && tep_not_null($_POST['pID'])) {
  $pID = (int)$_POST['pID'];
} elseif ( isset($_GET['pID'] ) && tep_not_null($_GET['pID'])) {
  $pID = (int)$_GET['pID'];
}
$action = '';
if ( isset($_POST['action'] ) && tep_not_null($_POST['action'])) {
  $action = tep_db_prepare_input($_POST['action']);
} elseif ( isset($_GET['action'] ) && tep_not_null($_GET['action'])) {
  $action = tep_db_prepare_input($_GET['action']);
} 

switch ($action) {

  case 'setflag_category':
    $status = tep_db_prepare_input($_GET['flag']);
    if ($status == '1') {
      tep_db_query("UPDATE " . TABLE_CDS_CATEGORIES . " 
                    SET categories_status = '1' 
                    WHERE categories_id = '" . (int)$cID . "'");
    } elseif ($status == '0') {
      tep_db_query("UPDATE " . TABLE_CDS_CATEGORIES . " 
                    SET categories_status = '0'
                    WHERE categories_id = '" . (int)$cID . "'");
    }
    tep_redirect(tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $cID));
    break;

  case 'setmenu_category':
    $status = tep_db_prepare_input($_GET['flag']);
    if ($status == '1') {
      tep_db_query("UPDATE " . TABLE_CDS_CATEGORIES . " 
                    SET categories_in_menu = '1' 
                    WHERE categories_id = '" . (int)$cID . "'");
    } elseif ($status == '0') {
      tep_db_query("UPDATE " . TABLE_CDS_CATEGORIES . " 
                    SET categories_in_menu = '0'
                    WHERE categories_id = '" . (int)$cID . "'");
    }
    tep_redirect(tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $cID));
    break;

  case 'setlisting_category':
    $status = tep_db_prepare_input($_GET['flag']);
    if ($status == '1') {
      tep_db_query("UPDATE " . TABLE_CDS_CATEGORIES . " 
                    SET categories_in_pages_listing = '2' 
                    WHERE categories_id = '" . (int)$cID . "'");
    } elseif ($status == '0') {
      tep_db_query("UPDATE " . TABLE_CDS_CATEGORIES . " 
                    SET categories_in_pages_listing = '0'
                    WHERE categories_id = '" . (int)$cID . "'");
    }
    tep_redirect(tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $cID));
    break;

  case 'edit_category':
    $categories_query = tep_db_query("SELECT ic.categories_id, ic.categories_status, ic.categories_image, ic.category_heading_title_image, ic.category_header_banner, ic.categories_sort_order, ic.categories_url_override, ic.categories_attach_product, ic.categories_url_override_target, ic.category_append_cdpath, ic.categories_sub_category_view, ic.categories_listing_content_mode, ic.categories_listing_columns, ic.categories_in_menu, ic.categories_in_pages_listing, ic.categories_language_saving_option, ic.categories_template, icd.categories_name, icd.categories_description
                                        from " . TABLE_CDS_CATEGORIES . " ic 
                                      LEFT JOIN " . TABLE_CDS_CATEGORIES_DESCRIPTION . " icd 
                                        on ic.categories_id = icd.categories_id 
                                      WHERE ic.categories_id = '" . (int)$cID . "' 
                                        and icd.language_id = '" . (int)$languages_id . "'");
    if ($categories = tep_db_fetch_array($categories_query)) {
      $cInfo = new objectInfo($categories);
    }
    break;

  case 'insert_category':
  case 'update_category':
    $categories_sort_order = tep_db_prepare_input($_POST['categories_sort_order']);
    $category_append_cdpath = tep_db_prepare_input($_POST['category_append_cdpath']);
    $categories_url_override = tep_db_prepare_input($_POST['categories_url_override']);
    $categories_url_override_target = tep_db_prepare_input($_POST['categories_url_override_target']);
    $categories_attach_product = tep_db_prepare_input($_POST['categories_attach_product']);     
    $categories_status = ((tep_db_prepare_input($_POST['categories_status']) == 'on') ? '1' : '0');
    $categories_sub_category_view =((tep_db_prepare_input($_POST['categories_sub_category_view']) == 'on') ? '1' : '0');
    $categories_listing_content_mode =((tep_db_prepare_input($_POST['categories_listing_content_mode']) == 'on') ? '1' : '0');
    $categories_listing_columns = tep_db_prepare_input($_POST['categories_listing_columns']);
    $categories_language_saving_option =((tep_db_prepare_input($_POST['categories_language_saving_option']) == 'on') ? '1' : '0');
    $categories_in_menu = tep_db_prepare_input($_POST['category_show_link']);
    $categories_in_pages_listing = tep_db_prepare_input($_POST['category_show_link1']);
    $categories_template = tep_db_prepare_input($_POST['categories_template']);
    $sql_data_array = array('categories_sort_order' => $categories_sort_order,
                            'categories_url_override' => $categories_url_override,
                            'categories_url_override_target' =>$categories_url_override_target,
                            'category_append_cdpath'=>$category_append_cdpath,  
                            'categories_attach_product' => $categories_attach_product,                                                       
                            'categories_status' => $categories_status,
                            'categories_sub_category_view' => $categories_sub_category_view,
                            'categories_listing_content_mode' =>$categories_listing_content_mode,
                            'categories_listing_columns' => $categories_listing_columns ,
                            'categories_language_saving_option' =>$categories_language_saving_option,
                            'categories_in_menu'=>$categories_in_menu,
                            'categories_template' => $categories_template,                                                        
                            'categories_in_pages_listing'=>$categories_in_pages_listing);

    if ($action == 'insert_category') {
      $insert_sql_data = array('categories_parent_id' => $current_categories_id, 
                               'categories_date_added' => 'now()');
      $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
      tep_db_perform(TABLE_CDS_CATEGORIES, $sql_data_array);
      $cID = tep_db_insert_id();
    } elseif ($action == 'update_category') {
      $update_sql_data = array('categories_last_modified' => 'now()');
      $sql_data_array = array_merge($sql_data_array, $update_sql_data);
      tep_db_perform(TABLE_CDS_CATEGORIES, $sql_data_array, 'update', "categories_id = '" . (int)$cID . "'");
    }
    $languages = tep_get_languages();
    for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
      $categories_name_array = $_POST['categories_name'];           
      $categories_heading_array = $_POST['categories_heading'];
      $categories_description_array = $_POST['categories_description'];
      $categories_blurb_array = $_POST['categories_blurb'];
      $categories_meta_keywords_array = $_POST['categories_meta_keywords'];
      $categories_meta_title_array = $_POST['categories_meta_title'];
      $categories_meta_keywords_array = $_POST['categories_meta_keywords'];
      $categories_meta_description_array = $_POST['categories_meta_description'];
      $language_id = $languages[$i]['id'];
      $language_code = $languages[$i]['code'];
            $lang_default = (defined('DEFAULT_LANGUAGE') && DEFAULT_LANGUAGE != '') ? DEFAULT_LANGUAGE : 'en';
            if (($categories_name_array[$language_id] == '') && ($language_code == $lang_default)) {
                $_SESSION['error_text'] = TEXT_CDS_ERROR_MENU_NAME;
        tep_redirect(FILENAME_PAGES . '?cPath=' . $cPath . '&cID=' . $cID . '&action=edit_category');
          }         
      $sql_data_array = array('categories_name' => tep_db_prepare_input($categories_name_array[$language_id]),
                              'categories_heading' => tep_db_prepare_input($categories_heading_array[$language_id]),
                              'categories_description' => tep_db_prepare_input($categories_description_array[$language_id]),
                              'categories_blurb' => tep_db_prepare_input($categories_blurb_array[$language_id]),
                              'categories_meta_keywords'=>tep_db_prepare_input($categories_meta_keywords_array[$language_id]),
                              'categories_meta_title' => tep_db_prepare_input($categories_meta_title_array[$language_id]),
                              'categories_meta_keywords' => tep_db_prepare_input($categories_meta_keywords_array[$language_id]),
                              'categories_meta_description' => tep_db_prepare_input($categories_meta_description_array[$language_id]) );
   
      if ($action == 'insert_category') {
        $insert_sql_data = array('categories_id' => $cID,
                                 'language_id' => $languages[$i]['id']);
        $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
        tep_db_perform(TABLE_CDS_CATEGORIES_DESCRIPTION, $sql_data_array);
      } elseif ($action == 'update_category') {
        tep_db_perform(TABLE_CDS_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$cID . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
      }
    }

    //Category Images Upload
    if (isset($_FILES['categories_image']) && tep_not_null($_FILES['categories_image']['name'])) {
      $categories_image = new upload('categories_image', DIR_FS_CATALOG_IMAGES);
      $sql=("UPDATE " . TABLE_CDS_CATEGORIES . " 
             SET categories_image = '" . tep_db_input($categories_image->filename) . "' 
             WHERE categories_id = '" . (int)$cID . "'");
      tep_db_query($sql);       
    }
    if (isset($_FILES['category_heading_title_image']) && tep_not_null($_FILES['category_heading_title_image']['name'])) {
      $category_heading_title_image = new upload('category_heading_title_image', DIR_FS_CATALOG_IMAGES);
      $sql = ("UPDATE  " . TABLE_CDS_CATEGORIES . " 
               SET category_heading_title_image = '" . tep_db_input($category_heading_title_image->filename) . "'
               WHERE categories_id = '" . (int)$cID . "'");
    tep_db_query($sql);     
    }
    if (isset($_FILES['category_header_banner']) && tep_not_null($_FILES['category_header_banner']['name'])) {
      $category_header_banner = new upload('category_header_banner',DIR_FS_CATALOG_IMAGES );
      tep_db_query("UPDATE " . TABLE_CDS_CATEGORIES . " 
                    SET category_header_banner = '" . tep_db_input($category_header_banner->filename) . "'
                    WHERE categories_id = '" . (int)$cID . "'");
    }
    // Category Images Remove and Delete part.
    if ($_POST['unlink_heading_image'] == 'yes') {
      $category_heading_title_image = '';
      tep_db_query("UPDATE " . TABLE_CDS_CATEGORIES . " 
                    SET category_heading_title_image = '" .($category_heading_title_image) . "'
                    WHERE categories_id = '" . (int)$cID . "'");
    }
    if ($_POST['delete_heading_image'] == 'yes') {
      unlink(DIR_FS_CATALOG_IMAGES . $_POST['catagory_heading_previous_image']);
      $category_heading_title_image = '';
      tep_db_query("UPDATE " . TABLE_CDS_CATEGORIES . " 
                    SET category_heading_title_image = '" . ( $category_heading_title_image ) . "'
                    WHERE categories_id = '" . (int)$cID . "'");  
    }
    if ($_POST['unlink_catagory_image'] == 'yes') {
      $categories_image = '';
      $sql=("UPDATE " . TABLE_CDS_CATEGORIES . " 
             SET categories_image = '" . tep_db_input($categories_image) . "' 
             WHERE categories_id = '" . (int)$cID . "'");
      tep_db_query($sql);   
    }
    if ( $_POST['delete_catagory_image'] == 'yes' ) {
      unlink(DIR_FS_CATALOG_IMAGES . $_POST['catagory_previous_image']);
      $categories_image = '';
      $sql=("UPDATE " . TABLE_CDS_CATEGORIES . " 
             SET categories_image = '" . tep_db_input($categories_image) . "' 
             WHERE categories_id = '" . (int)$cID . "'");
        tep_db_query($sql);   
    }
    if ($_POST['unlink_banner_image'] == 'yes') {
      $category_header_banner = '';
      tep_db_query("UPDATE " . TABLE_CDS_CATEGORIES . " 
                    SET category_header_banner = '" . tep_db_input($category_header_banner) . "'
                    WHERE categories_id = '" . (int)$cID . "'");
    }
    if ($_POST['delete_banner_image'] == 'yes') {
      unlink(DIR_FS_CATALOG_IMAGES . $_POST['catagory_banner_previous_image']);
      $category_header_banner = '';
      tep_db_query("UPDATE " . TABLE_CDS_CATEGORIES . " 
                    SET category_header_banner = '" . tep_db_input($category_header_banner) . "'
                    WHERE categories_id = '" . (int)$cID . "'");
    }
    tep_redirect(tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $cID));
    break;
 
  case 'deleteconfirm_category':
    if (tep_not_null($cID)) {
      $categories = tep_pages_get_categories_tree($cID, '', '0', '', true);
      $pages = array();
      $pages_delete = array();
      for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
        $pages_ids_query = tep_db_query("SELECT pages_id 
                                           from " . TABLE_CDS_PAGES_TO_CATEGORIES . " 
                                         WHERE categories_id = '" . (int)$categories[$i]['id'] . "'");
        while ($pages_ids = tep_db_fetch_array($pages_ids_query)) {
          $pages[$pages_ids['pages_id']]['categories'][] = $categories[$i]['id'];
        }
      }
      reset($pages);
      while (list($key, $value) = each($pages)) {
        $categories_ids = '';
        for ($i=0, $n=sizeof($value['categories']); $i<$n; $i++) {
          $categories_ids .= "'" . (int)$value['categories'][$i] . "', ";
        }
        $categories_ids = substr($categories_ids, 0, -2);
        $check_query = tep_db_query("SELECT count(*) as total 
                                                from " . TABLE_CDS_PAGES_TO_CATEGORIES . " 
                                             WHERE pages_id = '" . (int)$key . "' 
                                               and categories_id not in (" . $categories_ids . ")");
        $check = tep_db_fetch_array($check_query);
        if ($check['total'] < '1') {
          $pages_delete[$key] = $key;
        }
      }
      // make sure script doesn't time out
      tep_set_time_limit(0);
      for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
        tep_pages_remove_category($categories[$i]['id']);
      }
      reset($pages_delete);
      while (list($key) = each($pages_delete)) {
        tep_pages_remove_page($key);
      }
    }  
    tep_redirect(tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath));
    break;

  case 'moveconfirm_category':
    if ( isset($_POST['cID']) && ($_POST['cID'] != $_POST['move_to_category_id']) ) {
      $categories_id = tep_db_prepare_input($_POST['cID']);
      $new_parent_id = tep_db_prepare_input($_POST['move_to_category_id']);
      $path = explode('_', tep_get_generated_pages_category_path_ids($new_parent_id));
      if (in_array($categories_id, $path)) {
        $messageStack->add_session(ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');
        tep_redirect(tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $categories_id));
      } else {
        tep_db_query("UPDATE " . TABLE_CDS_CATEGORIES . " 
                              SET categories_parent_id = '" . (int)$new_parent_id . "', categories_last_modified = now() 
                              WHERE categories_id = '" . (int)$categories_id . "'");
        tep_redirect(tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $new_parent_id . '&cID=' . $categories_id));
      }
    }
    break;

  case 'moveconfirm_page':
    $pages_id = tep_db_prepare_input($_POST['pID']);
    $categories_id = tep_db_prepare_input($_POST['ID']);
    $new_parent_id = tep_db_prepare_input($_POST['move_to_category_id']);
    tep_db_query("UPDATE " . TABLE_CDS_PAGES_TO_CATEGORIES . " 
                          SET categories_id = '" . (int)$new_parent_id . "'
                          WHERE pages_id = '" . (int)$pID . "'");

    $menuname_query = tep_db_query("SELECT pages_menu_name 
                                                           from " . TABLE_CDS_PAGES_DESCRIPTION . " 
                                                        WHERE pages_id = '" . (int)$pages_id . "'");
    $menu_name = tep_db_fetch_array($menuname_query);
    $menu_name = $menu_name['pages_menu_name'];
    $pages_menu_name_array = array($menu_name);
    $separated = implode('_',explode(' ',$pages_menu_name_array[0]));

    $sql_category_name = tep_db_query("SELECT categories_name 
                                                            from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " 
                                                          WHERE categories_id = " . (int)$categories_id . " 
                                                            and language_id= " . $languages_id);

    $category_name=tep_db_fetch_array($sql_category_name);
    $cat_name = $category_name['categories_name'];              
    $separated_cat_name = strtolower($cat_name);

    $sql=( "SELECT * 
                 from  " . TABLE_LANGUAGES . "");
    $sql_res = tep_db_query($sql);
    while ($result= tep_db_fetch_array($sql_res)) {
      $lang_directory = $result['directory'];
      $string_lang = strtolower( $lang_directory );
      $menu_name = strtolower($menu_name);
      $pages_menu_name_array = array($menu_name);
      $separated = strtolower(implode('_', explode(' ', $pages_menu_name_array[0])));

      $sql_file =("UPDATE " . TABLE_CDS_PAGES_DESCRIPTION. " 
                      SET pages_file = ('" .$separated_cat_name . '_' . $separated . '_' . $dup_pages_id . '.php'. "') 
                      WHERE pages_id='".$dup_pages_id."'");
      tep_db_query($sql_file);
    }
    tep_redirect(tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $new_parent_id . '&pID=' . $pID));
    break;

  case 'copyconfirm_page':
    if ( isset($_POST['pID']) ) {
      $pages_id = tep_db_prepare_input($_POST['pID']);
      $categories_id = tep_db_prepare_input($_POST['ID']);
      if ( $_POST['copy_as']  ==  'link')  {
        if ( $categories_id != $current_category_id ) {
          $check_query = tep_db_query("SELECT count(*) as total 
                                         from " . TABLE_CDS_PAGES_TO_CATEGORIES . " 
                                       WHERE pages_id = '" . (int)$pages_id . "'
                                         and categories_id = '" . (int)$categories_id . "'");
          $check = tep_db_fetch_array($check_query);
          if ( $check['total'] < '1' ) {
            $next_sort_value = cre_get_next_sort_value();
            tep_db_query("INSERT into " . TABLE_CDS_PAGES_TO_CATEGORIES . " (pages_id, categories_id, page_sort_order) VALUES ('" . (int)$pages_id . "', '" . (int)$categories_id . "', '" . (int)$next_sort_value . "')");
          }
        } else {
          $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
        }
      } elseif ($_POST['copy_as'] == 'duplicate') {
        $page_query = tep_db_query("SELECT pages_image, pages_date_added, pages_date_modified, pages_author, pages_status, pages_sort_order, pages_in_menu, pages_in_page_listing, pages_url, pages_append_cdpath, pages_url_target, pages_attach_product, pages_group_access    
                                      from " . TABLE_CDS_PAGES . " 
                                    WHERE pages_id = '" . (int)$pages_id . "'");;
        $page = tep_db_fetch_array($page_query);
        tep_db_query("INSERT into " . TABLE_CDS_PAGES . " (pages_image, pages_date_added, pages_date_modified, pages_author, pages_status, pages_sort_order, pages_in_menu, pages_in_page_listing, pages_url, pages_append_cdpath, pages_url_target, pages_attach_product, pages_group_access) 
                      VALUES ('" . $page['pages_image'] . "', '" . date("Y-m-d") . "', '" . date("Y-m-d") . "', '" . $page['pages_author'] . "', '0', '" . (int)$page['pages_sort_order'] . "', '" . (int)$page['pages_in_menu'] . "','" . (int)$page['pages_in_page_listing'] . "', '" . $page['pages_url'] . "', '" . $page['pages_append_cd_path'] . "', '" . $page['pages_url_target'] . "', '" . (int)$page['pages_attach_product'] . "', '" . $page['pages_group_access'] . "')");
        $dup_pages_id = tep_db_insert_id();

        $description_query = tep_db_query ("SELECT pages_id, language_id, pages_title, pages_meta_title, pages_meta_keywords, pages_meta_description, pages_blurb, pages_body, pages_menu_name, pages_file  
                                             from  " . TABLE_CDS_PAGES_DESCRIPTION . " 
                                           WHERE pages_id = '" . (int)$pages_id . "'");

        while ($description = tep_db_fetch_array($description_query)) {
          tep_db_query("INSERT into " . TABLE_CDS_PAGES_DESCRIPTION . " (pages_id, language_id, pages_title, pages_meta_title, pages_meta_keywords, pages_meta_description, pages_blurb, pages_body, pages_menu_name)
                        VALUES ('" . (int)$dup_pages_id . "', '" . (int)$description['language_id'] . "', '" . tep_db_input($description['pages_title']) . "','" . tep_db_input($description['pages_meta_title']) . "', '" . tep_db_input($description['pages_meta_keywords']) . "', '" . tep_db_input($description['pages_meta_description']) . "', '" . tep_db_input($description['pages_blurb']) . "', '" . tep_db_input($description['pages_body']) . "', '" . tep_db_input($description['pages_menu_name']) . "')");
        }

        $next_sort_value = (isset($cPath)) ? cre_get_next_sort_value() : 10;
        tep_db_query("INSERT into " . TABLE_CDS_PAGES_TO_CATEGORIES . " (pages_id, categories_id, page_sort_order) 
                      VALUES ('" . (int)$dup_pages_id . "', '" . (int)$categories_id . "', '" . (int)$next_sort_value . "')");

        $menuname_query = tep_db_query("SELECT pages_menu_name 
                                          from " . TABLE_CDS_PAGES_DESCRIPTION . " 
                                        WHERE pages_id = '" . (int)$pages_id . "'");
        $menu_name = tep_db_fetch_array($menuname_query);
        $menu_name = $menu_name['pages_menu_name'];
        $pages_menu_name_array = array($menu_name);
        $separated = implode('_',explode(' ',$pages_menu_name_array[0]));

        $sql_category_name = tep_db_query("SELECT categories_name 
                                             from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " 
                                           WHERE categories_id = " . (int)$categories_id . " 
                                             and language_id= " . $languages_id);
        $category_name=tep_db_fetch_array($sql_category_name);
        $cat_name = $category_name['categories_name'];              
        $separated_cat_name = strtolower($cat_name);

        $sql=( "SELECT * 
                  from  " . TABLE_LANGUAGES . "");
        $sql_res = tep_db_query($sql);
        while ($result= tep_db_fetch_array($sql_res)) {
          $lang_directory = $result['directory'];
          $string_lang = strtolower( $lang_directory );
          $menu_name = strtolower($menu_name);
          $pages_menu_name_array = array($menu_name);
          $separated = strtolower(implode('_', explode(' ', $pages_menu_name_array[0])));

          $sql_file =("UPDATE " . TABLE_CDS_PAGES_DESCRIPTION. " 
                          SET pages_file = ('" .$separated_cat_name . '_' . $separated . '_' . $dup_pages_id . '.php'. "') 
                          WHERE pages_id='".$dup_pages_id."'");
          tep_db_query($sql_file);
        }
      }
    }
    tep_redirect(tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $categories_id . '&pID=' . $pages_id));
    break;

  case 'setflag_page':
    $flag = (int)$_GET['flag'];
    if ( ($flag == '0') || ($flag == '1') ) {
      if ( tep_not_null($pID) ) {
        tep_db_query("UPDATE " . TABLE_CDS_PAGES . " 
                      SET pages_status = '" . $flag . "'
                      WHERE pages_id = '" . (int)$pID . "'");
      } 
    }
    tep_redirect(tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $pID));
    break;
        
  case 'setmenu_page':
    $flag = (int)$_GET['flag'];
    if ( ($flag == '0') || ($flag == '1') ) {
      if ( tep_not_null($pID) ) {
        tep_db_query("UPDATE " . TABLE_CDS_PAGES . " 
                      SET pages_in_menu = '" . $flag . "'
                      WHERE pages_id = '" . (int)$pID . "'");
      } 
    }
    tep_redirect(tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $pID));
    break;
        
  case 'setlisting_page':
    $flag = (int)$_GET['flag'];
    if ( ($flag == '0') || ($flag == '2') ) {
      if ( tep_not_null($pID) ) {
        tep_db_query("UPDATE " . TABLE_CDS_PAGES . " 
                      SET pages_in_page_listing = '" . $flag . "'
                      WHERE pages_id = '" . (int)$pID . "'");
      } 
    }
    tep_redirect(tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $pID));
    break;
        
  case 'edit_page':
    $pages_query = tep_db_query("SELECT ip.pages_id, ip.pages_image, ip.pages_date_added, ip.pages_date_modified, ip.pages_status, ip.pages_in_menu, ip.pages_in_page_listing, ip.pages_attach_product, ip2c.page_sort_order, ipd.pages_title, ipd.pages_blurb ,ipd.pages_file 
                                   from " . TABLE_PAGES_TO_CATEGORIES . " ip2c, 
                                          " . TABLE_CDS_PAGES . " ip
                                 LEFT JOIN " . TABLE_CDS_PAGES_DESCRIPTION . " ipd 
                                   on ip.pages_id = ipd.pages_id 
                                 WHERE ipd.language_id = '" . (int)$languages_id . "' 
                                   and ip2c.pages_id = '" . (int)$pID . "' 
                                   and ip.pages_id = '" . (int)$pID . "'");
    if ($pages = tep_db_fetch_array($pages_query)) {
      $pInfo = new objectInfo($pages);
    }
   break;

  case 'insert_page':
  case 'update_page':
    $languages = tep_get_languages();
    $pages_menu_name_array = tep_db_prepare_input($_POST['pages_menu_name']);
    for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
      $language_id = (int)$languages[$i]['id'];         
      $language_code = $languages[$i]['code'];
      $lang_default = (defined('DEFAULT_LANGUAGE') && DEFAULT_LANGUAGE != '') ? DEFAULT_LANGUAGE : 'en';
      if (($pages_menu_name_array[$language_id] == '') && ($language_code == $lang_default)) {
        $_SESSION['error_text'] = TEXT_CDS_ERROR_MENU_NAME;
        tep_redirect(tep_href_link(FILENAME_PAGES, 'cPath=' . $cPath . '&action=new_page'));
      }
    }
    $pages_title_array = tep_db_prepare_input($_POST['pages_title']);
        
    $pages_category = isset($_POST['pages_category']) ? (int)$_POST['pages_category'] : 0;
    $pages_blurb_array = tep_db_prepare_input($_POST['pages_blurb']);
    $pages_body_array = tep_db_prepare_input($_POST['pages_body']);
    $pages_meta_title_array = tep_db_prepare_input($_POST['pages_meta_title']);
    $pages_meta_keywords_array = tep_db_prepare_input($_POST['pages_meta_keywords']);
    $pages_meta_description_array = tep_db_prepare_input($_POST['pages_meta_description']);
    $pages_status = ((tep_db_prepare_input($_POST['pages_status']))); 
    $pages_sort_order = tep_not_null($_POST['pages_sort_order']) ? (int)$_POST['pages_sort_order'] : 0 ;
    $pages_in_menu = isset($_POST['pages_in_menu']) ? (int)tep_db_prepare_input($_POST['pages_in_menu']) : 0;
    $pages_show_link_in_listing = tep_db_prepare_input($_POST['pages_show_link_in_listing']);
    $pages_attach_product = (int)tep_db_prepare_input($_POST['pages_attach_product']);
    $sql_data_array = array('pages_status' => $pages_status,
                            'pages_sort_order' => $pages_sort_order,
                            'pages_in_menu' => (int)$pages_in_menu,
                            'pages_in_page_listing' => $pages_show_link_in_listing,
                            'pages_attach_product' => $pages_attach_product
                           );
    if ( $action == 'update_page' ) {
      $sql_data_array['pages_date_modified'] = 'now()';
      tep_db_perform(TABLE_CDS_PAGES, $sql_data_array, 'update', "pages_id = '" . (int)$pID . "'");
    } else {
      $sql_data_merge = array('pages_date_added' => 'now()' );
            $sql_data_array = array_merge($sql_data_array, $sql_data_merge);
      tep_db_perform(TABLE_CDS_PAGES, $sql_data_array);
      $pID = tep_db_insert_id();
    }

    // upload image
    if (isset($_POST['unlink_image']) && $_POST['unlink_image'] == 'yes' ) {
      $pages_image = '';
      $sql=("UPDATE " . TABLE_CDS_PAGES . " 
             SET pages_image = '" . $pages_image . "' 
             WHERE pages_id = '" . (int)$pID . "'");
      tep_db_query($sql);   
    } 
    if (isset($_POST['delete_image']) && $_POST['delete_image'] == 'yes') {
      @unlink(DIR_FS_CATALOG_IMAGES . $_POST['pages_previous_image']);
      $pages_image = '';
      $sql=("UPDATE " . TABLE_CDS_PAGES . " 
             SET pages_image = '" . $pages_image . "' 
             WHERE pages_id = '" . (int)$pID . "'");
      tep_db_query($sql); 
    }
    if (isset($_FILES['pages_image']) && tep_not_null($_FILES['pages_image']['name'])) {
      $pages_image = new upload('pages_image', DIR_FS_CATALOG_IMAGES);
      if($pages_image->filename) {
        tep_db_query("UPDATE " . TABLE_CDS_PAGES . " 
                      SET pages_image = '" . $pages_image->filename . "' 
                      WHERE pages_id = '" . (int)$pID . "'");
      }     
    } elseif (isset($_POST['pages_image']) && tep_not_null($_POST['pages_image'])) {
      tep_db_query("UPDATE " . TABLE_CDS_PAGES . " 
                      SET pages_image = '" . tep_db_prepare_input($_POST['pages_image']) . "' 
                      WHERE pages_id = '" . (int)$pID . "'");
    }
    // update description tables
    $sql_data_array = array();
    for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
      $language_id = (int)$languages[$i]['id'];         
      $language_code = $languages[$i]['code'];
      $lang_default = (defined('DEFAULT_LANGUAGE') && DEFAULT_LANGUAGE != '') ? DEFAULT_LANGUAGE : 'en';
      $sql_data_array = array('pages_title' => $pages_title_array[$language_id], 
                              'pages_menu_name' => $pages_menu_name_array[$language_id],
                              'pages_blurb' => $pages_blurb_array[$language_id], 
                              'pages_body' => $pages_body_array[$language_id], 
                              'pages_meta_title' => $pages_meta_title_array[$language_id], 
                              'pages_meta_keywords' => $pages_meta_keywords_array[$language_id], 
                              'pages_meta_description' => $pages_meta_description_array[$language_id]);
      if ( $action == 'insert_page' ) {
        $sql_data_array['pages_id'] = (int)$pID;
        $sql_data_array['language_id'] = (int)$language_id;
        tep_db_perform( TABLE_CDS_PAGES_DESCRIPTION, $sql_data_array );
      } else {
        tep_db_perform( TABLE_CDS_PAGES_DESCRIPTION, $sql_data_array, 'update', "pages_id = '" . (int)$pID . "' and language_id = '" . (int)$language_id . "'");
      }
    }

    // update category info
    if ( $action == 'update_page') {
      $sql_update_page = ("UPDATE " . TABLE_CDS_PAGES_TO_CATEGORIES . "
                           SET categories_id = '" . (int)$current_category_id . "', page_sort_order = '" . (int)$pages_sort_order . "'
                           WHERE pages_id = '" . (int)$pID . "'");
      tep_db_query($sql_update_page); 
    } else if( $action == 'insert_page' ) {
      $sql_page_list =("INSERT into " . TABLE_CDS_PAGES_TO_CATEGORIES . " (pages_id, categories_id, page_sort_order) 
                        VALUES ('" . (int)$pID . "', '" . (int)$current_category_id . "', '" . (int)$pages_sort_order . "')");
      tep_db_query($sql_page_list);
    }

    //Creating New ACF file If not exist on Update Page.
    for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
      $pagename = tep_pages_get_auxillary_file( $pID, $languages[$i]['id'] );
      $sql=("SELECT *
               from  " .TABLE_LANGUAGES. "");
      $sql_res =tep_db_query($sql);
      while ( $result=tep_db_fetch_array($sql_res) ) {
        $lang_directory = $result['directory'];
        $ourFilename = DIR_FS_DOCUMENT_ROOT . DIR_WS_LANGUAGES . $lang_directory . '/pages/' . $pagename;
        if (!file_exists($ourFilename) ) {
          $sql_file =("UPDATE " . TABLE_CDS_PAGES_DESCRIPTION . " 
                        SET pages_file = ('" . $pagename . "')
                        WHERE pages_id='".$pID."'");
          tep_db_query($sql_file); 
        }
      }
    }

    if ( $action == 'insert_page' ) {
      $cPath = isset($_POST['cPath']) ? $_POST['cPath'] : '';
      if ($cPath) {
        $findme   = '_';
        $pos = strpos($cPath, $findme);
        if (!$pos) {
          $sql_category_name = tep_db_query("SELECT categories_name 
                                               from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " 
                                             WHERE categories_id = ". $cPath . " and language_id= " . $languages_id);

          $category_name=tep_db_fetch_array($sql_category_name);
          $cat_name = $category_name['categories_name'];              
        } else {
          $var = explode('_', $cPath);
          $var1 = array();
          for($i=0; $i < sizeof($var); $i++) {
            $sql_category_name = tep_db_query("SELECT categories_name 
                                                 from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " 
                                               WHERE categories_id = " . $var[$i] . " and language_id= " . $languages_id);

            $category_name=tep_db_fetch_array($sql_category_name);
            $cat_name = $category_name['categories_name'];
            $var1[]=$cat_name; 
          }
        }
      }
      if (isset($var1)) {  
        $separated = implode('_',  $var1 );
      } else {
        $separated = isset($cat_name) ? $cat_name:'';
      }
      $separated_cat_name = cre_clean_filename(strtolower(implode('_', explode( ' ', $separated)))); 
      $pages_menu_name_array = $_POST['pages_menu_name'];
      $separated = implode('_', explode(' ', $pages_menu_name_array[1])); 
      $sql=("SELECT *
               from  " . TABLE_LANGUAGES . "");

      $sql_res = tep_db_query($sql);
      while ( $result=tep_db_fetch_array($sql_res) ) {
        $lang_directory = $result['directory'];
        $string_lang = strtolower($lang_directory);
        $pages_menu_name_array = $_POST['pages_menu_name'];
        $final_separated  = implode('_', explode( ' ', $pages_menu_name_array[1]));
        $separated = cre_clean_filename(strtolower($final_separated));
        if ($cPath) {
          $ourFilename = DIR_FS_DOCUMENT_ROOT . DIR_WS_LANGUAGES.$string_lang . '/' . 'pages/' . $separated_cat_name . '_' . $separated . '_' . $pID . '.php';
        } else {
          $ourFilename = DIR_FS_DOCUMENT_ROOT . DIR_WS_LANGUAGES.$string_lang. '/' . 'pages/' . $separated . '_' . $pID .'.php';
        } 
        if ($cPath) {
          $sql_file =("UPDATE " . TABLE_CDS_PAGES_DESCRIPTION. " 
                       SET pages_file = ('" .$separated_cat_name . '_' . $separated . '_' . $pID . '.php'. "') 
                       WHERE pages_id='".$pID."'");
        } else {
          $sql_file =(" UPDATE " . TABLE_CDS_PAGES_DESCRIPTION. " 
                        SET pages_file = ('" .$separated . '_' . $pID .'.php'. "') 
                        WHERE pages_id='".$pID."'");
        }
        tep_db_query($sql_file);
      }
      // update cds_acf_pages.txt file
      include_once(FILENAME_CDS_ACF_PAGES);
    }

    if ( $action == 'update_page' )  {
      $rename_check = isset($_POST['renamecheck']) ? tep_db_prepare_input( $_POST['renamecheck'] ) : '';
      if ( $rename_check == '1') {
        if ($cPath) {
          $separated_cat_name = cre_clean_filename(cre_get_cat_name($cPath));
        }
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $language_id = (int)$languages[$i]['id'];
          $lang_directory = $result['directory'];
          $pID = isset($_GET['pID']) ? (int)$_GET['pID'] : 0;
          $pagename = tep_pages_get_auxillary_file( $pID,  $languages[$i]['id'] );
          $final_separated = explode ( '_', $pagename);
          $acf_rename = trim($_POST['acf_newname']);
          $separated_filename = implode('_', explode(' ', $acf_rename));
          $separated = cre_clean_filename(strtolower($separated_filename));
          $sql = ("SELECT * 
                        from  " . TABLE_LANGUAGES . "");
          $sql_res = tep_db_query($sql);
          while ( $result = tep_db_fetch_array($sql_res) ) {
            $lang_directory = $result['directory'];
            $ourFilename = DIR_FS_DOCUMENT_ROOT . DIR_WS_LANGUAGES . $lang_directory . '/pages/' . $pagename;
            $changed_file = DIR_FS_DOCUMENT_ROOT . DIR_WS_LANGUAGES . $lang_directory . '/' . 'pages/' . $separated_cat_name . '_' . $separated . '_' . $pID . '.php'; 
            if (file_exists($ourFilename)) {
              rename ($ourFilename, $changed_file);
            }
            $sql_file =("UPDATE " . TABLE_CDS_PAGES_DESCRIPTION. " 
                            SET pages_file = ('" . $separated_cat_name . '_' . $separated . '_' . $pID . '.php'. "') 
                            WHERE pages_id='" . $pID."'");
            tep_db_query($sql_file);
          }
        }
      } // if $rename_check

    }
    // update cds_acf_pages.txt file
    include_once(FILENAME_CDS_ACF_PAGES);
    tep_redirect(tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $pID));
    break;

  case 'deleteconfirm_page':
    tep_pages_remove_page($pID);
    tep_redirect(tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $pID));
    break;

  case 'update_sort' :
    $page_sort_order = isset($_POST['page_sort_order']) ? $_POST['page_sort_order'] : array();
    $category_sort_order = isset($_POST['category_sort_order']) ? $_POST['category_sort_order'] : array();
    if ($page_sort_order) {
      while (list($key, $value) = each($page_sort_order)) {
        $sql_update_sort =("UPDATE " . TABLE_CDS_PAGES_TO_CATEGORIES. " 
                            SET  page_sort_order = $value
                            WHERE  pages_id = $key");

        tep_db_query($sql_update_sort); 
      }
    }
    while (list($key, $value) = each($category_sort_order))  {
      $sql_update_sort =("UPDATE " . TABLE_CDS_CATEGORIES . " 
                          SET categories_sort_order = $value
                          WHERE categories_id = $key");

      tep_db_query($sql_update_sort); 
    }
    break;
  }

  // check if the pages/ directory exists
  $sql=("SELECT * 
           from  " . TABLE_LANGUAGES . "");
  $sql_res =tep_db_query($sql);
  while ( $result=tep_db_fetch_array($sql_res) ) {
    $lang_directory = $result['directory'];
    $ourFilename=DIR_FS_DOCUMENT_ROOT . DIR_WS_LANGUAGES .$lang_directory. '/' . 'pages/';
    if (is_dir($ourFilename)) {
      if (!is_writeable($ourFilename) ) $messageStack->add(ERROR_CATALOG_PAGE_DIRECTORY_NOT_WRITEABLE, 'error');
    } else {
      $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
    }
  }
  // check if the catalog image directory exists
  if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!is_writeable(DIR_FS_CATALOG_IMAGES ) ) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {

    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php
if ($is_62) {
    echo '<script language="javascript" src="includes/menu.js"></script>' . "\n";
} else {
    echo '<script type="text/javascript" src="includes/prototype.js"></script>' . "\n";
    echo '<!--[if IE]>' . "\n";
    echo '<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">' . "\n";
    echo '<![endif]-->' . "\n";
}
?>
<script language="javascript" src="includes/general.js"></script>
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
<?php include('includes/javascript/editor.php');?>
<SCRIPT LANGUAGE="JavaScript">
  <!-- Begin
  function popUp(URL) {
    day = new Date();
    id = day.getTime();
    eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=500,height=600');");
  }
  function trim(str) {
    return str.replace(/^\s+|\s+$/g,"");
  }
  // End -->
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <?php
      if ( $action == 'edit_page' || $action == 'update_page' || $action == 'new_page' || $action == 'insert_page') {
        if ($action == 'edit_page' || $action == 'update_page') {
          $form_action = 'update_page' . '&pID=' . $pID;
        } else {
          $form_action = 'insert_page';
        }
        $title = ( $action=='new_page' ) ? HEADING_TITLE_NEW_PAGES : HEADING_TITLE_EDIT_PAGES;
        ?>
                <tr>
          <td>
            <?php 
            if (isset($_GET['pID']) ) {
              $form_action_text = 'Update';
              $form_action_button =  tep_image_submit('button_quick_save.gif',$form_action_text,'name="Operation" onClick="document.pressed=this.value" VALUE="'.$form_action_text.'"');
            } else {
              $form_action_text = 'Save';
              $form_action_button = tep_image_submit('button_quick_save.gif',$form_action_text,'name="Operation" onClick="document.pressed=this.value" VALUE="'.$form_action_text.'"');
            }
            $form_action_add_update = (isset($_GET['pID'])) ? 'update_page' : 'insert_page';
            
            for ( $i=0; $i < sizeof($languages); $i++) {
                $page_menu_elements .= '$F( "pages_menu_name[' . $languages[$i]['id'] . ']" )=="" || ';
            }            
            ?>
            <script language="JavaScript">
            function OnSubmitForm() {
                //if page_menu is blank, show alert and stop form submission
                if (<?php echo substr($page_menu_elements, 0, -3);?>){  
                    alert("<?php echo TEXT_CDS_ERROR_MENU_NAME;?>")  ;
                    return false;
                }
              //alert(document.pressed);
              //alert('<?php echo $form_action_text;?>');
              if (trim(document.pressed) == '<?php echo $form_action_text;?>' ) {
                document.new_product.action ="<?php echo str_replace('&amp;', '&', tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . '&action=' . $form_action_add_update)); ?>";
              } else if ( trim(document.pressed) == 'Preview') {
                document.new_product.action ="<?php echo str_replace('&amp;', '&', tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . '&action=new_page_preview')); ?>";
              } 
              //alert(document.new_product.action);
              return true;
            }
            </script>
            <form name="new_product" method="post" enctype="multipart/form-data" onSubmit="return OnSubmitForm();return checkdelete()">
            <?php
            $categories_array = array();
            $categories_array[] = array('id' => '', 'text' => TEXT_NO_CATEGORY);
            $categories_query = tep_db_query("SELECT icd.categories_id, icd.categories_name 
                                                from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " icd 
                                              WHERE language_id = '" . (int)$languages_id . "' order by icd.categories_name");
            while ( $categories_values = tep_db_fetch_array($categories_query) ) {
              $categories_array[] = array('id' => $categories_values['categories_id'], 'text' => $categories_values['categories_name']);
            }
            ?>
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
              <tr>
                <?php
                  $title = (isset($pInfo->pages_title)) ? $pInfo->pages_title : HEADING_TITLE_NEW_PAGES;
                ?>
                <td valign="top" class="pageHeading"><?php echo $title;  ?></td>
                <td align="right">
                  <?php 
                  echo '<a href="#" onClick="history.go(-1)">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;';
                  echo $form_action_button;
                                   ?>
                </td> 
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
            </table>
            <?php
            // Load Editor
            $pages_body_elements = '';
            $pages_blurb_elements = '';
            echo tep_load_html_editor();
            for ( $i=0; $i < sizeof($languages); $i++) {
              $pages_body_elements .= 'pages_body[' . $languages[$i]['id'] . '],'; 
              $pages_blurb_elements .= 'pages_blurb[' . $languages[$i]['id'] . '],'; 
            }
            if (CDS_WYSIWYG_ON_PAGE_BODY == 'Enabled') {
              echo tep_insert_html_editor($pages_body_elements,'advanced','500');
            }
            if (CDS_WYSIWYG_ON_PAGE_BLURB == 'Enabled') {
              echo tep_insert_html_editor($pages_blurb_elements,'simple','200');
            }
            ?>
            <div class="tab-pane" id="tabPane1">
              <script type="text/javascript">tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );</script>
              <?php
              $this_id = (isset($pInfo)) ? $pInfo->pages_id : '';
              for ($i=0; $i < sizeof($languages); $i++) {
                $acf_file_exists = false;
                ?>
                <div class="tab-page" id="<?php echo $languages[$i]['name'];?>">
                  <h2 class="tab"><nobr><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'],'align="middle" style="height:16px; width:30px;"') . '&nbsp;' .$languages[$i]['name'];?></nobr></h2>
                  <script type="text/javascript">tp1.addTabPage( document.getElementById( "<?php echo $languages[$i]['name'];?>" ) );</script>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="tab table">
                    <tr>
                      <td valign="top"><table border="0" cellspacing="0" cellpadding="2" width="100%">
                        <tr>
                          <td class="main"><b><?php echo ENTRY_TITLE; ?></b></td>
                          <td class="main"><?php echo tep_draw_input_field('pages_title[' . $languages[$i]['id'] . ']', tep_pages_get_page_title($this_id, $languages[$i]['id']),"size='50'"); ?></td>
                        </tr>
                        <tr>
                          <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                        </tr>
                        <tr>
                          <td class="main"><b><?php echo ENTRY_MENU_NAME; ?></b></td>
                          <td class="main"><?php 
                                                      echo tep_draw_input_field('pages_menu_name[' . $languages[$i]['id'] . ']', tep_pages_get_menu_name($this_id, $languages[$i]['id']), ' id="pages_menu_name[' . $languages[$i]['id'] . ']" ') . '&nbsp;<span class="errorText">*&nbsp;' . $error_text . '</span>'; 
                                                  if (isset($_SESSION['error_text'])) unset($_SESSION['error_text']);
                                                      ?>
                                                    </td>                                                                                                 
                        </tr>
                        <tr>
                          <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td><table width="100%" cellpadding="4" cellspacing="0" border="0">
                        <tr>
                          <td class="main"><b><?php  echo ENTRY_BLURB; ?></b></td>
                        </tr>
                        <tr>
                          <td class="main"><?php echo cre_cds_draw_textarea_field('pages_blurb[' . $languages[$i]['id'] . ']', 'soft', '100%', 3, stripslashes(tep_pages_get_page_blurb($this_id, $languages[$i]['id']))); ?></td>
                        </tr>  
                      </table></td>
                    </tr>
                    <tr>
                      <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>
                    <tr>
                      <td><table width="100%" cellpadding="4" cellspacing="0" border="0">
                        <tr>
                          <td class="main"><b><?php echo ENTRY_BODY; ?></b></td>
                        </tr>
                        <tr>
                          <td class="main"><?php echo cre_cds_draw_textarea_field('pages_body[' . $languages[$i]['id'] . ']', 'soft', '100%', 25, stripslashes(tep_pages_get_page_body($this_id, $languages[$i]['id']))); ?></td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>
                    <tr>
                      <td valign="top"><fieldset style="display:none;"> <legend>&nbsp;</legend></fieldset>
					  <fieldset>
                        <legend><?php echo META_TAG_INFORMATION; ?></legend>
                                                <table width="100%"  border="0" cellspacing="3" cellpadding="3">
                                                  <tr>
                                                      <td class="main"><strong><?php echo ENTRY_META_TITLE;?></strong></td>
                                                    </tr>
                                                    <tr>
                                                      <td class="main">
                                                          <?php
                              echo tep_draw_textarea_field('pages_meta_title[' . $languages[$i]['id'] . ']', 'soft', '15', '1', tep_pages_get_page_meta_title($this_id, $languages[$i]['id']),'style="width: 100%"');
                                                            ?>                                                                                        
                            </td>
                          </tr>
                        </table>
                        <table width="100%"  border="0" cellspacing="3" cellpadding="3">
                          <tr>
                            <td width="50%" class="main"><strong><?php echo ENTRY_META_DESCRIPTION;?></strong></td>
                            <td width="50%" class="main"><strong><?php echo ENTRY_META_KEYWORDS; ?></strong></td>
                          </tr>
                          <tr>
                            <td class="main">
                              <?php
                              echo tep_draw_textarea_field('pages_meta_description[' . $languages[$i]['id'] . ']', 'soft', '25', '5', tep_pages_get_page_meta_description($this_id, $languages[$i]['id']),'style="width: 100%"');                                                         
                              ?>
                            </td>
                            <td class="main">
                              <?php
                                echo tep_draw_textarea_field('pages_meta_keywords[' . $languages[$i]['id'] . ']', 'soft', '25', '5', tep_pages_get_page_meta_keywords ($this_id, $languages[$i]['id']), 'style="width: 100%"');
                              ?>
                            </td>
                          </tr>                                                                   
                                       </table>
                                       </fieldset></td>
                                        </tr>                                   
                    <tr>
                      <td>
                        <?php
                                                if( $action=='edit_page' ) { 
                                                  ?>
                          <fieldset><legend><?php echo TEXT_AUXILIARY_CONTENT_FILE; ?></legend>
                            <table width="100%" cellspacing="0" cellpadding="2" border="0">
                              <tr>               
                                <?php
                                $file_name = DIR_FS_DOCUMENT_ROOT . DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/pages/' . tep_pages_get_auxillary_file($this_id, $languages[$i]['id']);
                                $file_location =  DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/pages/';
                                if ( tep_pages_get_auxillary_file($this_id, $languages[$i]['id']) == '' )  {
                                  $cds_aux_file = 'Upload files';
                                } else if (file_exists($file_name) ) {
                                  $acf_file_exists = true;
                                  $cds_aux_file = '<a href="javascript:popUp(\'' . tep_href_link(FILENAME_CDS_POPUP,'action=view'.'&pID='.(int)$pID.'&language_id='.$languages[$i]['id']) . '\')">' . tep_image(DIR_WS_IMAGES . '/icons/preview.gif', TEXT_PREVIEW) . '<b>' . tep_pages_get_auxillary_file($this_id, $languages[$i]['id']) .'</b></a>';
                                } else {
                                  $cds_aux_file = '<span class="errorText">' . sprintf(TEXT_ACF_FILE_MISSING,tep_pages_get_auxillary_file($this_id, $languages[$i]['id']) ).'</span><br><span class="smallText">' . TEXT_ACF_FILE_PLEASE_UPLOAD . '<b>' . $file_location . '</b></span>';
                                }
                                echo '<td valign="middle" align="center" class ="main">'. $cds_aux_file .'</td>';     
                                $pID = isset($_GET['pID']) ? (int)$_GET['pID'] : '';
                                $pageid = 0;
                                $pageid = tep_pages_get_auxillary_file($this_id, $languages[$i]['id']); 
                                $pagename = "";
                                if ( $pageid != 0 ) {
                                  $pagename=explode ( '.',$pageid );
                                  $pagename=$pagename[0];
                                  $pos=strpos($pagename, "_" , 1);
                                  $pagename=substr($pagename, $pos + 1);   
                                }
                                $pageid=explode('_',$pageid);         
                                ?>
                              </tr>  
                            </table>
                          </fieldset>
                          <?php 
                            } 
                          ?>
                      </td> 
                    </tr> 
                  </table>
                </div>
                <?php 
                  } 
               ?>
            </div>
            <script type="text/javascript">setupAllTabs();</script>
          </td>
        </tr>
        <tr>
          <td></td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td>
                      <fieldset><legend><b><?php echo PAGES_IMAGES;?></b></legend>
              <table cellpadding="1" cellspacing="1" border="0" width="100%">
                <tr>
                  <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td class="dataTableRow" valign="top"><span class="main"><?php echo '&nbsp;' . TEXT_PAGES_THUMBNAIL_IMAGE; ?></span></td>
                  <td class="dataTableRow" valign="top"><span class="smallText">
                    <?php echo '&nbsp;' . tep_draw_file_field('pages_image') . '&nbsp;<span class="smallText">' . TEXT_NO_PAGE_DETAIL_IMAGE . '</span><br>'; ?> 
                  <?php if((isset($_GET['pID']) && (isset($pInfo->pages_image) && $pInfo->pages_image != ''))) echo $pInfo->pages_image . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->pages_image, $pInfo->pages_image,'' ,'' , 'align="left" hspace="0" vspace="5"') . tep_draw_hidden_field('pages_previous_image', $pInfo->pages_image) . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image" value="yes">' . TEXT_PAGES_IMAGE_REMOVE . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image" value="yes">' . TEXT_PAGES_IMAGE_DELETE . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span>
                                </td>
                </tr>
                <tr>
                  <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
              </table>
            </fieldset>
            <fieldset><legend><b><?php echo DISPLAY_MODES; ?></b></legend>
              <table cellpadding="3" cellspacing="1" border="0" width="100%">
                <tr  bgcolor="#F0F1F1">
                  <td class="main" width="25%"><?php echo ENTRY_STATUS; ?></td>
                  <td class="main" valign="middle">
                    <?php 
                    if (isset($pInfo->pages_status)) {
                      if ($pInfo->pages_status == 1) {
                        $active = "checked";
                        $inactive = "";
                      } else {
                        $active = "";
                        $inactive = "checked";
                      }
                    } else {
                      if  (CDS_DEFAULT_PAGE_STATUS == 'Active') {
                        $active = "checked";
                        $inactive = "";
                      } else {
                        $active = "";
                        $inactive = "checked";
                      }
                    }
                    echo tep_draw_radio_field('pages_status', 1 ,$active) . ' ' . TEXT_PAGES_ACTIVE . '&nbsp;&nbsp;' . tep_draw_radio_field('pages_status', 0 , $inactive) . ' ' . TEXT_PAGES_INACTIVE; 
                    ?>
                  </td>
                </tr>
                <tr bgcolor="#F0F1F1">
                  <td class="main" width="25%"><?php echo ENTRY_SORT_ORDER; ?></td>
                  <td class="main" valign="middle">
                    <?php
                    if (!isset($pInfo)) {
                      // find last sort order value and increment by 10
                      $value = isset($cPath) ? cre_get_next_sort_value() : 10;
                    } else {
                      $value = (int)$pInfo->page_sort_order;
                    }
                    echo '&nbsp;' . tep_draw_input_field('pages_sort_order', $value, 'size="4"'); 
                                ?>
                            </td>
                </tr>
                <tr bgcolor="#F0F1F1">
                  <td class="main" width='25%'><?php echo TEXT_SHOW_LINK; ?></td>
                  <td class="main" valign="middle">
                    <?php 
                    if ($action == 'edit_page') { 
                      $in_menu = ($pInfo->pages_in_menu == 1 ) ? 'checked' : '';
                      $in_listing = ($pInfo->pages_in_page_listing == 2  ) ? 'checked' : '';
                    } else {
                      $in_menu = (CDS_DEFAULT_PAGE_IN_MENU == 'On') ? 'checked' : '';
                      $in_listing = (CDS_DEFAULT_PAGE_IN_LISTING == 'On') ? 'checked' : '';
                    }
                    echo '<table><tr><td>' . tep_draw_checkbox_field('pages_in_menu', 1 , $in_menu) . '</td><td>' . TEXT_IN_MENU . '</td><td>' . tep_draw_checkbox_field('pages_show_link_in_listing', 2 , $in_listing) . '</td><td>' . TEXT_IN_PAGE_LISTING . '</td></tr></table>';
                    ?>
                  </td> 
                </tr>
                <tr bgcolor="#F0F1F1">
                  <td class="main"><?php echo TEXT_ATTACH_PRODUCT; ?></td>
                  <td class="main">
                                <?php 
                    $product_query =tep_db_query ("SELECT p.products_id, pd.products_name 
                                                                      from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd 
                                                                    WHERE p.products_id = pd.products_id 
                                                                      and pd.language_id = '" . $languages_id . "' 
                                                                    ORDER BY pd.products_name");

                    $product_list = array(array( 'id' => '', 'text' => TEXT_SELECT_PRODUCT));
                    while ($product = tep_db_fetch_array($product_query)) {
                      $product_list[] = array('id' => $product['products_id'], 
                                                                      'text' => $product['products_name']
                                                        );  
                    }
                    if (isset($pInfo->pages_attach_product)) {
                      echo '&nbsp;' . tep_draw_pull_down_menu('pages_attach_product', $product_list, $pInfo->pages_attach_product); 
                    } else {
                      echo '&nbsp;' . tep_draw_pull_down_menu('pages_attach_product', $product_list); 
                    }
                    ?>
                  </td>
                </tr>
                <script>
                  function enabledisablerename(renamecheck) {
                    if ( renamecheck.checked )
                      document.new_product.acf_newname.disabled=false;
                    else
                      document.new_product.acf_newname.disabled=true;
                  }
                </script>
                <?php 
                          if ( $action == 'edit_page' ) { 
                  $pagename = ""; 
                  $pageid = $pInfo->pages_file;
                  $pagename = explode('.', $pageid);
                  $pagename = $pagename[0];
                  $length = strlen($pagename);
                  $pos = strrpos($pagename, "_");
                  $pagename = substr($pagename, 0, ($length-($length-$pos)));   
                  if (isset($cPath)) {
                    $cat_name = cre_get_cat_name($cPath);
                  }
                  if (!isset($cat_name)) {
                    $cat_name = '';
                  }
                  $cat_name .= '_';
                  $pagename = eregi_replace($cat_name, '', $pagename);
                          } 
                        ?>
              </table>
            </fieldset>
          </td>           
        </tr>
        <tr>
          <td><?php // echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td align="right" class="main"><table align="right" border="0">
        <tr>
          <td class="main" align="right">
            <?php
            echo tep_draw_hidden_field('cPath', $cPath);
            echo tep_image_submit('button_preview_save.gif', IMAGE_PREVIEW,'name="Operation" onClick="document.pressed=this.value" VALUE="Preview_page"'). '&nbsp;&nbsp;';
            if ( $form_action_text =='update') {
              echo  tep_image_submit('button_save.gif',IMAGE_SAVE,'name="Operation" onClick="document.pressed=this.value" VALUE="'.$form_action_text.'"');
            } elseif ($form_action_text == 'insert') {
              echo  tep_image_submit('button_save.gif',IMAGE_SAVE,'name="Operation" onClick="document.pressed=this.value" VALUE="'.$form_action_text.'"');
            }
            ?>
                </td>
          <td class="main" align="right"><?php echo  '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
            </tr>
          </table></form></td>
        </tr>
        <?php  
      } elseif ( isset($_GET['action']) && $_GET['action'] == 'new_page_preview') {
        $pID = isset($_GET['pID']) ? (int)$_GET['pID'] : 0;
        if ( tep_not_null($_POST )) {
          $pInfo = new objectInfo($_POST );
          $pages_title = isset($_POST['pages_title']) ? $_POST['pages_title'] : '';
          $pages_menu_name = isset($_POST['pages_menu_name']) ? $_POST['pages_menu_name'] : '';
          $pages_category = isset($_POST['pages_category']) ? (int)$_POST['pages_category'] : 0;
          $pages_blurb = isset($_POST['pages_blurb']) ? $_POST['pages_blurb'] : '';
          $pages_body = isset($_POST['pages_body']) ? $_POST['pages_body'] : '';
          $pages_meta_title = isset($_POST['pages_meta_title']) ? $_POST['pages_meta_title'] : '';
          $pages_meta_keywords = isset($_POST['pages_meta_keywords']) ? $_POST['pages_meta_keywords'] : '';
          $pages_meta_description = isset($_POST['pages_meta_description']) ? $_POST['pages_meta_description'] : '';
          $pages_status = isset($_POST['pages_status']) ? (int)$_POST['pages_status'] : 0;
          $pages_sort_order = isset($_POST['pages_sort_order']) ? (int)$_POST['pages_sort_order'] : 0;
          $pages_in_menu = isset($_POST['pages_in_menu']) ? (int)$_POST['pages_in_menu'] : 0;
          $pages_show_link_in_listing = isset($_POST['pages_show_link_in_listing']) ? (int)$_POST['pages_show_link_in_listing'] : 0;
          $pages_attach_product = isset($_POST['pages_attach_product']) ? (int)$_POST['pages_attach_product'] : 0;
          $pages_image_name = isset($_FILES['pages_image']['name']) ? $_FILES['pages_image']['name'] : '';
        } else {
                  $pages_query = tep_db_query("SELECT ip.pages_id, ip.pages_image, ip.pages_date_added, ip.pages_date_modified, ip.pages_status,ip.pages_in_menu,ip.pages_in_page_listing,ip.pages_attach_product,ip.pages_sort_order, ipd.pages_title, ipd.pages_blurb ,ipd.pages_file
                                         from " . TABLE_CDS_PAGES . " ip ,
                                              " . TABLE_CDS_PAGES_DESCRIPTION . " ipd 
                                         WHERE ip.pages_id = ipd.pages_id
                                                           and ip.pages_id = '" . (int)$_GET['pID'] . "'");
                  $pages = tep_db_fetch_array($pages_query);
                  $pInfo = new objectInfo($pages);
                  $pages_title = $pInfo->pages_title;
                  $pages_blurb = $pInfo->pages_blurb;
                  $pages_body  = $pInfo->pages_body;
            }
      
                // upload image
                if ((isset($_POST['unlink_image'])) && ($_POST['unlink_image'] == 'yes'))  {
                    $pages_image = '';
                    $sql=("UPDATE " . TABLE_CDS_PAGES . " 
                                  SET pages_image = '" . $pages_image . "' 
                                  WHERE pages_id = '" . (int)$pID . "'");
                    tep_db_query($sql);   
                } 
                if ((isset($_POST['delete_image'])) && ($_POST['delete_image'] == 'yes'))  {
                    unlink(DIR_FS_CATALOG_IMAGES . $_POST['pages_previous_image']);
                    $pages_image = '';
                    $sql=("UPDATE " . TABLE_CDS_PAGES . " 
                                  SET pages_image = '" . $pages_image . "' 
                                  WHERE pages_id = '" . (int)$pID . "'");
                    tep_db_query($sql); 
                }
                
                if (isset($_FILES['pages_image']) && tep_not_null($_FILES['pages_image']['name'])) {
                  $pages_image = new upload('pages_image', DIR_FS_CATALOG_IMAGES);
                  if($pages_image->filename) {
                      tep_db_query("UPDATE " . TABLE_CDS_PAGES . " 
                                                  SET pages_image = '" . $pages_image->filename . "' 
                                                  WHERE pages_id = '" . (int)$pID . "'");
                  }     
                } 
            
                $form_action = (isset($_GET['pID'])) ? 'update_page' : 'insert_page';
                //echo tep_draw_form('preview_page', FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $_GET['pID'] . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"'); 
                echo tep_draw_form('preview_page', FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $_GET['pID'] . '&action=new_page', 'post', 'enctype="multipart/form-data"'); 
                echo '<table width="100%" border="0" cellpadding="4" cellspacing="2" summary="Preview Table">' . "\n";
                reset($_POST);
                while (list($key, $value) = each($_POST)) {
                    if (is_array($value)) {
                        while (list($k, $v) = each($value)) {
                            echo tep_draw_hidden_field($key . '[' . $k . ']', htmlspecialchars(stripslashes($v))) . "\n";
                        }
                    } else {
                    echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value))) . "\n";
                    }
                    /*
                    // these are not necessary when you can get all posts from above code
                    $languages = tep_get_languages();
                    for ($i=0; $i < sizeof($languages); $i++) {
                        echo tep_draw_hidden_field('pages_title[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($pages_title[$languages[$i]['id']])));
                        echo tep_draw_hidden_field('pages_menu_name[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($pages_menu_name[$languages[$i]['id']])));
                        echo tep_draw_hidden_field('pages_blurb[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($pages_blurb[$languages[$i]['id']])));
                        echo tep_draw_hidden_field('pages_body[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($pages_body[$languages[$i]['id']])));
                        echo tep_draw_hidden_field('pages_meta_title[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($pages_meta_title[$languages[$i]['id']])));
                        echo tep_draw_hidden_field('pages_meta_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($pages_meta_description[$languages[$i]['id']])));
                        echo tep_draw_hidden_field('pages_meta_keywords[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($pages_meta_keywords[$languages[$i]['id']])));
                    }
                    echo tep_draw_hidden_field('pages_sort_order', stripslashes($pages_sort_order));
                    echo tep_draw_hidden_field('pages_in_menu', stripslashes($pages_in_menu));
                    echo tep_draw_hidden_field('pages_show_link_in_listing', stripslashes($pages_show_link_in_listing));
                    echo tep_draw_hidden_field('pages_attach_product', stripslashes($pages_attach_product));
                    echo tep_draw_hidden_field('pages_status', stripslashes($pages_status));
                    */
                }
               // echo tep_draw_hidden_field('pages_image', $pages_image_name);
                $languages = tep_get_languages();
                for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                    if (isset($_GET['read']) && ($_GET['read'] == 'only')) {
                        $pInfo->pages_title = tep_pages_get_page_title($pInfo->pages_id, $languages[$i]['id']);
                        $pInfo->pages_blurb = tep_pages_get_page_blurb($pInfo->pages_id, $languages[$i]['id']);
                        $pInfo->pages_body = tep_pages_get_page_body($pInfo->pages_id, $languages[$i]['id']); 
                    } else {
                        $pInfo->pages_title = tep_db_prepare_input($pages_title[$languages[$i]['id']]);
                        $pInfo->pages_blurb = tep_db_prepare_input($pages_blurb[$languages[$i]['id']]);
                        $pInfo->pages_body = tep_db_prepare_input($pages_body[$languages[$i]['id']]);
                    }
                    ?>
                    
                        <tr>
                            <td><table width="100%" border="0" cellpadding="4" cellspacing="2">
                                <tr>
                                    <td class="pageHeading"><?php 
                                        echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' .($pInfo->pages_title); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="main"><?php  echo ($pInfo->pages_blurb);   ?> </td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo ($pInfo->pages_body) ?></td>
                                </tr>
                                <tr>
                                    <td class="main">
                                        <?php  
                                        //Including the ACF file in the Preview.
                                        if ($pID){
                                            $pagename = tep_pages_get_auxillary_file($pID, $languages[$i]['id']);
                                            $file_name = (DIR_FS_DOCUMENT_ROOT.DIR_WS_LANGUAGES . $languages[$i]['directory'] . '/pages/' . $pagename);
                                            if (file_exists($file_name) && is_file($file_name)) {
                                                include($file_name);
                                            }
                                        }
                                        // EOF Including the ACF file in the Preview.
                                        ?> 
                                    </td>
                                </tr>
                            </table></td>
                        </tr>
                    <?php
                        } // end for
                    ?>
                        <tr>
                            <td align="right">  
                                <?php 
                                //echo '<a href="#" onClick="history.go(-1)">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;&nbsp';
                                echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;' . "\n";
                                if(!($_GET['read'] == 'only')) {
//                                    if ( tep_not_null( $_GET['pID'] ) ) { 
                                        echo '<a href="javascript:check_save();">' . tep_image_button('button_save.gif', IMAGE_SAVE ,'') . '</a>';
//                                    } 
                                    echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $_GET['pID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                  </form>

        <script type="text/javascript">
          function check_save() {
            document.preview_page.action = '<?php echo str_replace('&amp;', '&', tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $_GET['pID'] . '&action=' . $form_action)); ?>';
            document.preview_page.submit();
          }
        </script>
        <?php
      } elseif ($action == 'edit_category' || $action == 'update_category' || $action == 'new_category' || $action == 'insert_category') {
        if ($action == 'edit_category' || $action == 'update_category') {
                    $form_action = 'update_category' . '&cID=' . (int)$cInfo->categories_id;
                } else {
                    $form_action = 'insert_category';
                }
                $pages_category_query = tep_db_query("select icd.categories_name, icd.categories_heading, ic.categories_image from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " icd, " . TABLE_CDS_CATEGORIES . " ic where ic.categories_id = icd.categories_id and icd.categories_id = '" . (int)$cID . "' and icd.language_id = '" . (int)$languages_id . "'");
                $pages_category = tep_db_fetch_array($pages_category_query);
                $cname = ($cID == '') ? ("") : (" : " . $pages_category['categories_name']); 
                $title = ($action=='new_category') ? HEADING_TITLE_NEW_PAGES_CATEGORY : HEADING_TITLE_EDIT_PAGES_CATEGORY;
                $image_string = tep_not_null($pages_category['categories_image']) ? tep_image(DIR_WS_CATALOG_IMAGES . $pages_category['categories_image'], $pages_category['categories_name'], PAGES_IMAGE_WIDTH, PAGES_IMAGE_HEIGHT) : '&nbsp;';
              for ($i=0; $i < sizeof($languages); $i++) {
                $categories_name_elements .= '$F( "categories_name[' . $languages[$i]['id'] . ']" )=="" || ';
              } 
                ?>
            <script language="JavaScript">
            function OnSubmitForm() {
                if (<?php echo substr($categories_name_elements, 0, -3);?>){  
                    alert("<?php echo TEXT_CDS_ERROR_MENU_NAME;?>")  ;
                    return false;
                }
            }
            </script>
                <tr>
                    <td>
                        <?php echo tep_draw_form('pages', FILENAME_CDS_PAGE_MANAGER, 'action=' . $form_action . '&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"  onSubmit="return OnSubmitForm();"'); ?>
                        <table border="0" width="100%" cellspacing="0" cellpadding="2" align="center">
                     <tr>
                   <td>
                     <table border="0" cellspacing="0" cellpadding="0" width="100%">
                       <tr>
                         <?php
                         $title = (isset($cInfo)) ? $pages_category['categories_heading'] : HEADING_TITLE_NEW_PAGES_CATEGORY;
                         ?>
                         <td valign="top" class="pageHeading"><?php echo $title;  ?></td>
                                   <td align="right"><?php  echo '<a href="#" onClick="history.go(-1)">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?>&nbsp;
                         <?php echo (($action == 'edit_category') ? tep_image_submit('button_save.gif', IMAGE_SAVE) : tep_image_submit('button_save.gif', IMAGE_SAVE));?>
                         </td>
                       </tr>
                       <tr>
                         <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                       </tr>
                     </table>
                   </td>
                   </tr>
                          <tr>
                                <td><table border="0" cellspacing="0" cellpadding="2" width="100%" align="center">
                                    <tr>
                                        <td colspan="2" class="main" valign="top" width="100%"><table width="100%"  border="0" cellspacing="0" cellpadding="0"> 
                                            <tr>
                                                <td class="main" valign="top"><?php
                                                    // editor functions
                          $category_elements = '';
                          $category_blurb_elements = '';
                                                    echo tep_load_html_editor();
                                                    for ($i=0; $i < sizeof($languages); $i++) {
                                                        $category_elements .= 'categories_description[' . $languages[$i]['id'] . '],'; 
                                                        $category_blurb_elements .= 'categories_blurb[' . $languages[$i]['id'] . '],'; 
                                                    } 
                          if (CDS_WYSIWYG_ON_CATEGORY_BODY == 'Enabled') {
                            echo tep_insert_html_editor($category_elements,'advanced','500');
                          }
                          if (CDS_WYSIWYG_ON_CATEGORY_BLURB == 'Enabled') {
                            echo tep_insert_html_editor($category_blurb_elements,'simple','200');
                          }
                                                    // editor functions eof
                                                    ?>
                                                    <div class="tab-pane" id="tabPane2">
                                                        <script type="text/javascript">tp2 = new WebFXTabPane( document.getElementById( "tabPane2" ) );</script> 
                                                        <?php
                            $this_id = (isset($cInfo)) ? $cInfo->categories_id : '';
                                                        for ($i=0; $i < sizeof($languages); $i++) { 
                                                            ?>
                                                            <div class="tab-page" id="<?php echo $languages[$i]['name'];?>">
                                                                <h2 class="tab"><nobr><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'],'align="middle" style="height:16px; width:30px;"') . '&nbsp;' .$languages[$i]['name'];?></nobr></h2>
                                                                <script type="text/javascript">tp2.addTabPage( document.getElementById( "<?php echo $languages[$i]['name'];?>" ) );</script>
                                                                <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="tab table">
                                                              <tr>
                                                                <td valign="top"><table border="0" cellspacing="0" cellpadding="2">
                                                                     <tr>
                                                                     <td class="main"><b><?php echo TEXT_PAGES_CATEGORY_HEADING; ?></b></td>
                                       <td class="main"><?php
                                         echo tep_draw_input_field ('categories_heading[' . $languages[$i]['id'] . ']',tep_pages_get_category_heading($this_id, $languages[$i]['id']),'size="50"'); ?>
                                       </td>
                                     </tr>  
                                     <tr>
                                       <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                                     </tr>
                                     <tr>
                                       <td class="main"><b><?php echo TEXT_PAGES_CATEGORIES_NAME; ?></b></td>
                                       <td class="main"><?php
                                                                              echo tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', tep_pages_get_category_name($this_id, $languages[$i]['id']), ' id="categories_name[' . $languages[$i]['id'] . ']" ') . '&nbsp;<span class="errorText">*&nbsp;' . $error_text . '</span>'; 
                                                                              if (isset($_SESSION['error_text'])) unset($_SESSION['error_text']);
                                                                              ?></td>
                                                                            </tr>
                                                                        </table></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><table width="100%" cellpadding="4" cellspacing="0" border="0">
                                                                            <tr>
                                                                                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="main"><b><?php  echo ENTRY_BLURB; ?></b></td>
                                                                            </tr>
                                                                            <tr>
                                                                    <td class="main"><?php
                                                                    echo cre_cds_draw_textarea_field('categories_blurb[' . $languages[$i]['id'] . ']', 'soft', '100%', 3, stripslashes(tep_pages_get_category_blurb($this_id, $languages[$i]['id']))); ?>
                                                                                                                                        </td>
                                                                            </tr>
                                                                        </table></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><table width="100%" cellpadding="4" cellspacing="0" border="0">
                                                                            <tr>
                                                                                <td class="main"><b><?php  echo TEXT_PAGES_CATEGORIES_DESCRIPTION; ?></b></td>
                                                                            </tr>
                                                                            <tr>
                                                                    <td class="main"><?php
                                                                    echo cre_cds_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '100%', 25, stripslashes(tep_pages_get_category_description($this_id, $languages[$i]['id']))); ?>
                                                                    </td>
                                                                            </tr>
                                                                        </table></td> 
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                                                                    </tr>                     
                                  <tr>
                                    <td valign="top"><fieldset style="display:none;"> <legend>&nbsp;</legend></fieldset>
									<fieldset>
                                      <legend><?php echo META_TAG_INFORMATION; ?></legend>
                                                                          <table width="100%"  border="0" cellspacing="3" cellpadding="3">
                                                                              <tr>
                                                                                  <td class="main"><strong><?php echo ENTRY_META_TITLE;?></strong></td>
                                                                                  </tr>
                                                                                <tr>
                                                                                  <td class="main">
                                                                                      <?php
                                                                        echo tep_draw_textarea_field('categories_meta_title[' . $languages[$i]['id'] . ']', 'soft', '15', '1', tep_pages_get_category_meta_title($this_id, $languages[$i]['id']),'style="width: 100%"');
                                                                        ?>
                                          </td>
                                        </tr>
                                      </table>
                                      <table width="100%"  border="0" cellspacing="3" cellpadding="3">
                                        <tr>
                                          <td width="50%" class="main"><strong><?php echo ENTRY_META_DESCRIPTION;?></strong></td>
                                          <td width="50%" class="main"><strong><?php echo ENTRY_META_KEYWORDS; ?></strong></td>
                                        </tr>
                                        <tr>
                                          <td class="main">
                                            <?php
                                                                        echo tep_draw_textarea_field('categories_meta_description[' . $languages[$i]['id'] . ']', 'soft', '25', '5', tep_pages_get_category_meta_description($this_id, $languages[$i]['id']),'style="width: 100%"');                                                         
                                            ?>
                                          </td>
                                          <td class="main">
                                            <?php
                                                                        echo tep_draw_textarea_field('categories_meta_keywords[' . $languages[$i]['id'] . ']', 'soft', '25', '5', tep_pages_get_meta_keyword_category ($this_id, $languages[$i]['id']), 'style="width: 100%"');
                                            ?>
                                          </td>
                                        </tr>                                                                   
                                                                     </table>
                                                                     </fieldset></td>
                                                                      </tr>
                                                                </table>
                                                            </div>
                                                            <?php 
                                                        } // end for
                                                        ?>
                                                    </div>
                                                    <script type="text/javascript">
                                                        //<![CDATA[
                                                        setupAllTabs();
                                                        //]]>
                                                    </script>
                                                </td>
                                            </tr>
                                        </table></td>
                                    </tr>
                                </table></td>
                            </tr>
                            <tr>
                                <td>
                                    <fieldset><legend><!-- CATEGORY Images --><?php echo TEXT_CATEGORY_IMAGES;?></legend>
                                    <table width="100%" cellpadding="2" cellspacing="2" border='0'>
                                        <tr>
                                            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '4'); ?></td>
                                        </tr> 
                                        <tr>
                                            <td class="dataTableRow" valign="top"><span class="main"><?php echo TEXT_PAGES_CATEGORIES_IMAGE; ?></span></td>
                                            <td class="dataTableRow" valign="top">
                                                <span class="smallText">
                                                <?php echo '&nbsp;' .tep_draw_file_field('categories_image') . '&nbsp;'.TEXT_CATEGORY_IMAGE.  '<br>'; ?>
                                                <?php 
                                                if ((isset($_GET['cID'])) && ($cInfo->categories_image) != '') {
                                                    echo $cInfo->categories_image . tep_image(DIR_WS_CATALOG_IMAGES . $cInfo->categories_image, $cInfo->categories_image, '', '', 'align="left" hspace="0" vspace="5"') . tep_draw_hidden_field('catagory_previous_image', $cInfo->categories_image) . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;
                                                    <input type="checkbox" name="unlink_catagory_image" value="yes">' . TEXT_CATAGORY_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_catagory_image" value="yes">' . TEXT_CATAGORY_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); 
                                                }                                        
                                                ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="dataTableRow" valign="top"><span class="main"><?php echo ENTRY_HEADING_TITLE_IMAGE; ?></span></td>
                                            <td class="dataTableRow" valign="top">
                                                <span class="smallText">
                                                <?php echo '&nbsp;' .tep_draw_file_field('category_heading_title_image') .'&nbsp;'.TEXT_TITLE_IMAGE.  '<br>'; ?>
                                                <?php 
                                                if ((isset($_GET['cID'])) && ($cInfo->category_heading_title_image) != '') {
                                                    echo $cInfo->category_heading_title_image . tep_image(DIR_WS_CATALOG_IMAGES . $cInfo->category_heading_title_image, $cInfo->category_heading_title_image, '', '', 'align="left" hspace="0" vspace="5"') . tep_draw_hidden_field('catagory_heading_previous_image', $cInfo->category_heading_title_image) . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_heading_image" value="yes">' . TEXT_CATAGORY_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_heading_image" value="yes">' . TEXT_CATAGORY_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); 
                                                }
                                                ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="dataTableRow" valign="top"><span class="main"><?php echo ENTRY_CATEGORY_PAGE_BANNER_IMAGE; ?></span></td>
                                            <td class="dataTableRow" valign="top">
                                                <span class="smallText">
                                                <?php echo '&nbsp;' . tep_draw_file_field('category_header_banner').'&nbsp;'.TEXT_BANNER_IMAGE. '<br>'; ?>

                                                <?php 
                                                if ((isset($_GET['cID'])) && ($cInfo->category_header_banner) != '') {
                                                    echo $cInfo->category_header_banner . tep_image(DIR_WS_CATALOG_IMAGES . $cInfo->category_header_banner, $cInfo->category_header_banner, '', '', 'align="left" hspace="0" vspace="5"') . tep_draw_hidden_field('catagory_banner_previous_image', $cInfo->category_header_banner) . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_banner_image" value="yes">' . TEXT_CATAGORY_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_banner_image" value="yes">' . TEXT_CATAGORY_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); 
                                                }
                                                ?></span>
                                            </td>
                                        </tr>
                                    </table>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td><table width="100%" cellpadding="2" cellspacing="2" border='0'>
                                    <tr>
                                        <td width="100%"><fieldset><legend><b><?php echo DISPLAY_MODES; ?></b></legend>
                                            <table width="100%" cellpadding="2" cellspacing="2" border="0">
                                                <tr>
                                                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
                                                </tr>
                                                <tr bgcolor="#F0F1F1">
                                                    <td class="main"><?php echo TEXT_PAGES_CATEGORIES_STATUS; ?></td>
                                                    <td class="main"><?php 
                                   if (isset($cInfo->categories_status)) {
                                     if ($cInfo->categories_status == 1) {
                                       $active = "checked";
                                       $inactive = "";
                                     } else {
                                       $active = "";
                                       $inactive = "checked";
                                     }
                                   } else {
                                     if  (CDS_DEFAULT_CATEGORY_STATUS == 'Active') {
                                       $active = "checked";
                                       $inactive = "";
                                     } else {
                                       $active = "";
                                       $inactive = "checked";
                                     }
                                   }
                                   echo tep_draw_radio_field('categories_status', 'on', $active) . ' ' . TEXT_PAGES_ACTIVE . '&nbsp;&nbsp;' . tep_draw_radio_field('categories_status', 'off', $inactive) . ' ' . TEXT_PAGES_INACTIVE; ?>
                                                    </td>
                                                </tr>
                                                <tr bgcolor="#F0F1F1">
                                                    <td class="main"><?php echo TEXT_PAGES_CATEGORIES_SORT_ORDER; ?></td>
                                                    <td class="main">
                                   <?php
                                         // find last sort order value and increment by 10                                                                                
                                         if (!isset($cInfo->categories_sort_order)) {
                                     $value = (isset($cPath)) ? cre_get_next_sort_value() : 10;
                                   } else {
                                     $value = (isset($cInfo->categories_sort_order)) ? (int)$cInfo->categories_sort_order : 10;
                                   }
                                                        echo '&nbsp;' . tep_draw_input_field('categories_sort_order', $value, 'size="2"'); ?>
                                                    </td>
                                                </tr>
                                                <tr bgcolor="#F0F1F1">
                                                    <td class="main"><?php echo PAGES_SUB_CATEGORY_VIEW; ?></td>
                                                    <td class="main"><?php
                                                                              $nested = '';
                                                                              $flat = '';
                                       if (isset($cInfo)) { 
                                     if ($cInfo->categories_sub_category_view == 1 ) {
                                                             $nested = 'checked';
                                     } else {
                                                            $flat = 'checked';
                                                          } 
                                   } else {
                                      $nested = 'checked';
                                   }
                                                        echo tep_draw_radio_field('categories_sub_category_view', 'on', $nested) . ' ' . TEXT_PAGES_NESTED . '&nbsp;&nbsp;' . tep_draw_radio_field('categories_sub_category_view', 'off', $flat ) . ' ' . TEXT_PAGES_FLAT; ?>
                                                    </td>
                                                </tr>
                                                <tr bgcolor="#F0F1F1">
                                                    <td class="main"><?php echo PAGES_LISTING_COLUMNS; ?></td>
                                     <td class="main"><?php
                                                                            $one = '';
                                                                              $two = '';
                                                                              $three = '';
                                                                              $four = '';
                                                                            if (isset($cInfo->categories_listing_columns)) {
                                                        if ( $cInfo->categories_listing_columns == 1 ) {
                                                            $one = 'checked';
                                                        } elseif( $cInfo->categories_listing_columns == 2 ) {
                                                            $two = 'checked';
                                                        } elseif( $cInfo->categories_listing_columns == 3 ) {
                                                            $three = 'checked';
                                                        } elseif($cInfo->categories_listing_columns == 4 ) {
                                                            $four = 'checked';
                                                        } else {
                                                            $two = 'checked';
                                                        } 
                                                                            } else {
                                                                              $two = 'checked';
                                                                            }
                                                        echo tep_draw_radio_field('categories_listing_columns', '1', $one) . ' ' . '1' .'&nbsp;('. WITH_DESC_LEFT .')'. '&nbsp;&nbsp;' . tep_draw_radio_field('categories_listing_columns', '2', $two) . ' ' . '1' . '&nbsp;&nbsp;' . tep_draw_radio_field('categories_listing_columns', '3', $three) . ' ' . '2' . '&nbsp;&nbsp;' . tep_draw_radio_field('categories_listing_columns', '4', $four ) . ' ' . '3'; ?>
                                                    </td>
                                                </tr>
                                                <tr bgcolor="#F0F1F1">
                          <td class="main"><?php echo TEXT_SHOW_LINK; ?></td>                                             
                          <td class="main">
                            <?php
                            if ($action == 'edit_category') { 
                                         $in_menu = (isset($cInfo->categories_in_menu) && $cInfo->categories_in_menu == 1) ? 'checked' : '';
                                         $in_listing = (isset($cInfo->categories_in_pages_listing) && $cInfo->categories_in_pages_listing == 2) ? 'checked' : '';
                            } else {
                              $in_menu = (CDS_DEFAULT_CATEGORY_IN_MENU == 'On') ? 'checked' : '';
                              $in_listing = (CDS_DEFAULT_CATEGORY_IN_LISTING == 'On') ? 'checked' : '';
                            }
                            echo tep_draw_checkbox_field('category_show_link','1', $in_menu) . ' ' . TEXT_IN_MENU . '&nbsp;&nbsp;' . tep_draw_checkbox_field('category_show_link1','2', $in_listing) . ' ' .TEXT_IN_CATEGORY_LISTING; ?>
                          </td>                                           
                        </tr>
                        <tr bgcolor="#F0F1F1">
                          <td class="main"><?php echo TEXT_PAGE_CATEGORY_URL_OVERRIDE; ?></td>
                          <td class="main"><table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                              <td class="main" width="390"><?php echo '&nbsp;' . tep_draw_input_field('categories_url_override', (isset($cInfo->categories_url_override) ? $cInfo->categories_url_override : ''), 'size="60"'); ?></td>
                              <td class="smallText" valign="top" align="left"><table cellpadding="0" cellspacing="0" border="0"> 
                                <tr>
                                  <td valign="top">
                                    <?php
                                    $checked = '';
                                    if (isset($cInfo->category_append_cdpath) && $cInfo->category_append_cdpath == 1 ) {
                                      $checked = "checked";
                                    } else {
                                      if (!isset($cInfo)) {
                                        $checked = "checked";
                                      }
                                    }                                   
                                    echo tep_draw_checkbox_field('category_append_cdpath','1', $checked); ?></td><td class="smallText" valign="top"><?php echo TEXT_APPEND_CATEGORY; 
                                    ?>
                                  </td>
                                </tr>  
                              </table></td>
                                                            <td class="smallText" valign="top" align="left">
                            </tr>
                          </table></td>
                        </tr>
                        <tr bgcolor="#F0F1F1">
                          <td class="main"><?php echo TEXT_OVERRIDE_TARGET ; ?></td>
                          <td class="main"><table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                              <td class="smallText" valign="top"><table> 
                                <tr>
                                  <td class="main" valign="middle" ><?php echo tep_draw_input_field('categories_url_override_target', (isset($cInfo->categories_url_override_target) ? $cInfo->categories_url_override_target : ''), 'size="30"'); ?><span class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '5') . TEXT_TARGET_PAGE; ?></span></td>
                                </tr>  
                              </table></td>
                            </tr>                                                     
                                                  </table></td>
                                                </tr>
                        <tr bgcolor="#F0F1F1">
                          <td class="main"><?php echo TEXT_ATTACH_PRODUCT; ?></td>
                          <td class="main">
                            <?php 
                            $product_query =tep_db_query ("SELECT p.products_id, pd.products_name 
                                                             from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd 
                                                           WHERE p.products_id = pd.products_id 
                                                             and pd.language_id = '" . $languages_id . "' 
                                                           ORDER BY pd.products_name");

                            $product_list = array(array( 'id' => '', 'text' => TEXT_SELECT_PRODUCT));
                            while ($product = tep_db_fetch_array($product_query)) {
                              $product_list[] = array('id' => $product['products_id'], 
                                                      'text' => $product['products_name']
                                                     );  
                            }
                            if (isset($cInfo->categories_attach_product)) {
                              echo '&nbsp;' . tep_draw_pull_down_menu('categories_attach_product', $product_list, $cInfo->categories_attach_product); 
                            } else {
                              echo '&nbsp;' . tep_draw_pull_down_menu('categories_attach_product', $product_list); 
                            }
                            ?>
                          </td>
                        </tr>                                               
                        <?php
                        //RCI start
                        echo $cre_RCI->get('pages', 'displayoptions');
                        //RCI end
                        ?>
                        <tr>
                          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
                        </tr>
                      </table>
                    </fieldset></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td align="right" class="main"><?php                 
                echo (($action == 'edit_category') ? tep_image_submit('button_save.gif', IMAGE_SAVE) : tep_image_submit('button_save.gif', IMAGE_SAVE)) . ' <a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . (($action == 'edit_category') ? '&cID=' . (int)$cInfo->categories_id : '')) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
              </tr></form>              
            </table>            
          </td>
        </tr>
        <?php             
} else { 
        ?>
                <tr>
                    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <?php 
                            echo tep_draw_form('search', FILENAME_CDS_PAGE_MANAGER ); 
                            echo tep_draw_hidden_field(tep_session_name(), tep_session_id()); 
                            $pages_cid = 0;
                            if (tep_not_null($cPath)) {
                                $cPath_array = tep_pages_parse_categories_path($cPath);
                                $cPath = implode('_', $cPath_array);
                                $pages_cid = $cPath_array[(sizeof($cPath_array)-1)];
                            } 
                            $pages_category_query = tep_db_query("SELECT icd.categories_name, ic.categories_image, ic.categories_url_override, ic.categories_url_override 
                                                                               from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " icd, " . TABLE_CDS_CATEGORIES . " ic 
                                                                             WHERE ic.categories_id = icd.categories_id 
                                                                               and icd.categories_id = '" . (int)$pages_cid . "'
                                                                               and icd.language_id = '" . (int)$languages_id . "'");

                 $pages_category = tep_db_fetch_array($pages_category_query);
                 $current_folder_icon = ($pages_category['categories_url_override'] != '') ? 'cds_override_folder_icon.gif' : 'cds_current_folder_icon.gif';
                 $cname = ($pages_cid == '') ? ("") : ("<table><tr><td>" . tep_image(DIR_WS_ICONS . $current_folder_icon, ICON_FOLDER) . '</td><td class="pageHeading">' . $pages_category['categories_name'] . '</td></tr></table>'); 
                 $url_override_text = ($pages_category['categories_url_override'] != '') ? '(url override: ' .  '<a href="' . $pages_category['categories_url_override'] . '" target="_blank">' . $pages_category['categories_url_override'] . '</a>)' : '';
                            ?>
                 <td valign="bottom">
                   <table border="0" width="100%" cellspacing="0" cellpadding="0">
                     <tr>
                       <td class="pageHeading" valign="bottom"><?php echo ($cname != '') ? $cname : HEADING_TITLE; ?></td>
                     </tr>
                     <tr>
                       <td class="smallText">&nbsp;<?php echo $url_override_text; ?></td>
                     </tr>
                   </table>
                  </td>
                            <td valign="bottom">
                   <table border="0" width="100%" cellspacing="0" cellpadding="0">
                     <tr>
                       <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?></td></form>
                     </tr>
                     <tr><td><?php echo tep_draw_separator('pixel_trans.gif', 1, 4); ?></td></tr>
                     <tr>
                       <td class="smallText" align="right">
                         <?php
                         echo tep_draw_form('goto', FILENAME_CDS_PAGE_MANAGER, '', 'get');
                         echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', tep_get_pages_category_tree(), $current_categories_id, 'onChange="this.form.submit();"');
                         echo tep_draw_hidden_field(tep_session_name(), tep_session_id());
                         echo '</form>';
                         ?>
                       </td>
                     </tr>
                   </table>
                 </td>
                        </tr>
                    </table></td>
                </tr>
          <tr><td><?php echo tep_draw_separator('pixel_trans.gif', 1, 2); ?></td></tr>
                <tr>
                    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="top">
                                <table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
                                    <tr class="dataTableHeadingRow">
                                        <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PAGES_CATEGORIES; ?></td>
                                        <td class="dataTableHeadingContent" align="center" width="10%"><?php echo TABLE_HEADING_STATUS; ?></td>
                                        <td class="dataTableHeadingContent" align="center" width="10%"><?php echo TABLE_HEADING_MENU; ?></td>
                                        <td class="dataTableHeadingContent" align="center" width="10%"><?php echo TABLE_HEADING_LISTING; ?></td>
                                        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRODUCT; ?></td>
                                        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_SORT_ORDER; ?></td>
                                        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                                    </tr>
                                    <?php
                                    $search = '';
                                    $search1 = '';
                                    if (isset($_POST['search']) && tep_not_null($_POST['search'])) {
                                        $keywords = tep_db_input(tep_db_prepare_input($_POST['search']));
                                        $search = " and icd.categories_name like '%" . $keywords . "%'";
                                        $search1 ="and pd.pages_title like '%" . $keywords . "%'";
                                        if (tep_not_null($cPath) ) {
                                            $cPath_array = tep_pages_parse_categories_path($cPath);
                                            $cPath = implode('_', $cPath_array);
                                            $current_categories_id = $cPath_array[(sizeof($cPath_array)-1)];
                                        } else {
                                            $current_categories_id = 0;
                                        }
$sql_listing = " SELECT ic.categories_id as 'ID', ic.categories_parent_id as 'CID', ic.categories_sort_order as 'Sort', ic.categories_status as 'Status', icd.categories_name as 'Title', ic.categories_image as 'Image', icd.categories_description as 'body', ic.categories_date_added as 'date_added',ic.categories_last_modified as 'date_modified',ic.categories_url_override as 'URL' , 'c' as 'type', ic.categories_in_menu as 'Menu', ic.categories_in_pages_listing as 'Listing', ic.categories_attach_product as 'products_id' 
                      from " . TABLE_CDS_CATEGORIES . " ic 
                  LEFT JOIN " . TABLE_CDS_CATEGORIES_DESCRIPTION . " icd 
                      on ic.categories_id = icd.categories_id 
                  WHERE  icd.language_id = '" . (int)$languages_id . "'" . $search . " 
                  UNION
                  SELECT p.pages_id as 'ID', p2c.categories_id as 'CID', p2c.page_sort_order as 'Sort', p.pages_status as  'Status', pd.pages_title as 'Title', p.pages_image as 'Image',  pd.pages_body as 'body',  p.pages_date_added as 'date_added', p.pages_date_modified as 'date_modified',p.pages_url as 'URL' , 'p' as 'type', p.pages_in_menu as 'Menu', p.pages_in_page_listing as 'Listing', p.pages_attach_product as 'products_id'
                      from " . TABLE_CDS_PAGES . " p, 
                                " . TABLE_CDS_PAGES_DESCRIPTION . "  pd, 
                                " . TABLE_CDS_PAGES_TO_CATEGORIES . " p2c 
                  WHERE p.pages_id = pd.pages_id 
                      and pd.language_id ='" . (int)$languages_id . "'" . $search1 . "
                      and p.pages_id = p2c.pages_id  
                  ORDER BY Sort, Title";
} else {
$sql_listing = " SELECT ic.categories_id as 'ID', ic.categories_parent_id as 'CID', ic.categories_sort_order as 'Sort', ic.categories_status as 'Status', icd.categories_name as 'Title',ic.categories_image as 'Image', icd.categories_description as 'body',  ic.categories_date_added as 'date_added', ic.categories_last_modified as 'date_modified', ic.categories_url_override as 'URL' ,'c' as 'type', ic.categories_in_menu as 'Menu', ic.categories_in_pages_listing as 'Listing' , ic.categories_attach_product as 'products_id'  
        from " . TABLE_CDS_CATEGORIES . " ic 
    LEFT JOIN " . TABLE_CDS_CATEGORIES_DESCRIPTION . " icd 
        on ic.categories_id = icd.categories_id 
    WHERE ic.categories_parent_id = '" . (int)$current_categories_id . "' 
        and icd.language_id = '" . (int)$languages_id . "'
    UNION
    SELECT p.pages_id as 'ID', p2c.categories_id as 'CID', p2c.page_sort_order as 'Sort', p.pages_status as  'Status', pd.pages_title as 'Title',p.pages_image as 'Image', pd.pages_blurb as 'body', p.pages_date_added as 'date_added', p.pages_date_modified as 'date_modified' ,p.pages_url as 'URL', 'p' as 'type' , p.pages_in_menu as 'Menu', p.pages_in_page_listing as 'Listing', p.pages_attach_product as 'products_id'
        from " . TABLE_CDS_PAGES . " p, 
                  " . TABLE_CDS_PAGES_DESCRIPTION . "  pd, 
                  " . TABLE_CDS_PAGES_TO_CATEGORIES . " p2c 
    WHERE p.pages_id = pd.pages_id 
        and pd.language_id ='" . (int)$languages_id . "'
        and p.pages_id = p2c.pages_id 
        and p2c.categories_id ='" . (int)$current_categories_id . "'
    ORDER BY Sort, Title";
                                    }
//print($sql_listing);

  $listing_query = tep_db_query($sql_listing);
  $category_count = 0;
  $cnt = 0;
  $page_count = 0;
  echo (tep_draw_form('categories_sort', FILENAME_CDS_PAGE_MANAGER, 'action=update_sort&cPath=' . $cPath));
  while ( $categories = tep_db_fetch_array ( $listing_query ) ) {
      if (isset($_POST['search'])) $cPath = $categories['CID'];
      //print("<br>categories-type : ".$categories['type']."<br>");
      if ($categories['type'] == 'c') {
          $category_count++;
      } 
      if ($categories['type'] == 'p') {
          $page_count++;
      } 
      $cnt++;
      if ( (!isset($_GET['pID']) && !isset($_GET['cID']) || (isset($_GET['pID']) && ($_GET['pID'] == $categories['ID']))) && !isset($pInfo) && !isset($cInfo) && (substr($action, 0, 3) != 'new') && $categories['type'] == 'p') {
          $pInfo = new objectInfo($categories);
      } 
      if ( ( !isset($_GET['cID'] ) && !isset( $_GET['pID']) || ( isset($_GET['cID']) && ( $_GET['cID'] == $categories['ID']) ) ) && !isset($cInfo) && (substr($action, 0, 3) != 'new') && $categories['type'] == 'c') {
          $categories_count = array('categories_count' => tep_pages_get_categories_count($categories['ID']));
          $pages_count = array('pages_count' => tep_pages_get_pages_count($categories['ID']));
          $cInfo_array = array_merge($categories, $categories_count, $pages_count);
          $cInfo = new objectInfo($cInfo_array);
      } 
      if ($categories['type'] == 'c') {
          if (isset($cInfo) && is_object($cInfo) && ($categories['ID'] == $cInfo->ID) ) {
              echo ' <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)"> ' . "\n";
              echo '<td class="dataTableContent" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, tep_pages_get_categories_path($categories['ID'])) . '\'">';
          } else {
              echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
              echo '<td class="dataTableContent" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $categories['ID']) . '\'">';
          }
      } else if($categories['type'] == 'p')  {
          if (isset($pInfo) && is_object($pInfo) && ($categories['ID'] == $pInfo->ID)) {
              echo ' <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)"> ' . "\n";
              echo '<td class="dataTableContent" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $pInfo->ID . '&action=edit_page') . '\'">';
          } else {
              echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
              echo '<td class="dataTableContent" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $categories['ID']) . '\'">';
          }
      }
      if ($categories['type'] == 'c') {
          if ($categories['URL']) {
              echo '<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, tep_pages_get_categories_path($categories['ID'])) . '">' . tep_image(DIR_WS_ICONS . 'folder_white.gif', ICON_URL_OVERRIDE) . '</a>&nbsp;<b>' . $categories['Title'] . '</b>'; 
          } else {
              echo '<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, tep_pages_get_categories_path($categories['ID'])) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>&nbsp;<b>' . $categories['Title'] . '</b>'; 
          }
      } else if($categories['type']=='p') {
          echo '<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $categories['ID'] . '&action=new_page_preview&read=only') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $categories['Title']; 
      }
      echo "</td>";
      if ($categories['type'] == 'c') {
          if (isset($cInfo) && is_object($cInfo) && ($categories['ID'] == $cInfo->ID) ) {
              echo '<td  class="dataTableContent" align="center" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, tep_pages_get_categories_path($categories['ID'])) . '\'">';
          } else {
              echo '<td  class="dataTableContent" align="center" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $categories['ID']) . '\'">';
          } 
      } else if ($categories['type'] == 'p') {
          if (isset($pInfo) && is_object($pInfo) && ($categories['ID'] == $pInfo->ID)) {
              echo '<td class="dataTableContent" align="center" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $pInfo->ID . '&action=edit_page') . '\'">';
          } else {
              echo '<td class="dataTableContent" align="center" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $categories['ID']) . '\'">';
          }
      }
      if ($categories['type'] == 'c') {
          if ($categories['Status'] == '1') {
              echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'action=setflag_category&flag=0&cID=' . $categories['ID'] . '&cPath=' . $cPath, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
          } else {
              echo '<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'action=setflag_category&flag=1&cID=' . $categories['ID'] . '&cPath=' . $cPath, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
          }
      } else if ($categories['type'] == 'p') {
          if ($categories['Status'] == '1') {
              echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'action=setflag_page&flag=0&pID=' . $categories['ID'] . '&cPath=' . $cPath, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
          } else {
              echo '<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'action=setflag_page&flag=1&pID=' . $categories['ID'] . '&cPath=' . $cPath, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
          }
      }
      echo '</td>';
      if ($categories['type'] == 'c') {
          if (isset($cInfo) && is_object($cInfo) && ($categories['ID'] == $cInfo->ID) ) {
              echo '<td  class="dataTableContent" align="center" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, tep_pages_get_categories_path($categories['ID'])) . '\'">';
          } else {
              echo '<td  class="dataTableContent" align="center" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $categories['ID']) . '\'">';
          } 
      } else if ($categories['type'] == 'p') {
          if (isset($pInfo) && is_object($pInfo) && ($categories['ID'] == $pInfo->ID)) {
              echo '<td class="dataTableContent" align="center" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $pInfo->ID . '&action=edit_page') . '\'">';
          } else {
              echo '<td class="dataTableContent" align="center" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $categories['ID']) . '\'">';
          }
      }
      if ($categories['type'] == 'c') {
          if ($categories['Menu'] == '1') {
              echo '<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'action=setmenu_category&flag=0&cID=' . $categories['ID'] . '&cPath=' . $cPath, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icons/cds_admin_menu_on.gif', CDS_IMAGE_ICON_STATUS_ACTIVE, 10, 10) . '</a>';
          } else {
              echo '<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'action=setmenu_category&flag=1&cID=' . $categories['ID'] . '&cPath=' . $cPath, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icons/cds_admin_menu_off.gif', CDS_IMAGE_ICON_STATUS_INACTIVE, 10, 10) . '</a>';
          }
      } else if ($categories['type'] == 'p') {
          if ($categories['Menu'] == '1') {
              echo '<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'action=setmenu_page&flag=0&pID=' . $categories['ID'] . '&cPath=' . $cPath, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icons/cds_admin_menu_on.gif', CDS_IMAGE_ICON_STATUS_ACTIVE, 10, 10) . '</a>';
          } else {
              echo '<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'action=setmenu_page&flag=1&pID=' . $categories['ID'] . '&cPath=' . $cPath, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icons/cds_admin_menu_off.gif', CDS_IMAGE_ICON_STATUS_INACTIVE, 10, 10) . '</a>';
          }
      }
      echo "</td>";
      if ($categories['type'] == 'c') {
          if (isset($cInfo) && is_object($cInfo) && ($categories['ID'] == $cInfo->ID) ) {
              echo '<td  class="dataTableContent" align="center" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, tep_pages_get_categories_path($categories['ID'])) . '\'">';
          } else {
              echo '<td  class="dataTableContent" align="center" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $categories['ID']) . '\'">';
          } 
      } else if ($categories['type'] == 'p') {
          if (isset($pInfo) && is_object($pInfo) && ($categories['ID'] == $pInfo->ID)) {
              echo '<td class="dataTableContent" align="center" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $pInfo->ID . '&action=edit_page') . '\'">';
          } else {
              echo '<td class="dataTableContent" align="center" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $categories['ID']) . '\'">';
          }
      }
      if ($categories['type'] == 'c') {
          if ($categories['Listing'] == '2') {
              echo '<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'action=setlisting_category&flag=0&cID=' . $categories['ID'] . '&cPath=' . $cPath, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icons/cds_admin_listing_on.gif', CDS_IMAGE_ICON_STATUS_ACTIVE, 10, 10) . '</a>';
          } else {
              echo '<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'action=setlisting_category&flag=1&cID=' . $categories['ID'] . '&cPath=' . $cPath, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icons/cds_admin_listing_off.gif', CDS_IMAGE_ICON_STATUS_INACTIVE, 10, 10) . '</a>';
          }
      } else if ($categories['type'] == 'p') {
          if ($categories['Listing'] == '2') {
              echo '<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'action=setlisting_page&flag=0&pID=' . $categories['ID'] . '&cPath=' . $cPath, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icons/cds_admin_listing_on.gif', CDS_IMAGE_ICON_STATUS_ACTIVE, 10, 10) . '</a>';
          } else {
              echo '<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'action=setlisting_page&flag=2&pID=' . $categories['ID'] . '&cPath=' . $cPath, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icons/cds_admin_listing_off.gif', CDS_IMAGE_ICON_STATUS_INACTIVE, 10, 10) . '</a>';
          }
      }
      echo '</td>';
      if ($categories['type'] == 'c') {
          if (isset($cInfo) && is_object($cInfo) && ($categories['ID'] == $cInfo->ID) ) {
              echo '<td  class="dataTableContent" align="right" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, tep_pages_get_categories_path($categories['ID'])) . '\'">';
          } else {
              echo '<td  class="dataTableContent" align="right" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $categories['ID']) . '\'">';
          } 
      } else if ($categories['type'] == 'p') {
          if (isset($pInfo) && is_object($pInfo) && ($categories['ID'] == $pInfo->ID)) {
              echo '<td class="dataTableContent" align="right" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $pInfo->ID . '&action=edit_page') . '\'">';
          } else {
              echo '<td class="dataTableContent" align="right" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $categories['ID']) . '\'">';
          }
      }
      if ($categories['products_id'] != '0') {
        $products_model = tep_db_fetch_array(tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . $categories['products_id'] . "'"));
        if (tep_not_null($products_model['products_model'])) {
          echo $products_model['products_model'];
        } else {
          echo substr(tep_get_products_name($categories['products_id'], $languages_id), 0, 16);
        }
      }
                  echo '</td>';
   $debug =  (file_exists('debug.txt')) ? $categories['Sort'] : '';
                  if ($categories['type'] == 'c') { 
     $name = 'category_sort_order[' . $categories['ID'] . ']';
                      if (isset($cInfo) && is_object($cInfo) && ($categories['ID'] == $cInfo->ID) ) {
                          echo'<td class="dataTableContent" align="right">' . $debug; ?>
                          <input type="text" size="2" tabindex="<?php echo (10 * ($cnt)); ?>" name="<?php echo $name; ?>" value="<?php echo (10 * ($cnt)); ?>">
                          <?php 
        echo '</td>';
                      } else { echo'<td class="dataTableContent" align="right">' . $debug; ?>
                            <input type="text" size="2" tabindex="<?php echo (10 * ($cnt)); ?>" name="<?php echo $name; ?>" value="<?php echo (10 * ($cnt)); ?>" >
                          <?php  
                      } 
                  } else if($categories['type']=='p') {
                      if (isset($pInfo) && is_object($pInfo) && isset($pages['pages_id']) && ($pages['pages_id'] == $pInfo->pages_id)) {
                          echo'<td class="dataTableContent" align="right">' . $debug; ?>
                          <input type="text" size="2" tabindex="<?php echo (10 * ($cnt)); ?>" name="page_sort_order[<?php echo $categories['ID']; ?>]" value="<?php echo (10 * ($cnt));?>">
                          <?php
                          echo '</td>';
                      } else { echo'<td class="dataTableContent" align="right">' . $debug; ?>
                                                <input type="text" size="2" tabindex="<?php echo (10 * ($cnt)); ?>" name="page_sort_order[<?php echo $categories['ID']; ?>]" value="<?php echo (10 * ($cnt)); ?>">
                                                <?php
                                                echo  '</td>';
                                              }
                                        }
                                        if ($categories['type'] == 'c') { 
                                            if (isset($cInfo) && is_object($cInfo) && ($categories['ID'] == $cInfo->ID) ) {
                                                echo '<td  class="dataTableContent" align="right" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, tep_pages_get_categories_path($categories['ID'])) . '\'">'.'<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $categories['ID'] . '&action=edit_category') . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', ICON_EDIT) . '</a>'.'&nbsp;&nbsp;'.tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', '').'</td>';
                                            } else  {
                                                echo '<td  class="dataTableContent" align="right" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $categories['ID']) . '\'">'.'<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $categories['ID']) . '">'.'<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $categories['ID'] .  '&action=edit_category') . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', ICON_EDIT) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a></td>';
                                            }
                                        } else if (($categories['type']=='p')) {
                                            if (isset($pInfo) && is_object($pInfo) && ($categories['ID'] == $pInfo->ID)) {
                                                echo '<td class="dataTableContent" align="right" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $pInfo->ID . '&action=edit_page') . '\'">'.'<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $categories['ID'] . '&action=edit_page') . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', ICON_EDIT) . '</a>&nbsp;&nbsp;'.tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', '').'</td>';
                                            } else {
                                                echo  '<td class="dataTableContent" align="right" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $categories['ID']) . '\'">'.'<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $categories['ID']. '&action=edit_page') . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', ICON_EDIT) . '</a>&nbsp; '.'<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $categories['ID']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a></td>';
                                            }
                                        } 
                                        echo "</td></tr> ";
                                    } // end while
                                    echo "</form>";
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
                                        <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                            <tr>
                                                <td class="smallText"><?php echo TEXT_CATEGORIES_COUNT . '&nbsp;' . $category_count . '<br>' . TEXT_PAGES_COUNT . '&nbsp;' . $page_count; ?></td>
                                                <td align="right" class="smallText"><?php 

                                    if ((isset($_GET['cPath']) || isset($_POST['cPath'])) && sizeof($cPath_array) > 0) echo '<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, $cPath_back . 'cID=' . $current_categories_id) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;'; 
                                                    if (!isset($_GET['search'])) echo '<a href="javascript:check_save_sort()">' . tep_image_button('button_update_sort.gif', IMAGE_UPDATE_SORT, 'tabindex="' . (10 * ($cnt)). '"') . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&action=new_category') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&action=new_page') . '">' . tep_image_button('button_new_page.gif', IMAGE_NEW_PAGE) . '</a>'; ?>&nbsp;
                                                </td>
                                            </tr>
                                            <script language="javascript" type="text/javascript">
                                                function check_save_sort() {
                                                    document.categories_sort.submit();
                                                }
                                            </script>
                                        </table></td>
                                    </tr>
                                </table>
                            </td>
                            <?php
                            $heading = array();
                            $contents = array();
            
                            switch ($action) {
                            
                            case 'delete_category':
                                $heading[] = array('text' => '<b>' . TEXT_PAGES_HEADING_DELETE_PAGES_CATEGORY . '</b>');
                                $contents = array('form' => tep_draw_form('categories_delete', FILENAME_CDS_PAGE_MANAGER, 'action=deleteconfirm_category&cPath=' . $cPath) . tep_draw_hidden_field('cID', $cInfo->ID));
                                $contents[] = array('text' => TEXT_DELETE_PAGES_CATEGORIES_INTRO);
                                $contents[] = array('text' => '<br><b>' . $cInfo->Title . '</b>');
                                if (isset($cInfo->categories_page_count) && $cInfo->categories_page_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PAGES, $cInfo->categories_page_count));
                                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $cInfo->ID) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                                break;
                                
                            case 'delete_page':
                                $heading[] = array('text' => '<b>' . TEXT_PAGES_HEADING_DELETE_PAGE . '</b>');
                                $contents = array('form' => tep_draw_form('pages_delete', FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cPath=' . $cPath . '&pID=' . $pInfo->ID . '&action=deleteconfirm_page'));
                                $contents[] = array('text' => TEXT_DELETE_INTRO . '<br><br><b>' . $pInfo->Title . '</b>');
                                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $pInfo->ID) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                                break;
                                
                            case 'move_category':
                                $heading[] = array('text' => '<b>' . TEXT_PAGES_HEADING_MOVE_PAGES_CATEGORY . '</b>');
                                $contents = array('form' => tep_draw_form('categories_move', FILENAME_CDS_PAGE_MANAGER, 'action=moveconfirm_category&cPath=' . $cPath) . tep_draw_hidden_field('cID', $cInfo->ID));
                                $contents[] = array('text' => sprintf(TEXT_MOVE_PAGES_CATEGORIES_INTRO, $cInfo->Title));
                                $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $cInfo->Title) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_pages_category_tree(), $current_category_id));
                                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $cInfo->ID) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                                break;
                            
                            case 'move_page':
                                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_PAGES . '</b>');
                                $contents = array('form' => tep_draw_form('pages_move', FILENAME_CDS_PAGE_MANAGER, 'action=moveconfirm_page&cPath=' . $cPath) . tep_draw_hidden_field('pID', $pInfo->ID));
                                $contents[] = array('text' => sprintf(TEXT_MOVE_PAGES_INTRO, $pInfo->Title));
                                $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_PAGES_CATEGORIES . '<br><b>' . tep_output_generated_pages_category_path($pInfo->ID, 'page') . '</b>');
                                $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $pInfo->Title) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_pages_category_tree(), $current_category_id));
                                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $pInfo->ID) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                                break;
            
                            case 'copy_to':        
                                $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO_PAGES . '</b>');
                                $contents = array('form' => tep_draw_form('pages_copy', FILENAME_CDS_PAGE_MANAGER, 'action=copyconfirm_page&cPath=' . $cPath) . tep_draw_hidden_field('pID', $pInfo->ID));
                                $contents[] = array('text' => TEXT_INFO_COPY_TO_PAGES_INTRO);
                                $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_PAGES_CATEGORIES . '<br><b>' . tep_output_generated_pages_category_path($pInfo->ID, 'page') . '</b>');
                                $contents[] = array('text' => '<br>' . sprintf(TEXT_COPY, $pInfo->Title) . '<br>' . tep_draw_pull_down_menu('ID', tep_get_pages_category_tree(), $current_category_id));
                                $contents[] = array('text' => '<br>' . TEXT_PAGES_HOW_TO_COPY . '<br>' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_PAGES_COPY_AS_LINK . '<br>' . tep_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE);
                                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', IMAGE_COPY) . ' <a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $pInfo->ID) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                                break;
            
                            default:
                                if ((isset($cInfo) && is_object($cInfo)) && ($cInfo->type == 'c')) {
                                    $heading[] = array('text' => '<b>' . $cInfo->Title . '</b>');
                                    $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $cInfo->ID . '&action=edit_category') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $cInfo->ID . '&action=delete_category') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a><br><a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $cInfo->ID . '&action=move_category') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a>');
                                    //RCI start
                                      $returned_rci = $cre_RCI->get('pages', 'sidebarcatbuttons');
                                      $contents[] = array('align' => 'center', 'text' => $returned_rci);
                                    //RCI eof
                                    $contents[] = array('text' => '<br>' . tep_info_image($cInfo->Image, $cInfo->Title, SMALL_IMAGE_WIDTH, SMALL_IMAGE_WIDTH) . '<br>' . $cInfo->Image);
                                    if (!$cInfo->URL) {
                                        $contents[] = array('text' => '<br>' . TEXT_PAGES_CATEGORY_COUNT . ' '  . $cInfo->pages_count);
                                        $contents[] = array('text' => TEXT_CATEGORIES_COUNT . ' '  . $cInfo->categories_count);
                                    } else {
                                        $contents[] = array('text' => '<br>' . TEXT_CDS_URL_OVERRIDE . ' ' . $cInfo->URL);
                                        $contents[] = array('text' => '<br>' . TEXT_DATE_PAGES_CATEGORY_CREATED . ' ' . tep_date_short($cInfo->date_added));
                                    }
                                } elseif((isset($pInfo) && is_object($pInfo)) && ($pInfo->type == 'p')) {
                                    $heading[] = array('text' => '<b>' . $pInfo->Title . '</b>');
                                    $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $pInfo->ID . '&action=edit_page') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $pInfo->ID . '&action=delete_page') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a><br><a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $pInfo->ID . '&action=move_page') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a> <a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&pID=' . $pInfo->ID. '&action=copy_to') . '">' . tep_image_button('button_copy_to.gif', IMAGE_COPY_TO) . '</a>');
                                    //RCI start
                                      $returned_rci = $cre_RCI->get('pages', 'sidebarbuttons');
                                      $contents[] = array('align' => 'center', 'text' => $returned_rci);
                                    //RCI eof
                                    $contents[] = array('align' => 'center', 'text' => '<br>' . tep_info_image($pInfo->Image, $pInfo->Title, SMALL_IMAGE_WIDTH, SMALL_IMAGE_WIDTH));
                                    $contents[] = array('text' => '<br>' . $pInfo->body);
                                    $contents[] = array('text' => '<br>' . TEXT_DATE_PAGES_CREATED . ' ' . tep_date_short($pInfo->date_added));
                                } else {
                                    if (!isset($cInfo->Title)) {
                                        $heading[] = array('text' => '&nbsp;');
                                        if (($page_count == 0) && ($category_count == 0)) {
                                            $contents[] = array('text' => TEXT_NO_CHILD_CATEGORIES_OR_PAGES);
                                        } else {
                           if (($categories['type']=='p') && ($categories['ID'] != '')) {
                             tep_redirect(tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'pID=' . $categories['ID']));
                           }
                                           $contents[] = array('text' => TEXT_CDS_NO_CATEGORIES_OR_PAGES_SELECTED);                                       }
                                    } else {
                                        $heading[] = array('text' => '<b>' . $cInfo->Title . '</b>');
                                        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $cInfo->ID . '&action=edit_category') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $cInfo->ID . '&action=delete_category') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a><br><a href="' . tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'cPath=' . $cPath . '&cID=' . $cInfo->ID . '&action=move_category') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a>');
                                        $contents[] = array('text' => '<br>' . tep_info_image($cInfo->Image, $cInfo->Title, SMALL_IMAGE_WIDTH, SMALL_IMAGE_WIDTH) . '<br>' . $cInfo->Image);
                                        $contents[] = array('text' => '<br>' . TEXT_DATE_PAGES_CATEGORY_CREATED . ' ' . tep_date_short($cInfo->date_added));
                                    }
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
                    </table></td>   
                </tr>
              <?php
          }
          ?>
        <!-- body_text_eof //-->
    </table></td>
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