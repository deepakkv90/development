<?php
/*
  $Id: example.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- example //-->
<tr>
  <td>
  <?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_EXAMPLE . '</font>');
  // Change BOX_HEADING_EXAMPLE to a name of your choice. Use the same in Infobox Admin to configure the infobox title.
  new $infobox_template_heading($info_box_contents, '', ((isset($column_location) && $column_location !='') ? $column_location : '') ); 
  $info_box_contents = array();
  // add your infobox content to the text variable below  
  $info_box_contents[] = array('align' => 'center',
                               'text'  => 'Your Content Goes Here'
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
<!-- example eof//-->