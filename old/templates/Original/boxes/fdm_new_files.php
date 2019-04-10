<?php
/*
  $Id: fdm_new_files.php,v 1.1.1.1 2004/03/04 23:42:14 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- fdm_new_files bof //-->
<?php
$random_file_side = tep_db_query("select lf.files_id, lfd.files_description, lfd.files_descriptive_name from " . TABLE_LIBRARY_FILES . " lf, " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfd where lf.files_id = lfd.files_id and lfd.language_id = '" . $languages_id . "' order by lf.files_date_added desc limit 5");

$random_file_side_row = tep_db_num_rows($random_file_side);
if ($random_file_side_row > 0){
?>
          <tr>
            <td>
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_NEW_FILES . '</font>'
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
<!-- fdm_featured_files eof //-->