<?php
/*
  $Id: whos_online.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require(DIR_WS_LANGUAGES . $language . '/'.FILENAME_WHOS_ONLINEBOX);
$whos = new box_whos_online();
?>
<!-- whos_online //-->
<tr>
  <td>
    <?php
    $user_total = count($whos->rows);
    if ($user_total == 1) {
      $there_is_are = BOX_WHOS_ONLINE_THEREIS . '&nbsp;';
    } else {
      $there_is_are = BOX_WHOS_ONLINE_THEREARE . '&nbsp;';
    }
    if ($whos->guest_count == 1) {
      $word_guest = '&nbsp;' . BOX_WHOS_ONLINE_GUEST;
    } else {
      $word_guest = '&nbsp;' . BOX_WHOS_ONLINE_GUESTS;
    }
    if ($whos->member_count == 1) {
      $word_member = '&nbsp;' . BOX_WHOS_ONLINE_MEMBER;
    } else {
      $word_member = '&nbsp;' . BOX_WHOS_ONLINE_MEMBERS;
    }
    if (($whos->guest_count >= 1) && ($whos->member_count >= 1)) $word_and = '&nbsp;' . BOX_WHOS_ONLINE_AND . '&nbsp;<br>';
    $textstring = $there_is_are;
    if ($whos->guest_count >= 1) $textstring .= $whos->guest_count . $word_guest;
    if (!isset($word_and)) {
      $word_and = '';
    }
    $textstring .= $word_and;
    if ($whos->member_count >= 1) $textstring .= $whos->member_count . $word_member;
    $textstring .= '&nbsp;online.';
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_WHOS_ONLINE . '</font>');
    new $infobox_template_heading($info_box_contents, '', ((isset($column_location) && $column_location !='') ? $column_location : '') ); 
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  =>  $textstring
                                );
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
<!-- whos_online eof//-->