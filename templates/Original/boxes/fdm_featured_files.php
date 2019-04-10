<?php
/*
  $Id: fdm_featured_files.php,v 1.1.1.1 2004/03/04 23:42:14 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- fdm_featured_files bof //-->
<?php
  $CDpath_fdm = (isset($_GET['CDpath']) && (int)$_GET['CDpath'] != '') ? $_GET['CDpath'] : '';
  $random_file_side = tep_db_query("select distinct
                           lf.files_id,
                           lfd.files_descriptive_name,
                           fi.icon_large
                          from " . TABLE_LIBRARY_FILES . " lf left join " . TABLE_FILE_ICONS . " fi on fi.icon_id = lf.files_icon, 
                              " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfd,
                        " . TABLE_FEATURED_FILES . " ff
                                 where
                                   lf.files_status = '1'
                                   and ff.status = '1'
                                   and lf.files_id = ff.files_id
                                   and lfd.files_id = ff.files_id
                                   and lfd.language_id = '" . $languages_id . "'
                                   order by rand()");

$random_file_side_row = tep_db_num_rows($random_file_side);
if ($random_file_side_row > 0){
?>
          <tr>
            <td>
<?php
  $featured_random_file21 = tep_db_fetch_array($random_file_side);
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_FEATURED_FILES . '</font>'
                              );
  new infoBoxHeading($info_box_contents,  false, false, tep_href_link(FILENAME_FEATURED_FILES, '', 'NONSSL'));

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'center',
          'text'  => '<a href="' . tep_href_link(FILENAME_FILE_DETAIL, 'files_id=' . $featured_random_file21['files_id'] . '&CDpath=' . $CDpath_fdm, 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'file_icons/' . $featured_random_file21['icon_large'], $featured_random_file21['files_id'], FDM_LARGE_ICON_IMAGE_WIDTH, FDM_LARGE_ICON_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_FILE_DETAIL, 'files_id=' . $featured_random_file21['files_id'] . '&CDpath=' . $CDpath_fdm, 'NONSSL') . '">' . $featured_random_file21['files_descriptive_name'] . '</a>');
          
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<?php
}
?>
<!-- fdm_featured_files eof //-->