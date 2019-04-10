<?php
/*
  $Id: fdm_library_product.php,v 1.0.0.0 2006/10/12 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_FUNCTIONS . FILENAME_FDM_FUNCTIONS);
  $is_62 = (defined('PROJECT_VERSION') && ereg('6.2', PROJECT_VERSION)) ? true : false;
  $parm_exclude = array('fPath','fldID','fID','action');
  
  $pID = (isset($_GET['pID']) ? $_GET['pID'] : '');
  if ( $pID == '' ) {
    tep_redirect(tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params()));
  } else {
    $product_query = tep_db_query("select pd.products_name, pd.products_description, p.products_id, p.products_model, p.products_image, p.products_date_added, p.products_last_modified from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . $pID . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'");
    $product = tep_db_fetch_array($product_query);
    $pInfo = new objectInfo($product);
  }
  
  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  
  $folder_states = (isset($_POST['fld_state']) ? $_POST['fld_state'] : array());
  foreach ($folder_states as $fld_id => $state) {
    $_SESSION['folder_content_state'][$fld_id] = $state;
  }
  
  switch ( $action ) {
    case 'update_files' :
      $show_array = $_POST['show'];
      $qualify_array = $_POST['qualify'];
      
      $files = array();   
      if (isset($show_array)) {
        foreach ($show_array as $file_id => $value) {
          $files[$file_id] = $file_id;
        }
      }
      if (isset($qualify_array)) {
        foreach ($qualify_array as $file_id => $value) {
          $files[$file_id] = $file_id;
        }
      }
      ksort($files);
            
      $product_id = $_POST['product_id'];
      tep_db_query("delete from " . TABLE_LIBRARY_PRODUCTS . " where products_id  = '" . $product_id . "'");
      
      foreach ($files as $file_id) {
        $show_value = isset($show_array[$file_id]) ? $show_array[$file_id] : 0;
        $qualify_value = isset($qualify_array[$file_id]) ? $qualify_array[$file_id] : 0;
        $sql_query = ("INSERT INTO " . TABLE_LIBRARY_PRODUCTS . "
                       SET products_id = '" . $product_id . "',
                           library_type = 'f',
                           library_id = '" . $file_id . "',
                           download_show = '" . $show_value . "',
                           purchase_required = '" . $qualify_value . "'");
        tep_db_query ($sql_query);
      }
      break;
  }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php
if ($is_62) {
    echo '<script language="javascript" src="includes/menu.js"></script>' . "\n";
} else {
    echo '<script type="text/javascript" src="includes/prototype.js"></script>' . "\n";
    echo '<!--[if IE]>' . "\n";
    echo '<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">' . "\n";
    echo '<![endif]-->' . "\n";
}
?>
<script language="javascript" src="includes/general.js"></script>
<style type="text/css">
<!--
  .dataTableRow {
    background-color: rgb(240, 241, 241);
    color: #000000;
    }
  .dataTableRowHide {
    display: none;
    }
-->  
</style>
<script language="javascript"><!--
  function invertClassOn(prefix) {
    var updElm = document.getElementById(prefix);
    var els = document.getElementsByTagName('tr');
    for (var i = 0; i < els.length; i++) {
      var elm = els[i];
      if (elm.className == '') continue;
      if (elm.id == '') continue;
      if (elm.id.indexOf(prefix) != -1) {
        if (elm.className == 'dataTableRow') {
          elm.className = 'dataTableRowHide';
          updElm.value = '0';
        } else if (elm.className == 'dataTableRowHide') {
          elm.className = 'dataTableRow';
          updElm.value = '1';
        }
      }
    }			
  }
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<?php $padding = ($is_62 == true) ? 2 : 0; ?>
<table border="0" width="100%" cellspacing="<?php echo $padding; ?>" cellpadding="<?php echo $padding; ?>" class="body-table">
  <tr>
    <!-- left_navigation //-->
    <?php 
    if ($is_62 == true) echo '<td width="' . BOX_WIDTH . '" valign="top"><table border="0" width="' . BOX_WIDTH . '" cellspacing="1" cellpadding="1" class="columnLeft">' . "\n";
    require(DIR_WS_INCLUDES . 'column_left.php'); 
    if ($is_62 == true) echo '</table></td>' . "\n";
    ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td width="100%" valign="top">
       <table border="0" cellspacing="0" cellpadding="2" width="600px">
        <tr>
          <td width="100%">
             <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
              </tr>
            </table>
           </td>
        </tr>
        <tr>
          <td width="100%" align="center"><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td><?php echo tep_info_image($pInfo->products_image, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT); ?></td>            
              <td align="left"><b><?php echo $pInfo->products_name; ?></b>:&nbsp;<div class="smalltext"><b><?php echo $pInfo->products_model; ?></b></div></td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td width="100%">
<?php
    function setParentFileFlag($fld_id) {
    // this routine is used to float the files flag up to the top of the folder tree
      global $fld_data;
      $fld_data[$fld_id]['files'] = 1;
      if ($fld_data[$fld_id]['parent'] != 0)  setParentFileFlag($fld_data[$fld_id]['parent']);
    }
    
    function checkForSubs($sub_id, $lvl) {
      $current_lvl = $lvl + 1;
      $max_lvl = $current_lvl;
      if ($fld_data[$sub_id]['subs'] == 1) {
        foreach ($fld_data[$sub_id]['subs_array'] as $nxt_id) {
          $lvl = checkForSubs($nxt_id, $current_lvl);
        }
        if ($max_lvl < $lvl)  $max_lvl = $lvl;
      }
      return $max_lvl;
    }
    
    $fld_data = array();
    $fld_root = array();
    $fil_root = array();
    $fil_data = array();
    $current_settings = array();
    
    
    $sql_query = "SELECT f.folders_id, fd.folders_name, f.folders_parent_id
                  FROM " . TABLE_LIBRARY_FOLDERS . " f,
                       " . TABLE_LIBRARY_FOLDERS_DESCRIPTION . " fd
                  WHERE f.folders_id = fd.folders_id
                    and fd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                  ORDER BY f.folders_sort_order, fd.folders_name";
    $folders_query = tep_db_query($sql_query);
    while($folders = tep_db_fetch_array($folders_query)) {
      $fld_data[$folders['folders_id']] = array('name' => $folders['folders_name'],
                                                'parent' => $folders['folders_parent_id'],
                                                'subs' => 0,
                                                'subs_array' => array(),
                                                'files' => 0,
                                                'files_array' => array());
    }
    foreach ($fld_data as $fld_id => $fld_data_array) {
      if (!isset($_SESSION['folder_content_state'][$fld_id]))  $_SESSION['folder_content_state'][$fld_id] = 0;
      if ($fld_data_array['parent'] == 0) {
        $fld_root[] = $fld_id;
      } else {
        $fld_data[$fld_data_array['parent']]['subs'] = 1;
        $fld_data[$fld_data_array['parent']]['subs_array'][] = $fld_id; 
      }
    }
    
    
    $sql_query = "SELECT f.files_id, f.files_name, fd.files_descriptive_name, fi.icon_small, ff.folders_id, f.file_availability
                  FROM " . TABLE_LIBRARY_FILES . " f
                  LEFT JOIN " . TABLE_FILE_ICONS . " fi on fi.icon_id = f.files_icon,
                       " . TABLE_LIBRARY_FILES_DESCRIPTION . " fd,
                       " . TABLE_LIBRARY_FILES_TO_FOLDERS . " ff
                  WHERE f.files_status = '1'
                    and f.files_product_display = '1'
                    and f.files_id = ff.files_id
                    and f.files_id = fd.files_id
                    and fd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                  ORDER BY fd.files_descriptive_name";
    $files_query = tep_db_query($sql_query);
    while ($files = tep_db_fetch_array($files_query)) {
      $fil_data[$files['files_id']] = array('name' => $files['files_name'],
                                            'descriptive_name' => $files['files_descriptive_name'],
                                            'icon_small' => $files['icon_small'],
                                            'availability' => $files['file_availability']);
      if ($files['folders_id'] == 0) {
        $fil_root[] = $files['files_id'];
      } else {
        if (isset($fld_data[$files['folders_id']] )) {
          $fld_data[$files['folders_id']]['files'] = 1;
          $fld_data[$files['folders_id']]['files_array'][] = $files['files_id'];
          setParentFileFlag($fld_data[$files['folders_id']]['parent']);
        } // note, if the folder does not exist, the file is ignored
      }
    }
    
    
    // loop thru and remove all folders that have no files in them
    foreach ($fld_data as $fld_id => $fld_data_array) {
      if ($fld_data_array['files'] == 0 ) {
        if ($fld_data_array['parent'] == 0 ) {
          $key = array_search($fld_id, $fld_root);
          unset($fld_root[$key]);
        } else {
          if (isset($fld_data[$fld_data_array['parent']])) {
            $key = array_search($fld_id, $fld_data[$fld_data_array['parent']]['subs_array']);
            unset($fld_data[$fld_data_array['parent']]['subs_array'][$key]);
            if (count($fld_data[$fld_data_array['parent']]['subs_array']) == 0){
              $fld_data[$fld_data_array['parent']]['subs'] = 0;
            }
          }
        }
        unset($fld_data[$fld_id]);
      }
    }
    
    
    $max_lvl = 1;
    foreach ($fld_root as $fld_id) {
      if ($fld_data[$fld_id]['subs'] == 1) {
        foreach ($fld_data[$fld_id]['subs_array'] as $sub_id) {
          $lvl = checkForSubs($sub_id, $lvl);
        }
        if ($max_lvl < $lvl)  $max_lvl = $lvl;
      }
    }
    $max_lvl++; // this is becasue the files are presented indented by one additional column
    
    $sql_query = "SELECT library_id, purchase_required, download_show
                  FROM " . TABLE_LIBRARY_PRODUCTS . "
                  WHERE products_id = " . $pInfo->products_id . "
                    and library_type = 'f'
                  ORDER BY library_id";
    $settings_query = tep_db_query($sql_query);
    while($settings = tep_db_fetch_array($settings_query)) {
      $current_settings[$settings['library_id']] = array('show' => $settings['download_show'],
                                                        'qualify' => $settings['purchase_required']);
    }
    
    
            echo tep_draw_form('file_product', FILENAME_LIBRARY_PRODUCT, tep_get_all_get_params() . '&action=update_files') . tep_draw_hidden_field('product_id', $pInfo->products_id) . "\n";
?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">    
              <thead>
                <tr class="dataTableHeadingRow">
                  <td class="dataTableHeadingContent" align="center" width="10%" valign="bottom"><?php echo TABLE_HEADING_SELECT; ?>&nbsp;</td>
                  <td class="dataTableHeadingContent" align="center" width="12%" valign="bottom"><?php echo TABLE_REQUIRE; ?></td>
                  <td class="dataTableHeadingContent" align="left" width="78%" valign="bottom" colspan="<?php echo $max_lvl + 2; ?>"><?php echo TABLE_HEADING_FILES; ?></td>
                </tr>
              </thead>
              <tbody>
<?php
		function get_appends($level) {
			global $max_lvl;
			$append = '';
			for($i=$max_lvl;$i>$level;$i--) {
				$append .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			}
			return $append;
		}
    function displayFolders($fld_id, $lvl) {
      global $max_lvl, $fld_data;
      if ($lvl == 1) {
        echo '                <tr class="dataTableRow">' . "\n";
      } else {
        $class = $_SESSION['folder_content_state'][$fld_id] == 1 ? 'dataTableRow' : 'dataTableRowHide';
        $id = 'fld' . $fld_data[$fld_id]['parent'] . '_fld_' . $fld_id;
        echo '                <tr class="' . $class . '" id="' . $id . '">' . "\n";
      }
      $onclick = ' onclick="invertClassOn(\'fld' . $fld_id . '_\');"';
      echo '                  <td class="dataTableContent" align="center" valign="bottom">&nbsp;</td>' . "\n";
      echo '                  <td class="dataTableContent" align="center" valign="bottom">&nbsp;</td>' . "\n";
      for ($i = 0, $n = $lvl -1; $i < $n; $i++) {
        echo '                  <td class="dataTableContent">&nbsp;</td>' . "\n";
      }
      echo '                  <td class="dataTableContent" align="center" valign="bottom">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER, '', '' , $onclick . ' style="cursor: pointer;"') . '</td>' . "\n";
			$append = '';
			for ($i=0;$i<$max_lvl - $lvl + 1;$i++) {
				echo '                  <td class="dataTableContent" align="center" valign="bottom">&nbsp;</td>' . "\n";
			}
      echo '                  <td class="dataTableContent" align="left" valign="bottom">' . get_appends($max_lvl - $lvl) . $fld_data[$fld_id]['name'] . '</td>' . "\n";
      echo '                </tr>' . "\n";
      foreach ($fld_data[$fld_id]['subs_array'] as $nxt_id) {
        displayFolders($nxt_id, $lvl + 1);
      }
      displayFiles($fld_id, $lvl);  // display this folders files
    }
    function displayFiles($fld_id, $lvl) {
      global $max_lvl, $fld_data, $fil_data, $current_settings;
      $lvl = $lvl + 1;  // adjust the file level so they show under their foilder
      foreach ($fld_data[$fld_id]['files_array'] as $fil_id) {
        $img_file = $fil_data[$fil_id]['icon_small'] != '' ? '../images/file_icons/' . $fil_data[$fil_id]['icon_small'] : DIR_WS_ICONS . 'file.gif';
        $qualify_box = $fil_data[$fil_id]['availability'] == 2 ? tep_draw_checkbox_field('qualify[' . $fil_id . ']', '1', false, $current_settings[$fil_id]['qualify']) : '&nbsp;';
        $show_box = tep_draw_checkbox_field('show[' . $fil_id . ']', '1', false, $current_settings[$fil_id]['show']);
        $class = $_SESSION['folder_content_state'][$fld_id] == 1 ? 'dataTableRow' : 'dataTableRowHide';
        $id = 'fld' . $fld_id . '_fil_' . $fil_id;
        
        echo '                <tr class="' . $class . '" id="' . $id . '">' . "\n";
        echo '                  <td class="dataTableContent" align="center" valign="bottom">' . $show_box . '</td>' . "\n";
        echo '                  <td class="dataTableContent" align="center" valign="bottom">' . $qualify_box . '</td>' . "\n";
        for ($i = 0, $n = $lvl -1; $i < $n; $i++) {
          echo '                  <td class="dataTableContent">&nbsp;</td>' . "\n";
        }
        echo '                  <td class="dataTableContent" align="center" valign="bottom">' . tep_image($img_file, ICON_FILE) . '</td>' . "\n";
			for ($i=0;$i<$max_lvl - $lvl + 1;$i++) {
				echo '                  <td class="dataTableContent" align="center" valign="bottom">&nbsp;</td>' . "\n";
			}
        echo '                  <td class="dataTableContent" align="left" valign="bottom">' . get_appends($max_lvl - $lvl) . $fil_data[$fil_id]['descriptive_name'] . '</td>' . "\n";
        echo '                </tr>' . "\n";
      }
    }
    
    foreach ($fld_root as $fld_id) {
      displayFolders($fld_id, 1);
    }
?>
<!-- ////////////////// GSTART -->
<?php
$sql_file=("select f.files_id, f.files_name, fd.files_descriptive_name, f.files_status, f.files_date_added, f.file_date_created, f.files_last_modified, f.file_availability, f.files_general_display, f.files_product_display, fd.files_description, fi.icon_small from " . TABLE_LIBRARY_FILES . " f, " . TABLE_LIBRARY_FILES_DESCRIPTION . " fd, " . TABLE_LIBRARY_FILES_TO_FOLDERS . " ff , ".TABLE_FILE_ICONS." fi where ff.folders_id = '" . $fPath . "' and f.files_id = ff.files_id and f.files_id = fd.files_id and fi.icon_id = f.files_icon and fd.language_id = '" . (int)$_SESSION['languages_id'] . "'" . $file_orderby);
$files_query = tep_db_query($sql_file);
while ($files = tep_db_fetch_array($files_query)) {

  $img_file = $files['icon_small'] != '' ? '../images/file_icons/' . $files['icon_small'] : DIR_WS_ICONS . 'file.gif';
  $qualify_box = tep_draw_checkbox_field('qualify[' . $files['files_id'] . ']', '1', false, $current_settings[$files['files_id']]['qualify']) ;
  $show_box = tep_draw_checkbox_field('show[' . $files['files_id'] . ']', '1', false, $current_settings[$files['files_id']]['show']);
  $class = 'dataTableRow';
  $id = 'fld' . $files['files_id'] . '_fil_' . $files['files_id'];
        
  echo '                <tr class="' . $class . '" id="' . $id . '">' . "\n";
        echo '                  <td class="dataTableContent" align="center" valign="bottom">' . $show_box . '</td>' . "\n";
        echo '                  <td class="dataTableContent" align="center" valign="bottom">' . $qualify_box . '</td>' . "\n";
        
        echo '                  <td class="dataTableContent" align="center" valign="bottom">' . tep_image($img_file, ICON_FILE) . '</td>' . "\n";
        echo '                  <td class="dataTableContent">&nbsp;</td>' . "\n";
        echo '                  <td class="dataTableContent" align="center" valign="bottom">&nbsp;</td>' . "\n";
        echo '                  <td class="dataTableContent" align="left" valign="bottom">' . $files['files_descriptive_name'] . '</td>' . "\n";
        echo '                </tr>' . "\n";

}

?>
<!-- ///////////////// GEND -->
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="<?php echo $max_lvl + 4; ?>"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td align="center" colspan="<?php echo $max_lvl + 4; ?>"><table border="0" cellspacing="1" cellpadding="1" width="100%">
                    <tr>
<?php
    echo '                      <td>';
    foreach ($fld_data as $fld_id => $fld_data_array) {
      echo '<input type="hidden" name="fld_state[' . $fld_id . ']" id="fld' . $fld_id . '_" value="' . $_SESSION['folder_content_state'][$fld_id] . '">';
    }
    echo '</td>';
    echo '<td align="right"><a href="#" onClick="history.go(-1)">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;&nbsp;&nbsp;' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;&nbsp;</td>';
    echo '<td valign="top" algn="left"><a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_all_get_params()) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></td>';
?>
                    </tr>
                  </table></td>
                </tr>
              </tfoot>
            </table></form>
          </td>
        </tr>
      </table>
    </td>
    <!-- body_text_eof //-->
  </tr>
</table>
</div> 
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
