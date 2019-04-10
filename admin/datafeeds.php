<?php
/*
  $Id: datafeeds.php,v 1.0.0 2009/06/14 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
include_once(DIR_WS_FUNCTIONS . 'datafeed_functions.php');

$feed_file_save_path = DIR_FS_CATALOG . 'feeds/';
$imageURL = HTTP_SERVER . DIR_WS_HTTP_CATALOG . 'images/' . $data_image_url ;
define('FILENAME_PRODUCT_INFO','product_info.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  
  $currency_array = array();
  while (list($key, $value) = each($currencies->currencies)) {
      $currency_array[] = array('id' => $key, 'text' => $value['title']);
  }
  
  $languages = tep_get_languages();
  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      $language_array[] = array('id' => $languages[$i]['id'], 'text' => $languages[$i]['name']);
  }
  
// RCI top
echo $cre_RCI->get('global', 'top', false);
echo $cre_RCI->get('datafeeds', 'top', false); 

$action = (isset($_GET['action'])) ? $_GET['action'] : '';
$feed_id = (isset($_GET['feed_id'])) ? (int)$_GET['feed_id'] : 0;
$is_b2b = (defined('INSTALLED_VERSION_TYPE') && stristr(INSTALLED_VERSION_TYPE, 'B2B')) ? true : false;
$error = false;

// set the selection arrays
/*$feed_services_array = array(array('id' => 'Google Base', 'text' => 'Google Base'),
                             array('id' => 'Standard RSS', 'text' => 'Standard RSS'));*/
//We will do some automation here ;-)
$module_directory = DIR_WS_MODULES . 'datafeeds/'; //store datafeed class files.
$file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
$directory_array = array();
if ($dir = @dir($module_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($module_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          $directory_array[] = $file;
        }
      }
    }
    sort($directory_array);
    $dir->close();
  }

$feed_services_array = array();
for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
    $file = $directory_array[$i];
    include($module_directory . $file);
    $class = substr($file, 0, strrpos($file, '.'));
    if (tep_class_exists($class)) {
        $module = new $class;
    }
$feed_services_array[] = array('id' => $module->code, 'text' => $module->title);
}
//We listed all avaible feed modules.
                          
$feed_types_array = array(array('id' => 'Products (Basic)', 'text' => 'Products (Basic)'),
                          array('id' => 'Products (Advanced)', 'text' => 'Products (Advanced)'));

$feed_file_types_array = array(array('id' => 'XML', 'text' => 'XML'),
                               array('id' => 'Text', 'text' => 'Text'));
                               


$tax_class_query = tep_db_query("SELECT tax_class_title from " . TABLE_TAX_CLASS);
$tax_class_array = array(array('id' => '-None-', 'text' => '-None-'));
while($tax_class = tep_db_fetch_array($tax_class_query)) {
  $tax_class_array[] = array('id' => $tax_class['tax_class_title'], 'text' => $tax_class['tax_class_title']);     
}   

if ($is_b2b == true) {
  $groups_query = tep_db_query("SELECT customers_group_name from " . TABLE_CUSTOMERS_GROUPS);
  $groups_array = array();
  while($groups = tep_db_fetch_array($groups_query)) {
    $groups_array[] = array('id' => $groups['customers_group_name'], 'text' => $groups['customers_group_name']);     
  }                       
} else {
  $groups_array[] = array('id' => 'Retail', 'text' => 'Retail');
}
                                           
