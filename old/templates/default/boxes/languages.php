<?php
/*
  $Id: languages.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- languages //-->
<tr>
  <td>
    <?php
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_LANGUAGES . '</font>');
    new $infobox_template_heading($info_box_contents, '', ((isset($column_location) && $column_location !='') ? $column_location : '') );
    if (!isset($lng) || (isset($lng) && !is_object($lng))) {
      include(DIR_WS_CLASSES . FILENAME_LANGUAGE);
      $lng = new language;
    }
    $languages_string = '';
    reset($lng->catalog_languages);
    while (list($key, $value) = each($lng->catalog_languages)) {
      $languages_string .= ' <a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type) . '">' . tep_image(DIR_WS_LANGUAGES .  $value['directory'] . '/images/' . $value['image'], $value['name']) . '</a> ';
    }
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center',
                                 'text' => $languages_string);
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
<!-- languages eof//-->