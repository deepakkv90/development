<?php
/*
  Id: modules.php,v 1.4 2008/05/30 00:36:41 datazen Exp 

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
define('FILENAME_AUTHORIZENET_HELP', 'authnet_help.php');
require('includes/application_top.php');

  // RCI for global and individual top
  echo $cre_RCI->get('global', 'top', false);
  echo $cre_RCI->get('modules', 'top', false); 
  
$set = (isset($_GET['set'])) ? $_GET['set'] : '';
if (tep_not_null($set)) {
  switch ($set) {                                                                                                                                                                 
    case 'shipping':
      $module_type = 'shipping';
      $module_directory = DIR_FS_CATALOG_MODULES . 'shipping/';
      $module_key = 'MODULE_SHIPPING_INSTALLED';
      define('HEADING_TITLE', HEADING_TITLE_MODULES_SHIPPING);
      $SSL= 'NONSSL';
      break;
    case 'ordertotal':
      $module_type = 'order_total';
      $module_directory = DIR_FS_CATALOG_MODULES . 'order_total/';
      $module_key = 'MODULE_ORDER_TOTAL_INSTALLED';
      define('HEADING_TITLE', HEADING_TITLE_MODULES_ORDER_TOTAL);
      $SSL= 'NONSSL';
      break;
    case 'checkout_success':
      $module_type = 'checkout_success';
      $module_directory = DIR_FS_CATALOG_MODULES . 'checkout_success/';
      $module_key = 'MODULE_CHECKOUT_SUCCESS_INSTALLED';
      define('HEADING_TITLE', HEADING_TITLE_MODULES_CHECKOUT_SUCCESS);
      $SSL= 'NONSSL';
      break;
    case 'addons':
      $module_type = 'addons';
      $module_directory = DIR_FS_CATALOG_MODULES . 'addons/';
      $module_key = 'MODULE_ADDONS_INSTALLED';
      define('HEADING_TITLE', HEADING_TITLE_MODULES_ADDONS);
      $SSL= 'NONSSL';
      break;                          
    case 'payment':
      $module_type = 'payment';
      $module_directory = DIR_FS_CATALOG_MODULES . 'payment/';
      $module_key = 'MODULE_PAYMENT_INSTALLED';
      define('HEADING_TITLE', HEADING_TITLE_MODULES_PAYMENT);
      $SSL= 'SSL';
      break;
    default:
      $cre_RCI->get('modules', 'set');
      if ($module_type == '') {
        $module_type = 'payment';
        $module_directory = DIR_FS_CATALOG_MODULES . 'payment/';
        $module_key = 'MODULE_PAYMENT_INSTALLED';
        define('HEADING_TITLE', HEADING_TITLE_MODULES_PAYMENT);
        $SSL= 'SSL';
      }
      break;
  }
}
$action = (isset($_GET['action']) ? $_GET['action'] : '');
if (tep_not_null($action)) {
  switch ($action) {
    case 'save':
      while (list($key, $value) = each($_POST['configuration'])) {
        if( is_array( $value ) ){
          $value = implode( ", ", $value);
          $value = ereg_replace (", --none--", "", $value);
        }
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . mysql_escape_string($value) . "' where configuration_key = '" . $key . "'");
      }
      $cre_RCI->get('modules', 'action');
      tep_redirect(tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $_GET['module'], $SSL));
      break;
    case 'install':
      $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
      $class = basename($_GET['module']);
      if (file_exists($module_directory . $class . $file_extension)) {
        include($module_directory . $class . $file_extension);
        $module = new $class;
        $module->install();
      }
      $cre_RCI->get('modules', 'action');
      tep_redirect(tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $class, $SSL));
      break;
    case 'remove':
      $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
      $class = basename($_GET['module']);
      if (file_exists($module_directory . $class . $file_extension)) {
        include($module_directory . $class . $file_extension);
        $module = new $class;
        $module->remove();
      }
      $cre_RCI->get('modules', 'action');
      tep_redirect(tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $class, $SSL));
      break;
    default:
      // RCI call added incase there is an action needed for a called module
      $cre_RCI->get('modules', 'action');
  }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
            <td class="pageHeading"><?php echo (defined('HEADING_TITLE')) ? HEADING_TITLE : ''; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
              <tr class="dataTableHeadingRow">
                <?php
                if ($module_type == 'payment') {
                  echo '<td width="20" class="dataTableHeadingContent" align="left">PCI/PA-DSS</td>';
                }
                ?>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MODULES; ?></td>
                  <?php 
                  if ($set == 'addons') {
                    echo '<td class="dataTableHeadingContent" align="center">' . "\n";
                    echo TABLE_HEADING_INSTALLED;
                  } else {
                    echo '<td class="dataTableHeadingContent" align="right">' . "\n";
                    echo TABLE_HEADING_SORT_ORDER;
                  }
                  ?>
                </td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <?php
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
              $modules_wkey = array();  // this array used the file name as the key and the data as the sort order
              $modules_nokey = array();  // standard array
   
              // custom sort added for payment listing
              $new = '';
              foreach($directory_array as $value) {
                if (substr($value,0,3) == 'cre') $new .= $value . ',';     
              }   
              reset($directory_array);
              foreach($directory_array as $value) {
                if (substr($value,0,6) == 'paypal') $new .= $value . ',';     
              }
              reset($directory_array);
              foreach($directory_array as $value) {
                if ((substr($value,0,3) != 'cre') && (substr($value,0,6) != 'paypal')) $new .= $value . ',';     
              }
              $new = substr($new, 0, strlen($new)-1);
              $directory_array = explode(",", $new);
              // custom sort -eof        

              for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
                $file = $directory_array[$i];
                include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/' . $module_type . '/' . $file);
                include($module_directory . $file);
                $class = substr($file, 0, strrpos($file, '.'));
                if (tep_class_exists($class)) {
                  $module = new $class;
                  if ($module->check() > 0) {
                    if ($module->sort_order > 0) {
                      $modules_wkey[$file] = $module->sort_order;
                    } else {
                      $modules_nokey[] = $file;
                    }
                  }
                  if ((!isset($_GET['module']) || (isset($_GET['module']) && ($_GET['module'] == $class))) && !isset($mInfo)) {
                    $module_info = array('code' => $module->code,
                                         'title' => $module->title,
                                         'description' => $module->description,
                                         'status' => $module->check());

                    $module_keys = $module->keys();
                    $keys_extra = array();
                    for ($j=0, $k=sizeof($module_keys); $j<$k; $j++) {
                      $key_value_query = tep_db_query("select configuration_title, configuration_value, configuration_description, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_key = '" . $module_keys[$j] . "'");
                      $key_value = tep_db_fetch_array($key_value_query);
                      $keys_extra[$module_keys[$j]]['title'] = $key_value['configuration_title'];
                      $keys_extra[$module_keys[$j]]['value'] = $key_value['configuration_value'];
                      $keys_extra[$module_keys[$j]]['description'] = $key_value['configuration_description'];
                      $keys_extra[$module_keys[$j]]['use_function'] = $key_value['use_function'];
                      $keys_extra[$module_keys[$j]]['set_function'] = $key_value['set_function'];
                    }
                    $module_info['keys'] = $keys_extra;
                    $mInfo = new objectInfo($module_info);
                  }
                  if (isset($mInfo) && is_object($mInfo) && ($class == $mInfo->code) ) {
                    if ($module->check() > 0) {
                      echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $class . '&action=edit', $SSL) . '\'">' . "\n";
                    } else {
                      echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
                    }
                  } else {
                    echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $class) . '\'">' . "\n";
                  }
                  ?>
                <?php
                if ($module_type == 'payment') {
                  if (isset($module->pci) && $module->pci == true) {
                    echo '<td width="20" class="dataTableContent" align="center">' . tep_image(DIR_WS_IMAGES . 'icons/pci_shield.gif', 'PCI Compliant') . '</td>';
                  } else {
                    echo '<td width="20" class="dataTableContent" align="center">' . tep_image(DIR_WS_IMAGES . 'icons/x_shield.gif', 'Not PCI Compliant') . '</td>';
                  } 
                }
                ?>                  
                  <td class="dataTableContent"><?php 
				   if($module->description == "Australia Post Express"){
				   	echo $module->description;
				   }else{
				   	echo $module->title;
				   }
				   ?>
				   </td>                
                    <?php 
                    if ($set == 'addons') {
                      echo '<td class="dataTableContent" align="center">' . "\n";
                      if (isset($module->enabled) && $module->enabled == true) echo tep_image(DIR_WS_IMAGES . 'icons/check_mark_small.gif', TEXT_MODULE_INSTALLED);
                    } else {
                      echo '<td class="dataTableContent" align="right">' . "\n";
                      if (is_numeric($module->sort_order)&& isset($module->enabled) && $module->enabled == true) echo $module->sort_order; 
                    }
                    ?>
                  </td>
                  <td class="dataTableContent" align="right"><?php if (isset($mInfo) && is_object($mInfo) && ($class == $mInfo->code) ) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png'); } else { echo '<a href="' . tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $class, $SSL) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                  </tr>
                  <?php
                }
              }
              $installed_modules = array();
              asort($modules_wkey, SORT_REGULAR);
              foreach ($modules_wkey as $file => $sort_order) {
                $installed_modules[] = $file;
              }
              $installed_modules = array_merge($installed_modules, $modules_nokey);
              
              $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = '" . $module_key . "'");
              if (tep_db_num_rows($check_query)) {
                $check = tep_db_fetch_array($check_query);
                if ($check['configuration_value'] != implode(';', $installed_modules)) {
                  tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . implode(';', $installed_modules) . "', last_modified = now() where configuration_key = '" . $module_key . "'");
                }
              } else {
                tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Installed Modules', '" . $module_key . "', '" . implode(';', $installed_modules) . "', 'This is automatically updated. No need to edit.', '6', '0', now())");
              }
              ?>
              </table>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
              <tr>
                <td class="smallText"><?php echo TEXT_MODULE_DIRECTORY . ' ' . $module_directory; ?></td>
              </tr>
          <?php 
          if ($module_type == 'payment') {
            ?>
            <tr>
              <td valign="top" align="center" style="padding:20px 20px 0 0;">
                <!--payment modules-->
                <iframe src="messages.php?s=payment" frameborder="0" width="468" height="120" scrolling="No" allowtransparency="true"></iframe>
                <!--payment modules end-->
              </td>
            </tr>          
            <?php
          }
          ?>              
              
            </table></td>
            <?php
            $heading = array();
            $contents = array();
            if (!isset($mInfo)) {
              $mInfo = new objectInfo(array());
              $mInfo->title = '';
              $mInfo->status = '';
              $mInfo->keys = '';
              $mInfo->description = '';
              $mInfo->code = '';
            }
            switch ($action) {
              case 'edit':
                $keys = '';
                reset($mInfo->keys);
                while (list($key, $value) = each($mInfo->keys)) {
                  $keys .= '<b>' . $value['title'] . '</b><br>' . $value['description'] . '<br>';
                  if ($value['set_function']) {
                    eval('$keys .= ' . $value['set_function'] . "'" . $value['value'] . "', '" . $key . "');");
                  } else {
                    $keys .= tep_draw_input_field('configuration[' . $key . ']', $value['value']);
                  }
                  $keys .= '<br><br>';
                }
                $keys = substr($keys, 0, strrpos($keys, '<br><br>'));
                $heading[] = array('text' => '<b>' . $mInfo->title . '</b>');
                $contents[] = array('text' => '<br>' . $mInfo->description . '<br>');    
                $contents[] = array('text' => tep_draw_form('modules', FILENAME_MODULES, 'set=' . $set . '&module=' . (isset($_GET['module']) ? $_GET['module'] : '') . '&action=save', 'post', '', $SSL));
                $contents[] = array('text' => $keys);
                $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . (isset($_GET['module']) ? $_GET['module'] : ''), 'SSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '</form>');
                break;
              default:
                $heading[] = array('text' => '<b>' . $mInfo->title . '</b>');
                if ($mInfo->status == '1') {
                  $keys = '';
                  reset($mInfo->keys);
                  while (list(, $value) = each($mInfo->keys)) {
                    $keys .= '<b>' . $value['title'] . '</b><br>';
                    if ($value['use_function']) {
                      $use_function = $value['use_function'];
                      if (ereg('->', $use_function)) {
                        $class_method = explode('->', $use_function);
                        if (!isset(${$class_method[0]}) || !is_object(${$class_method[0]})) {
                          include(DIR_WS_CLASSES . $class_method[0] . '.php');
                          ${$class_method[0]} = new $class_method[0]();
                        }
                        $keys .= tep_call_function($class_method[1], $value['value'], ${$class_method[0]});
                      } else {
                        $keys .= tep_call_function($use_function, $value['value']);
                      }
                    } else {
                      $keys .= $value['value'];
                    }
                    $keys .= '<br><br>';
                  }
                  $keys = substr($keys, 0, strrpos($keys, '<br><br>'));
                  $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $mInfo->code . '&action=remove', $SSL) . '">' . tep_image_button('button_module_remove.gif', IMAGE_MODULE_REMOVE) . '</a><a href="' . tep_href_link(FILENAME_MODULES, 'set=' . $set . (isset($_GET['module']) ? '&module=' . $_GET['module'] : '') . '&action=edit', $SSL) . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a>');             
                  $contents[] = array('text' => '<br>' . $mInfo->description);
                  $contents[] = array('text' => '<br>' . $keys);
                } else {
                  if (isset($_GET['module']) && $_GET['module'] == '') {
                    $contents[] = array('text' => 'No module selected.');
                  } else {
                    $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_MODULES, 'set=' . $set . '&module=' . $mInfo->code . '&action=install', $SSL) . '">' . tep_image_button('button_module_install.gif', IMAGE_MODULE_INSTALL) . '</a>');
                    $contents[] = array('text' => '<br>' . $mInfo->description);
                  }
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
      // RCI for global and individual bottom
      echo $cre_RCI->get('modules', 'bottom'); 
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