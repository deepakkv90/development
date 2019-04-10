<?php
/*
  $Id: fdm_library_folders_edit.php,v 1.0.0.0 2006/10/12 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  $is_62 = (defined('PROJECT_VERSION') && ereg('6.2', PROJECT_VERSION)) ? true : false;
  $languages = tep_get_languages();
  
  $action = (isset($_POST['action']) ? $_POST['action'] : '');
  $fPath = (isset($_POST['fPath']) ? $_POST['fPath'] : '0');
  
  $return_to = (isset($_POST['return']) ? $_POST['return'] : '');
  if ( !$return_to <> '' ) $return_to = $REQUEST_URI;
  
  $folder_id = '';
  if (isset($_POST['folder_id'])) {
    $folder_id = $_POST['folder_id'];
  }elseif (isset($_GET['fldID'])) {
    $folder_id = $_GET['fldID'];
  }

  $dummy_folder = array('folders_id' => '',
                      'folders_parent_id' => '0',
                      'folders_sort_order' => '0',
                      'folders_date_added' => '',
                      'folders_name' => '',
                      'folders_heading_title' => '',
                      'folders_description' => '',
                      'folders_head_title_tag' => '',
                      'folders_head_desc_tag' => '',
                      'folders_head_keywords_tag' => '');
  if ( $folder_id <> '' ) {
    $folders_query = tep_db_query("select f.folders_id,f.folders_image,f.folders_parent_id, f.folders_sort_order, f.folders_date_added, fd.folders_name, fd.folders_heading_title, fd.folders_description, fd.folders_head_title_tag, fd.folders_head_desc_tag, fd.folders_head_keywords_tag from " . TABLE_LIBRARY_FOLDERS . " f, " . TABLE_LIBRARY_FOLDERS_DESCRIPTION . " fd where f.folders_id = '" . $folder_id . "' and f.folders_id = fd.folders_id and fd.language_id = '" . (int)$_SESSION['languages_id'] . "'");
    if ( $folders = tep_db_fetch_array($folders_query) ) {
      $fInfo = new objectInfo($folders);
    } else {
      $fInfo = new objectInfo($dummy_folder);
      $fInfo->folders_name = $folder_id;
    }
  } else {
    $fInfo = new objectInfo($dummy_folder);
  }
  
  if ( $action  == 'save' ) {
    if ( $fInfo->folders_id == '' ) {
    if ($uploaded_file = new upload('new_file', DIR_FS_CATALOG_IMAGES ) )  {
   $sql= ("Insert into " . TABLE_LIBRARY_FOLDERS . " set folders_image = '" . $uploaded_file->filename . "'");
   tep_db_query($sql);
   }
   tep_db_query("insert into " . TABLE_LIBRARY_FOLDERS . " set folders_parent_id='" . (int)$_POST['folder_parent_id'] . "', folders_sort_order='" . $_POST['folder_sort_order'] . "', folders_date_added=now()");
     $insert_id = tep_db_insert_id();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $language_id = $languages[$i]['id'];
        tep_db_query("insert into " . TABLE_LIBRARY_FOLDERS_DESCRIPTION . " set folders_id='" . $insert_id . "', language_id='" . $language_id .
                     "', folders_name='" . tep_db_prepare_input(tep_db_encoder($_POST['folder_name'][$language_id])) .
                     "', folders_heading_title='" . tep_db_prepare_input(tep_db_encoder($_POST['folder_heading_title'][$language_id])) .
                     "', folders_description='" . tep_db_prepare_input(tep_db_encoder($_POST['folder_description'][$language_id])) .
                     "', folders_head_title_tag='" . tep_db_prepare_input(tep_db_encoder($_POST['folder_meta_title'][$language_id])) .
                     "', folders_head_desc_tag='" . tep_db_prepare_input(tep_db_encoder($_POST['folder_meta_desc'][$language_id])) .
                     "', folders_head_keywords_tag='" . tep_db_prepare_input(tep_db_encoder($_POST['folder_meta_keywords'][$language_id])) .
                     "'");
      }
      tep_redirect($return_to);
    } else {
  
   if (isset($_POST['del_image']) && $_POST['del_image'] == 'on') {     
     @unlink(HTTP_SERVER.DIR_WS_CATALOG_IMAGES.$fInfo->folders_image);
     $sql= ("update " . TABLE_LIBRARY_FOLDERS . " set folders_image = '' where folders_id='" . $fInfo->folders_id . "'");
      //echo $sql;
      tep_db_query($sql);
   }

   if ( $uploaded_file = new upload('new_file', DIR_FS_CATALOG_IMAGES ) ) {
   //echo "**".$uploaded_file->filename; 
   if($uploaded_file->filename)  {
   $sql= ("update " . TABLE_LIBRARY_FOLDERS . " set folders_image = '" . $uploaded_file->filename . "' where folders_id='" . $fInfo->folders_id . "'");
   // echo $sql;
  //exit;
   tep_db_query($sql);
    }
   }
      $fullpath = DIR_FS_CATALOG . LIBRARY_DIRECTORY . $fInfo->folders_name;
  
    $crc="";
      tep_db_query("update " . TABLE_LIBRARY_FOLDERS . " set  folders_sort_order ='".$_POST['folder_sort_order']."',folders_last_modified=now() where folders_id ='" . $fInfo->folders_id . "'");
      tep_db_query("delete from " . TABLE_LIBRARY_FOLDERS_DESCRIPTION . " where folders_id ='" . $fInfo->folders_id . "'");
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $language_id = $languages[$i]['id'];
        tep_db_query("insert into " . TABLE_LIBRARY_FOLDERS_DESCRIPTION . " set folders_id='" . $fInfo->folders_id . "', language_id='" . $language_id .
                     "', folders_name='" . tep_db_prepare_input(tep_db_encoder($_POST['folder_name'][$language_id])) .
                     "', folders_heading_title='" . tep_db_prepare_input(tep_db_encoder($_POST['folder_heading_title'][$language_id])) .
                     "', folders_description='" . tep_db_prepare_input(tep_db_encoder($_POST['folder_description'][$language_id])) .
                     "', folders_head_title_tag='" . tep_db_prepare_input(tep_db_encoder($_POST['folder_meta_title'][$language_id])) .
                     "', folders_head_desc_tag='" . tep_db_prepare_input(tep_db_encoder($_POST['folder_meta_desc'][$language_id])) .
                     "', folders_head_keywords_tag='" . tep_db_prepare_input(tep_db_encoder($_POST['folder_meta_keywords'][$language_id])) .
                     "'");
      }
      tep_redirect($return_to);
    }
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
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=650,height=500,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
<!-- Tabs code -->
<script type="text/javascript" src="includes/javascript/tabpane/local/webfxlayout.js"></script>
<link type="text/css" rel="stylesheet" href="includes/javascript/tabpane/tab.webfx.css">
<style type="text/css">
.dynamic-tab-pane-control h2 {
  text-align: center;
  width:    auto;
}

.dynamic-tab-pane-control h2 a {
  display:  inline;
  width:    auto;
}

.dynamic-tab-pane-control a:hover {
  background: transparent;
}
</style>
<script type="text/javascript" src="includes/javascript/tabpane/tabpane.js"></script>
<!-- End Tabs -->
</script>
<?php
// Editor functions
include('includes/javascript/editor.php');
echo tep_load_html_editor();
for ($i=0; $i < sizeof($languages); $i++) {
   $folder_description .= 'folder_description[' . $languages[$i]['id'] . '],'; 
} 
tep_insert_html_editor($folder_description);
// editor functions
?>
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
    <td width="100%" valign="top"><?php echo tep_draw_form('folder_update', FILENAME_LIBRARY_FOLDERS_EDIT,tep_get_all_get_params(array('action')).'fPath=' . (int)$fPath,'post','enctype="multipart/form-data"') . tep_draw_hidden_field('action', 'save') . tep_draw_hidden_field('return', $return_to).tep_draw_hidden_field('folder_parent_id', (int)$fPath); ?>
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
              <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
              <td align="right"><?php echo '<a href="#" onClick="history.go(-1)">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?>&nbsp;<?php echo tep_image_submit('button_save.gif', IMAGE_SAVE); ?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td>
                <table width="100%" cellpadding="1"  cellspacing="1" border="0">
                  <tr>
                    <td width="40%">
                      <b><?php echo TEXT_FOLDER_IMAGE;  ?></b>&nbsp;<?php echo tep_draw_file_field('new_file');?>
                    </td>
                    <td align="right"><?php echo ($fInfo->folders_image != '') ? tep_image(HTTP_SERVER.DIR_WS_CATALOG_IMAGES.$fInfo->folders_image) : ''; ?></td>
                    <td><?php echo ($fInfo->folders_image != '') ? tep_draw_checkbox_field('del_image') .'&nbsp;&nbsp;' . TEXT_FOLDER_DELETE_IMAGE : ''; ?></td>
                  </tr>
                  <tr>
                    <td width="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo TEXT_FOLDER_SORT_ORDER; ?></b>&nbsp;<?php echo tep_draw_input_field('folder_sort_order', $fInfo->folders_sort_order, 'size="3" maxlength="3"'); ?></td>
                  </tr>
                </table>
              </td>
              <td align="left">&nbsp;</td>
              </tr>
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td>
            </tr>
            <!-- Tabs mod start //-->
            <tr>
             <td colspan="2" valign="top" width="100%">
                <div class="tab-pane" id="tabPane1">
                  <script type="text/javascript">
                  tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );
                  </script>
                  <?php
                  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                    $language_id = $languages[$i]['id'];
                    $language_name = $languages[$i]['name'];
                    ?>
                    <div class="tab-page" id="<?php echo $language_name;?>">
                    <h2 class="tab"><nobr><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'],'align="middle" style="height:16px; width:30px;"') . '&nbsp;' .$languages[$i]['name'];?></nobr></h2>
                    <script type="text/javascript">tp1.addTabPage( document.getElementById( "<?php echo $languages[$i]['name'];?>" ) );</script>
                    <table width="100%" border="0" cellspacing="3" cellpadding="2" summary="Product Description">
                      <tr>
                        <td><b><?php echo TEXT_FOLDER_NAME; ?></b> <?php echo tep_draw_input_field('folder_name[' . $language_id . ']', $fInfo->folders_name, 'size="60" maxlength="32"'); ?></td>
                      </tr>
                      <tr>
                        <td><b><?php echo TEXT_FOLDER_HEADING_TITLE; ?></b> <?php echo tep_draw_input_field('folder_heading_title[' . $language_id . ']', $fInfo->folders_heading_title, 'size="60" maxlength="64"'); ?></td>
                      </tr>
                      <tr>
                        <td valign="top"><b><?php echo TEXT_FOLDER_DESCRIPTION; ?></b><br><?php echo tep_draw_textarea_field('folder_description[' . $language_id . ']', 'soft', '70', '15', $fInfo->folders_description); ?></td>
                      </tr>
                      <tr>
                        <td><fieldset><legend>Meta Tag Information&nbsp;&nbsp;&nbsp;<?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']);?></legend>
                          <table width="100%" border="0" cellspacing="4" cellpadding="4" summary="Meta Tag Information">
                            <tr>
                              <td colspan="2"><b><?php echo TEXT_FOLDER_META_TITLE; ?></b> <?php echo tep_draw_input_field('folder_meta_title[' . $language_id . ']', $fInfo->folders_head_title_tag,'style="width:75%"'); ?></td>
                            </tr>
                            <tr>
                              <td><b><?php echo TEXT_FOLDER_META_DESCRIPTION; ?></b><br><?php echo tep_draw_textarea_field('folder_meta_desc[' . $language_id . ']', 'soft', '60', '5', $fInfo->folders_head_desc_tag); ?></td>
                              <td><b><?php echo TEXT_FOLDER_META_KEYWORDS; ?></b><br><?php echo tep_draw_textarea_field('folder_meta_keywords[' . $language_id . ']', 'soft', '60', '5', $fInfo->folders_head_keywords_tag); ?></td>
                            </tr>
                          </table>
                        </fieldset></td>
                      </tr>
                    </table>
                    </div>
                    <?php
                  } // lang loop end
                  ?>
                </div>
                <script type="text/javascript">
                //<![CDATA[
                setupAllTabs();
                //]]>
                </script>
              </td>
            </tr>
            <!-- Tabs mod End //-->
            <?php
            echo tep_draw_hidden_field('folder_id', $fInfo->folders_id);
            ?>
          </table></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
        <tr>
          <td align="center"><?php echo tep_image_submit('button_save.gif', IMAGE_SAVE); ?></td>
        </tr>
      </table></form>
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