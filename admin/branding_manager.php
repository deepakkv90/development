<?php
/*
  $Id: logo_manager.php,v 1.1.1.1 2007/07/07 23:38:34 wa4u Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
      if(!function_exists('cre_db_update')){
    function cre_db_update($table, $data, $link = 'db_link') {
    reset($data);
     $query = 'REPLACE INTO ' . $table . ' set ';
      while (list($columns, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= $columns . ' = now(), ';
            break;
          case 'null':
            $query .= $columns .= ' = null, ';
            break;
          default:
            $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) ;

    return tep_db_query($query, $link);
  }
}

  //destination for uploaded logos
  define('DIR_FS_LOGO',DIR_FS_CATALOG_IMAGES . 'logo/');
  define('DIR_WS_LOGO',HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . 'logo/');
    // get languages
  $languages = tep_get_languages();
  
    if (isset($_GET['action'])) {
    $action = $_GET['action'] ;
    }else if (isset($_POST['action'])){
    $action = $_POST['action'] ;
    } else {
    $action = '' ;
   }

   if (isset($action) && $action == 'upload') {
       
       for ($i=0; $i<sizeof($languages); $i++) {
           $store_brand_telephone = (isset($_POST['store_brand_telephone' . $languages[$i]['id']]) ? $_POST['store_brand_telephone' . $languages[$i]['id']] : '');
           $store_brand_fax = (isset($_POST['store_brand_fax' . $languages[$i]['id']]) ? $_POST['store_brand_fax' . $languages[$i]['id']] : '');
           $store_brand_homepage = (isset($_POST['store_brand_homepage' . $languages[$i]['id']]) ? $_POST['store_brand_homepage' . $languages[$i]['id']] : '');
           $store_brand_image = (isset($_POST['store_brand_image' . $languages[$i]['id']]) ? $_POST['store_brand_image' . $languages[$i]['id']] : '');
           $store_brand_name = (isset($_POST['store_brand_name' . $languages[$i]['id']]) ? $_POST['store_brand_name' . $languages[$i]['id']] : '');
           $store_brand_slogan = (isset($_POST['store_brand_slogan' . $languages[$i]['id']]) ? $_POST['store_brand_slogan' . $languages[$i]['id']] : '');
           $store_brand_support_email = (isset($_POST['store_brand_support_email' . $languages[$i]['id']]) ? $_POST['store_brand_support_email' . $languages[$i]['id']] : '');
           $store_brand_support_phone = (isset($_POST['store_brand_support_phone' . $languages[$i]['id']]) ? $_POST['store_brand_support_phone' . $languages[$i]['id']] : '');
           $store_brand_image_existing = (isset($_POST['store_brand_image_existing' . $languages[$i]['id']]) ? $_POST['store_brand_image_existing' . $languages[$i]['id']] : '');
           $delete_image = (isset($_POST['delete_image' . $languages[$i]['id']]) ? $_POST['delete_image' . $languages[$i]['id']] : '');
           
           //delete image
           $deleted_image = false;
           if($delete_image !='' && $store_brand_image_existing !='' || $store_brand_image != ''){
             $image_query = tep_db_query("SELECT store_brand_image FROM " . TABLE_BRANDING_DESCRIPTION . " WHERE store_brand_image = '" . $store_brand_image_existing . "' AND language_id <> '" . $languages[$i]['id'] . "'");
             if (tep_db_num_rows($image_query) == 0) {
               unlink(DIR_FS_LOGO . $store_brand_image_existing);
             }
               $delete_logo = "Update " . TABLE_BRANDING_DESCRIPTION . " set store_brand_image = '' where language_id = '" . $languages[$i]['id'] . "'";
               tep_db_query($delete_logo);
               $deleted_image = true;
           }
           
           //upload image        
               $store_brand_image_tmp = '';
               $store_brand_image_name = '';
               $store_brand_image_tmp = new upload('store_brand_image' . $languages[$i]['id']);
               $store_brand_image_tmp->set_destination(DIR_FS_LOGO);
               #$store_brand_image_tmp->set_extensions('gif','jpg','png');
               if ($store_brand_image_tmp->parse() && $store_brand_image_tmp->save()) {
                   $store_brand_image_name = $store_brand_image_tmp->filename;
               } else if (is_file(DIR_FS_LOGO . $store_brand_image)){
                   $store_brand_image_name = $store_brand_image;
               } else {
                   $store_brand_image_name = $store_brand_image_existing;
               }
               if($deleted_image) $store_brand_image_name = '';
               
               $sql_data_array = array( 'store_brand_image' =>   $store_brand_image_name,
                                        'store_brand_slogan' =>    tep_db_prepare_input($store_brand_slogan),
                                        'store_brand_telephone' =>  tep_db_prepare_input($store_brand_telephone),
                                        'store_brand_fax' => tep_db_prepare_input($store_brand_fax),
                                        'store_brand_homepage' => tep_db_prepare_input($store_brand_homepage),
                                        'store_brand_name' => tep_db_prepare_input($store_brand_name),
                                        'store_brand_support_email' => tep_db_prepare_input($store_brand_support_email),
                                        'store_brand_support_phone' => tep_db_prepare_input($store_brand_support_phone),
                                        'language_id' => tep_db_input($languages[$i]['id'])
                                        );
               cre_db_update(TABLE_BRANDING_DESCRIPTION, $sql_data_array);
    
   }// language loop end
    tep_redirect(tep_href_link(FILENAME_BRANDING_MANAGER));
 }// action end
 
  //check directory is exists and writable
  $error = false;
  if (is_dir(DIR_FS_LOGO)) {
    if (!is_writeable(DIR_FS_LOGO)) {
      $messageStack->add('search',ERROR_LOGO_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
       $error = true;
    }
  } else {
    $messageStack->add('search',ERROR_LOGO_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
    $error = true;
  } 
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="./includes/stylesheet.css">
<script language="javascript" src="./includes/general.js"></script>
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
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
        <!-- left_navigation //-->
        <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
        <!-- left_navigation_eof //-->
        <!-- body_text //-->
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_form('frm_upload', FILENAME_BRANDING_MANAGER, 'action=upload', 'post', 'enctype="multipart/form-data"'); ?>
            <table border="0" width="100%" cellspacing="0" cellpadding="4" align="center">
              <tr>
                <td><div class="tab-pane" id="tabPane1">
                    <script type="text/javascript">
               tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );
              </script>
                    <?php
for ($i=0; $i<sizeof($languages); $i++) {
//get existing brand info
$store_brand_info_qry = tep_db_query("SELECT * from " . TABLE_BRANDING_DESCRIPTION ." where language_id = '" . $languages[$i]['id'] . "'");
$store_brand_info = tep_db_fetch_array($store_brand_info_qry); 

?>
                    <div class="tab-page" id="<?php echo $languages[$i]['name'];?>">
                      <h2 class="tab"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], '', '', 'valign="middle" style="height:16px; width:30px;"') . '&nbsp;' .$languages[$i]['name'];?></h2>
                      <script type="text/javascript">tp1.addTabPage( document.getElementById( "<?php echo $languages[$i]['name'];?>" ) );</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="formAreaTitle"><?php echo sprintf(TITLE_STORE_BRAND,$languages[$i]['name']); ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo STORE_BRAND_TELEPHONE_NUMBER; ?></td>
            <td class="main"><?php echo tep_draw_input_field('store_brand_telephone' . $languages[$i]['id'], $store_brand_info['store_brand_telephone'], 'maxlength="32"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo STORE_BRAND_FAX_NUMBER; ?></td>
            <td class="main"><?php echo tep_draw_input_field('store_brand_fax' . $languages[$i]['id'], $store_brand_info['store_brand_fax'], 'maxlength="32"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo STORE_BRAND_HOMEPAGE; ?></td>
            <td class="main"><?php echo tep_draw_input_field('store_brand_homepage' . $languages[$i]['id'], $store_brand_info['store_brand_homepage'], 'maxlength="64"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo STORE_BRAND_COMPANY_LOGO; ?></td>
            <td class="main"><?php echo tep_draw_file_field('store_brand_image' . $languages[$i]['id']);  if (tep_not_null($store_brand_info['store_brand_image']) && file_exists( DIR_FS_LOGO . $store_brand_info['store_brand_image'] )) { echo '<br>' . DIR_FS_LOGO . $store_brand_info['store_brand_image']; }?>
            </td>
          </tr>
          <?php
          if (tep_not_null($store_brand_info['store_brand_image']) ) {
          ?>
          <tr>
            <td class="main"></td>
            <td class="main" valign="middle"><?php 
            if( file_exists( DIR_FS_LOGO . $store_brand_info['store_brand_image'] )) { 
                echo tep_image(DIR_WS_LOGO . $store_brand_info['store_brand_image']) ;
            } else {
                echo '<span class="errorText">' . BRANDING_ERROR_IMAGE_MISSING .'</span>';
            }
            echo ' &nbsp; ' . tep_draw_hidden_field('store_brand_image_existing' . $languages[$i]['id'], $store_brand_info['store_brand_image']) . tep_draw_checkbox_field('delete_image' . $languages[$i]['id'],'yes') . '&nbsp;' .  DELETE_STORE_BRANDING_COMPANY_LOGO;?></td>
          </tr>
          <?php
          }
          ?>
          <tr>
            <td class="main"><?php echo STORE_BRAND_BRANDING_COMPANY_NAME; ?></td>
            <td class="main"><?php echo tep_draw_input_field('store_brand_name' . $languages[$i]['id'], $store_brand_info['store_brand_name']); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo STORE_BRAND_BRANDING_SLOGAN; ?></td>
            <td class="main"><?php echo tep_draw_input_field('store_brand_slogan' . $languages[$i]['id'], $store_brand_info['store_brand_slogan']); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo STORE_BRAND_BRANDING_SUPPORT_EMAIL; ?></td>
            <td class="main"><?php echo tep_draw_input_field('store_brand_support_email' . $languages[$i]['id'],$store_brand_info['store_brand_support_email']); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo STORE_BRAND_BRANDING_SUPPORT_PHONE; ?></td>
            <td class="main"><?php echo tep_draw_input_field('store_brand_support_phone' . $languages[$i]['id'],$store_brand_info['store_brand_support_phone']); ?></td>
          </tr>

          </table>


  </td>
  </tr>
</table>
                    </div>
                    <?php
} //end language loop
?>
                  </div>
                  <script type="text/javascript">
         //<![CDATA[
         setupAllTabs();
         //]]>
         </script>
                </td>
              </tr>
              <tr>
                <td align="right" colspan="2"><?php 
        if (isset($_GET[tep_session_name()])) {
          echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
        }
if (!$error) { 
  echo tep_image_submit('button_update.gif', IMAGE_UPDATE_STORE_BRANDING); 
}
?></td>
              </tr>
            </table>
            </form>
          </td>
        </tr>
        <tr>
          <td><?php tep_draw_separator('pixel_trans.gif','100%','10');?></td>
        </tr>
      </table></td>
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