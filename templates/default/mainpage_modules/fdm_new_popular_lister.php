<?php
/*
  $Id: fdm_new_popular_lister.php,v 1.0.0.0 2008/01/24 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- new_popular_lister_bof //-->
<?php
  $list_box_contents = array();
  $column_list = array('FILE_LIST_NEW_DOWNLOADS', 'FILE_LIST_POPULAR_DOWNLOADS');
  for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
    switch ($column_list[$col]) {
      case 'FILE_LIST_NEW_DOWNLOADS':
        $lc_text = TABLE_HEADING_NEW_DOWNLOADS;
        $lc_align = 'center';
        break;
      case 'FILE_LIST_POPULAR_DOWNLOADS':
        $lc_text = TABLE_HEADING_POPULAR_DOWNLOADS;
        $lc_align = 'center';
        break;
    }

    $list_box_contents[0][] = array('align' => $lc_align,
                                    'params' => 'class="productListing-heading"',
                                    'text' => '&nbsp;' . $lc_text . '&nbsp;');
  }
  
  $rows = 0;
  $listing_query = tep_db_query("select lf.files_id, lf.files_name, lfd.files_description, lfd.files_descriptive_name, fi.icon_large from " . TABLE_LIBRARY_FILES . " lf, " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfd, " . TABLE_FILE_ICONS . " fi where lf.files_id = lfd.files_id and lfd.language_id = '" . $languages_id . "' and fi.icon_id = lf.files_icon order by lf.files_date_added desc limit 5");
  $no_listing1 = tep_db_num_rows($listing_query);
  while ($_listing = tep_db_fetch_array($listing_query)) {
    $listing1[] = $_listing;
  }
  $month_ago = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 30, date('Y')));
  $listing_query = tep_db_query("select files_id, count(*) as number from " . TABLE_LIBRARY_FILES_DOWNLOAD . " where download_time between '" . $month_ago . "' and '" . date('Y-m-d') . "' group by files_id order by number desc limit 5");
  $files_ids = '';
  while ($_listing = tep_db_fetch_array($listing_query)) {
    $files_ids .= $_listing['files_id'] . ', ';
  }
  $files_ids .= "''";
  $listing_query = tep_db_query("select lf.files_id, lf.files_name, lfd.files_description, lfd.files_descriptive_name, fi.icon_large from " . TABLE_LIBRARY_FILES . " lf, " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfd, " . TABLE_FILE_ICONS . " fi where lf.files_id = lfd.files_id and lfd.language_id = '" . $languages_id . "' and fi.icon_id = lf.files_icon and lf.files_id in (" . $files_ids . ")");
  $no_listing2 = tep_db_num_rows($listing_query);
  while ($_listing = tep_db_fetch_array($listing_query)) {
    $listing2[] = $_listing;
  }
  for ($x = 0; $x < max($no_listing1, $no_listing2); $x++) {
    $rows++;
    if (($rows/2) == floor($rows/2)) {
      $list_box_contents[] = array('params' => 'class="productListing-even"');
    } else {
      $list_box_contents[] = array('params' => 'class="productListing-odd"');
    }
    $cur_row = sizeof($list_box_contents) - 1;
    for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
      $lc_align = '';

      switch ($column_list[$col]) {
        case 'FILE_LIST_NEW_DOWNLOADS':
          $lc_align = 'center';
          $lc_text = '<a href="' . tep_href_link(FILENAME_FILE_DETAIL, 'file_id=' . $listing1[$x]['files_id']) . '">' . tep_image(DIR_WS_IMAGES . 'file_icons/' . $listing1[$x]['icon_large'], $listing1[$x]['files_descriptive_name'], FDM_LARGE_ICON_IMAGE_WIDTH, FDM_LARGE_ICON_IMAGE_HEIGHT) . '</a><br>&nbsp;<a href="' . tep_href_link(FILENAME_FILE_DETAIL, 'file_id=' . $listing1[$x]['files_id']) . '">' . $listing1[$x]['files_descriptive_name'] . '</a>';
          break;
        case 'FILE_LIST_POPULAR_DOWNLOADS':
          $lc_align = 'center';
          $lc_text = '<a href="' . tep_href_link(FILENAME_FILE_DETAIL, 'file_id=' . $listing2[$x]['files_id']) . '">' . tep_image(DIR_WS_IMAGES . 'file_icons/' . $listing2[$x]['icon_large'], $listing2[$x]['files_descriptive_name'], FDM_LARGE_ICON_IMAGE_WIDTH, FDM_LARGE_ICON_IMAGE_HEIGHT) . '</a><br>&nbsp;<a href="' . tep_href_link(FILENAME_FILE_DETAIL, 'file_id=' . $listing2[$x]['files_id']) . '">' . $listing2[$x]['files_descriptive_name'] . '</a>';
          break;
      }

      $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                             'params' => 'class="productListing-data"',
                                             'text'  => $lc_text);
    }
  }
  new productListingBox($list_box_contents);
?>
<!-- new_popular_lister_eof //-->