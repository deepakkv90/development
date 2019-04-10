<?php
/*
  $Id: cds_functions.php,v 1.1 2007/07/28 11:21:11 datazen Exp $

  CRE Loaded, Commercial Open Source E-Commerce
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

function cre_pages_parse_categories_path($CDpath) {
  // make sure the category ids are integers
  $CDpath_array = array_map('tep_string_to_int', explode('_', $CDpath));
  // make sure no duplicate category ids exist which could lock the server in a loop
  $tmp_array = array();
  $n = sizeof($CDpath_array);
  
  for ($i = 0; $i < $n; $i++) {
      if ($CDpath_array[$i] == 0) continue;
      if (!in_array($CDpath_array[$i], $tmp_array)) {
          $tmp_array[] = $CDpath_array[$i];
      }
  }
    return $tmp_array;
}

function cre_get_listing_array($id = 0) {
  global $languages_id;
    
  // build the SQL
  $listing_sql = "SELECT ic.categories_id as 'ID', ic.categories_parent_id as 'parentID', ic.categories_sort_order as 'sort', ic.categories_image as 'image', icd.categories_heading as 'name', ic.categories_url_override as 'url', ic.categories_url_override_target as 'target', ic.category_append_cdpath as 'append', 'c' as 'type', ic.categories_sub_category_view as 'view', ic.categories_listing_columns as 'list_columns'   
                    from " . TABLE_CDS_CATEGORIES . " ic 
                  LEFT JOIN " . TABLE_CDS_CATEGORIES_DESCRIPTION . " icd 
                    on (ic.categories_id = icd.categories_id) 
                  WHERE ic.categories_status = '1' 
                    and icd.language_id = '" . (int)$languages_id . "'
                    and ic.categories_in_pages_listing = '2' 
                                    and ic.categories_parent_id = '" . $id . "' 
                  UNION
                  SELECT p.pages_id as 'ID', p2c.categories_id as 'parentID', p2c.page_sort_order as 'sort', p.pages_image as 'image', pd.pages_title as 'name', p.pages_url as 'url', p.pages_url_target as 'target', '' as 'append', 'p' as 'type', '' as 'view', '2' as 'list_columns' 
                    from " . TABLE_CDS_PAGES . " p, 
                         " . TABLE_CDS_PAGES_DESCRIPTION . "  pd, 
                         " . TABLE_CDS_PAGES_TO_CATEGORIES . " p2c 
                  WHERE p.pages_id = pd.pages_id 
                    and pd.language_id ='" . (int)$languages_id . "'
                    and p.pages_id = p2c.pages_id 
                    and p.pages_status = '1' 
                    and p.pages_in_page_listing = '2'
                                    and p2c.categories_id ='" . $id . "'
                                ORDER BY sort";
                            
  $listing_query = tep_db_query($listing_sql);
  $listing_check = tep_db_num_rows($listing_query);
    
    $CDpath_array = (isset($_GET['CDpath']) && $_GET['CDpath'] != '') ? cre_pages_parse_categories_path($_GET['CDpath']) : array();
    $pID = (isset($_GET['pID']) && $_GET['pID'] != '') ? (int)$_GET['pID'] : 0;
    
    $this_listing_array = array();
    if ($listing_check > 0) {                               
    while ($listing_result = tep_db_fetch_array($listing_query)) {
          if($listing_result['type'] == 'c') {
        $this_listing_array[] = array('name' => $listing_result['name'], 
                                              'ID' => $listing_result['ID'], 
                                              'parentID' => $listing_result['parentID'], 
                                              'sort' => $listing_result['sort'], 
                                              'image' => $listing_result['image'],                                                                            
                                              'type' => $listing_result['type'],
                                                                  'url' => $listing_result['url'],
                                                                  'target' => $listing_result['target'],
                                                                  'append' => $listing_result['append'],
                                                                  'view' => $listing_result['view'],
                                                                  'list_columns' => $listing_result['list_columns']                                                                                                                                                                     
                                             );
      } else {
        $this_listing_array[] = array('name' => $listing_result['name'], 
                                              'ID' => $listing_result['ID'], 
                                              'parentID' => $listing_result['parentID'], 
                                              'sort' => $listing_result['sort'],
                                                                            'image' => $listing_result['image'], 
                                              'type' => $listing_result['type'],
                                                                  'url' => $listing_result['url'],
                                                                  'target' => $listing_result['target'],
                                                                  'append' => $listing_result['append'],
                                                                  'view' => $listing_result['view'],
                                                                  'list_columns' => $listing_result['list_columns']                                                                                                                                                                                                                               
                                                                );
      }               
        } // end while
    }   
                                              
  return $this_listing_array; 
}

function cre_get_box_array($id = 0) {
  global $languages_id;
    
  // build the SQL
  $box_sql = "SELECT ic.categories_id as 'ID', ic.categories_parent_id as 'parentID', ic.categories_sort_order as 'sort', icd.categories_name as 'name', ic.categories_url_override as 'url', ic.categories_url_override_target as 'target', ic.category_append_cdpath as 'append', 'c' as 'type' 
                FROM " . TABLE_CDS_CATEGORIES . " ic 
              LEFT JOIN " . TABLE_CDS_CATEGORIES_DESCRIPTION . " icd 
                ON (ic.categories_id = icd.categories_id) 
              WHERE ic.categories_status = '1' 
                AND icd.language_id = '" . (int)$languages_id . "'
                AND ic.categories_in_menu = '1' 
                AND ic.categories_parent_id = '" . $id . "' 
              UNION
              SELECT p.pages_id as 'ID', p2c.categories_id as 'parentID', p2c.page_sort_order as 'sort', pd.pages_menu_name as 'name', p.pages_url as 'url', p.pages_url_target as 'target', ' ' as 'append', 'p' as 'type' 
                FROM " . TABLE_CDS_PAGES . " p, 
                     " . TABLE_CDS_PAGES_DESCRIPTION . "  pd, 
                     " . TABLE_CDS_PAGES_TO_CATEGORIES . " p2c 
              WHERE p.pages_id = pd.pages_id 
                AND pd.language_id ='" . (int)$languages_id . "'
                AND p.pages_id = p2c.pages_id 
                AND p.pages_status = '1' 
                AND p.pages_in_menu = '1'
                AND pd.pages_menu_name <> ''
                AND p2c.categories_id ='" . $id . "'
                ORDER BY sort";
                            
  $box_query = tep_db_query($box_sql);
  $box_check = tep_db_num_rows($box_query);
    
    $CDpath_array = (isset($_GET['CDpath']) && $_GET['CDpath'] != '') ? cre_pages_parse_categories_path($_GET['CDpath']) : array();
    $pID = (isset($_GET['pID']) && $_GET['pID'] != '') ? (int)$_GET['pID'] : 0;
    
    $box_array = array();
    if ($box_check > 0) {                               
    while ($box_result = tep_db_fetch_array($box_query)) {
          if($box_result['type'] == 'c') {
              $has_subs = cre_category_has_subs((int)$box_result['ID']);
                $selected = (in_array($box_result['ID'], $CDpath_array)) ? true : false;
        $box_array[] = array('name' => $box_result['name'], 
                                    'ID' => $box_result['ID'], 
                                    'parentID' => $box_result['parentID'], 
                                    'sort' => $box_result['sort'], 
                                    'type' => $box_result['type'],
                                                          'subs' => $has_subs,
                                                          'selected' => $selected,
                                                          'url' => $box_result['url'],
                                                          'target' => $box_result['target'],
                                                          'append' => $box_result['append']
                                    );
      } else {
              $selected = ($pID == $box_result['ID']) ? true : false;
        $box_array[] = array('name' => $box_result['name'], 
                                    'ID' => $box_result['ID'], 
                                    'parentID' => $box_result['parentID'], 
                                    'sort' => $box_result['sort'], 
                                    'type' => $box_result['type'],
                                                          'subs' => false,
                                                      'selected' => $selected,
                                                          'url' => $box_result['url'],
                                                          'target' => $box_result['target'],
                                                          'append' => $box_result['append']
                                                        );
      }               
        } // end while
    }   
                                                
  return $box_array; 
}

function cre_category_has_subs($id = 0) {

  $has_subs_sql = "SELECT categories_id 
                     from " . TABLE_CDS_CATEGORIES . " 
                   WHERE categories_parent_id = '" . $id . "'
                                   and categories_status = '1' 
                     and categories_in_menu = '1'"; 

  $has_subs_check = tep_db_num_rows(tep_db_query($has_subs_sql));
    $has_subs = ($has_subs_check > 0) ? true : false;
    
    return $has_subs;
}

function cre_get_cds_page_path($current_page_id = '') {
  $path_query = tep_db_query("SELECT categories_id 
                                  from " . TABLE_CDS_PAGES_TO_CATEGORIES . " 
                                                            WHERE pages_id = '" . (int)$current_page_id . "'");
                                                            
  $path = tep_db_fetch_array($path_query);
    $CDpath_new = cre_get_cds_category_path($path['categories_id']);
    
    return $CDpath_new;
}

function cre_get_cds_category_path($current_categories_id = '') {

  if (tep_not_null($current_categories_id)) {
    $CDpath_new = $current_categories_id;
    $loop = true;
        while ($loop === true) {        
      $categories_query = tep_db_query("SELECT categories_parent_id 
                                                from " . TABLE_CDS_CATEGORIES. " 
                                                                                WHERE categories_id = '" . (int)$current_categories_id . "'");
                                                                                
      $categories = tep_db_fetch_array($categories_query);
        
          if ($categories['categories_parent_id'] != 0) {
            $CDpath_new .= '_' . $categories['categories_parent_id'];
                $current_categories_id = $categories['categories_parent_id'];
                continue;
          }
            $loop = false;
            break;
    }
  }  
  $CDpath_array_new = explode('_', $CDpath_new);
  krsort($CDpath_array_new);
  $CDpath_new = implode('_', $CDpath_array_new);

  return $CDpath_new;
}

function cre_build_box_string() {
  global $level, $subvalue;
    
    $this_box_string = '';
  $sub_indicator = (defined('CDS_TEXT_SUBS_INDICATOR')) ? CDS_TEXT_SUBS_INDICATOR : '';
  if ($subvalue[$level]['type'] == 'c') {
    $id = cre_get_cds_category_path($subvalue[$level]['ID']);
    if ($subvalue[$level]['url'] != '') {
      $separator = (strpos($subvalue[$level]['url'], '?')) ? '&amp;' : '?';
      $this_box_link = ($subvalue[$level]['append'] == true) ? $subvalue[$level]['url'] . $separator . 'CDpath=' . $id : $subvalue[$level]['url'];
      $this_box_target = ($subvalue[$level]['target'] != '') ? 'target="' . $subvalue[$level]['target'] . '"' : '';
    } else {
      $this_box_link = tep_href_link(FILENAME_PAGES, 'CDpath=' . $id);
      $this_box_target = '';
    }
  } else {
    $this_box_link = tep_href_link(FILENAME_PAGES, 'pID=' . $subvalue[$level]['ID'] . '&amp;CDpath=' . cre_get_cds_page_path($subvalue[$level]['ID']));
    $this_box_target = '';
  }  
  $this_box_string .= '<a href="' . $this_box_link . '"' . $this_box_target . '>';
  $this_box_string .= ($subvalue[$level]['selected'] == true) ? '<b>' : '';
  $this_box_string .= $subvalue[$level]['name'];
  $this_box_string .= ($subvalue[$level]['selected'] == true) ? '</b>' : '';
  $this_box_string .= ($subvalue[$level]['subs'] == true) ? $sub_indicator : '';
  $this_box_string .= '</a>';
  $this_box_string .= '<br>';
   
  return $this_box_string;
}

function cre_get_box_string() {
  global $languages_id, $level, $subvalue;
                                          
  $box_string = '';
  $this_id = 0;
  $level = 0;
  $spacer = '&nbsp;&nbsp;';
  $sub_indicator = (defined('CDS_TEXT_SUBS_INDICATOR')) ? CDS_TEXT_SUBS_INDICATOR : '';
                                                                  
  // get the box array
  $box_array = cre_get_box_array($this_id);
  
  while (list($key, $value) = each($box_array)) {
    // level 0
    if ($value['type'] == 'c') {
      $id = cre_get_cds_category_path($value['ID']);
      if ($value['url'] != '') {
        $separator = (strpos($value['url'], '?')) ? '&amp;' : '?';
        $box_link = ($value['append'] == true) ? $value['url'] . $separator . 'CDpath=' . $id : $value['url'];
        $box_target = ($value['target'] != '') ? 'target="' . $value['target'] . '"' : '';
      } else {
        $box_link = tep_href_link(FILENAME_PAGES, 'CDpath=' . $id);
        $box_target = '';
      }
    } else {
      $box_link = tep_href_link(FILENAME_PAGES, 'pID=' . $value['ID'] . '&amp;CDpath=' . cre_get_cds_page_path($value['ID']));
      $box_target = '';
    }   
    $box_string .= '<a href="' . $box_link . '"' . $box_target . '>';
    $box_string .= ($value['selected'] == true) ? '<b>' : '';
    $box_string .= $value['name'];
    $box_string .= ($value['selected'] == true) ? '</b>' : '';
    $box_string .= ($value['subs'] == true) ? $sub_indicator : '';
    $box_string .= '</a>';
    $box_string .= '<br>';

    // level 1
    if ($value['selected'] == true) {
      $sub_box_array1 = cre_get_box_array($value['ID']);
      $level++;
      while (list($subkey[$level], $subvalue[$level]) = each($sub_box_array1)) {
        $box_string .= str_repeat($spacer, 1) . cre_build_box_string();
        // level 2
        if ($subvalue[$level]['selected'] == true) {          
          $sub_box_array2 = cre_get_box_array($subvalue[$level]['ID']);
                    $pID_check = (isset($_GET['pID']) && $_GET['pID'] != '') ? $_GET['pID'] : 0;                            
          if ( (end(explode('_', $_GET['CDpath'])) == $pID_check) ) {
            if (array_key_exists(($subkey[$level] + 1), $sub_box_array2) ) { continue; } else { break; }
          }
          $level++;
          while (list($subkey[$level], $subvalue[$level]) = each($sub_box_array2)) {
            $box_string .= str_repeat($spacer, 2) . cre_build_box_string();
            // level 3
            if ($subvalue[$level]['selected'] == true) {                        
              $sub_box_array3 = cre_get_box_array($subvalue[$level]['ID']);
                      $pID_check = (isset($_GET['pID']) && $_GET['pID'] != '') ? $_GET['pID'] : 0;                            
              if ( (end(explode('_', $_GET['CDpath'])) == $pID_check) ) {
                if (array_key_exists(($subkey[$level] + 1), $sub_box_array3) ) { continue; } else { break; }
              }                           
              $level++;
              while (list($subkey[$level], $subvalue[$level]) = each($sub_box_array3)) {
                $box_string .= str_repeat($spacer, 3) . cre_build_box_string();
                // level 4
                if ($subvalue[$level]['selected'] == true) {
                  $sub_box_array4 = cre_get_box_array($subvalue[$level]['ID']);
                      $pID_check = (isset($_GET['pID']) && $_GET['pID'] != '') ? $_GET['pID'] : 0;                            
                  if ( (end(explode('_', $_GET['CDpath'])) == $pID_check) ) {
                    if (array_key_exists(($subkey[$level] + 1), $sub_box_array4) ) { continue; } else { break; }
                  }
                  $level++;
                  while (list($subkey[$level], $subvalue[$level]) = each($sub_box_array4)) {
                    $box_string .= str_repeat($spacer, 4) . cre_build_box_string();           
                    // level 5
                    if ($subvalue[$level]['selected'] == true) {
                      $sub_box_array5 = cre_get_box_array($subvalue[$level]['ID']);
                                    $pID_check = (isset($_GET['pID']) && $_GET['pID'] != '') ? $_GET['pID'] : 0;                            
                      if ( (end(explode('_', $_GET['CDpath'])) == $pID_check) ) {
                        if (array_key_exists(($subkey[$level] + 1), $sub_box_array5) ) { continue; } else { break; }
                      }
                      $level++;
                      while (list($subkey[$level], $subvalue[$level]) = each($sub_box_array5)) {
                        $box_string .= str_repeat($spacer, 5) . cre_build_box_string();
                        // level 6
                        if ($subvalue[$level]['selected'] == true) {
                          $sub_box_array6 = cre_get_box_array($subvalue[$level]['ID']);
                          $pID_check = (isset($_GET['pID']) && $_GET['pID'] != '') ? $_GET['pID'] : 0;                            
                          if ( (end(explode('_', $_GET['CDpath'])) == $pID_check) ) {
                            if (array_key_exists(($subkey[$level] + 1), $sub_box_array6) ) { continue; } else { break; }
                          }
                          $level++;
                          while (list($subkey[$level], $subvalue[$level]) = each($sub_box_array6)) {
                            $box_string .= str_repeat($spacer, 6) . cre_build_box_string();   
                            // level 7
                            if ($subvalue[$level]['selected'] == true) {
                              $sub_box_array7 = cre_get_box_array($subvalue[$level]['ID']);
                                          $pID_check = (isset($_GET['pID']) && $_GET['pID'] != '') ? $_GET['pID'] : 0;                            
                              if ( (end(explode('_', $_GET['CDpath'])) == $pID_check) ) {
                                if (array_key_exists(($subkey[$level] + 1), $sub_box_array7) ) { continue; } else { break; }
                              }
                              $level++;
                              while (list($subkey[$level], $subvalue[$level]) = each($sub_box_array7)) {
                                $box_string .= str_repeat($spacer, 7) . cre_build_box_string();                                               
                                // level 8
                                if ($subvalue[$level]['selected'] == true) {
                                  $sub_box_array8 = cre_get_box_array($subvalue[$level]['ID']);
                                              $pID_check = (isset($_GET['pID']) && $_GET['pID'] != '') ? $_GET['pID'] : 0;                            
                                  if ( (end(explode('_', $_GET['CDpath'])) == $pID_check) ) {
                                    if (array_key_exists(($subkey[$level] + 1), $sub_box_array8) ) { continue; } else { break; }
                                  }
                                  $level++;
                                  while (list($subkey[$level], $subvalue[$level]) = each($sub_box_array8)) {
                                    $box_string .= str_repeat($spacer, 8) . cre_build_box_string();                                               
                                    // level 9
                                    if ($subvalue[$level]['selected'] == true) {
                                      $sub_box_array9 = cre_get_box_array($subvalue[$level]['ID']);
                                        $pID_check = (isset($_GET['pID']) && $_GET['pID'] != '') ? $_GET['pID'] : 0;                            
                                      if ( (end(explode('_', $_GET['CDpath'])) == $pID_check) ) {
                                        if (array_key_exists(($subkey[$level] + 1), $sub_box_array9) ) { continue; } else { break; }
                                      }
                                      $level++;
                                      while (list($subkey[$level], $subvalue[$level]) = each($sub_box_array9)) {
                                        $box_string .= str_repeat($spacer, 9) . cre_build_box_string();                                               
                                      } // end while9       
                                    }                                                                       
                                  } // end while8
                                }                                                               
                              } // end while7
                            }                                                       
                          } // end while6       
                        }
                      } // end while5
                    }                                       
                  } // end while4
                }                                           
              } // end while3
            }                         
          } // end while2   
        }
      } // end while1
    }       
  }
                                  
  return $box_string;
}

function cre_get_header_banner($CDpath) {
  $CDpath_array = explode('_', $CDpath);
  krsort($CDpath_array);
  $header_banner = array();
  for ($i = sizeof($CDpath_array), $n = 0; $i > $n; $i--) {
    $cat_id = $CDpath_array[$i-1];
    $category_query = tep_db_query("SELECT category_header_banner from " . TABLE_CDS_CATEGORIES . " where categories_id = '" . (int)$cat_id . "'");
    $category = tep_db_fetch_array($category_query);
    if ($category['category_header_banner'] != '') {
      $header_banner = array('id' => (int)$cat_id, 'banner' => $category['category_header_banner']);
      break;
    }
  }
  return $header_banner;
}

function cre_get_cds_path_back($current_id, $current_type, $prev_type) {

  $CDpath = isset($_GET['CDpath']) ? $_GET['CDpath'] : '';
  $CDpath_new = array();

  if (tep_not_null($CDpath)) {
    $CDpath_new = explode('_', $CDpath);
    if ($current_type == 'c' && $prev_type == 'c') {
      if (sizeof($CDpath_new) > 0) {
        array_pop($CDpath_new); 
      }
      array_push($CDpath_new, $current_id);
    }
    if ($current_type == 'c' && $prev_type == 'p') {
      if (sizeof($CDpath_new) > 1) {
        array_pop($CDpath_new); 
      }
    }
    if ($current_type == 'p' && $prev_type == 'c') {
      array_push($CDpath_new, $current_id);
    }
    $CDpath_new = implode('_', $CDpath_new);
  }

  return $CDpath_new;
}

function cre_get_cds_path_next($current_id, $current_type, $next_type) {

  $CDpath = isset($_GET['CDpath']) ? $_GET['CDpath'] : '';
  $CDpath_new = array();

  if (tep_not_null($CDpath)) {
    $CDpath_new = explode('_', $CDpath);
    if ($current_type == 'c' && $next_type == 'c') {
      if (sizeof($CDpath_new) > 0) {
        array_pop($CDpath_new); 
      }
      array_push($CDpath_new, $current_id);
    }
    if ($current_type == 'c' && $next_type == 'p') {
      if (sizeof($CDpath_new) > 1) {
        array_pop($CDpath_new); 
      }
    }
    if ($current_type == 'p' && $next_type == 'c') {
      array_push($CDpath_new, $current_id);
    }
    $CDpath_new = implode('_', $CDpath_new);
  }

  return $CDpath_new;
}

function cre_category_exists($category_id) {
    
  $category_query = tep_db_query("SELECT categories_id   
                                      from " . TABLE_CDS_CATEGORIES . " 
                                                               WHERE categories_id = '" . (int)$category_id . "'");
                              
  $this_exists = (tep_db_num_rows($category_query) > 0) ? true : false;
    
  return $this_exists;
}

function cre_get_category_heading_image($category_id) {
    global $languages_id;
    
  $category_query = tep_db_query("SELECT ic.category_heading_title_image, icd.categories_heading  
                                      from " . TABLE_CDS_CATEGORIES . " ic, 
                                                                             " . TABLE_CDS_CATEGORIES_DESCRIPTION . " icd 
                                                               WHERE ic.categories_id = '" . (int)$category_id . "' 
                                                                      and icd.categories_id = '" . (int)$category_id . "' 
                                                                        and icd.language_id = '" . (int)$languages_id . "'");
                                                                                                                      
  $category = tep_db_fetch_array($category_query);

  return  tep_image(DIR_WS_IMAGES . $category['category_heading_title_image'], $category['categories_heading']);
}

function cre_get_category_title($category_id) {
  global $languages_id;
    
  $category_query = tep_db_query("SELECT categories_heading  
                                      from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " 
                                                               WHERE categories_id = '" . (int)$category_id . "' 
                                                                  and language_id = '" . (int)$languages_id . "'");
                                                                
  $category = tep_db_fetch_array($category_query);

  return $category['categories_heading'];
}

function cre_get_category_description($category_id) {
  global $languages_id;
    
  $category_query = tep_db_query("SELECT categories_description  
                                      from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " 
                                                               WHERE categories_id = '" . (int)$category_id . "' 
                                                                  and language_id = '" . (int)$languages_id . "'");
                                                                
  $category = tep_db_fetch_array($category_query);
    
  return $category['categories_description'];
}

function cre_get_category_blurb($category_id) {
  global $languages_id;
    
  $category_query = tep_db_query("SELECT categories_blurb 
                                      from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " 
                                                               WHERE categories_id = '" . (int)$category_id . "' 
                                                                  and language_id = '" . (int)$languages_id . "'");
                                                                
  $category = tep_db_fetch_array($category_query);
    
  return $category['categories_blurb'];
}

function cre_get_category_thumbnail($category_id) {
  global $languages_id;
    
  $category_query = tep_db_query("SELECT ic.categories_image, icd.categories_heading 
                                      from " . TABLE_CDS_CATEGORIES . " ic, 
                                                                             " . TABLE_CDS_CATEGORIES_DESCRIPTION . " icd 
                                                               WHERE ic.categories_id = '" . (int)$category_id . "' 
                                                                      and icd.categories_id = '" . (int)$category_id . "' 
                                                                      and icd.language_id = '" . (int)$languages_id . "'");
                                                                
  $category = tep_db_fetch_array($category_query);
    
  return tep_image(DIR_WS_IMAGES . $category['categories_image'], $category['categories_heading'], CDS_THUMBNAIL_WIDTH, CDS_THUMBNAIL_HEIGHT);
}

function cre_get_category_view($category_id) {
    
  $category_query = tep_db_query("SELECT categories_sub_category_view     
                                      from " . TABLE_CDS_CATEGORIES . " 
                                                               WHERE categories_id = '" . (int)$category_id . "'");
                                                                
  $category = tep_db_fetch_array($category_query);
  $flat_view = ((int)$category['categories_sub_category_view'] == 0) ? true : false;

  return $flat_view;
}

function cre_get_category_listing_columns($category_id) {
    
  $category_query = tep_db_query("SELECT categories_listing_columns 
                                      from " . TABLE_CDS_CATEGORIES . " 
                                                               WHERE categories_id = '" . (int)$category_id . "'");
                                                                
  $category = tep_db_fetch_array($category_query);

  return $category['categories_listing_columns'];
}

function cre_page_exists($page_id) {
    
  $page_query = tep_db_query("SELECT pages_id  
                                  from " . TABLE_CDS_PAGES . " 
                                                           WHERE pages_id = '" . (int)$page_id . "' and pages_status = '1'");
                                                                
                                                              
  $this_exists = (tep_db_num_rows($page_query) > 0) ? true : false;                                                                 
    
  return $this_exists;
}

function cre_get_page_title($page_id) {
  global $languages_id;
    
  $page_query = tep_db_query("SELECT pages_title 
                                  from " . TABLE_CDS_PAGES_DESCRIPTION . " 
                                                           WHERE pages_id = '" . (int)$page_id . "' 
                                                              and language_id = '" . (int)$languages_id . "'");
                                                                
  $page = tep_db_fetch_array($page_query);

  return $page['pages_title'];
}

function cre_get_page_body($page_id) {
  global $languages_id;
    
  $page_query = tep_db_query("SELECT pages_body 
                                  from " . TABLE_CDS_PAGES_DESCRIPTION . " 
                                                           WHERE pages_id = '" . (int)$page_id . "' 
                                                              and language_id = '" . (int)$languages_id . "'");
                                                                
  $page = tep_db_fetch_array($page_query);
    
  return $page['pages_body'];
}

function cre_get_page_blurb($page_id) {
  global $languages_id;
    
  $page_query = tep_db_query("SELECT pages_blurb 
                                  from " . TABLE_CDS_PAGES_DESCRIPTION . " 
                                                           WHERE pages_id = '" . (int)$page_id . "' 
                                                              and language_id = '" . (int)$languages_id . "'");
                                                                
  $page = tep_db_fetch_array($page_query);
    
  return $page['pages_blurb'];
}

function cre_get_page_thumbnail($page_id) {
  global $languages_id;
    
  $page_query = tep_db_query("SELECT ip.pages_image, icp.pages_title 
                                      from " . TABLE_CDS_PAGES . " ip, 
                                                                             " . TABLE_CDS_PAGES_DESCRIPTION . " icp 
                                                               WHERE ip.pages_id = '" . (int)$page_id . "' 
                                                                      and icp.pages_id = '" . (int)$page_id . "' 
                                                                      and icp.language_id = '" . (int)$languages_id . "'");
                                                                
  $page = tep_db_fetch_array($page_query);
    
  return tep_image(DIR_WS_IMAGES . $page['pages_image'], $page['pages_title'], CDS_THUMBNAIL_WIDTH, CDS_THUMBNAIL_HEIGHT);
}

function cre_get_acf_filename($page_id) {
  global $languages_id;
    
  $page_query = tep_db_query("SELECT pages_file
                                  from " . TABLE_CDS_PAGES_DESCRIPTION . " 
                                                           WHERE pages_id = '" . (int)$page_id . "' 
                                                              and language_id = '" . (int)$languages_id . "'");
                                                                
  $page = tep_db_fetch_array($page_query);
    
  return $page['pages_file'];
}

function cre_get_product_insert($id) {
  global $languages_id, $pf, $listing_columns;

  if (isset($_GET['pID']) && $_GET['pID'] != '') {
    $sql = "SELECT pages_attach_product 
              from " . TABLE_PAGES . " 
            WHERE pages_id = " . (int)$id . "
              and pages_status = '1'";
  } else {
    $sql = "SELECT categories_attach_product 
              from " . TABLE_PAGES_CATEGORIES . " 
            WHERE categories_id = " . (int)$id . "
              and categories_status = '1'";   
  }
  $page = tep_db_fetch_array(tep_db_query($sql));    
  $attach_product = (isset($_GET['pID']) && $_GET['pID'] != '') ? $page['pages_attach_product'] : $page['categories_attach_product'];
  $cPath = tep_get_product_path($attach_product);

  $product_string = '';
  
  if (isset($attach_product) && $attach_product != 0) {
    $cds_attach_product = tep_db_fetch_array(tep_db_query("SELECT pd.products_name, p.products_price, p.products_image 
                                                             from " . TABLE_PRODUCTS . " p, 
                                                                  " . TABLE_PRODUCTS_DESCRIPTION ." pd 
                                                           WHERE p.products_id = pd.products_id 
                                                             and p.products_id = " . $attach_product));
                                                                                                                          
    $pf->loadProduct($attach_product, $languages_id);
    $products_price = $pf->getPriceStringShort();
    if (htmlspecialchars(substr($products_price, -6)) == htmlspecialchars("&nbsp;")) {
      $products_price = substr($products_price, 0, strlen($products_price)-6);
    }
    $product_string .= '<!-- pages_product_insert -->' . "\n";		
				if ($listing_columns != 1) {
      $product_string .= '<table border="0" cellspacing="0" cellpadding="0" align="right"><tr><td class="attach_product">' . "\n";
				} else {
      $product_string .= '<table border="0" cellspacing="0" cellpadding="0"><tr><td class="attach_product">' . "\n";			
				}
   // $product_string .= '<div>' . "\n";
    $product_string .= '  <table border="0" cellspacing="0" cellpadding="0">' . "\n";
    $product_string .= '    <tr>' . "\n";
    $product_string .= '      <td><div class="cds_product_img">' . "\n";
    $product_string .= '        <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'CDpath=' . $_GET['CDpath'] . '&amp;products_id=' . $attach_product) . '">' . tep_image(DIR_WS_IMAGES . $cds_attach_product['products_image'], addslashes($cds_attach_product['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>'  . "\n";
    $product_string .= '      </div></td>' . "\n";
    $product_string .= '    </tr>' . "\n";
    $product_string .= '    <tr>' . "\n";
    $product_string .= '      <td><div class="cds_product_name">' . "\n";
    $product_string .= '        <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'CDpath=' . $_GET['CDpath'] . '&amp;products_id=' . $attach_product) . '">' . $cds_attach_product['products_name'] . '</a>' . "\n";
    $product_string .= '      </div></td>' . "\n";
    $product_string .= '    </tr>' . "\n";

    //### subproducts
    $allowcriteria = (STOCK_ALLOW_CHECKOUT == 'false') ? " and p.products_quantity > 0 " : "";
    $csort_order = tep_db_fetch_array(tep_db_query("SELECT configuration_value 
                                                                          from " . TABLE_CONFIGURATION . " 
                                                                        WHERE configuration_key = 'CATEGORIES_SORT_ORDER'"));
    $select_order_by = '';
    switch ($csort_order['configuration_value']) {
      case 'PRODUCT_LIST_MODEL':
        $select_order_by .= 'p.products_model';
        break;
      case 'PRODUCT_LIST_NAME':
        $select_order_by .= 'pd.products_name';
        break;
      case 'PRODUCT_LIST_PRICE':
        $select_order_by .= 'p.products_price';
        break;
     default:
       $select_order_by .= 'p.products_model';
       break;
    }
    $sub_products_sql = tep_db_query("SELECT p.products_id, p.products_price, p.products_tax_class_id, p.products_image, pd.products_name, pd.products_description, p.products_model 
                                                          from " . TABLE_PRODUCTS . " p, 
                                                                 " . TABLE_PRODUCTS_DESCRIPTION . " pd 
                                                        WHERE p.products_parent_id = " . (int)$attach_product . " " . $allowcriteria." 
                                                          and p.products_id = pd.products_id 
                                                          and pd.language_id = " . (int)$languages_id . " 
                                                        ORDER BY " . $select_order_by);

    if (tep_db_num_rows($sub_products_sql) > 0) {
      $product_string .= '    <tr>' . "\n";
      $product_string .= '      <td><table border="0" cellspacing="0" cellpadding="0">' . "\n";
      while ($sub_products = tep_db_fetch_array($sub_products_sql)) {
        $remain = strlen(substr( $sub_products['products_name'], 0, strpos($sub_products['products_name'], ' - ')) . ' - ');
        $subname = substr($sub_products['products_name'], $remain);
        $pf->loadProduct($sub_products['products_id'], $languages_id);
        $sub_products_price = $pf->getPriceStringShort();
        if (htmlspecialchars(substr($sub_products_price, -6)) == htmlspecialchars("&nbsp;")) {
          $sub_products_price = substr($sub_products_price, 0, strlen($sub_products_price)-6);
        }
        $product_string .= '        <tr>' . "\n";
        $product_string .= '          <td>' . "\n";
        $product_string .= '            <div class="cds_product_buy"><a href="' . tep_href_link(FILENAME_DEFAULT . 'action=buy_now&amp;products_id=' . $sub_products['products_id'] . '&amp;cPath=' . $cPath) . '">' . "\n";
        $product_string .= '            <nobr>' . CDS_TEXT_BUY .  '&nbsp;' . $subname . $sub_products_price . '</nobr></a></div>' . "\n";
        $product_string .= '          </td>' . "\n";
        if (eregi('productSpecialPrice', $sub_products_price)) {
          if (htmlspecialchars(substr($sub_products_price, 0,6)) == htmlspecialchars("&nbsp;")) {
            $sub_products_price = substr($sub_products_price, 6, strlen($sub_products_price));
          }
          $product_string .= '      </tr>' . "\n";
          $product_string .= '      <tr>' . "\n";
        }
        $product_string .= '        </tr>' . "\n";

      }
      $product_string .= '      </table></td>' . "\n";
      $product_string .= '    </tr>' . "\n";
    } else {  // no sub products
      $product_string .= '    <tr>' . "\n";
      $product_string .= '      <td><table border="0" width="100%" cellspacing="0" cellpadding="0">' . "\n";
      $product_string .= '        <tr>' . "\n";
      if (eregi('productSpecialPrice', $products_price)) {
        if (htmlspecialchars(substr($products_price, 0,6)) == htmlspecialchars("&nbsp;")) {
          $products_price = substr($products_price, 6, strlen($products_price));
        }
        $product_string .= '        </tr>' . "\n";
        $product_string .= '        <tr>' . "\n";
      }
      $product_string .= '          <td>' . "\n";
      $product_string .= '            <div class="cds_product_buy"><a href="' . tep_href_link(FILENAME_DEFAULT . 'action=buy_now&amp;products_id=' . $attach_product . '&amp;cPath=' . $cPath) . '">' . "\n";
      $product_string .= '            <nobr>' . CDS_TEXT_BUY . '&nbsp;<span class="cds_product_buy">' . $products_price . '</span></nobr></a></div>' . "\n";
      $product_string .= '          </td>' . "\n";
      $product_string .= '        </tr>' . "\n";
      $product_string .= '      </table></td>' . "\n";
      $product_string .= '    </tr>' . "\n";
    }
        $product_string .= '<tr><td class="cds_product_insert_width">' . tep_draw_separator('pixel_trans.gif', '1', '1') . '</td></tr>' . "\n";
    $product_string .= '   </table>' . "\n";
  //  $product_string .= ' </div>' . "\n";
    $product_string .= ' </td></tr>' . "\n";
        $product_string .= '</table>' . "\n";
//kiran added for fixing link issue in IE
$product_string .= '<br clear="left" />' . "\n";
    $product_string .= '<!-- pages_product_insert //eof-->' . "\n";
  }
  return $product_string;
}

function cre_build_listing_link($val, $image = false) {

  $this_box_link = '';
  $this_box_string = '';
  if ($val['type'] == 'c') {
    if ($val['url'] != '') {
      $separator = (strpos($val['url'], '?')) ? '&amp;' : '?';
      $this_box_link = ($val['append'] == true) ? $val['url'] . $separator . 'CDpath=' . cre_get_cds_category_path($val['ID']) : $val['url'];       
      $this_box_target = ($val['target'] != '') ? 'target="' . $val['target'] . '"' : '';
    } else {
      $this_box_link = tep_href_link(FILENAME_PAGES,'CDpath=' . cre_get_cds_category_path($val['ID']));
      $this_box_target = '';
    }
  } else {
    $this_box_link = tep_href_link(FILENAME_PAGES, 'pID=' . $val['ID'] . '&amp;CDpath=' . cre_get_cds_page_path($val['ID']));
    $this_box_target = '';
  }
  $this_box_string .= '<a href="' . $this_box_link . '"' . $this_box_target . '>';
  if ($val['type'] == 'c') {
    if ($image == true) {
      $this_box_string .= cre_get_category_thumbnail($val['ID']);
    } else {
      $this_box_string .= $val['name'];
    }
  } else {
    if ($image == true) {
      $this_box_string .= cre_get_page_thumbnail($val['ID']);
    } else {
    $this_box_string .= $val['name'];
    }   
  }
  $this_box_string .= '</a>';
  $this_box_string .= '<br>';
    
  return $this_box_string;
}

function cre_get_category_display_string() {
  global $value, $subvalue, $cell_width, $thumbnail, $title, $blurb, $has_image, $sub_has_image, $flat_view;
          
  $product_string = '';  
  $this_width = (defined('CDS_THUMBNAIL_WIDTH') && CDS_THUMBNAIL_WIDTH > 0) ? CDS_THUMBNAIL_WIDTH : 1;         
  $this_display_string = '<td valign="top" width="' . $cell_width . '%">' . "\n";   
  if ($flat_view == true && isset($subvalue)) {
    $this_display_string .= '  <table width="100%" border="0" cellspacing="0" cellpadding="0">' . "\n";
    $this_display_string .= '    <tr>' . "\n";
    if ($sub_has_image == true) {
      $this_display_string .= '      <td width="' . $this_width . '" class="cds_listing_category_img" valign="top" align="center"></td>' . "\n";
    } else {
      $this_display_string .= '      <td width="' . CDS_FLAT_VIEW_INDENT_VALUE . '" valign="top" align="center"></td>' . "\n";
    }
    $this_display_string   .= '    <td valign="top" class="cds_listing_category_content"><table border="0" cellpadding="0" cellspacing="0" width="100%">' . "\n";
    $this_display_string   .= '      <tr>' . "\n";
    $this_display_string   .= '        <td class="cds_listing_category_title">' . "\n";   
  }
  $this_display_string     .= '          <table width="100%" border="0" cellspacing="0" cellpadding="0">' . "\n";
  $this_display_string     .= '            <tr>' . "\n";
  if ($has_image == true) {
    $this_display_string   .= '                <td width="' . $this_width . '" class="cds_listing_category_img" valign="top" align="center">' . $thumbnail . '</td>' . "\n";
  } else {
    $this_display_string   .= '                <td width="1" valign="top" align="center"></td>' . "\n";
  }
  $this_display_string .= '                  <td valign="top" class="cds_listing_category_content"><table border="0" cellpadding="0" cellspacing="0" width="100%">' . "\n";
		if (isset($title) && strip_tags($title) != '') {
    $this_display_string .= '                <tr>' . "\n";
    $this_display_string .= '                  <td class="cds_listing_category_title">' . $title . '</td>' . "\n";
    $this_display_string .= '                </tr>' . "\n";
		}
  $this_display_string .= '                <tr>' . "\n";
  $this_display_string .= '                  <td class="cds_listing_category_blurb">' . $product_string . $blurb . '</td>' . "\n";
  $this_display_string .= '                </tr>' . "\n";
  $this_display_string .= '              </table>' . "\n";
  $this_display_string .= '            </td>' . "\n";
  $this_display_string .= '          </tr>' . "\n";
  $this_display_string .= '        </table>' . "\n";  
  if ($flat_view == true && isset($subvalue)) { 
    $this_display_string .= '        </td>' . "\n";
    $this_display_string .= '      </tr>' . "\n";
    $this_display_string .= '      <tr>' . "\n";
    $this_display_string .= '        <td class="cds_listing_category_blurb"></td>' . "\n";
    $this_display_string .= '      </tr>' . "\n";
    $this_display_string .= '      </table>' . "\n";
    $this_display_string .= '    </td>' . "\n";
    $this_display_string .= '  </tr>' . "\n";
    $this_display_string .= '</table>' . "\n";    
  }  
  $this_display_string .= '   </td>' . "\n";  


  return $this_display_string;
}

function cre_get_page_display_string() {
  global $subvalue, $cell_width, $thumbnail, $title, $blurb, $has_image, $sub_has_image, $flat_view;
    
  $this_width = (defined('CDS_THUMBNAIL_WIDTH') && CDS_THUMBNAIL_WIDTH > 0) ? CDS_THUMBNAIL_WIDTH : 1;      
  $this_display_string = '<td valign="top" width="' . $cell_width . '%">' . "\n"; 
    if ($flat_view == true && isset($subvalue)) {
    $this_display_string .= '  <table width="100%" border="0" cellspacing="0" cellpadding="0">' . "\n";
    $this_display_string .= '    <tr>' . "\n";
    if ($sub_has_image == true) {
      $this_display_string .= '      <td width="' . $width . '" class="cds_listing_pages_img" valign="top" align="center"></td>' . "\n";
      } else {
      $this_display_string .= '      <td width="' . CDS_FLAT_VIEW_INDENT_VALUE . '" valign="top" align="center"></td>' . "\n";
      }
    $this_display_string   .= '    <td valign="top" class="cds_listing_pages_content"><table border="0" cellpadding="0" cellspacing="0" width="100%">' . "\n";
    $this_display_string   .= '      <tr>' . "\n";
    $this_display_string   .= '        <td class="cds_listing_pages_title">' . "\n";    
    }
   $this_display_string     .= '          <table width="100%" border="0" cellspacing="0" cellpadding="0">' . "\n";
  $this_display_string     .= '            <tr>' . "\n";
    if ($has_image == true) {
    $this_display_string   .= '                <td width="' . $this_width . '" class="cds_listing_pages_img" valign="top" align="center">' . $thumbnail . '</td>' . "\n";
    } else {
    $this_display_string   .= '                <td width="1" valign="top" align="center">' . $thumbnail . '</td>' . "\n";
    }
  $this_display_string .= '                  <td valign="top" class="cds_listing_pages_content"><table border="0" cellpadding="0" cellspacing="0" width="100%">' . "\n";
		if (isset($title) && strip_tags($title) != '') {
    $this_display_string .= '                <tr>' . "\n";
    $this_display_string .= '                  <td class="cds_listing_pages_title">' . $title . '</td>' . "\n";
    $this_display_string .= '                </tr>' . "\n";
		}
  $this_display_string .= '                <tr>' . "\n";
  $this_display_string .= '                  <td class="cds_listing_pages_blurb">' . $blurb . '</td>' . "\n";
  $this_display_string .= '                </tr>' . "\n";
  $this_display_string .= '              </table>' . "\n";
  $this_display_string .= '            </td>' . "\n";
  $this_display_string .= '          </tr>' . "\n";
  $this_display_string .= '        </table>' . "\n";
    if ($flat_view == true && isset($subvalue)) { 
    $this_display_string .= '        </td>' . "\n";
    $this_display_string .= '      </tr>' . "\n";
    $this_display_string .= '      <tr>' . "\n";
    $this_display_string .= '        <td class="cds_listing_pages_blurb"></td>' . "\n";
    $this_display_string .= '      </tr>' . "\n";
    $this_display_string .= '      </table>' . "\n";
    $this_display_string .= '    </td>' . "\n";
    $this_display_string .= '  </tr>' . "\n";
    $this_display_string .= '</table>' . "\n";    
    }
    
  $this_display_string .= '   </td>' . "\n";  

  return $this_display_string;
}   
    

function cre_get_cds_thema($CDpath) {
  $CDpath_array = explode('_', $CDpath);
  krsort($CDpath_array);
  $template_name = '';
  for ($i=sizeof($CDpath_array), $n=0; $i>$n; $i--) {
    $cat_id = $CDpath_array[$i-1];
    $category_query = tep_db_query("SELECT categories_template from " . TABLE_CDS_CATEGORIES . " where categories_id = '" . (int)$cat_id . "'");
    $category = tep_db_fetch_array($category_query);
    if ($category['categories_template'] != '') {
      $template_name = $category['categories_template'];
      break;
    }
  }
  return $template_name;
}
?>