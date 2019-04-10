<?php
/*
  $Id: manufacturer_info.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$info = new box_manufacturer_info();
if (count($info->rows) > 0) {
  foreach ($info->rows as $manufacturer) {
?>
    <!-- manufacturer_info //-->
    <tr>
      <td>
        <?php
        $info_box_contents = array();
        $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_MANUFACTURER_INFO . '</font>');
        new $infobox_template_heading($info_box_contents, '', ((isset($column_location) && $column_location !='') ? $column_location : '') );
        $manufacturer_info_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">';
        if (tep_not_null($manufacturer['manufacturers_image'])) $manufacturer_info_string .= '<tr><td align="center" class="infoBoxContents" colspan="2">' . tep_image(DIR_WS_IMAGES . $manufacturer['manufacturers_image'], $manufacturer['manufacturers_name']) . '</td></tr>';
        if (tep_not_null($manufacturer['manufacturers_url'])) $manufacturer_info_string .= '<tr><td valign="top" class="infoBoxContents">-&nbsp;</td><td valign="top" class="infoBoxContents"><a href="' . tep_href_link(FILENAME_REDIRECT, 'action=manufacturer&amp;manufacturers_id=' . $manufacturer['manufacturers_id']) . '" target="_blank">' . sprintf(BOX_MANUFACTURER_INFO_HOMEPAGE, $manufacturer['manufacturers_name']) . '</a></td></tr>';
        $manufacturer_info_string .= '<tr><td valign="top" class="infoBoxContents">-&nbsp;</td><td valign="top" class="infoBoxContents"><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer['manf_id']) . '">' . BOX_MANUFACTURER_INFO_OTHER_PRODUCTS . ' ' . $manufacturer['manufacturers_name'] . '</a></td></tr></table>';  
        $info_box_contents = array();
        $info_box_contents[] = array('text' => $manufacturer_info_string);
        new $infobox_template($info_box_contents, true, true, ((isset($column_location) && $column_location !='') ? $column_location : '') );
        if (TEMPLATE_INCLUDE_FOOTER =='true'){
          $info_box_contents = array();
          $info_box_contents[] = array('align' => 'left',
                                       'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                      );
          new $infobox_template_footer($info_box_contents, ((isset($column_location) && $column_location !='') ? $column_location : '') );
        }  
        ?>
      </td>
    </tr>
    <!-- manufacturer_info eof//-->
    <?php
  }
}
?>