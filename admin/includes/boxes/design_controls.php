<?php
/*
  $Id: design_tools.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- Design Controls //-->
          <tr>
            <td>
<?php
$template_id_select_query = tep_db_query("select template_id from " . TABLE_TEMPLATE . "  where template_name = '" . DEFAULT_TEMPLATE . "'");
$template_id_select =  tep_db_fetch_array($template_id_select_query);
$template_default_id = $template_id_select['template_id'] ;
if (empty($template_id_select['template_id'])){
  $template_id_select['template_id'] = '0';
}
if (defined('MENU_DHTML') && MENU_DHTML == 'False'){
  $default_temp_link = '&amp;cID=' . $template_id_select['template_id'];
} else{
  $default_temp_link = '';
}
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_DESIGN_CONTROLS,
                   'link'  => tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'selected_box=design_controls' . $default_temp_link));
if ($_SESSION['selected_box'] == 'design_controls' || MENU_DHTML == 'True') {
  //RCI to include links  
  $returned_rci_top = $cre_RCI->get('designcontrols', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('designcontrols', 'boxesbottom');
  $returned_rci_top2 = $cre_RCI->get('designcontrols', 'boxestop2');
  $returned_rci_bottom2 = $cre_RCI->get('designcontrols', 'boxesbottom2');
  $returned_rci_top3 = $cre_RCI->get('designcontrols', 'boxestop3');
  $returned_rci_bottom3 = $cre_RCI->get('designcontrols', 'boxesbottom3');
  $returned_rci_top4 = $cre_RCI->get('designcontrols', 'boxestop4');
  $returned_rci_bottom4 = $cre_RCI->get('designcontrols', 'boxesbottom4');
  $returned_rci_top5 = $cre_RCI->get('designcontrols', 'boxestop5');
  $contents[] = array('text'  => $returned_rci_top .
                                 tep_admin_files_boxes('',BOX_HEADING_TEMPLATE_HEADER_TAGS ) .
                                 $returned_rci_top2 . 
                                 tep_admin_files_boxes(FILENAME_HEADER_TAGS_CONTROLLER, BOX_HEADER_TAGS_ADD_A_PAGE, 'NONSSL' , '', '2' ) .
                                 tep_admin_files_boxes(FILENAME_HEADER_TAGS_ENGLISH, BOX_HEADER_TAGS_ENGLISH, 'NONSSL' , '', '2' ).
                                 tep_admin_files_boxes(FILENAME_HEADER_TAGS_FILL_TAGS,  BOX_HEADER_TAGS_FILL_TAGS, 'NONSSL' , '', '2') .
                                 $returned_rci_bottom2 . 
                                 tep_admin_files_boxes('', BOX_HEADING_BRANDING_MANAGER) .
                                 $returned_rci_top3 . 
                                 tep_admin_files_boxes(FILENAME_BRANDING_MANAGER,BOX_HEADING_BRANDING_MANAGER, 'NONSSL', '', '2') .
                                 $returned_rci_bottom3 . 
                                 tep_admin_files_boxes('', BOX_HEADING_DESIGN_TEMPLATE) .
                                 $returned_rci_top4 . 
                                 tep_admin_files_boxes(FILENAME_TEMPLATE_ADMIN, BOX_HEADING_TEMPLATE_MANAGEMENT , 'NONSSL' , '', '2') .
                                 tep_admin_files_boxes(FILENAME_TEMPLATE_CONFIGURATION, BOX_HEADING_TEMPLATE_CONFIGURATION, 'NONSSL' ,'cID=' . $template_id_select['template_id'],'2') .
                                 $returned_rci_bottom4 . 
                                 tep_admin_files_boxes('', BOX_HEADING_DESIGN_INFOBOX) .
                                 $returned_rci_top5 . 
                                 tep_admin_files_boxes(FILENAME_INFOBOX_CONFIGURATION, BOX_HEADING_BOXES_ADMIN, 'NONSSL', 'gID=' . $template_id_select['template_id'],'2') .
                                 $returned_rci_bottom);
  }
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- design controls _eof //-->