<?php
/*
  $Id: fdm_folder_files_listing.php,v 1.0.0.0 2006/10/12 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
  <!-- bof fdm_folder_files_listing.php-->
  <?php
  require_once(DIR_WS_LANGUAGES . $language . '/modules/' . FILENAME_FOLDER_FILES_LISTING);
  require_once(DIR_WS_FUNCTIONS . FILENAME_FDM_FUNCTIONS);  

  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'lf.files_id');
  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
    ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_FILES); ?></td>
        <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
    </table>
    <?php
  }
  ?>
  <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
    <tr> 
      <td>
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td>
              <?php 
              if ($listing_split->number_of_rows > 0) {
                $rows = 0;
                $listing_query = tep_db_query($listing_split->sql_query);
                $no_of_listings = tep_db_num_rows($listing_query);

                while ($_listing = tep_db_fetch_array($listing_query)) {
                  $listing[] = $_listing;
                  $list_of_prdct_ids[] = $_listing['files_id'];
                }

                $select_list_of_prdct_ids = "files_id = '".$list_of_prdct_ids[0]."' ";
                if ($no_of_listings > 1) {
                  for ($n = 1 ; $n < count($list_of_prdct_ids) ; $n++) {
                    $select_list_of_prdct_ids .= "or files_id = '".$list_of_prdct_ids[$n]."' ";
                  }
                }
                require_once(DIR_WS_CLASSES . FILENAME_DOWNLOAD);
                $download = new download();
                for ($x = 0; $x < $no_of_listings; $x++) {
                  $rows++;
                  if (($rows/2) == floor($rows/2)) {
                    $list_box_contents[] = array('params' => 'class="productListing-even"');
                  } else {
                    $list_box_contents[] = array('params' => 'class="productListing-odd"');
                  }
                  $cur_row = sizeof($list_box_contents) - 1;
                  $current_file_id = $listing[$x]['files_id'];
                  $download->process($current_file_id,$_SESSION['customer_id']); 
                  $download_criteria=$download->file_content;
                  $file_query = tep_db_query("SELECT lf.files_name, lf.files_icon, lf.files_download, lf.file_date_created, lf.file_availability, lf.require_products_id, lf.files_status, lfd.files_description, lfd.files_descriptive_name, fi.icon_small 
                                                             from " . TABLE_LIBRARY_FILES . " lf, 
                                                                    " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfd, 
                                                                    " . TABLE_FILE_ICONS . " fi 
                                                           WHERE lf.files_icon = fi.icon_id 
                                                             and lf.files_status = '1' 
                                                             and lf.files_id = '" . (int)$current_file_id . "' 
                                                             and lfd.files_id = '" . (int)$current_file_id . "' 
                                                             and lfd.language_id = '" . $languages_id . "'");
                  $CDpath_fdm = (isset($_GET['CDpath']) && (int)$_GET['CDpath'] != '') ? $_GET['CDpath'] : '';
                  $file = tep_db_fetch_array($file_query);
                  $lc_text = '';
                  $lc_align = '';
                  $icon = ($file['icon_small'] == '') ? tep_draw_separator('pixel_trans.gif', '32', '32') : '<a href="' . tep_href_link(FILENAME_FILE_DETAIL, 'file_id=' . $current_file_id . (isset($current_folder_id) ? '&folder_id='. $current_folder_id : '') . '&CDpath=' . $CDpath_fdm) . '">' . tep_image(DIR_WS_IMAGES . 'file_icons/' . $file['icon_small']) . '</a>';
                  $desc = preg_replace('/sS*$/i', '', substr($file['files_description'], 0,180));
                  $files_name = DIR_FS_CATALOG . LIBRARY_DIRECTORY . $file['files_name'];
                   if (file_exists($files_name)) {
                     $f_size = (int)@filesize($files_name);   
                     $human_readable_size = '(' . cre_resize_bytes($f_size) . ')';
                     $files_date =  date("m/d/Y", filemtime($files_name));
                   }else{
                     $human_readable_size = '&nbsp;';
                     $files_date = '&nbsp;';
                   }
                   $backgrnd = ($rows & 1) ? 'filesListing-odd' : 'filesListing-even';
                   $lc_text .= '<table border="0" cellpadding="3" cellspacing="0" width="100%"><tr class="' . $backgrnd . '"><td class="' . $backgrnd . '"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr>';
                   $lc_text .= '<td width="' . FDM_SMALL_ICON_IMAGE_WIDTH . '">' . $icon . '</td>';
                   $lc_text .= '<td class="filesListing-name">&nbsp;<a href="'.tep_href_link(FILENAME_FILE_DETAIL, 'file_id=' . $current_file_id . (isset($current_folder_id) ? '&folder_id=' . $current_folder_id : '') . '&CDpath=' . $CDpath_fdm) . '">' . $file['files_name'] . '</a>&nbsp;&nbsp;&nbsp;' . $human_readable_size . '</td>';
                   $lc_text .= '<td class="filesListing-main" align="right">' . $files_date . '</td></tr></table></td></tr><tr>';
                   $lc_text .= '<td class="fileListing_heading"><a href="' . tep_href_link(FILENAME_FILE_DETAIL, 'file_id=' . $current_file_id . (isset($current_folder_id) ? '&folder_id=' . $current_folder_id : '') . '&CDpath=' . $CDpath_fdm). '"><b>' . $file['files_descriptive_name'] . '</b></a></td></tr><tr>';
                   $lc_text .= '<td class="filesListing-main">' . $desc . '</td></tr><tr><td><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr>';
                   $lc_text .= '<td class="filesListing-main" width="50%">' . $download_criteria . '</td><td align="right" class="main" width="50%">' . TEXT_DOWNLOADS . ':&nbsp;' . $file['files_download'] . '</td>';
                   $lc_text .= '</tr></table></td></tr></table></td></tr></table>';

                   $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                                                           'params' => 'class="productListing-data"',
                                                                           'text'  => $lc_text);
                }  // end for $x

                new productListingBox($list_box_contents);

              }else{
                $list_box_contents = array();
                $list_box_contents[0] = array('params' => 'class="productListing-odd"');
                $list_box_contents[0][] = array('params' => 'class="productListing-data"','text' => TEXT_NO_FILES);

                new productListingBox($list_box_contents);
              }
              ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <?php
  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
    ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_FILES); ?></td>
        <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td>
      </tr>
    </table>
    <?php
  }
?><!-- eof fdm_folder_files_listing.php-->
