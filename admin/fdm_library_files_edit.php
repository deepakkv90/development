<?php
/*
  $Id: fdm_library_files_edit.php,v 1.0.0.0 2006/10/12 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_FUNCTIONS . FILENAME_FDM_FUNCTIONS);
  $is_62 = (defined('PROJECT_VERSION') && ereg('6.2', PROJECT_VERSION)) ? true : false;

  $languages = tep_get_languages();

  $action = '';
  if (isset($_POST['action'])) {
    $action = $_POST['action'];
  }elseif (isset($_GET['action'])) {
    $action = $_GET['action'];
  }

  $fPath = (isset($_POST['fPath']) ? $_POST['fPath'] : '0');

  $return_to = (isset($_POST['return']) ? $_POST['return'] : '');
  if (isset($_SESSION['return'])) {
    $return_to = $_SESSION['return'];
    unset($_SESSION['return']);
  }
  
  if ( !$return_to <> '' ) $return_to = $REQUEST_URI;

  if (isset($_GET['fID'])) {
    $file_id = (int)$_GET['fID'];
  }

  $dummy_file = array('files_id' => '',
                      'files_name' => '',
                      'files_descriptive_name' => '',
                      'files_status' => '1',
                      'files_general_display' => '1',
                      'files_product_display' => '1',
                      'files_date_added' => '',
                      'files_head_title_tag' => '',
                      'files_head_desc_tag' => '',
                      'files_head_keywords_tag' => '');
  
  switch ( $action ) {
    case 'update' :
      if ($uploaded_file = new upload('new_file', DIR_FS_CATALOG.LIBRARY_DIRECTORY)) {
        $new_file_name = $uploaded_file->filename;
        $fullpath = DIR_FS_CATALOG.LIBRARY_DIRECTORY.$new_file_name;
//        $file_string = file_get_contents( $fullpath );
        $md5 = md5( $fullpath . filesize($fullpath));
        if ($new_file_name != '') {
            tep_db_query("update " . TABLE_LIBRARY_FILES . " set files_name='" . tep_db_prepare_input(tep_db_encoder($new_file_name)) . "', files_status='" . $_POST['file_status'] . "', file_availability = '" . $_POST['file_availability'] . "', files_general_display='" . $_POST['file_general_display'] . "', files_product_display='" . $_POST['file_product_display'] . "', files_md5='" . $md5 . "', files_last_modified=now(), file_date_created = '" . tep_date_raw($_POST['date_created']) . "', files_icon = '" . (int)$_POST['files_icon'] . "', require_products_id = '" . $_POST['require_purchase_product'] . "' where files_id ='" . (int)$_POST['file_id'] . "'");
        } else {
            tep_db_query("update " . TABLE_LIBRARY_FILES . " set files_status='" . $_POST['file_status'] . "', files_general_display='" . $_POST['file_general_display'] . "', file_availability = '" . $_POST['file_availability'] . "', files_product_display='" . $_POST['file_product_display'] . "', files_last_modified=now(), file_date_created = '" . tep_date_raw($_POST['date_created']) . "', files_icon = '" . (int)$_POST['files_icon'] . "', require_products_id = '" . $_POST['require_purchase_product'] . "' where files_id ='" . (int)$_POST['file_id'] . "'");
        }
    
    }
    tep_db_query("delete from " . TABLE_LIBRARY_FILES_DESCRIPTION . " where files_id ='" . (int)$_POST['file_id'] . "'");
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      $language_id = $languages[$i]['id'];
        $sql=("insert into " . TABLE_LIBRARY_FILES_DESCRIPTION . " set files_id='" . (int)$_POST['file_id'] . "', language_id='" . $language_id .
                   "', files_descriptive_name='" . tep_db_prepare_input(tep_db_encoder($_POST['files_descriptive_name'][$language_id])) .
                   "', files_description='" . tep_db_prepare_input(tep_db_encoder($_POST['file_description'][$language_id])) .
                   "', files_head_title_tag='" . tep_db_prepare_input(tep_db_encoder($_POST['file_meta_title'][$language_id])) .
                   "', files_head_desc_tag='" . tep_db_prepare_input(tep_db_encoder($_POST['file_meta_desc'][$language_id])) .
                   "', files_head_keywords_tag='" . tep_db_prepare_input(tep_db_encoder($_POST['file_meta_keywords'][$language_id])) .
                   "'");
          tep_db_query($sql);           
    }
    if(isset($file_availability) && (isset($require_purchase_product))) {
      $sql_prod=(" Update  ".TABLE_LIBRARY_PRODUCTS ."  SET  products_id ='".$require_purchase_product."', purchase_required='1', login_required ='0',library_type='f' where  library_id ='" . (int)$_POST['file_id'] . "'");
      tep_db_query($sql_prod);
    }
    tep_redirect($return_to);
    break;

    case 'edit' :
      $files_query = tep_db_query("select f.files_id, f.files_name, fd.files_descriptive_name, f.files_status, f.files_general_display, f.files_icon, f.files_download, f.files_product_display, f.files_date_added, fd.files_head_title_tag, f.file_date_created, f.file_availability, fd.files_head_desc_tag, fd.files_head_keywords_tag,fd.files_description, fi.file_ext, fi.icon_small, f.require_products_id from " . TABLE_LIBRARY_FILES . " f, " . TABLE_FILE_ICONS . " fi," . TABLE_LIBRARY_FILES_DESCRIPTION . " fd where f.files_id = '" . $file_id . "' and f.files_id = fd.files_id and fi.icon_id = f.files_icon and fd.language_id = '" . $languages_id . "'");
      $files = tep_db_fetch_array($files_query);
      $fInfo = new objectInfo($files);
      break;

    case 'load' :
      $fInfo = new objectInfo($dummy_file);
      $fInfo->files_name = $_POST['file_name'];
      break;
    
    case 'loadsave' :
      $new_file_name = $_POST['file_name'];
      $fullpath=DIR_FS_CATALOG.LIBRARY_DIRECTORY.$new_file_name; 
//      $file_string = file_get_contents( $fullpath );
      $md5 = md5( $fullpath . filesize($fullpath));
      tep_db_query("insert into " . TABLE_LIBRARY_FILES . " set files_name='" . $new_file_name . "', files_status='" . $_POST['file_status'] . "', file_availability = '" . $_POST['file_availability'] . "', files_general_display='" . $_POST['file_general_display'] . "', files_icon = '" . $_POST['files_icon'] . "', files_product_display='" . $_POST['file_product_display'] . "', files_md5='" . $md5 . "',  file_date_created = '" . tep_date_raw($_POST['date_created']) . "'");
      $insert_id = tep_db_insert_id();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $uploaded_file = new upload('new_file', DIR_FS_CATALOG.LIBRARY_DIRECTORY);
        $language_id = $languages[$i]['id'];
        $sql=("insert into " . TABLE_LIBRARY_FILES_DESCRIPTION . " set files_id='" . $insert_id . "', language_id='" . $language_id .
                     "', files_descriptive_name='" .tep_db_prepare_input(tep_db_encoder($_POST['files_descriptive_name'][$language_id])) .
                     "', files_description='" . tep_db_prepare_input(tep_db_encoder($_POST['file_description'][$language_id])) .
                     "', files_head_title_tag='" . tep_db_prepare_input(tep_db_encoder($_POST['file_meta_title'][$language_id])) .
                     "', files_head_desc_tag='" . tep_db_prepare_input(tep_db_encoder($_POST['file_meta_desc'][$language_id])) .
                     "', files_head_keywords_tag='" . tep_db_prepare_input(tep_db_encoder($_POST['file_meta_keywords'][$language_id])) .
                     "'");
         
         tep_db_query($sql);
      }
      if(isset($file_availability) && (isset($require_purchase_product))) {
       $sql_prod=("Insert into  ".TABLE_LIBRARY_PRODUCTS ." SET library_id='".$insert_id."',products_id ='".$require_purchase_product."', purchase_required='1', login_required ='0',library_type='f'");
       tep_db_query($sql_prod);
      }
      tep_db_query("insert into " . TABLE_LIBRARY_FILES_TO_FOLDERS . " set files_id='" . $insert_id . "', folders_id='" . $_POST['file_folder'] . "'");
      tep_redirect($return_to);
      break;

  case 'save' :
      if ($uploaded_file = new upload('new_file', DIR_FS_CATALOG.LIBRARY_DIRECTORY)) {
      $new_file_name = $uploaded_file->filename;
      $fullpath = DIR_FS_CATALOG.LIBRARY_DIRECTORY.$new_file_name;
//      $file_string = file_get_contents($fullpath);
      $md5 = md5( $fullpath . filesize($fullpath));
      $sql=("insert into " . TABLE_LIBRARY_FILES . " set files_name='" . $new_file_name . "', files_status='" . $_POST['file_status'] . "', file_availability = '" . $_POST['file_availability'] . "', files_general_display='" . $_POST['file_general_display'] . "', files_icon = '" . $_POST['files_icon'] . "', files_product_display='" . $_POST['file_product_display'] . "', files_md5='" . $md5 . "', files_last_modified = '" . tep_date_raw($_POST['date_created']) . "', files_date_added = '" . tep_date_raw($_POST['date_created']) . "',  file_date_created = '" . tep_date_raw($_POST['date_created']) . "'"); 
      (tep_db_query($sql));
      $insert_id = tep_db_insert_id();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $language_id = $languages[$i]['id'];
           $sql = ( "insert into " . TABLE_LIBRARY_FILES_DESCRIPTION . " set files_id='" . $insert_id . "', language_id='" . $language_id .
                       "', files_descriptive_name='" . tep_db_prepare_input(tep_db_encoder($_POST['files_descriptive_name'][$language_id])) .
                       "', files_description='" . tep_db_prepare_input(tep_db_encoder($_POST['file_description'][$language_id])) .
                       "', files_head_title_tag='" . tep_db_prepare_input(tep_db_encoder($_POST['file_meta_title'][$language_id])) .
                       "', files_head_desc_tag='" . tep_db_prepare_input(tep_db_encoder($_POST['file_meta_desc'][$language_id])) .
                       "', files_head_keywords_tag='" . tep_db_prepare_input(tep_db_encoder($_POST['file_meta_keywords'][$language_id])) .
                       "'");  
          tep_db_query($sql);
      }
      if(isset($file_availability) && (isset($require_purchase_product))) {
       $sql_prod=("Insert into  ".TABLE_LIBRARY_PRODUCTS ." SET library_id='".$insert_id."',products_id ='".$require_purchase_product."', purchase_required='1', login_required ='0',library_type='f'");
       tep_db_query($sql_prod);
      }
      tep_db_query("insert into " . TABLE_LIBRARY_FILES_TO_FOLDERS . " set files_id='" . $insert_id . "', folders_id='" . $_POST['file_folder'] . "'");
      tep_redirect($return_to);
    }  // note: if the upload fails, treat it as a new file condition

  case 'new' :
       default : 
      $fInfo = new objectInfo($dummy_file);
  }
  
  // set the form action value
  if ( $fInfo->files_id == '' ) {
    if ( $fInfo->files_name == '' ) {
      $form_action = tep_draw_hidden_field('action', 'save');
    } else {
      $form_action = tep_draw_hidden_field('action', 'loadsave');
    }
  } else {
    $form_action = tep_draw_hidden_field('action', 'update');
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
<script language="JavaScript" src="includes/javascript/fdm_calendar.js"></script>
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=650,height=500,screenX=150,screenY=150,top=150,left=150')
}

function set_Img() {
  var w = document.file_update.files_icon.selectedIndex;
  document.file_update.icon_image.src = "../images/file_icons/" + document.file_update.files_icon.options[w].text;
}
//-->
</script>
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
<?php
// Editor functions
include('includes/javascript/editor.php');
echo tep_load_html_editor();
for ($i=0; $i < sizeof($languages); $i++) {
  $file_description .= 'file_description[' . $languages[$i]['id'] . '],'; 
} 
echo tep_insert_html_editor($file_description,'advanced','300');
// editor functions
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<div id="popupcalendar" class="text"></div>
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
      <?php echo tep_draw_form('file_update', FILENAME_LIBRARY_FILES_EDIT, '', 'post', 'enctype="multipart/form-data"') . $form_action . tep_draw_hidden_field('return', $return_to); ?>
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
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
        <tr>
          <td>
<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="350" valign="top"><table border="0" cellspacing="3" cellpadding="3" align="left">
            <?php
            if ( $action == 'load' ) {
              $folder_title = tep_draw_pull_down_menu('file_folder', tep_get_folders_tree(), $fPath);
            } else {
              if ($fPath == '0' || $fPath == '' ) {
                $folder_title = '<b>TOP</b>';
              } else {
                $folder_query = tep_db_query("select folders_name from " . TABLE_LIBRARY_FOLDERS_DESCRIPTION . " where language_id = '" . $languages_id . "' and folders_id = '" . $fPath . "'");
                $folder = tep_db_fetch_array($folder_query);
                $folder_title = '<b>' . $folder['folders_name'] . '</b>';
                echo  tep_draw_hidden_field('file_folder', $fPath);
              }
            }
            ?>
            <!-- tr>
              <td><?php echo TEXT_FILE_FOLDER; ?></td>
              <td><?php echo $folder_title; ?></td>
            </tr -->
            <?php
            if ($fInfo->files_name <> '') {
              $file_name_field = '<b>' . $fInfo->files_name . '</b>' . tep_draw_hidden_field('file_name', $fInfo->files_name) . '</td></tr>' . "\n" . '<tr><td>' . TEXT_FILE_UPDATE . '</td><td>';
            }
            $file_name_field .= tep_draw_file_field('new_file','') . tep_draw_hidden_field('file_id', $fInfo->files_id);
            ?>
            <tr>
              <td><?php echo TEXT_FILE_NAME; ?></td>
              <td><?php echo $file_name_field; ?></td>
            </tr>
            <tr>
              <td><?php echo TEXT_FILE_DATE_CREATED; ?></td>
              <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                      <?php echo tep_draw_input_field('date_created', tep_date_short($fInfo->file_date_created)); ?>
                    </td>
                    <td valign="middle" style="padding-left:4px">
                      <a href="javascript:cal6.popup();"><img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date"></a>
                    </td>
                    <td style="padding-left:4px">
                      <input type="button" name="today" value="Today" onClick="document.file_update.date_created.value = '<?php echo date('m/d/Y'); ?>';" />
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <?php 
            if ($fInfo->files_id != '') {
              ?>          
              <tr>
                <td><?php echo TEXT_FILE_SIZE; ?></td>
                <?php
                $files_name = DIR_FS_CATALOG . LIBRARY_DIRECTORY . $fInfo->files_name;
                if (file_exists($files_name)) {
                  $f_size = (int)@filesize($files_name);   
                  $human_readable_size = cre_resize_bytes($f_size);
                  $files_date =  date("m/d/Y", filectime($files_name));
                }else{
                  $human_readable_size = '';
                  $files_date = '';
                }
                ?>
              <td><?php echo $human_readable_size; ?></td>
              </tr>
              <tr>
                <td><?php echo TEXT_FILE_DATE; ?></td>
                <td><?php echo $files_date; ?></td>
              </tr>
              <tr>
                <td><?php echo TEXT_FILE_DOWNLOAD; ?></td>
                <td><?php echo $fInfo->files_download; ?></td>
              </tr>
              <?php
            } 
            ?></table></td><td><table border="0" cellspacing="3" cellpadding="3" align="left">
            <tr>
              <td><?php echo TEXT_FILE_ICON; ?></td>
              <td><?php echo tep_image(DIR_WS_CATALOG_IMAGES . 'file_icons/' . $fInfo->icon_small, '', '', '', 'id="icon_image"'); ?></td>
            </tr>
            <tr>
              <td><?php echo TEXT_FILE_ICON_CHANGE; ?></td>
              <td>
                <?php  
                $files_icon_query = tep_db_query("select * from " . TABLE_FILE_ICONS . " order by file_ext");
                while ($files_icon = tep_db_fetch_array($files_icon_query)) {
                  $file_icons[] = array('id' => $files_icon['icon_id'], 'text' => $files_icon['icon_small']);
                }
                if ( $action == 'load' ) {
                  $pos = strrpos($fInfo->files_name, '.') + 1;
                  $file_ext_name = substr($fInfo->files_name, $pos);
                  $files_icon_id = tep_db_fetch_array(tep_db_query("select icon_id from " . TABLE_FILE_ICONS . " where lower(file_ext) = '" . strtolower($file_ext_name) . "'"));
                  echo tep_draw_pull_down_menu('files_icon', $file_icons, $files_icon_id['icon_id'], 'onChange="javascript:set_Img()"');
                } else {
                  echo tep_draw_pull_down_menu('files_icon', $file_icons, $fInfo->files_icon, 'onChange="javascript:set_Img()"');
                }
                ?>
                </td>
            </tr>
            <?php
            switch ($fInfo->files_status) {
              case '0':
                $en_status = false; 
                $dis_status = true; 
                break;
              case '1':
              default:
                $en_status = true; 
                $dis_status = false;
            }
            ?>
            <tr>
              <td><?php echo TEXT_FILE_STATUS; ?></td>
              <td><?php echo tep_draw_radio_field('file_status', '1', $en_status) . '&nbsp;' . TEXT_FILE_ENABLED . '&nbsp;' . tep_draw_radio_field('file_status', '0', $dis_status) . '&nbsp;' . TEXT_FILE_DISABLED; ?></td>
            </tr>
            <?php
            switch ($fInfo->file_availability) {
              case '0':
                $en_status = true; 
                $dis_status = false; 
                $pur_status = false;
                break;
              case '1':
              default:
                $en_status = false; 
                $dis_status = true;
                $pur_status = false;
                break;
              case '2':
                $en_status = false; 
                $dis_status = false;
                $pur_status = true;
                break;
            }
            $file_list[] = array('id'=>'','text' => TEXT_FILE_CHOOSE_FILES);
            $sql_file=("Select lf.files_name,lf.files_id,lfd.files_descriptive_name from ".TABLE_LIBRARY_FILES." lf,".TABLE_LIBRARY_FILES_DESCRIPTION." lfd where lf.files_id=lfd.files_id and lfd.language_id ='".$languages_id."'"); 
            $file_list_query =tep_db_query($sql_file);
            while ($file_list_array =tep_db_fetch_array( $file_list_query)) {
              $file_list[] =array('id' => $file_list_array['files_id'] , 'text' => $file_list_array['files_descriptive_name']);
            }
            if ($pur_status) {
              $list_param = '';
            } else {
              $list_param = 'disabled="true"';
            }
            ?>
            <tr>
              <td><?php echo TEXT_FILE_AVAILABILITY; ?></td>
              <td><?php echo tep_draw_radio_field('file_availability', '0', $en_status, '', 'onClick="javascript:set_prod_list(0);"') . '&nbsp;' . TEXT_FILE_FREE . '&nbsp;&nbsp;&nbsp;&nbsp;' . tep_draw_radio_field('file_availability', '1', $dis_status, '', 'onClick="javascript:set_prod_list(0);"') . '&nbsp;' . TEXT_FILE_LOGIN . '&nbsp;&nbsp;' . tep_draw_radio_field('file_availability', '2', $pur_status, '', 'onClick="javascript:set_prod_list(1);"') . '&nbsp;' . TEXT_FILE_RESTRICTED; ?></td>
            </tr>
            <?php
            switch ($fInfo->files_general_display) {
              case '0':
                $en_status = false; 
                $dis_status = true; 
                break;
              case '1':
              default:
                $en_status = true; 
                $dis_status = false;
            }
            ?>
            <tr>
              <td><?php echo TEXT_FILE_GENERAL_DISPLAY; ?></td>
              <td><?php echo tep_draw_radio_field('file_general_display', '1', $en_status) . '&nbsp;' . TEXT_FILE_ENABLED . '&nbsp;' . tep_draw_radio_field('file_general_display', '0', $dis_status) . '&nbsp;' . TEXT_FILE_DISABLED; ?></td>
            </tr>
            <?php
            switch ($fInfo->files_product_display) {
              case '0':
                $en_status = false; 
                $dis_status = true; 
                break;
              case '1':
                default:
                $en_status = true; 
                $dis_status = false;
            }
            ?>
            <tr>
              <td><?php echo TEXT_FILE_PRODUCT_DISPLAY; ?></td>
              <td><?php echo tep_draw_radio_field('file_product_display', '1', $en_status) . '&nbsp;' . TEXT_FILE_ENABLED . '&nbsp;' . tep_draw_radio_field('file_product_display', '0', $dis_status) . '&nbsp;' . TEXT_FILE_DISABLED; ?></td>
            </tr>
</td>
  </tr>
</table>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
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
                      <td><b><?php echo TEXT_FILE_DESCRIPTIVE_NAME; ?></b>&nbsp;<?php echo tep_draw_input_field('files_descriptive_name[' . $language_id . ']', $fInfo->files_descriptive_name, 'size="60" maxlength="64"'); ?></td>
                    </tr>
                    <tr>
                      <td valign="top"><b><?php echo TEXT_FILE_DESCRIPTION; ?></b><br><?php echo tep_draw_textarea_field('file_description[' . $language_id . ']', 'soft', '40', '15', $fInfo->files_description,' style="width: 100%"'); ?></td>
                    </tr>
                    <tr>
                      <td><fieldset><legend>Meta Tag Information &nbsp;&nbsp;<?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']);?></legend>
                        <table width="100%" border="0" cellspacing="4" cellpadding="4" summary="Meta Tag Information">
                          <tr>
                            <td colspan="2"><b><?php echo TEXT_FILE_META_TITLE; ?></b> <?php echo tep_draw_input_field('file_meta_title[' . $language_id . ']', $fInfo->files_head_title_tag,'style="width:75%"'); ?></td>
                          </tr>
                          <tr>
                            <td><b><?php echo TEXT_FILE_META_DESCRIPTION; ?></b><br><?php echo tep_draw_textarea_field('file_meta_desc[' . $language_id . ']', 'soft', '60', '5', $fInfo->files_head_desc_tag); ?></td>
                            <td><b><?php echo TEXT_FILE_META_KEYWORDS; ?></b><br><?php echo tep_draw_textarea_field('file_meta_keywords[' . $language_id . ']', 'soft', '60', '5', $fInfo->files_head_keywords_tag); ?></td>
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
         </table></td>
       </tr>
       <tr>
         <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
       </tr>
       <tr>
         <td align="center"><?php echo tep_image_submit('button_save.gif', IMAGE_SAVE); ?></td>
       </tr>
     </table></form>
      <script language="javascript">
       var cal6 = new calendar2(document.forms['file_update'].elements['date_created']);
       cal6.year_scroll = false;
       cal6.time_comp = false;
      </script>
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
<script language="javascript" type='text/javascript'><!--
function set_prod_list(i) {
  if (i == 1) {
    document.file_update.require_purchase_product.disabled = false;
  } else {
    document.file_update.require_purchase_product.selectedIndex = '';
    document.file_update.require_purchase_product.disabled = true;
  }
}
//-->
</script>