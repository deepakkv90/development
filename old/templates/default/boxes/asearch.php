<?php
/*
  $Id: asearch.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- asearch //-->
<tr>
  <td>
    <?php
    $param = '';
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_ASEARCH . '</font>');
    new $infobox_template_heading($info_box_contents, '', ((isset($column_location) && $column_location !='') ? $column_location : '') ); 
    $hide = tep_hide_session_id();
    $info_box_contents = array();
    $info_box_contents[] = array('form'  => '<form name="quick_find_article" method="get" action="' . tep_href_link(FILENAME_ARTICLE_SEARCH, '', 'NONSSL', false) . '">',
                                 'align' => 'center',
                                 'text'  => $hide . $param . '<input type="text" name="akeywords" size="10" maxlength="30" value="' . htmlspecialchars(StripSlashes(@$_GET["akeywords"])) . '">' . tep_template_image_submit('button_quick_find.gif', BOX_HEADING_SEARCH) . '<br><input type="checkbox" name="description">' . BOX_ASEARCH_TEXT . '<br>');
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
<!-- asearch eof//-->