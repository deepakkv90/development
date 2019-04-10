<?php
/*
  $Id: calendar.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (DOWN_FOR_MAINTENANCE != 'true') { // the iframe willnot work correctly if the site is down for maintenance
  ?>
  <!-- events_calendar //-->
  <tr>
    <td>
      <?php
      $info_box_contents = array();
      $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_CALENDAR . '</font>');
      new $infobox_template_heading($info_box_contents,tep_href_link(FILENAME_EVENTS_CALENDAR, '', 'NONSSL'), ((isset($column_location) && $column_location !='') ? $column_location : '') ); 
      $_month = isset($_GET['_month']) ? (int)$_GET['_month'] : date('n');
      $_year = isset($_GET['_year']) ? (iNT)$_GET['_year'] : date('Y');
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'center',
                                   'text' => '<iframe name="calendar" id="calendar" align="middle" marginwidth="0" marginheight="0" ' .
                                   'src="'  . FILENAME_EVENTS_CALENDAR_CONTENT . '?_month=' . $_month .'&amp;_year='. $_year .'" frameborder="0" height="220" width="162" scrolling="no"> ' .IFRAME_ERROR.'</iframe> ');
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
  <!-- events_calendar eof//-->
  <?php
  }
?>