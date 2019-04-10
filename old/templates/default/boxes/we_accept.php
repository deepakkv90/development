<?php
/*
  $Id: we_accept.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require(DIR_WS_LANGUAGES . $language . '/card1.php');
?>
<!-- we_accept //-->
<tr>
  <td>
    <?php
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_WE_ACCEPT . '</font>'
                                );
    new $infobox_template_heading($info_box_contents, '', ((isset($column_location) && $column_location !='') ? $column_location : '') );
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center',
                                 'text'  => tep_image(DIR_WS_IMAGES . 'cards/cards2.gif', (defined('BOX_WE_ACCEPT') ? BOX_WE_ACCEPT : 'Cards We Accept')) );
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
<!-- we_accept eof//-->