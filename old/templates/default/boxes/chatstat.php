<?php
/*
  $Id: chatstat.php,v 1.0 2009/03/03 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- chatstat //-->
<tr>
  <td>
    <?php
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center',
                                 'text'  => '<a href="http://www.chatstat.com/banner_click.asp?aff=CS-CRFL" target="_blank"><img border="0" src="https://www.chatstat.com/newbanners/images/standardv3_120x90_white.jpg" alt="ChatStat Logo"><br><span class="smallText">Get Chatstat live chat<br>Free for 30 days!</span></a><br>'
                                 );
    new $infobox_template($info_box_contents, false, false, ((isset($column_location) && $column_location !='') ? $column_location : '') );
    ?>
  </td>
</tr>
<!-- chatstat_eof//-->