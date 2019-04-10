<?php
/*
  $Id: fdm_file_library.php,v 1.0.0.0 2006/10/12 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require_once(DIR_WS_FUNCTIONS . FILENAME_FDM_FUNCTIONS);
?>
<!-- fdm_file_library.php -->
<tr>
  <td>
    <?php
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_FILE_LIBRARY . '</font>');
//    new infoBoxHeading($info_box_contents, false, false);
    new $infobox_template_heading($info_box_contents, '', $column_location);
    $file_directory = '';
    $level = array();
    tep_file_directory(0, '', $file_directory, $level, 0);
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                            'text'  => $file_directory);
//    new infoBox($info_box_contents);
    new $infobox_template($info_box_contents, true, true, $column_location);
    if (TEMPLATE_INCLUDE_FOOTER == 'true') {
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left',
                                              'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1'));

//      new infoboxFooter($info_box_contents, true, true);
      new $infobox_template_footer($info_box_contents, $column_location);
    }
    ?>
  </td>
</tr>
<!-- fdm_file_library.php-eof //-->