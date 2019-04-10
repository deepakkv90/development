<?php
/*
  $Id: forms.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
function tep_has_forms_subcategories($forms_id) {
  $forms_query = tep_db_query("select fss_categories_id from " . TABLE_FSS_CATEGORIES . " where fss_categories_parent_id = '" . $forms_id . "'");
  if (tep_db_num_rows($forms_query) > 0) {
    return true;
  } else {
    return false;
  }
}

function tep_show_form($fid, $fpath, $COLLAPSABLE) {
  global $forms_string3, $_GET, $level;
  $selectedPath = array();

  if(!is_array($level)){
    $level = array();
  }


  $forms_query = tep_db_query("select fss_categories_id, fss_categories_name, fss_categories_parent_id from " . TABLE_FSS_CATEGORIES . " where fss_categories_parent_id = " . $fid . " and fss_categories_id <> 1 order by sort_order, fss_categories_name");
  $i = 0;
  while ($forms = tep_db_fetch_array($forms_query))  {
    if ($level[$forms['fss_categories_parent_id']] == "") {
      $level[$forms['fss_categories_parent_id']] = 0; 
    }
    $level[$forms['fss_categories_id']] = $level[$forms['fss_categories_parent_id']] + 1;

    for ($a=1; $a<$level{$forms['fss_categories_id']}; $a++) {
      $forms_string3 .= "&nbsp;&nbsp;";
    }

    if ($level{$forms['fss_categories_id']} == 1 && $i != 0) {
      $forms_string3 .= '<hr />';
    }

    $i++;

    if ($level[$forms['fss_categories_id']] != 1) {
      $forms_string3 .= '|__';
    }
    
    $forms_string3 .= '<a href="';
    $forms_string3 .= tep_href_link(FILENAME_FSS_FORMS_INDEX, 'fPath=' . $forms['fss_categories_id']);
    $forms_string3 .= '">';

    if (isset($_GET['fPath']) && $_GET['fPath']) {
      $selectedPath = split("_", $_GET['fPath']);
    }
    
    if (in_array($forms['fss_categories_id'], $selectedPath)) {
      $forms_string3 .= '<font color="#ff0000"><b>'; 
    }

    if ($level[$forms['fss_categories_id']] == 1) {
      $forms_string3 .= '<b>' . $forms['fss_categories_name'] . '</b>';
    } else {
      $forms_string3 .= $forms['fss_categories_name'];
    }
    
    if ($COLLAPSABLE && tep_has_forms_subcategories($forms['fss_categories_id'])) {
      $forms_string3 .= ' ->'; 
    }


    if (in_array($forms['fss_categories_id'], $selectedPath)) { $forms_string3 .= '</b></font>'; }

    $forms_string3 .= '</a>';

    $forms_string3 .= '<br>';
  
    if (tep_has_forms_subcategories($forms['fss_categories_id'])) {

      if ($COLLAPSABLE) {
        if (in_array($forms['fss_categories_id'], $selectedPath)) {
          tep_show_form($forms['fss_categories_id'], $fpath, $COLLAPSABLE);
        }
      }
      else { tep_show_form($forms['fss_categories_id'], $fpath, $COLLAPSABLE); }

    }
  }  
}
?>
<!-- forms //-->
<tr>
  <td>
  <?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_FORMS . '</font>'
                              );
  new $infobox_template_heading($info_box_contents, false, $column_location);
  $forms_string3 = '';
  tep_show_form(0,'',0);
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $forms_string3);
  new $infobox_template($info_box_contents, true, true);
  
  if (TEMPLATE_INCLUDE_FOOTER == 'true'){
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                );
    new $infobox_template_footer($info_box_contents, $column_location );
  }
    
?>
   </td>
</tr>
<!-- forms eof//-->