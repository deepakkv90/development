<?php

  global $search_keywords, $languages_id, $language, $_GET;
  if (defined('MODULE_ADDONS_FDM_STATUS') && MODULE_ADDONS_FDM_STATUS == 'True') { 
  if (isset($search_keywords) && (sizeof($search_keywords) > 0)) {
    $where_str = " and (";
    for ($i=0, $n=sizeof($search_keywords); $i<$n; $i++ ) {
      switch ($search_keywords[$i]) {
        case '(':
        case ')':
        case 'and':
        case 'or':
          $where_str .= " " . $search_keywords[$i] . " ";
          break;
        default:
          $keyword = tep_db_prepare_input($search_keywords[$i]);
          $where_str .= "(lfd.files_descriptive_name like '%" . tep_db_input($keyword) . "%'";
          if (isset($_GET['search_in_description']) && ($_GET['search_in_description'] == '1')) $where_str .= " or lfd.files_description like '%" . tep_db_input($keyword) . "%'";
          $where_str .= ')';
          break;
      }
    }
    $where_str .= " )";
  }
  
  $listing_sql = "select lf.files_id, lfd.files_descriptive_name, fi.icon_large as file_icon from " . TABLE_LIBRARY_FILES . " lf left join " . TABLE_FILE_ICONS . " fi on fi.icon_id = lf.files_icon, " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfd where lf.files_status = '1' and lfd.files_id = lf.files_id and lfd.language_id = '" . $languages_id . "'" . $where_str . " order by lfd.files_descriptive_name";
  
  $file_query = tep_db_query($listing_sql);
  if (tep_db_num_rows($file_query) > 0) { 
?>
      <tr>
        <td>&nbsp;</td>
      </tr>
<?php
    if (MAIN_TABLE_BORDER == 'yes') {
      $heading_text = HEADING_TITLE ;
      table_image_border_top(false, false, $heading_text);
    }
    ?>
        <tr>
          <td>
<?php 
    if (LIBRARY_FILE_FOLDERS_LISTING  == 'detail') {
      include(DIR_WS_MODULES . FILENAME_FOLDER_FILES_LISTING); 
    } else {
      include(DIR_WS_MODULES . FILENAME_FOLDER_FILES_LISTING_TABLE);
    }
?>
          </td>
        </tr>
<?php
    if (MAIN_TABLE_BORDER == 'yes'){
      table_image_border_bottom();
    }
  }
  }
?>