$heading_title = (defined('HEADING_TITLE')) ? HEADING_TITLE : 'CRE Data Feed Manager';
if (tep_not_null($action)) {
  switch ($action) {
    case 'build':

      $feeds_query_raw = "SELECT * from " . TABLE_DATA_FEEDS . " WHERE feed_id = '" . $feed_id . "'";
      $feeds = tep_db_fetch_array(tep_db_query($feeds_query_raw));
      $fInfo = new objectInfo($feeds);
      $feed_name = (isset($fInfo->feed_name)) ? $fInfo->feed_name : '';    
      $feed_type = (isset($fInfo->feed_type)) ? $fInfo->feed_type : '';      
      $feed_desc = (isset($fInfo->feed_desc)) ? $fInfo->feed_desc : '';   
      $feed_service = (isset($fInfo->feed_service)) ? $fInfo->feed_service : '';     
      $feed_file_name = (isset($fInfo->feed_file_name)) ? $fInfo->feed_file_name : '';        
      $feed_file_type = (isset($fInfo->feed_file_type)) ? $fInfo->feed_file_type : '';        
      $feed_ftp_user = (isset($fInfo->feed_ftp_user)) ? $fInfo->feed_ftp_user : '';        
      $feed_ftp_pass = (isset($fInfo->feed_ftp_pass)) ? $fInfo->feed_ftp_pass : '';        
      $feed_language = (isset($fInfo->feed_language)) ? $fInfo->feed_language : '';  
      $feed_currency = (isset($fInfo->feed_currency)) ? $fInfo->feed_currency : '';                    
      $feed_tax_class = (isset($fInfo->feed_tax_class)) ? $fInfo->feed_tax_class : '';  
      $feed_price_group_id = (isset($fInfo->feed_price_group_id)) ? $fInfo->feed_price_group_id : '';  
      $feed_auto_send = (isset($fInfo->feed_auto_send)) ? $fInfo->feed_auto_send : '';

    if(file_exists($module_directory . $feed_service . '.php')){
    if (tep_class_exists($feed_service)) {
        $feed_class = new $feed_service;
    }
    } else {
        echo 'Sorry can not proceed... class file mising';
    }

    $file_ext = ($feed_file_type == 'XML') ? '.xml' : '.txt';
    $feed_file = $feed_file_save_path . $feed_file_name . $file_ext;
    cre_check_feed_file($feed_file);

    $cre_feed_data = array();
    $cre_feed_data = $feed_class->buildFeedHead($feed_desc);
    cre_feed_write_to_file($cre_feed_data, 'wb', $feed_file);

    $feed_lang_array = array();
    if($feed_language == 0){
        $feed_lang_query = tep_db_query("select languages_id from " . TABLE_LANGUAGES . " order by sort_order");
        while($lang_id = tep_db_fetch_array($feed_lang_query)){
            $feed_lang_array[] = array('id'=> $lang_id['languages_id']);
        }
    } else {
        $feed_lang_array[] = array('id'=> $feed_language);
    }
    
    $addtional_sql = '';
    if ($is_b2b) $addtional_sql = ' p.products_group_access AS group_access, ';
    
    for ($i=0; $i<sizeof($feed_lang_array); $i++) {  
      $sql = "SELECT 
              pd.language_id AS lang_id,
              p.products_id AS id,
              p.products_quantity AS quantity,
              p.products_model AS model,
              p.products_date_added AS date_added,
              p.products_last_modified AS last_modified,
              p.products_date_available AS date_available,
              p.products_weight AS weight,
              p.products_status AS stats,
              p.products_tax_class_id AS tax_id,
              " . $additional_sql . "
              pd.products_name AS name,
              p.manufacturers_id AS mfg_id,
              m.manufacturers_name AS mfg_name,
               FORMAT( IFNULL(sp.specials_new_products_price, p.products_price) ,2) AS price,
               CONCAT( '" . $imageURL . "' ,p.products_image) AS image_url,
               p2c.categories_id AS categories_id,
               c.parent_id AS parent_categories_id,
               cd.categories_name AS categories_name
        FROM categories c,
             categories_description cd,
             products_description pd,
             products_to_categories p2c,
             products p
        left join manufacturers m on ( m.manufacturers_id = p.manufacturers_id )
        left join specials sp on ( sp.products_id = p.products_id AND ( ( (sp.expires_date > CURRENT_DATE) OR (sp.expires_date = 0) ) AND ( sp.status = 1 ) ) )
        WHERE p.products_id=pd.products_id
          AND p.products_id=p2c.products_id
          AND p2c.categories_id=c.categories_id
          AND c.categories_id=cd.categories_id
          AND pd.language_id = '" . $feed_lang_array[$i]['id'] . "'
          AND pd.language_id = cd.language_id
          ORDER BY p.products_id ASC, model";
          
          
          
          
          $product_query = tep_db_query($sql);
          
          $product_check = array();
          while( $product = tep_db_fetch_array($product_query)) {
              if (isset($product_check[$product['id']][$product['lang_id']])) continue;
              $product_check[$product['id']][$product['lang_id']] = 1;
              
              $n++;
              //    print_r($product);
              if($product['name'] != ''){
                  $cre_feed_data = array();
                  $cre_feed_data = $feed_class->buildFeedNodes($product, $product['lang_id']);
                  cre_feed_write_to_file($cre_feed_data, 'a', $feed_file);
              }
          }
  }//$lang
  
  $cre_feed_data = $feed_class->buildFeedFoot();
  cre_feed_write_to_file($cre_feed_data, 'a', $feed_file);

      $messageStack->add_session('search', TEXT_BUILD_SUCCESS, 'success');
      tep_redirect(tep_href_link(FILENAME_DATA_MANAGER, 'feed_id=' . $feed_id . (($feed_auto_send) ? '&action=send' : '') ) );
      break;
      
    case 'send':
      $feeds_query_raw = "SELECT * from " . TABLE_DATA_FEEDS . " WHERE feed_id = '" . $feed_id . "'";
      $feeds = tep_db_fetch_array(tep_db_query($feeds_query_raw));
      $fInfo = new objectInfo($feeds);
      
      $feed_service = (isset($fInfo->feed_service)) ? $fInfo->feed_service : '';     
      $feed_file_name = (isset($fInfo->feed_file_name)) ? $fInfo->feed_file_name : '';
      $file_ext = ($fInfo->feed_file_type == 'XML') ? '.xml' : '.txt';
      $feed_file = $feed_file_save_path . $feed_file_name . $file_ext;
      
      if(file_exists($module_directory . $feed_service . '.php')){
          if (tep_class_exists($feed_service)) {
              $feed_class = new $feed_service;
          }
      } else {
          echo 'Sorry can not proceed... class file mising';
      }
      
      if(!file_exists($feed_file)){
          $messageStack->add_session('search', 'Feed file: ' . $feed_file . ' missing or not generated.','error');
      } else {
          $conn_id = ftp_connect($feed_class->ftp_server);
          if ( $conn_id == false ){
              $messageStack->add_session('search', 'Can not open FTP ' . $feed_class->ftp_server,'error');
          }
          
          $login_result = ftp_login($conn_id, $fInfo->feed_ftp_user, $fInfo->feed_ftp_pass);

          if ((!$conn_id) || (!$login_result)) {
              $messageStack->add_session('search', 'FTP connection failed to ' . $feed_class->ftp_server . ' with user ' . $fInfo->feed_ftp_user,'error');
          } else {
              $messageStack->add_session('search', 'Connected to ' . $feed_class->ftp_server . ' with user ' . $fInfo->feed_ftp_user,'success');
          }
          
          ftp_pasv ( $conn_id, true ) ;// to avoide php warnings of opening data connection.
          $upload = ftp_put( $conn_id, $feed_file_name . $file_ext, $feed_file, FTP_ASCII );
          
          // check upload status
          if (!$upload) {
              $messageStack->add_session('search', $feed_class->ftp_server . ': ' . $feed_file . ' upload has failed','error');
          } else {
              $messageStack->add_session('search', $feed_file . ' uploaded to ' . $feed_class->ftp_server,'success');
          }
          // close ftp connection
          ftp_close($conn_id);
      }
                      
      tep_redirect(tep_href_link(FILENAME_DATA_MANAGER, '&feed_id=' . $feed_id));
      break;
    
    case 'edit':
      $heading_title = (defined('HEADING_TITLE_EDIT')) ? HEADING_TITLE_EDIT : 'Edit Data Feed';
      $feeds_query_raw = "SELECT * 
                           from " . TABLE_DATA_FEEDS . " WHERE feed_id = '" . $feed_id . "'";
      $feeds = tep_db_fetch_array(tep_db_query($feeds_query_raw));
      $fInfo = new objectInfo($feeds);
      $feed_name = (isset($fInfo->feed_name)) ? $fInfo->feed_name : '';    
      $feed_type = (isset($fInfo->feed_type)) ? $fInfo->feed_type : '';      
      $feed_desc = (isset($fInfo->feed_desc)) ? $fInfo->feed_desc : '';   
      $feed_service = (isset($fInfo->feed_service)) ? $fInfo->feed_service : '';     
      $feed_file_name = (isset($fInfo->feed_file_name)) ? $fInfo->feed_file_name : '';        
      $feed_file_type = (isset($fInfo->feed_file_type)) ? $fInfo->feed_file_type : '';        
      $feed_ftp_user = (isset($fInfo->feed_ftp_user)) ? $fInfo->feed_ftp_user : '';        
      $feed_ftp_pass = (isset($fInfo->feed_ftp_pass)) ? $fInfo->feed_ftp_pass : '';        
      $feed_language = (isset($fInfo->feed_language)) ? $fInfo->feed_language : '';  
      $feed_currency = (isset($fInfo->feed_currency)) ? $fInfo->feed_currency : '';                    
      $feed_tax_class = (isset($fInfo->feed_tax_class)) ? $fInfo->feed_tax_class : '';  
      $feed_price_group_id = (isset($fInfo->feed_price_group_id)) ? $fInfo->feed_price_group_id : '';  
      $feed_auto_send = (isset($fInfo->feed_auto_send)) ? $fInfo->feed_auto_send : '';  
      break;  
      
    case 'new':
      $heading_title = (defined('HEADING_TITLE_ADD')) ? HEADING_TITLE_ADD : 'Insert Data Feed';
      $feed_name = '';  
      $feed_type = '';      
      $feed_desc = '';  
      $feed_service = 'Google Base';      
      $feed_file_name = '';        
      $feed_file_type = '';        
      $feed_ftp_user = '';        
      $feed_ftp_pass = '';        
      $feed_language = '';  
      $feed_currency = 'USD';                    
      $feed_tax_class = '';  
      $feed_price_group_id = '1';  
      $feed_auto_send = '';  
      break;      
      
    case 'update':
    case 'insert':
      if ($action == 'update') {
        $feed_id = tep_db_prepare_input($_GET['feed_id']);
      }
      $feed_name = tep_db_prepare_input($_POST['feed_name']);
      $feed_type = tep_db_prepare_input($_POST['feed_type']);      
      $feed_desc = tep_db_prepare_input($_POST['feed_desc']); 
      $feed_service = tep_db_prepare_input($_POST['feed_service']);     
      $feed_file_name = tep_db_prepare_input($_POST['feed_file_name']);       
      $feed_file_type = tep_db_prepare_input($_POST['feed_file_type']);    
      $feed_ftp_user = tep_db_prepare_input($_POST['feed_ftp_user']);      
      $feed_ftp_pass = tep_db_prepare_input($_POST['feed_ftp_pass']);     
      $feed_language = tep_db_prepare_input($_POST['feed_language']);  
      $feed_currency = tep_db_prepare_input($_POST['feed_currency']);          
      $feed_tax_class = tep_db_prepare_input($_POST['feed_tax_class']); 
      $feed_price_group_id = tep_db_prepare_input($_POST['feed_price_group_id']);
      $feed_auto_send = tep_db_prepare_input($_POST['feed_auto_send']);
      if (strlen($feed_name) <= 0) {
        $error = true;
        $entry_feed_name_error = true;
        $messageStack->add_session('search', 'feed name error', 'error');
      } else {
        $entry_feed_name_error = false;
      }     
      if (strlen($feed_file_name) <= 0) {
        $error = true;
        $entry_feed_file_name_error = true;
        $messageStack->add_session('search', 'feed file name error', 'error');
      } else {
        $entry_feed_file_name_error = false;
      }         
      if ($error != true) {
        if ($feed_service == 'Standard RSS') {
          $feed_ftp_user = '';
          $feed_ftp_pass = '';
          $feed_auto_send = '';
          $feed_file_type = 'XML';
        }
        if (isset($feed_auto_send) && $feed_auto_send == '0') $feed_auto_send = '1';
          
        $sql_update_array = array();
        $sql_data_array = array('feed_name' => $feed_name,
                                'feed_type' =>$feed_type,
                                'feed_desc' =>$feed_desc,
                                'feed_service' => $feed_service,
                                'feed_file_name' => $feed_file_name,
                                'feed_file_type' => $feed_file_type,        
                                'feed_ftp_user' => $feed_ftp_user,        
                                'feed_ftp_pass' => $feed_ftp_pass,        
                                'feed_language' => $feed_language,  
                                'feed_currency' => $feed_currency,                    
                                'feed_tax_class' => $feed_tax_class,  
                                'feed_auto_send' => $feed_auto_send,
                                'last_modified' => 'now()');
      
        if ($is_b2b == true) $sql_update_array = array('feed_price_group_id' => $feed_price_group_id);
        $sql_array = array_merge((array)$sql_data_array, (array)$sql_update_array);
/*                               
echo "<pre>";
print_r($sql_array);
echo "</pre>";
die('444'); 
*/
        if ($action == 'insert') {
            $messageStack->add_session('search', 'Added record', 'success');
            $insert_sql_data = array('date_created' => 'now()');
            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
            
          tep_db_perform(TABLE_DATA_FEEDS, $sql_array);  
          $feed_id = tep_db_insert_id();
        } else {            
            $messageStack->add_session('search', 'Updated record', 'success');
          tep_db_perform(TABLE_DATA_FEEDS, $sql_array, 'update', "feed_id = '" . (int)$feed_id . "'");
        }
        tep_redirect(tep_href_link(FILENAME_DATA_MANAGER, '&feed_id=' . $feed_id));          
      } else {
        tep_redirect(tep_href_link(FILENAME_DATA_MANAGER, 'action=new'));
      }
      break;
        
      case 'deleteconfirm':
        $feed_id = tep_db_prepare_input($_GET['feed_id']);
        tep_db_query("DELETE from " . TABLE_DATA_FEEDS . " WHERE feed_id = '" . (int)$feed_id . "'");
        $messageStack->add_session('search', 'feed deleted', 'warning');
        tep_redirect(tep_href_link(FILENAME_DATA_MANAGER, '')); 
        break;
           
      default:
        $feeds_query = tep_db_query("SELECT * 
                                       from " . TABLE_DATA_FEEDS . " 
                                     WHERE feed_id = '" . (int)$_GET['feed_id'] . "'");
    }
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link type="text/css" rel="StyleSheet" href="includes/helptip.css" /> 
<script type="text/javascript" src="includes/javascript/helptip.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript"> 
 function updateEvents(events) { 
    var googlebase = document.getElementById('googlebase'); 
    var standard = document.getElementById('standard');
    var autosend = document.getElementById('autosend'); 
    if (events == 'Google Base') {
      googlebase.style.display = 'block';  
      standard.style.display = 'none'; 
      autosend.style.display = 'block';
    } else if (events == 'Standard RSS') {
      googlebase.style.display = 'none';
      standard.style.display = 'block';
      autosend.style.display = 'none';    
    }
 } 
</script> 
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="updateEvents(document.getElementById('feed_service').value)">
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
    <td valign="top" class="page-container"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <?php
      if ($action == 'edit' || $action == 'update' || $action == 'new') {               
        ?>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?php echo $heading_title; ?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10');?></td>
        </tr>
        <tr><?php
          $form_action = ($action == 'new') ? 'insert' : 'update';
          echo tep_draw_form('feeds', FILENAME_DATA_MANAGER, tep_get_all_get_params(array('action')) . 'action=' . $form_action, 'post'); ?>
          <td valign="top" colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>          
              <td valign="top">
                <fieldset>
                  <legend><?php echo TEXT_FEED_INFO; ?></legend>          
                  <table width="100%" border="0" cellspacing="2" cellpadding="2">
                    <tr>
                      <td class="main" width="30%"><?php echo ENTRY_FEED_NAME; ?></td>
                      <td class="main">
                        <?php
                        if ($error == true) {
                          if (isset($entry_feed_name_error) && $entry_feed_name_error == true) {
                            echo tep_draw_input_field('feed_name', $feed_name, 'maxlength="32"') . '&nbsp;' . ENTRY_FEED_NAME_ERROR;
                          } else {
                            echo $feed_name . tep_draw_hidden_field('feed_name');
                          }
                        } else {
                          echo tep_draw_input_field('feed_name', $feed_name, 'maxlength="32"', true);
                        }
                        ?>&nbsp;  
                        <a class="helpLink" href="#" onMouseover="showhint('<?php echo BLOCK_HELP_HELPTIP_1;?>', this, event, '250px'); return false"><?php echo tep_image(DIR_WS_IMAGES . 'icons/icon_help.gif'); ?></a>
                      </td>  
                      <td class="main" width="14">&nbsp;</td>
                      <td class="main" width="50%">&nbsp;</td>
                    </tr>
                    <tr>
                      <td class="main"><?php echo ENTRY_FEED_TYPE; ?></td>
                      <td colspan="3" class="main">
                        <?php echo tep_draw_pull_down_menu('feed_type', $feed_types_array, $feed_type); ?>&nbsp;
                        <a class="helpLink" href="#" onMouseover="showhint('<?php echo BLOCK_HELP_HELPTIP_2;?>', this, event, '250px'); return false"><?php echo tep_image(DIR_WS_IMAGES . 'icons/icon_help.gif'); ?></a>
                      </td>
                    </tr>
                    <tr>
                      <td class="main"><?php echo ENTRY_FEED_DESC; ?></td>
                      <td valign="top" class="main"><?php echo tep_draw_textarea_field('feed_desc', 'soft', '60', '3', $feed_desc); ?></td>
                      <td class="main" valign="top" style="padding-top:6px;"><a class="helpLink" href="#" onMouseover="showhint('<?php echo BLOCK_HELP_HELPTIP_3;?>', this, event, '250px'); return false"><?php echo tep_image(DIR_WS_IMAGES . 'icons/icon_help.gif'); ?></a></td>
                      <td class="main">&nbsp;</td> 
                    </tr>
                  </table>
                </fieldset>
              </td>
            </tr>
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10');?></td>
            </tr>
            <tr>          
              <td valign="top">
                <!-- Data Feed Service -->
                <fieldset>
                  <legend><?php echo TEXT_FEED_SERVICE; ?></legend>
                  <div style="float:left; display:inline; width:100%;">
                    <table width="100%" border="0" cellspacing="2" cellpadding="2">                 
                      <tr>
                        <td width="20%" class="main"><label> <?php echo ENTRY_FEED_SERVICE; ?></label></td>
                        <td width="80%" class="main">
                          <?php echo tep_draw_pull_down_menu('feed_service', $feed_services_array, $feed_service, 'id="feed_service" onChange="updateEvents(this[this.selectedIndex].value);"'); ?>
                          <a class="helpLink" href="#" onMouseover="showhint('<?php echo BLOCK_HELP_HELPTIP_4;?>', this, event, '250px'); return false"><?php echo tep_image(DIR_WS_IMAGES . 'icons/icon_help.gif'); ?></a>
                        </td>
                      </tr>
                    </table>
                    <!-- googlebase div -->
                    <div id="googlebase" style="display:block;">  
                      <table width="100%" border="0" cellspacing="2" cellpadding="2">  
                        <tr>
                          <td width="20%" class="main"><label><?php echo ENTRY_FEED_FILENAME; ?></label></td>
                          <td width="80%" class="main">
                            <?php
                            if ($error == true) {
                              if (isset($entry_feed_file_name_error) && $entry_feed_file_name_error == true) {
                                echo tep_draw_input_field('feed_file_name', $feed_file_name, 'maxlength="32"') . '&nbsp;' . ENTRY_FEED_FILE_NAME_ERROR;
                              } else {
                                echo $feed_file_name . tep_draw_hidden_field('feed_file_name');
                              }
                            } else {
                              echo tep_draw_input_field('feed_file_name', $feed_file_name, 'maxlength="32"', true);
                            }
                            ?>&nbsp;                            
                            <a class="helpLink" href="#" onMouseover="showhint('<?php echo BLOCK_HELP_HELPTIP_5;?>', this, event, '250px'); return false"><?php echo tep_image(DIR_WS_IMAGES . 'icons/icon_help.gif'); ?></a>
                          </td>
                        </tr>
                        <tr>
                          <td width="20%" class="main"><label><?php echo ENTRY_FEED_FILE_TYPE; ?></label></td>
                          <td width="80%" class="main">
                            <?php echo tep_draw_pull_down_menu('feed_file_type', $feed_file_types_array, $feed_file_type); ?>
                            <a class="helpLink" href="#" onMouseover="showhint('<?php echo BLOCK_HELP_HELPTIP_6;?>', this, event, '250px'); return false"><?php echo tep_image(DIR_WS_IMAGES . 'icons/icon_help.gif'); ?></a>  
                          </td>              
                        </tr>
                        <tr>
                          <td width="20%" class="main"><label><?php echo ENTRY_FEED_FTP_USER; ?></label></td>
                          <td width="80%" class="main">
                            <?php echo tep_draw_input_field('feed_ftp_user', $feed_ftp_user, 'maxlength="32"'); ?>
                            <a class="helpLink" href="#" onMouseover="showhint('<?php echo BLOCK_HELP_HELPTIP_7;?>', this, event, '250px'); return false"><?php echo tep_image(DIR_WS_IMAGES . 'icons/icon_help.gif'); ?></a>
                          </td>              
                        </tr>
                        <tr>
                          <td width="20%" class="main"><label><?php echo ENTRY_FEED_FTP_PASS; ?></label></td>
                          <td width="80%" class="main">
                            <?php echo tep_draw_input_field('feed_ftp_pass', $feed_ftp_pass, 'maxlength="32"'); ?>
                            <a class="helpLink" href="#" onMouseover="showhint('<?php echo BLOCK_HELP_HELPTIP_8;?>', this, event, '250px'); return false"><?php echo tep_image(DIR_WS_IMAGES . 'icons/icon_help.gif'); ?></a>
                          </td>              
                        </tr>
                        <tr>
                          <td width="20%" class="main"><label><?php echo ENTRY_FEED_LANGUAGE; ?></label></td>
                          <td width="80%" class="main">
                            <?php echo tep_draw_pull_down_menu('feed_language', $language_array, $feed_language); ?>
                            <a class="helpLink" href="#" onMouseover="showhint('<?php echo BLOCK_HELP_HELPTIP_10;?>', this, event, '250px'); return false"><?php echo tep_image(DIR_WS_IMAGES . 'icons/icon_help.gif'); ?></a>
                          </td>              
                        </tr>                       
                        <tr>
                          <td width="20%" class="main"><label><?php echo ENTRY_FEED_TAX_CLASS; ?></label></td>
                          <td width="80%" class="main">
                            <?php echo tep_draw_pull_down_menu('feed_tax_class', $tax_class_array, $feed_tax_class); ?>
                            <a class="helpLink" href="#" onMouseover="showhint('<?php echo BLOCK_HELP_HELPTIP_11;?>', this, event, '250px'); return false"><?php echo tep_image(DIR_WS_IMAGES . 'icons/icon_help.gif'); ?></a>
                          </td>              
                        </tr>  
                        <?php 
                        if ($is_b2b == true) {
                          ?>      
                          <tr>
                            <td width="20%" class="main"><label><?php echo ENTRY_FEED_PRICE_GROUP; ?></label></td>
                            <td width="80%" class="main">
                              <?php echo tep_draw_pull_down_menu('feed_price_group', $groups_array, $feed_price_group); ?>
                              <a class="helpLink" href="#" onMouseover="showhint('<?php echo BLOCK_HELP_HELPTIP_12;?>', this, event, '250px'); return false"><?php echo tep_image(DIR_WS_IMAGES . 'icons/icon_help.gif'); ?></a>
                            </td>              
                          </tr>        
                          <?php
                        }
                        ?>
                      </table>            
                    </div>   
                    <!-- standard div -->
                    <div id="standard" style="display:none;"> 
                      <table width="100%" border="0" cellspacing="2" cellpadding="2">  
                        <tr>
                          <td width="20%" class="main"><label><?php echo ENTRY_FEED_CURRENCY; ?></label></td>
                          <td width="80%" class="main">
                            <?php echo tep_draw_pull_down_menu('feed_currency', $currency_array, $feed_currency); ?>
                            <a class="helpLink" href="#" onMouseover="showhint('<?php echo BLOCK_HELP_HELPTIP_9;?>', this, event, '250px'); return false"><?php echo tep_image(DIR_WS_IMAGES . 'icons/icon_help.gif'); ?></a>
                          </td>              
                        </tr>        
                        <tr>
                          <td width="20%" class="main"><label><?php echo ENTRY_FEED_TAX_CLASS; ?></label></td>
                          <td width="80%" class="main">
                            <?php echo tep_draw_pull_down_menu('feed_tax_class', $tax_class_array, $feed_tax_class); ?>
                            <a class="helpLink" href="#" onMouseover="showhint('<?php echo BLOCK_HELP_HELPTIP_11;?>', this, event, '250px'); return false"><?php echo tep_image(DIR_WS_IMAGES . 'icons/icon_help.gif'); ?></a>
                          </td>              
                        </tr>   
                        <?php 
                        if ($is_b2b == true) {
                          ?>
                          <tr>
                            <td width="20%" class="main"><label><?php echo ENTRY_FEED_PRICE_GROUP; ?></label></td>
                            <td width="80%" class="main">
                              <?php echo tep_draw_pull_down_menu('feed_price_group', $groups_array, $feed_price_group); ?>
                              <a class="helpLink" href="#" onMouseover="showhint('<?php echo BLOCK_HELP_HELPTIP_12;?>', this, event, '250px'); return false"><?php echo tep_image(DIR_WS_IMAGES . 'icons/icon_help.gif'); ?></a>
                            </td>              
                          </tr>        
                          <?php
                        }
                        ?>
                      </table> 
                    </div>  
                  </div>
                </fieldset>            
 
              </td>
            </tr>
            <tr>
              <td colspan="2" align="right" style="padding-right:150px;" class="main">
                <div id="autosend" style="display:block;"> 
                  <?php
                  $checked = (isset($feed_auto_send) && $feed_auto_send == '1') ? ' CHECKED' : '';
                  echo TEXT_AUTO_SEND . '&nbsp;&nbsp;' . tep_draw_checkbox_field('feed_auto_send', $feed_auto_send, $checked, true);
                  ?>
                </div>               
              </td>
            </tr>                      
            <tr>
              <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
              <td colspan="2"><?php echo tep_draw_separator('pixel_black.gif', '100%', '1'); ?></td>
            </tr>  
            <tr>
              <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>             
            <tr>
              <td colspan="2" align="right" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_DATA_MANAGER, tep_get_all_get_params(array('action'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . (($action == 'new') ? tep_image_submit('button_insert.gif', IMAGE_INSERT) : tep_image_submit('button_update.gif', IMAGE_UPDATE) ); ?></td>
            </tr></form>            
          </table></td>
        </tr>
        <?php
      } else {
        ?>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
                <tr class="dataTableHeadingRow">
                  <?php
                  $listing = isset($_GET['listing']) ? $_GET['listing'] : '';
                  switch ($listing) {
                    case "name":
                      $order = "feed_name";
                      break;
                    case "name-desc":
                      $order = "feed_name DESC";
                      break;
                    case "type":
                      $order = "feed_type";
                      break;
                    case "type-desc":
                      $order = "feed_type DESC";
                      break;
                    case "service":
                      $order = "feed_service";
                      break;
                    case "service-desc":
                      $order = "feed_service DESC";
                      break;
                    default:
                      $order = "feed_id DESC";
                  }
                  ?>                
                  <td class="dataTableHeadingContent" valign="top">
                    <?php echo TABLE_HEADING_FEED_NAME; ?>
                    <a href="<?php echo tep_href_link(FILENAME_DATA_MANAGER,'listing=name'); ?>"><?php echo tep_icon_submit('ic_up.gif', ' Sort ' . TABLE_HEADING_DISPLAY_NAME . ALT_IC_UP); ?></a>
                    <a href="<?php echo tep_href_link(FILENAME_DATA_MANAGER,'listing=name-desc'); ?>"><?php echo tep_icon_submit('ic_down.gif', ' Sort ' . TABLE_HEADING_DISPLAY_NAME . ALT_IC_DOWN); ?></a>
                  </td>
                  <td class="dataTableHeadingContent" valign="top">
                    <?php echo TABLE_HEADING_FEED_TYPE; ?>
                    <a href="<?php echo tep_href_link(FILENAME_DATA_MANAGER,'listing=type'); ?>"><?php echo tep_icon_submit('ic_up.gif', ' Sort ' . TABLE_HEADING_NETWORK . ALT_IC_UP); ?></a>
                    <a href="<?php echo tep_href_link(FILENAME_DATA_MANAGER,'listing=type-desc'); ?>"><?php echo tep_icon_submit('ic_down.gif', ' Sort ' . TABLE_HEADING_NETWORK . ALT_IC_DOWN); ?></a>
                  </td>
                  <td class="dataTableHeadingContent" valign="top">
                    <?php echo TABLE_HEADING_FEED_SERVICE; ?>
                    <a href="<?php echo tep_href_link(FILENAME_DATA_MANAGER,'listing=service'); ?>"><?php echo tep_icon_submit('ic_up.gif', ' Sort ' . TABLE_HEADING_SOCKET_ID . ALT_IC_UP); ?></a>
                    <a href="<?php echo tep_href_link(FILENAME_DATA_MANAGER,'listing=service-desc'); ?>"><?php echo tep_icon_submit('ic_down.gif', ' Sort ' . TABLE_HEADING_SOCKET_ID . ALT_IC_DOWN); ?></a>
                  </td>                
                  <td class="dataTableHeadingContent" align="right" valign="top"><?php echo TABLE_HEADING_ACTION; ?></td>
                </tr>
                <?php
                $feeds_query_raw = "SELECT * 
                                     from " . TABLE_DATA_FEEDS . " g order by $order";
                $show_build = false;
                $show_send = false;
                $feeds = array();
                $feeds_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $feeds_query_raw, $feeds_query_numrows);
                $feeds_query = tep_db_query($feeds_query_raw);
                while ($feeds = tep_db_fetch_array($feeds_query)) {
                  if ((!isset($_GET['feed_id']) || (isset($_GET['feed_id']) && ($_GET['feed_id'] == $feeds['feed_id']))) && !isset($fInfo)) {
                    $fInfo = new objectInfo($feeds);
                    $build_error_array = array();
                    $send_error_array = array();
                    switch ($fInfo->feed_service) {
                      case 'Google Base':
                        if ((!isset($fInfo->feed_file_name) || (isset($fInfo->feed_file_name) && $fInfo->feed_file_name == ''))) $build_error_array[] = 'feed_ftp_user';              
                        if ((!isset($fInfo->feed_ftp_user) || (isset($fInfo->feed_ftp_user) && $fInfo->feed_ftp_user == ''))) $send_error_array[] = 'feed_ftp_user';
                        if ((!isset($fInfo->feed_ftp_pass) || (isset($fInfo->feed_ftp_pass) && $fInfo->feed_ftp_pass == ''))) $send_error_array[] = 'feed_ftp_pass';
                        break;
                      case 'Standard RSS':    
                        if ((!isset($fInfo->feed_file_name) || (isset($fInfo->feed_file_name) && $fInfo->feed_file_name == ''))) $build_error_array[] = 'feed_ftp_user';              
                        $send_error_array[] = 'Send is not an option for this feed service';
                        break;
                    }
                    $show_build = ($build_error_array[0] == '') ? true : false;
                    $show_send = ($send_error_array[0] == '') ? true : false;  
                  }
                  if (isset($fInfo) && is_object($fInfo) && ($feeds['feed_id'] == $fInfo->feed_id)) {
                    echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_DATA_MANAGER, tep_get_all_get_params(array('feed_id', 'action')) . 'feed_id=' . $fInfo->feed_id . '&action=edit') . '\'">' . "\n";
                  } else {
                    echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_DATA_MANAGER, tep_get_all_get_params(array('feed_id')) . 'feed_id=' . $feeds['feed_id']) . '\'">' . "\n";
                  }
                  ?>
                  <td class="dataTableContent"><?php
                    if (strlen($feeds['feed_name']) > 30 ) {
                      print ("<acronym title=\"".$feeds['feed_name']."\">".substr($feeds['feed_name'], 0, 20)."&#160;</acronym>");
                    } else {
                      echo $feeds['feed_name']; 
                    } ?>
                  </td>
                  <td class="dataTableContent"><?php
                    if (strlen($feeds['feed_type']) > 10 ) {
                      print ("<acronym title=\"".$feeds['feed_type']."\">".substr($feeds['feed_type'], 0, 25)."&#160;</acronym>");
                    } else {
                      echo $feeds['feed_type']; 
                    } ?>
                  </td>
                  <td class="dataTableContent"><?php
                    if (strlen($feeds['feed_service']) > 30 ) {
                      print ("<acronym title=\"".$feeds['feed_service']."\">".substr($feeds['feed_service'], 0, 15)."&#160;</acronym>");
                    } else {
                      echo $feeds['feed_service']; 
                    } ?>
                  </td>
                  <td class="dataTableContent" align="right"><?php if (isset($fInfo) && is_object($fInfo) && ($feeds['feed_id'] == $fInfo->feed_id)) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_DATA_MANAGER, tep_get_all_get_params(array('feed_id')) . 'feed_id=' . $feeds['feed_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                </tr>
                <?php
                }
                ?>
                </table>
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                  <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="smallText" valign="top"><?php echo $feeds_split->display_count($feeds_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_FEEDS); ?></td>
                      <td class="smallText" align="right"><?php echo $feeds_split->display_links($feeds_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'feed_id'))); ?></td>
                    </tr>                    
                    <?php
                    if (empty($action)) {
                      ?>
                      <tr>
                        <td colspan="5" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DATA_MANAGER, 'page=' . $_GET['page'] . '&feed_id=' . $fInfo->feed_id . '&action=new') . '">' . tep_image_button('button_new_feed.gif', IMAGE_NEW_FEED) . '</a>'; ?></td>
                      </tr>
                      <?php
                    }
                    ?>
                  </table></td>
                </tr>              
              </table></td>
              <?php
              $heading = array();
              $contents = array();

              switch ($action) {
                case 'confirm':
                  $heading[] = array('text' => ''. tep_draw_separator('pixel_trans.gif', '11', '12') .'&nbsp;<br><b>' . TEXT_INFO_HEADING_DELETE_DATAFEED . '</b>');
                  $contents = array('form' => tep_draw_form('feeds', FILENAME_DATA_MANAGER, tep_get_all_get_params(array('feed_id', 'action')) . 'feed_id=' . $fInfo->feed_id . '&action=deleteconfirm'));
                  $contents[] = array('text' => TEXT_DELETE_INTRO . '<br><br><b>' . $fInfo->feed_name . '</b>');
                  $contents[] = array('align' => 'center', 'text' => '<br /><a href="' . tep_href_link(FILENAME_DATA_MANAGER, tep_get_all_get_params(array('feed_id', 'action')) . 'feed_id=' . $fInfo->feed_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_delete.gif', IMAGE_DELETE));
                  break;

                default:
                  if (isset($fInfo) && is_object($fInfo)) {
                    $heading[] = array('text' => '[' . $fInfo->feed_id . '] ' . $fInfo->feed_name );
                    $contents[] = array('align' => 'center',
                                        'text' => '<br /><a href="' . tep_href_link(FILENAME_DATA_MANAGER, tep_get_all_get_params(array('feed_id', 'action')) . 'feed_id=' . $fInfo->feed_id . '&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a>' .
                                                  '<a href="' . tep_href_link(FILENAME_DATA_MANAGER, tep_get_all_get_params(array('feed_id', 'action')) . 'feed_id=' . $fInfo->feed_id . '&action=confirm') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a><br />');
                    if ($show_build == true) {                              
                      $contents[] = array('align' => 'center',
                                           'text' => '<a href="' . tep_href_link(FILENAME_DATA_MANAGER, tep_get_all_get_params(array('feed_id', 'action')) . 'feed_id=' . $fInfo->feed_id . '&action=build') . '">' . tep_image_button('button_build_feed.png', IMAGE_BUILD_FEED) . '</a>');
                    }
                    if ($show_send == true) {                    
                      $contents[] = array('align' => 'center',
                                          'text' => '<a href="' . tep_href_link(FILENAME_DATA_MANAGER, tep_get_all_get_params(array('feed_id', 'action')) . 'feed_id=' . $fInfo->feed_id . '&action=send') . '">' . tep_image_button('button_send_feed.png', IMAGE_SEND_FEED) . '</a>');
                    } else {
                        $missing = array();
                        if (isset($fInfo->feed_ftp_user) && $fInfo->feed_ftp_user == '') $missing[] = 'feed_ftp_user';
                        if (isset($fInfo->feed_ftp_pass) && $fInfo->feed_ftp_user == '') $missing[] = 'feed_ftp_pass';
                        if ($missing[0] != '') {
                          $contents[] = array('text' => '<br>' . TEXT_SEND_ERROR . ' <b>' . implode(", ", $missing) . '</b>');
                        }
                    }
                    $contents[] = array('text' => '<br>' . TEXT_LANGUAGE . ' <b>' . cre_get_feed_language_name($fInfo->feed_language) . '</b>');
                    $contents[] = array('text' => '<br>' . TEXT_CURRENCY . ' <b>' . $fInfo->feed_currency . '</b>');                                                          
                    $contents[] = array('text' => '<br>' . TEXT_DATE_CREATED . '<br><b>' . $fInfo->date_created . '</b>');
                    $contents[] = array('text' => '<br>' . TEXT_LAST_MODIFIED . '<br><b>' . $fInfo->last_modified . '</b>');                                        
                    //echo 'b[' . $show_build . ']s[' . $show_send . ']<br>';                    
                  }
                  break;
              }
              if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
                echo '<td width="25%" valign="top">' . "\n";
                $box = new box;
                echo $box->infoBox($heading, $contents);
                echo '</td>' . "\n";
              }
              ?>
            </tr>
          </table></td>
        </tr>
        <?php
      }
      // RCI for global and individual bottom
      echo $cre_RCI->get('datafeeds', 'bottom'); 
      echo $cre_RCI->get('global', 'bottom');                                      
      ?>
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