<?php
/*
  $Id: fdm.php,v 1.1.1.1 2006/08/12 10:20:48 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com.com

  Copyright (c) 2006 CRE Loaded

  Released under the GNU General Public License
*/

	function cre_resize_bytes($size) {
	  $count = 0;
	  $format = array("B","KB","MB","GB","TB","PB","EB","ZB","YB");
	  while(($size/1024)>1 && $count<8) {
	    $size=$size/1024;
	    $count++;
	  }
	  $return = number_format($size,0,'','.')." ".$format[$count];
	  return $return;
	}

 if (!function_exists(tep_get_orders_status_selection)) {
   function tep_get_orders_status_selection($key_value, $key = '') {
 		  $orders_status_array = array();
     $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$_SESSION['languages_id'] . "' order by orders_status_id");
     while ($orders_status = tep_db_fetch_array($orders_status_query)) {
       $orders_status_array[] = array ('id' => $orders_status['orders_status_id'],
       																'name' => $orders_status['orders_status_name']);
     }
     for ($i=0; $i<(sizeof($orders_status_array)); $i++) {
 			   $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value[]');
 			   $string .= '<br><input type="checkbox" name="' . $name . '" value="' . $orders_status_array[$i]['name'] . '"';
 			   $key_values = explode(", ", $key_value);
 			   if (in_array($orders_status_array[$i]['name'], $key_values)) $string .= ' checked="checked"';
 			   $string .= '> ' . $orders_status_array[$i]['name'];
 		  }
 		  return $string;
   }
 }

 function tep_generate_folder_path($id, $from = 'folder', $folders_array = '', $index = 0) {

    if (!is_array($folders_array)) $folders_array = array();

    if ($from == 'file') {
      $folders_query = tep_db_query("select folders_id from " . TABLE_LIBRARY_FILES_TO_FOLDERS . " where files_id = '" . (int)$id . "'");
      while ($folders = tep_db_fetch_array($folders_query)) {
        if ($folders['folders_id'] == '0') {
          $folders_array[$index][] = array('id' => '0', 'text' => TEXT_TOP);
        } else {
          $folder_query = tep_db_query("select fd.folders_name, f.folders_parent_id from " . TABLE_LIBRARY_FOLDERS . " f, " . TABLE_LIBRARY_FOLDERS_DESCRIPTION . " fd where f.folders_id = '" . (int)$folders['folders_id'] . "' and f.folders_id = fd.folders_id and fd.language_id = '" . (int)$_SESSION['languages_id'] . "'");
          $folder = tep_db_fetch_array($folder_query);
          $folders_array[$index][] = array('id' => $folders['folders_id'], 'text' => $folder['folders_name']);
          if ( (tep_not_null($folder['folders_parent_id'])) && ($folder['folders_parent_id'] != '0') ) $folders_array = tep_generate_folder_path($folder['folders_parent_id'], 'folder', $folders_array, $index);
          $folders_array[$index] = array_reverse($folders_array[$index]);
        }
        $index++;
      }
    } elseif ($from == 'folder') {
      $folder_query = tep_db_query("select fd.folders_name, f.folders_parent_id from " . TABLE_LIBRARY_FOLDERS . " f, " . TABLE_LIBRARY_FOLDERS_DESCRIPTION . " fd where f.folders_id = '" . (int)$id . "' and f.folders_id = fd.folders_id and fd.language_id = '" . (int)$_SESSION['languages_id'] . "'");
      $folder = tep_db_fetch_array($folder_query);
      $folders_array[$index][] = array('id' => $id, 'text' => $folder['folders_name']);
      if ( (tep_not_null($folder['folders_parent_id'])) && ($folder['folders_parent_id'] != '0') ) $folders_array = tep_generate_folder_path($folder['folders_parent_id'], 'folder', $folders_array, $index);
    }

    return $folders_array;
  }

  function tep_output_generated_folder_path($id, $from = 'folder') {
    $calculated_folder_path_string = '';
    $calculated_folder_path = tep_generate_folder_path($id, $from);
    for ($i=0, $n=sizeof($calculated_folder_path); $i<$n; $i++) {
      for ($j=0, $k=sizeof($calculated_folder_path[$i]); $j<$k; $j++) {
        $calculated_folder_path_string .= $calculated_folder_path[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
      }
      //$calculated_folder_path_string = substr($calculated_folder_path_string, 0, -16) . '<br>';
      $calculated_folder_path_string = substr($calculated_folder_path_string, 0, -16) . '&gt;';
    }
    $calculated_folder_path_string = substr($calculated_folder_path_string, 0, -4);

    if (strlen($calculated_folder_path_string) < 1) $calculated_folder_path_string = TEXT_TOP;

    return $calculated_folder_path_string;
  }

  function tep_get_folders_tree($parent_id = '0', $spacing = '', $exclude = '', $folder_tree_array = '', $include_itself = false) {

    if (!is_array($folder_tree_array)) $folder_tree_array = array();
    if ( (sizeof($folder_tree_array) < 1) && ($exclude != '0') ) $folder_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);

    if ($include_itself) {
      $folder_query = tep_db_query("select folders_name from " . TABLE_LIBRARY_DESCRIPTION . " where language_id = '" . (int)$_SESSION['languages_id'] . "' and folders_id = '" . (int)$parent_id . "'");
      $folder = tep_db_fetch_array($folder_query);
      $folder_tree_array[] = array('id' => $parent_id, 'text' => $folder['folders_name']);
    }

    $folders_query = tep_db_query("select f.folders_id, fd.folders_name, f.folders_parent_id from " . TABLE_LIBRARY_FOLDERS . " f, " . TABLE_LIBRARY_FOLDERS_DESCRIPTION . " fd where f.folders_id = fd.folders_id and fd.language_id = '" . (int)$_SESSION['languages_id'] . "' and f.folders_parent_id = '" . (int)$parent_id . "' order by f.folders_sort_order, fd.folders_name");
    while ($folders = tep_db_fetch_array($folders_query)) {
      if ($exclude != $folders['folders_id']) $folder_tree_array[] = array('id' => $folders['folders_id'], 'text' => $spacing . $folders['folders_name']);
      $folder_tree_array = tep_get_folders_tree($folders['folders_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $folder_tree_array);
    }

    return $folder_tree_array;
  }

  function tep_remove_folder_tree($folder, $move_folders_to, $move_files_to) {
    $remove_folder = $folder;
    $new_folders_loc = $move_folders_to;
    $new_files_to = $move_files_to;
 
    if ( $new_folders_loc <> '' ) {
      tep_db_query("update " . TABLE_LIBRARY_FOLDERS . " set folders_parent_id = '" . $new_folders_loc . "' where folders_parent_id = '" . (int)$remove_folder . "'");
    } else {
		
      $folders_query = tep_db_query("select folders_id from " . TABLE_LIBRARY_FOLDERS . " where folders_parent_id = '" . (int)$remove_folder . "'");
      while ($folders = tep_db_fetch_array($folders_query)) {
        tep_remove_folder_tree($folders['folders_id'], '', $new_files_to);
        tep_db_query("delete from " . TABLE_LIBRARY_FOLDERS . " where folders_id = '" . $folders['folders_id'] . "'");
        tep_db_query("delete from " . TABLE_LIBRARY_FOLDERS_DESCRIPTION . " where folders_id = '" . $folders['folders_id'] . "'");
      }
    }
    if ( $new_files_to <> '' ) { 
      tep_db_query("update " . TABLE_LIBRARY_FILES_TO_FOLDERS . " set folders_id = '" . $new_files_to . "' where folders_id = '" . (int)$remove_folder . "'");
    } else {
      $files_query = tep_db_query("select files_id from " . TABLE_LIBRARY_FILES_TO_FOLDERS . " where folders_id = '" . (int)$remove_folder . "'");
      while ($files = tep_db_fetch_array($files_query)) {
        $file_query = tep_db_query("select folders_id from " . TABLE_LIBRARY_FILES_TO_FOLDERS . " where files_id = '" . $files['files_id'] . "'"); 
        $total_files = array();
        while ($file = tep_db_fetch_array($file_query)) {
          $total_files[] = $file['folders_id'];
        }
        if ( count( $total_files ) > 1 ) {
          tep_db_query("delete from " . TABLE_LIBRARY_FILES_TO_FOLDERS . " where files_id = '" . $files['files_id'] . "' and folders_id = '" . (int)$remove_folder . "'");
        } else {
          $name_query = tep_db_query("select files_name from " . TABLE_LIBRARY_FILES . " where files_id = '" . $files['files_id'] . "'");
          $file_name =  tep_db_fetch_array($name_query);
          tep_db_query("delete from " . TABLE_LIBRARY_FILES_TO_FOLDERS . " where files_id = '" . $files['files_id'] . "'");
          tep_db_query("delete from " . TABLE_LIBRARY_FILES . " where files_id = '" . $files['files_id'] . "'");
          tep_db_query("delete from " . TABLE_LIBRARY_FILES_DESCRIPTION . " where files_id = '" . $files['files_id'] . "'");
          unlink(DIR_FS_CATALOG . LIBRARY_DIRECTORY . $file_name['files_name']);
        }
      }
    }
    tep_db_query("delete from " . TABLE_LIBRARY_FOLDERS . " where folders_id = '" . (int)$remove_folder . "'");
  }

function files_table_build($current_parent = 0, $current_lvl = 0, $expanded_folders_array, $selected_folders_array, $selected_files_array, $login_require_array, $purchase_require_array) {
  
  $next_lvl = $current_lvl + 1;
  $table_data = '';
	
  $folders_array = array();
  $sql_query =("select f.folders_id, fd.folders_name from " . TABLE_LIBRARY_FOLDERS . " f, " . TABLE_LIBRARY_FOLDERS_DESCRIPTION . " fd where f.folders_parent_id  = '" . $current_parent . "' and f.folders_id = fd.folders_id and fd.language_id = '" . (int)$_SESSION['languages_id'] . "' order by f.folders_sort_order, fd.folders_name");
  $folders_query = tep_db_query($sql_query);
  
  while($folders = tep_db_fetch_array($folders_query)) {
    $sql_subfolder=("select count(folders_id) as count from " . TABLE_LIBRARY_FOLDERS . " where folders_parent_id  = '" . $folders['folders_id'] . "'");
    $subfolders_query = tep_db_query($sql_subfolder);
    $subfolders = tep_db_fetch_array($subfolders_query);
    $folders_array[] = array('id' => $folders['folders_id'],
                             'name' => $folders['folders_name'],
                             'subs' => $subfolders['count']);
  }
  $files_array = array();
  $files_query = tep_db_query("select f.files_id, f.files_name, fd.files_descriptive_name, fi.icon_small from " . TABLE_LIBRARY_FILES . " f left join " . TABLE_FILE_ICONS . " fi on fi.icon_id = f.files_icon, " . TABLE_LIBRARY_FILES_DESCRIPTION . " fd, " . TABLE_LIBRARY_FILES_TO_FOLDERS . " ff where ff.folders_id = '" . $current_parent . "' and f.files_id = ff.files_id and f.files_id = fd.files_id and fd.language_id = '" . (int)$_SESSION['languages_id'] . "' and f.files_status = '1' order by fd.files_descriptive_name");
  while ($files = tep_db_fetch_array($files_query)) {
    $files_array[] = array('id' => $files['files_id'],
                           'descriptive_name' => $files['files_descriptive_name'],
                           'icon_small' => $files['icon_small'],
                            'name' => $files['files_name']);
  }

  $spacer = '';
  if ( count($folders_array) > 0 ||  count($files_array) > 0 ) {
    for ( $i = 0; $i < $current_lvl; $i++) {
      $spacer .= '&nbsp;&nbsp;&nbsp;';
    }
  }
//  print_r($expanded_folders_array);
  foreach ( $folders_array as $indx => $folder_data ) {
  	$has_file = tep_db_query("select f.files_id, f.files_name, fd.files_descriptive_name, fi.icon_small from " . TABLE_LIBRARY_FILES . " f, " . TABLE_FILE_ICONS . " fi, " . TABLE_LIBRARY_FILES_DESCRIPTION . " fd, " . TABLE_LIBRARY_FILES_TO_FOLDERS . " ff where ff.folders_id = '" . $folder_data['id'] . "' and f.files_id = ff.files_id and f.files_id = fd.files_id and fi.icon_id = f.files_icon and fd.language_id = '" . (int)$_SESSION['languages_id'] . "' and f.files_status = '1' order by fd.files_descriptive_name");
//  	echo $expanded_folders_array[3] . ' - <br>';
    if ( $expanded_folders_array[$folder_data['id']] == 1 ) {
      $control ='<a href="' . tep_href_link(FILENAME_LIBRARY_PRODUCT, tep_get_all_get_params(array('action', 'fldID')) . 'action=fld_contract&fldID=' . $folder_data['id']) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER).'&nbsp;' . $folder_data['name']. '</a>'.'</td>' . "\n";
    } elseif ( $folder_data['subs'] > 0 || tep_db_num_rows($has_file) > 0 ) {
      $control ='<a href="' . tep_href_link(FILENAME_LIBRARY_PRODUCT, tep_get_all_get_params(array('action', 'fldID')) . 'action=fld_expand&fldID=' . $folder_data['id']) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER).'&nbsp;' . $folder_data['name']. '</a></td>' . "\n";      
    } else {
      $control = '&nbsp;' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '&nbsp;' . $folder_data['name']. '</a></td>' . "\n";;
    }
    $table_data .= '<tr class="dataTableRow" align="left">' . "\n";
    $table_data .= '<td class="dataTableContent" style="border-bottom:1px solid #DDD;" align="center">&nbsp;</td>' . "\n";
    $table_data .= ' <td class="dataTableContent" style="border-bottom:1px solid #DDD;" align="center">&nbsp;</td>' . "\n";
    $table_data .= '<td class="dataTableContent" style="border-bottom:1px solid #DDD;" valign="middle" width="80%" >'.$spacer . $control;
    $table_data .= ' </tr>' . "\n"; 
    if ( $expanded_folders_array[$folder_data['id']] == 1) {
      $table_data .= files_table_build($folder_data['id'], $next_lvl, $expanded_folders_array, $selected_folders_array, $selected_files_array, $login_require_array, $purchase_require_array);
    }
  }
  foreach ( $files_array as $indx => $file_data ) {
    $table_data .= '<tr class="dataTableRow" align="left">' . "\n";
    $table_data .= '<td class="dataTableContent" style="border-bottom:1px solid #DDD;" align="center" width="10%" valign="middle">' . tep_draw_checkbox_field('fil[' . $file_data['id'] . ']', $file_data['id'], '', $selected_files_array[$file_data['id']]) . '</td>' . "\n";
    if ($file_data['icon_small'] != '') {
    	$img_file = '../images/file_icons/' . $file_data['icon_small'];
    } else {
    	$img_file = DIR_WS_ICONS . 'file.gif';
    }
    $table_data .= '<td class="dataTableContent" style="border-bottom:1px solid #DDD;" align="center" valign="middle" width="10%">'  . tep_draw_checkbox_field('pur_req[' . $file_data['id'] . ']', '1', $purchase_require_array[$file_data['id']]) . tep_draw_hidden_field('file[]', $file_data['id']) . '</td>' . "\n";
    $table_data .= '<td><table border="0" width="100%"><tr><td class="dataTableContent" style="border-bottom:1px solid #DDD;" valign="middle" width="3%">'.tep_image($img_file, ICON_FILE).'</td><td class="dataTableContent" style="border-bottom:1px solid #DDD;" valign="middle">' . $file_data['descriptive_name'] . '</td></tr></table>'."\n";
    $table_data .= '</tr>' . "\n";
  }
  return $table_data;
}

