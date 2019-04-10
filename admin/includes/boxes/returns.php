<?php
/*
  $Id: returns.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- returns //-->
          <tr>
            <td>
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_RETURNS_HEADING,
                   'link'  => tep_href_link(FILENAME_RETURNS, 'selected_box=returns'));

if ($_SESSION['selected_box'] == 'returns' || MENU_DHTML == 'True') {
  //RCI to include links  
  $returned_rci_top = $cre_RCI->get('reports', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('reports', 'boxesbottom');
  $contents[] = array('text'  => $returned_rci_top . 
        tep_admin_files_boxes(FILENAME_RETURNS , BOX_RETURNS_MAIN, 'SSL','','2') .
        tep_admin_files_boxes(FILENAME_RETURNS_REASONS , BOX_RETURNS_REASONS, 'SSL','','2') .
        tep_admin_files_boxes(FILENAME_REFUND_METHODS , BOX_HEADING_REFUNDS, 'SSL','','2') .
        tep_admin_files_boxes(FILENAME_RETURNS_STATUS , BOX_RETURNS_STATUS, 'SSL','','2') .
        tep_admin_files_boxes(FILENAME_RETURNS_TEXT , BOX_RETURNS_TEXT, 'SSL','','2') . 
        $returned_rci_bottom);
}
$box = new box;
echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- returns_eof //-->