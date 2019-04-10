<?php
/*
  $Id: CDS_navigation_global_bottom.php,v 1.0.0.0 2007/03/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/

global $languages_id;

require_once(DIR_WS_FUNCTIONS . FILENAME_CDS_FUNCTIONS);

$CDpath = isset($_GET['CDpath']) ? $_GET['CDpath'] : '';
$pID = isset($_GET['pID']) ? (int)$_GET['pID'] : 0;
if ($CDpath == '' || $CDpath == '0') return;

$current_array = array();

// store back to list id
$back_to_list_array = explode('_', $CDpath);
if ((substr_count($CDpath, '_') > 0) && ($pID =='')) {
  array_pop($back_to_list_array);
}
$back_to_list_CDpath = implode('_', $back_to_list_array);
$new_cat_id =  end($back_to_list_array);
// check parent_id
$sql_parent = tep_db_query("SELECT categories_parent_id
                                            from " . TABLE_PAGES_CATEGORIES . " 
                                          WHERE categories_id =  '" . (int)$new_cat_id . "'");
$parent_result = tep_db_fetch_array($sql_parent); 
$parent_id = $parent_result['categories_parent_id'];
if(isset($_GET['keywords'])) {
  $link_back_to_list = tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'CDpath=' . $back_to_list_CDpath.'&keywords='.$_GET['keywords']);
} else { 
  $link_back_to_list = tep_href_link(FILENAME_CDS_INDEX, 'CDpath=' . $back_to_list_CDpath);
}



// store the array for current category/page
$nav_array = array();

$new_id = (int)(end(explode('_', $CDpath)));
$new_cat_id =  ($pID != '') ? $new_id : end($back_to_list_array);

$nav_query = tep_db_query("SELECT ic.categories_id as 'ID', ic.categories_sort_order as 'Sort', icd.categories_name as 'Name', ic.categories_url_override as 'Override', ic.categories_url_override_target as 'Target', ic.category_append_cdpath as 'Append', 'c' as 'type' 
                                            from " . TABLE_PAGES_CATEGORIES . " ic, 
                                                   " . TABLE_PAGES_CATEGORIES_DESCRIPTION . " icd  
                                          WHERE ic.categories_parent_id = '" . $new_cat_id . "' 
                                            and ic.categories_in_pages_listing = '2' 
                                            and ic.categories_status = '1' 
                                            and icd.categories_id = ic.categories_id 
                                            and icd.language_id =  '" . $languages_id . "' 
                                          UNION
                                          SELECT p2c.pages_id as 'ID', p2c.page_sort_order as 'Sort', pd.pages_menu_name as 'Name', '' as 'Override', '' as 'Target', '' as 'Append', 'p' as 'type'  
                                            from " . TABLE_PAGES_TO_CATEGORIES . " p2c, 
                                                   " . TABLE_PAGES . " p, 
                                                   " . TABLE_PAGES_DESCRIPTION . " pd 
                                          WHERE p2c.categories_id =  '" . $new_cat_id . "' 
                                            and p2c.pages_id = p.pages_id 
                                            and pd.pages_id = p.pages_id 
                                            and pd.language_id =  '" . $languages_id . "' 
                                            and p.pages_status = '1' 
                                            and p.pages_in_page_listing = '2' 
                                          ORDER BY Sort
                                       ");

while ($nav_result = tep_db_fetch_array($nav_query)) {
  $nav_array[] .= $nav_result['ID'] . ',' . $nav_result['type'] . ',' . $nav_result['Name'] . ',' . $nav_result['Override'] . ',' . $nav_result['Target'] . ',' . $nav_result['Append'];
}

$current_id = ($pID != '') ? (int)$pID : (int)end(explode('_', $CDpath));
$array_end_value = end($nav_array);
reset($nav_array);

// get pointers
$current_key = 0;
$prev_key = 0;
$next_key = 0;
foreach ($nav_array as $key => $val) {
  if ((int)substr($val, 0, strpos($val, ',')) == (int)$current_id ) {
    $current_key = $key;
    $current_val = $val;
    $current_array = explode(',', $val);
    $prev_key = ($key != 0) ? $key -1 : 0;
    $next_key = $key +1;
     if ((int)substr($val, 0, strpos($val, ',')) == (int)substr($array_end_value,0, strpos($array_end_value, ',')) ) {
      $next_key = $key;
    }
  }
}
$next_array = isset($nav_array[$next_key]) ? explode(',', $nav_array[$next_key]) : 0;
$back_array = isset($nav_array[$prev_key]) ? explode(',', $nav_array[$prev_key]) : 0;

$current = isset($current_array[1]) ? $current_array[1] : '';
$back0 = isset($back_array[0]) ? $back_array[0] : '';
$back2 = isset($back_array[1]) ? $back_array[1] : '';
$next0 = isset($next_array[0]) ? $next_array[0] : '';
$next2 = isset($next_array[1]) ? $next_array[1] : '';
$back_CDpath = cre_get_cds_path_back($back0, $current, $back2);
$next_CDpath = cre_get_cds_path_next($next0, $current, $next2);

// back link
$link_back = '';
if ($back_array[1] == 'p') {
  $link_back = '<a href="' . tep_href_link(FILENAME_CDS_INDEX, 'CDpath=' . $back_CDpath . '&pID=' . (int)$back_array[0]) . '">';
} else {
  if ($back_array[3] != '') { // is url override
    $separator = (strpos($next_array[3], '?')) ? '&' : '?';
    $param = ($back_array[5] == 1) ? $separator . 'CDpath=' . $back_CDpath : '';   
    $target = ($back_array[4] != '') ? ' target="' . $back_array[4] : '';
    $link_back = '<a href="' . $back_array[3] . $param . '">';
  } else {
    $link_back = '<a href="' . tep_href_link(FILENAME_CDS_INDEX, 'CDpath=' . $back_CDpath) . '">';
  }
}
$link_back .= '<b>' . CDS_TEXT_BACK . '&nbsp;' . $back_array[2] . '</b></a>';

// next link
$link_next = '';
if ($next_array[1] == 'p') {
  $link_next = '<a href="' . tep_href_link(FILENAME_CDS_INDEX, 'CDpath=' . $next_CDpath . '&pID=' . (int)$next_array[0]) . '">';
} else {
  if ($next_array[3] != '') { // is url override
    $separator = (strpos($next_array[3], '?')) ? '&' : '?';
    $param = ($next_array[5] == 1) ? $separator . 'CDpath=' . $next_CDpath : '';  
    $target = ($next_array[4] !='') ? ' target="' . $next_array[4] : '';
    $link_next = '<a href="' . $next_array[3] . $param . '">';
  } else {
    $link_next = '<a href="' . tep_href_link(FILENAME_CDS_INDEX, 'CDpath=' . $next_CDpath) . '">';
  }
}
$link_next .= '<b>' . $next_array[2] . '&nbsp;' . CDS_TEXT_NEXT . '</b></a>';

$rci = '';
if ( ($parent_id != 0) || (substr_count($CDpath, '_') > 0) || ($pID) || (isset($_GET['products_id']) && $_GET['products_id'] != '')) {
  $rci .= '<!-- CDS_navigation_global_bottom //-->' . "\n";
  $rci .= '<table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">' . "\n";
  $rci .= '<tr class="infoBoxContents">' . "\n";
  $rci .= '<td>' . "\n";
  $rci .= '<table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n";
  $rci .= '<tr>' . "\n";
  if ($back_array[0] != $current_id) {
    $rci .= '<td class="main" align="left" width="40%">' . $link_back . '</td>' . "\n";
  } else {
    $rci .= '<td class="main" align="center" width="40%">&nbsp;</td>' . "\n";
  }
  $rci .= '<td class="main" align="center" width="20%"><a href="' . $link_back_to_list . '"><b>' . CDS_TEXT_BACK_TO_LIST . '</b></a></td>' . "\n";
  if ( ($next_array[0] != $current_id) && (!isset($_GET['products_id'])) ){
    $rci .= '<td class="main" align="right" width="40%">' . $link_next . '</td>' . "\n";
  } else {
    $rci .= '<td class="main" align="center" width="40%">&nbsp;</td>' . "\n";
  }
  $rci .= '</tr></table></td></tr></table>' . "\n";
  $rci .= '<!-- CDS_navigation_global_bottom eof//-->' . "\n";
}
?>