////
// Sets the status of a file
  function tep_set_files_status($files_id, $status) {
    if ($status == '1') {
      return tep_db_query("update " . TABLE_LIBRARY_FILES . " set files_status = '1', files_last_modified = now() where files_id = '" . (int)$files_id . "'");
    } elseif ($status == '0') {
      return tep_db_query("update " . TABLE_LIBRARY_FILES . " set files_status = '0', files_last_modified = now() where files_id = '" . (int)$files_id . "'");
    } else {
      return -1;
    }
  }
  
  	function products_table_build($current_parent = 0, $current_lvl = 0, $expanded_categories_array, $selected_categories_array, $selected_products_array, $login_require_array, $purchase_require_array) {
  
	  $next_lvl = $current_lvl + 1;
	  $table_data = '';
		
	  $categories_array = array();
	  $sql_query =("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id  = '" . $current_parent . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$_SESSION['languages_id'] . "' order by c.sort_order, cd.categories_name");
	  $categories_query = tep_db_query($sql_query);
	  
	  while($categories = tep_db_fetch_array($categories_query)) {
	    $sql_subcategories=("select count(categories_id) as count from " . TABLE_CATEGORIES . " where parent_id  = '" . $categories['categories_id'] . "'");
	    $subcategories_query = tep_db_query($sql_subcategories);
	    $subcategories = tep_db_fetch_array($subcategories_query);
	    $categories_array[] = array('id' => $categories['categories_id'],
	                             'name' => $categories['categories_name'],
	                             'subs' => $subcategories['count']);
	  }
	  $products_array = array();
	  $products_query = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p2c.categories_id = '" . $current_parent . "' and p.products_id = p2c.products_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$_SESSION['languages_id'] . "' and p.products_parent_id = '0' order by pd.products_name");
	  while ($products = tep_db_fetch_array($products_query)) {
	  	$sub_products = tep_fdm_get_sub_products($products['products_id']);
      // code mod to add the sub to the parent products listing, not to replace it
      $products_array[] = array('id' => $products['products_id'],
                               	'name' => $products['products_name']);
	  	if (sizeof($sub_products) > 0) {
	  		foreach ($sub_products as $sub) {
		  		$products_array[] = array('id' => $sub['products_id'],
			                            	'name' => $sub['products_name']);
		    }
	  	}
	  }
	
	  $spacer = '';
	  if ( count($categories_array) > 0 ||  count($products_array) > 0 ) {
	    for ( $i = 0; $i < $current_lvl; $i++) {
	      $spacer .= '&nbsp;&nbsp;&nbsp;';
	    }
	  }
	//  print_r($expanded_categories_array);
	  foreach ( $categories_array as $indx => $categories_data ) {
	  	$has_products = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p2c.categories_id = '" . $categories_data['id'] . "' and p.products_id = p2c.products_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$_SESSION['languages_id'] . "' and p.products_status = '1' order by pd.products_name");
	//  	echo $expanded_categories_array[3] . ' - <br>';
	    if ( $expanded_categories_array[$categories_data['id']] == 1 ) {
	      $control ='<a href="' . tep_href_link(FILENAME_LIBRARY_FILES_PRODUCTS, tep_get_all_get_params(array('action', 'fldID')) . 'action=fld_contract&fldID=' . $categories_data['id']) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER).'&nbsp;' . $categories_data['name']. '</a>'.'</td>' . "\n";
	    } elseif ( $categories_data['subs'] > 0 || tep_db_num_rows($has_products) > 0 ) {
	      $control ='<a href="' . tep_href_link(FILENAME_LIBRARY_FILES_PRODUCTS, tep_get_all_get_params(array('action', 'fldID')) . 'action=fld_expand&fldID=' . $categories_data['id']) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER).'&nbsp;' . $categories_data['name']. '</a></td>' . "\n";  
	    } else {
	      $control = '&nbsp;' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '&nbsp;' . $categories_data['name']. '</a></td>' . "\n";;
	    }
	    $table_data .= '<tr class="dataTableRow" align="left">' . "\n";
	    $table_data .= '<td class="dataTableContent" style="border-bottom:1px solid #DDD;" align="center">&nbsp;</td>' . "\n";
	    $table_data .= ' <td class="dataTableContent" style="border-bottom:1px solid #DDD;" align="center">&nbsp;</td>' . "\n";
	    $table_data .= '<td class="dataTableContent" style="border-bottom:1px solid #DDD;" valign="middle" width="80%" >'.$spacer . $control;
	    $table_data .= ' </tr>' . "\n"; 
	    if ( $expanded_categories_array[$categories_data['id']] == 1) {
	      $table_data .= products_table_build($categories_data['id'], $next_lvl, $expanded_categories_array, $selected_categories_array, $selected_products_array, $login_require_array, $purchase_require_array);
	    }
	  }
	  foreach ( $products_array as $indx => $products_data ) {
	    $table_data .= '<tr class="dataTableRow" align="left">' . "\n";
	    $table_data .= '<td class="dataTableContent" style="border-bottom:1px solid #DDD;" align="center" width="10%" valign="middle">' . tep_draw_checkbox_field('prod[' . $products_data['id'] . ']', $products_data['id'], '', $selected_products_array[$products_data['id']]) . '</td>' . "\n";
	    $img_file = DIR_WS_ICONS . 'file.gif';
	    $table_data .= '<td class="dataTableContent" style="border-bottom:1px solid #DDD;" align="center" valign="middle" width="10%">'  . tep_draw_checkbox_field('pur_req[' . $products_data['id'] . ']', '1', $purchase_require_array[$products_data['id']]) . tep_draw_hidden_field('products[]', $products_data['id']) . '</td>' . "\n";
	    $table_data .= '<td><table border="0" width="100%"><tr><td class="dataTableContent" style="border-bottom:1px solid #DDD;" valign="middle" width="3%">'.tep_image($img_file, ICON_FILE).'</td><td class="dataTableContent" style="border-bottom:1px solid #DDD;" valign="middle">' . $products_data['name'] . '</td></tr></table>'."\n";
	    $table_data .= '</tr>' . "\n";
	  }
	  return $table_data;
	}
	
	function tep_fdm_get_sub_products($products_id) {
		$sub_query = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$_SESSION['languages_id'] . "' and p.products_parent_id = '" . $products_id . "'");
		$sub_products =  array();
		while ($sub = tep_db_fetch_array($sub_query)) {
			$sub_products[] = array('products_id' => $sub['products_id'],
															'products_name' => $sub['products_name']);
		}
		return $sub_products;
	}	
  function tep_fdm_get_monthly_unique_downloads($year, $month) {
    global $_GET;
    $downloads_unique = 0;
    $downloads_query_select = "select dayofmonth(flfd.download_time) as row_day";
    $downloads_query_from = " from " . TABLE_LIBRARY_FILES_DOWNLOAD . " flfd";
    $downloads_query_where = " where year(flfd.download_time) = '" . $year . "' and month(flfd.download_time) = '" . $month . "'";
    $downloads_query_extra .= " group by dayofmonth(flfd.download_time)";
    if ( isset($_GET['files']) && $_GET['files'] != '0' ) {
      $downloads_query_where .= " and flfd.files_id = '" . $_GET['files'] . "'";
    }
    $downloads_query = tep_db_query($downloads_query_select . $downloads_query_from . $downloads_query_where . $downloads_query_extra);
    while ($downloads = tep_db_fetch_array($downloads_query)) {
      $downloads_unique_raw = "select files_id from " . TABLE_LIBRARY_FILES_DOWNLOAD . " where year(download_time) = '" . $year . "' and month(download_time) = '" . $month . "' and customers_id <> 0";
      $downloads_unique_raw2 = "select files_id from " . TABLE_LIBRARY_FILES_DOWNLOAD . " where year(download_time) = '" . $year . "' and month(download_time) = '" . $month . "' and customers_id = 0";
      $downloads_unique_raw .= " and dayofmonth(download_time) = '" . $downloads['row_day'] . "'";
      $downloads_unique_raw2 .= " and dayofmonth(download_time) = '" . $downloads['row_day'] . "'";
      if ( isset($_GET['files']) && $_GET['files'] != '0' ) {
        $downloads_unique_raw .= " and files_id = '" . $_GET['files'] . "'";
        $downloads_unique_raw2 .= " and files_id = '" . $_GET['files'] . "'";
      }
      $downloads_unique_raw .= " group by customers_id";
      $downloads_unique_raw2 .= " group by ip_addr";
      $downloads_unique += tep_db_num_rows(tep_db_query($downloads_unique_raw)) + tep_db_num_rows(tep_db_query($downloads_unique_raw2));
    }
    return $downloads_unique;
  }
  
  function tep_draw_files_pull_down($name, $parameters = '', $exclude = '') {
    global $currencies, $languages_id;

    if ($exclude == '') {
      $exclude = array();
    }
    $select_string = '<select name="' . $name . '"';
if ($parameters) {
      $select_string .= ' ' . $parameters;
    }
    $select_string .= '>';
    $files_query = tep_db_query("SELECT p.files_id, pd.files_descriptive_name
                                    FROM " . TABLE_LIBRARY_FILES . " p,
                                         " . TABLE_LIBRARY_FILES_DESCRIPTION . " pd
                                    WHERE p.files_id = pd.files_id
                                      and pd.language_id = '" . (int)$languages_id . "'
                                      and p.files_status = '1'
                                    ORDER BY files_descriptive_name");
    while ($files = tep_db_fetch_array($files_query)) {
      if (!in_array($files['files_id'], $exclude)) {
        $select_string .= '<option value="' . $files['files_id'] . '">' . $files['files_descriptive_name'] . '</option>\n';
      }
    }
    $select_string .= '</select>';
    return $select_string;
  }
?>