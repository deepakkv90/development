<?php
/*
  $Id: fdm_popular_downloads.php,v 1.1.1.1 2004/03/04 23:42:14 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- fdm_popular_downloads bof //-->
<?php
$month_ago = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 30, date('Y')));
$listing_query = tep_db_query("select files_id, count(*) as number from " . TABLE_LIBRARY_FILES_DOWNLOAD . " where download_time between '" . $month_ago . "' and '" . date('Y-m-d') . "' group by files_id order by number desc limit 5");
$files_ids = '';
while ($_listing = tep_db_fetch_array($listing_query)) {
  $files_ids .= $_listing['files_id'] . ', ';
}
$files_ids .= "''";
$random_file_side = tep_db_query("select lf.files_id, lf.files_name, lfd.files_description, lfd.files_descriptive_name, fi.icon_large from " . TABLE_LIBRARY_FILES . " lf, " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfd, " . TABLE_FILE_ICONS . " fi where lf.files_id = lfd.files_id and lfd.language_id = '" . $languages_id . "' and fi.icon_id = lf.files_icon and lf.files_id in (" . $files_ids . ")");

$random_file_side_row = tep_db_num_rows($random_file_side);
if ($random_file_side_row > 0){
?>
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_POPULAR_DOWNLOADS . '</font>'
                              );
  new infoBoxHeading($info_box_contents,  false, false);

  $info_box_contents = array();
  $i = 1;
  $file_content = '<table>';
  while ($featured_random_file21 = tep_db_fetch_array($random_file_side)) {
    $file_content .= '<tr><td class="main" valign="top">' . $i . '. </td><td class="main" valign="top"><a href="' . tep_href_link(FILENAME_FILE_DETAIL, 'files_id=' . $featured_random_file21['files_id'], 'NONSSL') . '">' . $featured_random_file21['files_descriptive_name'] . '</a></td></tr>';
    $i++;
  }
  $file_content .= '</table>';
  $info_box_contents[] = array('align' => '',
                               'text'  => $file_content);
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<?php
}
?>
<!-- fdm_popular_downloads eof //-->