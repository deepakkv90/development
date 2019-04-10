<?php
/*
  $Id: fdm_featured_files.php,v 1.0.0.0 2008/01/24 13:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- fdm_featured_files //-->
<?php
$CDpath_fdm = (isset($_GET['CDpath']) && (int)$_GET['CDpath'] != '') ? $_GET['CDpath'] : '';
$info_box_contents = array();
$info_box_contents[] = array('align' => 'left', 'text' => TABLE_HEADING_FEATURED_FILES);

$featured_files_2bb_query = tep_db_query("SELECT DISTINCT lf.files_id, lfd.files_descriptive_name, fi.icon_large
                                            from " . TABLE_LIBRARY_FILES . " lf 
                                          LEFT JOIN " . TABLE_FILE_ICONS . " fi on fi.icon_id = lf.files_icon, 
                                                    " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfd,
                                                    " . TABLE_FEATURED_FILES . " ff
                                          WHERE lf.files_status = '1'
                                            and ff.status = '1'
                                            and lf.files_id = ff.files_id
                                            and lfd.files_id = ff.files_id
                                            and lfd.language_id = '" . $languages_id . "'
                                          ORDER BY rand(),lf.file_date_created DESC, lfd.files_descriptive_name limit " . MAX_DISPLAY_FDM_SEARCH_RESULTS);
$row = 0;
$col = 0;
$num = 0;
while ($featured_files_2bb = tep_db_fetch_array($featured_files_2bb_query)) {
  $num ++;
  if ($num == 1) {
    new contentBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_FEATURED_FILES));
  }    
  $info_box_contents[$row][$col] = array('align' => 'center',
                                         'params' => 'class="smallText" width="33%" valign="top"',
                                         'text' => '<a href="' . tep_href_link(FILENAME_FILE_DETAIL, 'files_id=' . $featured_files_2bb['files_id'] . '&CDpath=' . $CDpath_fdm, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'file_icons/' . $featured_files_2bb['icon_large'], $featured_files_2bb['files_descriptive_name'], FDM_LARGE_ICON_IMAGE_WIDTH, FDM_LARGE_ICON_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_FILE_DETAIL, 'files_id=' . $featured_files_2bb['files_id'] . '&CDpath=' . $CDpath_fdm, 'NONSSL') . '">' . $featured_files_2bb['files_descriptive_name'] . '</a>');
  $col ++;
  if ($col > 2) {
    $col = 0;
    $row ++;
  }
}
if($num) {
  new contentBox($info_box_contents);
}
?>
<!-- fdm_featured_files_eof //-->
