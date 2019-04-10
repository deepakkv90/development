<?php
/*
  $Id: manufacturers.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$manufacture = new box_manufacturers();
$number_of_rows =  count($manufacture->rows);
if ($number_of_rows > 0) {
  ?>
  <!-- manufacturers //-->
  <tr>
    <td>
      <?php
      $info_box_contents = array();
      $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_MANUFACTURERS . '</font>');
      new $infobox_template_heading($info_box_contents, '', ((isset($column_location) && $column_location !='') ? $column_location : '') );
      if ($number_of_rows <= MAX_DISPLAY_MANUFACTURERS_IN_A_LIST) {
        // Display a list
        $manufacturers_list = '';
        foreach ($manufacture->rows as $manufacturers) {
          $manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);
          if (isset($_GET['manufacturers_id']) && ($_GET['manufacturers_id'] == $manufacturers['manufacturers_id'])) $manufacturers_name = '<b>' . $manufacturers_name .'</b>';
          $manufacturers_list .= '<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers['manufacturers_id']) . '">' . $manufacturers_name . '</a><br>';
        }
        $manufacturers_list = substr($manufacturers_list, 0, -4);
        $info_box_contents = array();
        $info_box_contents[] = array('text' => $manufacturers_list);
      } else {
        // Display a drop-down
        $manufacturers_array = array();
        if (MAX_MANUFACTURERS_LIST < 2) {
          $manufacturers_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
        }
        foreach ($manufacture->rows as $manufacturers) {
          $manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);
          $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                         'text' => $manufacturers_name);
        }
        $info_box_contents = array();
        $info_box_contents[] = array('form' => tep_draw_form('manufacturers', tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false), 'get'),
                                     'text' => tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, (isset($_GET['manufacturers_id']) ? $_GET['manufacturers_id'] : ''), 'onChange="this.form.submit();" size="' . MAX_MANUFACTURERS_LIST . '" style="width: 100%"') . tep_hide_session_id());
      }
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
  <!-- manufacturers eof//-->
  <?php
}
?>